<?php
//ini_set('display_errors','Off');
class Easy_Facebook_Like_Box_Widget extends WP_Widget {
 
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'easy_racebook_likebox', // Base ID
			__('Easy Facebook Likebox (deprecated)', 'easy-facebook-likebox'), // Name
			array( 'description' => __( 'Drag and drop this widget for facebook like box integration', 'easy-facebook-likebox' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
 
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			
		echo Easy_Facebook_Likebox::render_fb_box($instance);
		 
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
  
	 
		$locales = array(  'af_ZA' => 'Afrikaans', 
						   'ar_AR' => 'Arabic', 
						   'az_AZ' => 'Azeri', 
						   'be_BY' => 'Belarusian', 
						   'bg_BG' => 'Bulgarian', 
						   'bn_IN' => 'Bengali', 
						   'bs_BA' => 'Bosnian', 
						   'ca_ES' => 'Catalan', 
						   'cs_CZ' => 'Czech', 
						   'cy_GB' => 'Welsh', 
						   'da_DK' => 'Danish', 
						   'de_DE' => 'German', 
						   'el_GR' => 'Greek', 
						   'en_US' => 'English (US)', 
						   'en_GB' => 'English (UK)', 
						   'eo_EO' => 'Esperanto', 
						   'es_ES' => 'Spanish (Spain)', 
						   'es_LA' => 'Spanish', 
						   'et_EE' => 'Estonian', 
						   'eu_ES' => 'Basque', 
						   'fa_IR' => 'Persian', 
						   'fb_LT' => 'Leet Speak', 
						   'fi_FI' => 'Finnish', 
						   'fo_FO' => 'Faroese', 
						   'fr_FR' => 'French (France)', 
						   'fr_CA' => 'French (Canada)', 
						   'fy_NL' => 'NETHERLANDS (NL)', 
						   'ga_IE' => 'Irish', 
						   'gl_ES' => 'Galician', 
 						   'hi_IN' => 'Hindi', 
						   'hr_HR' => 'Croatian', 
						   'hu_HU' => 'Hungarian', 
						   'hy_AM' => 'Armenian', 
						   'id_ID' => 'Indonesian', 
						   'is_IS' => 'Icelandic', 
						   'it_IT' => 'Italian', 
						   'ja_JP' => 'Japanese', 
						   'ka_GE' => 'Georgian', 
						   'km_KH' => 'Khmer', 
						   'ko_KR' => 'Korean', 
						   'ku_TR' => 'Kurdish', 
						   'la_VA' => 'Latin', 
						   'lt_LT' => 'Lithuanian', 
						   'lv_LV' => 'Latvian', 
						   'mk_MK' => 'Macedonian', 
						   'ml_IN' => 'Malayalam', 
						   'ms_MY' => 'Malay', 
						   'nb_NO' => 'Norwegian (bokmal)', 
						   'ne_NP' => 'Nepali', 
						   'nl_NL' => 'Dutch', 
						   'nn_NO' => 'Norwegian (nynorsk)', 
						   'pa_IN' => 'Punjabi', 
						   'pl_PL' => 'Polish', 
						   'ps_AF' => 'Pashto', 
						   'pt_PT' => 'Portuguese (Portugal)', 
						   'pt_BR' => 'Portuguese (Brazil)', 
						   'ro_RO' => 'Romanian', 
						   'ru_RU' => 'Russian', 
						   'sk_SK' => 'Slovak', 
						   'sl_SI' => 'Slovenian', 
						   'sq_AL' => 'Albanian', 
						   'sr_RS' => 'Serbian', 
						   'sv_SE' => 'Swedish', 
						   'sw_KE' => 'Swahili', 
						   'ta_IN' => 'Tamil', 
						   'te_IN' => 'Telugu', 
						   'th_TH' => 'Thai', 
						   'tl_PH' => 'Filipino', 
						   'tr_TR' => 'Turkish', 
						   'uk_UA' => 'Ukrainian',
						   'ur_PK' => 'Urdu',
 						   'vi_VN' => 'Vietnamese', 
						   'zh_CN' => 'Simplified Chinese (China)', 
						   'zh_HK' => 'Traditional Chinese (Hong Kong)', 
						   'zh_TW' => 'Traditional Chinese (Taiwan)',
						   );
		
		$defaults = array(
						  'title'		=> '',
						  'fb_appid'	=>	'',
						  'fanpage_url' => 'https://www.facebook.com/jwebsol',
						  'box_width'	=>	250,
						  'box_height' 	=>  '',
						  'colorscheme' =>  'light',
						  'show_faces' => 1,
						  'show_stream' => 0,
						  'show_header' => 1,
						  'show_border' => 1,
						  'responsive'	=> 0,
						  'locale' => 'en_US',
						  'locale_other'=> ''
						  );
 		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
 		extract($instance, EXTR_SKIP);
 
 		?>
 
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        
        <p>
		<label for="<?php echo $this->get_field_id( 'fanpage_url' ); ?>"><?php _e( 'Fanpage Url:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'fanpage_url' ); ?>" name="<?php echo $this->get_field_name( 'fanpage_url' ); ?>" type="text" value="<?php echo esc_attr( $fanpage_url ); ?>"><br />
		<i>Full url including http://</i>
		</p>
        
        <p>
		<label for="<?php echo $this->get_field_id( 'fb_appid' ); ?>"><?php _e( 'Application ID:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'fb_appid' ); ?>" name="<?php echo $this->get_field_name( 'fb_appid' ); ?>" type="text" value="<?php echo esc_attr( $fb_appid ); ?>"><br />
		<i>Optional</i>
		</p>
        
        <p>
		<label for="<?php echo $this->get_field_id( 'box_width' ); ?>"><?php _e( 'Width:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'box_width' ); ?>" name="<?php echo $this->get_field_name( 'box_width' ); ?>" type="text" value="<?php echo esc_attr( $box_width ); ?>"><br />
 		</p>
        
        <p>
		<label for="<?php echo $this->get_field_id( 'box_height' ); ?>"><?php _e( 'Height:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'box_height' ); ?>" name="<?php echo $this->get_field_name( 'box_height' ); ?>" type="text" value="<?php echo esc_attr( $box_height ); ?>"><br />
 		</p>
        
        
        <p class="widget-half">
		<label for="<?php echo $this->get_field_id( 'colorscheme' ); ?>"><?php _e( 'Colorscheme:' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'colorscheme' ); ?>" name="<?php echo $this->get_field_name( 'colorscheme' ); ?>">
                 <option <?php selected( $colorscheme, 'light' , $echo = true); ?> value="light">light</option>
                <option <?php selected( $colorscheme, 'dark', $echo = true); ?> value="dark">dark</option>
            </select> 
  		</p>
       
       <p class="widget-half">
		<label for="<?php echo $this->get_field_id( 'locale' ); ?>"><?php _e( 'Locale:' ); ?></label>
        
        
            <select id="<?php echo $this->get_field_id( 'locale' ); ?>" name="<?php echo $this->get_field_name( 'locale' ); ?>">
            <?php if($locales){
						foreach ($locales as $key=>$value){?>
                <option <?php selected( $locale, $key , $echo = true); ?> value="<?php echo $key?>"><?php echo $value?></option>
                <?php }
				}?>
             </select> 
             <i>Language</i>
  		</p>
        
         <p>
		<label for="<?php echo $this->get_field_id( 'locale_other' ); ?>"><?php _e( 'Locale (Other):' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'locale_other' ); ?>" name="<?php echo $this->get_field_name( 'locale_other' ); ?>" type="text" value="<?php echo esc_attr( $locale_other ); ?>" placeholder="en_US">
              
             <i>input locale if you can not find yours in dropdown list</i>
  		</p>
         
        <p class="widget-half">
	        <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'responsive' ); ?>" name="<?php echo $this->get_field_name( 'responsive' ); ?>" value="1" <?php checked( $responsive, 1 ); ?>>
			<label for="<?php echo $this->get_field_id( 'responsive' ); ?>">Responsive</label>
			
		</p>
          
        <p class="widget-half">
	        <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_faces' ); ?>" name="<?php echo $this->get_field_name( 'show_faces' ); ?>" value="1" <?php checked( $show_faces, 1 ); ?>>
			<label for="<?php echo $this->get_field_id( 'show_faces' ); ?>">Show Faces</label>
			
		</p>
        
        <p class="widget-half">
        <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_stream' ); ?>" name="<?php echo $this->get_field_name( 'show_stream' ); ?>" value="1" <?php checked( $show_stream, 1 ); ?>>
			<label for="<?php echo $this->get_field_id( 'show_stream' ); ?>">Show Posts</label>
			
		</p>
        
        <p class="widget-half">
	        <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_border' ); ?>" name="<?php echo $this->get_field_name( 'show_border' ); ?>" value="1" <?php checked( $show_border, 1 ); ?>>
			<label for="<?php echo $this->get_field_id( 'show_border' ); ?>">Show Border</label>
			
		</p>
        
        <p class="widget-half">
       		<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_header' ); ?>" name="<?php echo $this->get_field_name( 'show_header' ); ?>" value="1" <?php checked( $show_header, 1 ); ?>> 
			<label for="<?php echo $this->get_field_id( 'show_header' ); ?>">Show Header</label>
			
		</p>
        
        <div class="clearfix"></div>
        
        <p>Use Below shortcode to use inside pages or posts</p>
        <?php 
		if( empty($show_stream) ){
			$show_stream = 0;
		}
		
		if( empty($show_faces) ){
			$show_faces = 0;
		}
		
		if( empty($show_header) ){
			$show_header = 0;
		}
		
		if( empty($show_border) ){
			$show_border = 0;
		}
		
		if( !empty($locale_other) ){
			$locale = $locale_other;
		}
		
		if( !empty($fb_appid) ){
			$fb_appid = 'fb_appid="'.$fb_appid.'"';
		}
		
		$fb_url = parse_url( $fanpage_url );
		$fanpage_url = str_replace('/', '', $fb_url['path']);
		/*echo "<pre>";
		print_r( $fb_url  );
  		echo "</pre>";*/
 		
		$responsive = (  empty( $responsive ) ) ? strip_tags( 0 ) : $responsive;
		
		?>
        
        <p style="background:#ddd; padding:5px; "><?php echo '[efb_likebox fanpage_url="'.$fanpage_url.'" '.$fb_appid.' box_width="'.$box_width.'" box_height="'.$box_height.'" colorscheme="'.$colorscheme.'" locale="'.$locale.'" responsive="'.$responsive.'" show_faces="'.$show_faces.'" show_header="'.$show_header.'" show_stream="'.$show_stream.'" show_border="'.$show_border.'" ]'?></p>
         
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['fanpage_url'] = ( ! empty( $new_instance['fanpage_url'] ) ) ? strip_tags( $new_instance['fanpage_url'] ) : '';
		$instance['fb_appid'] = ( ! empty( $new_instance['fb_appid'] ) ) ? strip_tags( $new_instance['fb_appid'] ) : '';
		$instance['show_faces'] = ( ! empty( $new_instance['show_faces'] ) ) ? strip_tags( $new_instance['show_faces'] ) : '';
		$instance['show_stream'] = ( ! empty( $new_instance['show_stream'] ) ) ? strip_tags( $new_instance['show_stream'] ) : '';
		$instance['show_border'] = ( ! empty( $new_instance['show_border'] ) ) ? strip_tags( $new_instance['show_border'] ) : '';
		$instance['show_header'] = ( ! empty( $new_instance['show_header'] ) ) ? strip_tags( $new_instance['show_header'] ) : '';
		$instance['box_height'] = ( ! empty( $new_instance['box_height'] ) ) ? strip_tags( $new_instance['box_height'] ) : '';
		$instance['box_width'] = ( ! empty( $new_instance['box_width'] ) ) ? strip_tags( $new_instance['box_width'] ) : '';
		$instance['colorscheme'] = ( ! empty( $new_instance['colorscheme'] ) ) ? strip_tags( $new_instance['colorscheme'] ) : '';
		
		$instance['responsive'] = ( ! empty( $new_instance['responsive'] ) ) ? strip_tags( $new_instance['responsive'] ) : '';
		$instance['locale'] = ( ! empty( $new_instance['locale'] ) ) ? strip_tags( $new_instance['locale'] ) : '';
		$instance['locale_other'] = ( ! empty( $new_instance['locale_other'] ) ) ? strip_tags( $new_instance['locale_other'] ) : '';
		
		
		return $instance;
	}

} // class Foo_Widget
?>