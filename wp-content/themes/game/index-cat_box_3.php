<?php
/**
 * Template to display Full Width Content 
 */
?>
<style>
	#content{
	  width: 643px;
	}
</style>
<?php global $games, $category, $cat_id; ?>

<div class="gamebox">
  <h1><span><a href="<?php echo get_category_link($cat_id); ?>"><?php echo $category->name; ?></a></span></h1>
  
  <div class="spacer"></div>

  <?php foreach ($games as $post) : ?>
  <div class="gamebox_head_wrapper">

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
  <?php endforeach; ?>
<div class="clear"></div>
  <div class="cat_link">
    <a href="<?php echo get_category_link($cat_id); ?>">
      <?php _e("More Games", "braygames"); ?>
    </a>
  </div>

  <div class="clear"></div>
</div>