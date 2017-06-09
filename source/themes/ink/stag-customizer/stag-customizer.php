<?php
/**
 * @package Stag_Customizer
 * @author Ram Ratan Maurya (Codestag)
 */

/**
 * Stag Customizer.
 *
 * @version 1.0
 */
final class Stag_Customizer {
	/**
	 * @var Stag_Customizer The single instance of Stag_Customizer class.
	 */
	protected static $_instance = null;

	/**
	 * Stag_Customizer Version.
	 *
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * Main Stag_Customizer class.
	 *
	 * Ensure only one instance of Stag_Customizer is loaded or can be loaded.
	 *
	 * @return Stag_Customizer - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->setup_constants();
			self::$_instance->includes();
		}
		return self::$_instance;
	}

	/**
	 * Stag Customizer constructor.
	 *
	 * @access public
	 * @return Stag_Customizer
	 */
	public function __construct() {

		// Filters that allow shortcodes in Text widgets
		add_filter( 'widget_text', 'shortcode_unautop' );
		add_filter( 'widget_text', 'do_shortcode' );

		add_action( 'wp_head', array( $this, 'add_version_meta' ) );
		add_filter( 'body_class', array( $this, 'body_classes' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'scripts_and_styles' ) );

		add_action( 'customize_preview_init', array( $this, 'customizer_preview_js' ) );
		add_action( 'customize_controls_print_scripts', array( $this, 'customizer_ui_css' ) );

		add_action( 'customize_register', array( $this, 'customize_register_settings' ) );
		add_action( 'customize_register', array( $this, 'customize_register_transport' ) );
	}

