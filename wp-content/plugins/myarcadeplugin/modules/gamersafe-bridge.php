<?php
/**
 * GamerSafe briddge module for MyArcadePlugin Pro
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

/*$_POST["salt"] = "myarcadeplugin";
$_POST["userID"] = "1";
$_POST["sessionID"] = "ce56c3f3001fb4254b50cdcd7c0857c7";
$_POST["message"] = "scoreboard_entry";
$_POST["display_ascending"] = "false";
$_POST["display_time"] = "false";
$_POST["extra"] = "DE";
$_POST["gameID"] = "179";
$_POST["params"] = "display_ascending, display_time, extra, gameID, score, scoreboard_id, scoreboard_title, unregistered_name, username";
$_POST["score"] = "34728";
$_POST["scoreboard_id"] = "818";
$_POST["scoreboard_title"] = "HighScores";
$_POST["unregistered_name"] = "nbnhjhkkjhkjh";
$_POST["username"] = "";
$_POST["hash"] = "f19748d6aa50ad9712503a9d9842a91debcd1c97";*/


// https://www.gamersafe.com/developer/data_bridge.php

$message = filter_input( INPUT_POST, 'message' );

// Locate WordPress root folder
$root = dirname( dirname( dirname( dirname( dirname(__FILE__) ) ) ) );

if ( file_exists($root . '/wp-load.php') ) {
  define('MYARCADE_DOING_ACTION', true);
  require_once($root . '/wp-load.php');
}
else {
  // WordPress not found
  die();
}

// Get bridge parameters
$user_id = filter_input( INPUT_POST, 'userID', FILTER_SANITIZE_NUMBER_INT );
$post_id = filter_input( INPUT_POST, 'gameID' );
$session = filter_input( INPUT_POST, 'sessionID' );
$message = filter_input( INPUT_POST, 'message' );
$hash    = filter_input( INPUT_POST, 'hash' );
$salt    = 'myarcadeplugin';

if ( ! $user_id || ! $post_id || ! $session || ! $message || ! $hash ) {
  // Invalid bridge call
  die();
}

// Check params
$extra = filter_input( INPUT_POST, 'extra' );
$score = filter_input( INPUT_POST, 'score' );
$scoreboard_id = filter_input( INPUT_POST, 'scoreboard_id' );
$unregistered_user = filter_input( INPUT_POST, 'unregistered_user' );
$params = filter_input( INPUT_POST, 'params' );
$params_array = explode( ',', $params );
$params_array = array_map( 'trim', $params_array );

// Generate the hashstring
$hashphrase  = $salt . $user_id . $session . $message;

foreach ( $params_array as $key => $value) {
  $hashphrase .= (string) filter_input( INPUT_POST, $value );
}

$hash_generated = sha1( $hashphrase );

// Check hash
if ( $hash != $hash_generated ) {
  // Hash mismatch
  die();
}

switch ( $message ) {

  case 'scoreboard_entry' : {
    // sent whenever a player records a new score.
      // score -  the value to be recorded
      // scoreboard_id - games can have multiple scoreboards, and this is to differentiate them
      // extra  - a string that can be passed in with a score, specified by the game or the user
      // unregistered_user - in the case of a user without a GamerSafe account, this field will contain a user-supplied name
      // display_ascending - in some cases, lower is better. you should sort the scores upward.
      // display_time - true when the score represents a time, in microseconds

    $game_tag = get_post_meta( $post_id, 'mabp_game_tag', true );

    if ( ! $game_tag ) {
      die();
    }

    $score_order  = get_post_meta( $post_id, 'mabp_score_order', true );

    if ( ! $score_order ) {
      $display_ascending = filter_input( INPUT_POST, 'display_ascending' );

      if ( "true" == $display_ascending ) {
        $score_order = 'ASC';
      }
      else {
        $score_order = 'DESC';
      }

      // Update the post meta
      update_post_meta($post_id, 'mabp_score_order', $score_order );
    }

    $score_array = array(
      'session'   => $session,
      'date'      => date('Y-m-d'),
      'datatype'  => 'number',
      'game_tag'  => $game_tag,
      'user_id'   => $user_id,
      'score'     => $score,
      'sortorder' => $score_order,
    );

    myarcade_handle_score( $score_array );
  } break;

  case 'achievement_info' : {
    // letting you know that an achievement exists. you can ignore it if you like.
  } break;

  case 'achievement_awarded' : {
    // sent when a player attains a new achievement.
    // ach_id - a numeric ID for the achievement
    // title - the name of the achievement
    // description - a longer description of the achievement
    // point_value - the value, in GamerPoints of the achievement
    // icon_url - the URL of an icon used to represent the achievement

    $game_tag = get_post_meta( $post_id, 'mabp_game_tag', true );

    if ( ! $game_tag ) {
      die();
    }

    $achievement = array(
      'date'      => date('Y-m-d'),
      'game_tag'  => $game_tag,
      'user_id'   => $user_id,
      'score'     => filter_input( INPUT_POST, 'ach_id' ),
      'name'      => filter_input( INPUT_POST, 'title' ),
      'description' => filter_input( INPUT_POST, 'description' ),
      'thumbnail' => filter_input( INPUT_POST, 'icon_url' ),
    );

    global $wpdb;

    // Avoid duplicate achivements
    $achievement_id = $wpdb->get_var( "SELECT id FROM ".$wpdb->prefix.'myarcademedals'." WHERE
      game_tag = '".$game_tag."' AND
      user_id = '".$user_id."'AND
      score = '".$achievement['score']."' LIMIT 1"
    );

    if ( $achievement_id ) {
      // Duplicate Achivement
      die();
    }

    // Download achivement thumbnail
    $file_info = pathinfo( $achievement['thumbnail'] );
    $file_temp = myarcade_get_file( $achievement['thumbnail'] );
    $upload_dir_specific = myarcade_get_folder_path( $file_info['filename'], 'custom' );
    $file_name = wp_unique_filename( $upload_dir_specific['thumbsdir'], $file_info['basename'] );
    $result = file_put_contents( $upload_dir_specific['thumbsdir'] . $file_name, $file_temp['response'] );

    if ( $result == true) {
      $achievement['thumbnail'] = $upload_dir_specific['thumbsurl'] . $file_name;
    }

    do_action( 'myarcade_new_medal', $achievement );

    $wpdb->insert( $wpdb->prefix.'myarcademedals', $achievement );
  } break;

  case 'login' : {
    //  the user has just logged in to gamersafe. Attach this info to your own accounts if you like.
      // gs_account_id -  a numeric ID for this user.
      // username - the user's GamerSafe username.
  } break;

  case 'logout' : {
    // the user has just logged out of GamerSafe. Only really useful if you want to use GS as your portal's account system.
  } break;

  default : {
    // Unknown message
  } break;
}

$redirect = get_permalink( $post_id );
header( 'Location: ' . $redirect );
die();
?>