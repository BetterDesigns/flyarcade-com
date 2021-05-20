<?php
/**
 * braygames action functions
 * 
 * @author Daniel Bakovic
 * @uri http://myarcadeplugin.com   
 * 
 * @package WordPress
 * @subpackage braygames
 */

function braygames_init_actions() {
  /** Add WPSeoContentManager Compatibility **/
  if ( function_exists('get_WPSEOContent') ) {
    add_action('braygames_after_404_content', 'get_WPSEOContent');
    add_action('braygames_after_archive_content', 'get_WPSEOContent');
    add_action('braygames_after_index_content', 'get_WPSEOContent');
  }  
}
add_action('init', 'braygames_init_actions');

function braygames_action_after_404_content() {
  do_action('braygames_after_404_content');
}

function braygames_action_after_archive_content() {
  do_action('braygames_after_archive_content');
}

function braygames_action_after_index_content() {
  do_action('braygames_after_index_content');
}
?>
