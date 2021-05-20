<div id="game_tabs_wrapper">
	<div class="game_tabs_menu2">
			<ul class="game_tabs_menu">
				<li><a href="#gameinfo"><?php _e('Game Info', 'braygames'); ?></a></li>
				<li><a href="#share"><?php _e('Share', 'braygames'); ?></a></li>
				<li><a href="#moregames"><?php _e('More Games', 'braygames'); ?></a></li>
			</ul>
	</div> 

<div class="game_tabs">

	<div class="clear"></div><br />	
  <div id="content" class="content<?php echo get_option('braygames_singlesidebar_position'); ?>">

<!-- single-sidebar2 Start-->	  
	<div class="single-sidebar2_<?php echo get_option('braygames_singlesidebar_position'); ?>">
			<?php
			  if (is_active_sidebar('single-sidebar2')) {
			  dynamic_sidebar('single-sidebar2');
			  } 
			  ?>
	</div> 
<!-- single-sidebar2 End-->	
	

        <div class="single_game" id="post-<?php the_ID(); ?>">
				<?php if ( function_exists('myscore_check_leaderboard') && myscore_check_leaderboard() ) : ?>
				  <span style="margin:6px 6px 0 0" class="lb_enabled"></span>
				<?php endif; ?>	
		<h2 id="gameinfo"><?php the_title(); ?></h2>
 
    
          <div class="cover">
            <div class="entry">
              <?php // Check if this is a game post ?>
              <?php // Show content banner if configured ?>
              
              <?php the_content(); ?>
			  
              <?php 
              // Display game screen shots if available              
              if ( (get_option('braygames_dispay_screens') == 'enable') && myabp_count_screenshots() ) {
                ?>
                <div class="clear"></div><br />
                <h2 id="tabs_header"><?php the_title(''); ?>&nbsp;<?php _e('Screen Shots:', 'braygames'); ?></h2>
                <div class="screencenter">
                <?php myabp_print_screenshot_all(130, 130, 'screen_thumb'); ?>
                </div>
                <?php
              }
              ?>              
              
              <?php
              // Display some manage buttons if logged in user is a admin
              if ( current_user_can('delete_posts') ) {
                // Show edit and delete links
                echo '<div class="clear"></div>';
                echo "<div style='float:right'><strong>Admin Actions: </strong><a href='" . wp_nonce_url("/wp-admin/post.php?action=delete&amp;post=".$post->ID, 'delete-post_' . $post->ID) . "'>Delete</a>";
                echo " | ";
                echo "<a href='" . wp_nonce_url("/wp-admin/post.php?post=".$post->ID."&action=edit") . "'>Edit</a></div>";
              }
              ?>              
              <div class="clear"></div> 
				<div class="allcomments"> 
				  <?php comments_template(); ?>
				</div>
				
            </div> <?php // end entry ?>
          </div> <?php // end cover ?>
        </div> <?php // single_game ?>

        <?php // Show the game share box ?>       
        <?php if ( (get_option('braygames_share_box') == 'enable') && (get_post_meta($post->ID, "mabp_game_type", true) != 'embed') ) : ?>
        <div class="single_game">
		<h2 id="share"><?php _e("Do You Like This Game?", "braygames"); ?></h2>
		
              <?php // Display sexy bookmarks if plugin installed ?>
			  <?php if(function_exists('selfserv_shareaholic')) { selfserv_shareaholic(); } ?>
              
              <div class="clear"></div> <br />
	
          <h2 id="tabs_header"><?php _e("Embed this game on your Website:", "braygames"); ?></h2>
          <form name="select_all"><textarea name="text_area" onClick="javascript:this.form.text_area.focus();this.form.text_area.select();"><a href="<?php bloginfo('url');?>"><?php bloginfo('name'); ?></a><br /><?php echo $embedcode; ?></textarea>
          </form>
        </div> <?php // end single_game ?>
        <?php endif; ?>
       
 

        
      <?php // Display Related Games ?>

      <?php 
      if ( function_exists('related_entries') ) {
        related_entries();
      } else { ?>
	  <div>
	  <h2 id="moregames"></h2>	
		<?php include (TEMPLATEPATH . '/related.php'); } ?>
	  </div>
        

  </div> <?php // end content ?>
  <div id="turnoff"></div> 
  
	<div id="sidebar<?php echo get_option('braygames_singlesidebar_position'); ?>">
		<div style="margin-top:4px;padding:6px" class="single-sidebar">
					<?php if(function_exists('wp_gdsr_render_article_thumbs')) { ?> 
					<div class="rating_wrapper">
						
							<div align="center" style="padding-right:10px;">
								<?php wp_gdsr_render_article(10, false, 'soft'); ?> 
							</div> 
						
						<div class="clear"></div>
					</div>
					<?php } ?> 
 
				<?php if (function_exists('the_views')) : ?>	
					<div class="game_info_content"><strong><?php _e("Game Stats", "braygames"); ?>:&nbsp;&nbsp;</strong><?php the_views(); ?></div>
				<?php endif; ?>	
					<div class="game_info_content"><strong><?php _e("Game Categories", "braygames"); ?>:&nbsp;&nbsp;</strong><?php the_category(', '); ?></div>
					<div class="game_info_content"><?php the_tags( '<strong>'.__("Game Tags", "braygames").':&nbsp;&nbsp;</strong>', ', ', ''); ?></div>
		</div>
	</div> 

<?php get_sidebar(); ?>

</div>
</div> <?php // end game_tabs_wrapper ?>