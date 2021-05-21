<div class="related">
  <h2 id="tabs_header"><?php _e("More Games", "braygames"); ?></h2>
  <ul>
    <?php
    $blog = get_cat_ID( get_option('braygames_blog_category') );  
    if ( $blog ) {
      $exclude = '&exclude='.$blog.',';
    } else { $exclude = ''; }
    
    $relatedgames = new WP_Query ('showposts=10&orderby=rand'.$exclude);
    if ( $relatedgames->have_posts() ) 
      while ($relatedgames->have_posts()) :
        $relatedgames->the_post();
        ?>
      <li>
        <div class="moregames">
          <?php $screen = get_post_meta($post->ID, 'mabp_thumbnail_url', true); ?>
          <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
            <img src="<?php echo $screen; ?>" height="80" width="80" alt="<?php the_title_attribute(); ?>" align="left" />
           <h3> <?php the_title(); ?></h3>
          </a>
          <?php braygames_get_excerpt(150); ?>
        </div> <?php // end moregames ?>
      </li>
    <?php endwhile; ?>
  </ul>
</div> <?php // end related ?>
<?php wp_reset_query(); ?>