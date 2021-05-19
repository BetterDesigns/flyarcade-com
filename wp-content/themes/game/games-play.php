<?php
$color_scheme = get_option('braygames_color_scheme'); 
?>
<style>
	#wrapper{
	  width: 100%;
	  margin: 0 auto;
	}
	#sidebarleft{
	  padding: 0 0 0px 0px;
	}
	div.shr-bookmarks {
	margin: 5px 0 8px;
	}
	#content{
	  box-shadow: 0 0px 0;
	  -moz-box-shadow: 0 0px 0;
	  -webkit-box-shadow: 0 0px 0;
	  border:0;
	  background-color: transparent;
	  width: 660px;
	  height: 100%;
	  padding:0px;
	}
	.breadcrumbs{
		display:none;
	}
	.entry p img {
		width: auto;
		height: auto;
		border: 0;
	}

</style>

<div id="content_game">
      
  <center>
    <?php if ( get_option('braygames_progressbarstatus') == 'enable' ) : ?>
      <?php $gamesize='0px'; ?>
      <?php include (TEMPLATEPATH . '/inc/myabp_progressbar.php'); ?>
          
      <div id="showprogressbar" style="display:block; margin: 15px 0px;"> 
	  
	    <div id="progressbar"> 
          <span id="progresstext">0%</span> 
          <div id="progressbarloadbg" style="z-index: 2;">&thinsp;</div>
        </div>
		
        <?php
        $banner2 = stripslashes(get_option('braygames_loadinggameadcode'));
        if ($banner2) : ?>
          <div id="loadinggame_ad" style="margin: 15px auto;">
            <?php echo $banner2; ?>
          </div>                  
        <?php endif; ?>
              

      </div>
            
      <div id="progressbarloadtext" onclick="window.hide();"> 
        <?php echo get_option('braygames_progressbartextload'); ?>
      </div>
    
    <?php else : ?>
      <?php $gamesize='100%'; ?>  
    <?php endif; ?>		
          
    <div class="clear"></div>  

    <div id="my_game" style="overflow:hidden; height: <?php echo $gamesize; ?>; width: <?php echo $gamesize; ?>;">
  	<div class="cont1">
        <div class="cont2">
          <div class="cont3">
            <div id="escenario">			
			<div class="game_name"><?php the_title(); ?></div>	
			
				<?php // Start Game Buttons ?> 		
				<div id="game_buttons">
					<a href="<?php bloginfo('url'); ?>/?gid=<?php echo $post->ID;?>&play=fullscreen" class="fullscreen" title="<?php _e('Fullscreen', 'braygames'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/blank.png" border="0" alt="<?php _e('Fullscreen', 'braygames'); ?>" /></a>
					<?php if (function_exists('braygames_favorite')) { braygames_favorite(); } ?>
					<a href="#" title="<?php _e('Turn lights off / on', 'braygames'); ?>" class="interruptor"><img src="<?php bloginfo('template_directory'); ?>/images/blank.png" border="0" alt="<?php _e('Lights Toggle', 'braygames'); ?>" /></a>				
				</div>
				<?php // end Game Buttons ?> 
				
              <div id="play_game">
                <?php 
                if (function_exists('get_game')) { ?>
                  <div id= "bordeswf">
                    <?php 
                    $embedcode = get_game($post->ID);
                    global $mypostid; $mypostid = $post->ID;
                    echo myarcade_get_leaderboard_code();        
                    echo $embedcode; 
                    ?> 
                  </div> 
                <?php } ?>
              </div><?php // end play_game ?> 
            </div>
          </div>
        </div>
      </div>
	
    </div>
  </center>
 </div><?php // end content_game ?>


<div id="game_tabs_wrapper">

<div class="game_tabs_menu2">
		<ul class="game_tabs_menu">
			<li><a href="#gameinfo"><?php _e('Game Info', 'braygames'); ?></a></li>
			
			<?php if ( get_post_meta($post->ID, "mabp_instructions", true)) : ?>
			<li><a href="#instructions"><?php _e('Game Instructions', 'braygames'); ?></a></li>
			<?php endif; ?>		
						
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
	
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
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
        
        <?php // Show the game instrcutions ?>       
        <?php if ( get_post_meta($post->ID, "mabp_instructions", true)) : ?>
		<div class="single_game">
		<h2 id="instructions"><?php _e("Game Instructions", "braygames"); ?></h2>
			<div class="entry">
			
				
					<p><?php echo myabp_get_instructions();?></p>
						
				
			</div>     
              <div class="clear"></div> <br />

        </div> 
		<?php endif; ?>	
		<?php // end single_game ?>
		
        <?php // Show the game share box ?>       
        <?php if ( (get_option('braygames_share_box') == 'enable') && (get_post_meta($post->ID, "mabp_game_type", true) != 'embed') ) : ?>
        <div class="single_game">
		<h2 id="share"><?php _e("Do You Like This Game?", "braygames"); ?></h2>
		

              
              <div class="clear"></div> <br />
	
          <h2 id="tabs_header"><?php _e("Embed this game on your Website:", "braygames"); ?></h2>
          <form name="select_all"><textarea name="text_area" onClick="javascript:this.form.text_area.focus();this.form.text_area.select();"><a href="<?php bloginfo('url');?>"><?php bloginfo('name'); ?></a><br /><?php echo $embedcode; ?></textarea>
          </form>
        </div> <?php // end single_game ?>
        <?php endif; ?>
       
 
      <?php endwhile; ?>
        
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
    <?php else: ?>
      <h1 class="title"><?php _e("Not Found", "braygames"); ?></h1>
      <p><?php _e("I'm Sorry, you are looking for something that is not here. Try a different search.", "braygames"); ?></p>  
    <?php endif; ?>	

<?php get_sidebar(); ?>
</div>
</div><br />
