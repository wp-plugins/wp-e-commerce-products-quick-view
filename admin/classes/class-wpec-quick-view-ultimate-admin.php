<?php
/**
 * WPSC_Settings_Tab_Quick_View_Settings Class
 *
 * Class Function into WP e-Commerce plugin
 *
 * Table Of Contents
 *
 * __construct()
 
 */
class WPSC_Settings_Tab_Quick_View_Settings
{
	public function WPSC_Settings_Tab_Quick_View_Settings() {
		$this->init();
	}
	
	public function init () {
		add_filter( 'wpsc_settings_tabs', array(&$this, 'add_wpec_settings_tabs' ) );
		add_action( 'admin_footer', array(&$this, 'wp_admin_footer_scripts') );
	}
	
	/*
    * Admin Functions
    */
	public function set_setting($reset=false){
		if ( get_option('wpec_quick_view_ultimate_enable', '') == '' || $reset ) {
			update_option('wpec_quick_view_ultimate_enable','1');
		}
		
		if ( get_option('wpec_quick_view_ultimate_on_hover_bt_text') == '' || $reset ) {
			update_option('wpec_quick_view_ultimate_on_hover_bt_text', __('QUICKVIEW', 'wpecquickview') );
		}
		if ( get_option('wpec_quick_view_ultimate_on_hover_bt_alink') == '' || $reset ) {
			update_option('wpec_quick_view_ultimate_on_hover_bt_alink','center');
		}
	}
	
	public function add_wpec_settings_tabs($tabs){
		$tabs['quick_view_settings'] = __('Quick View', 'wpecquickview');
		return $tabs;
	}
	
