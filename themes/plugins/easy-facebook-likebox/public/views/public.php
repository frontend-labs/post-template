<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
 
$options = get_option( 'efbl_settings_display_options' );
/*echo "<pre>";
print_r($options);
exit;*/
$delay = $options['efbl_popup_interval'];
$width = $options['efbl_popup_width'];
$height = $options['efbl_popup_height'];
$shortcode = $options['efbl_popup_shortcode'];


if($options['efbl_enable_popup']){

?>
<div style="display:none">
<a class="popup-with-form efbl_popup_trigger" href="#efbl_popup" >Inline</a>
</div>
<!-- This file is used to markup the public facing aspect of the plugin. -->

<div id="efbl_popup" class="white-popup  mfp-hide" style="width:<?php echo $width?>px; height:<?php echo $height?>px">
		<?php 
		if(empty($shortcode)){
			echo __('Please enter easy facebook like box shortcode from settings > Easy Fcebook Likebox', 'easy-facebook-likebox');
		}else{
			echo do_shortcode($shortcode);
 		}
		?>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	/*$.removeCookie('dont_show', { path: '/' });  */
	
	 $('.popup-with-form').magnificPopup({
          type: 'inline',
          preloader: false,
		  
		  <?php if($options['efbl_do_not_show_again'] == 1){?>
		  callbacks: {
			  close: function() {
 				  $.cookie('dont_show', '1' ,{ expires: 7, path: '/' } );	
			  }
		  },
		  <?php }?>
 	 	 
         });
	 
  	
	
	if( $.cookie('dont_show') != 1) 
		openFancybox(<?php echo $delay?>);

});

function openFancybox(interval) {
    setTimeout( function() {jQuery('.efbl_popup_trigger').trigger('click'); },interval);
}
</script>
<?php }?>