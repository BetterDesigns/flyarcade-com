<?php
/**
 * FreeGamesForYourWebsite
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
function myarcade_settings_fog() {
  $fog = MyArcade()->get_settings( 'fog' );
  ?>
  <h2 class="trigger"><?php _e("FreeGamesForYourWebsite (FOG)", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <p>
              <i>
                <?php printf( __( "%s distributes Flash and Unity3D games.", 'myarcadeplugin' ), '<a href="http://www.freegamesforyourwebsite.com" target="_blank">FreeGamesForYourWebsite</a>' ); ?>
              </i>
            </p>
          </td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="fogurl" value="<?php echo $fog['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Thumbnail Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="fogthumbsize" id="fogthumbsize">
              <option value="small" <?php myarcade_selected($fog['thumbsize'], 'small'); ?> ><?php _e("Small (100x100)", 'myarcadeplugin'); ?></option>
              <option value="medium" <?php myarcade_selected($fog['thumbsize'], 'medium'); ?> ><?php _e("Medium (180x135)", 'myarcadeplugin'); ?></option>
              <option value="large" <?php myarcade_selected($fog['thumbsize'], 'large'); ?> ><?php _e("Large (300x300)", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select the size of the thumbnails that should be used for games from FreeGamesForYourWebsite. Default size is small (100x100).", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Use Large Thumbnails as Screenshots", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="fogscreen" value="true" <?php myarcade_checked($fog['screenshot'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Check this if you want to use large thumbnails (300x300px) from the feed as game screenshots", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Game Categories", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="fogtag" id="fogtag">
              <option value="all" <?php myarcade_selected($fog['tag'], 'all'); ?>>All Categories</option>
              <option value="3D" <?php myarcade_selected($fog['tag'], '3D'); ?>><?php _e('3D Games', 'myarcadeplugin'); ?></option>
              <option value="Adventure" <?php myarcade_selected($fog['tag'], 'Adventure'); ?>><?php _e('Adventure Games', 'myarcadeplugin'); ?></option>
              <option value="Defense" <?php myarcade_selected($fog['tag'], 'Defense'); ?>><?php _e('Defense Games', 'myarcadeplugin'); ?></option>
              <option value="Driving" <?php myarcade_selected($fog['tag'], 'Driving'); ?>><?php _e('Driving Games', 'myarcadeplugin'); ?></option>
              <option value="Flying" <?php myarcade_selected($fog['tag'], 'Flying'); ?>><?php _e('Flying Games', 'myarcadeplugin'); ?></option>
              <option value="Multiplayer" <?php myarcade_selected($fog['tag'], 'Multiplayer'); ?>><?php _e('Multiplayer Games', 'myarcadeplugin'); ?></option>
              <option value="Puzzle" <?php myarcade_selected($fog['tag'], 'Puzzle'); ?>><?php _e('Puzzle Games', 'myarcadeplugin'); ?></option>
              <option value="Shooting" <?php myarcade_selected($fog['tag'], 'Shooting'); ?>><?php _e('Shooting Games', 'myarcadeplugin'); ?></option>
              <option value="Sports" <?php myarcade_selected($fog['tag'], 'Sports'); ?>><?php _e('Sports Games', 'myarcadeplugin'); ?></option>
              <option value="unity-games" <?php myarcade_selected($fog['tag'], 'unity-games'); ?>><?php _e('Unity Games', 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a game category that you would like to fetch from FreeGamesForYourWebsite.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Language", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="foglanguage" id="foglanguage">
              <option value="ar" <?php myarcade_selected($fog['language'], 'ar'); ?>><?php _e('Arabic', 'myarcadeplugin'); ?></option>
              <option value="en" <?php myarcade_selected($fog['language'], 'en'); ?>><?php _e('English', 'myarcadeplugin'); ?></option>
              <option value="fr" <?php myarcade_selected($fog['language'], 'fr'); ?>><?php _e('French', 'myarcadeplugin'); ?></option>
              <option value="de" <?php myarcade_selected($fog['language'], 'de'); ?>><?php _e('German', 'myarcadeplugin'); ?></option>
              <option value="el" <?php myarcade_selected($fog['language'], 'el'); ?>><?php _e('Greek', 'myarcadeplugin'); ?></option>
              <option value="ro" <?php myarcade_selected($fog['language'], 'ro'); ?>><?php _e('Romanian', 'myarcadeplugin'); ?></option>
              <option value="es" <?php myarcade_selected($fog['language'], 'es'); ?>><?php _e('Spanish', 'myarcadeplugin'); ?></option>
              <option value="ur" <?php myarcade_selected($fog['language'], 'ur'); ?>><?php _e('Urdu', 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a game language.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="fog_cron_fetch" value="true" <?php myarcade_checked($fog['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="fog_cron_fetch_limit" value="<?php echo $fog['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="fog_cron_publish" value="true" <?php myarcade_checked($fog['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="fog_cron_publish_limit" value="<?php echo $fog['cron_publish_limit']; ?>" />
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
 * @access  public
 * @return  array Default settings
 */
