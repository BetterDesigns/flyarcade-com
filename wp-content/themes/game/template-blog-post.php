<?php
/* Single Post Template: Blog Post Template */
?>

<?php get_header(); ?>
	
	<style>
		.single-sidebar {
			margin:0px 0px 10px 0px;
			padding:6px 0px 6px 0px;
			border: 0px;
			background-color: #FFFFFF;
			box-shadow: 0 1px 0 rgba(0, 0, 0, .4);
			-moz-box-shadow: 0 1px 0 rgba(0,0,0,.4);
			-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, .4);
			-webkit-border-radius: 6px;
			-moz-border-radius: 6px;
			border-radius: 6px;
			overflow: hidden;
		}
		.entry p img {
		width: auto;
		height: auto;
		}
	</style>

<div id="content" class="content<?php echo get_option('braygames_sidebar_position'); ?>">  
  <?php if (class_exists('breadcrumb_navigation_xt')) : ?>
    <div class="breadcrumb">
       <?php _e("You Are Here: ", "braygames"); ?>
        <?php
        $mybreadcrumb = new breadcrumb_navigation_xt;
        $mybreadcrumb->display();
        ?>
    </div>
  <?php endif; ?>

  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <div class="singlepage" id="post-<?php the_ID(); ?>">
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
            <?php the_content(__('Read more..', 'braygames')); ?>
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

<?php get_sidebar(); ?>
<?php get_footer(); ?>