	/**
	 * Setup theme constants.
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function setup_constants() {
		define( 'STAG_CUSTOMIZER_VERSION', $this->version );

		if ( function_exists( 'wp_get_theme' ) ) {

			if ( is_child_theme() ) {
				$temp_obj  = wp_get_theme();
				$theme_obj = wp_get_theme( $temp_obj->get('Template') );
			} else {
				$theme_obj = wp_get_theme();
			}

			$theme_version    = $theme_obj->get('Version');
			$theme_name       = $theme_obj->get('Name');
			$theme_uri        = $theme_obj->get('ThemeURI');
			$theme_author     = $theme_obj->get('Author');
			$theme_author_uri = $theme_obj->get('AuthorURI');
		}

		define( 'STAG_THEME_NAME', $theme_name );
		define( 'STAG_THEME_VERSION', $theme_version );
		define( 'STAG_THEME_URI', $theme_uri );
		define( 'STAG_THEME_AUTHOR', $theme_author );
		define( 'STAG_THEME_AUTHOR_URI', $theme_author_uri );

		/**
		 * The suffix to use for scripts.
		 */
		if ( ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ) {
			define( 'STAG_SCRIPT_SUFFIX', '' );
		} else {
			define( 'STAG_SCRIPT_SUFFIX', '.min' );
		}
	}

	public function add_version_meta() {
		echo '<meta name="generator" content="' . STAG_THEME_NAME . ' ' . STAG_THEME_VERSION . '">'."\n";
		echo '<meta name="generator" content="StagCustomizer ' . STAG_CUSTOMIZER_VERSION . '">'."\n";
	}

	/**
	 * Add browser body class.
	 *
	 * Forked from StagFramework.
	 *
	 * @since 1.0
	 *
	 * @param array $classes An array containing classes for body.
	 * @return array Modified body class array.
	 */
	public function body_classes( $classes ) {
		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

		if($is_lynx) $classes[] = 'lynx';
		elseif($is_gecko) $classes[] = 'gecko';
		elseif($is_opera) $classes[] = 'opera';
		elseif($is_NS4) $classes[] = 'ns4';
		elseif($is_safari) $classes[] = 'safari';
		elseif($is_chrome) $classes[] = 'chrome';
		elseif($is_IE) {
			$browser = $_SERVER['HTTP_USER_AGENT'];
			$browser = substr( "$browser", 25, 8);
			if ($browser == "MSIE 7.0"  ) {
				$classes[] = 'ie7';
				$classes[] = 'ie';
			} elseif ($browser == "MSIE 6.0" ) {
				$classes[] = 'ie6';
				$classes[] = 'ie';
			} elseif ($browser == "MSIE 8.0" ) {
				$classes[] = 'ie8';
				$classes[] = 'ie';
			} elseif ($browser == "MSIE 9.0" ) {
				$classes[] = 'ie9';
				$classes[] = 'ie';
			} else {
				$classes[] = 'ie';
			}
		}
		else $classes[] = 'unknown';

		if( $is_iphone ) $classes[] = 'iphone';

		return $classes;
	}

	/**
	 * Enqueue required scripts and styles.
	 *
	 * @param string $hook Current page slug.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function scripts_and_styles( $hook ) {
		if( $hook == 'post.php' || $hook == 'post-new.php' ) {
			wp_enqueue_media();
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'stag-admin-metabox', get_template_directory_uri() . '/stag-customizer/css/stag-admin-metabox.css', array('wp-color-picker'), STAG_CUSTOMIZER_VERSION, 'screen' );
		}
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function customizer_preview_js() {
		wp_enqueue_script( 'stag_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20130508', true );
	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function includes() {
		// Common includes

		$path = get_template_directory() . '/stag-customizer/';

		require_once $path . 'class-stag-logo.php';
		require_once $path . 'class-stag-widget.php';
		require_once $path . 'class-tgm-plugin-activation.php';
		require_once $path . 'helper-fonts.php';
		require_once $path . 'helper-typekit.php';

		if( is_admin() ) : // Admin includes
			require_once $path . 'stag-admin-metabox.php';
		else : // Front-end includes

		endif;
	}

	/**
	 * Register settings.
	 *
	 * Take the final list of theme mods, and register all the settings,
	 * and add all of the proper controls.
	 *
	 * If the type is one of the default supported ones, add it normally. Otherwise
	 * Use the type to create a new instance of that control type.
	 *
	 * @since 1.0
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @return void
	 */
	function customize_register_settings( $wp_customize ) {
		$mods = stag_get_theme_mods();

		foreach ( $mods as $section => $settings ) {
			foreach ( $settings as $key => $setting ) {
				$wp_customize->add_setting( $key, array(
					'default' => stag_theme_mod( $section, $key, true )
				) );

				$type = $setting[ 'type' ];

				if ( in_array( $type, array( 'text', 'checkbox', 'radio', 'select', 'dropdown-pages' ) ) ) {
					$wp_customize->add_control( $key, array(
						'label'      => $setting[ 'title' ],
						'section'    => $section,
						'settings'   => $key,
						'type'       => $type,
						'choices'    => isset ( $setting[ 'choices' ] ) ? $setting[ 'choices' ] : null,
						'priority'   => isset ( $setting[ 'priority' ] ) ? $setting[ 'priority' ] : null
					) );
				} else {
					$wp_customize->add_control( new $type( $wp_customize, $key, array(
						'label'      => $setting[ 'title' ],
						'section'    => $section,
						'settings'   => $key,
						'choices'    => isset ( $setting[ 'choices' ] ) ? $setting[ 'choices' ] : null,
						'priority'   => isset ( $setting[ 'priority' ] ) ? $setting[ 'priority' ] : null
					) ) );
				}
			}
		}

		do_action( 'stag_customize_regiser_settings', $wp_customize );

		return $wp_customize;
	}

	/**
	 * Add postMessage support for all default fields, as well
	 * as the site title and desceription for the Theme Customizer.
	 *
	 * @since 1.0
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @return void
	 */
	function customize_register_transport( $wp_customize ) {
		$built_in = array( 'blogname' => '', 'blogdescription' => '', 'header_textcolor' => '' );
		$stag     = stag_get_theme_mods( array( 'keys_only' => true ) );

		$transport = array_merge( $built_in, $stag );

		foreach ( $transport as $key => $default ) {

			$transport = ( '' != $default ) ? $default : 'postMessage';

			$wp_customize->get_setting( $key )->transport = $transport;
		}
	}

	function customizer_ui_css() {
		?>
		<style type="text/css">
			.stag_slider {
				border-radius: 4px;
				margin-top: 11px;
				height: 3px;
				border: 1px solid #ddd;
				text-align: left;
				background: #fff;
				position: relative;
			}
			.stag_slider .ui-slider-handle {
				background: #FFF;
				border: 1px solid #d0d0d0;
				border-radius: 100px;
				cursor: pointer;
				height: 1.4em;
				margin-left: -.6em;
				outline: 0;
				position: absolute;
				top: -9px;
				width: 1.4em;
				z-index: 2;
				-moz-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
				box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			}
		</style>
		<?php
	}
}

function SC() {
	return Stag_Customizer::instance();
}

$GLOBALS['stag_customizer'] = SC();


/**
 * Get Theme Mod
 *
 * Instead of options, customizations are stored/accessed via Theme Mods
 * (which are still technically settings). This wrapper provides a way to
 * check for an existing mod, or load a default in its place.
 *
 * @since 1.0
 *
 * @param string $key The key of the theme mod to check. Prefixed with 'stag_'
 * @return mixed The theme modification setting
 */
function stag_theme_mod( $section, $key, $_default = false ) {
	$mods = stag_get_theme_mods();

	$default = $mods[ $section ][ $key ][ 'default' ];

	if ( $_default )
		$mod = $default;
	else
		$mod = get_theme_mod( $key, $default );

	return apply_filters( 'stag_theme_mod_' . $key, $mod );
}

/**
 * Textarea Control
 *
 * Attach the custom textarea control to the `customize_register` action
 * so the WP_Customize_Control class is initiated.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function stag_customize_textarea_control( $wp_customize ) {
	/**
	 * Textarea Control
	 */
	class Stag_Customize_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';

		public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		</label>

		<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
		<?php
		}
	}
}
add_action( 'customize_register', 'stag_customize_textarea_control', 1, 1 );

