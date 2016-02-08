<?php
/**
 * Template Name: Widgetized
 *
 * Widgetized template, outputs sidebar area with a shortcode.
 * Requires Stag Custom Sidebars plugin.
 *
 * @see https://wordpress.org/plugins/stag-custom-sidebars/
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

// Get selected sidebar
$sidebar = stag_get_post_meta( 'settings', get_the_ID(), 'page-sidebar' );

get_header(); ?>

	<div id="main" class="site-main page-cover page-cover--<?php echo get_the_ID(); ?>">

		<div class="page-cover__background"></div>

		<?php stag_post_background_css( get_the_ID(), '.page-cover--', '.page-cover__background' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- #main -->

	<?php rewind_posts(); ?>

	<?php if ( '' != $sidebar ) : ?>
	<section id="<?php echo esc_attr( $sidebar ); ?>" class="stag-custom-widget-area">
		<?php dynamic_sidebar( $sidebar ); ?>
	</section>
	<?php endif; ?>

<?php get_footer();
