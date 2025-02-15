<?php get_header('buddypress') ?>

	<div id="content" class="content<?php echo get_option('braygames_sidebar_position'); ?>">  
		<div class="singlepage">

			<div id="item-header">
				<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>
			</div>

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav">
					<ul>
						<?php bp_get_displayed_user_nav() ?>
					</ul>
				</div>
			</div>

			<div id="item-body">

				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>
						<?php bp_get_options_nav() ?>
					</ul>
				</div>
				
				<?php global $bp; cp_show_logs($bp->displayed_user->id, get_option('bp_points_logs_per_page_cp_bp'), false); ?>
				
			</div><!-- #item-body -->
		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_sidebar('buddypress') ?>

<?php get_footer('buddypress') ?>