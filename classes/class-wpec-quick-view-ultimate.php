<?php
/**
 * WPEC_Quick_View_Ultimate Class
 *
 * Table Of Contents
 *
 * WPEC_Quick_View_Ultimate()
 * init()
 * quick_view_ultimate_wp_enqueue_script()
 * quick_view_ultimate_wp_enqueue_style()
 * quick_view_ultimate_popup()
 * quick_view_ultimate_clicked()
 * quick_view_ultimate_reload_cart()
 * a3_wp_admin()
 * plugin_extension()
 * plugin_extra_links()
 */
class WPEC_Quick_View_Ultimate
{
	
	public function WPEC_Quick_View_Ultimate() {
		$this->init();
	}
	
	public function init () {
		// Include google fonts into header
		add_action( 'wp_head', array( $this, 'add_google_fonts'), 11 );
		
		
		add_action( 'wp_footer', array( $this,'wpec_add_quick_view_button_default_template' ) );
		add_action( 'wp_footer', array( $this,'wpec_quick_view_ultimate_wp_enqueue_script' ),11 );
		add_action( 'wp_footer', array( $this,'wpec_quick_view_ultimate_wp_enqueue_style' ),11 );
		add_action( 'wp_footer', array( $this, 'wpec_quick_view_ultimate_popup' ),11 );
		
		add_action('wp_ajax_wpec_quick_view_ultimate_reload_cart', array( $this, 'wpec_quick_view_ultimate_reload_cart') );
		add_action('wp_ajax_nopriv_wpec_quick_view_ultimate_reload_cart', array( $this, 'wpec_quick_view_ultimate_reload_cart') );
		
		// Add script check if checkout then close popup and redirect to checkout page
		add_action( 'wp_head', array( $this, 'redirect_to_checkout_page_from_popup') );
		
		// Add upgrade notice to Dashboard pages
		global $wpec_qv_admin_init;
		add_filter( $wpec_qv_admin_init->plugin_name . '_plugin_extension', array( $this, 'plugin_extension' ) );
		
		$admin_pages = $wpec_qv_admin_init->admin_pages();
		if ( is_array( $admin_pages ) && count( $admin_pages ) > 0 ) {
			foreach ( $admin_pages as $admin_page ) {
				add_action( $wpec_qv_admin_init->plugin_name . '-' . $admin_page . '_tab_start', array( $this, 'plugin_extension_start' ) );
				add_action( $wpec_qv_admin_init->plugin_name . '-' . $admin_page . '_tab_end', array( $this, 'plugin_extension_end' ) );
			}
		}
	}
	
	public function add_google_fonts() {
		global $wpec_qv_fonts_face;
		$wpec_quick_view_ultimate_on_hover_bt_font = get_option( 'wpec_quick_view_ultimate_on_hover_bt_font' );
		$wpec_quick_view_ultimate_under_image_link_font = get_option( 'wpec_quick_view_ultimate_under_image_link_font' );
		$wpec_quick_view_ultimate_under_image_bt_font = get_option( 'wpec_quick_view_ultimate_under_image_bt_font' );
		
		$google_fonts = array( $wpec_quick_view_ultimate_on_hover_bt_font['face'], $wpec_quick_view_ultimate_under_image_link_font['face'], $wpec_quick_view_ultimate_under_image_bt_font['face'] );
		
		$wpec_qv_fonts_face->generate_google_webfonts( $google_fonts );
	}
	
	public function redirect_to_checkout_page_from_popup() {
		if ( get_option( 'checkout_url' ) == get_permalink() ) {
	?>
    	<script type="text/javascript">
		if ( window.self !== window.top ) {
			self.parent.location.href = '<?php echo get_option( 'checkout_url' ); ?>';
		}
		</script>
    <?php
		}
	}
	
	public function wpec_quick_view_ultimate_wp_enqueue_script(){
		$wpec_quick_view_ultimate_enable = get_option('wpec_quick_view_ultimate_enable');
		$wpec_quick_view_ultimate_type = get_option('wpec_quick_view_ultimate_type');
		$do_this = false;
		if( $wpec_quick_view_ultimate_enable == '1' ) $do_this = true;
		if( !$do_this ) return;
		if( $wpec_quick_view_ultimate_type != 'hover' ) return;
		wp_enqueue_script('jquery');
		wp_register_script( 'wpec-quick-view-script', WPEC_QV_ULTIMATE_JS_URL.'/quick_view_ultimate.js');
		wp_enqueue_script( 'wpec-quick-view-script' );
	}
	
