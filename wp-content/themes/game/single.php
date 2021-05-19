<?php get_header(); ?>

  <?php
      // Check if pre-game page is disabled.
      if ( get_option('braygames_pregame_page') == 'enable' ) {
        // Pre-Game Page is enabled
        get_template_part('single', 'pre-game');
      } else {
        // Display game and content without the landing page
        get_template_part('games', 'play');
      }


  // Do some actions before the content wrap ends
  do_action('braygames_before_content_end');
  ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>