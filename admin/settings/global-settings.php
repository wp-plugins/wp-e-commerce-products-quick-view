<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
WPEC Quick View Global Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class WPEC_QV_Global_Settings extends WPEC_QV_Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'settings';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = '';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'wpec_quick_view_global';
	
	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 1;
	
	/**
	 * @var array
	 */
	public $form_fields = array();
	
	/**
	 * @var array
	 */
	public $form_messages = array();
	
	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		$this->init_form_fields();
		//$this->subtab_init();
		
		$this->form_messages = array(
				'success_message'	=> __( 'Quick View Settings successfully saved.', 'wpecquickview' ),
				'error_message'		=> __( 'Error: Quick View Settings can not save.', 'wpecquickview' ),
				'reset_message'		=> __( 'Quick View Settings successfully reseted.', 'wpecquickview' ),
			);
			
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'reset_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'clean_on_deletion' ) );
		
		//add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
		
		// Add yellow border for pro fields
		add_action( $this->plugin_name . '_settings_pro_quick_view_ultimate_type_before', array( $this, 'pro_fields_before' ) );
		add_action( $this->plugin_name . '_settings_pro_quick_view_ultimate_popup_content_after', array( $this, 'pro_fields_after' ) );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {
		
		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {
		global $wpec_qv_admin_interface;
		
		$wpec_qv_admin_interface->reset_settings( $this->form_fields, $this->option_name, false );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* reset_default_settings()
	/* Reset default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function reset_default_settings() {
		global $wpec_qv_admin_interface;
		
		$wpec_qv_admin_interface->reset_settings( $this->form_fields, $this->option_name, true, true );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* clean_on_deletion()
	/* Process when clean on deletion option is un selected */
	/*-----------------------------------------------------------------------------------*/
	public function clean_on_deletion() {
		if ( ( isset( $_POST['bt_save_settings'] ) || isset( $_POST['bt_reset_settings'] ) ) && get_option( 'wpec_quick_view_lite_clean_on_deletion' ) == 0  )  {
			$uninstallable_plugins = (array) get_option('uninstall_plugins');
			unset($uninstallable_plugins[WPEC_QV_ULTIMATE_NAME]);
			update_option('uninstall_plugins', $uninstallable_plugins);
		}
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
		global $wpec_qv_admin_interface;
		
		$wpec_qv_admin_interface->get_settings( $this->form_fields, $this->option_name );
	}
	
	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array ( 
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {
		
		$subtab_data = array( 
			'name'				=> 'settings',
			'label'				=> __( 'Settings', 'wpecquickview' ),
			'callback_function'	=> 'wpec_qv_global_settings_form',
		);
		
		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {
	
		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();
		
		return $subtabs_array;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {
		global $wpec_qv_admin_interface;
		
		$output = '';
		$output .= $wpec_qv_admin_interface->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );
		
		return $output;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {
		
  		// Define settings			
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
		
			array(
            	'name' => __( 'Master Switch', 'wpecquickview' ),
                'type' => 'heading',
           	),
			array(  
				'name' 		=> __( 'Quick View Feature', 'wpecquickview' ),
				'id' 		=> 'wpec_quick_view_ultimate_enable',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 1,
				'free_version'		=> true,
				'checked_value'		=> 1,
				'unchecked_value'	=> 0,
				'checked_label'		=> __( 'Enable', 'wpecquickview' ),
				'unchecked_label' 	=> __( 'Disable', 'wpecquickview' ),
			),
			array(
            	'name' => __( 'Set Display Type', 'wpecquickview' ),
				'id'	=> 'pro_quick_view_ultimate_type',
                'type' => 'heading',
           	),
			array(  
				'name' 		=> __( "Show Quick View as", 'wpecquickview' ),
				'id' 		=> 'wpec_quick_view_ultimate_type',
				'type' 		=> 'onoff_radio',
				'default'	=> 'hover',
				'onoff_options' => array(
					array(
						'val' 				=> 'hover',
						'text'				=> __( 'Button that shows on mouse hover on product image', 'wpecquickview' ) ,
						'checked_label'		=> 'ON',
						'unchecked_label' 	=> 'OFF',
					),
					
					array(
						'val' 				=> 'under',
						'text' 				=> __( 'Show as button or link text under image', 'wpecquickview' ) ,
						'checked_label'		=> 'ON',
						'unchecked_label' 	=> 'OFF',
					) 
				),
			),
			array(
            	'name' => __( 'Select a Pop Up Tool', 'wpecquickview' ),
                'type' => 'heading',
           	),
			array(  
				'name' 		=> __( "Pop Up Tool", 'wpecquickview' ),
				'id' 		=> 'wpec_quick_view_ultimate_popup_tool',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'fancybox',
				'checked_value'		=> 'fancybox',
				'unchecked_value'	=> 'colorbox',
				'checked_label'		=> __( 'FANCYBOX', 'wpecquickview' ),
				'unchecked_label' 	=> __( 'COLORBOX', 'wpecquickview' ),
			),
			
			array(
            	'name' => __( 'Choose Pop Up Content Type', 'wpecquickview' ),
				'id'	=> 'pro_quick_view_ultimate_popup_content',
                'type' => 'heading',
           	),
			array(  
				'name' 		=> __( 'Site Product Page', 'wpecquickview' ),
				'id' 		=> 'wpec_quick_view_ultimate_popup_content',
				'type' 		=> 'onoff_radio',
				'default'	=> 'full_page',
				'onoff_options' => array(
					array(
						'val' 				=> 'full_page',
						'text' 				=> __( 'Open full site in pop-up', 'wpecquickview' ) .' <span class="description">('.__( 'recommended', 'wpecquickview' ).')</span>' ,
						'checked_label'		=> 'ON',
						'unchecked_label' 	=> 'OFF',
					),
				),
			),
			array(  
				'name' 		=> __( "Product Page Content", 'wpecquickview' ),
				'id' 		=> 'wpec_quick_view_ultimate_popup_content',
				'type' 		=> 'onoff_radio',
				'default'	=> 'full_page',
				'onoff_options' => array(
					array(
						'val' 				=> 'product_page',
						'text' 				=> __( 'Product page excluding site header, sidebar and Footer. <span class="description">Note:</span> Any JavaScript features that load per product page will not work with this setting', 'wpecquickview' ) ,
						'checked_label'		=> 'ON',
						'unchecked_label' 	=> 'OFF',
					),
				),
			),
			
			array(
            	'name' => __( 'House Keeping :', 'wpecquickview' ),
                'type' => 'heading',
           	),
			array(  
				'name' 		=> __( 'Clean up on Deletion', 'wpecquickview' ),
				'desc' 		=> __( "On deletion (not deactivate) the plugin will completely remove all tables and data it created, leaving no trace it was ever here.", 'wpecquickview' ),
				'id' 		=> 'wpec_quick_view_lite_clean_on_deletion',
				'type' 		=> 'onoff_checkbox',
				'default'	=> '1',
				'free_version'		=> true,
				'checked_value'		=> '1',
				'unchecked_value'	=> '0',
				'checked_label'		=> __( 'ON', 'wpecquickview' ),
				'unchecked_label' 	=> __( 'OFF', 'wpecquickview' ),
			),
        ));
	}
}

global $wpec_qv_global_settings;
$wpec_qv_global_settings = new WPEC_QV_Global_Settings();

/** 
 * wpec_qv_global_settings_form()
 * Define the callback function to show subtab content
 */
function wpec_qv_global_settings_form() {
	global $wpec_qv_global_settings;
	$wpec_qv_global_settings->settings_form();
}

?>