function myarcade_default_settings_fog() {
  return array(
    'feed'          => 'http://www.freegamesforyourwebsite.com/feeds/games/',
    'limit'         => '20',
    'thumbsize'     => 'medium',
    'screenshot'    => true,
    'tag'           => 'all',
    'language'      => 'en',
    'cron_fetch'    => false,
    'cron_fetch_limit' => '1',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
    'status'        => 'publish',
  );
}

/**
 * Handle distributor settings update
 *
 * @version 5.32.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_fog() {

  myarcade_check_settings_nonce();

  // FreeGamesForYourSite Settings
  $fog = array();
  if ( isset($_POST['fogurl'])) $fog['feed'] = esc_url_raw($_POST['fogurl']); else $fog['feed'] = '';
  $fog['limit'] = 20;
  if ( isset($_POST['fogthumbsize'])) $fog['thumbsize'] = trim($_POST['fogthumbsize']); else $fog['thumbsize'] = 'small';
  if ( isset($_POST['fogscreen'])) $fog['screenshot'] = true; else $fog['screenshot'] = false;
  if ( isset($_POST['fogtag'])) $fog['tag'] = sanitize_text_field($_POST['fogtag']); else $fog['tag'] = 'all';
  if ( isset($_POST['foglanguage'])) $fog['language'] = sanitize_text_field($_POST['foglanguage']); else $fog['language'] = 'en';

  $fog['cron_fetch']          = (isset($_POST['fog_cron_fetch'])) ? true : false;
  $fog['cron_fetch_limit']    = (isset($_POST['fog_cron_fetch_limit']) ) ? intval($_POST['fog_cron_fetch_limit']) : 1;
  $fog['cron_publish']        = (isset($_POST['fog_cron_publish']) ) ? true : false;
  $fog['cron_publish_limit']  = (isset($_POST['fog_cron_publish_limit']) ) ? intval($_POST['fog_cron_publish_limit']) : 1;

  // Update Settings
  update_option('myarcade_fog', $fog);
}

/**
 * Display distributor fetch games options
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_fog() {

  $fog = myarcade_get_fetch_options_fog();
  ?>
  <div class="myarcade_border white hide mabp_680" id="fog">
    <div style="float:left;width:150px;">
      <input type="radio" name="fetchmethodfog" value="latest" <?php myarcade_checked($fog['method'], 'latest');?>>
      <label><?php _e("Latest Games", 'myarcadeplugin'); ?></label>
    </div>
    <div class="myarcade_border" style="float:left;padding-top: 5px;background-color: #F9F9F9">
      Fetch <input type="number" name="limitfog" value="<?php echo $fog['limit']; ?>" /> games
    </div>
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
function myarcade_get_fetch_options_fog() {

  // Get distributor settings
  $settings = MyArcade()->get_settings( 'fog' );

  // Set default fetching options
  $settings['method']     = 'latest';

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    // Set submitted fetching options
    $settings['method']  = filter_input( INPUT_POST, 'fetchmethodfog' );
    $settings['limit']   = filter_input( INPUT_POST, 'limitfog' );
  }

  return $settings;
}

/**
 * Retrieve available distributor's categories mapped to MyArcadePlugin categories
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Distributor categories
 */
function myarcade_get_categories_fog() {
  return array(
    "Action"      => false,
    "Adventure"   => true,
    "Arcade"      => false,
    "Board Game"  => false,
    "Casino"      => false,
    "Defense"     => true,
    "Customize"   => false,
    "Dress-Up"    => false,
    "Driving"     => true,
    "Education"   => false,
    "Fighting"    => false,
    "Jigsaw"      => false,
    "Multiplayer" => true,
    "Other"       => true,
    "Puzzles"     => true,
    "Rhythm"      => false,
    "Shooting"    => true,
    "Sports"      => true,
    "Strategy"    => false,
  );
}

