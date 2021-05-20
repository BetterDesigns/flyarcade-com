<?php get_header(); ?>

  <div id="content" class="content<?php echo get_option('braygames_sidebar_position'); ?>">
   
      <div class="archive_view">
  
      <?php if (have_posts()) : ?>
        <?php $post = $posts[0]; ?>
        <?php if (is_category()) { ?>
          <h1><span><?php echo single_cat_title(); ?></span></h1>
        <?php } elseif (is_day()) { ?>
          <h1><span><?php _e("Archive for", "braygames"); ?> <?php the_time('F jS, Y'); ?></span></h1>
        <?php } elseif (is_month()) { ?>
          <h1><span><?php _e("Archive for", "braygames"); ?> <?php the_time('F, Y'); ?></span></h1>
        <?php } elseif (is_year()) { ?>
          <h1><span><?php _e("Archive for", "braygames"); ?> <?php the_time('Y'); ?></span></h1>
        <?php } elseif (is_author()) { ?>
          <h1><span><?php _e("Author Archive", "braygames"); ?></span></h1>
        <?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
          <h1><span><?php _e("Blog Archives", "braygames"); ?></span></h1>
        <?php } ?>

        <?php while (have_posts()) : the_post(); ?>
          <div class="cat_view">
                <a class="cat_view_image" href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $post->post_title; ?>">
                <?php if (has_post_thumbnail()): ?>
                  <?php the_post_thumbnail(array(80,80), array('class'	=> "alignleft") ) ?>
                <?php else: ?>
				
                  <img class="cat_view_image" src="<?php myabp_print_thumbnail_url(); ?>" height="80" width="80" alt="<?php echo $post->post_title; ?>" align="left"/>
                <?php endif; ?>
				
				</a>
            

              <div class="entry">

				<?php if ( function_exists('myscore_check_leaderboard') && myscore_check_leaderboard() ) : ?>
				  <span class="lb_enabled"></span>
				<?php endif; ?>
				
					  <h4>
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php myabp_get_title(40); ?></a>
					  </h4>

                <?php braygames_get_excerpt(80); ?> 
				
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
							</div>		
              </div>

          </div>
        <?php endwhile; ?>
      
        <div class="clear"></div>

       <div id="navigation">
        <?php if(function_exists('wp_pagenavi')) : ?>
          <?php wp_pagenavi(); ?>
        <?php else: ?>
          <div class="post-nav clearfix">
			     <p id="previous"><?php next_posts_link(__('Older games &laquo;', 'braygames')) ?></p>
			     <p id="next-post"><?php previous_posts_link(__('&raquo; Newer games', 'braygames')) ?></p>
		      </div>
        <?php endif; ?>  
        </div>

    <?php else: ?>
      <h1 class="title"><?php _e("Not Found", "braygames"); ?></h1>
      <p><?php _e("Sorry, but you are looking for something that isn't here.", "braygames"); ?></p>
    <?php endif; ?>
    </div> <?php // end archive ?>
    
    <?php braygames_action_after_archive_content(); ?>
    
  </div> <?php // end content ?>
  
<?php get_sidebar(); ?>
<?php get_footer(); ?>