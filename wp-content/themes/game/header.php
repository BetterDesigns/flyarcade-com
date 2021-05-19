<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> >
<head profile="http://gmpg.org/xfn/11">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<script defer type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/pngfix.js"></script>
<title>
  <?php if (is_home()) { ?>
    <?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>
  <?php } elseif (is_category()) {?>
    <?php single_cat_title(); ?> - <?php bloginfo('name'); ?>
  <?php } elseif (is_single()) { ?>
    <?php single_post_title(); ?>
  <?php } elseif (is_page()) { ?>
    <?php bloginfo('name'); ?>: <?php single_post_title(); ?>
  <?php } elseif (is_404()) { ?>
     <?php bloginfo('name'); ?> - <?php _e("Page not found", "braygames"); ?>
  <?php } elseif (is_search()) { ?>
     <?php bloginfo('name'); ?> - <?php _e("Search results for", "braygames"); ?> <?php echo esc_html($s, 1); ?>
  <?php } ?>
</title>

<?php if( get_option('braygames_custom_favicon_status') == 'enable' ) : ?>
  <?php $faviconurl = get_option('braygames_custom_favicon'); ?>
  <link rel="shortcut icon" href="<?php echo $faviconurl ?>" />
<?php endif; ?>

<?php if ( get_option('braygames_site_sitedesc_status') == 'enable') : ?>
<meta name="keywords" content="<?php echo stripslashes(get_option('braygames_custom_site_keywords')); ?>" />

<?php if (is_category()) {?>
<meta name="description" content="<?php echo category_description( $category_id ); ?>" />
  <?php } else { ?>
<meta name="description" content="<?php echo stripslashes(get_option('braygames_custom_sitedesc')); ?>" />
 <?php } ?>

<?php endif; ?>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> <?php _e("RSS Feed", "braygames"); ?>" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<?php

	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
 
<?php 
if ( get_option('braygames_custom_headercode_status') == 'enable' ) { echo stripslashes( get_option('braygames_custom_headercode') ); } 
?>

</head>
<body <?php body_class(); ?>>


  <?php
  // Custom Header Image
  $header_img = get_header_image();
  if ( !empty($header_img) ) {
    $header_bg_style = 'style="background-image:url('.$header_img.');"';
  } else {
    $header_bg_style = ''; 
  }  
  ?>

 <div id="header_container" <?php echo $header_bg_style; ?>> 

		<?php // Include the login box ?>
		
		<?php 
		$color_scheme = get_option('braygames_color_scheme'); 
		include (TEMPLATEPATH . '/loginform.php'); 
		?>
		
		<div class="clear"></div>		
	
	<div class="clear"></div>
  <div id="top"> 
    <div class="blogname">
      <h1>
        <a href="<?php bloginfo('url');?>" title="<?php bloginfo('name');?>">
          <?php $custom_logo = get_option('braygames_custom_logo');
            if ( !empty($custom_logo) ) {
              ?><img src="<?php echo $custom_logo; ?>" alt="<?php bloginfo('name');?>"><?php
            } else {
              ?><img src="<?php bloginfo('template_directory'); ?>/images/<?php echo $color_scheme ?>/logo.png" alt="<?php bloginfo('name');?>" /><?php
            }
           ?>
        </a>
      </h1>
    </div>
        
	<!-- Place your 728x90 Ad here -->
	<div id="leaderboard_area">		
     <?php echo stripslashes( get_option('braygames_adtop') ); ?>
	</div>
	<!-- Place your 728x90 Ad here -->
	  
    <div class="clear"></div>
  </div> <?php // end top ?>
  <?php // Header menu ?>

			<!-- Start Menu -->
			<?php $menuClass = 'catmenu';
			$primaryNav = '';
			
			if (function_exists('wp_nav_menu')) {
				$primaryNav = wp_nav_menu( array( 'theme_location' => 'primary', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'echo' => false ) );
			};
			if ($primaryNav == '') { ?>
				<ul class="<?php echo $menuClass; ?>">
					
					<li <?php if (is_front_page()) echo('class="current_page_item"') ?>><a href="<?php bloginfo('url'); ?>"><?php _e('Home','braygames'); ?></a></li>
					

					<?php show_categories_menu($menuClass,false); ?>
					
					
				</ul> <!-- end ul.nav -->
			<?php }
			else echo($primaryNav); ?>		
			<!-- End Menu -->	

 </div> <?php // end header_container ?>
 
 <div id="wrapper">
 
 <?php // Include Breadcrumb 
  if ( is_front_page() ) {
  } else if ( get_option('braygames_breadcrumb') == '1' )  {?>

    <?php if(function_exists('bcn_display')) { ?>
    <div class="breadcrumbs">
	<?php bcn_display();?>
	</div>
	
	<?php } ?>
 
<?php } ?>
  <div class="clear"></div>
  <div id="fgpage">