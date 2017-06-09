<?php
/**
 * @package Stag_Customizer
 * @subpackage Ink
 */

function stag_custom_user_fields() {

	$default_fields = apply_filters( 'stag_custom_user_fields', array(
		'twitter' => array(
			'title' => __( 'Twitter', 'stag' ),
			'class' => 'fa-twitter',
		),
		'facebook' => array(
			'title' => __( 'Facebook', 'stag' ),
			'class' => 'fa-facebook',
		),
		'instagram' => array(
			'title' => __( 'Instagram', 'stag' ),
			'class' => 'fa-instagram',
		),
		'foursquare' => array(
			'title' => __( 'foursquare', 'stag' ),
			'class' => 'fa-foursquare',
		),
		'google_plus_square' => array(
			'title' => __( 'Google+', 'stag' ),
			'class' => 'fa-google-plus-square',
		)
	) );

	return $default_fields;
}

/**
 * Add custom user profile fields.
 *
 * @param  array $fields An array containing existing user profile fields.
 * @return array $fields
 */
function stag_user_profile_fields( $fields ) {

	$user_fields = stag_custom_user_fields();

	foreach( $user_fields as $key => $social ) {
		$fields[$key] = $social['title'];
	}

	return $fields;
}
add_filter( 'user_contactmethods', 'stag_user_profile_fields' );

/**
 * Retrieve user's custom social profile.
 *
 * @param  int $id User ID.
 * @return array Custom user fields.
 */
function stag_get_user_social_profiles( $id ) {
	$fields      = array();
	$user_fields = stag_custom_user_fields();

	foreach( $user_fields as $field_key => $field_value ) {
		$fields[$field_key] = get_the_author_meta( $field_key, $id );
	}

	return array_filter( $fields );
}

/**
 * Body classes.
 *
 * @param  array $classes An array containing default body classes.
 * @return array          An array containing modified body classes.
 */
function stag_body_classes( $classes ) {
	$header_over = false;

	if( is_singular() || is_home() ) {
		$header_over = true;

		if ( is_page() ) {
			$id       = get_the_ID();
			$thumb_id = get_post_thumbnail_id( $id );

		    if ( $thumb_id != '' ) {
				$thumb_url        = wp_get_attachment_image_src( $thumb_id, 'full', true );
				$page_bg = $thumb_url[0];
		    } else {
		    	$page_bg = '';
		    }

			$page_color = stag_get_post_meta( 'settings', $id, 'post-background-color' );

			if( '' == $page_bg && ( '' == $page_color || '#ffffff' == $page_color ) ) {
				$header_over = false;
			}

			if ( get_page_template_slug() === 'widgetized.php' ) {
				$hide_title = stag_get_post_meta( 'settings', get_the_ID(), 'page-hide-title' );
				if( $hide_title === "on" ) {
					$classes[] = 'hide-page-title';
				}
			}
		}
	}

	if( is_search() || get_page_template_slug() === 'current.php' || ( is_archive() && !is_author() ) ) {
		$header_over = true;
	}

	if ( $header_over ) {
		$classes[] = 'header-over';
	} else {
		$classes[] = 'header-normal';
	}

	// Hide Author byline if user has selected so under options
	$is_hiding = stag_theme_mod( 'post_settings', 'hide_author_title' );

	if ( $is_hiding ) {
		$classes[] = 'hide-author';
	}

	return $classes;
}
add_filter( 'body_class', 'stag_body_classes' );

