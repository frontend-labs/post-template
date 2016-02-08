<?php
/**
 * Theme related functions.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

// Set the theme's content width.
if ( ! isset( $content_width ) ) {
	$content_width = 975;
}

// Set Retina Cookie
$GLOBALS['is_retina'] = ( isset($_COOKIE['retina']) ) ? true : false;

if ( ! function_exists( 'stag_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 */
function stag_theme_setup() {

	/*
	 * Makes theme available for translation.
	 *
	 * Attempt to load text domain from child theme first.
	 * Translations can be added to the /languages/ directory.
	 */
	if ( ! load_theme_textdomain( 'stag', get_stylesheet_directory() . '/languages' ) ) {
		load_theme_textdomain( 'stag', get_template_directory() . '/languages' );
	}

	// This theme uses wp_nav_menu() in following locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'stag' ),
		'footer'  => __( 'Footer Menu', 'stag' )
	) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', 'gallery', 'caption' ) );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// Add the theme's editor style
	add_editor_style( 'assets/css/editor-style.css' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );
}
endif; // stag_theme_setup
add_action( 'after_setup_theme', 'stag_theme_setup' );

/**
* Register widget areas and widgets.
*
* @since 1.0
*/
function stag_sidebar_init() {

	// Register widget areas
	register_sidebar(array(
		'name'          => __( 'Footer Widget Area', 'stag' ),
		'id'            => 'sidebar-footer',
		'before_widget' => '<aside id="%1$s" class="widget unit one-of-two %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
		'description'   => __( 'Sitewide footer widgets.', 'stag' )
	));

	register_sidebar(array(
		'name'          => __( 'Side Drawer Widget Area', 'stag' ),
		'id'            => 'sidebar-drawer',
		'before_widget' => '<aside id="%1$s" class="site-nav__section %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
		'description'   => __( 'Sidebar drawer navigation widgets.', 'stag' )
	));
}
add_action( 'widgets_init', 'stag_sidebar_init' );

/**
* Enqueues scripts and styles for front end.
*/
function stag_scripts_styles() {

	$style_dependencies = array();

	// Google fonts
	if ( '' !== $google_request = stag_get_google_font_uri() ) {
		// Enqueue the fonts
		wp_enqueue_style(
			'stag-google-fonts',
			$google_request,
			$style_dependencies,
			STAG_THEME_VERSION
		);
		$style_dependencies[] = 'stag-google-fonts';
	}

	// Main stylesheet
	wp_enqueue_style( 'stag-style', get_stylesheet_uri(), $style_dependencies, STAG_THEME_VERSION );

	/**
	 * Remove default Subtitles plugin styles.
	 *
	 * @link https://wordpress.org/plugins/subtitles/
	 * @since 1.1.0
	 */
	wp_dequeue_style( 'subtitles-style' );

	wp_deregister_style( 'font-awesome' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri()  . '/assets/css/font-awesome.css' , '', '4.1.0', 'all' );

	wp_register_script( 'stag-custom', get_template_directory_uri().'/assets/js/jquery.custom' . STAG_SCRIPT_SUFFIX . '.js', array( 'jquery' ), STAG_THEME_VERSION, true );
	wp_register_script( 'stag-plugins', get_template_directory_uri().'/assets/js/plugins.js', array( 'jquery', 'stag-custom' ), STAG_THEME_VERSION, true );
	wp_register_script( 'fitvids', get_template_directory_uri().'/assets/js/lib/fitvids/jquery.fitvids' . STAG_SCRIPT_SUFFIX . '.js', array( 'jquery' ), '1.1.1', true );

	// Default fitvids selectors
	$selector_array = array(
		"iframe[src*='www.viddler.com']",
		"iframe[src*='money.cnn.com']",
		"iframe[src*='www.educreations.com']",
		"iframe[src*='//blip.tv']",
		"iframe[src*='//embed.ted.com']",
		"iframe[src*='//www.hulu.com']",
	);

	// Allow dev to customize the selectors
	$fitvids_custom_selectors = apply_filters( 'stag_fitvids_custom_selectors', $selector_array );

	// Compile selectors
	$fitvids_custom_selectors = array(
		'customSelector' => implode( ',', $fitvids_custom_selectors )
	);

	// Send to the script
	wp_localize_script(
		'stag-custom',
		'StagFitvidsCustomSelectors',
		$fitvids_custom_selectors
	);

	// Send to the script
	wp_localize_script(
		'stag-custom',
		'postSettings',
		array(
			'ajaxurl'  => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('stag-ajax'),
			'category' => get_query_var( 'cat' ),
			'search'   => get_query_var( 's' ),
		)
	);

	// Enqueue Scripts
	wp_enqueue_script( 'stag-plugins' );

	if( is_singular() ) {
		wp_enqueue_script( 'fitvids' );
	}

	// Comment reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' ); // loads the javascript required for threaded comments
	}
}
add_action( 'wp_enqueue_scripts', 'stag_scripts_styles' );

function stag_maybe_enqueue_spin() {
	// Register spinner scripts
	wp_enqueue_script(
		'spin',
		get_template_directory_uri() . '/assets/js/lib/spin/spin' . STAG_SCRIPT_SUFFIX . '.js',
		array(),
		'1.3'
	);

	wp_enqueue_script(
		'jquery.spin',
		get_template_directory_uri() . '/assets/js/lib/spin/jquery.spin' . STAG_SCRIPT_SUFFIX . '.js',
		array( 'jquery', 'spin' ),
		'1.3'
	);
}
add_action( 'wp_enqueue_scripts', 'stag_maybe_enqueue_spin' );

if ( ! function_exists( 'stag_early_head_times' ) ) :
/**
 * Items to add before other scripts and styles load in the head.
 *
 * @since 1.0
 */
function stag_early_head_times() {
	?>
	<link rel="dns-prefetch" href="//fonts.googleapis.com">
	<?php
}
endif;

add_action( 'wp_head', 'stag_early_head_times', 1 );

/**
 * Register the required plugins for this theme.
 *
 * @since 1.0.0
 *
 * @return void
 */
function stag_required_plugins() {
	$plugins = array(
		array(
			'name'     => 'StagTools',
			'slug'     => 'stagtools',
			'required' => true
		),
		array(
			'name'     => 'Stag Custom Sidebars',
			'slug'     => 'stag-custom-sidebars',
			'required' => true
		),
		array(
			'name'     => 'Jetpack',
			'slug'     => 'jetpack',
			'required' => false
		),
		array(
			'name'         => 'Stag Envato Updater',
			'slug'         => 'stag-envato-updater',
			'source'       => 'https://github.com/Codestag/stag-envato-updater/releases/download/v1.0/stag-envato-updater.zip',
			'required'     => false,
			'external_url' => 'https://github.com/Codestag/stag-envato-updater/',
		)
	);

	tgmpa( $plugins );
}
add_action( 'tgmpa_register', 'stag_required_plugins' );

$tmp_dir = get_template_directory();

/**
 * Include Stag_Customizer class.
 */
include_once ( $tmp_dir . '/stag-customizer/stag-customizer.php' );

/**
 * Include theme partials: widgets, metaboxes and rest.
 */
include_once ( $tmp_dir . '/inc/init.php' );

/**
 * Include Restrict Content Pro related files.
 *
 * @link http://pippinsplugins.com/restrict-content-pro-premium-content-plugin/
 */
include_once ( $tmp_dir . '/rcp/init.php' );
