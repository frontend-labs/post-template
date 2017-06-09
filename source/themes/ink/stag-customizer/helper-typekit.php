<?php
/**
 * @package Stag_Customizer
 */
if ( ! class_exists( 'Stag_Typekit_Customizer' ) ) :
/**
 * Setup the customizer to handle Typekit fonts.
 *
 * @since 1.0.0.
 */
class Stag_Typekit_Customizer {
	/**
	 * The Typekit ID before save.
	 *
	 * @since 1.0.0.
	 *
	 * @var   string    The Typekit ID before save.
	 */
	var $typekit_id_before_save;

	/**
	 * The one instance of Stag_Typekit_Customizer.
	 *
	 * @since 1.0.0.
	 *
	 * @var   Stag_Typekit_Customizer
	 */
	private static $instance;

	/**
	 * Section name under which to add the settings.
	 *
	 * @var string
	 */
	var $section_name = 'typography';

	/**
	 * Instantiate or return the one Stag_Typekit_Customizer instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return Stag_Typekit_Customizer
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Create a new section.
	 *
	 * @since  1.0.0.
	 *
	 * @return Stag_Typekit_Customizer
	 */
	public function __construct() {
		// Add the sections
		add_action( 'customize_register', array( $this, 'customize_register' ), 20 );

		// Add scripts and styles
		add_action( 'wp_head', array( $this, 'print_typekit' ), 0 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );
		add_action( 'customize_controls_print_styles', array( $this, 'customize_controls_print_styles' ) );

		// AJAX handler
		add_action( 'wp_ajax_stag_get_typekit_fonts', array( $this, 'get_typekit_fonts' ) );

		// Filter the available font choices
		add_filter( 'stag_all_fonts', array( $this, 'all_fonts' ) );

		// Handle saving extra options based on what the customizer saved
		add_action( 'customize_save', array( $this, 'customize_save' ) );
		add_action( 'customize_save_after', array( $this, 'customize_save_after' ) );
	}

	/**
	 * Add the Typekit ID input.
	 *
	 * @since  1.0.0.
	 *
	 * @param  WP_Customize_Manager    $wp_customize    Theme Customizer object.
	 * @return void
	 */
	public function customize_register( $wp_customize ) {

		$section = apply_filters( 'stag_typekit_settings_section', $this->section_name );

		// Site title font size
		$wp_customize->add_setting(
			'typekit-id',
			array(
				'default'           => '',
				'type'              => 'theme_mod',
				'sanitize_callback' => array( $this, 'sanitize_typekit_id' ),
			)
		);

		$wp_customize->add_control(
			'stag-typekit-id',
			array(
				'settings' => 'typekit-id',
				'section'  => $section,
				'label'    => __( 'Typekit Kit ID', 'stag' ),
				'type'     => 'text',
				'priority' => 180
			)
		);

		$wp_customize->add_control(
			new Stag_Customize_Misc_Control(
				$wp_customize,
				'stag-typekit-load-fonts',
				array(
					'section'     => $section,
					'type'        => 'text',
					'description' => '<a href="#">' . __( 'Reset', 'stag' ) . '</a><a href="#">' . __( 'Load Typekit Fonts', 'stag' ) . '</a>',
					'priority'    => 181
				)
			)
		);

		$wp_customize->add_control(
			new Stag_Customize_Misc_Control(
				$wp_customize,
				'stag-typekit-documentation',
				array(
					'section'     => $section,
					'type'        => 'text',
					'description' => sprintf( __( 'For more information about Typekit integration, please see <a target="_blank" href="%1$s">%2$s\' documentation</a>.', 'stag' ), '//docs.codestag.com/'. sanitize_title_with_dashes( STAG_THEME_NAME ) .'/#typekit-integration', STAG_THEME_NAME ),
					'priority'    => 182
				)
			)
		);
	}

