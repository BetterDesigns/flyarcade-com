
<?php
	if ( get_option('braygames_fpgames_sortby') == 'Random Games') { $bg_sortby = 'rand'; } else { $bg_sortby = 'date'; } 
 
	if ( get_option('braygames_fpgames_category') == '-- All --') { $category   = ''; } 
	else {  
	$category = get_cat_ID( get_option('braygames_fpgames_category'));
	}
	
	$limit = get_option('braygames_fpgames_limit');	
	$title = get_option('braygames_fpgames_title');


    if ( !$category ) {$category = ''; $comma = ''; } else { $comma = ','; }
    $blogcat = get_cat_ID( get_option('braygames_blog_category'));
    if ( !empty($blogcat) ) $exclude = '&cat='.$category.$comma.'-'.$blogcat; else $exclude = $category;


    $games = new WP_Query('cat='.$exclude.'&showposts='.$limit.'&orderby='.$bg_sortby);
	
	if ( !empty($games) ) {
      
	?>  

		<?php 
		?><div style="margin:0px 14px 7px 0px; " class="module_title"><span><?php echo $title; ?></span></div><?php
		while( $games->have_posts() ) : $games->the_post();	
		?>
		
		  <div id="fpgames_head_wrapper">

			<span class="game_thumb">
			  <a href="<?php the_permalink() ?>" class="thumb_link" rel="bookmark" title="<?php echo $post->post_title; ?>" ><?php myabp_print_thumbnail(75, 75); ?></a>
			</span>

			<span class="gamebox_info">
				<?php if ( function_exists('myscore_check_leaderboard') && myscore_check_leaderboard() ) : ?>
				  <span class="lb_enabled"></span>
				<?php endif; ?>	
			  <h3>
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $post->post_title; ?>" >
				  <?php myabp_get_title(20); ?>
				</a>
			  </h3>
			  <div style="padding:3px 0px 8px 0px;"><?php braygames_get_excerpt(40) ?></div>
							<div style="margin-top:4px" class="recent_games_ratings">
								<div class="games_ratings_stars">
								<?php
									if(function_exists('wp_gdsr_render_article_thumbs')) { 
										$postRatingData = wp_gdsr_rating_article(get_the_ID());
										gdsr_render_stars_custom(array(
											"max_value" => gdsr_settings_get('stars'),
											"size" => 12,
											"vote" => $postRatingData->rating
										));	
									}	
								?> 
								</div>
							<span> <?php if(function_exists('the_views')) { the_views(); } ?></span>
							</div>
			</span>

		  </div>
						
		<?php endwhile; ?>
      
	<?php }  ?>     

