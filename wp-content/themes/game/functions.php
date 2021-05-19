<?php

define ( 'MYAPB_THEMEVERSION', '1.1.0');
define ( 'MYAPB_THEMENAME', 'BrayGames');

/** Include MyArcadePlugin Theme API **/
include_once('inc/myabp_api.php');

/** Include MyArcadePlugin Theme settings **/
include_once('inc/admin-functions.php');
include_once('inc/admin-interface.php');
include_once('inc/theme-settings.php');

/** Include custom widgets **/
include_once('inc/myabp_widgets.php');

/** Include theme actions API **/
include_once('inc/actions.php');

/** Include buddypress integration file **/
include_once('inc/buddypress.php');

add_action('init', 'braygames_init', 0);

/* activate shortcodes */
include_once('inc/arconix-shortcodes/plugin.php');

add_action('widgets_init', 'braygames_widgets_init');
add_action('wp_head', 'braygames_custom_style');

if ( !is_admin() ) {
  add_action('wp_print_scripts', 'braygames_scripts_init');
  add_action('wp_print_styles',  'braygames_stylesheet_init');
}

/** Add filter for blog template **/
add_filter('single_template', 'braygames_blog_template');
// Add action to handle fullscreen
add_filter('template_include', 'braygames_fullscreen');


function braygames_fullscreen( $template ) {

  //echo "full"; die();

  if ( isset($_GET['gid']) && isset($_GET['play']) && ($_GET['play'] == 'fullscreen') ) {
   $template = locate_template('template-fullscreen.php');
  }

  return $template;
}
/**
 * braygames activation function
 */
function braygames_activation( $oldname, $oldtheme = false ) {
  add_rewrite_endpoint( 'play', EP_PERMALINK );
  add_rewrite_endpoint('fullscreen', EP_PERMALINK);
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'braygames_activation', 0);

/**
 * braygames init function - called when WordPress is initialized
 */
function braygames_init() {

  // Check if pre-game page is enabled
  if ( get_option('braygames_pregame_page') == 'enable' ) {
    $endpoint = get_option('braygames_endpoint_play');
    if ( empty($endpoint) ) $endpoint = 'play';
    add_rewrite_endpoint( $endpoint, EP_PERMALINK );
    add_action( 'template_redirect', 'braygames_play_template_redirect' );
  }

}

/**
 * Handles game display when user comes from the pre-game page (game landing page)
 *
 * @global type $wp_query
 * @return type
 */

function braygames_play_template_redirect() {
  global $wp_query;

  $endpoint = get_option('braygames_endpoint_play');
  if ( empty($endpoint) ) return;

  // if this is not a request for game play then bail
  if ( !is_singular() || !isset($wp_query->query_vars[$endpoint]) ) {
    return;
  }

  // Include game play template
  include dirname( __FILE__ ) . '/single-play.php';
  exit;
}


/**
 * Handles full screen redirect
 *
 * @global type $wp_query
 * @return type
 */


/**
 * Generate play permalink
 *
 * @return type
 */
function braygames_play_link() {
  $endpoint = get_option('braygames_endpoint_play');
  if ( empty($endpoint) ) return;
  ?>
  <a href="<?php echo get_permalink() . $endpoint . '/'; ?>" title="<?php echo __("Play", "braygames"); ?> <?php the_title_attribute(); ?>" rel="bookmark nofollow" class="btn-play">
    <?php _e("Play Game", "braygames"); ?>
  </a>
  <?php
}