if ( ! function_exists( 'stag_post_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function stag_post_classes( $classes ) {
	$post_id = get_the_ID();

	if ( ! is_single() && ! is_page() ) {
		$classes[] = 'post-grid';
	}

	if( true == apply_filters( 'stag_showing_related_posts', false ) ) {
		$classes[] = 'post-grid';
	}

	if( true == apply_filters( 'stag_loading_more_posts', false ) ) {
		$classes[] = 'animate';
	}

	if( stag_rcp_user_has_no_access() ) {
		$classes[] = 'restricted-post';
	}

	return $classes;
}
endif;

add_filter( 'post_class', 'stag_post_classes' );

/**
 * Outputs post specific CSS for post background image, color and opacity.
 *
 * @param  int $post_id Post ID.
 * @param  string $sel1 CSS Selector 1.
 * @param  string $sel2 CSS Selector 1.
 * @return mixed
 */
function stag_post_background_css( $post_id = null, $sel1 = '.article-cover--', $sel2 = '.article-cover__background' ) {
	if ( 0 === absint( $post_id ) ) {
        $post_id = get_the_ID();
    }

    $thumb_id = get_post_thumbnail_id( $post_id );

    if ( $thumb_id != '' ) {
		$thumb_url        = wp_get_attachment_image_src( $thumb_id, 'full', true );
		$background_image = $thumb_url[0];
    } else {
    	$background_image = '';
    }

	$background_color   = stag_get_post_meta( 'settings', $post_id, 'post-background-color' );
	$background_opacity = stag_get_post_meta( 'settings', $post_id, 'post-background-opacity' );
	$text_color         = stag_get_post_meta( 'settings', $post_id, 'post-text-color' );
	$link_color         = stag_get_post_meta( 'settings', $post_id, 'post-link-color' );

    // Set a default background opacity
	if ( empty($background_opacity) ) $background_opacity = 40;

	ob_start(); ?>

	<style type="text/css" scoped>
		<?php if( ! empty( $background_color ) ) : ?>
		<?php echo $sel1 . $post_id; ?> { background-color: <?php echo stag_maybe_hash_hex_color($background_color); ?>; }
		<?php endif; ?>

		.background-video { opacity: <?php echo absint($background_opacity)/100; ?>; }

		<?php if( ! empty( $background_image ) ) : ?>
		<?php echo $sel2; ?> { background-image: url(<?php echo esc_url( $background_image ); ?>); opacity: <?php echo absint($background_opacity)/100; ?>; }
		<?php endif; ?>

		<?php if( is_page() ) : ?>

		<?php if( ! empty( $text_color ) ) : ?>
		<?php echo $sel1 . $post_id; ?> { color: <?php echo stag_maybe_hash_hex_color($text_color); ?>; }
		<?php echo $sel1 . $post_id; ?> input { color: <?php echo stag_maybe_hash_hex_color($text_color); ?>; border-color: <?php echo stag_maybe_hash_hex_color($text_color); ?>; }
		<?php endif; ?>

		<?php if( ! empty( $link_color ) ) : ?>
		<?php echo $sel1 . $post_id; ?> a:not(.stag-button):not(.ui-tabs-anchor) { color: <?php echo stag_maybe_hash_hex_color($link_color); ?>; }
		<?php endif; ?>

		<?php endif; ?>
	</style>

	<?php
	echo ob_get_clean();
}

if ( ! function_exists( 'stag_registered_sidebars' ) ) :
/**
 * Get a list of registered sidebars.
 *
 * @since 1.0.0.
 *
 * @param  array  $sidebars Any additional sidebar keys to add.
 * @param  array  $exclude  Array of sidebars to exclude
 * @return array An array containing list of Sidebars.
 */
function stag_registered_sidebars( $sidebars = array(), $exclude = array() ) {
	global $wp_registered_sidebars;

	foreach ( $wp_registered_sidebars as $sidebar ) {
		if( !in_array( $sidebar['name'], $exclude ) ) {
			$sidebars[$sidebar['id']] = $sidebar['name'];
		}
	}

	return $sidebars;
}
endif;

/**
 * Hide layout metabox and background settings when not needed.
 *
 * @return void
 */
function stag_admin_print_script() {
	if( 'page' != get_post_type() )
		return;

	?>
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function(event) {

			var page_template = document.getElementById('page_template');
			var layout_metabox = document.getElementById('stag-metabox-layout');

			console.log(page_template.value);

			if (page_template.value !== "widgetized.php") {
				layout_metabox.style.display = "none";
			}

			page_template.addEventListener( 'change', function(e){
				if (e.target.value === "widgetized.php") {
					layout_metabox.style.display = "block";
				} else {
					layout_metabox.style.display = "none";
				}
			}, false );
		});
	</script>
	<?php
}
add_action( 'admin_print_scripts-post.php', 'stag_admin_print_script' );
add_action( 'admin_print_scripts-post-new.php', 'stag_admin_print_script' );

