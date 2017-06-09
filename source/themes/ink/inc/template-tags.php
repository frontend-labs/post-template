<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Stag_Customizer
 */

if ( ! function_exists( 'stag_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function stag_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'stag' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'stag' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<span class="comment-author vcard">
					<?php comment_author_link(); ?>
				</span><!-- .comment-author -->

				<span class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php echo human_time_diff( get_comment_time('U'), current_time('timestamp') ) . __( ' ago', 'stag' ); ?>
						</time>
					</a>
					<?php
						comment_reply_link( array_merge( $args, array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<span class="divider">/</span><span class="reply">',
							'after'     => '</span>',
						) ) );
					?>
				</span><!-- .comment-metadata -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'stag' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->
		</article><!-- .comment-body -->

	<?php
	endif;
}
endif;  // ends check for stag_comment()

function stag_comment_form_filter( $defaults ) {
	$defaults['title_reply'] = __( 'Submit a comment', 'stag' );

	$input_size = '50';

	// Comment Author
	$comment_author = ( isset( $_POST['author'] ) ) ? wp_strip_all_tags( $_POST['author'], true ) : null;
	$author_field = sprintf(
		'<p class="comment-form-author"><input class="text-input respond-type" type="text" name="%1$s" id="%1$s" value="%3$s" size="%4$d" aria-required="true" tabindex="%5$d" /><label for="%1$s">%2$s</label></p>',
		'author',
		esc_attr_x( 'Name', 'comment field placeholder', 'stag' ),
		esc_attr( $comment_author ),
		$input_size,
		1
	);

	// Comment Author Email
	$comment_author_email = ( isset( $_POST['email'] ) ) ? trim( $_POST['email'] ) : null;
	$email_field = sprintf(
		'<p class="comment-form-email"><input class="text-input respond-type" type="email" name="%1$s" id="%1$s" value="%3$s" size="%4$d" aria-required="true" tabindex="%5$d" /><label for="%1$s">%2$s</label></p>',
		'email',
		esc_attr_x( 'Email', 'comment field placeholder', 'stag' ),
		esc_attr( $comment_author_email ),
		$input_size,
		2
	);

	// Comment Author URL
	$comment_author_url = ( isset( $_POST['url'] ) ) ? trim( $_POST['url'] ) : null;
	$url_field = sprintf(
		'<p class="comment-form-url"><input class="text-input respond-type" type="url" name="%1$s" id="%1$s" value="%3$s" size="%4$d" tabindex="%5$d" /><label for="%1$s">%2$s</label></p>',
		'url',
		esc_attr_x( 'Website', 'comment field placeholder', 'stag' ),
		esc_attr( $comment_author_url ),
		$input_size,
		3
	);

	// Set the fields in the $defaults array
	$defaults['fields'] = array(
		'author' => $author_field,
		'email'  => $email_field,
		'url'    => $url_field
	);

	// Comment Form
	$defaults['comment_field'] = sprintf(
		'<p class="comment-form-comment"><textarea id="comment" class="blog-textarea respond-type" name="comment" cols="58" rows="10" aria-required="true" tabindex="4"></textarea><label for="comment">%s</label></p>',
		esc_attr_x( 'Your Comment', 'comment field placeholder', 'stag' )
	);

	// Comment form notes
	$defaults['comment_notes_before'] = '';
	$defaults['comment_notes_after']  = '';

	// Submit label
	$defaults['label_submit'] = __( 'Submit comment', 'stag' );

	return $defaults;
}
add_filter( 'comment_form_defaults', 'stag_comment_form_filter' );

if ( ! function_exists( 'stag_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function stag_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$author = get_the_author();

	if( $author == '' ) {
		global $wp_query;
		$authordata = $GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
		$author = $authordata->display_name;
	}

	printf( __( '<span class="posted-on">%1$s</span><span class="reading-time hide">%3$s</span><span class="byline">por %2$s</span>', 'stag' ),
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			$time_string
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( $author )
		),
		stag_post_reading_time()
	);
}
endif;  // ends check for stag_posted_on()

/**
 * Returns true if a blog has more than 1 category
 */
function stag_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so stag_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so stag_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in stag_categorized_blog
 */
function stag_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'stag_category_transient_flusher' );
add_action( 'save_post',     'stag_category_transient_flusher' );

if ( ! function_exists( 'stag_post_reading_time' ) ) :
/**
 * Calculate post reading time.
 *
 * Cache the post reading time under post meta for better performance.
 *
 * @uses stag_get_post_meta()
 * @uses stag_update_post_meta()
 * @since 1.0.0.
 * @param int $post_id Post ID of which to calculate the post reading time.
 * @return mixed
 */
