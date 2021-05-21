  <div id="header_top">
  
  		<!-- Top Search Form Start-->
			<?php if ( get_option('braygames_search_area') == '1' )  : ?>
		
				<form method="get" class="search_form-wrapper cf" action="<?php bloginfo ('url'); ?>">
						<input type="text" name="s" value="<?php _e("Search For Games...", "braygames"); ?>" onfocus="if (this.value == '<?php _e("Search For Games...", "braygames"); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e("Search For Games...", "braygames"); ?>';}">
						<button type="submit"></button>
				</form>   
			<?php endif; ?>
		<!-- Top Search Form End-->
		
		
    <?php
	if ( get_option('braygames_members_area') == '1' )  :
      global $user_ID, $user_identity;
      get_currentuserinfo();
      if (!$user_ID):
      ?>
	<div id="loginform"> 

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
		  
		<form name="loginform" id="loginform" action="<?php bloginfo('url') ?>/wp-login.php" method="post">		
            
			<label>
              <input type="text" name="log" id="log" size="10" tabindex="7" value="<?php _e('Username') ?>" onblur="if(this.value=='') this.value='<?php _e('Username') ?>';" onfocus="if(this.value=='<?php _e('Username') ?>') this.value='';"/>
            </label>
			
            <label>
              <input type="password" name="pwd" id="pwd" size="10" tabindex="8" value="<?php _e('Password') ?>" onblur="if(this.value=='') this.value='<?php _e('Password') ?>';" onfocus="if(this.value=='<?php _e('Password') ?>') this.value='';"/>
            </label>
			
            <label>
              <input type="checkbox" name="rememberme" value="forever" tabindex="9" /><?php _e('Remember me', 'braygames'); ?>
            </label>
			
            <input type="submit" name="submit" value="<?php _e('Login', 'braygames'); ?>" tabindex="10" class="submit"/>
			 

			<span class="registericon"> <?php wp_register('', ''); ?></span>
						
        </form> 

	</div>


		 


	
		
        <?php else: ?>
          <?php // check for buddypress or mingle ?>
		  
		<div id="loggedin">
			<div class="welcomeback"><?php _e('Welcome back,', 'braygames'); ?> <strong><?php echo $user_identity; ?></strong></div>
			
			  <?php if ( defined('MNGL_PLUGIN_NAME') ) : ?>
			  <?php 
				global $mngl_options, $mngl_message;     
				$unread_count = $mngl_message->get_unread_count();
				$unread_count_str = '[0]';
				if($unread_count) $unread_count_str = " [{$unread_count}]";            
			  ?>
				<a href="<?php echo get_permalink($mngl_options->activity_page_id); ?>" title="<?php _e("Activity Stream", 'braygames'); ?>"><?php _e('Activity', 'braygames'); ?></a>
				
				<a href="<?php echo get_permalink($mngl_options->profile_page_id); ?>" title="<?php _e("View Your Profile Page", 'braygames'); ?>"><?php _e('Profile', 'braygames'); ?></a>
				
				<a href="<?php echo get_permalink($mngl_options->profile_edit_page_id); ?>" title="<?php _e('Change Your Settings', 'braygames'); ?>"><?php _e("Settings", "braygames"); ?></a>
					 
				<a href="<?php echo get_permalink($mngl_options->friends_page_id); ?>" title="<?php _e('View Your Friends', 'braygames'); ?>"><?php _e('Friends', 'braygames'); ?></a>
				
				<a href="<?php echo get_permalink($mngl_options->friend_requests_page_id); ?>" title="<?php _e('Add New Friends', 'braygames'); ?>"><?php _e('Friend Requests', 'braygames'); ?></a>
				
				<a href="<?php echo get_permalink($mngl_options->inbox_page_id); ?>"><?php _e('Inbox', 'braygames'); ?> <?php echo $unread_count_str; ?></a>            
				<?php elseif ( defined('BP_VERSION') ) : ?>
				  <?php global $bp; ?>
				
				  <?php if( bp_is_active('activity') ) : ?>
					<a href="<?php echo $bp->loggedin_user->domain . BP_ACTIVITY_SLUG . '/'; ?>"><?php _e('Activity', 'braygames'); ?></a>
					
				  <?php endif; ?>
					
				  <a href="<?php echo site_url( bp_get_members_root_slug() ); ?>"><?php _e('Members', 'braygames'); ?></a>
				  
				  
				  <a href="<?php echo $bp->loggedin_user->domain ?>"><?php _e('Profile', 'braygames'); ?></a>
<?php if ( isset( $bp->loaded_components['scores'] ) ) : ?>
        
            <a href="<?php echo $bp->loggedin_user->domain . 'scores'; ?>"><?php _e('My Scores', 'braygames'); ?></a>
          <?php endif; ?>
				  
				<?php endif; ?>
						   
				
				<?php 
				  if ( ! is_user_logged_in() )
					$link = '<a href="' . get_option('siteurl') . '/wp-login.php">' . __('Log in') . '</a>';
				  else
					$link = "<a href='" . wp_nonce_url( site_url("/wp-login.php?action=logout&redirect_to=" . get_option('siteurl'), 'login'), 'log-out' ) . "'>".__('Log out', 'braygames')."</a>"; 

				  echo apply_filters('loginout', $link);
				?>
		</div>
        <?php endif; ?>
		<?php endif; ?>
  </div>
  
  <div class="clear"></div>