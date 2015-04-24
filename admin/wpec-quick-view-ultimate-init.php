<?php
/**
 * Register Activation Hook
 */
update_option('wpec_quick_view_ultimate_plugin', 'wpec_quick_view_ultimate');
function wpec_quick_view_ultimate_install(){
	update_option('wpec_quick_view_ultimate_version', '1.1.0');
	update_option('wpec_quick_view_lite_version', '1.1.0');

	// Set Settings Default from Admin Init
	global $wpec_qv_admin_init;
	$wpec_qv_admin_init->set_default_settings();

	// Build sass
	global $wpec_qv_less;
	$wpec_qv_less->plugin_build_sass();

	update_option('wpec_quick_view_ultimate_just_installed', true);
}

function wpec_quick_view_ultimate_init() {
	if ( get_option('wpec_quick_view_ultimate_just_installed') ) {
		delete_option('wpec_quick_view_ultimate_just_installed');
		wp_redirect( admin_url( 'admin.php?page=wpec-quick-view', 'relative' ) );
		exit;
	}
	load_plugin_textdomain( 'wpecquickview', false, WPEC_QV_ULTIMATE_FOLDER.'/languages' );
}

// Add language
add_action('init', 'wpec_quick_view_ultimate_init');

// Add custom style to dashboard
add_action( 'admin_enqueue_scripts', array( 'WPEC_Quick_View_Ultimate', 'a3_wp_admin' ) );

// Add admin sidebar menu css
add_action( 'admin_enqueue_scripts', array( 'WPEC_Quick_View_Ultimate', 'admin_sidebar_menu_css' ) );

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('WPEC_Quick_View_Ultimate', 'plugin_extra_links'), 10, 2 );

// Need to call Admin Init to show Admin UI
global $wpec_qv_admin_init;
$wpec_qv_admin_init->init();

$GLOBALS['wpec_quick_view_ultimate'] = new WPEC_Quick_View_Ultimate();

