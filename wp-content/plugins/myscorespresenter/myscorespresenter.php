<?php
/**
 * Plugin Name:  MyScoresPresenter
 * Plugin URI:   http://myarcadeplugin.com/documentation/myscorespresenter/
 * Description:  Shows scores and leaderboards on your blog. Requires MyArcadePlugin Pro
 * Version:      4.2.0
 * Author:       Daniel Bakovic
 * Author URI:   http://myarcadeplugin.com/
 *
 * Requires at least: 4.0
 * Tested up to: 4.4
 */


define('MYSCORE_VERSION', '4.2.0');

/**
 * Register admin menu
 */
function myscore_admin_menu() {
  if ( defined('MYARCADE_VERSION') ) {
    add_submenu_page('myarcade_admin.php',
       __('MyScoresPresenter',  'myscorespresenter'),
      __('MyScoresPresenter',  'myscorespresenter'),
      'manage_options',  basename(__FILE__), 'myscore_options_page');
  }
  else {
    add_options_page(
      __('MyScoresPresenter',  'myscorespresenter') ,
      __('MyScoresPresenter',  'myscorespresenter'),
      'manage_options' ,
      basename(__FILE__),
      'myscore_options_page');
  }
}
add_action('admin_menu', 'myscore_admin_menu');

/**
 * Display the optinos page
 */
function myscore_options_page() {
  include( 'includes/myscore-options.php' );
  myscore_options();
}

/**
 * Init MyScoresPresenter
 */
function myscore_init() {

  load_plugin_textdomain( 'myscorespresenter', false, dirname( plugin_basename( __FILE__ ) ) .'/languages' );

  if ( ! is_admin() ) {
    if ( get_option('myscore_include_css') ) {
      wp_register_style('MyScoresPresenter', WP_PLUGIN_URL . '/myscorespresenter/myscorespresenter.css');
      wp_enqueue_style( 'MyScoresPresenter');
    }
  }
}
add_action ( 'init', 'myscore_init' );

/**
 * Init MyScoresPresenter Widgets
 */
function myscore_init_widgets() {

  include_once( 'includes/widgets/widget-latest-scores.php' );
  include_once( 'includes/widgets/widget-todays-scores.php' );
  include_once( 'includes/widgets/widget-game-scores.php' );
  include_once( 'includes/widgets/widget-user-achievements.php' );
  include_once( 'includes/widgets/widget-latest-achievements.php' );
  include_once( 'includes/widgets/widget-leaderboard.php' );
  include_once( 'includes/widgets/widget-most-active-players.php' );

  // Add  this widget, only if BuddyPress is installed
  if ( function_exists('bp_displayed_user_id') ) {
    include_once( 'includes/widgets/widget-user-scores.php' );
  }

  do_action( 'myscore_init_widgets' );
}
add_action('widgets_init', 'myscore_init_widgets');

/**
 * Clean up transients when new scores are submitted
 */
function myscore_clean_transients( $param = false ) {

  //if ( isset($param['game_tag']) )
    //delete_transient( 'myscore_transient_game_scores_' . $param['game_tag'] );

  delete_transient( 'myscore_transient_latest_scores' );
  delete_transient( 'myscore_transient_todays_scores' );
}
add_action( 'myarcade_new_score', 'myscore_clean_transients' );

function  myscore_clean_transients_players() {
  delete_transient( 'myscore_transient_most_active_players' );
}
add_action('myarcade_game_play', 'myscore_clean_transients_players' );

function myscore_clean_transients_leaderbaord( $new_score, $old_score) {
  delete_transient( 'myscore_transient_leaderboard' );
}
add_action( 'myarcade_new_highscore', 'myscore_clean_transients_leaderbaord', 10, 2);

/**
 * Get MyArcadePlugin table name
 *
 * @global mixed $wpdb
 * @param string $table
 * @return string Table Name
 */
function myscore_get_table_name( $table ) {
  global $wpdb;

  $tables = array(
      'game'      => 'myarcadegames',
      'score'     => 'myarcadescores',
      'highscore' => 'myarcadehighscores',
      'user'      => 'myarcadeuser',
      'medal'     => 'myarcademedals'
  );

  // MyArcadePlugin 6+ compatibility
  if ( function_exists( 'MyArcade' ) ) {
    $table_name = $table . '_table';
    return MyArcade()->$table_name;
  }
  else {
    return $wpdb->prefix.$tables[ $table ];
  }
}

