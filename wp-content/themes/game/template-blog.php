 <?php
/*
Template Name: Blog Template
*/
?>

<?php get_header(); ?>

<?php $blog_category = get_cat_ID( get_option('braygames_blog_category') ); ?>
<?php query_posts("cat=$blog_category" . '&paged=' . get_query_var('paged')); ?>

<?php // content start ?>
<div id="content" class="content<?php echo get_option('braygames_sidebar_position'); ?>">

  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
  
    <div class="singlepage" id="post-<?php the_ID(); ?>">							
      <div class="title">
        <h1><span><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'braygames'); ?> <?php the_title(); ?>"><?php the_title(); ?></a></span></h1>
      </div>
      
      <?php if (has_post_thumbnail()): ?>
      <div class="post-thumbnail">
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(esc_attr__('Permanent Link to %s', 'braygames'), the_title_attribute('echo=0')) ?>">
          <?php the_post_thumbnail() ?>
        </a>
      </div>
      <?php endif ?>      
      
      <div class="cover">
        <div class="entry">
          <?php the_excerpt(); ?>
          <a class="readmore" href="<?php the_permalink(); ?>" title="<?php _e('Read more about', 'braygames'); the_title_attribute(); ?>"><?php _e('Read more', 'braygames'); ?> &raquo</a>
					<div class="clear"></div>
        </div>
      </div>
    </div>
		<?php endwhile; ?>      
  
    <div id="navigation">
      <?php if(function_exists('wp_pagenavi')) : ?>
        <?php wp_pagenavi(); ?>
      <?php else: ?>
        <div class="post-nav clearfix">
          <p id="previous"><?php next_posts_link(__('Older posts &laquo;', 'braygames')) ?></p>
          <p id="next-post"><?php previous_posts_link(__('&raquo; Newer posts', 'braygames')) ?></p>
        </div>
      <?php endif; ?>  
    </div>
  
	<?php else : ?>
    <div class="singlepage">
      <h2 class="title"><?php _e('Not Found' , 'braygames'); ?></h2>
      <div class="cover">
        <div class="entry">
          <p><?php _e("Sorry, but you are looking for something that isn't here.", 'braygames'); ?></p>
        </div>
      </div>
    </div>

	<?php endif; ?>  
 
</div><?php // end content ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>