<?php
/**
 * WPEC_Quick_View_Ultimate_Style
 *
 * Table Of Contents
 *
 * WPEC_Quick_View_Ultimate_Style()
 * button_style_show_on_hover()
 */
class WPEC_Quick_View_Ultimate_Style
{
	
	public function WPEC_Quick_View_Ultimate_Style(){
		//construct
	}
	
	public function button_style_show_on_hover(){
		$wpec_quick_view_ultimate_on_hover_bt_alink = get_option( 'wpec_quick_view_ultimate_on_hover_bt_alink' );
		
		?>
		<style type="text/css">
        .wpec_quick_view_ultimate_container{text-align:<?php echo $wpec_quick_view_ultimate_on_hover_bt_alink;?>;clear:both;display:block;width:100%;}
		.wpec_quick_view_ultimate_container span{
			text-align:<?php echo $wpec_quick_view_ultimate_on_hover_bt_alink;?> !important;
			font-family: Arial, sans-serif !important;
			font-size: 14px !important;
			padding: 7px 17px !important;

			font-style:normal !important;
			font-weight:normal !important;
		}
		
		.product_hover .wpec_quick_view_ultimate_container span.wpec_quick_view_ultimate_button{
			color: #FFFFFF !important;
			border: 1px solid #FFFFFF !important;
			background: #999999 !important;
			background: -webkit-gradient(linear, left top, left bottom, from(#999999), to(#999999));
			background: -webkit-linear-gradient(#999999, #999999);
			background: -moz-linear-gradient(center top, #999999 0%, #999999 100%);
			background: -moz-gradient(center top, #999999 0%, #999999 100%);
			border-radius: 3px !important;
			-moz-border-radius: 3px !important;
			-webkit-border-radius: 3px !important;
			box-shadow: 0 0 30px #999999 !important;
			-moz-box-shadow: 0 0 30px #999999 !important;
			-webkit-box-shadow: 0 0 30px #999999 !important;
			opacity:0.5;
			filter:alpha(opacity=50);
		}
        </style>
		<?php
		
	}
}