/**
 * Retrieve the game_tag. Required for Mochi games.
 *
 * @global type $mypostid
 * @param int Post ID (optional)
 * @return string Game Tag
 */
function myscore_get_game_tag( $post_id = false ) {
  global $wpdb, $mypostid;

  if ( !$post_id ) {
    $post_id = $mypostid;
  }

  $game_tag = get_post_meta( $post_id, 'mabp_game_tag', true );

  if ( !$game_tag ) {
    if ( $wpdb->get_col("SHOW columns from ".myscore_get_table_name('game')."` WHERE field='postid'") ) {
      $game_tag = $wpdb->get_var("SELECT game_tag FROM ".myscore_get_table_name('game')." WHERE postid = '".$post_id."'");
    }
  }

  return $game_tag;
}

/**
 * Retrieve the score order of the current game
 *
 * @param int Post ID
 * @return string ASC / DESC
 */
/*function myscore_get_score_order( $post_id ) {
  global $wpdb;

  $order = get_post_meta( $post_id, 'mabp_score_order', true );

  if ( !$order ) {
    // Compatibility wih older MyArcadePlugin versions
    if ( $wpdb->get_col("SHOW columns from ".myscore_get_table_name('score')."` WHERE field='scoreorder'") ) {
      $game_tag = myscore_get_game_tag( $post_id );
      $order = $wpdb->get_var( "SELECT scoreorder FROM ".myscore_get_table_name('score')." WHERE game_tag = '".$game_tag."'" );
    }
    else {
      $order = DESC;
    }
  }

  return $order;
}*/

/**
 * Retrieve the post id by game_tag
 *
 * @param string Game tag
 * @return int post ID
 */
