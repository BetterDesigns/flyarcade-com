<?php
if ( class_exists('WP_Widget') ) {

 /**
  * Include Widgets
  */
  include_once 'widgets/widget_display_games.php';
  include_once 'widgets/widget_user_panel.php';
  include_once 'widgets/widget_frontpage_games.php';
  include_once 'widgets/widget_advertisement.php';
  include_once 'widgets/widget_braygames_scroller.php';
  include_once 'widgets/widget_small_squares.php';
  include_once 'widgets/braygames_video_widget/braygames_video_widget.php';

  if ( !function_exists('myabp_register_widgets') ) {
    function myabp_register_widgets() {
      register_widget('WP_Widget_MABP_User_Login');
      register_widget('WP_Widget_MABP_Frontpage_Games');
      register_widget('WP_Widget_MABP_Display_Games');
      register_widget('WP_Widget_MABP_Advertisement');
  	  register_widget('WP_Widget_MABP_Braygames_Scroller');
  	  register_widget('WP_Widget_MABP_Small_Squares');
  	  register_widget('BrayGamesVideoWidget');
    }
  }

  add_action('widgets_init', 'myabp_register_widgets');
}
?>