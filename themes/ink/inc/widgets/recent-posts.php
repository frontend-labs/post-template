<?php
/**
 * Custom Recent Posts
 *
 * @since Ink 1.0
 */
class Stag_Widget_Recent_Posts extends Stag_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_id          = 'stag_widget_recent_posts';
		$this->widget_cssclass    = 'stag_widget_recent_posts full-wrap';
		$this->widget_description = __( 'Displays recent posts from Blog.', 'stag' );
		$this->widget_name        = __( 'Section: Recent Posts', 'stag' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => 'Latest Posts',
				'label' => __( 'Title:', 'stag' ),
			),
			'count' => array(
				'type'  => 'number',
				'std'   => '3',
				'label' => __( 'Number of posts to show:', 'stag' ),
			),
			'post_date' => array(
				'type'  => 'checkbox',
				'std'   => 'on',
				'label' => __( 'Display Post Date?', 'stag' ),
			),
			'bg_color' => array(
				'type'  => 'colorpicker',
				'std'   => stag_theme_mod( 'colors', 'accent' ),
				'label' => __( 'Background Color:', 'stag' ),
			),
			'bg_opacity' => array(
				'type'  => 'number',
				'std'   => '20',
				'step'  => '5',
				'min'   => '0',
				'max'   => '100',
				'label' => __( 'Background Opacity:', 'stag' ),
			),
			'bg_image' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Background Image URL:', 'stag' ),
			),
			'text_color' => array(
				'type'  => 'colorpicker',
				'std'   => '#ffffff',
				'label' => __( 'Text Color:', 'stag' ),
			),
			'link_color' => array(
				'type'  => 'colorpicker',
				'std'   => '#f8f8f8',
				'label' => __( 'Link Color:', 'stag' ),
			)
		);

		parent::__construct();
	}

	/**
	 * Widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) )
			return;

		ob_start();

		extract( $args );

		$title      = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$count      = $instance['count'];
		$post_date  = $instance['post_date'];
		$bg_color   = $instance['bg_color'];
		$bg_opacity = $instance['bg_opacity'];
		$bg_image   = $instance['bg_image'];
		$text_color = $instance['text_color'];
		$link_color = $instance['link_color'];
		$posts      = wp_get_recent_posts( array( 'post_type' => 'post', 'numberposts' => $count, 'post_status' => 'publish' ), OBJECT );

		$posts_page = get_option('page_for_posts');

		if( $posts_page == 0 ) {
			$posts_page = home_url();
		} else {
			$posts_page = get_permalink($posts_page);
		}

		global $post;

		echo $before_widget;
		?>

		<section class="inner-section">
			<span class="hentry" data-bg-color="<?php echo esc_attr( $bg_color ); ?>" data-bg-image="<?php echo esc_url( $bg_image ); ?>" data-bg-opacity="<?php echo esc_attr( $bg_opacity ); ?>" data-text-color="<?php echo esc_attr( $text_color ); ?>" data-link-color="<?php echo esc_attr( $link_color ); ?>"></span>

			<?php if( $title ) echo $before_title . $title . $after_title; ?>

			<?php foreach( $posts as $post ) : setup_postdata( $post ); ?>

				<article id="post-<?php the_ID(); ?>">
					<header class="entry-header">
						<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
					</header><!-- .entry-header -->

					<footer class="entry-meta">
						<?php stag_posted_on(); ?>
						<?php stag_post_reading_time(); ?>
						<?php edit_post_link( __( 'Edit', 'stag' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-meta -->
				</article>

			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>

			<a href="<?php echo $posts_page; ?>" class="button all-posts"><?php _e( 'See All Posts', 'stag' ); ?></a>
		</section>

		<?php
		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	/**
	 * Registers the widget with the WordPress Widget API.
	 *
	 * @return void.
	 */
	public static function register() {
	    register_widget( __CLASS__ );
	}
}

add_action( 'widgets_init', array( 'Stag_Widget_Recent_Posts', 'register' ) );