function braygames_scripts_init() {
  global $wp_scripts;

  $path_to_theme = get_bloginfo('template_url');

  if ( !is_admin() ) {

    // load the jquery script
    if ( get_option('braygames_jquery_cdn') == 'enable' ) {
      if ( is_a($wp_scripts, 'WP_Scripts') && isset($wp_scripts->registered['jquery']) ) {
        if ( isset($wp_scripts->registered['jquery']->ver) && $wp_scripts->registered['jquery']->ver ) {
          $google_jquery_url = ($_SERVER['SERVER_PORT'] == 443 ? "https" : "http") . "://ajax.googleapis.com/ajax/libs/jquery/". $wp_scripts->registered['jquery']->ver ."/jquery.min.js";

          $request = wp_remote_head( $google_jquery_url );

          if ( 200 === wp_remote_retrieve_response_code( $request ) ) {
            wp_deregister_script('jquery');
            wp_register_script('jquery', $google_jquery_url , false, null, false);
          }
        }
      }
    }

    wp_enqueue_script('jquery');

    // Do this only on the front page
    if ( is_front_page() ) {
      // Which slider should be displayed..
	if ( get_option('braygames_slider_active') == '1' )  {
      switch ( get_option('braygames_slider_type') ) {
        case 'nivoSlider': {
          // load the nivoslider script
          wp_enqueue_script('braygames_nivoslider',
              $path_to_theme.'/js/jquery.nivo.slider.pack.js',
              '',
              '',
              false);
        } break;
        case 'Default':
        default: {
          // load default slider script
		  if ( is_rtl() ) {
          wp_enqueue_script('braygames_default_slider',
              $path_to_theme.'/js/basic-jquery-slider-rtl.js',
              '',
              '',
              false
          );
		  }
		  else {
          wp_enqueue_script('braygames_default_slider',
              $path_to_theme.'/js/basic-jquery-slider.js',
              '',
              '',
              false
          );
			}
        } break;

      }
	 }
	 if ( get_option('braygames_carousel_active') == '1' )  {
          wp_enqueue_script('braygames_carousel',
              $path_to_theme.'/js/carousel.js',
              '',
              '',
              false
          );
	  }
    }


    // Include featured scoller code only when the widget is displayed
    //if ( is_active_widget(false, false, 'MABP_Random_Games') ) {
      // load the featured scroller script
      wp_enqueue_script('braygames_featured_scroller',
                $path_to_theme.'/js/spy.js',
                '',
                '',
                false );
    //}

    if ( is_singular() ) {
      wp_enqueue_script('braygames_lights',
                $path_to_theme.'/js/lights.js',
                '',
                '',
                false );
      wp_enqueue_script('braygames_domtabs',
                $path_to_theme.'/js/domtab.js',
                '',
                '',
                false );
      wp_enqueue_script('braygames_wmode2transparent',
                $path_to_theme.'/js/fix_wmode2transparent_swf.js',
                '',
                '',
                false );
      wp_enqueue_script('braygames_favorites',
                $path_to_theme.'/js/favorites.js',
                '',
                '',
                false );
    }
  }
}

