<?php
/**
 * Arcade Game Feed
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// No direct Access
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Display distributor settings on admin page
 *
 * @version 5.32.0
 * @access  public
 * @return  void
 */
function myarcade_settings_agf() {

  $agf = MyArcade()->get_settings( 'agf' );
  ?>
  <h2 class="trigger"><?php _e("Arcade Game Feed (AGF)", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes Flash games.", 'myarcadeplugin' ), '<a href="http://arcadegamefeed.com" target="_blank">Arcade Games Feed</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_url" value="<?php echo $agf['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Thumbnail Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="agf_thumbnail" id="agf_thumbnail">
              <option value="lgthumb" <?php myarcade_selected($agf['thumbnail'], 'lgthumb'); ?> ><?php _e("100x100", 'myarcadeplugin'); ?></option>
              <option value="thumbnail" <?php myarcade_selected($agf['thumbnail'], 'thumbnail'); ?> ><?php _e("180x135", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a thumbnail size.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="agf_cron_fetch" value="true" <?php myarcade_checked($agf['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_cron_fetch_limit" value="<?php echo $agf['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="agf_cron_publish" value="true" <?php myarcade_checked($agf['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_cron_publish_limit" value="<?php echo $agf['cron_publish_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be published on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

      </table>
      <input class="button button-primary" id="submit" type="submit" name="submit" value="<?php _e("Save Settings", 'myarcadeplugin'); ?>" />
    </div>
  </div>
  <?php
}

/**
 * Retrieve distributor's default settings
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Default settings
 */
function myarcade_default_settings_agf() {
  return array(
    'feed'                => 'http://www.arcadegamefeed.com/feed.php',
    'limit'               => '50',
    'thumbnail'           => 'thumbnail',
    'cron_fetch'          => false,
    'cron_fetch_limit'    => '1',
    'cron_publish'        => false,
    'cron_publish_limit'  => '1',
  );
}

/**
 * Handle distributor settings update
 *
 * @version 5.19.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_agf() {

  myarcade_check_settings_nonce();

  $agf = array();
  $agf['feed'] = (isset($_POST['agf_url'])) ? esc_sql($_POST['agf_url']) : '';
  $agf['limit'] = 50;
  $agf['thumbnail'] = filter_input( INPUT_POST, 'agf_thumbnail' );
  $agf['cron_fetch'] = (isset($_POST['agf_cron_fetch']) ) ? true : false;
  $agf['cron_fetch_limit'] = (isset($_POST['agf_cron_fetch_limit']) ) ? intval($_POST['agf_cron_fetch_limit']) : 1;
  $agf['cron_publish'] = (isset($_POST['agf_cron_publish']) ) ? true : false;
  $agf['cron_publish_limit'] = (isset($_POST['agf_cron_publish_limit']) ) ? intval($_POST['agf_cron_publish_limit']) : 1;
    // Update settings
    update_option('myarcade_agf', $agf);
}

/**
 * Display distributor fetch games options
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_agf() {

  $agf = myarcade_get_fetch_options_agf();
  ?>
  <div class="myarcade_border white hide mabp_680" id="agf">
    Fetch <input type="number" name="agf_limit" value="<?php echo $agf['limit']; ?>" /> games
    <div class="clear"></div>
  </div>
  <?php
}

/**
 * Generate an options array with submitted fetching parameters
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Fetching options
 */
function myarcade_get_fetch_options_agf() {

  // Get distributor settings
  $settings = MyArcade()->get_settings( 'agf' );

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    // Set submitted fetching options
    $settings['limit'] = filter_input( INPUT_POST, 'agf_limit' );
  }

  return $settings;
}

/**
 * Fetch FlashGameDistribution games
 *
 * @version 5.26.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_agf( $args = array() ) {

  $defaults = array(
    'echo'     => false,
    'settings' => array(),
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;
  $add_game = false;

  $agf = myarcade_get_fetch_options_agf();
  $feedcategories = get_option('myarcade_categories');

  // Init settings var's
  if ( !empty($settings) ) {
    $settings = array_merge($agf, $settings);
  }
  else {
    $settings = $agf;
  }

  if ( !isset($settings['method']) ) {
    $settings['method'] = 'latest';
  }

  // Generate Feed URL
  $feed = add_query_arg( array("format" => "json"), trim( $settings['feed'] ) );

  // Check if there is a feed limit. If not, feed all games
  if ( ! empty( $settings['limit'] ) ) {
    $feed = add_query_arg( array( "limit" => intval($settings['limit']) ), $feed );
  }

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  // Fetch games
  $json_games = myarcade_fetch_games( array( 'url' => $feed, 'service' => 'json', 'echo' => $echo) );

  //====================================
  if ( !empty($json_games) ) {
    foreach ($json_games as $game_obj) {

      $game = new stdClass();
      $game->uuid     = $game_obj->id . '_agf';
      // Generate a game tag for this game
      $game->game_tag = md5( $game_obj->id . 'agf' );

      $add_game   = false;

      // Transform some categories
      $categories = explode(',', $game_obj->category);
      $categories_string = 'Other';

      foreach($categories as $gamecat) {

        // Transform some feed categories
        switch ( $gamecat ) {
          case 'Cartoon':
          case 'Coloring': {
            $gamecat = 'Other';
          } break;

          case 'Dressup': {
            $gamecat = 'Dress-Up';
          } break;

          case 'Puzzle': {
            $gamecat = 'Puzzle';
          } break;

          case 'Shooter': {
            $gamecat = 'Shooting';
          } break;
        }

        foreach ($feedcategories as $feedcat) {
          if ( ($feedcat['Name'] == $gamecat) && ($feedcat['Status'] == 'checked') ) {
            $add_game = true;
            $categories_string = $gamecat;
            break 2;
          }
        }
      } // END - Category-Check

      if ( ! $add_game ) {
        continue;
      }

      $thumb_size = $settings['thumbnail'];

      if ( ! empty( $game_obj->$thumb_size ) ) {
        $game->thumbnail_url = esc_sql( $game_obj->$thumb_size );
      }
      else {
        $game->thumbnail_url = esc_sql($game_obj->thumbnail);
      }

      $game->type          = 'agf';
      $game->name          = esc_sql($game_obj->title);
      $game->slug          = myarcade_make_slug($game_obj->title);
      $game->description   = esc_sql($game_obj->description);
      $game->instructions  = esc_sql($game_obj->instructions);
      $game->tags          = esc_sql($game_obj->keywords);
      $game->categs        = $categories_string;
      $game->swf_url       = esc_sql($game_obj->file);
      $game->width         = esc_sql($game_obj->width);
      $game->height        = esc_sql($game_obj->height);

      if ( strtolower( $game_obj->hsapi ) != 'no support' ) {
        $game->leaderboard_enabled =  '1';
      }

      // Add game to the database
      if ( myarcade_add_fetched_game( $game, $args ) ) {
        $new_games++;
      }
    }
  }

  // Show, how many games have been fetched
  myarcade_fetched_message( $new_games, $echo );
}

/**
 * Return game embed method
 *
 * @version 5.18.0
 * @since   5.18.0
 * @access  public
 * @return  string Embed Method
 */
function myarcade_embedtype_agf() {
  return 'flash';
}
?>