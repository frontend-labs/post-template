<?php
/**
 * Author page template.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

// Current author
global $authordata;

$author_id = $authordata->ID;

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<section class="page-header current-author">
				<div class="page-header__content">
					<h1 class="current-author__name"><?php echo esc_attr( $authordata->display_name ); ?></h1>

					<?php if ( ! empty( $authordata->description ) ) : ?>
					<div class="current-author__description"><?php echo wpautop( $authordata->description ); ?></div>
					<?php endif; ?>

					<div class="current-author__social-profiles">
						<?php
							$social_profiles = stag_get_user_social_profiles( $author_id );
							$default_fields = stag_custom_user_fields();

							if( count( $social_profiles ) ) {
								foreach( $social_profiles as $slug => $url ) {
									echo '<li class="'. esc_attr( $slug ) .'"><a href="'. esc_url( $url ) .'" title="'. esc_attr( ucfirst( $slug ) ) .'"><i class="fa '. $default_fields[$slug]['class'] .'"></i></a></li>';
								}
							}

							if ( '' != $author_url = get_the_author_meta( 'user_url' ) ) {
								echo '<li class="website"><a href="'. esc_url( $author_url ) .'" title="'.  esc_attr__( 'Website', 'stag' ).'"><i class="fa fa-globe"></i></a></li>';
							}
						?>
					</div>
				</div><!-- .page-header__content -->
			</section><!-- .page-header -->

			<?php

			$query = new WP_Query( array(
				'author'         => $author_id,
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 10
			) );

			?>

			<?php if( $query->have_posts() ): ?>

				<?php /* Start the Loop */ ?>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
					?>

				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

		</main><!-- #main -->

		<?php get_template_part( '_post', 'load' ); ?>

		<div class="block-button">
			<span>
				<?php $message = sprintf( _x( '%1$s en %2$s - %3$s', '1: Author Name 2: Site title', 'stag' ), esc_attr( $authordata->display_name ), get_bloginfo( 'name' ), get_author_posts_url( $author_id ) ); ?>
				<a target="_blank" href="<?php echo esc_url( sprintf( 'http://www.twitter.com?status=%s', urlencode( $message ) ) ); ?>"><?php _e( 'Comparte este perfil en Twitter', 'stag' ); ?></a>
			</span>
		</div><!-- .block-button -->
	</div><!-- #primary -->

<?php get_footer();