	/**
	 * Maybe enqueue Typekit styles.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function print_typekit() {
		$id = $this->get_typekit_id();

		if ( '' !== $id && true === $this->is_typekit_used() ) : ?>
			<link rel="dns-prefetch" href="//use.typekit.net">
			<script type="text/javascript" src="//use.typekit.net/<?php echo $this->sanitize_typekit_id( $id ); ?>.js"></script>
			<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
		<?php endif;
	}

	/**
	 * Determine if a single Typekit font is used.
	 *
	 * @since  1.0.0.
	 *
	 * @return bool    True if a Typekit font is used; False if no Typekit fonts are used.
	 */
	public function is_typekit_used() {
		// Grab the font choices
		$fonts = array(
			stag_theme_mod( 'typography', 'header_font' ),
			stag_theme_mod( 'typography', 'body_font' )
		);

		// De-dupe the fonts
		$fonts         = array_unique( $fonts );
		$allowed_fonts = $this->get_typekit_choices();

		foreach ( $fonts as $key => $font ) {
			if ( isset( $allowed_fonts[ $font ] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the list of Typekit fonts in the current Typekit Kit.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    Array of Typekit fonts available to the kit.
	 */
	public function get_typekit_choices() {
		return ( '' !== get_theme_mod( 'typekit-temp-choices', '' ) ) ? get_theme_mod( 'typekit-temp-choices', array() ) : get_theme_mod( 'typekit-choices', array() );
	}

	/**
	 * Get the list of Typekit ID for the current Typekit Kit.
	 *
	 * @since  1.0.0.
	 *
	 * @return string    ID for the current Typekit Kit.
	 */
	public function get_typekit_id() {
		return ( '' !== get_theme_mod( 'typekit-temp-id', '' ) ) ? get_theme_mod( 'typekit-temp-id', array() ) : get_theme_mod( 'typekit-id', '' );
	}

	/**
	 * Add scripts to the customizer.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function customize_controls_enqueue_scripts() {
		wp_enqueue_script(
			'stag-typekit-customizer',
			get_template_directory_uri(). '/stag-customizer/js/customizer-typekit.js',
			array(
				'jquery',
				'customize-controls',
				'wp-util',
			),
			STAG_THEME_VERSION,
			true
		);

		$typekit_choices = get_theme_mod( 'typekit-choices', array() );

		wp_localize_script(
			'stag-typekit-customizer',
			'StagTypekitData',
			array(
				'nonce'          => wp_create_nonce( 'stag-typekit-request' ),
				'headerLabel'    => __( 'Typekit Fonts', 'stag' ),
				'noInputError'   => __( 'Please enter your Typekit Kit ID', 'stag' ),
				'ajaxError'      => __( 'Typekit fonts could not be found. Please try again', 'stag' ),
				'ajaxSuccess'    => __( 'Typekit fonts added, select them from dropdown above.', 'stag' ),
				'typekitChoices' => ( ! empty( $typekit_choices ) ) ? array_keys( $typekit_choices ) : array(),
				'headerFont'     => stag_theme_mod( 'typography', 'header_font', true ),
				'bodyFont'       => stag_theme_mod( 'typography', 'body_font', true )
			)
		);
	}

	/**
	 * Add styles for the Typekit customizer controls.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function customize_controls_print_styles() {
	?>
		<style type="text/css">
			#customize-control-stag-typekit-load-fonts .error {
				color: red !important;
				margin-bottom: 10px;
				display: block;
			}
			#customize-control-stag-typekit-load-fonts .success {
				color: green !important;
				margin-bottom: 10px;
				display: block;
			}
			#customize-control-stag-typekit-load-fonts .description {
				margin-top: 10px;
			}
			#customize-control-stag-typekit-load-fonts .button {
				font-style: normal;
			}
			#customize-control-stag-typekit-load-fonts .load-fonts {
				margin-left: 5px;
			}
			#customize-control-stag-typekit-load-fonts .spinner {
				display: inline-block;
				margin-top: 4px;
				vertical-align: middle;
			}
		</style>
	<?php
	}

	/**
	 * Append the Typekit fonts to the array of font choices.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $choices    The current font choices.
	 * @return array                The updated font choices.
	 */
	public function all_fonts( $choices ) {
		$typekit_fonts = $this->get_typekit_choices();

		if ( ! empty( $typekit_fonts ) ) {
			$choices = array_merge( $typekit_fonts, $choices );
			$choices = array_merge( array(
				0 => array(
					'label' => sprintf( '&mdash; %s &mdash;', __( 'Typekit Fonts', 'stag' ) )
				)
			), $choices );
		}

		return $choices;
	}

	/**
	 * Callback to handle the AJAX request.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function get_typekit_fonts() {
		// Make sure we have got the data we are expecting.
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$id    = isset( $_POST['id'] ) ? $this->sanitize_typekit_id( $_POST['id'] ) : '';

		if ( wp_verify_nonce( $nonce, 'stag-typekit-request' ) && ! empty( $id ) ) {
			$response      = wp_remote_get( 'https://typekit.com/api/v1/json/kits/' . $id . '/published' );
			$response_code = wp_remote_retrieve_response_code( $response );
			$response_body = json_decode( wp_remote_retrieve_body( $response ) );

			if ( 200 === (int) $response_code && is_object( $response_body ) && isset( $response_body->kit ) && isset( $response_body->kit->families ) && is_array( $response_body->kit->families ) ) {
				$php_options = array();
				$js_options  = array();

				// Package the new select options
				foreach ( $response_body->kit->families as $family ) {
					// This format is needed to plug into the existing fonts
					$php_options[ sanitize_title_with_dashes( $family->slug ) ] = array(
						'label' => wp_strip_all_tags( $family->name ),
						'stack' => ( isset( $family->css_stack ) ) ? wp_strip_all_tags( $family->css_stack ) : '',
					);

					// Key/value pair for JS
					$js_options[ sanitize_title_with_dashes( $family->slug ) ] = wp_strip_all_tags( $family->name );
				}

				// Save the current choices to a theme mod
				set_theme_mod( 'typekit-temp-choices', $php_options );
				set_theme_mod( 'typekit-choices', $php_options );

				// Since we have a successful response, save the Typekit Kit ID
				set_theme_mod( 'typekit-temp-id', $id );

				wp_send_json_success( $js_options );
			} else {
				wp_send_json_error( $response_body );
			}
		} else {

		}
	}

	/**
	 * Denote the value of the Typekit ID before saving a new one.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function customize_save() {
		$this->typekit_id_before_save = get_theme_mod( 'typekit-id' );
	}

	/**
	 * Potentially update the typekit choices and remove temporary choices..
	 *
	 * When the customizer is saved, the temporary values need to be cleaned. The temp choices that are saved
	 * during the AJAX request to Typekit need to be moved to the real choices and the temp choices need to be removed.
	 * Additionally, the temp ID needs to be removed to indicate that the state is no longer preview.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function customize_save_after() {
		$typekit_id_has_changed                      = ( $this->typekit_id_before_save !== get_theme_mod( 'typekit-id' ) );
		$typekit_id_has_not_changed_but_choices_have = ( $this->typekit_id_before_save === get_theme_mod( 'typekit-id' ) && get_theme_mod( 'typekit-choices' ) !== get_theme_mod( 'typekit-temp-choices' ) );

		// If the Typekit ID is empty remove it and the Typekit choices
		if ( '' === get_theme_mod( 'typekit-id' ) ) {
			// Remove options that are no longer needed
			remove_theme_mod( 'typekit-id' );
			remove_theme_mod( 'typekit-choices' );
			remove_theme_mod( 'typekit-temp-choices' );
			remove_theme_mod( 'typekit-temp-id' );
		} else if ( true === $typekit_id_has_changed || true === $typekit_id_has_not_changed_but_choices_have ) {
			// Determine if the Typekit ID has changed
			set_theme_mod( 'typekit-choices', get_theme_mod( 'typekit-temp-choices' ) );
		}

	}

	/**
	 * Ensure that Typekit IDs are [a-z0-9] only.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $value    The dirty ID.
	 * @return string              The clean ID.
	 */
	public function sanitize_typekit_id( $value ) {
		return preg_replace( '/[^0-9a-z]+/', '', $value );
	}
}
endif;


if ( ! function_exists( 'stag_get_typekit_customizer' ) ) :
/**
 * Instantiate or return the one Stag_Typekit_Customizer instance.
 *
 * @since  1.0.0.
 *
 * @return Stag_Typekit_Customizer
 */
function stag_get_typekit_customizer() {
	return Stag_Typekit_Customizer::instance();
}
endif;

stag_get_typekit_customizer();