function myscore_get_post_id_by_tag( $game_tag ) {
  global $wpdb;

  $post_id = $wpdb->get_var("
    SELECT m.post_id FROM ".$wpdb->postmeta." AS m
    INNER JOIN ".$wpdb->posts." AS p ON m.post_id = p.ID
    WHERE m.meta_key = 'mabp_game_tag'
    AND m.meta_value = '".$game_tag."'
    AND p.post_status = 'publish'
    LIMIT 1
  ");

  // Compatibility with old MyArcadePlugin versions
  if ( ! $post_id ) {
    $post_id = $wpdb->get_var("SELECT postid FROM ".myscore_get_table_name('game')." WHERE game_tag = '".$game_tag."' LIMIT 1");
  }

  return $post_id;
}

/**
 * Retrieves the score order of a game
 * 
 * @param string $game_tag
 * @return string DESC|ASC| or FALSE on error
 */
function myscore_get_score_order( $game_tag = false ) {
  global $mypostid;
  
  if ( $mypostid ) {
    // Try to get the order by post_id
    $order = get_post_meta( $mypostid, 'mabp_score_order', true );
    
    if ( $order ) {
      return $order;
    }
  }
  
  if ( $game_tag ) {
    $post_id = myscore_get_post_id_by_tag($game_tag);
    
    if ( $post_id ) {
      $order = get_post_meta( $mypostid, 'mabp_score_order', true );
      
      if ( $order ) {
        return $order;
      }
    }
  }
  
  return "DESC";
}

/**
 * Retrieve post thumbnail url
 *
 * @param int Post ID
 * @return string Thumbnail URL
 */
function myscore_get_thumbnail_url( $post_id ) {
  if ( has_post_thumbnail( $post_id ) ) {
    $thumb_id = get_post_thumbnail_id( $post_id );
    return wp_get_attachment_thumb_url( $thumb_id );
  }
  else {
    return get_post_meta( $post_id, 'mabp_thumbnail_url', true );
  }
}

/**
 * Integrate MyScoresPresenter with BuddyPress
 *
 * @return MyScore_BuddyPress_Component
 */
function myscore_buddypress() {
  if ( function_exists( 'buddypress' ) && buddypress() && ! buddypress()->maintenance_mode && bp_is_active( 'xprofile' ) ) {
    require_once( 'includes/myscore-buddypress.php' );
    return new MyScore_BuddyPress_Component();
  }
}
add_action( 'bp_setup_components', 'myscore_buddypress' );

/*
 * Show today's scores as list of elements orderd by ID
 *
 * @param int Limit
 * @return void
 */
function myscore_get_todays_scores( $limit = 10 ) {
  global $wpdb;

  $output = '';

  $results = get_transient( 'myscore_transient_todays_scores' );

  if ( ! $results ) {
    $results = $wpdb->get_results("SELECT * FROM ".myscore_get_table_name('score')." WHERE date = curdate() ORDER BY id DESC LIMIT ".$limit);
    set_transient( 'myscore_transient_todays_scores', $results, 3600*24 );
  }

  if ( !empty($results) ) {
    $template = get_option('myscore_template_today');
    foreach ($results as $result) {
      $output .= '<li>'.myscore_expand_template($template, $result).'<div class="clear"></div></li>' . "\n";
    }
  }
  else {
    $output = "<li>" . __("There are no scores, yet! Play this game to become the <strong>best</strong> player!", 'myscorespresenter') . "</li>";
  }

  echo $output;
}

/**
 * Shows todays game scores
 *
 * @global type $wpdb
 * @param type $limit
 */
function myscore_get_todays_scores_game( $limit = 10 ) {
  global $wpdb;

  $output = '';
  $results = false;

  $game_tag = myscore_get_game_tag();

  if ( $game_tag ) {
    $order = myscore_get_score_order( $game_tag );
    
    if ( $order ) { 
      $results = $wpdb->get_results("SELECT * FROM ".myscore_get_table_name('score')." WHERE date = curdate() AND game_tag = '".$game_tag."' ORDER BY id ".$order." LIMIT ".$limit);
    }
  }

  if ( !empty($results) ) {
    $template = get_option('myscore_template_game');
    foreach ($results as $result) {
      $output .= '<li>'.myscore_expand_template($template, $result).'<div class="clear"></div></li>' . "\n";
    }
  }
  else {
    $output = "<li>" . __("There are no score, yet! Play this game to be the <strong>best</strong> player!", 'myscorespresenter') . "</li>";
  }

  echo $output;
}

/*
 * @brief: Shows todays scores as list elements orderd by ID
 */
function myscore_get_weekly_scores($limit = 10) {
  global $wpdb;

  $output = '';
  $template = get_option('myscore_template_today');

  $game_tag = myscore_get_game_tag();
  $results = false;

  if ( $game_tag ) {
    $results = $wpdb->get_results("SELECT * FROM ".myscore_get_table_name('score')." WHERE game_tag = '".$game_tag."' AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) <=  date ORDER BY id DESC LIMIT ".$limit);
  }

  if (!empty($results)) {
    foreach ($results as $result) {
      $output .= '<li>'.myscore_expand_template($template, $result).'<div class="clear"></div></li>' . "\n";
    }
  }
  else {
    $output = "<li>" . __("There are no weekly scores! Play this game to be the <strong>best</strong> player!", 'myscorespresenter') . "</li>";
  }

  echo $output;
}

/*
 * @brief: Shows most active players as a list of elements ordered by plays count
 */
function myscore_get_most_active_users($limit = 10) {
  global $wpdb;

  $output = '';
  $template = get_option('myscore_template_muser');

  $results = get_transient( 'myscore_transient_most_active_players' );

  if ( ! $results ) {
    $results = $wpdb->get_results("SELECT user_id, plays FROM ".myscore_get_table_name('user')." ORDER BY plays DESC LIMIT ".$limit);

    set_transient( 'myscore_transient_most_active_players', $results, 3600*24 );
  }

  if (!empty($results)) {
    foreach ($results as $result) {
      $output .= '<li>'.myscore_expand_template($template, $result).'<div class="clear"></div></li>' . "\n";
    }
  }

  echo $output;
}


function myscore_get_leaderboard() {
  global $wpdb;

  $results = get_transient( 'myscore_transient_leaderboard' );

  if ( !$results ) {
    $results = $wpdb->get_results("SELECT user_id, COUNT(*) as highscores FROM ".myscore_get_table_name('highscore')." GROUP BY user_id ORDER BY highscores DESC LIMIT 3");

    set_transient( 'myscore_transient_leaderboard', $results, 3600*24 );
  }

  if (!empty($results)) {
    $count = 0;

    foreach ($results as $result) {
      $count++;

      $user_info = get_userdata($result->user_id);

      if ( $count <= 3 ) {

        if ( $count == 1 ) {
          ?><li><div class="topplayer"><?php
        }
        ?>

        <div class="playerinfo <?php if ($count==3) { echo 'last'; } ?>">
          <?php echo get_avatar($result->user_id, 50); ?>
          <?php echo myscore_get_user_link($result->user_id); ?>
          <center><div class="award-<?php echo $count; ?>"></div></center>
          <?php echo $result->highscores; ?>
        </div>
        <?php
        if ( $count == 3) {
          ?>
          </div>
          <div style="clear:both;padding-bottom:10px"></div>
        </li>
        <?php
        }
      }
      else {
        ?>
        <li>
          <?php echo sprintf( __('%s. %s with %s Highscores', 'myscorespresenter'), $count, $user_info->user_nicename, $result->highscores ); ?>
        </li>
        <?php
      }
    }
  }
}

/*
 * @brief: Shows latest scores as list elements orderd by ID
 */
function myscore_get_latest_scores($limit = 10) {
  global $wpdb;

  $output = '';
  $template = get_option('myscore_template_latest');

  $results = get_transient( 'myscore_transient_latest_scores' );

  if( !$results ) {
    $results = $wpdb->get_results("SELECT * FROM ".myscore_get_table_name('score')." ORDER BY id DESC LIMIT ".$limit);

    set_transient( 'myscore_transient_latest_scores', $results, 3600*24 );
  }

  if ( !empty( $results ) ) {
    foreach ($results as $result) {
      $output .= '<li>'.myscore_expand_template($template, $result).'<div class="clear"></div></li>' . "\n";
    }
  }

  echo $output;
}

/*
 * @brief: Shows scores of a single game as list elements orderd by highscore
 */
function myscore_get_game_scores($limit = 10) {
  global $wpdb;

  $game_tag = myscore_get_game_tag();

  $output = '';
  $template = get_option('myscore_template_game');

  $results = false;

  if ( $game_tag ) {
    $order = myscore_get_score_order( $game_tag );
    
    if ( $order ) {    
      $results = $wpdb->get_results("SELECT * FROM ".myscore_get_table_name('score')." WHERE game_tag = '".$game_tag."' ORDER BY score+0 ".$order." LIMIT ".$limit);
    }
  }

  if (!empty($results)) {
    $rank = 1;
    foreach ($results as $result) {
      $output .= '<li class="rank_'.$rank.'">'.myscore_expand_template($template, $result).'<div class="clear"></div></li>' . "\n";
      $rank++;
    }
  }
  else {
    $output = "<li>" . __("There are no best players! Play this game to be the <strong>best</strong> player!", 'myscorespresenter') . "</li>";
  }

  echo $output;
}


/*
 * @brief: Show last scores of an user on the sidebar
 */
function myscore_show_users_scores($user_id, $limit = 10) {
  global $wpdb;

  // Get Scores Of User
  $scores = $wpdb->get_results("SELECT * FROM ".myscore_get_table_name('score')." WHERE user_id = '".$user_id."' ORDER BY id DESC LIMIT ".$limit);

  if ($scores) {
    $output = '<p>';
    foreach ($scores as $score) {

      $post_id = $wpdb->get_var("
        SELECT m.post_id FROM ".$wpdb->postmeta." AS m
        INNER JOIN ".$wpdb->posts." AS p ON m.post_id = p.ID
        WHERE m.meta_key = 'mabp_game_tag'
        AND m.meta_value = '".$score->game_tag."'
        AND p.post_status = 'publish'
        LIMIT 1
      ");

      if ( $post_id ) {
        $title = get_the_title( $post_id );
        $gamelink = '<a href="'.get_permalink( $post_id ).'" title ="'.esc_attr( $title ).'">'.$title.'</a>';
        $output .= sprintf( __("%s - Scored: <strong>%s</strong> on %s", 'myscorespresenter'), $score->date, $score->score, $gamelink) . "<br />";
      }
    }

    $output .= '</p>';
  }
  else {
    $output = "<p>" . __("The user has not played any games, yet.", 'myscorespresenter') . "</p>";
  }

  echo $output;
}

/**
 * Get high scores of a user per game
 *
 * @version 4.0.0
 * @access  public
 * @param   integer $user_id
 * @param   integer $limit
 */
function myscore_get_users_highscore_per_game( $user_id, $limit = 10 ) {
  global $wpdb;

  return $wpdb->get_results( "SELECT * FROM ".myscore_get_table_name('highscore')." WHERE user_id = '".$user_id."' ORDER BY id DESC LIMIT "  . $limit );
}


/**
 * Get latest user scores ordered by ID DESC
 *
 * @version 4.0.0
 * @access  public
 * @param   integer $user_id
 * @param   integer $limit
 */
function myscore_get_users_latest_scores( $user_id, $limit = 10 ) {
  global $wpdb;

  return $wpdb->get_results( "SELECT * FROM ".myscore_get_table_name('score')." WHERE user_id = '".$user_id."' ORDER BY id DESC LIMIT " . $limit );
}

/**
 * Get latest user medals
 * @version 4.0.0
 * @access  public
 * @param   integer $user_id
 * @param   integer $limit
 */
function myscore_get_users_medals( $user_id, $limit = 10 ) {
  global $wpdb;

  return $wpdb->get_results( "SELECT * FROM ".myscore_get_table_name('medal')." WHERE user_id = '".$user_id."' ORDER BY id DESC LIMIT " . $limit );
}

/**
 * @brief: Shows achieved medals of the displayed game
 */
function myscore_get_game_medals ( $post_id = false ) {
  global $post, $wpdb;

  $user_ID = get_current_user_id();

  if ( ! $post_id && ! empty( $post->ID ) ) {
    $post_id = $post->ID;
  }
  
  if ( ! $post_id || ! $user_ID ) {
    return false;
  }

  $game_tag = myscore_get_game_tag( $post_id );

  $achievements = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}myarcademedals WHERE user_id = '{$user_ID}' AND game_tag = '{$game_tag}'" );

  if ( ! $achievements ) {
    return false;
  }



  var_dump($achievements);

  //$output = '';
  //$template = get_option('myscore_template_game_medals');

  //echo stripslashes($output);
}

function myscore_get_user_link($user_id, $chars = false) {

  $user = get_userdata($user_id);

  $username = $user->display_name;

  if ($chars) {
    if (strlen($username) > $chars) {
      $username = mb_substr($username, 0, $chars). '..';
    }
  }

  // Check if mingle is installed
  if ( defined('MNGL_PLUGIN_NAME') ) {
    // Get mingle options
    $mingl_options = get_option('mngl_options');
    // Get the permalink of the user's profile
    $permalink = get_permalink($mingl_options->profile_page_id);
    // Correct the profile link...
    $param_char = ((preg_match("#\?#",$permalink))?'&':'?');
    $link = $permalink.$param_char.'u='.$user->display_name;

    // Generate a link to users profile
    $userlink = '<a href="'.$link.'" title="'.$user->display_name.'" >'.$username.'</a>';
  }
  else {
    // Check if BuddyPress is installed and get the BuddyPress user link...
    if ( defined('BP_VERSION') ) {
      $link = bp_core_get_user_domain( $user_id );
      $userlink = '<a href="'.$link.'" title="'.$user->display_name.'" >'.$username.'</a>';
    }
    else {
      // No Link. Show only the user name
      $userlink = $username;
    }
  }

  return $userlink;
}

/*
 * @brief Expand Score data
 */
function myscore_expand_template($template, $score_result) {

    $value = $template;

    if (strpos($template, '%USERNAME%') !== false) {
      // Check the UserID
      if ( !empty($score_result->user_id) ) {
        $userlink = myscore_get_user_link($score_result->user_id);

        $value = str_replace("%USERNAME%", $userlink, $value);
      }
      else {
        // UserID is empty
        // Check if this is a guest
        if ( !empty($score_result->user_name) ) {
          $value = str_replace("%USERNAME%", $score_result->user_name, $value);
        }
      }
    }

    if (strpos($template, '%AVATAR%') !== false) {
      $value = str_replace( "%AVATAR%", get_avatar( $score_result->user_id, apply_filters( 'myscores_avatar_size', 30 ) ), $value);
    }


    if( isset($score_result->score) ) {
      $value = str_replace("%SCORE%",     $score_result->score, $value);
    }
    
    if( isset($score_result->name) ) {
      $value = str_replace("%GAMENAME%",  $game->name, $value);
    }
    
    if( isset($score_result->plays) ) {
      $value = str_replace("%GAMEPLAYS%", $score_result->plays, $value);
    }
    
    if( isset($score_result->date) ) {
      $value = str_replace("%DATE%",    $score_result->date, $value);
    }

    if( isset($score_result->game_tag) ) {
      $post_id = myscore_get_post_id_by_tag( $score_result->game_tag );

      if ( $post_id ) {
        if ( strpos($template, '%GAME%') !== false ) {
          $title = get_the_title( $post_id );
          $gamelink = '<a href="'.get_permalink($post_id).'" title ="'.esc_attr( $title ).'">'.$title.'</a>';
          $value = str_replace("%GAME%", $gamelink, $value);
        }

        if ( strpos($template, '%GAMEIMAGEURL%') !== false ) {
          // Get Post Thumbnail URL
          $image = myscore_get_thumbnail_url( $post_id );
          $value = str_replace("%GAMEIMAGEURL%", $image, $value);
        }
      }
    }

    return $value;
}


/*
 * @brief: Check if a game has leaderboard enabled
 *         Use this function only inside the loop
 */
function myscore_check_leaderboard() {
  global $wpdb, $post;

  $lb_enable = get_post_meta($post->ID, 'mabp_leaderboard', true);

  if ( !isset($lb_enable) ) {
    $lb_enable = $wpdb->get_var("SELECT leaderboard_enabled FROM ".myscore_get_table_name('game')." WHERE postid = '".$post->ID."' LIMIT 1");
  }

  if ($lb_enable) {
    $result = true;
  }
  else {
    $result = false;
  }

  return $result;
}


/*
 * @brief: Shows the global scores of a single game
 */
function myscore_get_global_game_scores() {
  global $wpdb, $mypostid;

  $game_slug = $wpdb->get_var("SELECT slug FROM ".myscore_get_table_name('game')." WHERE postid = '".$mypostid."'");
  $game_type = $wpdb->get_var("SELECT game_type FROM ".myscore_get_table_name('game')." WHERE postid = '".$mypostid."'");
  //$global_scores = $wpdb->get_var("SELECT global_scores FROM ".MYARCADE_SETTINGS_TABLE."");

  $global_scores = true;

  if ( ($global_scores == 'true') && ($game_type != 'heyzap') ) {
    $output  = '<div id="leaderboard_widget"></div>';
    //$output .= '<script src="http://xs.mochiads.com/static/pub/swf/leaderboard.js" type="text/javascript"></script>';
    $output .= '<script type="text/javascript">';
    $output .= 'var options = {game: "'.$game_slug.'", width: 335, height: 500, id: "leaderboard_widget"};';
    // Select a specific leaderboard for a game - optional (if not included, a selectable list will be used)
    //options.leaderboard = "Level 1";
    // your publisher ID - optional (if not included, ALL scores from all sites will be shown)
    //$output .= 'options.partnerID = "'.$publisher_id.'";';
    // username link prefix - optional
    //options.profileURL = "http://www.example.com/profile/${name}";
    // leaderboard background color - optional
    $output .= 'options.backgroundColor = "#DFE3E6";';

    // Show the widget!
    //options.defaultTab = "alltime";
    // Default to the all time tab!
    $output .= 'Mochi.showLeaderboardWidget(options);';
    $output .= '</script>';

    echo $output;
  }
}


/**
 * MyScoresPresenter Install Funciton
 */
function myscore_install() {

  if ( !get_option('myscore_template_today') ) {
    add_option('myscore_template_today',       '%USERNAME% ' . __("on",'myscorespresenter') .' %GAME% (%SCORE%)');
    add_option('myscore_template_latest',      '<strong>%USERNAME%</strong> ' . __("on",'myscorespresenter') .' %GAME%');
    add_option('myscore_template_muser',       '<strong>%USERNAME%</strong> - %GAMEPLAYS% ' . __("plays". 'myscorespresenter') );
    add_option('myscore_template_game',        '<strong>%USERNAME%</strong> - %SCORE%');
  }

  add_option('myscore_include_css', true);
}
register_activation_hook  ( __FILE__, 'myscore_install' );