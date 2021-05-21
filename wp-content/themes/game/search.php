<?php get_header(); ?>
<div id="content" class="content<?php echo get_option('braygames_sidebar_position'); ?>">
  <?php if (have_posts()) : ?>
    <h2 class="module_title"><span><?php _e("Search Results", "braygames"); ?>:</span></h2>
    <?php while (have_posts()) : the_post(); ?>
      <div style="width: 100%;" class="single_game" id="post-<?php the_ID(); ?>">
        <br />
		<div class="title">
          <h2>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'braygames'); ?> <?php the_title(); ?>"><?php the_title(); ?></a>
          </h2>
          
            <?php if(function_exists('wp_gdsr_render_article_thumbs')) : ?>
              <div style="background-color:#FFFFFF;float:right; padding-left:10px;">
					<?php
						if(function_exists('wp_gdsr_render_article_thumbs')) { 
							wp_gdsr_render_article(10, false, 'soft');
						}	
					?> 
              </div>
			  <div style="clear:both"></div>
            <?php endif; ?>          
        </div>
  
        <div class="cover">
          <div class="entry">
            <?php if ($wp_query->current_post == 0) : ?> 
              <?php // Show content banner if configured ?>
              <?php $banner = get_option('braygames_adcontent'); // 300 x 250 ?>
              <?php if ($banner) : ?>
                <div class="adright">
                  <?php echo stripslashes($banner); ?>
                </div>
              <?php endif; ?>
            <?php endif; ?>
            
            <a href="<?php the_permalink() ?>" rel="bookmark" title="Play <?php the_title(); ?>"><img src="<?php myabp_print_thumbnail_url(); ?>" width="100" height="100" style="float:left;"></a>
            <?php braygames_get_excerpt(180); ?>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="Play <?php the_title(); ?>"><?php _e('Play Now', 'braygames'); ?></a>
                        
            <div class="clear"></div>
          </div> <?php // end entry ?>
        </div>  <?php // end cover ?>
      </div> <?php // end single_game ?>
    <?php endwhile; ?>

    <div id="navigation">
      <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>  
    </div>
    
  <?php else : ?>
    <h2 class="module_title"><span><?php _e("Sorry, Can't find that Game. But maybe you like one of these games:", "braygames"); ?></span></h2>
<div style="width: 100%;" class="related">
        <ul>
          <?php
          global $post;
          $mygames = get_posts('numberposts=20&orderby=rand');
          foreach($mygames as $post) :
          ?>
            <li>
              <div class="moregames">
                <?php $screen = get_post_meta($post->ID, 'mabp_thumbnail_url', true); ?>
                <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                  <img src="<?php echo $screen; ?>" height="80" width="80" alt="" align="left"/>
                  <?php the_title(); ?>
                </a>
                <br />
                <?php braygames_get_excerpt(300); ?>
              </div> <?php // end moregames ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div> <?php // end related ?>
  <?php endif; ?>
</div> <?php // content ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>