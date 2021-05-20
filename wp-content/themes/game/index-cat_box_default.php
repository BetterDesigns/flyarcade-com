<?php

?>
<?php global $games, $category, $cat_id;?>

<div class="gamebox">
 	<h1><span><a href="<?php echo get_category_link($cat_id); ?>"><?php echo $category->name; ?></a></span></h1>

  <?php $gamecounter = 0; ?>
  <?php foreach ($games as $post) : ?>
    <?php $gamecounter++; ?>
    <?php if ($gamecounter <= 2) : ?>
	<div class="gamebox_head_wrapper">	
		<div class="gamebox_image">
			<a href="<?php the_permalink() ?>"><?php myabp_print_thumbnail(85, 76); ?></a>
		</div> 

		<div class="gamebox_info">
		
			<?php if ( function_exists('myscore_check_leaderboard') && myscore_check_leaderboard() ) : ?>
			  <span class="lb_enabled"></span>
			<?php endif; ?>

			<div class="gamebox_title">
				<a href="<?php the_permalink() ?>"><?php myabp_get_title(20); ?></a>
			</div>
		


			<div style="padding:8px 0px 8px 0px;"><?php braygames_get_excerpt(45) ?></div>
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
	</div> 
	<div style="clear:both"></div>


  <?php else: ?>
    <?php if ($gamecounter == 3) : ?>
    <div class="clear"></div>
    <ul class="games-ul">
    <?php endif; ?>
      <li>
        <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"> <?php myabp_get_title(35); ?></a>
      </li>
  <?php endif; ?>
  <?php endforeach; ?>
    </ul>

  <div class="cat_link">
    <a href="<?php echo get_category_link($cat_id); ?>">
      <?php _e("More Games", "braygames"); ?>
    </a>
  </div>
  <div class="clear"></div>
</div>
