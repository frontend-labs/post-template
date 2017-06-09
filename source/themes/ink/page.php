<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

$background_filter  = stag_get_post_meta( 'settings', get_the_ID(), 'post-background-filter' );

if ( ! $background_filter ) $background_filter = 'none';

get_header(); ?>

	<div id="main" class="site-main page-cover page-cover--<?php echo get_the_ID(); ?>">

		<div class="page-cover__background stag-image--<?php echo esc_attr( $background_filter ); ?>"></div>

		<?php stag_post_background_css( get_the_ID(), '.page-cover--', '.page-cover__background' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- #main -->

	<?php get_template_part( '_post', 'comments' ); ?>

<?php get_footer();