/**
 * Fetch FreeGamesForYourWebsite games
 *
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_fog( $args = array() ) {

  $defaults = array(
    'game_id'   => false,
    'echo'      => false,
    'settings'  => array()
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;

  $fog            = myarcade_get_fetch_options_fog();
  $fog_categories = myarcade_get_categories_fog();
  $feedcategories = get_option('myarcade_categories');

  // Init settings var's
  if ( !empty($settings) ) {
    $settings = array_merge($fog, $settings);
  }
  else {
    $settings = $fog;
  }

  if ( !isset( $settings['method'] ) ) {
    $settings['method'] = 'latest';
  }

  if ( $settings['language'] !== 'en' ) {
    $settings['feed'] = str_replace( '.com/feeds/', '.com/' . $settings['language'] . '/feeds/', $settings['feed'] );
  }

  // Generate Feed URL
  $feed = add_query_arg( array("format" => "json"), trim( $settings['feed'] ) );

  // Check if there is a feed limit. If not, feed all games
  if ( empty($settings['limit']) ) {
    $feed = add_query_arg( array("limit" => "all"), $feed );
  }
  else {
    $feed = add_query_arg( array("limit" => $settings['limit'] ), $feed );
  }

  // check game tag
  if ( empty($settings['tag']) ) {
    $feed = add_query_arg( array("tag" => 'all' ), $feed );
  }
  else {
    $feed = add_query_arg( array("tag" => strtolower( $settings['tag'] ) ), $feed );
  }

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  // Fetch games
  $json_games = myarcade_fetch_games( array('url' => $feed, 'service' => 'json', 'echo' => $echo) );

  //====================================
  if ( !empty($json_games) ) {
    foreach ( $json_games as $game_obj ) {

      $game = new stdClass();
      $game->uuid     = $game_obj->id . '_fog';
      // Generate a game tag for this game
      $game->game_tag = md5($game_obj->id.'fog');

      // Transform some categories
      if ( ! empty( $game_obj->category ) ) {
        switch ( $game_obj->category ) {
          case 'Puzzle':
            $game_obj->category = 'Puzzles';
          break;

          case 'None':
          case '3D':
          case 'Flying':
            $game_obj->category = 'Other';
          break;
        }
      }
      else {
        $game_obj->category = 'Other';
      }

      $add_game = false;

      foreach ($feedcategories as $feedcat) {
        if ( $feedcat['Status'] == 'checked') {
          if ( isset($fog_categories[ $feedcat['Name'] ]) && $fog_categories[ $feedcat['Name'] ] === true ) {
            $add_game = true;
            break;
          }
        }
      }

      if ( ! $add_game ) {
        continue;
      }

      switch ( $fog['thumbsize'] ) {

        case 'large': {
          $thumbnail_url = $game_obj->large_thumbnail_url;
        } break;

        case 'small': {
          $thumbnail_url = $game_obj->small_thumbnail_url;

          // Sometimes small thumbnails are missing. Therefor we should check if the image is available
          $response = wp_remote_head( $thumbnail_url );
          $response_code = $response['response']['code'];

          if ( '200' == $response_code ) {
            // Break if the image is available. Otherwise use medium image
            break;
          }
        } // no break here

        case 'medium':
        default: {
          $thumbnail_url = $game_obj->med_thumbnail_url;
        } break;
      }

      if ( $fog['screenshot'] == true ) {
        $screen_url = $game_obj->large_thumbnail_url;
      }
      else {
        $screen_url = '';
      }

      $dimmensions = explode('x', $game_obj->resolution);

      // Clean up the swf file link
      $game->swf_file = strtok($game_obj->swf_file, '?');

      // Check game type
      $game_extension = pathinfo( $game->swf_file, PATHINFO_EXTENSION );

      if ( 'unity3d' == $game_extension ) {
        $game->type = 'unity';
      }
      else {
        $game->type = 'fog';
      }

      $game->name          = esc_sql($game_obj->title);
      $game->slug          = myarcade_make_slug($game_obj->title);
      $game->created       = esc_sql($game_obj->created);
      $game->description   = esc_sql($game_obj->description);
      $game->categs        = esc_sql($game_obj->category);
      $game->control       = esc_sql($game_obj->controls);
      $game->thumbnail_url = esc_sql($thumbnail_url);
      $game->swf_url       = esc_sql($game_obj->swf_file);
      $game->screen1_url   = $screen_url;
      $game->width         = $dimmensions[0];
      $game->height        = $dimmensions[1];
      $game->tags          = !empty( $game_obj->tags ) ? implode( ',', $game_obj->tags ) : '';

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
function myarcade_embedtype_fog() {
  return 'flash';
}
?>