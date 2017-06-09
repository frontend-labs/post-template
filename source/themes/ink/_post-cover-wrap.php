<?php
/**
 * Template for displaying post specific post cover, background color and images.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

$post_id         = get_the_ID();

// Get post Categories
$categories_list = get_the_category_list(', ');

// Get Featured Image's caption
$thumb_post      = get_post( get_post_thumbnail_id() );
$caption         = $thumb_post->post_excerpt;

$videos = array(
	'video/mp4'  => stag_get_post_meta( 'settings', $post_id, 'post-video-mp4' ),
	'video/webm' => stag_get_post_meta( 'settings', $post_id, 'post-video-webm' ),
	'video/ogv'  => stag_get_post_meta( 'settings', $post_id, 'post-video-ogv' )
);
$videos            = array_filter( $videos );

// Hide videos if the post is restricted or password protected
$restricted_condition = ( count( $videos ) && ! stag_rcp_user_has_no_access() && ! post_password_required() );

// Get CSS3 background filter option
$background_filter = stag_get_post_meta( 'settings', $post_id, 'post-background-filter' );
if ( ! $background_filter ) $background_filter = 'none';

// Output post cover CSS
stag_post_background_css();

?>

<div class="article-cover article-cover--<?php echo esc_attr( get_the_ID() ); if( $restricted_condition ) echo ' has-video'; ?>">
	<div class="article-cover__background stag-image--<?php echo esc_attr( $background_filter ); ?>"></div>

	<?php if( $restricted_condition ) : ?>
	<video id="background-video" class="background-video" autoplay loop muted>
		<?php foreach ( $videos as $type => $src ) : ?>
		<source src="<?php echo esc_url( $src ); ?>" type="<?php echo esc_attr( $type ); ?>">
		<?php endforeach; ?>
	</video>
	<?php endif; ?>

	<div class="article-cover__inner">
		<div class="article-cover__content">
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php the_title(); ?>

					<?php if ( '' !== ( $subtitle = get_post_meta( get_the_ID(), '_subtitle', true ) ) ) : ?>
					<span class="entry-subtitle custom custom-2"><?php echo $subtitle; ?></span>
					<?php endif; ?>
				</a>
			</h1>


			<footer class="entry-meta">
				<?php stag_posted_on(); ?>
				<?php stag_post_reading_time(); ?>
				<?php edit_post_link( __( 'Edit', 'stag' ), '<span class="edit-link">', '</span>' ); ?>
			</footer>

			<?php if ( stag_theme_mod( 'post_settings', 'post_categories' ) && stag_categorized_blog() && $categories_list ) : ?>
			<div class="entry-categories">
				<?php _e( 'In ', 'stag' ); ?><?php echo $categories_list; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $caption != '' ) : ?>
	<div class="article-cover__caption">
		<p><?php echo esc_html( $caption ); ?></p>
	</div>
	<?php endif; ?>

	<span id="scroll-to-content" class="article-cover__arrow">
		<i class="fa fa-chevron-down"></i>
	</span>

</div>
