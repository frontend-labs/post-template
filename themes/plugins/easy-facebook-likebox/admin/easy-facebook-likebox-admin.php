<?php 
/** 
 * Plugin Name.
 *
 * @package   Easy_Facebook_Likebox_Admin
 * @author    Sajid Javed <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Plugin_Name_Admin
 * @author  Your Name <email@example.com> 
 */
class Easy_Facebook_Likebox_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		
		$plugin = Easy_Facebook_Likebox::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
		
		add_action( 'admin_init', array( $this, 'i_have_supported_efbl') );
		
		//if ( get_option('I_HAVE_SUPPORTED_THE_EFBL_PLUGIN') != 1 )
			add_action( 'admin_notices', array( $this, 'post_installtion_upgrade_nag') );
 
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @TODO:
	 *
	 * - Rename "Plugin_Name" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		/*if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			
		}*/
		
		wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Easy_Facebook_Likebox::VERSION );

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @TODO:
	 *
	 * - Rename "Plugin_Name" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script('common');
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Easy_Facebook_Likebox::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * @TODO:
		 *
		 * - Change 'Page Title' to the title of your plugin admin page
		 * - Change 'Menu Text' to the text for menu item for the plugin settings page
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'Easy Fcebook Likebox', $this->plugin_slug ),
			__( 'Easy Fcebook Likebox', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' ),
			plugins_url( 'easy-facebook-likebox/assets/PluginIcon.png' )
		);
		
		add_action('load-'.$this->plugin_screen_hook_suffix, array(&$this, 'on_load_page'));

	}
	
	//will be executed if wordpress core detects this page has to be rendered
	function on_load_page() {
		 
 		//add several metaboxes now, all metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
		add_meta_box('easy-facebook-how_to', __('How to use this plugin', 'easy-facebook-likebox'), array(&$this, 'on_how_to_use'), $this->plugin_screen_hook_suffix, 'normal', 'core');
		add_meta_box('easy-facebook-feed', __('Settings', 'easy-facebook-likebox'), array(&$this, 'on_efbfeed_settings'), $this->plugin_screen_hook_suffix, 'easyfbfeed', 'core');
		
 		add_meta_box('easy-facebook-likebox_popup', __('Like box pup up settings', 'easy-facebook-likebox'), array(&$this, 'on_popup_settings'), $this->plugin_screen_hook_suffix, 'additional', 'core');
		add_meta_box('efbl-support_us_box', __( 'Support us by liking our fan page and/or consider some dontaion!' , 'easy-facebook-likebox'), array(&$this, 'on_support_us'), $this->pagehook, 'side', 'core');
 		
		 
	}
	
	/*
	 * Display first metabox with special instructions.
	 *
 	 */	
	
	function on_how_to_use( $data ){
		include_once( 'views/instructinos.php' );  
	}
	
	/*
	 * Display promotion block
	 *
 	 */	
	function on_support_us( $data ){
		include_once( 'views/support-us.php' );  
	  }
	
	/*
	 * Display popup settings block
	 *
 	 */	
	function on_popup_settings( $data ){
			include_once( 'views/popup-settings.php' );  
	}
	
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}
	
	

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}
	
	/**
	 * Display a thank you nag when the plugin has been installed/upgraded.
	 */
	public function post_installtion_upgrade_nag() { 
 		if ( !current_user_can('install_plugins') ) return;
		
		$plugin_verstion = Easy_Facebook_Likebox::VERSION;
		
		$version_key = '_efbl_version';
		$notice_key = 'I_HAVE_SUPPORTED_THE_EFBL_PLUGIN';
		
		if ( get_site_option( $version_key ) == $plugin_verstion && get_site_option( $notice_key ) == 1 ) return;

		$msg = sprintf(__('Thanks for installting/upgrading the Easy Facebook Likebox Plugin! If you like this plugin, please consider some <a href="%s" target="_blank">donation</a> and/or <a href="%s" target="_blank">rating it</a>!
		Support us by liking our facebook fan page! 
		
	  <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=517129121754984&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>

<div class="fb-like" data-href="https://facebook.com/jwebsol" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
			  	  <br /><br />
		<a href="%s" class="button button-primary">I have supported already</a>				  
		', $this->plugin_slug ),
				'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=sjaved786%40gmail%2ecom&lc=US&item_name=Easy%20Facebook%20Like%20Box%20WordPress%20Plugin&item_number=efbl&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted',
				'http://wordpress.org/plugins/easy-facebook-likebox/',
				get_admin_url('', '/admin.php?page=easy-facebook-likebox&efbl_supported=1')
				);
		echo "<div class='update-nag'>$msg</div>";

		update_site_option( $version_key, $plugin_verstion );
 	}
	
	/**
	 * Provides default values for the Social Options.
	 */
	function efbl_default_options() {
 		
		$defaults = array(
			'efbl_enable_popup'			=>	'',
			'efbl_popup_interval'		=>	5000,
			'efbl_popup_width'			=>	400,
			'efbl_popup_height'			=>	300,
			'efbl_popup_shortcode'			=>	'',
		);
		
		return apply_filters( 'efbl_default_options', $defaults );
		
	} // end sandbox_theme_default_social_options

	
	function i_have_supported_efbl(){
 		 
		// First, we register a section. This is necessary since all future options must belong to a 
		add_settings_section(
			'efbl_general_settings_section',			// ID used to identify this section and with which to register options
			__( '', 'easy-facebook-likebox' ),		// Title to be displayed on the administration page
			array($this, 'efbl_options_callback'),	// Callback used to render the description of the section
			'efbl_settings_display_options'		// Page on which to add this section of options
		);
		
		// First, we register a section. This is necessary since all future options must belong to a 
		add_settings_section(
			'efbl_general_settings_section_advanced',			// ID used to identify this section and with which to register options
			__( 'Like box pup up advanced settings', 'easy-facebook-likebox' ),		// Title to be displayed on the administration page
			array($this, 'efbl_options_callback'),	// Callback used to render the description of the section
			'efbl_settings_display_options'		// Page on which to add this section of options
		);
		
		
		// Next, we'll introduce the fields for toggling the visibility of content elements.
		add_settings_field(	
			'efbl_enable_popup',						// ID used to identify the field throughout the theme
			__( 'Enable PopUp', 'easy-facebook-likebox' ),			// The label to the left of the option interface element
			array($this, 'efbl_display_enable_check'),	// The name of the function responsible for rendering the option interface
			'efbl_settings_display_options',	// The page on which this option will be displayed
			'efbl_general_settings_section',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				__( 'Activate this setting to display the header.', 'easy-facebook-likebox' ),
			)
		);
		
		// Next, we'll introduce the fields for toggling the visibility of content elements.
		add_settings_field(	
			'efbl_popup_interval',						// ID used to identify the field throughout the theme
			__( 'PopUp delay after page load', 'easy-facebook-likebox' ),			// The label to the left of the option interface element
			array($this, 'efbl_display_popup_interval'),	// The name of the function responsible for rendering the option interface
			'efbl_settings_display_options',	// The page on which this option will be displayed
			'efbl_general_settings_section',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				__( 'Delay in miliseconds. 1000 ms = 1 second.', 'easy-facebook-likebox' ),
			) 
		);
		
		
		// Next, we'll introduce the fields for toggling the visibility of content elements.
		add_settings_field(	
			'efbl_popup_width',						// ID used to identify the field throughout the theme
			__( 'PopUp Width', 'easy-facebook-likebox' ),			// The label to the left of the option interface element
			array($this, 'efbl_display_popup_width'),	// The name of the function responsible for rendering the option interface
			'efbl_settings_display_options',	// The page on which this option will be displayed
			'efbl_general_settings_section',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				__( 'Width in pixels.', 'easy-facebook-likebox' ),
			)
		);
		
		// Next, we'll introduce the fields for toggling the visibility of content elements.
		add_settings_field(	
			'efbl_popup_height',						// ID used to identify the field throughout the theme
			__( 'PopUp height', 'easy-facebook-likebox' ),			// The label to the left of the option interface element
			array($this, 'efbl_display_popup_height'),	// The name of the function responsible for rendering the option interface
			'efbl_settings_display_options',	// The page on which this option will be displayed
			'efbl_general_settings_section',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				__( 'Height in pixels.', 'easy-facebook-likebox' ),
			) 
		);
		
 		
		add_settings_field(	
			'efbl_popup_shortcode',						// ID used to identify the field throughout the theme
			__( 'PopUp content', 'easy-facebook-likebox' ),			// The label to the left of the option interface element
			array($this, 'efbl_display_popup_shortcode'),	// The name of the function responsible for rendering the option interface
			'efbl_settings_display_options',	// The page on which this option will be displayed
			'efbl_general_settings_section',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				__( 'You can generate easy facebook like box shortcode from Widgets > Easy Facebook LikeBox.', 'easy-facebook-likebox' ),
			)
		);
		
		add_settings_field(	
			'efblp_enabe_show_on_home_only',						// ID used to identify the field throughout the theme
			__( 'Show on home only', 'easy-facebook-likebox' ),			// The label to the left of the option interface element
			array($this, 'efbl_display_on_home_only'),	// The name of the function responsible for rendering the option interface
			'efbl_settings_display_options',	// The page on which this option will be displayed
			'efbl_general_settings_section_advanced',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				__( 'Display PopUp on home page only.', 'easy-facebook-likebox' ),
			)
		);
		
		add_settings_field(	
			'efblp_enabe_if_login',						// ID used to identify the field throughout the theme
			__( 'Enable if visitor is logged in', 'easy-facebook-likebox' ),			// The label to the left of the option interface element
			array($this, 'efbl_display_enable_user_loggin'),	// The name of the function responsible for rendering the option interface
			'efbl_settings_display_options',	// The page on which this option will be displayed
			'efbl_general_settings_section_advanced',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				__( 'Shows the PopUp if the user is logged in to your site.', 'easy-facebook-likebox' ),
			)
		);
		
		add_settings_field(	
			'efblp_enabe_if_not_login',						// ID used to identify the field throughout the theme
			__( 'Enable if visitor is not logged in', 'easy-facebook-likebox' ),			// The label to the left of the option interface element
			array($this, 'efbl_display_enable_user_not_loggin'),	// The name of the function responsible for rendering the option interface
			'efbl_settings_display_options',	// The page on which this option will be displayed
			'efbl_general_settings_section_advanced',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				__( 'Shows the PopUp if the user is not logged in to your site (Above option will be ignored if checked).', 'easy-facebook-likebox' ),
			)
		);
		
		add_settings_field(	
			'efblp_enabe_do_not_show_again',						// ID used to identify the field throughout the theme
			__( 'Close button act as never show again', 'easy-facebook-likebox' ),			// The label to the left of the option interface element
			array($this, 'efbl_do_not_show_again'),	// The name of the function responsible for rendering the option interface
			'efbl_settings_display_options',	// The page on which this option will be displayed
			'efbl_general_settings_section_advanced',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				__( 'PopUp will not appear when visitor will close it once.', 'easy-facebook-likebox' ),
			)
		);
		
		add_settings_field(	
			'efblp_enabe_do_not_show_on_mobile',						// ID used to identify the field throughout the theme
			__( 'Disable on mobile', 'easy-facebook-likebox' ),			// The label to the left of the option interface element
			array($this, 'efbl_do_not_show_on_mobile'),	// The name of the function responsible for rendering the option interface
			'efbl_settings_display_options',	// The page on which this option will be displayed
			'efbl_general_settings_section_advanced',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				__( 'Do not display on mobile devices', 'easy-facebook-likebox' ),
			)
		);
		
		
		
		// Finally, we register the fields with WordPress
		register_setting(
				'efbl_settings_display_options',
				'efbl_settings_display_options'
		);
 			
		if(isset($_GET['efbl_supported'])) {
			update_site_option( 'I_HAVE_SUPPORTED_THE_EFBL_PLUGIN', 1 );	
 		}
	}
	
	function efbl_options_callback(){
		//Do nothing for now
	}
	 
	
	//Enable pupup
 	function efbl_display_enable_check(){

		$options = get_option( 'efbl_settings_display_options' );
		$options = wp_parse_args($options, $this->efbl_default_options());

		$html = '<input type="checkbox" id="efbl_enable_popup" name="efbl_settings_display_options[efbl_enable_popup]" value="1"' . checked( 1, $options['efbl_enable_popup'], false ) . '/>';
		$html .= '&nbsp;';
		
		echo $html;
		
	}
	
	//Interval
	function efbl_display_popup_interval($args) {

		$options = get_option( 'efbl_settings_display_options' );
		$options = wp_parse_args($options, $this->efbl_default_options());
		
		// Render the output
		echo '<input type="text" id="efbl_popup_interval" name="efbl_settings_display_options[efbl_popup_interval]" value="' . $options['efbl_popup_interval'] . '" />';
		
		echo '&nbsp;<label for="efbl_popup_interval">'.$args[0].'</label>';
	
	} // end sandbox_input_element_callback

	//Width
	function efbl_display_popup_width($args) {
	
		$options = get_option( 'efbl_settings_display_options' );
		$options = wp_parse_args($options, $this->efbl_default_options());
		
		// Render the output
		echo '<input type="text" id="efbl_popup_width" name="efbl_settings_display_options[efbl_popup_width]" value="' . $options['efbl_popup_width'] . '" />';
		
		echo '&nbsp;<label for="efbl_popup_width">'.$args[0].'</label>';
	
	} // end sandbox_input_element_callback
	
	//Height
	function efbl_display_popup_height($args) {
	
		$options = get_option( 'efbl_settings_display_options' );
		$options = wp_parse_args($options, $this->efbl_default_options());
		
		// Render the output
		echo '<input type="text" id="efbl_popup_height" name="efbl_settings_display_options[efbl_popup_height]" value="' . $options['efbl_popup_height'] . '" />';
		
		echo '&nbsp;<label for="efbl_popup_height">'.$args[0].'</label>';
	
	} // end sandbox_input_element_callback

	
	function efbl_display_popup_shortcode($args){
		
		$options = get_option( 'efbl_settings_display_options' );
		$options = wp_parse_args($options, $this->efbl_default_options());
		/*echo "<pre>";
		print_r($options);
		echo "</pre>"; */
		
		$html = '<textarea id="efbl_popup_shortcode" name="efbl_settings_display_options[efbl_popup_shortcode]" rows="5" cols="50" placeholder="'.$description[0].'">' . $options['efbl_popup_shortcode'] . '</textarea>';
 		 
		$html .= '<br /> '.$args[0].'&nbsp;';
		
		echo $html;
		
	}
	
	//Enable when uers logged pupup
 	function efbl_display_on_home_only($args){

		$options = get_option( 'efbl_settings_display_options' );

		$html = '<input type="checkbox" id="efbl_enabe_home_only" name="efbl_settings_display_options[efbl_enabe_if_home]" value="1"' . checked( 1, $options['efbl_enabe_if_home'], false ) . '/>';
		$html .= '&nbsp;&nbsp;';
		$html .= '<i>'.$args[0].'</i>';
		$html .= '&nbsp;';
		echo $html;
		
	}
	
	//Enable when uers logged pupup
 	function efbl_display_enable_user_loggin($args){

		$options = get_option( 'efbl_settings_display_options' );

		$html = '<input type="checkbox" id="efbl_enabe_if_login" name="efbl_settings_display_options[efbl_enabe_if_login]" value="1"' . checked( 1, $options['efbl_enabe_if_login'], false ) . '/>';
		$html .= '&nbsp;&nbsp;';
		$html .= '<i>'.$args[0].'</i>';
		$html .= '&nbsp;';
		echo $html;
		
	}
	
	//Enable when uers not logged in 
 	function efbl_display_enable_user_not_loggin($args){

		$options = get_option( 'efbl_settings_display_options' );

		$html = '<input type="checkbox" id="efbl_enabe_if_not_login" name="efbl_settings_display_options[efbl_enabe_if_not_login]" value="1"' . checked( 1, $options['efbl_enabe_if_not_login'], false ) . '/>';
		
		$html .= '&nbsp;&nbsp;';
		
		$html .= '<i>'.$args[0].'</i>';
		
		$html .= '&nbsp;';
		
		echo $html;
		
	}
	
	//Enable when uers not logged in 
 	function efbl_do_not_show_again($args){

		$options = get_option( 'efbl_settings_display_options' );

		$html = '<input type="checkbox" id="efbl_do_not_show_again" name="efbl_settings_display_options[efbl_do_not_show_again]" value="1"' . checked( 1, $options['efbl_do_not_show_again'], false ) . '/>';
		
		$html .= '&nbsp;&nbsp;';
		$html .= '<i>'.$args[0].'</i>';
		$html .= '&nbsp;';
		echo $html;
		
	}
	
	//Hide on mobile
 	function efbl_do_not_show_on_mobile($args){

		$options = get_option( 'efbl_settings_display_options' );

		$html = '<input type="checkbox" id="efbl_do_not_show_again" name="efbl_settings_display_options[efbl_do_not_show_on_mobile]" value="1"' . checked( 1, $options['efbl_do_not_show_on_mobile'], false ) . '/>';
		
		$html .= '&nbsp;&nbsp;';
		
		$html .= '<i>'.$args[0].'</i>';
		
		$html .= '&nbsp;';
		
		echo $html;
		
	}
}
