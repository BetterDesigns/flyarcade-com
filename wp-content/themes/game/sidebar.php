<div id="sidebar<?php echo get_option('braygames_sidebar_position'); ?>">
  <div class="sidebar">
    <?php   
    // Reset WordPress query vars
    wp_reset_query();
    
    if (is_single()) {
      if (is_active_sidebar('single-sidebar')) {
      dynamic_sidebar('single-sidebar');
      	} else {
				?>
				  <div class="warning">
					<?php _e('This is your Single Sidebar and no widgets have been placed here, yet!', MYAPB_THEMENAME); ?>
					<p>Click <a href="<?php echo get_option('home') ?>/wp-admin/widgets.php" title="">here</a> to setup this sidebar!</p>
				  </div>
				
			<?php } 
    }
    elseif (is_page()) {
      if (is_active_sidebar('page-sidebar')) {
      dynamic_sidebar('page-sidebar');
      	} else {
				?>
				  <div class="warning">
					<?php _e('This is your Page Sidebar and no widgets have been placed here, yet!', MYAPB_THEMENAME); ?>
					<p>Click <a href="<?php echo get_option('home') ?>/wp-admin/widgets.php" title="">here</a> to setup this sidebar!</p>
				  </div>
				
			<?php } 

    }
    elseif (is_category()) {
      if (is_active_sidebar('category-sidebar')) {
      dynamic_sidebar('category-sidebar');
      	} else {
				?>
				  <div class="warning">
					<?php _e('This is your Category Sidebar and no widgets have been placed here, yet!', MYAPB_THEMENAME); ?>
					<p>Click <a href="<?php echo get_option('home') ?>/wp-admin/widgets.php" title="">here</a> to setup this sidebar!</p>
				  </div>
				
			<?php } 
	  }	     
    
    else {
      // Home Sidebar
      if (is_active_sidebar('home-sidebar')) {
      dynamic_sidebar('home-sidebar');
      	} else {
				?>
				  <div class="warning">
					<?php _e('This is your Home Sidebar and no widgets have been placed here, yet!', MYAPB_THEMENAME); ?>
					<p>Click <a href="<?php echo get_option('home') ?>/wp-admin/widgets.php" title="">here</a> to setup this sidebar!</p>
				  </div>
				
			<?php }       
    }
    ?>  
  </div><?php // end class sidebar ?>
</div> <?php // end rightcol ?>
<div class="clear"></div>