	public function wpec_quick_view_ultimate_wp_enqueue_style(){
		$wpec_quick_view_ultimate_enable = get_option('wpec_quick_view_ultimate_enable');
		$do_this = false;
		if( $wpec_quick_view_ultimate_enable == '1' ) $do_this = true;
		if( !$do_this ) return;
		wp_enqueue_style( 'wpec-quick-view-css', WPEC_QV_ULTIMATE_CSS_URL.'/style.css');
		WPEC_Quick_View_Ultimate_Style::button_style_under_image();
		WPEC_Quick_View_Ultimate_Style::button_style_show_on_hover();
	}
	
	public function wpec_add_quick_view_button_default_template(){
		global $wpsc_gc_view_mode;
		$wpec_quick_view_ultimate_enable = get_option('wpec_quick_view_ultimate_enable');
		$wpec_quick_view_ultimate_type = get_option('wpec_quick_view_ultimate_type');
		$do_this = false;
		
		if( $wpec_quick_view_ultimate_enable == '1' ) $do_this = true;
		
		if( !$do_this ) return;

		if ( $wpec_quick_view_ultimate_type == 'hover' ){
		?>
		<script type="text/javascript">
		var bt_position = '<?php echo get_option('wpec_quick_view_ultimate_on_hover_bt_alink');?>';
		var bt_text = '<?php esc_attr_e( stripslashes( get_option('wpec_quick_view_ultimate_on_hover_bt_text', __( 'QUICKVIEW', 'wpecquickview' ) ) ) );?>';
		var popup_tool = 'fancybox';
        jQuery(window).load(function(){
			jQuery( document ).find ( 'form.product_form' ).each(function(){
				product_id = jQuery('input[name="product_id"]',this).val();
				image_element_id = 'product_image_'+product_id;
				jQuery( "#"+image_element_id).data("product_id", product_id );
				parent_container = jQuery(this).parents('div.product_view_'+product_id);
				parent_container.addClass('product_view_item');
				jQuery( "#"+image_element_id).parent('a').parent('div').addClass('wpec_image');
				var bt_html = '<div class="wpec_quick_view_ultimate_container" position="'+bt_position+'"><div class="wpec_quick_view_ultimate_content"><span id="'+product_id+'" data-link="'+jQuery(this).attr('action')+'" class="'+popup_tool+' wpec_quick_view_ultimate_button wpec_quick_view_ultimate_click">'+bt_text+'</span></div></div>';
				<?php
				if($wpsc_gc_view_mode == 'grid'){
				?>
				parent_container.find('.wpec_image').append(bt_html);
				<?php
				}elseif($wpsc_gc_view_mode == 'list'){
					$wpec_quick_view_ultimate_popup_tool = 'fancybox';
					$wpec_quick_view_ultimate_under_image_bt_type = get_option( 'wpec_quick_view_ultimate_under_image_bt_type' );
					$wpec_quick_view_ultimate_under_image_link_text = esc_attr( stripslashes( get_option('wpec_quick_view_ultimate_under_image_link_text', __( 'Quick View', 'wpecquickview' ) ) ) );
					$wpec_quick_view_ultimate_under_image_bt_text = esc_attr( stripslashes( get_option('wpec_quick_view_ultimate_under_image_bt_text', __( 'Quick View', 'wpecquickview' ) ) ) );
					$wpec_quick_view_ultimate_under_image_bt_class = get_option( 'wpec_quick_view_ultimate_under_image_bt_class' );
					$link_text = $wpec_quick_view_ultimate_under_image_link_text;
					$class = $wpec_quick_view_ultimate_popup_tool.' wpec_quick_view_ultimate_under_link wpec_quick_view_ultimate_click';
					if( $wpec_quick_view_ultimate_under_image_bt_type == 'button' ){
						$link_text = $wpec_quick_view_ultimate_under_image_bt_text;
						$class = $wpec_quick_view_ultimate_popup_tool.' wpec_quick_view_ultimate_under_button wpec_quick_view_ultimate_click';
						if( trim($wpec_quick_view_ultimate_under_image_bt_class) != '' ){$class .= ' '.trim($wpec_quick_view_ultimate_under_image_bt_class);}
					}
				?>
				var bt_text = '<?php echo $link_text; ?>';
				jQuery('tr.product_view_'+product_id).append('<td class="bt_quick_view"><div style="clear:both;"></div><div class="wpec_quick_view_ultimate_container_under"><div class="wpec_quick_view_ultimate_content_under"><a class="<?php echo $class;?>" id="'+product_id+'" href="'+jQuery(this).attr('action')+'" data-link="'+jQuery(this).attr('action')+'">'+bt_text+'</a></div></div><div style="clear:both;"></div></td>');
				<?php
				}else{
				?>
				parent_container.find('.wpec_image').append(bt_html);
				<?php
				}
				?>
			});
		});
        </script>
		<?php
		}else{
		$wpec_quick_view_ultimate_popup_tool = 'fancybox';
		$wpec_quick_view_ultimate_under_image_bt_type = get_option( 'wpec_quick_view_ultimate_under_image_bt_type' );
		$wpec_quick_view_ultimate_under_image_link_text = esc_attr( stripslashes( get_option('wpec_quick_view_ultimate_under_image_link_text', __( 'Quick View', 'wpecquickview' ) ) ) );
		$wpec_quick_view_ultimate_under_image_bt_text = esc_attr( stripslashes( get_option('wpec_quick_view_ultimate_under_image_bt_text', __( 'Quick View', 'wpecquickview' ) ) ) );
		$wpec_quick_view_ultimate_under_image_bt_class = esc_attr( stripslashes( get_option( 'wpec_quick_view_ultimate_under_image_bt_class' ) ) );
		$link_text = $wpec_quick_view_ultimate_under_image_link_text;
		$class = $wpec_quick_view_ultimate_popup_tool.' wpec_quick_view_ultimate_under_link wpec_quick_view_ultimate_click';
		if( $wpec_quick_view_ultimate_under_image_bt_type == 'button' ){
			$link_text = $wpec_quick_view_ultimate_under_image_bt_text;
			$class = $wpec_quick_view_ultimate_popup_tool.' wpec_quick_view_ultimate_under_button wpec_quick_view_ultimate_click';
			if( trim($wpec_quick_view_ultimate_under_image_bt_class) != '' ){$class .= ' '.trim($wpec_quick_view_ultimate_under_image_bt_class);}
		}
		?>
		<script type="text/javascript">
		var bt_text = '<?php echo $link_text; ?>';
		var popup_tool = '<?php echo get_option('wpec_quick_view_ultimate_popup_tool');?>';
        jQuery(window).load(function(){
			jQuery( document ).find ( 'form.product_form' ).each(function(){
				product_id = jQuery('input[name="product_id"]',this).val();
				image_element_id = 'product_image_'+product_id;
				jQuery( "#"+image_element_id).data("product_id", product_id );
				parent_container = jQuery(this).parents('div.product_view_'+product_id);
				parent_container.addClass('product_view_item');
				jQuery( "#"+image_element_id).parent('a').parent('div').addClass('wpec_image');
				var bt_html = '<div style="clear:both;"></div><div class="wpec_quick_view_ultimate_container_under"><div class="wpec_quick_view_ultimate_content_under"><a class="<?php echo $class;?>" id="'+product_id+'" href="'+jQuery(this).attr('action')+'" data-link="'+jQuery(this).attr('action')+'">'+bt_text+'</a></div></div><div style="clear:both;"></div>';
				<?php
				if($wpsc_gc_view_mode == 'grid'){
				?>
				parent_container.find('.wpec_image').append(bt_html);
				<?php
				}elseif($wpsc_gc_view_mode == 'list'){
				?>
				jQuery('tr.product_view_'+product_id).append('<td class="bt_quick_view">'+bt_html+'</td>');
				<?php
				}else{
				?>
				parent_container.find('.wpec_image').append(bt_html);
				<?php
				}
				?>
			});
		});
        </script>
		<?php
		}
	}
	