/**
 * Slider Control
 *
 * Attach the custom slider control to the `customize_register` action
 * so the WP_Customize_Control class is initiated.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function stag_customize_slider_control( $wp_customize ) {
	/**
	 * Slider Control
	 */
	class Stag_Customize_Slider_Control extends WP_Customize_Control {
		public $type = 'slider';

		public function enqueue() {
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-slider' );
		}

		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input style="width: 13%; margin-right: 3%; float: left; text-align: center;" type="text" id="input_<?php echo $this->id; ?>" value="<?php echo $this->value(); ?>" <?php $this->link(); ?>/>
			</label>

			<div style="width: 82%; float: left;" id="slider_<?php echo $this->id; ?>" class="stag_slider"></div>

			<script>
			jQuery(document).ready(function($) {
			    $( "#slider_<?php echo $this->id; ?>" ).slider({
			        value: <?php echo $this->value(); ?>,
			        min: <?php echo $this->choices['min']; ?>,
			        max: <?php echo $this->choices['max']; ?>,
			        step: <?php echo $this->choices['step']; ?>,
			        slide: function( event, ui ) {
			            $( "#input_<?php echo $this->id; ?>" ).val(ui.value).keyup();
			        }
			    });
			    $( "#input_<?php echo $this->id; ?>" ).val( $( "#slider_<?php echo $this->id; ?>" ).slider( "value" ) );
			});
			</script>
		<?php
		}
	}
}
add_action( 'customize_register', 'stag_customize_slider_control', 1, 1 );


if ( ! function_exists( 'stag_get_post_meta' ) ) :
/**
 * Get a specific value from one of the two post meta arrays.
 *
 * Posts in Ink store meta data in two different arrays. The "cache" array
 * is for data that is generated during a page load that can be stored and
 * retrieved later for better performance. The "settings" array is for user-
 * specified data pertaining to the post.
 *
 * @since 1.0.
 *
 * @param  string $type    Which meta array. Either 'cache' or 'settings'.
 * @param  int    $post_id The post id.
 * @param  string $key     The array key to target.
 * @return mixed|bool      The stored value, or false if it doesn't exist.
 */
function stag_get_post_meta( $type, $post_id, $key ) {
	$post_meta = get_post_meta( $post_id, '_stag-post-' . $type, true );

	if ( isset( $post_meta[$key] ) ) {
		return $post_meta[$key];
	} else {
		return false;
	}
}
endif;

if ( ! function_exists( 'stag_update_post_meta' ) ) :
/**
 * Update or remove an item from one of the two post meta arrays.
 *
 * Posts in Ink store meta data in two different arrays. The "cache" array
 * is for data that is generated during a page load that can be stored and
 * retrieved later for better performance. The "settings" array is for user-
 * specified data pertaining to the post.
 *
 * @since 1.0.
 *
 * @param string $type    Which meta array. Either 'cache' or 'settings'.
 * @param int    $post_id The post id.
 * @param string $key     The array key to target.
 * @param mixed  $value   The value to set.
 */
function stag_update_post_meta( $type, $post_id, $key, $value ) {
	if ( ! in_array( $type, array( 'cache', 'settings' ) ) ) {
		return;
	}

	$post_meta = get_post_meta( $post_id, '_stag-post-' . $type, true );

	// If value is null, remove the array item
	if ( null === $value && isset( $post_meta[$key] ) ) {
		unset( $post_meta[$key] );
	}
	// Otherwise set/add the array item
	else {
		$post_meta[$key] = $value;
	}

	update_post_meta( $post_id, '_stag-post-' . $type, $post_meta );
}
endif;

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Stag_Customize_Misc_Control' ) ) :
/**
 * Class Stag_Customize_Misc_Control
 *
 * Control for adding arbitrary HTML to a Customizer section.
 *
 * @since 1.1.0.
 */
class Stag_Customize_Misc_Control extends WP_Customize_Control {
	/**
	 * The current setting name.
	 *
	 * @since 1.0.0.
	 *
	 * @var   string    The current setting name.
	 */
	public $settings = 'blogname';

	/**
	 * The current setting description.
	 *
	 * @since 1.0.0.
	 *
	 * @var   string    The current setting description.
	 */
	public $description = '';

	/**
	 * The current setting group.
	 *
	 * @since 1.0.0.
	 *
	 * @var   string    The current setting group.
	 */
	public $group = '';

	/**
	 * Render the description and title for the section.
	 *
	 * Prints arbitrary HTML to a customizer section. This provides useful hints for how to properly set some custom
	 * options for optimal performance for the option.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function render_content() {
		switch ( $this->type ) {
			default:
			case 'text' :
				echo '<p class="description">' . stag_allowed_tags( $this->description ) . '</p>';
				break;

			case 'heading':
				echo '<span class="customize-control-title">' . stag_allowed_tags( $this->label ) . '</span>';
				break;

			case 'line' :
				echo '<hr />';
				break;
		}
	}
}
endif;
