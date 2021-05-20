<?php
// Functions that are needed for the buddypress ingegration

// Do it only if buddypress is installed
if( defined('BP_VERSION') ) {

  // Load the AJAX functions for the theme
  require_once( WP_PLUGIN_DIR . '/buddypress/bp-themes/bp-default/_inc/ajax.php');

  if ( !function_exists('bp_dtheme_enqueue_scripts') ) {
    function bp_dtheme_enqueue_scripts() {
      // Bump this when changes are made to bust cache
      $version = '20120110';

      // Enqueue the global JS - Ajax will not work without it
      wp_enqueue_script('dtheme-ajax-js', WP_PLUGIN_URL . '/buddypress/bp-themes/bp-default/_inc/global.js', array('jquery'), $version);

      // Add words that we need to use in JS to the end of the page so they can be translated and still used.
      $params = array(
        'my_favs'           => __( 'My Favorites', 'buddypress' ),
        'accepted'          => __( 'Accepted', 'buddypress' ),
        'rejected'          => __( 'Rejected', 'buddypress' ),
        'show_all_comments' => __( 'Show all comments for this thread', 'buddypress' ),
        'show_all'          => __( 'Show all', 'buddypress' ),
        'comments'          => __( 'comments', 'buddypress' ),
        'close'             => __( 'Close', 'buddypress' ),
        'view'              => __( 'View', 'buddypress' ),
        'mark_as_fav'	    => __( 'Favorite', 'buddypress' ),
        'remove_fav'	    => __( 'Remove Favorite', 'buddypress' )
      );

      wp_localize_script( 'dtheme-ajax-js', 'BP_DTheme', $params );
    }

    add_action( 'wp_enqueue_scripts', 'bp_dtheme_enqueue_scripts' );
  }
} // end if buddypress is activated
?>