<?php // This code runs whenever in the wp-admin. pb_backupbuddy::$options preloaded.



/********** MISC **********/



// Needed for retina icons in menu.
add_action( 'admin_enqueue_scripts', create_function( '',
	"wp_enqueue_style( 'pb_backupbuddy-wp-admin', '" . pb_backupbuddy::plugin_url() . "/css/wp-admin.css', array(), pb_backupbuddy::settings( 'version' ) );"
	)
);
global $wp_version;
if ( $wp_version >= 3.8 ) {
	add_action( 'admin_enqueue_scripts', create_function( '',
		"wp_enqueue_style( 'pb_backupbuddy-wp-admin-fonticon', '" . pb_backupbuddy::plugin_url() . "/css/wp-admin-fonticon.css', array(), pb_backupbuddy::settings( 'version' ) );"
		)
	);
}


// Enqueue styles for Dashboard Widget
function enqueue_dashboard_stylesheet($hook) {
	if( 'index.php' != $hook )
		return;
	wp_enqueue_style( 'bub_dashboard_widget', pb_backupbuddy::plugin_url() . '/css/dashboard_widget.css' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_dashboard_stylesheet' );


// Set datepicker defaults for schedules page
function pb_backupbuddy_set_scheduled_datepicker_default_format() {
	$cs = get_current_screen();
	if ( ! empty( $cs->base ) && $cs->base == 'backupbuddy_page_pb_backupbuddy_scheduling' ) {
		?>
		<script type="text/javascript">
			jQuery(function($) {
				$.datepicker.setDefaults({
					'dateFormat':'m/d/yy'
				});
			});
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'pb_backupbuddy_set_scheduled_datepicker_default_format', 999 );


// Load backupbuddy class with helper functions.
if ( ! class_exists( 'backupbuddy_core' ) ) {
	require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
}



/* BEGIN HANDLING DATA STRUCTURE UPGRADE */
$default_options = pb_backupbuddy::settings( 'default_options' );
if ( pb_backupbuddy::$options['data_version'] < $default_options['data_version'] ) {
	backupbuddy_core::verify_directories( $skipTempGeneration = true );
	pb_backupbuddy::status( 'details', 'Data structure version of `' . pb_backupbuddy::$options['data_version'] . '` behind current version of `' . $default_options['data_version'] . '`. Running activation upgrade.' );
	require_once( pb_backupbuddy::plugin_path() . '/controllers/activation.php' );
}
/* END HANDLING DATA STRUCTURE UPGRADE */



// Schedule daily housekeeping.
backupbuddy_core::verifyHousekeeping();
backupbuddy_core::verifyLiveCron();


/********** ACTIONS (admin) **********/



// Iterating edits since last updates widget
pb_backupbuddy::add_action( array( 'save_post', 'iterate_edits_since_last' ), 10, 3 );

// Set up reminders if enabled.
if ( pb_backupbuddy::$options['backup_reminders'] == '1' ) {
	pb_backupbuddy::add_action( array( 'load-update-core.php', 'wp_update_backup_reminder' ) );
	pb_backupbuddy::add_action( array( 'post_updated_messages', 'content_editor_backup_reminder_on_update' ) );
}

// Display warning to network activate if running in normal mode on a MultiSite Network.
if ( is_multisite() && !backupbuddy_core::is_network_activated() ) {
	pb_backupbuddy::add_action( array( 'all_admin_notices', 'multisite_network_warning' ) ); // BB should be network activated while on Multisite.
}



/********** AJAX (admin) **********/



pb_backupbuddy::add_ajax( 'backupbuddy' ); // New AJAX wrapper to begin passing all AJAX through this single call to reduce number of registered hooks. POST or GET the var function containing the function.php file to run within controllers/ajax.
//pb_backupbuddy::add_ajax( 'ajax_controller_callback_function' ); // Tell WordPress about this AJAX callback.

// Register BackupBuddy API. As of BackupBuddy v5.0. Access credentials will be checked within callback.
add_action( 'wp_ajax_backupbuddy_api', array( pb_backupbuddy::$_ajax, 'api' ) );
add_action( 'wp_ajax_nopriv_backupbuddy_api', array( pb_backupbuddy::$_ajax, 'api' ) );



/********** DASHBOARD (admin) **********/



// Display stats in Dashboard.
if ( ( !is_multisite() ) || ( is_multisite() && is_network_admin() ) ) { // Only show if standalone OR in main network admin.
	pb_backupbuddy::add_dashboard_widget( 'stats', 'BackupBuddy v' . pb_backupbuddy::settings( 'version' ), 'godmode' );
}



/********** FILTERS (admin) **********/



pb_backupbuddy::add_filter( 'plugin_row_meta', 10, 2 );



/********** PAGES (admin) **********/



$icon = '';

if ( is_multisite() && backupbuddy_core::is_network_activated() && !defined( 'PB_DEMO_MODE' ) ) { // Multisite installation.
	if ( defined( 'PB_BACKUPBUDDY_MULTISITE_EXPERIMENT' ) && ( PB_BACKUPBUDDY_MULTISITE_EXPERIMENT == TRUE ) ) { // comparing with bool but loose so string is acceptable.
		
		if ( is_network_admin() ) { // Network Admin pages
			pb_backupbuddy::add_page( '', 'backup', array( pb_backupbuddy::settings( 'name' ), __( 'Backup', 'it-l10n-backupbuddy' ) ), 'manage_network', $icon );
			if ( '1' !== pb_backupbuddy::$options['hide_live'] ) {
				pb_backupbuddy::add_page( 'backup', 'live', __( 'Stash Live', 'it-l10n-backupbuddy' ), 'manage_network' );
			}
			pb_backupbuddy::add_page( 'backup', 'migrate_restore', __( 'Migrate, Restore', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'destinations', __( 'Remote Destinations', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'multisite_import', __( 'MS Import (beta)', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'server_tools', __( 'Server Tools', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'malware_scan', __( 'Malware Scan', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'scheduling', __( 'Schedules', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'settings', __( 'Settings', 'it-l10n-backupbuddy' ), 'manage_network' );
		} else { // Subsite pages.			
			$export_note = '';
			
			$options = get_site_option( 'pb_' . pb_backupbuddy::settings( 'slug' ) );
			$multisite_export = $options[ 'multisite_export' ];
			unset( $options );

			if ( $multisite_export == '1' ) { // Settings enable admins to export. Set capability to admin and higher only.
				$capability = pb_backupbuddy::$options['role_access'];
				$export_title = '<span title="Note: Enabled for both subsite Admins and Network Superadmins based on BackupBuddy settings">' . __( 'MS Export (experimental)', 'it-l10n-backupbuddy' ) . '</span>';
			} else { // Settings do NOT allow admins to export; set capability for superadmins only.
				$capability = 'manage_network';
				$export_title = '<span title="Note: Enabled for Network Superadmins only based on BackupBuddy settings">' . __( 'MS Export SA (experimental)', 'it-l10n-backupbuddy' ) . '</span>';
			}
			
			//pb_backupbuddy::add_page( '', 'getting_started', array( pb_backupbuddy::settings( 'name' ), 'Getting Started' . $export_note ), $capability );
			pb_backupbuddy::add_page( '', 'multisite_export', array( pb_backupbuddy::settings( 'name' ), $export_title ), $capability, $icon );
			pb_backupbuddy::add_page( 'multisite_export', 'malware_scan', __( 'Malware Scan', 'it-l10n-backupbuddy' ), $capability );
		}
		
	} else { // PB_BACKUPBUDDY_MULTISITE_EXPERIMENT not in wp-config / set to TRUE.
		pb_backupbuddy::status( 'error', 'Multisite detected but PB_BACKUPBUDDY_MULTISITE_EXPERIMENT definition not found in wp-config.php / not defined to boolean TRUE.' );
	}
	
} else { // Standalone site.
	
	pb_backupbuddy::add_page( '', 'backup', array( pb_backupbuddy::settings( 'name' ), __( 'Backup', 'it-l10n-backupbuddy' ) ), pb_backupbuddy::$options['role_access'], $icon );
	if ( '1' !== pb_backupbuddy::$options['hide_live'] ) {
		pb_backupbuddy::add_page( 'backup', 'live', __( 'Stash Live', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	}
	pb_backupbuddy::add_page( 'backup', 'migrate_restore', __( 'Restore / Migrate', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	pb_backupbuddy::add_page( 'backup', 'destinations', __( 'Remote Destinations', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	pb_backupbuddy::add_page( 'backup', 'server_tools', __( 'Server Tools', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	pb_backupbuddy::add_page( 'backup', 'malware_scan', __( 'Malware Scan', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	pb_backupbuddy::add_page( 'backup', 'scheduling', __( 'Schedules', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	pb_backupbuddy::add_page( 'backup', 'settings', __( 'Settings', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
}



/********** OTHER (admin) **********/
add_filter( 'contextual_help', 'pb_backupbuddy_contextual_help', 10, 3 );
function pb_backupbuddy_contextual_help( $contextual_help, $screen_id, $screen ) { // Loads help from file in controllers/help/:PAGENAME:.php
	if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
		return;
	}
	
	// WordPress pre-v3.3 so no contextual help.
	if ( ! method_exists( $screen, 'add_help_tab' ) ) {
		return $contextual_help;
	}
	
	// Not a backupbuddy page.
	if ( false === stristr( $screen_id, 'backupbuddy' ) ) {
		return $contextual_help;
	}
	
	// Load page-specific help.
	$page = str_replace( 'pb_backupbuddy_', '', str_replace( 'toplevel_page_', '', str_replace( 'backupbuddy_page_pb_backupbuddy_', '', $screen_id ) ) );
	$helpFile = dirname( __FILE__ ) . '/controllers/help/' . $page . '.php';
	if ( file_exists( $helpFile ) ) {
		include( $helpFile );
	}
	
	// Global help.
	$screen->add_help_tab(
	array(
	'id'      => 'pb_backupbuddy_additionalhelp',
	'title'   => __( 'Tutorials & Support', 'it-l10n-backupbuddy' ),
	'content' => '<p>
					<a href="http://ithemes.com/publishing/getting-started-with-backupbuddy/" target="_blank">' . __( 'Getting Started eBook', 'it-l10n-backupbuddy' ) . '</a>
					<br>
					<a href="http://ithemes.tv/category/backupbuddy/" target="_blank">' . __( 'Getting Started Videos', 'it-l10n-backupbuddy' ) . '</a>
					<br>
					<a href="http://ithemes.com/codex/" target="_blank">' . __( 'Knowledge Base & Tutorials', 'it-l10n-backupbuddy' ) . '</a>
					<br>
					<a href="http://ithemes.com/support/" target="_blank"><b>' . __( 'Support', 'it-l10n-backupbuddy' ) . '</b></a>
				</p>',
	));
	
	return $contextual_help;
	
} // End pb_backupbuddy_contextual_help().



/***** BEGIN STASH LIVE ADMIN BAR *****/
function backupbuddy_live_admin_bar_menu( $wp_admin_bar ) {
	if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) || '1' === pb_backupbuddy::$options['hide_live'] ) {
		return;
	}
	
    $args = array(
        'id'    => 'backupbuddy_stash_live_admin_bar',
        'title' => 'BackupBuddy Stash Live',
    );
    $wp_admin_bar->add_node( $args );

    $child_args = array();

    array_push( $child_args, array(
        'id'     => 'backupbuddy_stash_live_admin_bar_stats',
        'title'  => '<div class="backupbuddy-stash-live-admin-bar-stats-container"><span class="backupbuddy-pulsing-orb"></span><span class="backupbuddy-stash-live-admin-bar-stats-text">' . __( 'Loading...', 'it-l10n-backupbuddy' ) . '</span></div>',
        'href'   =>  admin_url( 'admin.php?page=pb_backupbuddy_live' ),
        'parent' => 'backupbuddy_stash_live_admin_bar',
    ));

    foreach ( $child_args as $args ) {
        $wp_admin_bar->add_node($args);
    }

}
function backupbuddy_live_admin_bar_script() {
	if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
		return;
	}
	
	wp_register_script( 'backupbuddy_live_admin_bar', pb_backupbuddy::plugin_url() . '/destinations/live/admin_bar.js', array( 'jquery') );
	wp_localize_script(
		'backupbuddy_live_admin_bar',
		'backupbuddy_live_admin_bar_translations',
		array(
			'currently' => __( 'Currently', 'it-l10n-backupbuddy' )
		)
	);
	wp_enqueue_script( 'backupbuddy_live_admin_bar' );
	wp_enqueue_style( 'backupbuddy_live_admin_bar_style', pb_backupbuddy::plugin_url() . '/destinations/live/admin_bar.css' );
}
function backupbuddy_live_statsPoll() {
	if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
		return;
	}
	
	include( pb_backupbuddy::plugin_path() . '/destinations/live/_statsPoll.php' );
}
if ( 'disconnect' != pb_backupbuddy::_GET( 'live_action' ) ) { // If not disconnecting from Live this pageload.
	foreach( pb_backupbuddy::$options['remote_destinations'] as $destination ) { // Look for Live destination.
		if ( ( 'live' == $destination['type'] ) && ( isset( $destination['show_admin_bar'] ) ) && ( '1' == $destination['show_admin_bar'] ) ) {
			add_action( 'admin_bar_menu', 'backupbuddy_live_admin_bar_menu', 999 );
			add_action( 'admin_enqueue_scripts', 'backupbuddy_live_admin_bar_script' );
			add_action( 'admin_footer', 'backupbuddy_live_statsPoll', 999 );
			break;
		}
	}
}
/***** END STASH LIVE ADMIN BAR *****/

// v7.0 announcement banner.
function backupbuddy_admin_notices() {
	if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
		return;
	}
	
	if ( version_compare( phpversion(), '5.3', '>=' ) ) {
		if ( is_network_admin() ) {
			$stashlive_url = network_admin_url( 'admin.php' );
		} else {
			$stashlive_url = admin_url( 'admin.php' );
		}
		$stashlive_url .= '?page=pb_backupbuddy_live';

		pb_backupbuddy::disalert( 'backupbuddy_version_seven', '<p><span class="backupbuddy-icon-drive"></span>Real-time backups are here. <a class="backupbuddy-nag-button pb_backupbuddy_disalert" href="' . $stashlive_url . '" alt="' . pb_backupbuddy::ajax_url( 'disalert' ) . '">Get Started</a><a class="backupbuddy-nag-button" href="https://ithemes.com/backupbuddy-stash-live-is-here" target="_blank">See What\'s New</a></p>' );
		wp_enqueue_style( 'backupbuddy_version_seven_style', pb_backupbuddy::plugin_url() . '/css/version_seven.css' );
	}
}
if ( ( !is_multisite() ) || ( is_multisite() && is_network_admin() ) ) { // Only show if standalone OR in main network admin.
	add_action( 'admin_notices', 'backupbuddy_admin_notices' );
}


// Global admin javascript files.
function backupbuddy_global_admin_scripts() {
	wp_register_script( 'backupbuddy_global_admin_scripts', pb_backupbuddy::plugin_url() . '/js/global_admin.js', array( 'jquery') );
	wp_enqueue_script( 'backupbuddy_global_admin_scripts' );
}
add_action( 'admin_enqueue_scripts', 'backupbuddy_global_admin_scripts' );

