<?php
/**
 * Register Activation Hook
 */
update_option('wpec_quick_view_ultimate_plugin', 'wpec_quick_view_ultimate');
function wpec_quick_view_ultimate_install(){
	WPSC_Settings_Tab_Quick_View_Settings::set_setting();
	update_option('wpec_quick_view_ultimate_version', '1.0.0');
	update_option('wpec_quick_view_ultimate_just_installed', true);
}

function wpec_quick_view_ultimate_init() {
	if ( get_option('wpec_quick_view_ultimate_just_installed') ) {
		delete_option('wpec_quick_view_ultimate_just_installed');
		wp_redirect( admin_url( 'options-general.php?page=wpsc-settings&tab=quick_view_settings', 'relative' ) );
		exit;
	}
	load_plugin_textdomain( 'wpecquickview', false, WPEC_QV_ULTIMATE_FOLDER.'/languages' );
}

// Add language
add_action('init', 'wpec_quick_view_ultimate_init');

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('WPEC_Quick_View_Ultimate', 'plugin_extra_links'), 10, 2 );

$GLOBALS['quick_view_settings'] = new WPSC_Settings_Tab_Quick_View_Settings();
$GLOBALS['wpec_quick_view_ultimate'] = new WPEC_Quick_View_Ultimate();

update_option('wpec_quick_view_ultimate_version', '1.0.0');
?>