// Check upgrade functions
add_action('plugins_loaded', 'wpec_quick_view_lite_upgrade_plugin');
function wpec_quick_view_lite_upgrade_plugin () {

	// Upgrade to 1.0.2
	if ( version_compare(get_option('wpec_quick_view_ultimate_version'), '1.0.1' ) === -1) {
		$wpec_quick_view_ultimate_on_hover_bt_border_width = get_option( 'wpec_quick_view_ultimate_on_hover_bt_border_width' );
		$wpec_quick_view_ultimate_on_hover_bt_border_style = get_option( 'wpec_quick_view_ultimate_on_hover_bt_border_style' );
		$wpec_quick_view_ultimate_on_hover_bt_border_color = get_option( 'wpec_quick_view_ultimate_on_hover_bt_border_color' );
		$wpec_quick_view_ultimate_on_hover_bt_rounded = get_option( 'wpec_quick_view_ultimate_on_hover_bt_rounded' );
		$wpec_quick_view_ultimate_on_hover_bt_border = array(
			'width' 	=> $wpec_quick_view_ultimate_on_hover_bt_border_width . 'px',
			'style'		=> $wpec_quick_view_ultimate_on_hover_bt_border_style,
			'color'		=> $wpec_quick_view_ultimate_on_hover_bt_border_color,
			'corner'	=> ( $wpec_quick_view_ultimate_on_hover_bt_rounded > 0) ? 'rounded' : 'square',
			'rounded_value' => $wpec_quick_view_ultimate_on_hover_bt_rounded
		);
		update_option( 'wpec_quick_view_ultimate_on_hover_bt_border', $wpec_quick_view_ultimate_on_hover_bt_border );

		$wpec_quick_view_ultimate_on_hover_bt_font_family = get_option( 'wpec_quick_view_ultimate_on_hover_bt_font_family' );
		$wpec_quick_view_ultimate_on_hover_bt_font_size = get_option( 'wpec_quick_view_ultimate_on_hover_bt_font_size' );
		$wpec_quick_view_ultimate_on_hover_bt_font_style = get_option( 'wpec_quick_view_ultimate_on_hover_bt_font_style' );
		$wpec_quick_view_ultimate_on_hover_bt_font_color = get_option( 'wpec_quick_view_ultimate_on_hover_bt_font_color' );
		$wpec_quick_view_ultimate_on_hover_bt_font = array(
			'size' 		=> $wpec_quick_view_ultimate_on_hover_bt_font_size . 'px',
			'face'		=> $wpec_quick_view_ultimate_on_hover_bt_font_family,
			'style'		=> $wpec_quick_view_ultimate_on_hover_bt_font_style,
			'color' 	=> $wpec_quick_view_ultimate_on_hover_bt_font_color
		);
		update_option( 'wpec_quick_view_ultimate_on_hover_bt_font', $wpec_quick_view_ultimate_on_hover_bt_font );

		$wpec_quick_view_ultimate_on_hover_bt_enable_shadow = get_option( 'wpec_quick_view_ultimate_on_hover_bt_enable_shadow' );
		$wpec_quick_view_ultimate_on_hover_bt_shadow_color = get_option( 'wpec_quick_view_ultimate_on_hover_bt_shadow_color' );
		$wpec_quick_view_ultimate_on_hover_bt_shadow = array(
			'enable'	=> $wpec_quick_view_ultimate_on_hover_bt_enable_shadow,
			'h_shadow'	=> '0px',
			'v_shadow'	=> '0px',
			'blur' 		=> '30px',
			'spread'	=> '0px',
			'color'		=> $wpec_quick_view_ultimate_on_hover_bt_shadow_color,
			'inset'		=> '',

		);
		update_option( 'wpec_quick_view_ultimate_on_hover_bt_shadow', $wpec_quick_view_ultimate_on_hover_bt_shadow );

		$wpec_quick_view_ultimate_on_hover_bt_transparent = get_option( 'wpec_quick_view_ultimate_on_hover_bt_transparent' );
		$wpec_quick_view_ultimate_on_hover_bt_transparent = $wpec_quick_view_ultimate_on_hover_bt_transparent * 10;
		update_option( 'wpec_quick_view_ultimate_on_hover_bt_transparent', $wpec_quick_view_ultimate_on_hover_bt_transparent );

		$wpec_quick_view_ultimate_under_image_link_font_family = get_option( 'wpec_quick_view_ultimate_under_image_link_font_family' );
		$wpec_quick_view_ultimate_under_image_link_font_size = get_option( 'wpec_quick_view_ultimate_under_image_link_font_size' );
		$wpec_quick_view_ultimate_under_image_link_font_style = get_option( 'wpec_quick_view_ultimate_under_image_link_font_style' );
		$wpec_quick_view_ultimate_under_image_link_font_color = get_option( 'wpec_quick_view_ultimate_under_image_link_font_color' );
		$wpec_quick_view_ultimate_under_image_link_font = array(
			'size' 		=> $wpec_quick_view_ultimate_under_image_link_font_size . 'px',
			'face'		=> $wpec_quick_view_ultimate_under_image_link_font_family,
			'style'		=> $wpec_quick_view_ultimate_under_image_link_font_style,
			'color' 	=> $wpec_quick_view_ultimate_under_image_link_font_color
		);
		update_option( 'wpec_quick_view_ultimate_under_image_link_font', $wpec_quick_view_ultimate_under_image_link_font );

		$wpec_quick_view_ultimate_under_image_bt_border_width = get_option( 'wpec_quick_view_ultimate_under_image_bt_border_width' );
		$wpec_quick_view_ultimate_under_image_bt_border_style = get_option( 'wpec_quick_view_ultimate_under_image_bt_border_style' );
		$wpec_quick_view_ultimate_under_image_bt_border_color = get_option( 'wpec_quick_view_ultimate_under_image_bt_border_color' );
		$wpec_quick_view_ultimate_under_image_bt_rounded = get_option( 'wpec_quick_view_ultimate_under_image_bt_rounded' );
		$wpec_quick_view_ultimate_under_image_bt_border = array(
			'width' 	=> $wpec_quick_view_ultimate_under_image_bt_border_width . 'px',
			'style'		=> $wpec_quick_view_ultimate_under_image_bt_border_style,
			'color'		=> $wpec_quick_view_ultimate_under_image_bt_border_color,
			'corner'	=> ( $wpec_quick_view_ultimate_under_image_bt_rounded > 0) ? 'rounded' : 'square',
			'rounded_value' => $wpec_quick_view_ultimate_under_image_bt_rounded
		);
		update_option( 'wpec_quick_view_ultimate_under_image_bt_border', $wpec_quick_view_ultimate_under_image_bt_border );

		$wpec_quick_view_ultimate_under_image_bt_font_family = get_option( 'wpec_quick_view_ultimate_under_image_bt_font_family' );
		$wpec_quick_view_ultimate_under_image_bt_font_size = get_option( 'wpec_quick_view_ultimate_under_image_bt_font_size' );
		$wpec_quick_view_ultimate_under_image_bt_font_style = get_option( 'wpec_quick_view_ultimate_under_image_bt_font_style' );
		$wpec_quick_view_ultimate_under_image_bt_font_color = get_option( 'wpec_quick_view_ultimate_under_image_bt_font_color' );
		$wpec_quick_view_ultimate_under_image_bt_font = array(
			'size' 		=> $wpec_quick_view_ultimate_under_image_bt_font_size . 'px',
			'face'		=> $wpec_quick_view_ultimate_under_image_bt_font_family,
			'style'		=> $wpec_quick_view_ultimate_under_image_bt_font_style,
			'color' 	=> $wpec_quick_view_ultimate_under_image_bt_font_color
		);
		update_option( 'wpec_quick_view_ultimate_under_image_bt_font', $wpec_quick_view_ultimate_under_image_bt_font );

		update_option('wpec_quick_view_ultimate_version', '1.0.1');
	}

	if ( version_compare(get_option('wpec_quick_view_lite_version'), '1.1.0' ) === -1) {
		// Build sass
		global $wpec_qv_less;
		$wpec_qv_less->plugin_build_sass();
		update_option('wpec_quick_view_lite_version', '1.1.0');
	}

	update_option('wpec_quick_view_ultimate_version', '1.1.0');
	update_option('wpec_quick_view_lite_version', '1.1.0');
}
?>
