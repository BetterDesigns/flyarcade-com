<?php
/* 
Plugin Name:  Full Screen Mod for MyArcadePlugin Pro
Plugin URI:   http://myarcadeplugin.com
Description:  Show a game in full screen mode
Version:      2.0.0
Author:       Daniel Bakovic
Author URI:   http://netreview.de
*/
?>

<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

    <title><?php bloginfo('name'); ?> - Playing game: - <?php single_post_title(); ?></title>

    <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="all" />
    
    <?php wp_head(); ?>   
    
    <?php if ( get_option('braygames_custom_headercode_status') == 'enable' ) { echo stripslashes( get_option('braygames_custom_headercode') ); } ?>    

    <script src="http://xs.mochiads.com/static/pub/swf/leaderboard.js" type="text/javascript"></script>
  </head>

  <body>
    <center>
      <div id="fullgame">
        <h2><a href="javascript:history.go(-1)"><?php _e('Go Back To', MYAPB_THEMENAME); ?>: <?php bloginfo('name'); ?> </a></h2>
         
        <?php
          global $mypostid, $post; 
          $mypostid = $_GET['gid'];
          // overwrite the post id
          $post->ID = $mypostid;
          if ( function_exists('myarcade_get_leaderboard_code') )  {
            echo myarcade_get_leaderboard_code();
          }         
          echo get_game($mypostid, $fullsize = false, $preview = false, $fullscreen = true);
        ?>
		 </div>  
    </center>
    
    <?php 
    wp_footer();    
    
    // custom footer code
    if ( get_option('braygames_custom_footercode_status') == 'enable' ) { echo stripslashes(get_option('braygames_custom_footercode')); }
    ?> 
  </body>
</html>