	public function is_submit_button_displayed() {
		return true;
	}
	public function is_update_message_displayed() {
		if(isset($_REQUEST['wpsc-update-options'])){
			$this->update_settings($_POST);
		}
		return true;
	}
	public function update_settings($request){
		
		if( is_array($request) && count($request) > 0 ){
			unset($request['wpsc_admin_action']);
			unset($request['wpsc-update-options']);
			unset($request['_wp_http_referer']);
			unset($request['updateoption']);
			foreach($request as $key=>$value){
				update_option($key,$value);
			}
			if (!isset($request['wpec_quick_view_ultimate_enable'])) update_option('wpec_quick_view_ultimate_enable', 0);
			if (!isset($request['wpec_quick_view_ultimate_on_hover_bt_enable_shadow'])) update_option('wpec_quick_view_ultimate_on_hover_bt_enable_shadow', 0);
			if (!isset($request['wpec_quick_view_ultimate_clean_on_deletion'])) {
				update_option('wpec_quick_view_ultimate_clean_on_deletion', 0);
				$uninstallable_plugins = (array) get_option('uninstall_plugins');
				unset($uninstallable_plugins[WPEC_QV_ULTIMATE_NAME]);
				update_option('uninstall_plugins', $uninstallable_plugins);
			}
		}
	}
	public function display() {
		global $wpdb;
		$wpec_quick_view_ultimate= wp_create_nonce("wpec_quick_view_ultimate");
		$transparents = array();
		for( $i = 1; $i <= 10; $i++ ){
			$transparents[$i] = $i.__("0%", 'wpecquickview');
		}
		$borders = array();
		for( $i = 0; $i <= 20; $i++ ){
			$borders[$i] = $i.__("px", 'wpecquickview');
		}
		$borders_style = array( 'solid' => __("Solid", 'wpecquickview'),'dotted' => __("Dotted", 'wpecquickview'),'dashed' => __("Dashed", 'wpecquickview'),'double' => __("Double", 'wpecquickview') );
		$fonts = array( 
			'Arial, sans-serif'													=> __( 'Arial', 'wpecquickview' ),
			'Verdana, Geneva, sans-serif'										=> __( 'Verdana', 'wpecquickview' ),
			'Trebuchet MS, Tahoma, sans-serif'								=> __( 'Trebuchet', 'wpecquickview' ),
			'Georgia, serif'													=> __( 'Georgia', 'wpecquickview' ),
			'Times New Roman, serif'											=> __( 'Times New Roman', 'wpecquickview' ),
			'Tahoma, Geneva, Verdana, sans-serif'								=> __( 'Tahoma', 'wpecquickview' ),
			'Palatino, Palatino Linotype, serif'								=> __( 'Palatino', 'wpecquickview' ),
			'Helvetica Neue, Helvetica, sans-serif'							=> __( 'Helvetica*', 'wpecquickview' ),
			'Calibri, Candara, Segoe, Optima, sans-serif'						=> __( 'Calibri*', 'wpecquickview' ),
			'Myriad Pro, Myriad, sans-serif'									=> __( 'Myriad Pro*', 'wpecquickview' ),
			'Lucida Grande, Lucida Sans Unicode, Lucida Sans, sans-serif'	=> __( 'Lucida', 'wpecquickview' ),
			'Arial Black, sans-serif'											=> __( 'Arial Black', 'wpecquickview' ),
			'Gill Sans, Gill Sans MT, Calibri, sans-serif'					=> __( 'Gill Sans*', 'wpecquickview' ),
			'Geneva, Tahoma, Verdana, sans-serif'								=> __( 'Geneva*', 'wpecquickview' ),
			'Impact, Charcoal, sans-serif'										=> __( 'Impact', 'wpecquickview' ),
			'Courier, Courier New, monospace'									=> __( 'Courier', 'wpecquickview' ),
			'Century Gothic, sans-serif'										=> __( 'Century Gothic', 'wpecquickview' ),
		);
		$fonts_size = array();
		for( $i = 9; $i <= 40; $i++ ){
			$fonts_size[$i] = $i.__("px", 'wpecquickview');
		}
		$fonts_style = array( 'normal' => __("Normal", 'wpecquickview'),'italic' => __("Italic", 'wpecquickview'),'bold' => __("Bold", 'wpecquickview'),'bold_italic' => __("Bold/Italic", 'wpecquickview') );
		?>
        <style type="text/css">
		.description{font-family: sans-serif;font-size: 12px;font-style: italic;color:#666666;} .subsubsub { white-space:normal;} .subsubsub li { white-space:nowrap;} 
		.form-table { margin:0; }
		#a3_plugin_panel_container { position:relative; margin-top:10px;}
		#a3_plugin_panel_fields {width:65%; float:left; margin-top:10px;}
		#a3_plugin_panel_upgrade_area { position:relative; margin-left: 65%; padding-left:10px;}
		#a3_plugin_panel_extensions { border:2px solid #E6DB55;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; color: #555555; margin: 0px; padding: 5px 10px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); background:#FFFBCC; }
		.pro_feature_fields { margin-right: -12px; position: relative; z-index: 10; border:2px solid #E6DB55;-webkit-border-radius:10px 0 0 10px;-moz-border-radius:10px 0 0 10px;-o-border-radius:10px 0 0 10px; border-radius: 10px 0 0 10px; border-right: 2px solid #FFFFFF; }
		.pro_feature_fields h3 { margin:6px 5px; }
		.pro_feature_fields p { margin-left:5px; }
		.pro_feature_fields  .form-table td, .pro_feature_fields .form-table th { padding:4px 10px; }		
        </style>
        <div id="a3_plugin_panel_container">
        <div class="a3_subsubsub_section">
            <ul class="subsubsub">
            	<li><a href="#global-settings" class="current"><?php _e('Settings', 'wpecquickview'); ?></a> | </li>
                <li><a href="#hover-position-style"><?php _e('Hover Position & Style', 'wpecquickview'); ?></a> | </li>
     			<li><a href="#under-image-style"><?php _e('Under Image Style', 'wpecquickview'); ?></a> | </li>
                <li><a href="#fancybox-pop-up"><?php _e('Fancy Box Pop Up', 'wpecquickview'); ?></a> | </li>
                <li><a href="#colourbox-pop-up"><?php _e('Colour Box Pop Up', 'wpecquickview'); ?></a></li>
            </ul>
            <br class="clear">
            <div id="a3_plugin_panel_fields">
            <div class="section" id="global-settings">
                <h3><?php _e('Global Settings', 'wpecquickview'); ?></h3>
                <table class="form-table">
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_enable"><?php _e('Enable/Disable', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <label><input type="checkbox" <?php checked( get_option('wpec_quick_view_ultimate_enable', 1 ), 1 ); ?> value="1" id="wpec_quick_view_ultimate_enable" name="wpec_quick_view_ultimate_enable" /> <span class=""><?php _e('Check to activate the Quick View feature.', 'wpecquickview');?></span></label>
                    </td>
                  </tr>
                </table>
                
                <div class="pro_feature_fields">
                <table class="form-table">
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_type"><?php _e('Show Button on', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <label><input disabled="disabled" type="radio" checked="checked" value="hover" id="wpec_quick_view_ultimate_type" name="" /> <span class=""><?php _e('Show on mouse hover over image.', 'wpecquickview');?></span></label>
                      <br />
                      <label><input disabled="disabled" type="radio" value="under" id="wpec_quick_view_ultimate_type_under" name="" /> <span class=""><?php _e('Show as button or link text under image.', 'wpecquickview');?></span></label>
                    </td>
                  </tr>
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_popup_tool"><?php _e("Popup Tool", 'wpecquickview');?></label></th>
                    <td class="forminp">
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_popup_tool" style="width:300px">
                            <option selected="selected" value="fancybox"><?php _e('Fancybox (Default)','wpecquickview'); ?></option>
                            <option value="colorbox"><?php _e('ColorBox','wpecquickview'); ?></option>
                        </select>
                    </td>
                  </tr>
                </table>
                
                <h3><?php _e('Pop-Up Content', 'wpecquickview'); ?></h3>
                <table class="form-table">
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_popup_content1"><?php _e('Product Page Content', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <label><input disabled="disabled" type="radio" checked="checked" value="product_page" id="wpec_quick_view_ultimate_popup_content1" name="" /> <span class=""><?php _e('Check will show Product page excluding site header, sidebar and Footer. Note any plugin that runs its JavaScript just on the product page cannot run because this is not the actual product page url.', 'wpecquickview');?></span></label>
                    </td>
                  </tr>
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_popup_content2"><?php _e('Site Product Page', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <label><input disabled="disabled" type="radio" value="full_page" id="wpec_quick_view_ultimate_popup_content2" name="" /> <span class=""><?php _e('Check will show Product page, site header, sidebar and footer.', 'wpecquickview');?></span></label>
                    </td>
                  </tr>
                </table>
                </div>
                
                <h3><?php _e('House Keeping', 'wpecquickview');?> :</h3>		
                <table class="form-table">
                    <tr valign="top" class="">
                        <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_clean_on_deletion"><?php _e('Clean up on Deletion', 'wpecquickview');?></label></th>
                        <td class="forminp">
                           <label><input <?php checked( get_option('wpec_quick_view_ultimate_clean_on_deletion', 0 ), 1); ?> type="checkbox" value="1" id="wpec_quick_view_ultimate_clean_on_deletion" name="wpec_quick_view_ultimate_clean_on_deletion"> 
							<?php _e('Check this box and if you ever delete this plugin it will completely remove all tables and data it created, leaving no trace it was ever here.', 'wpecquickview');?></label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="section" id="hover-position-style">
            	<h3><?php _e('Button Show On Hover', 'wpecquickview'); ?></h3>
                <table class="form-table">
                  <tr valign="top">
					<th class="titledesc" scope="row"><label for="quick_view_ultimate_on_hover_bt_text"><?php _e('Button Text', 'wpecquickview'); ?></label></th>
                    <td class="forminp forminp-text">
                    	<input type="text" class="" value="<?php esc_attr_e( stripslashes( get_option('wpec_quick_view_ultimate_on_hover_bt_text', __( 'QUICKVIEW', 'wpecquickview' ) ) ) );?>" style="width:300px;" id="wpec_quick_view_ultimate_on_hover_bt_text" name="wpec_quick_view_ultimate_on_hover_bt_text"> <span class="description"><?php _e('Text for Quick View Button Show On Hover', 'wpecquickview');?></span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_on_hover_bt_alink"><?php _e("Button Align", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php
                    $wpec_quick_view_ultimate_on_hover_bt_alink = get_option('wpec_quick_view_ultimate_on_hover_bt_alink', 'center');
                    ?>
                        <select class="chzn-select" size="1" name="wpec_quick_view_ultimate_on_hover_bt_alink" id="wpec_quick_view_ultimate_on_hover_bt_alink" style="width:120px">
                            <option <?php selected( $wpec_quick_view_ultimate_on_hover_bt_alink, 'top' );?> value="top"><?php _e('Top','wpecquickview'); ?></option>
                            <option <?php selected( $wpec_quick_view_ultimate_on_hover_bt_alink, 'center' );?> value="center"><?php _e('Center','wpecquickview'); ?></option>
                            <option <?php selected( $wpec_quick_view_ultimate_on_hover_bt_alink, 'bottom' );?> value="bottom"><?php _e('Bottom','wpecquickview'); ?></option>
                        </select>
                    </td>
                  </tr>
                </table>
                
                <div class="pro_feature_fields">
                <table class="form-table">
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_on_hover_bt_padding_tb"><?php _e('Button Padding Top/Bottom','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-text">
                    	<input disabled="disabled" type="text" class="" value="7" style="width:120px;" id="wpec_quick_view_ultimate_on_hover_bt_padding_tb" name=""> <span class="description">px <?php _e('(Padding Top/Bottom from Button text to Button border Show On Hover)','wpecquickview'); ?></span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_on_hover_bt_padding_lr"><?php _e('Button Padding Left/Right','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-text">
                    	<input disabled="disabled" type="text" class="" value="17" style="width:120px;" id="wpec_quick_view_ultimate_on_hover_bt_padding_lr" name=""> <span class="description">px <?php _e('(Padding Left/Right from Button text to Button border Show On Hover)','wpecquickview'); ?></span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_on_hover_bt_bg"><?php _e('Background Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#999999" style="width: 120px;" id="wpec_quick_view_ultimate_on_hover_bt_bg" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_on_hover_bt_bg"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #999999</span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_on_hover_bt_bg_from"><?php _e('Background Colour Gradient From','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#999999" style="width: 120px;" id="wpec_quick_view_ultimate_on_hover_bt_bg_from" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_on_hover_bt_bg_from"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #999999</span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_on_hover_bt_bg_to"><?php _e('Background Colour Gradient To','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#999999" style="width: 120px;" id="wpec_quick_view_ultimate_on_hover_bt_bg_to" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_on_hover_bt_bg_to"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #999999</span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_on_hover_bt_transparent"><?php _e("Button Transparency", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_on_hover_bt_transparent = 5; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_on_hover_bt_transparent" style="width:120px">
                        <?php
						foreach( $transparents as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_on_hover_bt_transparent ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_on_hover_bt_border_width"><?php _e("Button Border Weight", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_on_hover_bt_border_width = 1; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_on_hover_bt_border_width" style="width:120px">
                        <?php
						foreach( $borders as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_on_hover_bt_border_width ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_on_hover_bt_border_style"><?php _e("Button Border Style", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_on_hover_bt_border_style = 'solid'; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_on_hover_bt_border_style" style="width:120px">
                        <?php
						foreach( $borders_style as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_on_hover_bt_border_style ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_on_hover_bt_border_color"><?php _e('Button Border Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#FFFFFF" style="width: 120px;" id="wpec_quick_view_ultimate_on_hover_bt_border_color" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_on_hover_bt_border_color"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #FFFFFF</span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_on_hover_bt_rounded"><?php _e('Border Rounded','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-text">
                    	<input disabled="disabled" type="text" class="" value="3" style="width:120px;" id="wpec_quick_view_ultimate_on_hover_bt_rounded" name=""> <span class="description"><?php _e('px','wpecquickview'); ?></span>                    </td>
                </tr>
                
                <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_on_hover_bt_font_family"><?php _e("Button Font", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_on_hover_bt_font_family = 'Arial, sans-serif'; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_on_hover_bt_font_family" style="width:120px">
                        <?php
						foreach( $fonts as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_on_hover_bt_font_family ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_on_hover_bt_font_size"><?php _e("Button Size", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_on_hover_bt_font_size = 14; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_on_hover_bt_font_size" style="width:120px">
                        <?php
						foreach( $fonts_size as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_on_hover_bt_font_size ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_on_hover_bt_font_style"><?php _e("Button Style", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_on_hover_bt_font_style = 'normal'; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_on_hover_bt_font_style" style="width:120px">
                        <?php
						foreach( $fonts_style as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_on_hover_bt_font_style ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_on_hover_bt_font_color"><?php _e('Button Font Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#FFFFFF" style="width: 120px;" id="wpec_quick_view_ultimate_on_hover_bt_font_color" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_on_hover_bt_font_color"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #FFFFFF</span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_on_hover_bt_enable_shadow"><?php _e('Button Shadow', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <label><input disabled="disabled" type="checkbox" checked="checked" value="1" id="wpec_quick_view_ultimate_on_hover_bt_enable_shadow" name="" /> <span class=""><?php _e('Activating this setting show shadow for button.', 'wpecquickview');?></span></label>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_on_hover_bt_shadow_color"><?php _e('Button Shadow Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#999999" style="width: 120px;" id="wpec_quick_view_ultimate_on_hover_bt_shadow_color" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_on_hover_bt_shadow_color"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #999999</span>
                    </td>
                  </tr>
                </table>
                </div>
            </div>
            <div class="section" id="under-image-style">
            	<div class="pro_feature_fields">
            	<h3><?php _e('Button/Hyperlink Show under Image', 'wpecquickview'); ?></h3>
                <table class="form-table">
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_bt_type"><?php _e('Button or Hyperlink Type', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <label><input class="wpec_quick_view_ultimate_under_image_change" type="radio" checked="checked" value="link" id="wpec_quick_view_ultimate_under_image_bt_type" name="" /> <span class=""><?php _e('Hyperlink', 'wpecquickview');?></span></label>
                      <br />
                      <label><input class="wpec_quick_view_ultimate_under_image_change" type="radio" value="button" id="wpec_quick_view_ultimate_under_image_bt_type_button" name="" /> <span class=""><?php _e('Button', 'wpecquickview');?></span></label>
                    </td>
                  </tr>
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_bt_alink"><?php _e("Button or Hyperlink Align", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php
                    $wpec_quick_view_ultimate_under_image_bt_alink = 'center';
                    ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_under_image_bt_alink" style="width:120px">
                            <option <?php selected( $wpec_quick_view_ultimate_under_image_bt_alink, 'left' ); ?> value="left"><?php _e('Left','wpecquickview'); ?></option>
                            <option <?php selected( $wpec_quick_view_ultimate_under_image_bt_alink, 'center' ); ?> value="center"><?php _e('Center','wpecquickview'); ?></option>
                            <option <?php selected( $wpec_quick_view_ultimate_under_image_bt_alink, 'right' ); ?> value="right"><?php _e('Right','wpecquickview'); ?></option>
                        </select>
                    </td>
                  </tr>
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_under_image_bt_margin"><?php _e('Button or Hyperlink magrin','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-text">
                    	<input disabled="disabled" type="text" class="" value="10" style="width:120px;" id="wpec_quick_view_ultimate_under_image_bt_margin" name=""> <span class="description">px <?php _e('Above/Below','wpecquickview'); ?></span>
                    </td>
                  </tr>
            	</table>
                <div class="show_under_image_hyperlink_styling">
                <h3><?php _e('Hyperlink Styling', 'wpecquickview'); ?></h3>
                <table class="form-table">
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_link_text"><?php _e('Hyperlink Text', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <input disabled="disabled" type="text" class="" value="<?php _e( 'Quick View', 'wpecquickview' );?>" style="width:300px;" id="wpec_quick_view_ultimate_under_image_link_text" name=""> <span class="description"><?php _e('Text for Hyperlink show under image','wpecquickview'); ?></span>
                    </td>
                  </tr>
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_link_font_family"><?php _e("Hyperlink Font", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_under_image_link_font_family = 'Arial, sans-serif'; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_under_image_link_font_family" style="width:120px">
                        <?php
						foreach( $fonts as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_under_image_link_font_family ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_link_font_size"><?php _e("Hyperlink Font Size", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_under_image_link_font_size = 12; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_under_image_link_font_size" style="width:120px">
                        <?php
						foreach( $fonts_size as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_under_image_link_font_size ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_link_font_style"><?php _e("Hyperlink Font Style", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_under_image_link_font_style = 'bold'; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_under_image_link_font_style" style="width:120px">
                        <?php
						foreach( $fonts_style as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_under_image_link_font_style ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_under_image_link_font_color"><?php _e('Hyperlink Font Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#000000" style="width: 120px;" id="wpec_quick_view_ultimate_under_image_link_font_color" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_under_image_link_font_color"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #000000</span>
                    </td>
                  </tr>
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_under_image_link_font_hover_color"><?php _e('Hyperlink Hover Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#999999" style="width: 120px;" id="wpec_quick_view_ultimate_under_image_link_font_hover_color" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_under_image_link_font_hover_color"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #999999</span>
                    </td>
                  </tr>
                </table>
                
                </div>
                <div class="show_under_image_button_styling">
                <h3><?php _e('Button Styling', 'wpecquickview'); ?></h3>
                <table class="form-table">
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_bt_text"><?php _e('Button Text', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <input disabled="disabled" type="text" class="" value="<?php _e( 'Quick View', 'wpecquickview' ); ?>" style="width:300px;" id="wpec_quick_view_ultimate_under_image_bt_text" name=""> <span class="description"><?php _e('Text for Button show under image','wpecquickview'); ?></span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_bt_padding_tb"><?php _e('Button Padding Top/Bottom', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <input disabled="disabled" type="text" class="" value="7" style="width:120px;" id="wpec_quick_view_ultimate_under_image_bt_padding_tb" name=""> <span class="description"><?php _e('Padding Top/Bottom from Button text to Button border show under image','wpecquickview'); ?></span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_bt_padding_lr"><?php _e('Button Padding Left/Right', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <input disabled="disabled" type="text" class="" value="8" style="width:120px;" id="wpec_quick_view_ultimate_under_image_bt_padding_lr" name=""> <span class="description"><?php _e('Padding Left/Right from Button text to Button border show under image','wpecquickview'); ?></span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_under_image_bt_bg"><?php _e('Background Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#000000" style="width: 120px;" id="wpec_quick_view_ultimate_under_image_bt_bg" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_under_image_bt_bg"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #000000</span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_under_image_bt_bg_from"><?php _e('Background Colour Gradient From','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#000000" style="width: 120px;" id="wpec_quick_view_ultimate_under_image_bt_bg_from" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_under_image_bt_bg_from"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #000000</span>
                    </td>
                  </tr>
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_under_image_bt_bg_to"><?php _e('Background Colour Gradient To','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#000000" style="width: 120px;" id="wpec_quick_view_ultimate_under_image_bt_bg_to" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_under_image_bt_bg_to"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #000000</span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_bt_border_width"><?php _e("Button Border Weight", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_under_image_bt_border_width = 1; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_under_image_bt_border_width" style="width:120px">
                        <?php
						foreach( $borders as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_under_image_bt_border_width ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_bt_border_style"><?php _e("Button Border Style", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_under_image_bt_border_style = 'solid'; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_under_image_bt_border_style" style="width:120px">
                        <?php
						foreach( $borders_style as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_under_image_bt_border_style ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_under_image_bt_border_color"><?php _e('Button Border Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#000000" style="width: 120px;" id="wpec_quick_view_ultimate_under_image_bt_border_color" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_under_image_bt_border_color"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #000000</span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_under_image_bt_rounded"><?php _e('Border Rounded','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-text">
                    	<input disabled="disabled" type="text" class="" value="3" style="width:120px;" id="wpec_quick_view_ultimate_under_image_bt_rounded" name=""> <span class="description"><?php _e('px','wpecquickview'); ?></span>               </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_bt_font_family"><?php _e("Button Font", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_under_image_bt_font_family = 'Arial, sans-serif'; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_under_image_bt_font_family" style="width:120px">
                        <?php
						foreach( $fonts as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_under_image_bt_font_family ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_bt_font_size"><?php _e("Button Font Size", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_under_image_bt_font_size = 12; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_under_image_bt_font_size" style="width:120px">
                        <?php
						foreach( $fonts_size as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_under_image_bt_font_size ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_under_image_bt_font_style"><?php _e("Button Font Style", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php $wpec_quick_view_ultimate_under_image_bt_font_style = 'bold'; ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_under_image_bt_font_style" style="width:120px">
                        <?php
						foreach( $fonts_style as $key=>$value ){
							if( $key == $wpec_quick_view_ultimate_under_image_bt_font_style ){
								?><option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}else{
								?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
							}
                        }
                        ?>
                        </select>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_under_image_bt_font_color"><?php _e('Button Font Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#FFFFFF" style="width: 120px;" id="wpec_quick_view_ultimate_under_image_bt_font_color" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_under_image_bt_font_color"></div> <span class="description"><?php _e('Default','wpecquickview'); ?> #FFFFFF</span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_under_image_bt_class"><?php _e('CSS Class','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input disabled="disabled" type="text" value="" style="width: 120px;" id="wpec_quick_view_ultimate_under_image_bt_class" name="" /><span class="description"><?php _e('Enter your own button CSS class','wpecquickview'); ?></span>
                    </td>
                  </tr>
                  
                </table>
                </div>
                </div>
            </div>
            <div class="section" id="fancybox-pop-up">
            	<div class="pro_feature_fields">
            	<h3><?php _e('Fancy Box Pop Up', 'wpecquickview'); ?></h3>
                <table class="form-table">
            	  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_fancybox_popup_tool_wide"><?php _e("Popup Tool Wide", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php
                    $wpec_quick_view_ultimate_fancybox_popup_tool_wide = 75;
                    ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_fancybox_popup_tool_wide" style="width:120px">
                            <option <?php if ($wpec_quick_view_ultimate_fancybox_popup_tool_wide == '50') echo 'selected="selected"'; ?> value="50"><?php _e('50%','wpecquickview'); ?></option>
                            <option selected="selected" value="75"><?php _e('75%','wpecquickview'); ?></option>
                            <option <?php if ($wpec_quick_view_ultimate_fancybox_popup_tool_wide == '100') echo 'selected="selected"'; ?> value="100"><?php _e('100%','wpecquickview'); ?></option>
                        </select>
                    </td>
                  </tr>
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_fancybox_center_on_scroll"><?php _e('Fix Position on Scroll', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <label><input disabled="disabled" type="radio" checked="checked" value="true" id="wpec_quick_view_ultimate_fancybox_center_on_scroll" name="" /> <span class=""><?php _e('Yes - Pop-up stays centered in screen while page scrolls behind it.', 'wpecquickview');?></span></label>
                      <br />
                      <label><input disabled="disabled" type="radio" value="false" id="wpec_quick_view_ultimate_fancybox_center_on_scroll_no" name="" /> <span class=""><?php _e('No - Pop-up scrolls up and down with the page.', 'wpecquickview');?></span></label>
                    </td>
                  </tr>
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_fancybox_transition_in"><?php _e("Open Transition Effect", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php
                    $wpec_quick_view_ultimate_fancybox_transition_in = 'none';
                    ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_fancybox_transition_in" style="width:120px">
                            <option <?php if ($wpec_quick_view_ultimate_fancybox_transition_in == 'none') echo 'selected="selected"'; ?> value="none"><?php _e('None','wpecquickview'); ?></option>
                            <option <?php if ($wpec_quick_view_ultimate_fancybox_transition_in == 'fade') echo 'selected="selected"'; ?> value="fade"><?php _e('Fade','wpecquickview'); ?></option>
                            <option <?php if ($wpec_quick_view_ultimate_fancybox_transition_in == 'elastic') echo 'selected="selected"'; ?> value="elastic"><?php _e('Elastic','wpecquickview'); ?></option>
                        </select> <span class="description"><?php _e("Choose a pop-up opening effect. Default - None.", 'wpecquickview');?></span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_fancybox_transition_out"><?php _e("Close Transistion Effect", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php
                    $wpec_quick_view_ultimate_fancybox_transition_out = 'none';
                    ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_fancybox_transition_out" style="width:120px">
                            <option <?php if ($wpec_quick_view_ultimate_fancybox_transition_out == 'none') echo 'selected="selected"'; ?> value="none"><?php _e('None','wpecquickview'); ?></option>
                            <option <?php if ($wpec_quick_view_ultimate_fancybox_transition_out == 'fade') echo 'selected="selected"'; ?> value="fade"><?php _e('Fade','wpecquickview'); ?></option>
                            <option <?php if ($wpec_quick_view_ultimate_fancybox_transition_out == 'elastic') echo 'selected="selected"'; ?> value="elastic"><?php _e('Elastic','wpecquickview'); ?></option> 
                        </select> <span class="description"><?php _e("Choose a pop-up closing effect. Default - None.", 'wpecquickview');?></span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_fancybox_speed_in"><?php _e('Opening Speed','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-text">
                    	<input disabled="disabled" type="text" class="" value="300" style="width:120px;" id="wpec_quick_view_ultimate_fancybox_speed_in" name=""> <span class="description"><?php _e('Milliseconds when open popup','wpecquickview'); ?></span>               </td>
                  </tr>
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_fancybox_speed_out"><?php _e('Closing Speed','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-text">
                    	<input disabled="disabled" type="text" class="" value="0" style="width:120px;" id="wpec_quick_view_ultimate_fancybox_speed_out" name=""> <span class="description"><?php _e('Milliseconds when close popup','wpecquickview'); ?></span>               </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_fancybox_overlay_color"><?php _e('Background Overlay Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#666666" style="width: 120px;" id="wpec_quick_view_ultimate_fancybox_overlay_color" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_fancybox_overlay_color"></div> <span class="description"><?php _e('Select a colour or type TRANSPARENT for no colour.','wpecquickview'); ?> <?php _e('Default','wpecquickview'); ?> #666666</span>
                    </td>
                  </tr>
                  
            	</table>
                </div>
            </div>
            <div class="section" id="colourbox-pop-up">
            	<div class="pro_feature_fields">
            	<h3><?php _e('Colour Box Pop Up', 'wpecquickview'); ?></h3>
                <table class="form-table">
            	  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_colorbox_popup_tool_wide"><?php _e("Popup Tool Wide", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php
                    $wpec_quick_view_ultimate_colorbox_popup_tool_wide = 75;
                    ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_colorbox_popup_tool_wide" style="width:120px">
                            <option <?php if ($wpec_quick_view_ultimate_colorbox_popup_tool_wide == '50') echo 'selected="selected"'; ?> value="50"><?php _e('50%','wpecquickview'); ?></option>
                            <option <?php if (get_option('wpec_quick_view_ultimate_colorbox_popup_tool_wide') == '' || $wpec_quick_view_ultimate_colorbox_popup_tool_wide == '75') echo 'selected="selected"'; ?> value="75"><?php _e('75%','wpecquickview'); ?></option>
                            <option <?php if ($wpec_quick_view_ultimate_colorbox_popup_tool_wide == '100') echo 'selected="selected"'; ?> value="100"><?php _e('100%','wpecquickview'); ?></option>
                        </select>
                    </td>
                  </tr>
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_colorbox_center_on_scroll"><?php _e('Fix Position on Scroll', 'wpecquickview');?></label></th>
                    <td class="forminp">
                      <label><input disabled="disabled" type="radio" checked="checked" value="true" id="wpec_quick_view_ultimate_colorbox_center_on_scroll" name="" /> <span class=""><?php _e('Yes - Pop-up stays centered in screen while page scrolls behind it.', 'wpecquickview');?></span></label>
                      <br />
                      <label><input disabled="disabled" type="radio" value="false" id="wpec_quick_view_ultimate_colorbox_center_on_scroll_no" name="" /> <span class=""><?php _e('No - Pop-up scrolls up and down with the page.', 'wpecquickview');?></span></label>
                    </td>
                  </tr>
                  <tr valign="top">
                    <th class="titledesc" scope="row"><label for="wpec_quick_view_ultimate_colorbox_transition"><?php _e("Open & Close Transition Effect", 'wpecquickview');?></label></th>
                    <td class="forminp">
                    <?php
                    $wpec_quick_view_ultimate_colorbox_transition = 'none';
                    ?>
                        <select class="chzn-select" size="1" name="" id="wpec_quick_view_ultimate_colorbox_transition" style="width:120px">
                            <option <?php if ($wpec_quick_view_ultimate_colorbox_transition == 'none') echo 'selected="selected"'; ?> value="none"><?php _e('None','wpecquickview'); ?></option>
                            <option <?php if ($wpec_quick_view_ultimate_colorbox_transition == 'fade') echo 'selected="selected"'; ?> value="fade"><?php _e('Fade','wpecquickview'); ?></option>
                            <option <?php if ($wpec_quick_view_ultimate_colorbox_transition == 'elastic') echo 'selected="selected"'; ?> value="elastic"><?php _e('Elastic','wpecquickview'); ?></option>
                        </select> <span class="description"><?php _e("Choose a pop-up opening & closing effect. Default - None", 'wpecquickview');?></span>
                    </td>
                  </tr>
                  
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_colorbox_speed"><?php _e('Opening & Closing Speed','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-text">
                    	<input disabled="disabled" type="text" class="" value="300" style="width:120px;" id="wpec_quick_view_ultimate_colorbox_speed" name=""> <span class="description"><?php _e('Milliseconds when open and close popup.','wpecquickview'); ?></span>               </td>
                  </tr>
                 
                  <tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wpec_quick_view_ultimate_colorbox_overlay_color"><?php _e('Background Overlay Colour','wpecquickview'); ?></label>
					</th>
                    <td class="forminp forminp-color">
                    	<input type="text" class="colorpick" value="#666666" style="width: 120px;" id="wpec_quick_view_ultimate_colorbox_overlay_color" name="" /> <div style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;" class="colorpickdiv" id="colorPickerDiv_wpec_quick_view_ultimate_colorbox_overlay_color"></div> <span class="description"><?php _e('Select a colour or type TRANSPARENT for no colour.','wpecquickview'); ?> <?php _e('Default','wpecquickview'); ?> #666666</span>
                    </td>
                  </tr>
                  
            	</table>
                </div>
            </div>
            </div>
            <div id="a3_plugin_panel_upgrade_area"><?php echo WPEC_Quick_View_Ultimate::plugin_extension(); ?></div>
        </div>
        </div>
        <div style="clear:both;"></div>
		<script type="text/javascript">
            jQuery(window).load(function(){
                // Subsubsub tabs
                jQuery('div.a3_subsubsub_section ul.subsubsub li a:eq(0)').addClass('current');
                jQuery('div.a3_subsubsub_section .section:gt(0)').hide();
        
                jQuery('div.a3_subsubsub_section ul.subsubsub li a').click(function(){
                    var $clicked = jQuery(this);
                    var $section = $clicked.closest('.a3_subsubsub_section');
                    var $target  = $clicked.attr('href');
        
                    $section.find('a').removeClass('current');
        
                    if ( $section.find('.section:visible').size() > 0 ) {
                        $section.find('.section:visible').fadeOut( 100, function() {
                            $section.find( $target ).fadeIn('fast');
                        });
                    } else {
                        $section.find( $target ).fadeIn('fast');
                    }
        
                    $clicked.addClass('current');
                    jQuery('#last_tab').val( $target );
            
                    return false;
                });
        
            <?php if (isset($_GET['subtab']) && $_GET['subtab']) echo 'jQuery("div.a3_subsubsub_section ul.subsubsub li a[href=#'.$_GET['subtab'].']").click();'; ?>
            });
        </script>
		<?php 
		add_action('admin_footer', array(&$this, 'add_scripts'), 10);
	}
	
	public function wp_admin_footer_scripts() {
	?>
    <script type="text/javascript">
		(function($){		
			$(function(){	
				$("a.nav-tab").click(function(){
					if($(this).attr('data-tab-id') == 'quick_view_settings'){
						window.location.href=$(this).attr('href');
						return false;
					}
				});
			});		  
		})(jQuery);
	</script>
    <?php
	}
	
	public function add_scripts(){
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );
		wp_enqueue_style( 'a3rev-chosen-style', WPEC_QV_ULTIMATE_JS_URL . '/chosen/chosen.css' );
		wp_enqueue_script( 'chosen', WPEC_QV_ULTIMATE_JS_URL . '/chosen/chosen.jquery'.$suffix.'.js', array(), false, true );
		wp_enqueue_script( 'a3rev-chosen-script-init', WPEC_QV_ULTIMATE_JS_URL.'/init-chosen.js', array(), false, true );
		wp_enqueue_script( 'wpec-quick_view_ultimate-admin', WPEC_QV_ULTIMATE_JS_URL . '/admin.js', array(), false, true );
	}
}
?>