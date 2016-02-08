<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main id="main">.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui">
<title><?php wp_title('|', true, 'right'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">


<!-- Google Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59791791-1', 'auto');
  ga('require', 'displayfeatures');
  ga('send', 'pageview');

</script>
<!-- End of Google Analytics -->

<?php /*
<!-- Google Analytics -->
<script type="text/javascript">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-52967407-1', 'auto');
ga('require', 'displayfeatures');
ga('send', 'pageview');

</script>
<!-- End of Google Analytics -->
*/ ?>

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-layout="<?php echo esc_attr( stag_site_layout() ); ?>">

<?php
/**
 * Aesop Story Engine theme hook.
 *
 * @since 1.1.0
 *
 */
do_action('ase_theme_body_inside_top');
?>

<nav class="site-nav" role="complementary">
	<div class="site-nav--scrollable-container">
		<i class="fa fa-times close-nav"></i>

		<?php if( has_nav_menu( 'primary' ) ) : ?>
		<nav id="site-navigation" class="navigation main-navigation site-nav__section" role="navigation">
			<h4 class="widgettitle"><?php _e( 'Menu', 'stag' ); ?></h4>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'primary-menu', 'container' => false, 'fallback_cb' => false ) ); ?>
		</nav><!-- #site-navigation -->
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'sidebar-drawer' ) ) : ?>
			<?php dynamic_sidebar( 'sidebar-drawer' ); ?>
		<?php endif; ?>
	</div>
</nav>
<div class="site-nav-overlay"></div>

<div id="page" class="hfeed site">

	<div id="content" class="site-content">

		<header id="masthead" class="site-header">

			<div class="site-branding">

				<?php /*if ( stag_get_logo()->has_logo() ) : */ ?>
					<a class="custom-logo" title="<?php esc_attr_e( 'Frontend-Labs', 'stag' ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<div class="bubbles">
						    <div class="bubble b1"></div>
						    <div class="bubble b2"></div>
						    <div class="bubble b3"></div>
						    <div class="bubble b4"></div>    
						</div>  
					</a>
				<?php /*else: ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
				<?php endif; */ ?>

				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
			</div>

			<a href="#" id="site-navigation-toggle" class="site-navigation-toggle"><i class="fa fa-navicon"></i></a>

			<?php if ( ! is_author() && ( is_archive() || is_search() ) ) : ?>
			<div class="archive-header">
				<h3 class="archive-header__title"><?php echo stag_archive_title(); ?></h3>
			</div>
			<?php endif; ?>

		</header><!-- #masthead -->