/**
 * Display site favicon.
 *
 * @return void
 */
function stag_site_favicon() {

	$favicon = stag_theme_mod( 'general_settings', 'favicon' );

	if ( $favicon != '' ) {
		echo "<link rel='shortcut icon' href='$favicon' />";
	}

	return;
}
add_action( 'wp_head', 'stag_site_favicon' );

/**
 * Output Google Analytics code in footer.
 *
 * @return void
 */
function stag_google_analytics_code() {
	$tracking_code = stag_theme_mod( 'general_settings', 'google_analytics' );

	if( $tracking_code == '' )
		return;

	?>
	<script type="text/javascript"><?php echo $tracking_code; ?></script>
	<?php
}
add_action( 'wp_footer', 'stag_google_analytics_code' );

if ( ! function_exists( 'stag_site_layout' ) ) :
/**
 * Returns user selected layout for blog pages.
 *
 * Author page uses '2-2-2-2' layout by default.
 *
 * @return mixed
 */
function stag_site_layout() {
	$layout = stag_theme_mod( 'layout_options', 'layout' );

	if ( is_author() || 'widgetized.php' === get_page_template_slug() ) {
		$layout = "2-2-2-2";
	}

	if( is_single() ) {
		return false;
	}

	$layout = apply_filters( 'ink_site_layout', $layout );

	return $layout;
}
endif;

if ( ! function_exists( 'stag_custom_404_page' ) ) :
/**
 * Custom 404 page redirect.
 *
 * @return void
 */
function stag_custom_404_page() {
	$custom_404 = stag_theme_mod( '404_page', '404_custom_page' );

	if( is_404() && $custom_404 != '0' ) {
		$link = get_permalink( $custom_404 );

		header("HTTP/1.0 301 Moved Permanently");
		header("Location: $link");

		exit();
	}
}
endif;

add_action( 'template_redirect', 'stag_custom_404_page' );

if ( ! function_exists( 'stag_inifinite_scroll' ) ) :
/**
 * Infinite scroll function.
 *
 * @since  1.0.3.
 * @return void
 */
function stag_inifinite_scroll() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'stag-ajax') )
		return;

	if ( ! is_numeric( $_POST['page'] ) || $_POST['page'] < 0 )
		return;

	$query_args = array(
		'post_status'    => 'publish',
		'post_type'      => 'post',
		'posts_per_page' => get_option('posts_per_page'),
		'paged'          => $_POST['page'],
		'post__not_in'   => get_option('sticky_posts')
	);

	if ( isset( $_POST['archive'] ) && $_POST['archive'] != '' ) {
		$archive = $_POST['archive'];
		$query_args = array_merge( $query_args, $archive );
	}

	if ( isset( $_POST['search'] ) && $_POST['search'] != '' ) {
		$query_args['s'] = $_POST['search'];
		$query_args['post_type'] = array( 'post', 'page' );
	}

	$query = new WP_Query( $query_args );

	add_filter( 'stag_loading_more_posts', '__return_true' );

	ob_start();

	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) : $query->the_post();
			get_template_part( 'content', get_post_format() );
		endwhile;
	endif;

	$content = ob_get_clean();

	wp_reset_postdata();

	remove_all_filters( 'stag_loading_more_posts' );

	wp_send_json( array(
		'pages'   => $query->max_num_pages,
		'content' => $content
	) );
}
endif;

add_action( 'wp_ajax_stag_inifinite_scroll', 'stag_inifinite_scroll' );
add_action( 'wp_ajax_nopriv_stag_inifinite_scroll', 'stag_inifinite_scroll' );
