<?php get_header(); $color_scheme = get_option('braygames_color_scheme'); ?>
<style>
	#content{
	  box-shadow: 0 0px 0;
	  -moz-box-shadow: 0 0px 0;
	  -webkit-box-shadow: 0 0px 0;
	  border:0;
	  background-color: transparent;
	  width: 660px;
	  height: 100%;
	  padding:0px;
	}

</style>
<?php if (get_option('braygames_homeslider_position') == 'right') { ?>
			<style>
			#home-position1 {
				float:right;
				width: 655px;
				height: 250px;
				padding:7px;
			}
			#home-position2 {
				float:left;
				padding:7px;
				text-align: center;
				width: 300px;
				height: 250px;
			}
			</style>
<?php } ?>
<?php
if (get_option('braygames_carousel_position') == 'top') {
 // Include the top container
 include (TEMPLATEPATH . '/top_container.php');
}
else if (get_option('braygames_carousel_position') == 'bot') {
 // Include the top container
 include (TEMPLATEPATH . '/top_container2.php');
}
else {
 // Include the top container
 include (TEMPLATEPATH . '/top_container3.php');
}

?>

<?php // content start ?>
<div id="content" class="content<?php echo get_option('braygames_sidebar_position'); ?>">

  <?php
  // Get categories, hide empty, exclude defined
  $exclude = braygames_get_excluded_categories();
  $categories = get_categories($exclude);

  // Get box count. If emty then set to 5 (default)
  $braygames_box_count = get_option('braygames_box_count');
  if ( empty($braygames_box_count) ) { $braygames_box_count = 5; }

  // Get the post order
  $order = '';
  if ( get_option('braygames_order_games') == 'Random') { $order = '&orderby=rand'; }

  // Generate the query string.
  $get_post_query = 'numberposts='.intval($braygames_box_count).$order.'&category=';

	if ( get_option('braygames_fpcat_sidebar') == '1' )  {

		  // Generate Game Boxes... Loop through all categories
		  foreach ($categories as $category) {
			// Get the current category ID
			$cat_id = $category->cat_ID;

			// Get games from this category
			$games = get_posts($get_post_query.$cat_id);

			// Check if we have some games in this category
			if ( $games ) {
			  // There are some games.. Create the game box
			  get_template_part('index', get_option('braygames_box_design') );
			}
		  } // END category loop

	}

  ?>

  <?php // Display front page text if exists ?>
  <?php $frontpage_text = stripslashes( get_option('braygames_frontpage_text') ); ?>
  <?php if ( $frontpage_text ): ?>
  <div class="clear"></div>
  <div class="contentbox customtext">
    <?php echo $frontpage_text; ?>
  </div>
  <?php endif; ?>

  <?php braygames_action_after_index_content(); ?>

</div><?php // end content ?>

<?php if ( get_option('braygames_fpcat_sidebar') == '1' )  { ?>
<?php get_sidebar(); ?>
<?php } ?>

<?php get_footer(); ?>