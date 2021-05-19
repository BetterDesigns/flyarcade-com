<?php get_header(); ?>

<div id="content" class="content<?php echo get_option('braygames_sidebar_position'); ?>">  
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <div class="singlepage" id="post-<?php the_ID(); ?>">
        <div class="title">
          <h1><span><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></span></h1>
        </div>

        <div class="cover">
          <div class="entry">
            <?php the_content(__('Read more..', 'braygames')); ?>
            <div class="clear"></div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <h2 class="pagetitle"><?php _e("Sorry, Can't find that Game. But maybe you like one of these games:", "braygames"); ?></h2>
    <div class="related">
      <ul>
        <?php
        global $post;
        $mygames = get_posts('numberposts=30&orderby=rand');
        foreach($mygames as $post) :
        ?>
          <li>
            <div class="moregames">
              <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                <img src="<?php myabp_print_thumbnail_url(); ?>" height="80" width="80" alt="<?php echo $post->post_title; ?>" align="left"/>
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
</div> <?php // end content ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>