
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
		?><div style="margin:0px 14px 7px 2px; " class="module_title"><span><?php echo $title; ?></span></div><?php
		while( $games->have_posts() ) : $games->the_post();	
		?>
		
						<div class="recent_games_image">
					
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php myabp_print_thumbnail(160 ,160 ,''); ?>
							</a>
									
							<div style="margin-top:6px;clear:both;"></div>
				<?php if ( function_exists('myscore_check_leaderboard') && myscore_check_leaderboard() ) : ?>
				  <span class="lb_enabled"></span>
				<?php endif; ?>	
								<a href="<?php the_permalink(); ?>"><?php myabp_get_title(15); ?></a>
							<div style="clear:both;"></div>
								
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
						</div> 	
						
		<?php endwhile; ?>
      
	<?php }  ?>     

