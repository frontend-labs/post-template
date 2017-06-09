<?php
/**
 * Ink theme customizer settings.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

function stag_customize_register_sections( $wp_customize ) {
	$wp_customize->add_section( 'general_settings', array(
		'title'       => _x( 'General Settings', 'Theme customizer section title', 'stag' ),
		'priority'    => 30,
		'description' => __( 'Upload site logo, favicon, and Google Analytics tracking code.', 'stag' )
	) );

	$wp_customize->add_section( 'layout_options', array(
		'title'       => _x( 'Styling Options', 'Theme customizer section title', 'stag' ),
		'priority'    => 40,
	) );

	$wp_customize->add_section( 'typography', array(
		'title'       => _x( 'Typography Options', 'Theme customizer section title', 'stag' ),
		'priority'    => 60,
	) );

	$wp_customize->add_section( 'post_settings', array(
		'title'       => _x( 'Post Settings', 'Theme customizer section title', 'stag' ),
		'priority'    => 100
	) );

	$wp_customize->add_section( 'stag_footer', array(
		'title'      => _x( 'Footer', 'Theme customizer section title', 'stag' ),
		'priority'   => 900
	) );

	$wp_customize->add_section( '404_page', array(
		'title'       => _x( '404 Error Page', 'Theme customizer section title', 'stag' ),
		'priority'    => 910,
		'description' => __( 'Select a custom 404 page.', 'stag' )
	) );
}
add_action( 'customize_register', 'stag_customize_register_sections' );

/**
 * Default theme customizations.
 *
 * @return $options an array of default theme options
 */
