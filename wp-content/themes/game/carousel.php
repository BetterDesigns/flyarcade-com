<?php 
if ( get_option('braygames_carousel_active') == '1' )  : 
$color_scheme = get_option('braygames_color_scheme');

 if ( get_option('braygames_buddypress_adminbar') == '1' )  { 
     $caouseltop = '85';
} else{ 
     $caouseltop = '60';
}

 if ( get_option('braygames_admin_toolbar') == '1' )  { 
		
     $caouseltop = '85';
}
else{ 
     $caouseltop = '60';
}
?>

<div id="home-position-top">
<?php  if ( get_option('braygames_carousel_showmarkers') == '1') { $bg_showMarkers  = 'true'; } else { $bg_showMarkers  = 'false';} ?>
<?php if ( get_option('braygames_carousel_auto') == '1') { $bg_auto_scroll  = 'true'; } else { $bg_auto_scroll  = 'false';} ?>
<script type="text/javascript">
	stepcarousel.setup({
	galleryid: 'mygallery',     // id of carousel DIV
	beltclass: 'belt',          // class of inner "belt" DIV containing all the panel DIVs
	panelclass: 'panel',        // class of panel DIVs each holding content
	autostep: {enable:<?php echo $bg_auto_scroll; ?>, moveby:1, pause:3000},		
	panelbehavior: {speed:500, wraparound:true, persist:true},
	defaultbuttons: {enable: <?php echo $bg_showMarkers; ?>, moveby: <?php echo get_option('braygames_carousel_moveby'); ?>, leftnav: ['<?php bloginfo('template_directory'); ?>/images/<?php echo $color_scheme ?>/arrow_left.png', -13, <?php echo $caouseltop ?>], rightnav: ['<?php bloginfo('template_directory'); ?>/images/<?php echo $color_scheme ?>/arrow_right.png', 2, <?php echo $caouseltop ?>]},
	statusvars: ['statusA', 'statusB', 'statusC'], //register 3 variables that contain current panel (start), current panel (last), and total panels
	contenttype: ['external'] //content setting ['inline'] or ['external', 'path_to_external_file']
	})
</script>

<div id="topslides">
  <div id="mygallery" class="stepcarousel">
    <div class="belt">
<?php  
if ( get_option('braygames_carousel_category') == '-- All --') { $category   = ''; } 
	else {  
	$category = get_cat_ID( get_option('braygames_carousel_category'));
	}
	$limit = get_option('braygames_carousel_limit');


    if ( !$category ) {$category = ''; $comma = ''; } else { $comma = ','; }
    $blogcat = get_cat_ID( get_option('braygames_blog_category'));
    if ( !empty($blogcat) ) $exclude = '&cat='.$category.$comma.'-'.$blogcat; else $exclude = $category;
	if ( get_option('braygames_carousel_sortby') == 'Random Games') { $bg_sortby = 'rand'; } else { $bg_sortby = 'date'; }	

        $query = new WP_Query('cat='.$exclude.'&showposts='.$limit.'&orderby='.$bg_sortby);
        while ($query->have_posts()) : 
          $query->the_post();
      ?>
          <div class="panel">
            <a href="<?php the_permalink() ?>" rel="bookmark" title="Play <?php the_title(); ?>">
              <img src="<?php echo get_post_meta($post->ID, 'mabp_thumbnail_url', true); ?>" height='100' width='100' alt="<?php echo the_title(); ?>"/> 
            </a>
            <h2>
              <a href="<?php the_permalink() ?>" rel="bookmark" title="Play <?php the_title(); ?>"><?php the_title(); ?></a>
            </h2>
          </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>
<div class="clear"></div>

</div>
<?php endif; ?>
	<?php 
			  if (is_active_sidebar('home-position-top')) {
			  dynamic_sidebar('home-position-top');
			  } 
	?>