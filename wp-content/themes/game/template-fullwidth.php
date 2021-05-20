<?php
/* Template Name: Full Width */
?>

<?php get_header(); ?>

<div id="content" class="content<?php echo get_option('braygames_sidebar_position'); ?> full">  

  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <div class="singlepage full" id="post-<?php the_ID(); ?>">
        <div class="title">
          <h1><span><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></span></h1>
          <div class="date"><span class="author">Posted by <?php the_author(); ?></span> <span class="clock"> On <?php the_time('F - j - Y'); ?></span></div>
        </div>

        <div class="cover">
          <div class="entry">
            <?php $banner = get_option('braygames_adcontent'); ?>
              <?php if ($banner) : ?>
                <div style="float:left;margin: 0 10px 10px 0;">
                  <?php echo stripslashes($banner); ?>
                </div>
              <?php endif; ?>            
            <?php the_content(__('Read more...', 'braygames')); ?>
            <div class="clear"></div>
              
            <?php // Display sexy bookmarks if plugin installed ?>
            <?php if( function_exists('selfserv_shareaholic') ) { selfserv_shareaholic(); } ?>            
          </div>
        </div>
      </div>
             
      <div class="clear"></div>
      
      <div class="allcomments"> 
        <?php comments_template(); ?>            
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="title">
      <h2><?php _e('Oops.. Nothing Found!', 'braygames'); ?></h2>
    </div>
    <div class="cover">
      <p>
        <?php _e('I think what you are looking for is not here or it has been moved. Please try a different search..', 'braygames'); ?>
      </p>
    </div>
  <?php endif; ?>
</div> <?php // end content ?>

<?php get_footer(); ?>