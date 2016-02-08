<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */
?>


		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
			<div class="footer-widgets-container">
				<div class="inside">
					<div class="footer-widgets grid">
						<?php dynamic_sidebar( 'sidebar-footer' ); ?>
					</div>
				</div>
			</div><!-- .site-footer -->
			<?php endif; ?>
            
        <?php if (get_the_ID()!=1669){ ?> 
			<div class="after_all">

				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- after_all -->
				<ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-9151106315507816" data-ad-slot="1949147488"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>	
			</div>
        <?php } ?>

			<?php $footer_text = stag_theme_mod( 'stag_footer', 'copyright' ); ?>
			<?php if ( ! empty( $footer_text ) || has_nav_menu( 'footer' ) ) : ?>
			<div class="copyright">
				<div class="inside">

					<div class="grid">
						<div class="unit one-of-two site-info">
							Copyright © <?php echo date('Y'); ?> — Frontend Labs.
							<?php //echo do_shortcode( stag_allowed_tags($footer_text) ); ?>
						</div><!-- .site-info -->

						<?php if( has_nav_menu('footer') ) : ?>
						<div class="unit one-of-two">
							<?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'footer-menu', 'container' => false, 'fallback_cb' => false, 'before' => '<span class="divider">/</span>' ) ); ?>
						</div>
						<?php endif; ?>
					</div>

				</div>
			</div><!-- .copyright -->
			<?php endif; ?>
		</footer><!-- #colophon -->

	</div><!-- #content -->

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
