<?php

add_action('init','of_options');

if (!function_exists('of_options')) {
function of_options(){


$shortname = str_replace(' ', '_', strtolower(MYAPB_THEMENAME));

$themesettings_path =  get_template_directory_uri() . '/inc/images/layouts/';

//Populate the options array
global $tt_options;
$tt_options = get_option('of_options');


//Access the WordPress Pages via an Array
$tt_pages = array();
$tt_pages_obj = get_pages('sort_column=post_parent,menu_order');
foreach ($tt_pages_obj as $tt_page) {
$tt_pages[$tt_page->ID] = $tt_page->post_name; }
$tt_pages_tmp = array_unshift($tt_pages, "Select a page:");

// Get all categories
$slidercategs_obj = get_categories('hide_empty=0');
$slidercategs = array();
$slidercategs[] = '-- All --';

foreach ($slidercategs_obj as $categ) {
  $slidercategs[$categ->cat_ID] = $categ->cat_name;
}
// Hide categories
$hidecategs_obj = get_categories('hide_empty=0');
$hidecategs = array();

foreach ($hidecategs_obj as $categ) {
  $hidecategs[$categ->cat_ID] = $categ->cat_name;
}
// Get Blog categories
$blogcats_obj = get_categories('hide_empty=0');
$blogcats = array();
$blogcats[] = '-- none --';

foreach ($blogcats_obj as $categ) {
  $blogcats[$categ->cat_ID] = $categ->cat_name;
}

/*-----------------------------------------------------------------------------------*/
/* Create The Custom Site Options Panel
/*-----------------------------------------------------------------------------------*/
$options = array(); // do not delete this line - sky will fall

/* Layout Setting*/
$options[] = array( "name" => __('General Settings','framework_localize'),
			"type" => "heading");

$options[] = array( "name" => __('General settings','framework_localize'),
			"desc" => "",
			"std" => "This section customizes the look of the theme.",
			"type" => "titletab");

$options[] = array( "name" => __('Color Schemes','framework_localize'),
			"desc" => __('Choose the color scheme to use in your website.','framework_localize'),
			"id" => $shortname."_color_scheme",
			"std" => "Default",
			"type" => "images",
			"options" => array(
				'Default' => $themesettings_path . 'cs_default.png',
				'Blue' => $themesettings_path . 'cs_blue.png',
				'Green' => $themesettings_path . 'cs_green.png',
				'Pink' => $themesettings_path . 'cs_pink.png',
				'Black' => $themesettings_path . 'cs_black.png',
				'White' => $themesettings_path . 'cs_white.png',
				));
$options[] = array(  "name"   	=>__('Pregame Page','framework_localize'),
				"desc"   	=> "Enable or disable the pregame page",
				"id"     	=> $shortname."_pregame_page",
				"std"    	=> "enable",
				"type"   	=> "select",
				"options"	=> array( 'disable', 'enable' ) );

$options[] = array( 		"name" 		=>__('End Point Play','framework_localize'),
            	"desc"     => "Define the permalink endpoint for the game play page (Default: play). When you change this then you MUST visit the <a href='".admin_url('options-permalink.php')."' target='_blank'>Permalinks Settings</a> page once!",
        		"id" 		=> $shortname."_endpoint_play",
				"std"    	=> "Play",
        		"type" 		=> "text");

$options[] = array(  	"name"   	=>__('Enable/Disable the Sidebar & Category games','framework_localize'),
				"desc"   	=> "Disable/Enable Sidebar & Category games from Frontpage.",
				"id"     	=> $shortname."_fpcat_sidebar",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array( "name" => __('Blog category','framework_localize'),
				"desc"    => "Select a category that should be used as the regular blog.",
				"id"      => $shortname."_blog_category",
				"std"     => "-- none --",
				"type"    => "select",
				"options" => $blogcats);

$options[] = array( "name" => __('Sidebar Position','framework_localize'),
			"desc" => __('Choose a sidebar position.','framework_localize'),
			"id" => $shortname."_sidebar_position",
			"std" => "right",
			"type" => "images",
			"options" => array(
				'left' => $themesettings_path . 'sidebar_position_l.png',
				'right' => $themesettings_path . 'sidebar_position_r.png',
				));
$options[] = array( "name" => __('Single Sidebar Position','framework_localize'),
			"desc" => __('Choose a Single Sidebar Position.','framework_localize'),
			"id" => $shortname."_singlesidebar_position",
			"std" => "right",
			"type" => "images",
			"options" => array(
				'left' => $themesettings_path . 'singlesidebar_position_l.png',
				'right' => $themesettings_path . 'singlesidebar_position_r.png',
				));

$options[] = array( 		"name" 		=>__('Front Page Games per Box','framework_localize'),
        		"desc" 		=> "Set the number of games that should be shown in each category box on the front page",
        		"id" 		=> $shortname."_box_count",
				"std"    	=> "6",
        		"type" 		=> "text");

$options[] = array(  "name"   	=>__('Front Page Games Order','framework_localize'),
				"desc"   	=> "Select how games should be ordered on the front page. <b>Info: Random order may slow down your site!</b>",
				"id"     	=> $shortname."_order_games",
				"std"    	=> "Descending",
				"type"   	=> "select",
				"options"	=> array( 'Descending', 'Random' ) );

$options[] = array( "name" => __('Home category Presentation','framework_localize'),
			"desc" => __('Select how the category boxes should be displayed on the front page.','framework_localize'),
			"id" => $shortname."_box_design",
			"std" => "cat_box_default",
			"type" => "images",
			"options" => array(
				'cat_box_default' => $themesettings_path . 'cat_box_default.png',
				'cat_box_2' => $themesettings_path . 'cat_box_2.png',
				'cat_box_3' => $themesettings_path . 'cat_box_3.png',
				'cat_box_4' => $themesettings_path . 'cat_box_4.png',
				));

$options[] = array( 		"name" 		=>__('Hide Category Boxes','framework_localize'),
        		"desc" 		=> "By default the homepage displays a list of all of your games. However, if you would like to exlcude certain category from the list you can do so here.",
				"std"    	=> "",
				"type"    => "display_id",
				"options" => $hidecategs);

$options[] = array(
        		"desc" 		=> "Enter the Category IDs you want to exclude (separated by commas)",
        		"id" 		=> $shortname."_exclude_front_cat",
				"std"    	=> "",
        		"type" 		=> "display_id2");

$options[] = array(  "name"   	=>__('Game Embed Box','framework_localize'),
				"desc"   	=> "Enable or disable the game embed box ('Embed this game on your site..').",
				"id"     	=> $shortname."_share_box",
				"std"    	=> "enable",
				"type"   	=> "select",
				"options"	=> array( 'disable', 'enable' ) );

$options[] = array(  "name"   	=>__('Breadcrumb navigation','framework_localize'),
				"desc"   	=> "Enable or disable the breadcrumb navigation.",
				"id"     	=> $shortname."_breadcrumb",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array( 	"name" 		=>__('Front Page Text','framework_localize'),
        		"desc" 		=> "Here you can add some text for SEO purpose or just to introduce your site.",
        		"id" 		=> $shortname."_frontpage_text",
				"std"    	=> "",
        		"type" 		=> "textarea");

$options[] = array( 	"name" 		=>__('Load jQuery from Google CDN','framework_localize'),
        		"desc" 		=> "Enable this if you want to load the jQuery library from Google CDN instead from your site. This could reduce the load time of your site.",
        		"id" 		  => $shortname."_jquery_cdn",
				"std"    	=> "disable",
				"type"   	=> "select",
				"options"	=> array( 'disable', 'enable' ) );

$options[] = array( 	"name" 		=>__('Display Game Screen Shots','framework_localize'),
        		"desc" 		=> "Enable this if you want to display game screenshots on single game pages (only when available).",
        		"id" 		=> $shortname."_dispay_screens",
				"std"    	=> "enable",
				"type"   	=> "select",
				"options"	=> array( 'disable', 'enable' ) );

/* Customize Header */
$options[] = array( "name" => __('Header Settings','framework_localize'),
			"type" => "heading");

$options[] = array( "name" => __('Header Settings','framework_localize'),
			"desc" => "",
			"std" => "Here you can specify all header settings for your template.",
			"type" => "titletab");
$options[] = array(  	"name"   	=>__('Search Area','framework_localize'),
				"desc"   	=> "Enable or disable the Search Area on top left.",
				"id"     	=> $shortname."_search_area",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );
$options[] = array(  	"name"   	=>__('Members Area ','framework_localize'),
				"desc"   	=> "Enable or disable the Members Area on top right.",
				"id"     	=> $shortname."_members_area",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );
$options[] = array(  	"name"   	=>__('WordPress Toolbar ','framework_localize'),
				"desc"   	=> "Hide WordPress toolbar from all users except Administrators.",
				"id"     	=> $shortname."_admin_toolbar",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );
$options[] = array(  	"name"   	=>__('BuddyPress Adminbar ','framework_localize'),
				"desc"   	=> "Hide the BuddyPress admin bar from all.",
				"id"     	=> $shortname."_buddypress_adminbar",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array(  "name"   	=>__('Custom Favicon','framework_localize'),
				"desc"   	=> "Enable or disable the Custom Favicon.",
				"id"     	=> $shortname."_custom_favicon_status",
				"std"    	=> "disable",
				"type"   	=> "select",
				"options"	=> array( 'disable', 'enable' ) );

$options[] = array(
        		"desc" 		=> "The complete URL to your favicon.",
        		"id" 		  => $shortname."_custom_favicon",
				"std"    	=> "",
        		"type" 		=> "text");

$options[] = array(  	"name"    =>__('Custom Logo','framework_localize'),
				"desc"    => "The complete URL to you logo. Maximum dimensions are 230x100px.",
				"id"      => $shortname."_custom_logo",
				"std"    	=> "",
				"type"    => "text");
$options[] = array(  	"name"    =>__('Site Keywords','framework_localize'),
				"desc"    => "The keywords that best describe your website seperated by a coma(,). This is a meta-tag within the HEAD tags.",
				"id"      => $shortname."_custom_site_keywords",
				"std"    	=> "",
				"type"    => "text");
$options[] = array(  	"name"    =>__('Site Description','framework_localize'),
				"desc"    => "A small description of your site for the meta-tag",
				"id"      => $shortname."_custom_sitedesc",
				"std"    	=> "",
				"type"    => "textarea");
$options[] = array(  	"name"    =>__('Description and Keywords Status','framework_localize'),
				"desc"    => "Disable Site Description and Site Keywords if you use a SEO plugin.",
				"id"      => $shortname."_site_sitedesc_status",
				"std"     => "disable",
				"type"    => "select",
				"options" => array('enable', 'disable') );
$options[] = array(  	"name"   	=>__('Custom Header Code','framework_localize'),
				"desc"   	=> "Enable or disable the Custom Header Code.",
				"id"     	=> $shortname."_custom_headercode_status",
				"std"    	=> "disable",
				"type"   	=> "select",
				"options"	=> array( 'disable', 'enable' ) );
$options[] = array( 	"name" 		=> "",
        		"desc" 		=> "Enter special code. ( e.g.: Mochi Verification)",
        		"id" 		  => $shortname."_custom_headercode",
				"std"    	=> "",
        		"type" 		=> "textarea");

/* Customize Footer */
$options[] = array( "name" => __('Footer Settings','framework_localize'),
			"type" => "heading");

$options[] = array( "name" => __('Footer Settings','framework_localize'),
			"desc" => "",
			"std" => "Here you can specify all footer settings for your template.",
			"type" => "titletab");

$options[] = array( 		"name"   	=>__('Custom Footer Code','framework_localize'),
				"desc"   	=> "Enable or disable the Custom Footer Code.",
				"id"     	=> $shortname."_custom_footercode_status",
				"std"    	=> "disable",
				"type"   	=> "select",
				"options"	=> array( 'disable', 'enable' ) );
$options[] = array( 	"name" 		=> "",
        		"desc" 		=> "Enter special code ( e.g.: Google Analytics).",
        		"id" 		  => $shortname."_custom_footercode",
				"std"    	=> "",
        		"type" 		=> "textarea");

/* Home Slider Setting */
$options[] = array( "name" => __('Slider Settings','framework_localize'),
			"type" => "heading");

$options[] = array( "name" => __('Slider Settings','framework_localize'),
			"desc" => "",
			"std" => "This section customizes the slider.",
			"type" => "titletab");

$options[] = array(  	"name"   	=>__('Enable/Disable the Slider','framework_localize'),
				"desc"   	=> "Enable or disable the Slider.",
				"id"     	=> $shortname."_slider_active",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array(  	"name"   	=>__('Games screenshots','framework_localize'),
				"desc"   	=> "If enabled only games which have screenshots will be displayed.",
				"id"     	=> $shortname."_slider_screenshots",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array( "name" => __('Slider Position','framework_localize'),
			"desc" => __('Choose a Home Slider position.','framework_localize'),
			"id" => $shortname."_homeslider_position",
			"std" => "left",
			"type" => "images",
			"options" => array(
				'left' => $themesettings_path . 'homeslider_position_l.png',
				'right' => $themesettings_path . 'homeslider_position_r.png',
				));

$options[] = array(  	"name"   	=> "Slider Type (Recommended:default)",
				"desc"   	=>__('Select a slider that should be shown on the fron page.','framework_localize'),
				"id"     	=> $shortname."_slider_type",
				"std"    	=> "Default",
				"type"   	=> "select",
				"options"	=> array( 'Default', 'nivoSlider'));
$options[] = array(  	"name"    =>__('Slider category','framework_localize'),
				"desc"    => "Select a category that should be displayed on the slider.",
				"id"      => $shortname."_slidercategory",
				"std"     => "-- All --",
				"type"    => "select",
				"options" => $slidercategs);

$options[] = array(  	"name"   	=> "Slider Limit",
				"desc"   	=>__('select the number of slides you want to show.','framework_localize'),
				"id"     	=> $shortname."_slider_limit",
				"std"    	=> "10",
				"type"   	=> "select",
				"options"	=> array( '1','2','3','4','5','6','7','8','9','10'));

$options[] = array(  	"name"   	=> "Sort by",
				"desc"   	=>__('Select the Order of the Slides being displayed.','framework_localize'),
				"id"     	=> $shortname."_slider_sortby",
				"std"    	=> "Newest Games",
				"type"   	=> "select",
				"options"	=> array( 'Random Games','Newest Games'));

$options[] = array(  	"name"   	=> "Animation effects",
				"desc"   	=>__('Select the animation effect for <strong>Default Slider</strong>','framework_localize'),
				"id"     	=> $shortname."_slider_animation",
				"std"    	=> "slide",
				"type"   	=> "select",
				"options"	=> array( 'slide','fade'));

$options[] = array(
				"desc"   	=>__('Select the animation effect for <strong>nivoSlider</strong>','framework_localize'),
				"id"     	=> $shortname."_nivoslider_animation",
				"std"    	=> "random",
				"type"   	=> "select",
				"options"	=> array('random','sliceDownRight','sliceDownLeft','sliceUpRight','sliceUpLeft','sliceUpDown','sliceUpDownLeft','fold','fade','boxRandom','boxRain','boxRainReverse','boxRainGrow','boxRainGrowReverse')
				);

$options[] = array(  	"name"   	=>__('Auto scroll','framework_localize'),
				"desc"   	=> "Enable or disable auto scroll.",
				"id"     	=> $shortname."_slider_auto",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array(  "name"   	=>__('Show Markers.','framework_localize'),
				"desc"   	=> "Show Markers.",
				"id"     	=> $shortname."_showmarkers",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array(  "name"   	=>__('Show Controls.','framework_localize'),
				"desc"   	=> "Show Controls.",
				"id"     	=> $shortname."_showcontrols",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array( "name"   	=>__('Default slider Typography','framework_localize'),
				"desc"   	=>__('Choose a Font','framework_localize'),
				"id"     	=> $shortname."_slider_font",
				"std"    	=> "Cagliostro",
				"type"   	=> "select",
				"options"	=>
				array("Cagliostro", "Molengo" , "Josefin Sans" , "Dancing Script" , "Goudy Bookletter 1911" , "Raleway" ,"Cabin" ,"Lobster" , "Arial" , "Courier New" , "Georgia" , "Helvetica" , "Lucida Grande", "Tahoma" , "Times New Roman", "Verdana" )


				);

/* Home Carousel Setting */
$options[] = array( "name" => __('Carousel Settings','framework_localize'),
			"type" => "heading");

$options[] = array( "name" => __('Carousel Settings','framework_localize'),
			"desc" => "",
			"std" => "This section customizes the Carousel.",
			"type" => "titletab");

$options[] = array(  	"name"   	=>__('Enable/Disable the Carousel','framework_localize'),
				"desc"   	=> "Enable or disable the Carousel.",
				"id"     	=> $shortname."_carousel_active",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array( "name" => __('Carousel Position','framework_localize'),
			"desc" => __('Choose a Carousel Position.','framework_localize'),
			"id" => $shortname."_carousel_position",
			"std" => "top",
			"type" => "images",
			"options" => array(
				'top' => $themesettings_path . 'carousel_position_top.png',
				'bot' => $themesettings_path . 'carousel_position_bot.png',
				'bot2' => $themesettings_path . 'carousel_position_bot2.png',
				));

$options[] = array(  	"name"    =>__('Carousel category','framework_localize'),
				"desc"    => "Select a category that should be displayed on the Carousel.",
				"id"      => $shortname."_carousel_category",
				"std"     => "-- All --",
				"type"    => "select",
				"options" => $slidercategs);

$options[] = array(  	"name"   	=> "Carousel Limit",
				"desc"   	=>__('set the number of games you want to show.','framework_localize'),
				"id"     	=> $shortname."_carousel_limit",
				"std"    	=> "20",
				"type"   	=> "select",
				"options"	=> array( '1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'));

$options[] = array(  	"name"   	=> "Sort by",
				"desc"   	=>__('Select the Order of the Slides being displayed.','framework_localize'),
				"id"     	=> $shortname."_carousel_sortby",
				"std"    	=> "Newest Games",
				"type"   	=> "select",
				"options"	=> array( 'Random Games','Newest Games'));

$options[] = array(  	"name"   	=> "Move by",
				"desc"   	=>__('Move by','framework_localize'),
				"id"     	=> $shortname."_carousel_moveby",
				"std"    	=> "2",
				"type"   	=> "select",
				"options"	=> array( '1','2','3','4','5','6','7','8','9','10'));

$options[] = array(  	"name"   	=>__('Auto scroll','framework_localize'),
				"desc"   	=> "Enable or disable auto scroll.",
				"id"     	=> $shortname."_carousel_auto",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array(  "name"   	=>__('Show Markers.','framework_localize'),
				"desc"   	=> "Show Markers.",
				"id"     	=> $shortname."_carousel_showmarkers",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

/* FrontPages Games Setting */
$options[] = array( "name" => __('FrontPage Games Settings','framework_localize'),
			"type" => "heading");

$options[] = array( "name" => __('FrontPage Games Settings','framework_localize'),
			"desc" => "",
			"std" => "This section customizes the FrontPage games setting.",
			"type" => "titletab");

$options[] = array(  	"name"   	=>__('Enable/Disable the FrontPage games','framework_localize'),
				"desc"   	=> "Enable or disable the FrontPage games.",
				"id"     	=> $shortname."_fpgames_active",
				"std"    	=> "1",
				"type"   	=> "radio",
				"options"	=> array( 'Disable', 'Enable' ) );

$options[] = array( "name" => __('Frontpages Games Presentation','framework_localize'),
			"desc" => __('Select how the Frontpages Games should be displayed.','framework_localize'),
			"id" => $shortname."_fpgames_presentation",
			"std" => "fpgames_view_default",
			"type" => "images",
			"options" => array(
				'fpgames_view_default' => $themesettings_path . 'fpgames_view_default.png',
				'fpgames_view_2' => $themesettings_path . 'fpgames_view_2.png',
				));

$options[] = array(  	"name"    =>__('Frontpages Games Title','framework_localize'),
				"desc"    => "Choose a title for the FrontPage games",
				"id"      => $shortname."_fpgames_title",
				"std"    	=> "Recent Games",
				"type"    => "text");

$options[] = array(  	"name"    =>__('FrontPage games category','framework_localize'),
				"desc"    => "Select a category that should be displayed on the FrontPage games.",
				"id"      => $shortname."_fpgames_category",
				"std"     => "-- none --",
				"type"    => "select",
				"options" => $slidercategs);

$options[] = array(  	"name"   	=> "Games Limit",
				"desc"   	=>__('select the number of games you want to show.','framework_localize'),
				"id"     	=> $shortname."_fpgames_limit",
				"std"    	=> "10",
				"type"   	=> "text",
				);

$options[] = array(  	"name"   	=> "Sort by",
				"desc"   	=>__('Select the Order of the games being displayed.','framework_localize'),
				"id"     	=> $shortname."_fpgames_sortby",
				"std"    	=> "Newest Games",
				"type"   	=> "select",
				"options"	=> array( 'Random Games','Newest Games'));

/* Advertisement Banners */
$options[] = array( "name" => __('Advertisement','framework_localize'),
			"type" => "heading");

$options[] = array( "name" => __('Advertisement Banners','framework_localize'),
			"desc" => "",
			"std" => "Manage your banners and adsense Advertisement areas.",
			"type" => "titletab");

$options[] = array(  	"name"    =>__('Header Banner','framework_localize'),
				"desc"    => "Put your code for 728x90 banner here",
				"id"      => $shortname."_adtop",
				"std"     => "",
				"type"    => "textarea");
$options[] = array(  	"name"    =>__('Content Banner','framework_localize'),
				"desc"    => "Put your code for 300x250 banner here.",
				"id"      => $shortname."_adcontent",
				"std"     => "",
				"type"    => "textarea");
$options[] = array(  	"name"    =>__('Game Preloader Banner','framework_localize'),
				"desc"    => "Put your advertisement code for the game preloader here.",
				"id"      => $shortname."_loadinggameadcode",
				"std"     => "",
				"type"    => "textarea");

/* Progress Bar */
$options[] = array( "name" => __('Progress Bar Settings','framework_localize'),
			"type" => "heading");

$options[] = array( "name" => __('Progress Bar Settings','framework_localize'),
			"desc" => "",
			"std" => "This section customizes the settings of the Progress Bar game site.",
			"type" => "titletab");

$options[] = array(  "name"   	=>__('Progress Bar','framework_localize'),
				"desc"   	=> "Enable or disable the progress bar.",
				"id"     	=> $shortname."_progressbarstatus",
				"std"    	=> "enable",
				"type"   	=> "select",
				"options"	=> array( 'enable', 'disable' ) );
$options[] = array(  "name" 		=> "",
				"desc" 		=> "Select the delay in ms before the progress bar starts to load.",
				"id" 		  => $shortname."_progressbardelay",
				"std" 		=> "0",
				"type" 		=> "select",
				"options" => array( "0", "500", "1000", "1500", "2000", "2500", "3000", "3500", "4000", "4500", "5000", "5500", "6000", "6500", "7500", "8000", "9000" ) );
$options[] = array(  "name" 		=> "",
				"desc" 		=> "Select the load speed index of the progress bar",
				"id" 		  => $shortname."_progressbarspeedindex",
				"std" 		=> "10",
				"type" 		=> "select",
				"options" => array( "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20" ) );
$options[] = array(  "name"   	=> "",
				"desc"   	=> "Enable or disable the text under the progress bar .",
				"id"     	=> $shortname."_progressbartextloadstatus",
				"std"     => "enable",
				"type"    => "select",
				"options" => array('enable', 'disable') );
$options[] = array( 	"name" 		=> "",
        		"desc" 		=> "Progress Bar Text",
		   		"id" 		  => $shortname."_progressbartextload",
				"std"     => "Game loaded, click here to start the game!",
        		"type" 		=> "text");
$options[] = array( 	"name" 		=> "",
        		"desc" 		=> "Set the limit in percent of the loading progress when the Progress Bar Text should be shown",
		   		"id"  		=> $shortname."_progressbartextloadlimit",
				"std" 		=> "35",
				"type" 		=> "select",
				"options" 	=> array( "0", "5", "10", "15", "20", "25", "30", "35", "40", "45", "50", "55", "60", "65", "70", "75", "80", "85", "90", "95", "100" ) );
/* Recommended Plugins */
$options[] = array( "name" => __('Recommended Plugins','framework_localize'),
			"type" => "heading");

$options[] = array( "name" => __('Recommended Plugins','framework_localize'),
			"desc" => "",
			"std" => "A list of recommended plugins, all theme functions will work properly.",
			"type" => "titletab");

$options[] = array( 	"name" 		=>__('MyArcadePlugin Pro','framework_localize'),
				"function"	  => "get_game",
        		"type" 		=> "plugincheck");
$options[] = array( 	"name" 		=>__('MyGameListCreator','framework_localize'),
				"function"	  => "get_game_list",
        		"type" 		=> "plugincheck");
$options[] = array( 	"name" 		=>__('MyScoresPresenter','framework_localize'),
				"function"	  => "myscore_get_latest_scores",
        		"type" 		=> "plugincheck");
$options[] = array( 	"name" 		=>__('WP-PageNavi','framework_localize'),
				"function"	  => "wp_pagenavi",
        		"type" 		=> "plugincheck");
$options[] = array( 	"name" 		=>__('GD Star Rating','framework_localize'),
				"function"	  => "wp_gdsr_blog_rating",
        		"type" 		=> "plugincheck");
$options[] = array( 	"name" 		=>__('WP-PostViews','framework_localize'),
				"function"	  => "the_views",
        		"type" 		=> "plugincheck");
$options[] = array( 	"name" 		=>__('WP Favorite Posts','framework_localize'),
				"function"	  => "wpfp_link",
        		"type" 		=> "plugincheck");
$options[] = array( 	"name" 		=>__('Shareaholic | email, bookmark, share buttons','framework_localize'),
				"function"	  => "selfserv_shareaholic",
        		"type" 		=> "plugincheck");

$options[] = array( 	"name" 		=>__('Breadcrumb NavXT','framework_localize'),
				"function"	  => "bcn_display",
        		"type" 		=> "plugincheck");

$options[] = array( 	"name" 		=>__('Lightbox Plus','framework_localize'),
				"function"	  => "ActivateLBP",
        		"type" 		=> "plugincheck");


update_option('of_template',$options);
update_option('of_shortname',$shortname);

    // Set Default Settings
    foreach ($options as $value) {
      if ( isset($value['id']) && isset($value['std']) ) {
        add_option($value['id'], $value['std']);
      }
    }

}
}
?>