function stag_get_theme_mods( $args = array() ) {
	$defaults = array(
		'keys_only' => false
	);

	$args = wp_parse_args( $args, $defaults );

	$fonts = stag_all_font_choices();

	$mods = array(
		'general_settings' => array(
			'favicon' => array(
				'title'   => __( 'Favicon', 'stag' ),
				'type'    => 'WP_Customize_Image_Control',
				'default' => null,
				'priority' => 30
			),
			'contact_email' => array(
				'title'   => __( 'Contact Form Email Address', 'stag' ),
				'type'    => 'text',
				'default' => null,
				'priority' => 90
			),
			'google_analytics' => array(
				'title'   => __( 'Google Analytics Tracking Code', 'stag' ),
				'type'    => 'Stag_Customize_Textarea_Control',
				'default' => null,
				'priority' => 100
			)
		),
		'colors' => array(
			'background' => array(
				'title'   => __( 'Background Color', 'stag' ),
				'type'    => 'WP_Customize_Color_Control',
				'default' => '#ffffff'
			),
			'accent' => array(
				'title'   => __( 'Accent Color', 'stag' ),
				'type'    => 'WP_Customize_Color_Control',
				'default' => '#f0ad2c'
			)
		),
		'layout_options' => array(
			'layout' => array(
				'title'   => __( 'Homepage Layout', 'stag' ),
				'type'    => 'Stag_Customizer_Layout_Control',
				'default' => '1-2-1-2',
				'choices' => array(
					'1-2-1-2' => '1-2-1-2',
					'1-1-1-1' => '1-1-1-1',
					'1-2-2-2' => '1-2-2-2',
					'2-2-2-2' => '2-2-2-2'
				)
			),
			'custom_css' => array(
				'title'     => __( 'Custom CSS', 'stag' ),
				'type'      => 'Stag_Customize_Textarea_Control',
				'default'   => null,
				'transport' => 'refresh'
			)
		),
		'typography' => array(
			'body_font' => array(
				'title'     => __( 'Body Font', 'stag' ),
				'type'      => 'select',
				'default'   => 'Roboto Slab',
				'transport' => 'refresh',
				'choices'   => $fonts
			),
			'header_font' => array(
				'title'     => __( 'Header Font', 'stag' ),
				'type'      => 'select',
				'default'   => 'Montserrat',
				'transport' => 'refresh',
				'choices'   => $fonts
			),
			'subset' => array(
				'title'    => __( 'Character Subset', 'stag' ),
				'type'     => 'select',
				'default'  => 'latin',
				'choices'  => stag_get_google_font_subsets(),
			),
		),
		'post_settings' => array(
			'share_buttons' => array(
				'title'     => __( 'Disable Sharing Buttons', 'stag' ),
				'type'      => 'checkbox',
				'default'   => 0,
				'transport' => 'refresh'
			),
			'post_categories' => array(
				'title'     => __( 'Include Post Category at Post Cover&rsquo;s meta', 'stag' ),
				'type'      => 'checkbox',
				'default'   => 0,
				'transport' => 'refresh'
			),
			'hide_author_title' => array(
				'title'   => __( 'Hide Author Title under Posts', 'stag' ),
				'type'    => 'checkbox',
				'default' => 0
			),
			'show_related_posts' => array(
				'title'   => __( 'Show Related Posts on Single Posts', 'stag' ),
				'type'    => 'checkbox',
				'default' => 0
			),
			'related_posts_count' => array(
				'title'   => __( 'Number of Related posts to show', 'stag' ),
				'type'    => 'text',
				'default' => '2'
			),
			'show_excerpt' => array(
				'title'     => __( 'Show Post Excerpt on Archive pages', 'stag' ),
				'type'      => 'checkbox',
				'default'   => 0,
				'transport' => 'refresh'
			),
			'excerpt_length' => array(
				'title'     => __( 'Post Excerpt Length (in words)', 'stag' ),
				'type'      => 'text',
				'default'   => '25',
				'transport' => 'refresh'
			),
		),
		'stag_footer' => array(
			'copyright' => array(
				'title'   => __( 'Copyright Text', 'stag' ),
				'type'    => 'Stag_Customize_Textarea_Control',
				'default' => sprintf( 'Copyright &copy; %d — %s', date('Y'), '<a href="http://frontendlabs.io/author/jansanchez", title="Jan Sanchez" target="_blank">Jan Sanchez</a>' )
			)
		),
		'404_page' => array(
			'404_custom_page' => array(
				'title'   => __( 'Custom 404 Page', 'stag' ),
				'type'    => 'dropdown-pages',
				'default' => '0'
			)
		)
	);

	$mods = apply_filters( 'stag_theme_mods', $mods );

	/** Return all keys within all sections (for transport, etc) */
	if ( $args[ 'keys_only' ] ) {
		$transport = array();

		foreach ( $mods as $section => $settings ) {
			foreach( $settings as $key => $setting ) {
				if ( isset( $setting['transport'] ) ) {
					$transport[$key] = $setting['transport'];
				} else {
					$transport[$key] = '';
				}
			}
		}

		return $transport;
	}

	return $mods;
}

/**
 * Output the basic extra CSS for primary and accent colors.
 * Split away from widget colors for brevity.
 *
 * @return void
 */
function stag_header_css() {
	?>
	<style id="stag-custom-css" type="text/css">
		body,
		.site,
		hr:not(.stag-divider)::before {
			background-color: <?php echo stag_theme_mod( 'colors', 'background' ); ?>;
		}
		body, .entry-subtitle {
			font-family: "<?php echo stag_theme_mod( 'typography', 'body_font' ); ?>";
		}
		a,
		.archive-header__title span,
		.footer-menu a:hover {
			color: <?php echo stag_theme_mod( 'colors', 'accent' ); ?>;
		}
		h1, h2, h3, h4, h5, h6, .button, .stag-button, input[type="submit"], input[type="reset"], .button-secondary, legend, .rcp_subscription_level_name {
			font-family: "<?php echo stag_theme_mod( 'typography', 'header_font' ); ?>";
		}
		.post-grid {
			border-color: <?php echo stag_theme_mod( 'colors', 'background' ); ?>;
		}
		<?php echo stag_theme_mod( 'layout_options', 'custom_css' ); ?>
	</style>
	<?php
}
add_action( 'wp_head', 'stag_header_css' );


