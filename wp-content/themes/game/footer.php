</div> <?php // end fgpage ?>
</div> <?php // end wrapper ?>
 <?php
 include(TEMPLATEPATH . '/inc/scripts.php');
 $color_scheme = get_option('braygames_color_scheme');
 ?>
 <!--[if IE]>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/ie_style.css" media="screen" />
<![endif]-->

<div class="footbar">

<?php // show game list if plugin exists ?>
<?php if (function_exists('get_game_list')) { get_game_list(); } ?>

      <div id="menu">
        <ul>
          <?php
          // show header menu
          wp_nav_menu( array(
              'theme_location'  => 'footer',
              'menu_id' => '',
              'container' => '',
              'fallback_cb' => 'braygames_default_footer_menu'
              )
           );
        ?>
        </ul>
      </div>
	  <?php // end menu ?>

      <div class="clear"></div>
      <div id="footer">
        <?php get_sidebar('footer'); ?>
      </div>

      <?php
      $affiliate_link = get_option('braygames_affiliate');
      if ( empty( $affiliate_link ) ) $affiliate_link = '';
      $affiliate_text = get_option('braygames_affiliate_text');
      if ( empty( $affiliate_text ) ) $affiliate_text = '';
      ?>
        <div class="footer_powered" style="float:left;color:#333333;font-size:11px;margin-left:7px;">
		2021 @ <?php bloginfo('name'); ?>. All Rights Reserved.
		</div>

        <div class="clear"></div>

    </div><?php // end footer ?>

    <?php
      wp_footer();

      // custom footer code
      if ( get_option('braygames_custom_footercode_status') == 'enable' ) { echo stripslashes(get_option('braygames_custom_footercode')); }
    ?>

    <?php /*echo '<!-- '.get_num_queries().' queries. '; timer_stop(1); echo ' seconds. -->'; */ ?>
</body>
</html>