function stag_post_reading_time( $post_id = null ) {
	if ( 0 === absint( $post_id ) ) {
        $post_id = get_the_ID();
    }

    // Use cached value if available
    $char_count = stag_get_post_meta( 'cache', $post_id, 'char-count' );
    if ( false !== $char_count ) {
        $char_count = absint( $char_count );
    } else {
		$content = wp_strip_all_tags( strip_shortcodes( get_post( $post_id )->post_content ), true );

		/**
		 * Use - count( preg_split( '/\s+/', $content ) )
		 * for compatibility with cyrillic or other characters which are not assumed words.
		 *
		 * @since 1.1.0
		 */
		$char_count = apply_filters( 'post_words_count', str_word_count( $content ) );

        // Cache the value
        stag_update_post_meta( 'cache', $post_id, 'char-count', $char_count );
    }

    $wpm = apply_filters( 'ink_words_per_minute', 200 );

    // Get Estimated time
    $minutes = floor( $char_count / $wpm);
    $seconds = floor( ($char_count / ($wpm / 60) ) - ( $minutes * 60 ) );

    // If less than a minute
    if( $minutes < 1 ) {
        $estimated_time = __( '1 minuto', 'stag' );
    }

    // If more than a minute
    if( $minutes > 1 ) {

    	if( $seconds > 30 ) {
	    	$minutes++;
    	}

    	/* translators: %d = minute count */
        $estimated_time = sprintf( __( '%d minutos', 'stag' ), $minutes );
    }

    return $estimated_time;
}
endif;

if ( ! function_exists( 'stag_related_posts' ) ) :
/**
 * Display related posts, based on post tags.
 *
 * @since 1.0.0.
 * @return void
 */
function stag_related_posts() {
	global $post;

	if ( stag_rcp_user_has_no_access() )
		return;

	if ( ! stag_theme_mod( 'post_settings', 'show_related_posts' ) )
		return;

	$tags = wp_get_post_tags( $post->ID );

	if ( count( $tags ) ) {
		$tag_ids = array();

		foreach ( $tags as $individual_tag ) {
			$tag_ids[] = $individual_tag->slug;
		}

		$query = new WP_Query( array(
			'tag'                 => implode(',', $tag_ids),
			'post__not_in'        => array( $post->ID ),
			'posts_per_page'      => absint( stag_theme_mod( 'post_settings', 'related_posts_count' ) ),
			'ignore_sticky_posts' => true
		) );

		// Add filter to later attach 'post-grid' class to posts for homepage layouts.
		add_filter( 'stag_showing_related_posts', '__return_true' );

		if( $query->have_posts() ) :

			echo '<section class="related-posts" data-layout="2-2-2-2">';

			while( $query->have_posts() ) : $query->the_post();
				get_template_part( 'content', get_post_format() );
			endwhile;

			echo '</section>';

		endif;
	}

	wp_reset_query();

	remove_all_filters( 'stag_showing_related_posts' );
}
endif;

if ( ! function_exists( 'stag_archive_title' ) ) :
/**
 * Archive page title for different archive pages.
 *
 * @since 1.0.0.
 * @return void
 */
function stag_archive_title() {
	if( is_category() ) :
		printf( __( 'Todos los posts en %s', 'stag' ), '<span>' . single_cat_title( '', false ) . '</span>' );

	elseif( is_tag() ) :
		printf( __( 'Todos los posts en %s', 'stag' ), '<span>' . single_tag_title( '', false ) . '</span>' );

	elseif( is_author() ) :
		the_post();
		printf( __( 'Author: %s', 'stag' ), '<span class="vcard">' . get_the_author() . '</span>' );
		rewind_posts();

	elseif ( is_day() ) :
		printf( __( 'Day: %s', 'stag' ), '<span>' . get_the_date() . '</span>' );

	elseif ( is_month() ) :
		printf( __( 'Month: %s', 'stag' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

	elseif ( is_year() ) :
		printf( __( 'Year: %s', 'stag' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

	elseif ( is_search() ) :
		printf( __( 'Search Results for: %s', 'stag' ), '<span>' . get_search_query() . '</span>' );

	else :
		_e( 'Archives', 'stag' );

	endif;
}
endif;

if ( ! function_exists( 'stag_list_authors' ) ) :
/**
 * Print a list of all site contributors who published at least one post.
 *
 * @since Twenty Fourteen 1.0
 */
function stag_list_authors() {
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'orderby' => apply_filters( 'ink_contributors_orderby', 'post_count' ),
		'order'   => 'DESC',
		'who'     => 'authors',
	) );

	$authors_count = count( $contributor_ids );

	$class = '';
	if ( $authors_count === 1 ) {
		$class = 'one-column';
	} elseif ( $authors_count === 2 ) {
		$class = 'two-column';
	} elseif ( $authors_count === 3 ) {
		$class = 'three-column';
	} elseif ( $authors_count === 4 || $authors_count > 4 ) {
		$class = 'four-column';
	}

	foreach ( $contributor_ids as $contributor_id ) :
		$post_count = count_user_posts( $contributor_id );

		// Move on if user has not published a post (yet).
		if ( ! $post_count ) {
			continue;
		}
	?>

	<div class="contributor <?php echo esc_attr( $class ); ?>">
		<div class="contributor-info">
			<figure class="contributor-avatar">
				<a class="contributor-posts-link" href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
					<?php echo get_avatar( $contributor_id, 255 ); ?>
				</a>
			</figure>
			<div class="contributor-summary">
				<h4 class="contributor-name">
					<a class="contributor-posts-link" href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
						<?php echo get_the_author_meta( 'display_name', $contributor_id ); ?>
					</a>
				</h4>
			</div><!-- .contributor-summary -->
		</div><!-- .contributor-info -->
	</div><!-- .contributor -->

	<?php
	endforeach;
}
endif;
