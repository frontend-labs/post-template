<?php
/**
 * @package Stag_Customizer
 * @subpackage Ink
 */

$categories_list = get_the_category_list(', ');

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( '_post', 'background' ); ?>
	<div class="post-content">
		<header class="entry-header">
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
				<?php if ( has_filter( 'stag_loading_more_posts' ) && function_exists('get_the_subtitle') && get_the_subtitle() ) : ?>
				<span class="entry-subtitle"><?php echo get_the_subtitle(); ?></span>
				<?php endif; ?>
				</a>
			</h1>
		</header><!-- .entry-header -->

		<footer class="entry-meta">
			<?php stag_posted_on(); ?>
			<?php edit_post_link( __( 'Edit', 'stag' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->

		<?php if ( stag_theme_mod( 'post_settings', 'post_categories' ) && stag_categorized_blog() && $categories_list ) : ?>
		<div class="entry-categories">
			<?php _e( 'In ', 'stag' ); ?><?php echo $categories_list; ?>
		</div>
		<?php endif; ?>

		<?php if( stag_theme_mod( 'post_settings', 'show_excerpt' ) ) : ?>
		<div class="post-excerpt">
			<?php the_excerpt(); ?>
		</div>
		<?php endif; ?>
	</div>
</article>
