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



    <div class="clear"></div>
    <div id="my_game">
	<div id="pregamebox">

		  <?php
		  if ( have_posts() ) {
		    while (have_posts()) {
		      the_post(); ?>
				<div class="gametitle">
							<h1><?php the_title(); ?></h1>
							<?php if ( function_exists('myscore_check_leaderboard') && myscore_check_leaderboard() ) : ?>
							<div class="lb_enabled"></div>
							<?php endif; ?>
				</div>
		        <?php // Display Game Play Button
		          braygames_play_link();
		        ?>
				<div class="left">
					<?php the_content(); ?>
	                <div class="clear"></div>

		              <?php
		              // Display game screen shots if available
		              if ( (get_option('braygames_dispay_screens') == 'enable') && myabp_count_screenshots() ) {
		                ?>

		                <h2 class="title"><?php the_title(''); ?>&nbsp;<?php _e('Screen Shots:', 'braygames'); ?></h2>
		                <div class="screenshots">
		                <?php myabp_print_screenshot_all(130, 130, 'screen_thumb'); ?>
		                </div>

		                <?php
		              }
		              ?>

				</div><?php // end left ?>
		      <?php
		    } // end while
		  }
		  // Do some actions before the content wrap ends
		  do_action('braygames_before_content_end');
		  ?>

		<div class="right">
			<?php
			    if (is_single()) {
			      if (is_active_sidebar('pregame-sidebar')) {
			      dynamic_sidebar('pregame-sidebar');
			      	} else {
							?>
							  <div class="warning">
								<?php _e('This is your Pre-game sidebar Sidebar and no widgets have been placed here, yet!', MYAPB_THEMENAME); ?>
								<p>Click <a href="<?php echo get_option('home') ?>/wp-admin/widgets.php" title="">here</a> to setup this sidebar!</p>
							  </div>

						<?php }
			    }
			?>
		</div><?php // end right ?>

	</div><?php // end pregamebox ?>
    </div>

 </div><?php // end content_game ?>


<div id="game_tabs_wrapper">

<div class="game_tabs_menu2">
		<ul class="game_tabs_menu">
			<li><a href="#gameinfo"><?php _e('Game Comments', 'braygames'); ?></a></li>

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

          <div id="gameinfo" class="cover">
            <div class="entry">

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
        <?php if ( (get_option('braygames_share_box') == 'enable') && (get_post_meta($post->ID, "mabp_game_type", true) != 'embed') && function_exists('get_game_code') ) : ?>
        <div class="single_game">
		      <h2 id="share"><?php _e("Do You Like This Game?", "braygames"); ?></h2>
          <div class="clear"></div> <br />
          <h2 id="tabs_header"><?php _e("Embed this game on your Website:", "braygames"); ?></h2>
          <form name="select_all"><textarea name="text_area" onClick="javascript:this.form.text_area.focus();this.form.text_area.select();"><a href="<?php bloginfo('url');?>"><?php bloginfo('name'); ?></a><br /><?php echo get_game_code(); ?></textarea>
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