	public function wpec_quick_view_ultimate_popup(){
		$suffix 				= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$wpec_quick_view_ultimate_enable = get_option('wpec_quick_view_ultimate_enable');
		$do_this = false;
		if( $wpec_quick_view_ultimate_enable == '1' ) $do_this = true;
		if( !$do_this ) return;		
		
			wp_enqueue_style( 'woocommerce_fancybox_styles', WPEC_QV_ULTIMATE_JS_URL . '/fancybox/fancybox.css' );
			wp_enqueue_script( 'fancybox', WPEC_QV_ULTIMATE_JS_URL . '/fancybox/fancybox'.$suffix.'.js', array(), false, true );
			?>
			<!--<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
            <?php
		wp_enqueue_style( 'wpec-quick-view-css', WPEC_QV_ULTIMATE_CSS_URL.'/style.css');
		
		
		$wpec_quick_view_ultimate_fancybox_center_on_scroll = 'true';
		$wpec_quick_view_ultimate_fancybox_transition_in = 'none';
		$wpec_quick_view_ultimate_fancybox_transition_out = 'none';
		$wpec_quick_view_ultimate_fancybox_speed_in = 300;
		$wpec_quick_view_ultimate_fancybox_speed_out = 0;
		$wpec_quick_view_ultimate_fancybox_overlay_color = '#666666';
		
		?>
		<script type="text/javascript">
			function wpec_qv_getWidth() {
				xWidth = null;
				if(window.screen != null)
				  xWidth = window.screen.availWidth;
			
				if(window.innerWidth != null)
				  xWidth = window.innerWidth;
			
				if(document.body != null)
				  xWidth = document.body.clientWidth;
			
				return xWidth;
			}
			
			jQuery(document).on("click", ".wpec_quick_view_ultimate_click.fancybox", function(){
			
				var product_id = jQuery(this).attr('id');
				var product_url = jQuery(this).attr('data-link');
				
				var obj = jQuery(this);
				var auto_Dimensions = true;
				
				// detect iOS to fix scroll for iframe on fancybox
				var iOS = ( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false );
				if ( iOS ) {
					jQuery('#fancybox-content').attr( "style", "overflow-y: auto !important; -webkit-overflow-scrolling: touch !important;" );
				}
				var url = product_url;
				
				var popup_wide = 700;
				if ( wpec_qv_getWidth()  <= 600 ) { 
					popup_wide = '90%'; 
				}
				
				jQuery.fancybox({
					href: url,
					type: "iframe",
					centerOnScroll : <?php echo $wpec_quick_view_ultimate_fancybox_center_on_scroll;?>,
					transitionIn : '<?php echo $wpec_quick_view_ultimate_fancybox_transition_in;?>', 
					transitionOut: '<?php echo $wpec_quick_view_ultimate_fancybox_transition_out;?>',
					easingIn: 'swing',
					easingOut: 'swing',
					speedIn : <?php echo $wpec_quick_view_ultimate_fancybox_speed_in;?>,
					speedOut : <?php echo $wpec_quick_view_ultimate_fancybox_speed_out;?>,
					width: popup_wide,
					autoScale: true,
					height: 500,
					margin: 0,
					padding: 10,
					maxWidth: "90%",
					maxHeight: "90%",
					autoDimensions: true,
					overlayColor: '<?php echo $wpec_quick_view_ultimate_fancybox_overlay_color;?>',
					showCloseButton : true,
					openEffect	: "none",
					closeEffect	: "none",
					onClosed: function() {
						jQuery.post( '<?php echo admin_url('admin-ajax.php', 'relative');?>?action=wpec_quick_view_ultimate_reload_cart&security=<?php echo wp_create_nonce("reload-cart");?>', '', function(rsHTML){
							jQuery('.shopping-cart-wrapper').html(rsHTML);
							
						});
					}
                });

				return false;
			});
			
		</script>
		<?php
	}
	