if ( ! function_exists( 'braygames_stylesheet_init' ) ) :
function braygames_stylesheet_init() {
  $path_to_theme_css = get_bloginfo('template_url').'/css';
  $color_scheme = get_option('braygames_color_scheme');
  wp_register_style('braygamesStyle', $path_to_theme_css.'/color-'.$color_scheme.'.css');
  wp_enqueue_style( 'braygamesStyle');

  $box_design = get_option('braygames_box_design');
  if ( empty($box_design) ) $box_design = 'cat_box_default';

  wp_register_style('braygamesBoxDesign', $path_to_theme_css.'/'.$box_design.'.css');
  wp_enqueue_style( 'braygamesBoxDesign');

  // Include Frontpages games css
  if ( get_option('braygames_fpgames_presentation') == 'fpgames_view_2' ) {
    wp_register_style('fpgamespresentationStyle', $path_to_theme_css.'/fpgames.css');
    wp_enqueue_style( 'fpgamespresentationStyle');
  }

  // Include Accordion css
  if ( get_option('braygames_slider_type') == 'Accordion' ) {
    wp_register_style('AccordionStyle', $path_to_theme_css.'/accordion.css');
    wp_enqueue_style( 'AccordionStyle');
  } else if ( get_option('braygames_slider_type') == 'nivoSlider' ) {
    wp_register_style('nivoSliderStyle', $path_to_theme_css.'/nivoslider.css');
    wp_enqueue_style( 'nivoSliderStyle');
    wp_register_style('nivoSliderOrman', $path_to_theme_css.'/nivoslider/nivoslider_bg.css');
    wp_enqueue_style( 'nivoSliderOrman');
  }

  //Should we include Buddypress adminbar css?
  if ( defined( 'BP_VERSION' ) ) {
    $bpcss = WP_PLUGIN_URL . '/buddypress/bp-themes/bp-default/_inc/css/adminbar.css';
    wp_register_style('braygamesBuddyPress', $bpcss);
    wp_enqueue_style( 'braygamesBuddyPress');
    wp_register_style('braygamesBuddyPressIntegration', $path_to_theme_css.'/buddypress.css');
    wp_enqueue_style( 'braygamesBuddyPressIntegration');
  }
   //Include Mingle css
  if ( defined( 'MNGL_PLUGIN_NAME' ) ) {
    wp_register_style('braygamesmingleIntegration', $path_to_theme_css.'/mingle.css');
    wp_enqueue_style( 'braygamesmingleIntegration');
  }

  //Include wp_pagenavi css
  if ( function_exists( 'wp_pagenavi' ) ) {
    wp_register_style('braygamespagenaviIntegration', $path_to_theme_css.'/pagenavi-css.css');
    wp_enqueue_style( 'braygamespagenaviIntegration');
  }
  //Include wp_pagenavi css
  if ( function_exists( 'wp_pagenavi' ) ) {
    wp_register_style('braygamespagenaviIntegration', $path_to_theme_css.'/pagenavi-css.css');
    wp_enqueue_style( 'braygamespagenaviIntegration');
  }
}
endif;

