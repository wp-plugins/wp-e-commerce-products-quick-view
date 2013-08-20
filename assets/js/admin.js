// JavaScript Document
jQuery(document).ready(function() {
	
	jQuery('.colorpick').each(function(){
		jQuery('.colorpickdiv', jQuery(this).parent()).farbtastic(this);
		jQuery(this).click(function() {
			if ( jQuery(this).val() == "" ) jQuery(this).val('#');
			jQuery('.colorpickdiv', jQuery(this).parent() ).show();
		});
	});
	jQuery(document).mousedown(function(){
		jQuery('.colorpickdiv').hide();
	});
	
	if ( jQuery("input[name='wpec_quick_view_ultimate_under_image_bt_type']:checked").val() == 'link') {
		jQuery(".show_under_image_hyperlink_styling").show();
		jQuery(".show_under_image_button_styling").hide();
	} else {
		jQuery(".show_under_image_hyperlink_styling").hide();
		jQuery(".show_under_image_button_styling").show();
	}
	jQuery('.wpec_quick_view_ultimate_under_image_change').click(function(){
		if ( jQuery("input[name='wpec_quick_view_ultimate_under_image_bt_type']:checked").val() == 'link') {
			jQuery(".show_under_image_hyperlink_styling").show();
			jQuery(".show_under_image_button_styling").hide();
		} else {
			jQuery(".show_under_image_hyperlink_styling").hide();
			jQuery(".show_under_image_button_styling").show();
		}
	});
});	
