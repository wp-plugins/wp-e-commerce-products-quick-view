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
 * plugin_extension()
 * plugin_extra_links()
 */
class WPEC_Quick_View_Ultimate
{
	
	public function WPEC_Quick_View_Ultimate() {
		$this->init();
	}
	
	public function init () {
		add_action( 'wp_footer', array( &$this,'wpec_add_quick_view_button_default_template' ) );
		add_action( 'wp_footer', array( &$this,'wpec_quick_view_ultimate_wp_enqueue_script' ),11 );
		add_action( 'wp_footer', array( &$this,'wpec_quick_view_ultimate_wp_enqueue_style' ),11 );
		add_action( 'wp_footer', array( &$this, 'wpec_quick_view_ultimate_popup' ),11 );
		
		add_action('wp_ajax_wpec_quick_view_ultimate_reload_cart', array( &$this, 'wpec_quick_view_ultimate_reload_cart') );
		add_action('wp_ajax_nopriv_wpec_quick_view_ultimate_reload_cart', array( &$this, 'wpec_quick_view_ultimate_reload_cart') );
		
		// Add script check if checkout then close popup and redirect to checkout page
		add_action( 'wp_head', array( &$this, 'redirect_to_checkout_page_from_popup') );
		
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
		$do_this = false;
		if( $wpec_quick_view_ultimate_enable == '1' ) $do_this = true;
		if( !$do_this ) return;
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
		WPEC_Quick_View_Ultimate_Style::button_style_show_on_hover();
	}
	
	public function wpec_add_quick_view_button_default_template(){
		global $wpsc_gc_view_mode;
		$wpec_quick_view_ultimate_enable = get_option('wpec_quick_view_ultimate_enable');
		$do_this = false;
		
		if( $wpec_quick_view_ultimate_enable == '1' ) $do_this = true;
		
		if( !$do_this ) return;

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
					$wpec_quick_view_ultimate_under_image_link_text = __( 'Quick View', 'wpecquickview' );
					$link_text = $wpec_quick_view_ultimate_under_image_link_text;
					$class = $wpec_quick_view_ultimate_popup_tool.' wpec_quick_view_ultimate_under_link wpec_quick_view_ultimate_click';
					
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
	}
	
	public function wpec_quick_view_ultimate_popup(){
		$suffix 				= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$wpec_quick_view_ultimate_enable = get_option('wpec_quick_view_ultimate_enable');
		$do_this = false;
		if( $wpec_quick_view_ultimate_enable == '1' ) $do_this = true;
		if( !$do_this ) return;
		$wpec_quick_view_ultimate_fancybox_popup_tool_wide = 75;		
		
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
		
			<?php
				if ( $wpec_quick_view_ultimate_fancybox_popup_tool_wide == 100 ) $wpec_quick_view_ultimate_fancybox_popup_tool_wide = 94;
				
			?>
			jQuery(document).on("click", ".wpec_quick_view_ultimate_click.fancybox", function(){
			
				var product_id = jQuery(this).attr('id');
				var product_url = jQuery(this).attr('data-link');
				
				var obj = jQuery(this);
				var url = product_url;
				
                jQuery.fancybox({
					'centerOnScroll' : <?php echo $wpec_quick_view_ultimate_fancybox_center_on_scroll;?>,
					'transitionIn' : '<?php echo $wpec_quick_view_ultimate_fancybox_transition_in;?>', 
					'transitionOut':'<?php echo $wpec_quick_view_ultimate_fancybox_transition_out;?>',
					'easingIn': 'swing',
					'easingOut': 'swing',
					'speedIn' : <?php echo $wpec_quick_view_ultimate_fancybox_speed_in;?>,
					'speedOut' : <?php echo $wpec_quick_view_ultimate_fancybox_speed_out;?>,
					'width':'<?php echo $wpec_quick_view_ultimate_fancybox_popup_tool_wide;?>%',
					'autoScale': false,
					'height':'80%',
					'margin':0,
					'padding':0,
					'autoDimensions': true,
                    'type': 'iframe',
                    'content': url,
					'overlayColor':'<?php echo $wpec_quick_view_ultimate_fancybox_overlay_color;?>',
					
					'onClosed': function() {
						jQuery.post( '<?php echo admin_url('admin-ajax.php', 'relative');?>?action=wpec_quick_view_ultimate_reload_cart&security=<?php echo wp_create_nonce("reload-cart");?>', '', function(rsHTML){
							jQuery('.shopping-cart-wrapper').html(rsHTML);
						});
					},
                });
				jQuery.fancybox.center;

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
	
	public function plugin_extension() {
		$html = '';
		$html .= '<a href="http://a3rev.com/shop/" target="_blank" style="float:right;margin-top:5px; margin-left:10px;" ><img src="'.WPEC_QV_ULTIMATE_IMAGES_URL.'/a3logo.png" /></a>';
		$html .= '<h3>'.__('Upgrade to WPEC Quick View Ultimate', 'wpecquickview').'</h3>';
		$html .= '<p>'.__("<strong>NOTE:</strong> All the functions inside the Yellow border on the plugins admin panel are extra functionality that is activated by upgrading to the Pro version", 'wpecquickview').':</p>';
		$html .= '<p>';
		$html .= '<h3 style="margin-bottom:5px;">* <a href="'.WPEC_QV_ULTIMATE_AUTHOR_URI.'" target="_blank">'.__('WPEC Quick View Ultimate', 'wpecquickview').'</a></h3>';
		$html .= '<div><strong>'.__('Activates these advanced Features', 'wpecquickview').':</strong></div>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>1. '.__("WYSIWYG Quick View hover button style editor.", 'wpecquickview').'</li>';
		$html .= '<li>2. '.__("Show Quick View as a Button or link text under image.", 'wpecquickview').'</li>';
		$html .= '<li>3. '.__('Under Image Button or Link text WYSIWYG style editor.', 'wpecquickview').'</li>';
		$html .= '<li>4. '.__('Show product page content in pop-up instead of whole site.', 'wpecquickview').'</li>';
		$html .= '<li>5. '.__('Optional Colorbox pop-up tool.', 'wpecquickview').'</li>';
		$html .= '<li>6. '.__('Set pop-up wide as a % of screen size in larger screens.', 'wpecquickview').'</li>';
		$html .= '<li>7. '.__('Select pop-up open and close effect.', 'wpecquickview').'</li>';
		$html .= '<li>8. '.__("Set pop-up opening / closing speed.", 'wpecquickview').'</li>';
		$html .= '<li>9. '.__("Set pop-up background overlay colour.", 'wpecquickview').'</li>';
		$html .= '<li>10. '.__("Same day priority support and auto updates.", 'wpecquickview').'</li>';
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
		$html .= '<li>* <a href="http://wordpress.org/plugins/contact-us-page-contact-people/" target="_blank">'.__('Contact Us Page - Contact People', 'wpecquickview').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-email-template/" target="_blank">'.__('WordPress Email Template', 'wpecquickview').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/page-views-count/" target="_blank">'.__('Page View Count', 'wpecquickview').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		return $html;
	}
	
	public function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != WPEC_QV_ULTIMATE_NAME) {
			return $links;
		}
		$links[] = '<a href="http://docs.a3rev.com/user-guides/plugins-extensions/wp-e-commerce/wpec-quick-view/" target="_blank">'.__('Documentation', 'wpecquickview').'</a>';
		$links[] = '<a href="http://wordpress.org/support/plugin/wp-e-commerce-products-quick-view/" target="_blank">'.__('Support', 'wpecquickview').'</a>';
		return $links;
	}
}
?>