function braygames_widgets_init() {
  register_sidebar(
    array('name'          =>'Home Sidebar',
          'id'            =>'home-sidebar',
          'description'   => 'This is the sidebar that gets shown on the home page.',
          'before_widget' => '<div class="home-sidebar">',
          'after_widget'  => '</div>',
          'before_title'  => '<div class="module_title"><span>',
          'after_title'   => '</span></div>',
    )
  );
  register_sidebar(
    array('name'          =>'Home position top',
          'id'            =>'home-position-top',
          'description'   => 'This is the sidebar that gets shown on home-position-top.',
          'before_widget' => '<div id="home-position-top">',
          'after_widget'  => '</div>',
          'before_title'  => '<h2>',
          'after_title'   => '</h2>',
    )
  );
  register_sidebar(
    array('name'          =>'Home position1',
          'id'            =>'home-position1',
          'description'   => 'This is the sidebar that gets shown on home-position1.',
          'before_widget' => '',
          'after_widget'  => '',
          'before_title'  => '<h2>',
          'after_title'   => '</h2>',
    )
  );
   register_sidebar(
    array('name'          =>'Home position2',
          'id'            =>'home-position2',
          'description'   => 'This is the sidebar that gets shown on home-position2.',
          'before_widget' => '',
          'after_widget'  => '',
          'before_title'  => '<h2>',
          'after_title'   => '</h2>',
    )
  );
  register_sidebar(
    array('name'          =>'Home position3',
          'id'            =>'home-position3',
          'description'   => 'This is the sidebar that gets shown on home-position3.',
          'before_widget' => '<div id="home-position3">',
          'after_widget'  => '</div>',
          'before_title'  => '<div style="margin:0px 14px 7px 2px; " class="module_title"><span>',
          'after_title'   => '</span></div>',
    )
  );

  register_sidebar(
    array('name'          =>'Single Sidebar',
          'id'            =>'single-sidebar',
          'description'   => 'This is your sidebar that gets shown on the game or blog pages.',
          'before_widget' => '<div class="single-sidebar">',
          'after_widget'  => '</div>',
          'before_title'  => '<div style="margin:0px 10px 7px 4px; " class="module_title"><span>',
          'after_title'   => '</span></div>',
    )
  );
  register_sidebar(
    array('name'          =>'Single Sidebar2',
          'id'            =>'single-sidebar2',
          'description'   => 'This is your sidebar that gets shown on the game or blog pages.',
          'before_widget' => '<div style="margin-bottom:6px;">',
          'after_widget'  => '</div>',
          'before_title'  => '<div class="single-sidebar2_title"><span>',
          'after_title'   => '</span></div>',
    )
  );

  register_sidebar(
    array('name'          =>'Page Sidebar',
          'id'            =>'page-sidebar',
          'description'   => 'This is your sidebar that gets shown on most of your pages.',
          'before_widget' => '<div class="home-sidebar">',
          'after_widget'  => '</div>',
          'before_title'  => '<div class="module_title"><span>',
          'after_title'   => '</span></div>',
    )
  );

  register_sidebar(
    array('name'          =>'Category Sidebar',
          'id'            =>'category-sidebar',
          'description'   => 'This is your sidebar that gets shown on the category pages.',
          'before_widget' => '<div class="home-sidebar">',
          'after_widget'  => '</div>',
          'before_title'  => '<div class="module_title"><span>',
          'after_title'   => '</span></div>',
    )
  );

  register_sidebar(
    array('name'          =>'Pre-game Sidebar',
          'id'            =>'pregame-sidebar',
          'description'   => 'This is your sidebar that gets shown on the pregame page.',
          'before_widget' => '',
          'after_widget'  => '',
          'before_title'  => '<div class="module_title"><span>',
          'after_title'   => '</span></div>',
    )
  );

  // Area 1, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => __( 'Footer Widget 1', 'braygames' ),
    'id' => 'first-footer-widget-area',
    'description' => __( 'The first footer widget area', 'braygames' ),
    'before_widget' => '',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="module_title"><span>',
    'after_title'   => '</span>',
  ) );

  // Area 2, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => __( 'Footer Widget 2', 'braygames' ),
    'id' => 'second-footer-widget-area',
    'description' => __( 'The second footer widget area', 'braygames' ),
    'before_widget' => '',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="module_title"><span>',
    'after_title'   => '</span>',
  ) );

  // Area 3, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => __( 'Footer Widget 3', 'braygames' ),
    'id' => 'third-footer-widget-area',
    'description' => __( 'The third footer widget area', 'braygames' ),
    'before_widget' => '',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="module_title"><span>',
    'after_title'   => '</span>',
  ) );

  // Area 4, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => __( 'Footer Widget 4', 'braygames' ),
    'id' => 'fourth-footer-widget-area',
    'description' => __( 'The fourth footer widget area', 'braygames' ),
    'before_widget' => '',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="module_title"><span>',
    'after_title'   => '</span>',
  ) );
}


function braygames_default_header_menu() {
    wp_list_categories( 'title_li=&sort_column=menu_order&depth=3');
}

function show_categories_menu($customClass = 'nav clearfix', $addUlContainer = true){
	global $shortname, $category_menu, $exclude_cats, $hide, $strdepth2, $projects_cat;

	//hide empty categories
	$hide = '1';

	//dropdown for categories
	$strdepth2 = "depth=3";

	$args = "orderby=name&order=asc&".$strdepth2."&exclude=&hide_empty=".$hide."&title_li=&echo=0";

	$categories = get_categories($args);

	if (!empty($categories)) {
		$category_menu = wp_list_categories($args);
		if ($addUlContainer) echo('<ul class="'.$customClass.'">');
			if ($category_menu <> '<li>No categories</li>') echo($category_menu);
		if ($addUlContainer) echo('</ul>');
	};

};

function braygames_default_footer_menu() {
  ?>
  <li class="page_item <?php if ( is_home() ) { ?>current_page_item<?php } ?>">
    <a href="<?php echo get_option('url'); ?>/" title="<?php _e("Home", "braygames"); ?>"><?php _e("Home", "braygames"); ?></a>
  </li>
  <?php
  wp_list_pages('sort_column=menu_order&depth=1&title_li=');
}

if ( !function_exists('braygames_favorite_link') ) {
  function myabp_favorite_link($post_id, $opt, $action) {
    $img = get_bloginfo('template_directory').'/images/'.$action.'.png';
    $link = "<a href='?wpfpaction=".$action."&amp;postid=". $post_id . "' title='". $opt ."' rel='nofollow'><img src='".$img."' title='".$opt."' alt='".$opt."' class=".favoritos." /></a>";
    $link = apply_filters( 'wpfp_link_html', $link );
    return $link;
  }
}


