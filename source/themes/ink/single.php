<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

get_header(); ?>

<script type="text/javascript" src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>

	<?php get_template_part( '_post', 'cover-wrap' ); ?>

	<main id="main" class="site-main">

	<?php /* Start the Loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'single' ); ?>

		<?php stag_related_posts(); ?>

		<?php get_template_part( '_post', 'comments' ); ?>

	<?php endwhile; // end of the loop. ?>

	</main><!-- #main -->

<?php get_footer();
