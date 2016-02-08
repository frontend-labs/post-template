<?php
/**
 * Post sharing buttons.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

// Disable subtitles for titles
add_filter( 'subtitle_view_supported', '__return_false' );

$message = apply_filters( 'ink_share_message', sprintf( _x( '%1$s on %2$s %3$s', '1: Article title 2: Site Name 3: Site URL', 'stag' ), get_the_title(), get_bloginfo( 'name' ), get_permalink() ) );

?>

<div class="post-share-buttons">
	<h5><?php _e( 'Share on', 'stag' ); ?></h5>

	<?php do_action( 'ink_share_before', $message ); ?>
	<a target="_blank" class="button" href="<?php echo esc_url( sprintf( 'http://www.twitter.com?status=%s', urlencode( $message ) ) ); ?>"><?php _e( 'Twitter', 'stag' ); ?></a>
	<a target="_blank" class="button" href="<?php echo esc_url( sprintf( 'http://www.facebook.com/sharer.php?u=%s', urlencode( get_permalink() ) ) ); ?>"><?php _e( 'Facebook', 'stag' ); ?></a>
	<?php do_action( 'ink_share_after', $message ); ?>
</div>