	public function wpec_quick_view_ultimate_reload_cart() {
		global $wpsc_cart;
		check_ajax_referer( 'reload-cart', 'security' );
		include_once( wpsc_get_template_file_path( 'wpsc-cart_widget.php' ) );
		die();
	}
	
	public function plugin_extension_start() {
		global $wpec_qv_admin_init;
		
		$wpec_qv_admin_init->plugin_extension_start();
	}
	
	public function plugin_extension_end() {
		global $wpec_qv_admin_init;
		
		$wpec_qv_admin_init->plugin_extension_end();
	}
	
	public static function a3_wp_admin() {
		wp_enqueue_style( 'a3rev-wp-admin-style', WPEC_QV_ULTIMATE_CSS_URL . '/a3_wp_admin.css' );
	}

	public static function admin_sidebar_menu_css() {
		wp_enqueue_style( 'a3rev-wpec-qv-admin-sidebar-menu-style', WPEC_QV_ULTIMATE_CSS_URL . '/admin_sidebar_menu.css' );
	}
	
	public function plugin_extension() {
		$html = '';
		$html .= '<a href="http://a3rev.com/shop/" target="_blank" style="float:right;margin-top:5px; margin-left:10px;" ><div class="a3-plugin-ui-icon a3-plugin-ui-a3-rev-logo"></div></a>';
		$html .= '<h3>'.__('Upgrade to WPEC Quick View Ultimate', 'wpecquickview').'</h3>';
		$html .= '<p>'.__("<strong>NOTE:</strong> All the functions inside the Yellow border on the plugins admin panel are extra functionality that is activated by upgrading to the Pro version", 'wpecquickview').':</p>';
		$html .= '<p>';
		
		$html .= '<h3>* <a href="'.WPEC_QV_ULTIMATE_AUTHOR_URI.'" target="_blank">'.__('WPEC Quick View Ultimate', 'wpecquickview').'</a> '.__('Features', 'wpecquickview').':</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>1. '.__("Quick View Custom Template for pop-up.", 'wpecquickview').'</li>';
		$html .= '<li>2. '.__("Custom Template Dynamic Product Gallery.", 'wpecquickview').'</li>';
		$html .= '<li>3. '.__('Custom Template Next &gt; &lt; Previous Product feature.', 'wpecquickview').'</li>';
		$html .= '<li>4. '.__('Custom Template Style and layout settings.', 'wpecquickview').'</li>';
		$html .= '<li>5. '.__('Optional Fancybox | Colorbox pop-up tool.', 'wpecquickview').'</li>';
		$html .= '<li>6. '.__('Select pop-up open and close effect.', 'wpecquickview').'</li>';
		$html .= '<li>7. '.__('Set pop-up opening / closing speed.', 'wpecquickview').'</li>';
		$html .= '<li>8. '.__("Set pop-up background overlay colour.", 'wpecquickview').'</li>';
		$html .= '<li>9. '.__("Access to support from developers.", 'wpecquickview').'</li>';
		$html .= '<li>10. '.__("Lifetime upgrades and maintenence.", 'wpecquickview').'</li>';
		$html .= '</ul>';
		$html .= '</p>';
		$html .= '<h3>'.__('View this plugins', 'wpecquickview').' <a href="http://docs.a3rev.com/user-guides/plugins-extensions/wp-e-commerce/wpec-quick-view/" target="_blank">'.__('documentation', 'wpecquickview').'</a></h3>';
		$html .= '<h3>'.__('Visit this plugins', 'wpecquickview').' <a href="http://wordpress.org/support/plugin/wp-e-commerce-products-quick-view/" target="_blank">'.__('support forum', 'wpecquickview').'</a></h3>';
		$html .= '<h3>'.__('More FREE a3rev WP e-Commerce Plugins', 'wpecquickview').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-dynamic-gallery/" target="_blank">'.__('WP e-Commerce Dynamic Gallery', 'wpecquickview').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-predictive-search/" target="_blank">'.__('WP e-Commerce Predictive Search', 'wpecquickview').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-ecommerce-compare-products/" target="_blank">'.__('WP e-Commerce Compare Products', 'wpecquickview').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-catalog-visibility-and-email-inquiry/" target="_blank">'.__('WP e-Commerce Catalog Visibility & Email Inquiry', 'wpecquickview').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-grid-view/" target="_blank">'.__('WP e-Commerce Grid View', 'wpecquickview').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		$html .= '<h3>'.__('FREE a3rev WordPress Plugins', 'wpecquickview').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/plugins/a3-responsive-slider/" target="_blank">'.__('a3 Responsive Slider', 'wpecquickview').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/contact-us-page-contact-people/" target="_blank">'.__('Contact Us Page - Contact People', 'wpecquickview').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-email-template/" target="_blank">'.__('WordPress Email Template', 'wpecquickview').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/page-views-count/" target="_blank">'.__('Page View Count', 'wpecquickview').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		return $html;
	}
	
	public static function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != WPEC_QV_ULTIMATE_NAME) {
			return $links;
		}
		$links[] = '<a href="http://docs.a3rev.com/user-guides/plugins-extensions/wp-e-commerce/wpec-quick-view/" target="_blank">'.__('Documentation', 'wpecquickview').'</a>';
		$links[] = '<a href="http://wordpress.org/support/plugin/wp-e-commerce-products-quick-view/" target="_blank">'.__('Support', 'wpecquickview').'</a>';
		return $links;
	}
}
?>
