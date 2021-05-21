<?php if ( get_option('braygames_slider_type') == 'Default' )  : ?>
<?php
	if ( get_option('braygames_slider_sortby') == 'Random Games') { $bg_sortby = 'rand'; } else { $bg_sortby = 'date'; } 
	if ( get_option('braygames_slider_auto') == '1') { $bg_auto_scroll  = 'true'; } else { $bg_auto_scroll  = 'false';}
	if ( get_option('braygames_showmarkers') == '1') { $bg_showMarkers  = 'true'; } else { $bg_showMarkers  = 'false';} 
	if ( get_option('braygames_showcontrols') == '1') { $bg_showControls  = 'true'; } else { $bg_showControls  = 'false';}  

	if ( get_option('braygames_slidercategory') == '-- All --') { $category   = ''; } 
	else {  
	$category = get_cat_ID( get_option('braygames_slidercategory'));
	}

	$limit = get_option('braygames_slider_limit');
	$bg_animation = get_option('braygames_slider_animation');
	$slider_font = get_option('braygames_slider_font');
	$color_scheme = get_option('braygames_color_scheme'); 


    if ( !$category ) {$category = ''; $comma = ''; } else { $comma = ','; }
    $blogcat = get_cat_ID( get_option('braygames_blog_category'));
    if ( !empty($blogcat) ) $exclude = '&cat='.$category.$comma.'-'.$blogcat; else $exclude = $category;

	if ( get_option('braygames_slider_screenshots') == '1' )  { 
	$games = new WP_Query('cat='.$exclude.'&showposts='.$limit.'&meta_key=mabp_screen1_url&meta_compare=>=&meta_value=http&orderby='.$bg_sortby);
	}

	else  { 
	$games = new WP_Query('cat='.$exclude.'&showposts='.$limit.'&orderby='.$bg_sortby);
	}
	
	if ( !empty($games) ) {
      
	?>  

 	<!-- braygames Slider Fonts Start  -->   
	<link href='http://fonts.googleapis.com/css?family=Cagliostro' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Cabin' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Goudy+Bookletter+1911' rel='stylesheet' type='text/css'>	
	<link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Molengo' rel='stylesheet' type='text/css'>
 
	<style>
	.braygames_slider_left .desc strong{
	  font-family: '<?php echo $slider_font; ?>', sans-serif;
	}
	.braygames_slider_left .desc{
	  font-family: '<?php echo $slider_font; ?>', sans-serif;
	}
	a.bjqs-prev{
	  background-image: url(<?php bloginfo('template_directory'); ?>/images/<?php echo $color_scheme ?>/slider_prev.png);
	}

	a.bjqs-next{
	  background-image: url(<?php bloginfo('template_directory'); ?>/images/<?php echo $color_scheme ?>/slider_next.png);
	}
	</style>
	<!-- braygames Slider /Fonts  End -->  
      <div id="braygames_slider">
        <ul class="bjqs">
		<?php 
		while( $games->have_posts() ) : $games->the_post();
		$screen = base64_encode(myabp_print_screenshot_url(1, false));
		
		 if ( myabp_count_screenshots() ) { 
		$img = get_bloginfo('template_directory').'/timthumb.php?src='.$screen.'&w=320&h=280&zc=1&q=100';

		 }	
		 else {
		$img = get_bloginfo('template_directory').'/images/no-screenshot.jpg';	
		}	

		
		?>
			<li>
				<div class="braygames_slider_left">
					<div class="desc">
				<?php if ( function_exists('myscore_check_leaderboard') && myscore_check_leaderboard() ) : ?>
				  <span style="margin:10px 5px 0 0" class="lb_enabled"></span>
				<?php endif; ?>											
						<a href="<?php the_permalink() ?>"><strong><?php myabp_get_title(50); ?></strong></a>
						<?php braygames_get_excerpt(160) ?>
						
					</div>
					
					<div class="ratings_stars">
								<div class="games_ratings_stars">
								<?php
									if(function_exists('wp_gdsr_render_article_thumbs')) { 
										$postRatingData = wp_gdsr_rating_article(get_the_ID());
										gdsr_render_stars_custom(array(
											"max_value" => gdsr_settings_get('stars'),
											"size" => 20,
											"vote" => $postRatingData->rating
										));	
									}	
								?> 
								</div>
						<span><?php if(function_exists('the_views')) { echo '['; the_views(); echo ']'; } ?></span>
					</div>
<div class="playnow">					
<?php					
 if ( is_rtl() ) {
 ?>
 <a style="background-image: url(<?php bloginfo('template_directory'); ?>/images/Default/play_slider_rtl.png);" href="<?php the_permalink() ?>"><?php _e('Play Now!', 'braygames'); ?></a>
<?php
}
else {
 ?>
  <a href="<?php the_permalink() ?>"><?php _e('Play Now!', 'braygames'); ?></a>
  <?php
}
?>						

					</div>					
				</div>

				<div style="background-image: url(<?php echo $img; ?>);background-repeat:no-repeat; background-position: center;" class="braygames_slider_right">
				
				</div>					
			</li>
		<?php endwhile; ?>
        </ul>
      </div>
      
	<?php }  ?>
 
 <?php
 echo "
 <script>
      jQuery(document).ready(function() {       
        jQuery('#braygames_slider').bjqs({
          animation : '".$bg_animation."',
		  showMarkers: ".$bg_showMarkers.",
		  automatic:".$bg_auto_scroll.",
		  showControls: ".$bg_showControls.",
        });        
      });
 </script> 
";       
?>
<?php endif; ?>