<?php
/**
 * Automated fetching and publishing
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// No direct Access
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Automated game fetching
 *
 * @version 5.19.0
 * @return  void
 */
function myarcade_cron_fetching() {

  if ( myarcade_schluessel() ) {

    // Build the cron array
    $crons = array();

    $distributors = MyArcade()->distributors();

    foreach($distributors as $key => $name) {
      $option = get_option('myarcade_'.$key);
      if ($option && isset($option['cron_fetch']) && ($option['cron_fetch'] == true) ) {
        $limit = (!empty($option['cron_fetch_limit'])) ? intval($option['cron_fetch_limit']) : 1;
        $crons[$key] = array( 'echo' => false, 'settings' => array('limit' => $limit) );
      }
    }

    if ( count($crons) > 0 ) {
      foreach($crons as $key => $args) {
        $fetch_function = 'myarcade_feed_'.$key;

        // Get distributor integration file
        MyArcade()->load_distributor( $key );

        if ( function_exists( $fetch_function ) ) {
          $fetch_function( $args );
        }
      }
    }
  }
}
add_action('cron_fetching', 'myarcade_cron_fetching');

/**
 * Automated game publishing
 *
 * @version 5.21.2
 * @return  void
 */
function myarcade_cron_publishing() {
  global $wpdb;

  if ( ! myarcade_schluessel() ) {
    return;
  }

  // Build the cron array
  $crons = array();

  $distributors      = MyArcade()->distributors();
  $custom_game_types = MyArcade()->custom_game_types();

  // Game distributors
  foreach($distributors as $key => $name) {
    $option = get_option('myarcade_'.$key);
    if ($option && isset($option['cron_publish']) && ($option['cron_publish'] == true) ) {
      $limit = (!empty($option['cron_publish_limit'])) ? intval($option['cron_publish_limit']) : 1;
      $crons[$key] = $limit;
    }
  }

  $general = get_option('myarcade_general');

  // Custom game types
  if ( $general['cron_publish_limit'] > 0 ) {
    foreach ( $custom_game_types as $key => $name ) {
      $limit = (!empty( $general['cron_publish_limit'] ) ) ? intval($general['cron_publish_limit']) : 1;
      $crons[$key] = $limit;
    }
  }

  // Proceed with game publishing
  if ( count($crons) > 0 ) {
    // go trough all distributors
    foreach($crons as $type => $limit) {
      // publish games for each distributor
      for($x=0; $x<$limit; $x++) {
        // Get game id
        $game_id = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix . 'myarcadegames'." WHERE game_type = '".$type."' AND status = 'new' ORDER BY id LIMIT 1");
        if ( $game_id ) {
          myarcade_add_games_to_blog( array('game_id' => $game_id, 'post_status' => 'publish', 'echo' => false) );
        }
      }
    }
  }
}
add_action('cron_publishing', 'myarcade_cron_publishing');
?>