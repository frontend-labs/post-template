<?php
/**
 * Template for displaying post specific post cover, background color and images.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

$id = get_the_ID();

$thumb_id         = get_post_thumbnail_id( $id );
$background_image = '';

if ( '' != $thumb_id ) {
	$thumb_url          = wp_get_attachment_image_src( $thumb_id, 'full', true );
	$background_image   = $thumb_url[0];
}

$background_color   = stag_get_post_meta( 'settings', $id, 'post-background-color' );
$background_opacity = stag_get_post_meta( 'settings', $id, 'post-background-opacity' );
$background_filter  = stag_get_post_meta( 'settings', $id, 'post-background-filter' );

if ( ! $background_filter ) $background_filter = 'none';

// Set a default background opacity
if ( empty($background_opacity) ) $background_opacity = 40;

?>

	<style type="text/css">
		<?php if( ! empty( $background_color ) ) : ?>
		.post-<?php echo $id; ?> { background-color: <?php echo stag_maybe_hash_hex_color($background_color); ?> !important; }
		<?php endif; ?>

		<?php if( ! empty( $background_image ) ) : ?>
		.post-cover-<?php echo $id; ?> { background-image: url(<?php echo esc_url( $background_image ); ?>); opacity: <?php echo absint($background_opacity)/100; ?>; }
		<?php endif; ?>
	</style>
<div class="post-cover post-cover-<?php echo $id; ?> stag-image--<?php echo esc_attr( $background_filter ); ?>"></div>
