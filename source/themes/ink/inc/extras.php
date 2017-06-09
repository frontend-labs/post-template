<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

/**
* Filters wp_title to print a neat <title> tag based on what is being viewed.
*
* @param string $title Default title text for current view.
* @param string $sep Optional separator.
* @return string The filtered title.
*/
function stag_wp_title( $title, $sep ) {
	if ( function_exists( 'stag_check_third_party_seo' ) && ! stag_check_third_party_seo() ) {
		global $page, $paged;

		if ( is_feed() ) {
			return $title;
		}
		$title .= get_bloginfo( 'name' );

		$site_desc = get_bloginfo( 'description', 'display' );
		if( $site_desc && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_desc";
		}

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'stag' ), max( $paged, $page ) );
		}
	}
	return $title;
}
add_filter( 'wp_title', 'stag_wp_title', 10, 2 );

/**
 * Check if there is any third party plugin active.
 *
 * @return boolean Returns true if popular SEO plugins are active or false.
 */
function stag_check_third_party_seo() {
	include_once( ABSPATH .'wp-admin/includes/plugin.php' );

	if( is_plugin_active('headspace2/headspace.php') ) return true;
	if( is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php') ) return true;
	if( is_plugin_active('wordpres-seo/wp-seo.php') ) return true;

	return false;
}

if ( ! function_exists( 'stag_allowed_tags' ) ) :
/**
 * Allow only the allowedtags array in a string.
 *
 * @since  1.0.
 *
 * @param  string    $string    The unsanitized string.
 * @return string               The sanitized string.
 */
function stag_allowed_tags( $string ) {
	global $allowedtags;
	return wp_kses( $string , $allowedtags );
}
endif;

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function stag_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'stag_setup_author' );

if ( ! function_exists( 'stag_sanitize_hex_color' ) ) :
/**
 * Validates a hex color.
 *
 * Returns either '', a 3 or 6 digit hex color (with #), or null.
 * For validating values without a #, see sanitize_hex_color_no_hash().
 *
 * @since  1.0.
 *
 * @param  string         $color    Hexidecimal value to sanitize.
 * @return string|null              Sanitized value.
 */
function stag_sanitize_hex_color( $color ) {
	if ( '' === $color )
		return '';

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
		return $color;

	return null;
}
endif;

if ( ! function_exists( 'stag_sanitize_hex_color_no_hash' ) ) :
/**
 * Sanitizes a hex color without a hash. Use stag_sanitize_hex_color() when possible.
 *
 * Saving hex colors without a hash puts the burden of adding the hash on the
 * UI, which makes it difficult to use or upgrade to other color types such as
 * rgba, hsl, rgb, and html color names.
 *
 * Returns either '', a 3 or 6 digit hex color (without a #), or null.
 *
 * @since  1.0.
 *
 * @param  string         $color    Hexidecimal value to sanitize.
 * @return string|null              Sanitized value.
 */
function stag_sanitize_hex_color_no_hash( $color ) {
	$color = ltrim( $color, '#' );

	if ( '' === $color )
		return '';

	return stag_sanitize_hex_color( '#' . $color ) ? $color : null;
}
endif;

if ( ! function_exists( 'stag_maybe_hash_hex_color' ) ) :
/**
 * Ensures that any hex color is properly hashed.
 * Otherwise, returns value untouched.
 *
 * This method should only be necessary if using sanitize_hex_color_no_hash().
 *
 * @since  1.0.
 *
 * @param  string    $color    Hexidecimal value to sanitize.
 * @return string              Sanitized value.
 */
function stag_maybe_hash_hex_color( $color ) {
	if ( $unhashed = stag_sanitize_hex_color_no_hash( $color ) )
		return '#' . $unhashed;

	return $color;
}
endif;

if ( ! function_exists( 'stag_jpg_quality' ) ) :
/**
 * Filter: Increase the quality of processed images.
 *
 * The default is 90. This sets it to the max of 100.
 *
 * @since 1.0.
 *
 * @return int
 */
function stag_jpg_quality() {
	return 100;
}
endif;

add_filter( 'wp_editor_set_quality', 'stag_jpg_quality' );
add_filter( 'jpeg_quality', 'stag_jpg_quality' );

if ( ! function_exists( 'stag_excerpt_length' ) ) :
/**
 * Set the excerpt length
 *
 * @since 1.0.
 *
 * @param  int $length The excerpt length
 * @return int         The modified excerpt length
 */
function stag_excerpt_length( $length ) {
	global $post;

	$length = absint( stag_theme_mod( 'post_settings', 'excerpt_length' ) );

	return $length;
}
endif;

add_filter( 'excerpt_length', 'stag_excerpt_length' );

if ( ! function_exists( 'stag_excerpt_more' ) ) :
/**
 * Filter: Modify the suffix of a truncated excerpt.
 *
 * @since 1.0.
 *
 * @return string
 */
function stag_excerpt_more() {
	return _x( '&hellip;', 'end marker for truncated content', 'stag' );
}
endif;

add_filter( 'excerpt_more', 'stag_excerpt_more' );
