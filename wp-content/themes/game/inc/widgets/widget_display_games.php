<?php

/*
 * Shows random games scroller
 *
 */

if ( !class_exists('WP_Widget_MABP_display_games') ) {
  class WP_Widget_MABP_display_games extends WP_Widget {

    // Constructor
    function WP_Widget_MABP_display_games() {

      $widget_ops   = array('description' => 'Shows Newest, Most Played, or Random games on right/left side bar.');

      $this->WP_Widget('MABP_display_games', 'BrayGames ==> Display Games', $widget_ops);
    }

    // Display Widget
    function widget($args, $instance) {
      extract($args);

      $title = apply_filters('widget_title', esc_attr($instance['title']));
      $limit = $instance['limit'];
      $category = $instance['category'];
	  $bg_sortby = $instance['bg_sortby'];

      global $post, $wpdb;

      echo $before_widget;

      if($title) {
        echo $before_title . $title . $after_title;
      }

      // <-- START --> HERE COMES THE OUTPUT
      if ( !$category ) {$category = ''; $comma = ''; } else { $comma = ','; }
      $blogcat = get_cat_ID( get_option('braygames_blog_category'));
      if ( !empty($blogcat) ) $exclude = '&cat='.$category.$comma.'-'.$blogcat; else $exclude = $category;

	  if(function_exists('the_views') && $bg_sortby=="views") {
        $games = new WP_Query('cat='.$exclude.'&showposts='.$limit.'&v_sortby='.$bg_sortby.'&v_orderby=desc');
      }

	  else {
        $games = new WP_Query('cat='.$exclude.'&showposts='.$limit.'&orderby='.$bg_sortby);
      }



      if ( !empty($games) ) {
        ?>
		<div id="display_games">
			<div id="postlist">
			  <ul>
			<?php
			while( $games->have_posts() ) : $games->the_post();
			  ?>
			  <li>
				<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
				  <img class="fthumb" src="<?php echo get_post_meta($post->ID, 'mabp_thumbnail_url', true); ?>" height="80" width="80" alt="<?php the_title_attribute(); ?>" />

				<?php if ( function_exists('myscore_check_leaderboard') && myscore_check_leaderboard() ) : ?>
				  <span style="margin:7px 10px 0 0" class="lb_enabled"></span>
				<?php endif; ?>

				  <span><?php myabp_get_title(25); ?></span>
				</a>
				 <div style="margin:-6px 0px 0px 0px;"><?php braygames_get_excerpt(27) ?></div>

            <div class="frating">
							<div class="recent_games_ratings">
								<div class="games_ratings_stars">
								<?php
									if(function_exists('wp_gdsr_render_article_thumbs')) {
										$postRatingData = wp_gdsr_rating_article(get_the_ID());
										gdsr_render_stars_custom(array(
											"max_value" => gdsr_settings_get('stars'),
											"size" => 12,
											"vote" => $postRatingData->rating
										));
									}
								?>
								</div>
							<span><?php if(function_exists('the_views')) { the_views(); } ?></span>
							</div>
			</div>

			  </li>
			  <?php endwhile; ?>
			</ul>
			</div>
		</div>
        <div class="clear"></div>
        <?php
      }

      // <-- END --> HERE COMES THE OUTPUT

      echo $after_widget;
    }

    // Update Widget
    function update($new_instance, $old_instance) {

      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['limit'] = strip_tags($new_instance['limit']);
      $instance['category'] = strip_tags($new_instance['category']);
	  $instance['bg_sortby'] = strip_tags($new_instance['bg_sortby']);

      return $instance;
    }

    // Display Widget Control Form
    function form($instance) {

      $instance = wp_parse_args((array) $instance, array('title' => 'Featured Games', 'limit' => 12, 'wcategory' => 0));

      $title = esc_attr($instance['title']);
      $limit = esc_attr($instance['limit']);

      if ( !empty($instance['category'])) {
        $category = esc_attr($instance['category']);
      }
      else {
        $category = false;
      }

      $bg_sortby = 'rand';
      if ( !empty($instance['bg_sortby'])) {
        $bg_sortby = esc_attr($instance['bg_sortby']);
      }

      $slidercategs_obj = get_categories('hide_empty=0');
      $slidercategs = array();
      $slidercategs[0] = 'All';
      foreach ($slidercategs_obj as $categ) {
        $slidercategs[$categ->cat_ID] = $categ->cat_name;
      }

      ?>

      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">
          Title
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>

      <p>
        <label for="<?php echo $this->get_field_id('limit'); ?>">
          Limit
          <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" />
        </label>
      </p>

      <p>
        <label for="wcategory">
          Category<br />
          <select name="<?php echo $this->get_field_name('category'); ?>">
            <?php foreach ($slidercategs as $id => $name) { ?>
            <option value="<?php echo $id;?>" <?php if ( $category == $id) { echo 'selected="selected"'; } ?>><?php echo $name; ?></option>
            <?php } ?>
          </select>
        </label>
      </p>

	<p>
		<label for="<?php echo $this->get_field_id('bg_sortby'); ?>">Sort by:</label>
		<select id="<?php echo $this->get_field_id('bg_sortby'); ?>" name="<?php echo $this->get_field_name('bg_sortby'); ?>" class="widefat" style="width:100%;">
				<option value="rand" <?php selected('rand',  $bg_sortby); ?>>Random Games</option>
				<option value="date" <?php selected('date',  $bg_sortby); ?>>Newest Games</option>
				<option value="views" <?php selected('views',$bg_sortby); ?>>Most Played Games</option>
		</select>
	</p>

      <?php
    }
  }
}
?>