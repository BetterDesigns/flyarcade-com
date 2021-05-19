	<?php 
	$fpgames_view = get_option('braygames_fpgames_presentation');
	?>
<!-- Top Container Start -->

	<div id="top_container">
	
	<!-- home-position1 Start -->
	<div id="home-position1">			
	<?php if ( get_option('braygames_slider_active') == '1' )  { ?>
			<?php include (TEMPLATEPATH . '/nivoslider.php'); ?>
			<?php include (TEMPLATEPATH . '/default_slider.php'); ?>
	<?php } else { ?>

	<?php 
			if (is_active_sidebar('home-position1')) {
			dynamic_sidebar('home-position1');
			} else {
	?>
			<div class="warning">
				<?php _e('This is your home-position1 and no widgets have been placed here, yet!', MYAPB_THEMENAME); ?>
				<p>Click <a href="<?php echo get_option('home') ?>/wp-admin/widgets.php" title="">here</a> to setup this sidebar!</p>
			</div>
					
	<?php }}	?>
	</div>
	<!-- home-position1 End -->
	
	<!-- home-position2 Start-->
		<div id="home-position2">
			<?php
			  if (is_active_sidebar('home-position2')) {
			  dynamic_sidebar('home-position2');
			  } else {
				?>
				  <div class="warning">
					<?php _e('This is your home-position2 and no widgets have been placed here, yet!', MYAPB_THEMENAME); ?>
					<p>Click <a href="<?php echo get_option('home') ?>/wp-admin/widgets.php" title="">here</a> to setup this sidebar!</p>
				  </div>
				
			<?php } ?>
		</div>
	<!-- home-position2 End -->

	</div>
<!-- Top Container End-->	
	<!-- home-position3 Start -->
				
	<?php if ( get_option('braygames_fpgames_active') == '1' )  { ?>
	<div id="home-position3">
			<?php include (TEMPLATEPATH .'/'.$fpgames_view.'.php'); ?>
	</div>
	<?php } ?>

	<?php 
			  if (is_active_sidebar('home-position3')) {
			  dynamic_sidebar('home-position3');
			  } 
	?>
	<!-- home-position3 End -->
	<!-- home-position-top Start-->

			<?php include (TEMPLATEPATH . '/carousel.php'); ?>

	<!-- home-position-top End -->	