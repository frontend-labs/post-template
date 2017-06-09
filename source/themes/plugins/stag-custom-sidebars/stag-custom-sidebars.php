<?php
/**
 * Plugin Name: Stag Custom Sidebars
 * Plugin URI: https://wordpress.org/plugins/stag-custom-sidebars
 * Description: Create custom dynamic sidebars and use anywhere with shortcodes.
 * Version: 1.0.11
 * Author: Ram Ratan Maurya
 * Author URI: https://mauryaratan.me
 * Requires at least: 3.3
 * Tested up to: 4.0
 * License: GPLv2 or later
 *
 * Text Domain: stag
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Plugin class for Stag Custom Sidebars.
 *
 * @package Stag_Custom_Sidebars
 * @author Ram Ratan Maurya
 * @version 1.0.11
 * @copyright 2014 Ram Ratan Maurya
 */
final class Stag_Custom_Sidebars {

	/**
	 * @var Stag_Custom_Sidebars The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * @var string
	 */
	public $version = '1.0.11';

	/**
	 * @var string
	 */
	public $plugin_url;

	/**
	 * @var string
	 */
	public $stored;

	/**
	 * @var array
	 */
	public $sidebars = array();

	/**
	 * @access protected
	 * @var string
	 */
	protected $title;

	/**
	 * Main Stag_Custom_Sidebars Instance
	 *
	 * Ensures only one instance of Stag_Custom_Sidebars is loaded or can be loaded.
	 *
	 * @since 1.0.6
	 * @static
	 * @see SCS()
	 * @return Stag_Custom_Sidebars - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Plugin Constructor.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {

		$this->title  = __( 'Custom Widget Area', 'stag' );
		$this->stored = 'stag_custom_sidebars';

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'admin_footer', array( &$this, 'template_custom_widget_area' ), 200 );
		add_action( 'load-widgets.php', array( &$this, 'load_scripts_styles' ) , 5 );

		add_action( 'widgets_init', array( &$this, 'register_custom_sidebars' ), 1000 );
		add_action( 'wp_ajax_stag_ajax_delete_custom_sidebar', array( &$this, 'delete_sidebar_area' ) , 1000 );

		add_shortcode( 'stag_sidebar', array( &$this, 'stag_sidebar_shortcode' ) );

		add_filter( 'wie_unencoded_export_data', array( &$this, 'export_data' ) );
		add_filter( 'wie_import_results', array( &$this, 'reset_custom_key' ) );
		add_action( 'wie_import_data', array( &$this, 'before_wie_import' ) );

		add_action( 'customize_controls_print_scripts', array( &$this, 'customize_controls_print_scripts' ) );
	}

	/**
	 * Internationalization.
	 *
	 * @return void
	 */
	function load_plugin_textdomain() {
		load_plugin_textdomain( 'stag', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Get the plugin url.
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_url() {
		if ( $this->plugin_url ) return $this->plugin_url;
		return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Register/queue scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function load_scripts_styles() {

		global $wp_version;

		add_action( 'load-widgets.php', array( $this, 'add_sidebar_area' ), 100 );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'stag-custom-sidebars', $this->plugin_url() . '/assets/js/stag-custom-sidebars.js', array( 'jquery' ), $this->version, true );

		wp_localize_script( 'stag-custom-sidebars', 'objectL10n', array(
			'shortcode'           => __( 'Shortcode', 'stag' ),
			'delete_sidebar_area' => __( 'Are you sure you want to delete this sidebar?', 'stag' )
			) );

		if ( true === version_compare( $wp_version, '3.7.9', '>' ) ) {
			wp_enqueue_style( 'stag-custom-sidebars-wp38plus', $this->plugin_url() .  '/assets/css/stag-custom-sidebars-wp38plus.css', '', $this->version, 'screen' );
		} else {
			wp_enqueue_style( 'stag-custom-sidebars', $this->plugin_url() .  '/assets/css/stag-custom-sidebars.css', '', $this->version, 'screen' );
		}
	}

	/**
	 * Template for displaying the custom widget area add interface.
	 *
	 * @return Output custom widget area field
	 */
	public function template_custom_widget_area() {
		global $wp_version;
		?>
		<script type="text/html" id="tmpl-stag-add-widget">
			<div class="stag-widgets-holder-wrap">
				<?php if ( false === version_compare( $wp_version, '3.7.9', '>' ) ) : ?>
				<div class="sidebar-name">
					<h3><?php echo $this->title ?></h3>
				</div>
			<?php endif; ?>

			<form class="stag-add-widget" method="post">
				<?php if ( true === version_compare( $wp_version, '3.7.9', '>' ) ) : ?>
				<div class="sidebar-name">
					<h3><?php echo $this->title ?></h3>
				</div>
			<?php endif; ?>
			<input type="text" name="stag-add-widget" value="" placeholder="<?php _e( 'Enter name of the new widget area here', 'stag' ); ?>" required />
			<?php submit_button( __( 'Add Widget Area', 'stag' ), 'secondary large', $name = 'stag-custom-sidebar-submit' ); ?>
			<input type='hidden' name='scs-delete-nonce' value="<?php echo wp_create_nonce( 'scs-delete-nonce' ) ?>">
		</form>
	</div>
</script>
<?php
}

	/**
	 * Add Sidebar area.
	 *
	 * @return void
	 */
	public function add_sidebar_area() {
		if ( ! empty( $_POST['stag-add-widget'] ) ) {
			$this->sidebars = get_option( $this->stored );
			$name           = $this->get_name( $_POST['stag-add-widget'] );

			$this->sidebars[ sanitize_title_with_dashes( $name ) ] = $name;

			update_option( $this->stored, $this->sidebars );
			wp_redirect( admin_url( 'widgets.php' ) );
			die();
		}
	}

	/**
	 * Delete Sidebar area.
	 *
	 * @return void
	 */
	public function delete_sidebar_area() {
		check_ajax_referer( 'scs-delete-nonce' );

		if ( ! empty( $_POST['name'] ) ) {
			$name           = sanitize_title_with_dashes( stripslashes( $_POST['name'] ) );
			$this->sidebars = get_option( $this->stored );

			if ( array_key_exists( $name, $this->sidebars ) ) {
				unset( $this->sidebars[ $name ] );
				update_option( $this->stored, $this->sidebars );
				unregister_sidebar( $name );
				echo 'sidebar-deleted';
			}
		}
		die();
	}

	/**
	 * Check user entered widget area name and manage conflicts.
	 *
	 * @param string $name User entered name
	 * @return string Processed name
	 */
	public function get_name( $name ) {
		if ( empty( $GLOBALS['wp_registered_sidebars'] ) ) {
			return $name;
		}

		$taken = array();

		foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
			$taken[] = $sidebar['name'];
		}

		if ( empty($this->sidebars) ) $this->sidebars = array();
		$taken = array_merge( $taken, $this->sidebars );

		if ( in_array( $name, $taken ) ) {
			$counter  = substr( $name, -1 );
			$new_name = '';

			if ( ! is_numeric( $counter ) ) {
				$new_name = $name . ' 1';
			} else {
				$new_name = substr( $name, 0, -1 ) . ( (int) $counter + 1 );
			}

			$name = $this->get_name( $new_name );
		}

		return $name;
	}

	/**
	 * Register sidebars.
	 *
	 * @access public
	 * @return void
	 */
	public function register_custom_sidebars() {

		$sidebars = get_option( $this->stored );

		$args = apply_filters( 'stag_custom_sidebars_widget_args', array(
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
			)
		);

		if ( is_array( $sidebars ) ) {
			foreach ( $sidebars as $sidebar ) {
				$args['name']  = $sidebar;

				$sidebar = sanitize_title_with_dashes( $sidebar );

				$args['id']    = $sidebar;
				$args['class'] = 'stag-custom';

				register_sidebar( apply_filters( 'scs_widget_args_' . $sidebar, $args ) );
			}
		}
	}

	/**
	 * Shortcode handler.
	 *
	 * @param  array $atts Array of attributes
	 * @return string $output returns the modified html string
	 */
	public function stag_sidebar_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'id' => '1',
			'class' => '',
		), $atts );

		$output = '';

		if ( is_active_sidebar( $atts['id'] ) ) {
			ob_start();

			do_action( 'stag_custom_sidebars_before', $atts['id'] );

			echo "<section id='{$atts['id']}' class='stag-custom-widget-area {$atts['class']}'>";
			dynamic_sidebar( $atts['id'] );
			echo '</section>';

			do_action( 'stag_custom_sidebars_after' );

			$output = ob_get_clean();
		}

		return $output;
	}

	/**
	 * Set a custom array key in export data.
	 *
	 * Inject all custom sidebar areas created on site under export data of "Widget Importer and Exporter".
	 *
	 * @uses Widget_Importer_Exporter
	 * @link https://wordpress.org/plugins/widget-importer-exporter
	 *
	 * @since 1.0.6
	 * @param  array $sidebars An array containing sidebars' widget data.
	 * @return array $sidebars Modified array, adds custom array key set during export.
	 */
	public function export_data( $sidebars ) {

		if ( empty( $this->sidebars ) ) $this->sidebars = get_option( $this->stored );

		$sidebars['stag-custom-sidebars-areas'] = $this->sidebars;

		return $sidebars;
	}

	/**
	 * Delete custom array key before 'Widget Importer & Exporter' import.
	 *
	 * @uses Widget_Importer_Exporter
	 * @link https://wordpress.org/plugins/widget-importer-exporter
	 *
	 * @since 1.0.6
	 * @param  array $results An array containing sidebars' widget data.
	 * @return array $results Modified array, deletes custom array key set during export.
	 */
	public function reset_custom_key( $results ) {
		unset($results['stag-custom-sidebars-areas']);

		return $results;
	}

	/**
	 * Create new sidebar areas.
	 *
	 * Filter widget data before widgets import. Deletes the custom key set during widget file export.
	 * Also register new custom widgets areas.
	 *
	 * @global $wp_registered_sidebars
	 *
	 * @param  object $data Contains widget import data.
	 * @return array  $data Modified widget import data.
	 */
	function before_wie_import( $data ) {
		global $wp_registered_sidebars;

		$data = (array) $data;

		$key             = 'stag-custom-sidebars-areas';
		$sidebars        = get_option( 'stag_custom_sidebars' );
		$custom_sidebars = (array) $data[ $key ];

		unset( $data[ $key ] );

		// Loop through each imported custom sidebar area and prepare it
		// to be added in new custom sidebar areas.
		foreach ( $custom_sidebars as $sidebar_id => $title ) {
			if ( ! isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
				$sidebars[ $sidebar_id ] = $title;
			}
		}

		update_option( 'stag_custom_sidebars', $sidebars );

		SCS()->register_custom_sidebars();

		return $data;
	}

	/**
	 * Tweak style for Widget customizer.
	 *
	 * @since 1.0.7.
	 * @return void
	 */
	function customize_controls_print_scripts() {

		if ( false === ( $sidebars = get_option( 'stag_custom_sidebars' ) ) ) return;

		// Get custom sidebar keys
		$sidebars = array_keys( $sidebars );

		if ( ! is_array( $sidebars ) ) return;

		echo "<style type='text/css'>\n";
		foreach ( $sidebars as $sidebar_id ) :
			echo "#accordion-section-sidebar-widgets-{$sidebar_id} { display: list-item !important; height: auto !important; }\n";
			echo "#accordion-section-sidebar-widgets-{$sidebar_id} .widget-top { opacity: 1 !important; }\n";
		endforeach;
		echo "</style>\n";
	}
}

/**
 * Returns the main instance of SCS to prevent the need to use globals.
 *
 * @since  1.0.6
 * @return Stag_Custom_Sidebars
 */
function SCS() {
	return Stag_Custom_Sidebars::instance();
}

// Global for backwards compatibility.
$GLOBALS['stag_custom_sidebars'] = SCS();
