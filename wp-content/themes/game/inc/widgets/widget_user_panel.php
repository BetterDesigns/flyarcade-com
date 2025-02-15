<?php

/*
 * Shows the User Login panel. When the user is logged in serveral user links
 * are shown.
 * 
 * @todo: add BuddyPress and Mingle profile links
 */

if ( !class_exists('WP_Widget_MABP_User_Login') ) {
  class WP_Widget_MABP_User_Login extends WP_Widget {
  
    // Constructor
    function WP_Widget_MABP_User_Login() {
      
      $widget_ops   = array('description' => 'Shows the user login and the user panel.');
      
      $this->WP_Widget('MABP_User_Login', 'BrayGames ==> User Login Panel', $widget_ops);

      add_action('wp_print_scripts', array($this, 'js_load_scripts'));
      
    }
    
	function js_load_scripts () {
    if ( defined('WDFB_PLUGIN_URL')) {
      wp_enqueue_script('wdfb_connect_widget', WDFB_PLUGIN_URL . '/js/wdfb_connect_widget.js');
      wp_enqueue_script('wdfb_facebook_login', WDFB_PLUGIN_URL . '/js/wdfb_facebook_login.js');
    }
	}    
    
    // Display Widget
    function widget($args, $instance) {
      global $user_ID, $current_user, $user_identity;
      
      // Populate user vars
      get_currentuserinfo();
      
      extract($args);

      $title = apply_filters('widget_title', esc_attr($instance['title']));     
      
      echo $before_widget.$before_title.$title.$after_title;
      
      // <-- START --> HERE COMES THE OUPUT
      if ( $user_ID ) {
        // user is logged in
        ?>
        <div class="gravatar">
          <?php echo get_avatar( $current_user->user_email, $size = '85'); ?> 
        </div>
        <div class="userinfo">
          <div calss="welcome"><?php _e('Hello', 'braygames'); ?>, <strong><?php echo $user_identity; ?></strong>! [<a href="<?php echo wp_logout_url( get_bloginfo('url') ); ?>"><?php _e('Logout', 'braygames'); ?></a>]</div>
        </div>
        <div class="clear"></div>
          <ul id="userpanel">
            <?php if ( defined('MNGL_PLUGIN_NAME') ) : ?>
            <?php 
              global $mngl_options, $mngl_message;     
              $unread_count = $mngl_message->get_unread_count();
              $unread_count_str = '[0]';
              if($unread_count) $unread_count_str = " [{$unread_count}]";            
            ?>
            <li><a href="<?php echo get_permalink($mngl_options->activity_page_id); ?>"><?php _e('Activity', 'braygames'); ?></a></li>
            <li><a href="<?php echo get_permalink($mngl_options->profile_page_id); ?>"><?php _e('Profile', 'braygames'); ?></a></li>
            <li><a href="<?php echo get_permalink($mngl_options->profile_edit_page_id); ?>"><?php _e('Settings', 'braygames'); ?></a></li>
            <li><a href="<?php echo get_permalink($mngl_options->friends_page_id); ?>"><?php _e('Friends', 'braygames'); ?></a></li>
            <li><a href="<?php echo get_permalink($mngl_options->friend_requests_page_id); ?>"><?php _e('Friend Requests', 'braygames'); ?><?php echo $request_count_str; ?></a></li>
            <li><a href="<?php echo get_permalink($mngl_options->inbox_page_id); ?>"><?php _e('Inbox', 'braygames'); ?> <?php echo $unread_count_str; ?></a></li>
            
            <?php elseif ( defined('BP_VERSION') ) : ?>
            <?php global $bp; ?>
            <?php if( bp_is_active('activity') ) : ?>
            <li><a href="<?php echo $bp->loggedin_user->domain . BP_ACTIVITY_SLUG . '/'; ?>"><?php _e('Activity', 'braygames'); ?></a></li>
            <?php endif; ?>    
            <li><a href="<?php echo site_url( bp_get_members_root_slug() ); ?>"><?php _e('Members', 'braygames'); ?></a></li>
            <?php if( bp_is_active('groups') ) : ?>
            <li><a href="<?php echo $bp->loggedin_user->domain . BP_GROUPS_SLUG . '/'; ?>"><?php _e('Groups', 'braygames'); ?></a></li>
            <?php endif; ?>
            <?php if ( bp_is_active( 'friends' ) ) : ?>
            <li><a href="<?php echo $bp->loggedin_user->domain . BP_FRIENDS_SLUG . '/'; ?>"><?php _e('Friends', 'braygames'); ?></a></li>
            <?php endif; ?>            
            <li><a href="<?php echo $bp->loggedin_user->domain ?>"><?php _e('Profile', 'braygames'); ?></a></li>
            <?php if ( isset($bp->myscore) ) : ?>
            <li><a href="<?php echo $bp->loggedin_user->domain . $bp->myscore->slug . '/'; ?>"><?php _e('My Scores', 'braygames'); ?></a></li>
            <?php endif; ?>
            <?php else: ?>
            <li><a href="<?php bloginfo('url') ?>/wp-admin/index.php"><?php _e('Go to Dashboard', 'braygames'); ?></a></li>
            <li><a href="<?php bloginfo('url') ?>/wp-admin/profile.php"><?php _e('Edit My Profile', 'braygames'); ?></a></li>
            <?php endif; ?>
          </ul>
          <?php if(function_exists('wpfp_list_favorite_posts')) { ?>                 
			<div class="module_title"><span><?php _e('Your Favorite Games', 'braygames'); ?></span></div>
            <?php wpfp_list_favorite_posts() ?>           
				<?php } ?>
        <?php
      } else {
        // user isn't logged in
        ?>
        <div id="loginBox">
          <form action="<?php bloginfo('url') ?>/wp-login.php" method="post">
          <label><input type="text" name="log" id="log" value="username" /></label>
          <label><input type="password" name="pwd" id="pwd" value="password" /></label>
          <input type="hidden" name="redirect_to" value="<?php bloginfo('url'); ?>"/>
		  <div style="clear:both"></div>
          <input class="submit" type="submit" name="Login" value="<?php _e('Login', 'braygames'); ?>" class="logininp" />
          </form>  
          <div class="register_recover"> 
            <?php 
            if(get_option('users_can_register')) {
              $signup_url = get_bloginfo('url') . '/wp-login.php?action=register';
              
              if ( defined('MNGL_PLUGIN_NAME') ) {
                global $mngl_options; 
              
                if( !empty($mngl_options->signup_page_id) and $mngl_options->signup_page_id > 0) {
                  $signup_url = get_permalink($mngl_options->signup_page_id);
                }
              }
              ?>
              <a href="<?php echo $signup_url; ?>"><?php _e('Register', 'braygames'); ?></a> | 
              <?php
            }
            ?>
             <a href="<?php bloginfo('url') ?>/wp-login.php?action=lostpassword"><?php _e('Lost password?', 'braygames'); ?></a>
          </div>

          <?php
            if ( function_exists('wdfb_get_fb_plugin_markup') && class_exists('Wdfb_Permissions') ) {
             echo '<p class="wdfb_login_button">' .
                wdfb_get_fb_plugin_markup('login-button', array(

                   'scope' => Wdfb_Permissions::get_permissions(),
                   'redirect-url' => wdfb_get_login_redirect(),
                   'content' => __("Login with Facebook", 'wdfb'),
                )) .
             '</p>';
            }
          
          ?>
        </div>          
        <?php
      } 
      // <-- END --> HERE COMES THE OUPUT
      
      echo $after_widget;
    }
    
    // Update Widget
    function update($new_instance, $old_instance) {
    
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      
      return $instance;
    }
    
    // Display Widget Control Form
    function form($instance) {
      global $wpdb;
      
      $instance = wp_parse_args((array) $instance, array('title' => 'User Panel'));
      
      $title = esc_attr($instance['title']);
      
      ?>
      
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">
          Title 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>      
      <?php
    }
  }
}
?>