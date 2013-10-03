<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
A3rev Plugin Admin UI

TABLE OF CONTENTS

- var plugin_name
- var admin_plugin_url
- var admin_plugin_dir
- var admin_pages
- admin_plugin_url()
- admin_plugin_dir()
- admin_pages()
- plugin_extension_start()
- plugin_extension_end()
- pro_fields_before()
- pro_fields_after()

-----------------------------------------------------------------------------------*/

class WPEC_QV_Admin_UI
{
	/**
	 * @var string
	 * You must change to correct plugin name that you are working
	 */
	public $plugin_name = 'wpec_quick_view_ultimate';
	
	/**
	 * @var string
	 */
	public $admin_plugin_url;
	
	/**
	 * @var string
	 */
	public $admin_plugin_dir;
	
	/**
	 * @var array
	 * You must change to correct page you want to include scripts & styles, if you have many pages then use array() : array( 'quotes-orders-mode', 'quotes-orders-rule' )
	 */
	public $admin_pages = array();
	
	
	/*-----------------------------------------------------------------------------------*/
	/* admin_plugin_url() */
	/*-----------------------------------------------------------------------------------*/
	public function admin_plugin_url() {
		if ( $this->admin_plugin_url ) return $this->admin_plugin_url;
		return $this->admin_plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* admin_plugin_dir() */
	/*-----------------------------------------------------------------------------------*/
	public function admin_plugin_dir() {
		if ( $this->admin_plugin_dir ) return $this->admin_plugin_dir;
		return $this->admin_plugin_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* admin_pages() */
	/*-----------------------------------------------------------------------------------*/
	public function admin_pages() {
		$admin_pages = apply_filters( $this->plugin_name . '_admin_pages', $this->admin_pages );
		
		return (array)$admin_pages;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* plugin_extension_start() */
	/* Start of yellow box on right for pro fields
	/*-----------------------------------------------------------------------------------*/
	public function plugin_extension_start( $echo = true ) {
		$optput = '<div id="a3_plugin_panel_container">';
		$optput .= '<div id="a3_plugin_panel_fields">';
		
		$optput = apply_filters( $this->plugin_name . '_plugin_extension_start', $optput );
		
		if ( $echo )
			echo $optput;
		else
			return $optput;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* plugin_extension_start() */
	/* End of yellow box on right for pro fields
	/*-----------------------------------------------------------------------------------*/
	public function plugin_extension_end( $echo = true ) {
		$optput = '</div>';
		$optput .= '<div id="a3_plugin_panel_upgrade_area">';
		$optput .= '<div id="a3_plugin_panel_extensions">';
		$optput .= apply_filters( $this->plugin_name . '_plugin_extension', '' );
		$optput .= '</div>';
		$optput .= '</div>';
		$optput .= '</div>';
		
		$optput = apply_filters( $this->plugin_name . '_plugin_extension_end', $optput );
		
		if ( $echo )
			echo $optput;
		else
			return $optput;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* pro_fields_before() */
	/* Start of yellow box on right for pro fields
	/*-----------------------------------------------------------------------------------*/
	public function pro_fields_before( $echo = true ) {
		echo apply_filters( $this->plugin_name . '_pro_fields_before', '<div class="pro_feature_fields">' );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* pro_fields_after() */
	/* End of yellow border for pro fields
	/*-----------------------------------------------------------------------------------*/
	public function pro_fields_after( $echo = true ) {
		echo apply_filters( $this->plugin_name . '_pro_fields_after', '</div>' );
	}

}

?>