/**
 * Layout Picker Control
 *
 * Attach the custom layout picker control to the `customize_register` action
 * so the WP_Customize_Control class is initiated.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function stag_customizer_layout_control( $wp_customize ) {
	class Stag_Customizer_Layout_Control extends WP_Customize_Control {
		public $type = 'layout';

		public function render_content() {

			$img_dir = get_template_directory_uri() . '/assets/img/';
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<ul>
			<?php

			foreach ( $this->choices as $key => $value ) {
				?>
				<li class="customizer-control-row">
					<input type="radio" value="<?php echo esc_attr( $key ) ?>" name="<?php echo $this->id; ?>" <?php echo $this->link(); ?> <?php if( $this->value() === $key ) echo 'checked="checked"'; ?>>
					<label for="<?php echo $this->id;  ?>[key]"><?php echo $value; ?></label>
				</li>
				<?php
			}

			?> </ul> <?php
		}
	}
}
add_action( 'customize_register', 'stag_customizer_layout_control', 1, 1 );

function stag_customizer_layout_css() {
	wp_register_script('customizer-ui-js', get_template_directory_uri() . '/assets/js/customizer-ui.js', 'jquery');
	wp_enqueue_script('customizer-ui-js');
	?>
	<style type="text/css">
		.customizer-control-row {
			position: relative;
			display: inline-block;
			vertical-align: top;
			width: 125px;
			height: 75px;
			overflow: hidden;
			margin: 0 0 7px 0;
			font: 0/0 a;
		}
		.customizer-control-row:nth-child(2n+2) {
			margin-left: 4px;
		}

		.customizer-control-row:nth-child(1) label { background-position: 0 0; }
		.customizer-control-row:nth-child(2) label { background-position: -268px 0; }
		.customizer-control-row:nth-child(3) label { background-position: -133px 0; }
		.customizer-control-row:nth-child(4) label { background-position: -399px 0; }

		.customizer-control-row input {
			position: absolute;
			width: 100%;
			height: 100%;
			opacity: 0;
		}
		.customizer-control-row input,
		.customizer-control-row label {
			width: 100%;
		}
		.customizer-control-row label {
			height: 75px;
			background: url("<?php echo get_template_directory_uri(); ?>/assets/img/layout-images.png") no-repeat;
			background-size: 521px 73px;
			display: block;
			box-sizing: border-box;
		}

		.customizer-control-row input[type="radio"]:checked + label {
			background-image: url("<?php echo get_template_directory_uri(); ?>/assets/img/layout-images-gray.png");
		}

		@media
		only screen and (-webkit-min-device-pixel-ratio: 2),
		only screen and (   min--moz-device-pixel-ratio: 2),
		only screen and (     -o-min-device-pixel-ratio: 2/1),
		only screen and (        min-device-pixel-ratio: 2),
		only screen and (                min-resolution: 192dpi),
		only screen and (                min-resolution: 2dppx) {
			.customizer-control-row label {
				background-image: url("<?php echo get_template_directory_uri(); ?>/assets/img/layout-images-2x.png");
			}
			.customizer-control-row input[type="radio"]:checked + label {
				background-image: url("<?php echo get_template_directory_uri(); ?>/assets/img/layout-images-gray-2x.png");
			}
		}

		#stag-loading {
			background: #333;
			padding: 10px 0;
			/*border-radius: 5px;*/
			color: white;
			width: 40px;
			position: absolute;
			top: 30px;
			right: 30px;
			text-align: center;
		}

		#stag-loading i {
			display: inline-block;
			-webkit-animation: rotate 400ms linear 0s infinite alternate;
			-moz-animation: rotate 400ms linear 0s infinite alternate;
			-ms-animation: rotate 400ms linear 0s infinite alternate;
			animation: rotate 400ms linear 0s infinite alternate;
		}

		@-webkit-keyframes rotate { 0% { -webkit-transform: rotate(0deg); } 100% { -webkit-transform: rotate(180deg); } }
		@-moz-keyframes rotate { 0% { -moz-transform: rotate(0deg); } 100% { -moz-transform: rotate(180deg); } }
		@keyframes rotate { 0% { transform: rotate(0deg); } 100% { transform: rotate(180deg); } }

		.accordion-section .customize-control-image .preview-thumbnail img {
			width: auto;
			height: auto;
		}
	</style>
	<?php
}
add_action( 'customize_controls_print_scripts', 'stag_customizer_layout_css' );