if ( !function_exists('braygames_favorite') ) {
  function braygames_favorite() {
    global $post, $action;
    // Works only when WP Favorite Post is active
    if (function_exists('wpfp_link')) {
      if ($action == "remove") {
        $str .= myabp_favorite_link($post->ID, wpfp_get_option('remove_favorite'), "remove");
       } elseif ($action == "add") {
        $str .= myabp_favorite_link($post->ID, wpfp_get_option('add_favorite'), "add");
       } elseif (wpfp_check_favorited($post->ID)) {
        $str .= myabp_favorite_link($post->ID, wpfp_get_option('remove_favorite'), "remove");
       } else {
        $str .= myabp_favorite_link($post->ID, wpfp_get_option('add_favorite'), "add");
       }
       echo $str;
    }
  }
}


if ( !function_exists('braygames_blog_template') ) {
  /**
  * Blog template redirection
  */
  function braygames_blog_template($template) {
    global $post;

    // Get the blog category
    $blog_cat = get_option('braygames_blog_category');

    if ($blog_cat == '-- none --') return $template;

    $blog_category = get_cat_ID( $blog_cat );
    $post_cat = get_the_category();


    if ( is_singular() && !empty($post_cat) && ( in_category($blog_category) || ($blog_category == $post_cat[0]->category_parent) ) ) {
      // overwrite the template file if exist
      if ( file_exists(TEMPLATEPATH . '/template-blog-post.php' ) ) {
        $template = TEMPLATEPATH . '/template-blog-post.php';
      }
    }

    return $template;
  }
}


function braygames_get_excluded_categories() {
  $result = 'exclude=';
  $blog = get_cat_ID( get_option('braygames_blog_category') );
  if ( $blog ) {
    $result = 'exclude='.$blog.',';
  }

  $result .= get_option('braygames_exclude_front_cat');

  return $result;
}


function braygames_get_excerpt($excerpt_length = false, $echo = true) {
  global $post;

  // Get post excerpt
  $text = strip_shortcodes( $post->post_content );
  //$text = apply_filters('the_content', $text);
  $text = str_replace(']]>', ']]&gt;', $text);
  $text = wp_trim_words( $text, 100, '' );

  if ( $excerpt_length ) {
    if ( strlen($text) > $excerpt_length ) {
      $text = mb_substr($text, 0, $excerpt_length).' [...]';
    }
  }

  if ($echo)
    echo $text;
  else
    return $text;
}


/*** REDIRECT MODIFICATION BEGIN ***/
function braygames_login_redirect() {
  global $mngl_options, $pagenow;

  // Check if mingle is instaleld
  if ( defined('MNGL_PLUGIN_NAME') ) {

    if( isset($_GET['action']) ) $theaction = $_GET['action']; else $theaction ='';

    if ($pagenow == 'wp-login.php' && $theaction != 'logout') {
      if ($theaction == 'register') {
        // Redirect to the sign up page
       wp_redirect( get_permalink($mngl_options->signup_page_id) );
      }
      else {
        // Redirect to the login page
        wp_redirect( get_permalink($mngl_options->login_page_id) );
      }
    }
  }
}
//add_action('init', 'braygames_login_redirect');
/*** REDIRECT MODIFICATION END ***/

 if ( get_option('braygames_buddypress_adminbar') == '0' )  {
	define('BP_DISABLE_ADMIN_BAR', true);
}

 if ( get_option('braygames_admin_toolbar') == '0' )  {

	/*** Hide WordPress toolbar from all users except Administrators Start ***/
	function my_function_admin_bar($content) {
	   return ( current_user_can("administrator") ) ? $content : false;
	}
	add_filter( 'show_admin_bar' , 'my_function_admin_bar');
	/*** Hide WordPress toolbar from all users except Administrators End ***/
}
?>