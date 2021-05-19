<?php get_header(); ?>


  <?php   

      // Display game and content
      get_template_part('games', 'play');



  
  // Do some actions before the content wrap ends
  do_action('braygames_before_content_end');
  ?>


<?php get_sidebar(); ?>
<?php get_footer(); ?>