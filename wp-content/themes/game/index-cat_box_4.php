<?php
/**
 * Template to display Horizontal Category Boxes
 */
?>
<style>
	#content{
	  width: 643px;
	}
</style>
<?php global $category, $games, $cat_id; ?>

<div class="gamebox">
 
  <h1><span><a href="<?php echo get_category_link($cat_id); ?>"><?php echo $category->name; ?></a></span></h1>

  <?php // print out all games from this category ?>
  <?php foreach ($games as $post) : ?>
  <div class="game_title">
    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $post->post_title; ?>" >
      <?php myabp_get_title(14); ?>
    </a>
	<div style="margin-bottom: 5px;" class="clear"></div>

    <a href="<?php the_permalink() ?>" class="thumb_link" rel="bookmark" title="<?php echo $post->post_title; ?>" >
      <?php myabp_print_thumbnail(100, 100); ?>
    </a>
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