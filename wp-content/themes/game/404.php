<?php 
get_header(); 
$color_scheme = get_option('braygames_color_scheme');
?>
	<style>
	.related{
	  width: 100%;
	}
	</style>
	
<div id="content" style="overflow:hidden; width: 980px;">
<center>
<img src="<?php bloginfo('template_directory'); ?>/images/<?php echo $color_scheme ?>/404oops.jpg" alt="<?php bloginfo('name');?>" />
</center>
<?php braygames_action_after_404_content(); ?>
<?php include (TEMPLATEPATH . '/related.php'); ?>
</div>



