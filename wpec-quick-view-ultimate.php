<?php
/*
Plugin Name: WP e-Commerce Products Quick View
Description: This plugin adds the ultimate Quick View feature to your stores Product page, Product category and Product tags listings. Opens the full pages content - add to cart, view cart and click to cloase and keep browsing your store.
Version: 1.0.0
Author: A3 Revolution
Author URI: http://www.a3rev.com/
License: This software is under commercial license and copyright to A3 Revolution Software Development team

	WP e-Commerce Quick View Ultimate. Plugin for the WP e-Commerce.
	Copyright© 2011 A3 Revolution Software Development team
	
	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define('WPEC_QV_ULTIMATE_FILE_PATH', dirname(__FILE__));
define('WPEC_QV_ULTIMATE_DIR_NAME', basename(WPEC_QV_ULTIMATE_FILE_PATH));
define('WPEC_QV_ULTIMATE_FOLDER', dirname(plugin_basename(__FILE__)));
define('WPEC_QV_ULTIMATE_URL', WP_CONTENT_URL.'/plugins/'.WPEC_QV_ULTIMATE_FOLDER);
define('WPEC_QV_ULTIMATE_DIR', WP_CONTENT_DIR.'/plugins/'.WPEC_QV_ULTIMATE_FOLDER);
define('WPEC_QV_ULTIMATE_NAME', plugin_basename(__FILE__) );
define('WPEC_QV_ULTIMATE_TEMPLATE_PATH', WPEC_QV_ULTIMATE_FILE_PATH . '/templates' );
define('WPEC_QV_ULTIMATE_IMAGES_URL',  WPEC_QV_ULTIMATE_URL . '/assets/images' );
define('WPEC_QV_ULTIMATE_JS_URL',  WPEC_QV_ULTIMATE_URL . '/assets/js' );
define('WPEC_QV_ULTIMATE_CSS_URL',  WPEC_QV_ULTIMATE_URL . '/assets/css' );
define('WPEC_QV_ULTIMATE_WP_TESTED', '3.6' );
if(!defined("WPEC_QV_ULTIMATE_AUTHOR_URI"))
    define("WPEC_QV_ULTIMATE_AUTHOR_URI", "http://a3rev.com/shop/wp-e-commerce-quick-view-ultimate/");
	
include 'classes/class-wpec-quick-view-ultimate-style.php';
include 'classes/class-wpec-quick-view-ultimate.php';
include 'admin/classes/class-wpec-quick-view-ultimate-admin.php';

include 'admin/wpec-quick-view-ultimate-init.php';

/**
* Call when the plugin is activated and deactivated
*/
register_activation_hook(__FILE__,'wpec_quick_view_ultimate_install');
function wpec_quick_view_ultimate_uninstall(){
	if ( get_option('wpec_quick_view_ultimate_clean_on_deletion') == 1 ) {
		
		// Delete global settings
		delete_option( 'wpec_quick_view_ultimate_enable' );
		
		// Delete Button Show On Hover
		delete_option( 'wpec_quick_view_ultimate_on_hover_bt_text' );
		delete_option( 'wpec_quick_view_ultimate_on_hover_bt_alink' );
		
		delete_option( 'wpec_quick_view_ultimate_clean_on_deletion' );
	}
}
if ( get_option('quick_view_ultimate_clean_on_deletion') == 1 ) {
	register_uninstall_hook( __FILE__, 'wpec_quick_view_ultimate_uninstall' );
}
?>