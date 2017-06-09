<?php
/**
 * Restrict Content Pro related functions.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 *
 * @since 1.0
 */

/**
 * Checks if Restrict Content Pro is active.
 *
 * @return bool
 */
function stag_is_rcp_active() {
	return is_plugin_active( 'restrict-content-pro/restrict-content-pro.php' );
}

/**
 * Check if current post is locked and if user has access to current content.
 *
 * @return bool
 */
function stag_rcp_user_has_no_access() {

	if ( ! stag_is_rcp_active() ) return false;

	global $post, $user_ID, $rcp_options;
	$access_level = get_post_meta( $post->ID, 'rcp_access_level', true );

	if ( rcp_is_paid_content( $post->ID ) ) {
		if ( ! rcp_is_paid_user( $user_ID ) || ( !rcp_user_has_access( $user_ID, $access_level ) && $access_level > 0 ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Display Subscribe/Login button on restricted pages.
 *
 * @return void
 */
function stag_rcp_locked_options( $atts ) {
	if ( ! stag_is_rcp_active() ) return;

	$args = shortcode_atts( array(
		'button' => '',
	), $atts, 'locked_options' );

	global $user_ID, $rcp_options;

	$is_displayed      = $show_login_button = false;
	$registration_page = $rcp_options['registration_page'];

	if ( ! is_user_logged_in() ) {
		$show_login_button = true;
	}

	$has_access       = ( ! rcp_is_paid_user($user_ID) && isset( $registration_page ) && $registration_page != '' ) ? true : false;

	if ( isset( $args['button'] ) && $args['button'] != '' ) {
		$button_color = $args['button'];
	} else {
		$background_color = stag_get_post_meta( 'settings', get_the_ID(), 'post-background-color' );
		$button_color     = ( $background_color === '' && !has_post_thumbnail() ) ? 'black' : 'white';

		if ( is_single() ) $button_color = 'black';
	}

	ob_start();

	?>

	<div class="locked-options<?php echo ( $has_access ) ? ' no-access' : ' has-access'; ?> <?php echo $button_color ?>-buttons">
		<?php if ( $has_access ) : ?>
			<a href="<?php echo get_permalink($registration_page); ?>" class="stag-button stag-button--stroke stag-button--<?php echo esc_attr( $button_color ); ?>"><?php _e( 'Subscribe', 'stag' ); ?></a>
			<?php $is_displayed = true; ?>
		<?php endif; ?>
		<?php if ( $show_login_button ) : ?>
			<?php if ( $is_displayed ) echo '<span class="form-divider"></span>'; ?>
			<a href="<?php echo wp_login_url(); ?>" class="stag-button stag-button--stroke stag-button--<?php echo esc_attr( $button_color ); ?>"><?php _e( 'Login', 'stag' ); ?></a>
		<?php endif; ?>
	</div>

	<?php

	return ob_get_clean();
}
add_shortcode( 'locked_options', 'stag_rcp_locked_options' );

function stag_rcp_error_before() {
	echo '<div class="stag-alert stag-alert--red">';
}
add_action( 'rcp_error_before', 'stag_rcp_error_before', 1 );

function stag_rcp_error_after() {
	echo '</div>';
}
add_action( 'rcp_error_after', 'stag_rcp_error_after', 1 );

function stag_rcp_registration_title() {
	return _e( 'Don&rsquo;t have an account? Subscribe here!', 'stag' );
}
add_filter( 'rcp_registration_header_logged_in', 'stag_rcp_registration_title' );
