<?php if ( get_option('braygames_slider_type') == 'nivoSlider' )  : ?>
<?php
 function myabp_get_nivoslider( $query ) { 
  // Get the post order
 if ( get_option('braygames_slider_sortby') == 'Random Games') { $bg_sortby = 'rand'; } else { $bg_sortby = 'date'; } 
	if ( get_option('braygames_slidercategory') == '-- All --') { $category   = ''; } 
	else {  
	$category = get_cat_ID( get_option('braygames_slidercategory'));
	}
	$limit = get_option('braygames_slider_limit');
	
    if ( !$category ) {$category = ''; $comma = ''; } else { $comma = ','; }
    $blogcat = get_cat_ID( get_option('braygames_blog_category'));
    if ( !empty($blogcat) ) $exclude = '&cat='.$category.$comma.'-'.$blogcat; else $exclude = $category;

	if ( get_option('braygames_slider_screenshots') == '1' )  { 
	$games = new WP_Query('cat='.$exclude.'&showposts='.$limit.'&meta_key=mabp_screen1_url&meta_compare=>=&meta_value=http&orderby='.$bg_sortby);
	}

	else  { 
	$games = new WP_Query('cat='.$exclude.'&showposts='.$limit.'&orderby='.$bg_sortby);
	}  
   
 

  

  $nivoslider_animation = get_option('braygames_nivoslider_animation'); 
  
if ( !empty($games) ) { 
    ?>
   
    <div class="customslider">
      <div class="slider-wrapper theme-braygames">


		<div class="ribbon"></div>

			<div id="slider" class="nivoSlider">
				<?php  
				while ($games->have_posts()) {
				$games->the_post();     
				  
				$screen = base64_encode(myabp_print_screenshot_url(1, false));

				 if ( myabp_count_screenshots() ) { 
				$img = get_bloginfo('template_directory').'/timthumb.php?src='.$screen.'&w=646&h=240&zc=1&q=100';
				 }	
				 else {
				$img = get_bloginfo('template_directory').'/images/no-screenshot2.jpg';	
				}	  
				  ?>
				  
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<img src="<?php echo $img; ?>" alt="<?php the_title_attribute(); ?>" title="<?php echo wp_strip_all_tags(braygames_get_excerpt(130, false)); ?>" width="646" height="240" />
				</a> 
				<?php } ?>
			</div>
      </div>
    </div>

    <script type="text/javascript">
    jQuery(window).load(function() {
        jQuery('#slider').nivoSlider({
          animSpeed: 600,
          pauseTime: 4000,

          <?php 
          if ( get_option('braygames_slider_auto') == '1') {
            echo 'manualAdvance:false,';
          }
          if ( get_option('braygames_showmarkers') == '0') {
            echo 'controlNav:false,';
          }
          if ( get_option('braygames_showcontrols') == '0') {
            echo 'directionNav:false,';
          }
		  echo 'effect:"'.$nivoslider_animation.'"';
          ?>
        });
    });
    </script>  
    <?php  
  }
}  
  switch ( get_option('braygames_slider_type') ) {
    case 'nivoSlider':
      { 
        myabp_get_nivoslider($game_query);
      } break;
  }
  
  // Reset WordPress query
  wp_reset_query();
?>
<?php endif; ?>