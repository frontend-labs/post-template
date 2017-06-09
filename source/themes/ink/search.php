<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'search' ); ?>

			<?php endwhile; ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->

		<?php get_template_part( '_post', 'load' ); ?>

	</section><!-- #primary -->

<?php get_footer();
