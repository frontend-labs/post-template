<?php
//ini_set('display_errors','Off');
class Easy_Custom_Facebook_Feed_Widget extends WP_Widget {
 
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'easy_facebook_feed', // Base ID
			__('Easy Facebook Feed', 'easy-facebook-likebox'), // Name
			array( 'description' => __( 'Drag and drop this widget for facebook feed integration', 'easy-facebook-likebox' ), ) // Args
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
			
		echo Easy_Facebook_Likebox::render_fbfeed_box($instance);
		 
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
						  'fanpage_url' => 'jwebsol',
						  'layout'		=> 'half',
						  'image_size'	=>	'normal',
						  'type'		=>  'page',
						  'post_by' 	=>  'me',
						  'post_number' => 10,
						  'post_limit' 	=> 10,
						  'show_logo' => 1,
						  'show_image' => 1,
						  'show_like_box'	=> 1,
						  'cache_unit'	=> 1,
						  'cache_duration'	=> 'hours',
						  'show_like_box'	=> 1,
						  'locale' => 'en_US',
						  'locale_other'=> ''
						  );
		/*echo "<pre>";
		print_r($defaults);
		echo "</pre>";*/
  		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		/*echo "<pre>";
		print_r($instance);
		echo "</pre>";*/
		
 		extract($instance, EXTR_SKIP);?>
        <div class="efbl_widget">
 
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'easy-facebook-likebox' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        
        <p>
		<label for="<?php echo $this->get_field_id( 'fanpage_url' ); ?>"><?php _e( 'Fanpage ID:', 'easy-facebook-likebox' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'fanpage_url' ); ?>" name="<?php echo $this->get_field_name( 'fanpage_url' ); ?>" type="text" value="<?php echo esc_attr( $fanpage_url ); ?>"><br />
		<i>E.g jwebsol or 123456789</i>
		</p>
         
        <p class="widget-half">
		<label for="<?php echo $this->get_field_id( 'layout' ); ?>"><?php _e( 'Posts Layout:', 'easy-facebook-likebox' ); ?></label> 
		<select id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo $this->get_field_name( 'layout' ); ?>">
        	 	<option <?php selected( $layout, 'thumbnail' , $echo = true); ?> value="thumbnail" ><?php _e( 'Thumbnail', 'easy-facebook-likebox' ); ?></option>
                <option <?php selected( $layout, 'halfwidth', $echo = true); ?> value="half" ><?php _e( 'Half Width', 'easy-facebook-likebox' ); ?></option>
                <option <?php selected( $layout, 'fullwidth', $echo = true); ?> value="fullwidth"><?php _e( 'Full Width', 'easy-facebook-likebox' ); ?></option>
        </select><br />
 		</p>
        
         <p class="widget-half">
		<label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image size:', 'easy-facebook-likebox' ); ?></label> 
		<select id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>">
        	 	<option <?php selected( $image_size, 'thumbnail' , $echo = true); ?> value="thumbnail" ><?php _e( 'Thumbnail', 'easy-facebook-likebox' ); ?></option>
                <option <?php selected( $image_size, 'album', $echo = true); ?> value="album" ><?php _e( 'Album', 'easy-facebook-likebox' ); ?></option>
                <option <?php selected( $image_size, 'normal', $echo = true); ?> value="normal"><?php _e( 'Normal', 'easy-facebook-likebox' ); ?></option>
        </select><br />
 		</p>
        
        <p class="widget-half efbl_last">
		<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Page type:', 'easy-facebook-likebox' ); ?></label> 
		<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
        	 	<option <?php selected( $type, 'page' , $echo = true); ?> value="page"><?php _e( 'Page', 'easy-facebook-likebox' ); ?></option>
                <option <?php selected( $type, 'group', $echo = true); ?> value="group"><?php _e( 'Group', 'easy-facebook-likebox' ); ?></option>
        </select><br />
 		</p>
        
      
         <p class="widget-half efbl_last">
		<label for="<?php echo $this->get_field_id( 'post_by' ); ?>"><?php _e( 'Posts by:', 'easy-facebook-likebox' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'post_by' ); ?>" name="<?php echo $this->get_field_name( 'post_by' ); ?>">
                 <option <?php selected( $post_by, 'me' , $echo = true); ?> value="me"><?php _e( 'Only the page owner (me)', 'easy-facebook-likebox' ); ?></option>
                <option <?php selected( $post_by, 'others', $echo = true); ?> value="others" ><?php _e( 'Page owner + other people', 'easy-facebook-likebox' ); ?></option>
                <option <?php selected( $post_by, 'onlyothers', $echo = true); ?> value="onlyothers" ><?php _e( 'Only other people', 'easy-facebook-likebox' ); ?></option>
            </select> 
  		</p>
          <div class="clearfix"></div>
        
        <p>
		<label for="<?php echo $this->get_field_id( 'post_number' ); ?>"><?php _e( 'Posts to display:', 'easy-facebook-likebox' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'post_number' ); ?>" name="<?php echo $this->get_field_name( 'post_number' ); ?>" type="text" value="<?php echo esc_attr( $post_number ); ?>" size="5"><br />
		<i><?php _e( 'Define how many posts you want to display in feed', 'easy-facebook-likebox' ); ?></i>
		</p>
        
        <p>
		<label for="<?php echo $this->get_field_id( 'post_limit' ); ?>"><?php _e( 'Posts limit to retrieve:', 'easy-facebook-likebox' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'post_limit' ); ?>" name="<?php echo $this->get_field_name( 'post_limit' ); ?>" type="text" value="<?php echo esc_attr( $post_limit ); ?>" size="5"><br />
		<i><?php _e( 'Define how many posts you want to retrieve from facebook', 'easy-facebook-likebox' ); ?></i>
		</p>

     <p class="widget-half">
        <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_logo' ); ?>" name="<?php echo $this->get_field_name( 'show_logo' ); ?>" value="1" <?php checked( $show_logo, 1, true ); ?> >
			<label for="<?php echo $this->get_field_id( 'show_logo' ); ?>"><?php _e( 'Show page logo', 'easy-facebook-likebox' ); ?></label>
			
		</p>
        
          <p class="widget-half">
        <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_image' ); ?>" name="<?php echo $this->get_field_name( 'show_image' ); ?>" value="1" <?php checked( $show_image, 1 ); ?>>
			<label for="<?php echo $this->get_field_id( 'show_image' ); ?>"><?php _e( 'Show image', 'easy-facebook-likebox' ); ?></label>
		</p>
        
        <p class="widget-half">
        <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_like_box' ); ?>" name="<?php echo $this->get_field_name( 'show_like_box' ); ?>" value="1" <?php checked( $show_like_box, 1 ); ?>>
			<label for="<?php echo $this->get_field_id( 'show_like_box' ); ?>"><?php _e( 'Show like box', 'easy-facebook-likebox' ); ?></label>
			
		</p>
       
        <div class="clearfix"></div>
          <p>
		<label for="<?php echo $this->get_field_id( 'cache_unit' ); ?>"><?php _e( 'Check new posts after every:', 'easy-facebook-likebox' ); ?></label><br />
 
		<input class="half_field" id="<?php echo $this->get_field_id( 'cache_unit' ); ?>" name="<?php echo $this->get_field_name( 'cache_unit' ); ?>" type="text" value="<?php echo esc_attr( $cache_unit ); ?>" size="5">  
        <select class="half_field" id="<?php echo $this->get_field_id( 'cache_duration' ); ?>" name="<?php echo $this->get_field_name( 'cache_duration' ); ?>">
        		<option <?php selected( $cache_duration, 'minutes', $echo = true); ?> value="minutes" ><?php _e( 'Minutes', 'easy-facebook-likebox' ); ?></option>
                 <option <?php selected( $cache_duration, 'hours' , $echo = true); ?> value="hours"><?php _e( 'Hours', 'easy-facebook-likebox' ); ?></option>
                <option <?php selected( $cache_duration, 'days', $echo = true); ?> value="days" ><?php _e( 'Days', 'easy-facebook-likebox' ); ?></option>
            </select><br />
         <i><?php _e( 'Plugin will store the posts in database temporarily and will look for new posts after every selected time duration', 'easy-facebook-likebox' ); ?></i>    
		</p>
       
        <p><?php _e( 'Use Below generated shortcode to use in pages or posts', 'easy-facebook-likebox' ); ?></p>
        <?php 
  		
		$fb_url = parse_url( $fanpage_url );
		$fanpage_url = str_replace('/', '', $fb_url['path']);
		/*echo "<pre>";
		print_r( $fb_url  );
  		echo "</pre>";*/
		 
		$show_logo = (  isset( $show_logo ) ) ? $show_logo : 0;
		$show_image = (  isset( $show_image ) ) ? $show_image : 0;
		$show_like_box = (  isset( $show_like_box ) ) ? $show_like_box : 0;
		?>
        
        <p style="background:#ddd; padding:5px; "><?php echo '[efb_feed fanpage_url="'.$fanpage_url.'" layout="'.$layout.'" image_size="'.$image_size.'" type="'.$type.'" post_by="'.$post_by.'" show_logo="'.$show_logo.'" show_image="'.$show_image.'" show_like_box="'.$show_like_box.'" post_number="'.$post_number.'" post_limit="'.$post_limit.'" cache_unit="'.$cache_unit.'" cache_duration="'.$cache_duration.'" ]'?></p>
         </div>
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
		
		$instance['image_size'] = ( ! empty( $new_instance['image_size'] ) ) ? strip_tags( $new_instance['image_size']  ) : '';
		
		$instance['layout'] = ( ! empty( $new_instance['layout'] ) ) ? strip_tags( $new_instance['layout'] ) : '';
		
		$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
		
		$instance['post_by'] = ( ! empty( $new_instance['post_by'] ) ) ? strip_tags( $new_instance['post_by'] ) : '';
		$instance['post_number'] = ( ! empty( $new_instance['post_number'] ) ) ? strip_tags( $new_instance[ 'post_number'] ) : '';
		
		$instance['post_limit'] = ( ! empty( $new_instance['post_limit'] ) ) ? strip_tags( $new_instance[ 'post_limit'] ) : '';
		
		$instance['show_logo'] = ( ! empty( $new_instance['show_logo'] ) ) ? strip_tags( $new_instance[ 'show_logo'] ) : '';
		
		$instance['show_image'] = ( ! empty( $new_instance['show_image'] ) ) ? strip_tags( $new_instance['show_image']  ) : '';
		
		$instance['show_like_box'] = ( ! empty( $new_instance['show_like_box'] ) ) ? strip_tags( $new_instance['show_like_box'] ) : '';
		
		$instance['cache_unit'] = ( ! empty( $new_instance['cache_unit'] ) ) ? strip_tags( $new_instance['cache_unit'] ) : '';
		
		$instance['cache_duration'] = ( ! empty( $new_instance['cache_duration'] ) ) ? strip_tags( $new_instance['cache_duration'] ) : '';
		
		return $instance;
	}

} // class Foo_Widget
?>