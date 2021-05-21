<?php
/**
 * Edit game form
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// Locate WordPress root folder
$root = dirname( dirname( dirname( dirname( dirname(__FILE__)))));

if ( file_exists($root . '/wp-load.php') ) {
  define('MYARCADE_DOING_ACTION', true);
  require_once($root . '/wp-load.php');
}
else {
  // WordPress not found
  die();
}

// Check user privilege
if ( function_exists('current_user_can') && !current_user_can('manage_options') ) {
  die();
}

// Load required MyArcadePlugin functions
require_once( MYARCADE_CORE_DIR . '/myarcade_admin.php' );
require_once( MYARCADE_CORE_DIR . '/addgames.php' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title><?php _e("Edit Game", 'myarcadeplugin'); ?></title>


<link rel='stylesheet' href='<?php echo admin_url("css/wp-admin.css") ?>' type='text/css' />
<link rel='stylesheet' href='<?php echo admin_url("css/colors/blue/colors.css") ?>' type='text/css' />
<link rel='stylesheet' href='<?php echo includes_url("css/buttons.min.css") ?>' type='text/css' />
<link rel='stylesheet' href='<?php echo includes_url("css/dashicons.min.css") ?>' type='text/css' />

<link rel='stylesheet' href='<?php echo MYARCADE_URL; ?>/assets/css/myarcadeplugin.css' type='text/css' />

<script type="text/javascript" src="<?php echo get_option('siteurl')."/".WPINC."/js/jquery/jquery.js"; ?>"></script>

<style type="text/css">
  .wrap { margin-left: 15px; }
</style>

</head>
<body>
<div class="wrap">
<p style="margin-top: 10px"><img src="<?php echo MYARCADE_URL . '/assets/images/logo.png'; ?>" alt="MyArcadePlugin" /></p>
<?php
$general= get_option('myarcade_general');

if ( isset($_POST['editgame']) &&  $_POST['editgame'] == 'edit') {
  ?>
  <script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery("#closelink").click(function() {
      jQuery("#gstatus_<?php if (isset($_POST['gameid']) ) { echo $_POST['gameid']; } ?>", top.document).html('<div style="color:red;">updated</div>');
    });
  });

  function publish_status() {
    jQuery("#gstatus_<?php if (isset($_POST['gameid']) ) { echo $_POST['gameid']; } ?>", top.document).html('<div style="color:red;">published</div>');
  }
  </script>

  <?php
  $gameID = $_POST['gameid'];

  // Update game
  if ( isset($_POST['leaderenable']) ) {
    $leaderbaord  = $_POST['leaderenable'];
  }
  else {
    $leaderbaord = '';
  }

  if ( isset($_POST['highscoretype']) ) {
    $scoreorder = $_POST['highscoretype'];
  }
  else {
    $scoreorder = 'high';
  }

  $score_bridge = ( !empty($_POST['score_bridge']) ) ? $_POST['score_bridge'] : '';


  if ($_POST['published'] == '1') {
    $query = "UPDATE ".$wpdb->prefix . 'myarcadegames'." SET
      leaderboard_enabled = '$leaderbaord',
      highscore_type = '$scoreorder',
     WHERE id = '".$gameID."'";
  }
  else {
    $name         = esc_sql(esc_attr($_POST['gamename']));
    $description  = $_POST['gamedescr'];
    $instructions = $_POST['gameinstr'];
    //$controls     = $_POST['gamecontrols'];
    $game_type    = $_POST['gametype'];
    $tags         = esc_sql($_POST['gametags']);
    $width        = (isset($_POST['gamewidth'])) ? intval($_POST['gamewidth']) : '';
    $height       = (isset($_POST['gameheight'])) ? intval($_POST['gameheight']) : '';

    // Transform category ids to names
    $new_categs = array();

    if ( 'post' !== MyArcade()->get_post_type() && !empty( $general['custom_category']) && taxonomy_exists($general['custom_category']) ) {
      foreach ($_POST['gamecategs'] as $cat_id) {
        $term = get_term_by('id', $cat_id, $general['custom_category']);
        $new_categs[] = $term->name;
      }
    }
    else {
      foreach ($_POST['gamecategs'] as $cat_id) {
        $new_categs[] = get_cat_name($cat_id);
      }
    }

    $categories   = implode(",", $new_categs);

    $query = "UPDATE ".$wpdb->prefix . 'myarcadegames'." SET
      name          = '$name',
      game_type     = '$game_type',
      categories    = '$categories',
      description   = '$description',
      tags          = '$tags',
      instructions  = '$instructions',
      width         = '$width',
      height        = '$height',
      leaderboard_enabled = '$leaderbaord',
      highscore_type = '$scoreorder',
      score_bridge = '$score_bridge'
     WHERE id = '".$gameID."'";
  }

  $result = $wpdb->query($query);

  if ($result) {
    echo '<div class="mabp_info">'.__("Game has been updated!", 'myarcadeplugin').'</div>';
  }
  else {
    echo '<div class="mabp_error">'.__("Can't update the game!", 'myarcadeplugin').'</div>';
  }
}
else {
  $gameID = $_GET['gameid'];
}

// Get game
$game = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . 'myarcadegames'." WHERE id = '$gameID' LIMIT 1", ARRAY_A);

$disabled = '';

$publish = "<button class=\"button-secondary\" onclick = \"jQuery('#gstatus_$gameID').html('<div class=\'gload\'> </div>');jQuery.post('".admin_url('admin-ajax.php')."',{action:'myarcade_handler',gameid:'$gameID',func:'publish'},function(data){jQuery('#gstatus_$gameID').html(data);});jQuery('#gstatus_$gameID', top.document).html('<div style=\'color:red;\'>published</div>');self.parent.tb_remove();\">".__("Publish", 'myarcadeplugin')."</button>&nbsp;";
?>

<div id="myabp_import">
  <form enctype="multipart/form-data" class="niceform" method="post" name="FormEditGame">

    <input class="button-secondary" id="submit" type="submit" name="submit" value="<?php _e("Save Changes", 'myarcadeplugin'); ?>" />
    <?php echo $publish; ?>
    <div style="float:right">
      <button class="button-secondary" id="closelink" onclick="self.parent.tb_remove();return false;">Close</button>
    </div>

    <input type="hidden" name="gameid" value="<?php echo $_GET['gameid']; ?>" />
    <input type="hidden" name="editgame" value="edit" />
    <input type="hidden" name="published" value="<?php if ($game['status'] == 'published') { echo "1"; } else { echo "0"; } ?>" />

    <h2><?php _e("Edit Game", 'myarcadeplugin'); ?></h2>

    <div class="container">
      <div class="block">
        <table class="optiontable" width="100%">
          <?php if ($game['status'] == 'published') : ?>
          <?php $disabled = ' disabled'; ?>
          <tr>
            <td colspan="2"><div class="myerror fade"><?php _e("You are about to edit a published game. Thereby, will only be able to change the score settings.", 'myarcadeplugin'); ?></div></td>
          </tr>
          <?php endif; ?>
          <tr>
            <td><h3><?php _e("Name", 'myarcadeplugin'); ?> <small>(<?php _e("required", 'myarcadeplugin'); ?>)</small></h3></td>
          </tr>
          <tr>
            <td>
              <input name="gamename" size="50" type="text" value="<?php echo stripslashes($game['name']); ?>" <?php echo $disabled; ?>/>
            </td>
          </tr>
        </table>
      </div>
    </div>

  <div class="container">
    <div class="block">
      <table class="optiontable" width="100%">
        <tr>
          <td colspan="2"><h3><?php _e("Game Dimensions", 'myarcadeplugin'); ?></h3></td>
        </tr>
        <tr>
          <td>
            <?php _e("Game width (px)", 'myarcadeplugin'); ?>: <input id="gamewidth" name="gamewidth" type="text" size="20" value="<?php echo $game['width']; ?>" />
          </td>
          <td>
            <?php _e("Game height (px)", 'myarcadeplugin'); ?>: <input id="gameheight" name="gameheight" type="text" size="20" value="<?php echo $game['height']; ?>" />
          </td>
        </tr>
      </table>
    </div>
  </div>

    <div class="container">
      <div class="block">
        <table class="optiontable" width="100%">
          <tr>
            <td><h3><?php _e("Game Description", 'myarcadeplugin'); ?> <small>(<?php _e("required", 'myarcadeplugin'); ?>)</small></h3></td>
          </tr>
          <tr>
            <td>
              <textarea rows="6" cols="80" name="gamedescr" <?php echo $disabled; ?>><?php echo stripslashes($game['description']); ?></textarea>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="container">
      <div class="block">
        <table class="optiontable" width="100%">
          <tr>
            <td><h3><?php _e("Game Instructions", 'myarcadeplugin'); ?></h3></td>
          </tr>
          <tr>
            <td>
              <textarea rows="6" cols="80" name="gameinstr" <?php echo $disabled; ?>><?php echo stripslashes($game['instructions']); ?></textarea>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="container">
      <div class="block">
        <table class="optiontable" width="100%">
          <tr>
            <td><h3><?php _e("Tags", 'myarcadeplugin'); ?></h3></td>
          </tr>
          <tr>
            <td>
              <input name="gametags" type="text" size="50" value="<?php echo $game['tags']; ?>" <?php echo $disabled; ?>/>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="container">
      <div class="block">
        <table class="optiontable" width="100%">
          <tr>
            <td><h3><?php _e("Category", 'myarcadeplugin'); ?> <small>(<?php _e("required", 'myarcadeplugin'); ?>)</small></h3></td>
          </tr>
          <tr>
            <td>
            <?php
            $game_categories = explode (',', $game['categories']);
            $categs = array();

            if ( 'post' !== MyArcade()->get_post_type() && !empty( $general['custom_category']) && taxonomy_exists($general['custom_category']) ) {

              $custom_terms = get_terms( $general['custom_category'], array( 'hide_empty' => 0 ) );

              // Build the category array
              foreach($custom_terms as $custom_term) {
                $categs[$custom_term->term_id] =  $custom_term->name;
              }
            }
            else {
              // Get all categories
              $all_categories = get_terms( 'category', array('fields' => 'ids', 'get' => 'all') );
              foreach( $all_categories as $all_cat_id ) {
                $categs[$all_cat_id] = get_cat_name($all_cat_id) ;
              }
            }

            $i = count($categs);
            foreach ($categs as $cat_id => $cat_name) {
              foreach ($game_categories as $game_cat) {
                if ($game_cat == $cat_name) {
                  $checked = 'checked';
                  break;
                }
                else {
                  $checked = '';
                }
              }

              $i--;
              $br = '';
              if ($i > 0) {
                $br = '<br />';
              }

              echo '<input type="checkbox" name="gamecategs[]" value="'.$cat_id.'" '.$checked.' '.$disabled.'/><label class="opt">&nbsp;'.$cat_name.'</label>'.$br;
            }
            ?>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="container">
      <div class="block">
        <table class="optiontable" width="100%">
          <tr>
            <td><h3><?php _e("Highscore Settings", 'myarcadeplugin'); ?></h3></td>
          </tr>
          <tr>
            <td>
              <input type="checkbox" name="leaderenable" value="1" <?php if ($game['leaderboard_enabled'] == '1') { echo 'checked'; } ?> /><label class="opt">&nbsp;<?php _e("Yes - This game is able to submit scores", 'myarcadeplugin'); ?></label>
            </td>
          </tr>
          <tr>
            <td>
              <p>
              <?php _e("Score Order (Highscore Type)", 'myarcadeplugin'); ?>
              <select size="1" name="highscoretype" id="highscoretype">
                <option value="high" <?php if ($game['highscore_type'] == 'high') { echo "selected"; } ?>><?php _e("DESC (High to Low)", 'myarcadeplugin') ;?></option>
                <option value="low" <?php if ($game['highscore_type'] == 'low') { echo "selected"; } ?>><?php _e("ASC (Low to High)", 'myarcadeplugin') ?></option>
              </select>
              </p>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="container">
      <div class="block">
        <table class="optiontable" width="100%">
          <tr>
            <td><h3><?php _e("Game Type", 'myarcadeplugin'); ?></h3></td>
          </tr>
          <tr>
            <td>
              <select size="1" name="gametype" id="gametype">
                <?php
                $distributors      = MyArcade()->distributors();
                $custom_game_types = MyArcade()->custom_game_types();
                $game_types        = array_merge( $distributors, $custom_game_types );

                foreach ( $game_types as $slug => $name ) :
                  ?>
                  <option value="<?php echo $slug; ?>" <?php myarcade_selected($game['game_type'], $slug); ?>><?php echo $name; ?></option>
                  <?php
                endforeach;
                ?>
              </select>
              <br />
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="container">
      <div class="block">
        <input class="button-secondary" id="submit" type="submit" name="submit" value="<?php _e("Save Changes", 'myarcadeplugin'); ?>" />
        <?php echo $publish; ?>
        <div style="float:right">
          <button class="button-secondary" id="closelink" onclick="self.parent.tb_remove();return false;">Close</button>
        </div>
      </div>
    </div>
  </form>
</div>
</div>
</body>
</html>