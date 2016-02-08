<?php
/**
 * Template Name: Current
 *
 * Display the last post from the blog, like a single post.
 *
 * @package Stag_Customizer
 * @since 1.1.0.
 */

get_header();

$args      = array( 'posts_per_page' => 1 );
$postslist = get_posts( $args );

foreach ( $postslist as $post ) : setup_postdata( $post ); ?>
	<?php get_template_part( '_post', 'cover-wrap' ); ?>

	<main id="main" class="site-main">
		<?php get_template_part( 'content', 'single' ); ?>
		<?php get_template_part( '_post', 'comments' ); ?>
	</main>

<?php endforeach; ?>

<?php wp_reset_postdata(); ?>

<?php get_footer();
