<?php
/**
 * Custom Recent Posts
 *
 * @since Ink 1.0
 */
class Stag_Widget_Recent_Posts_Grid extends Stag_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_id          = 'stag_widget_recent_posts_grid';
		$this->widget_cssclass    = 'stag_widget_recent_posts_grid full-wrap';
		$this->widget_description = __( 'Displays recent posts from Blog in grid style.', 'stag' );
		$this->widget_name        = __( 'Section: Recent Posts (Grid)', 'stag' );
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
			'category' => array(
				'type'  => 'categories',
				'std'   => '0',
				'label' => __( 'Post Category:', 'stag' ),
			),
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

		$title    = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$count    = $instance['count'];
		$category = $instance['category'];
		$posts    = wp_get_recent_posts( array( 'post_type' => 'post', 'numberposts' => $count, 'post_status' => 'publish', 'category' => $category ), OBJECT );

		global $post;

		echo $before_widget;

		echo '<section class="recent-posts-grid" data-layout="2-2-2-2">';

		if ( $title ) echo $before_title . $title . $after_title;

		foreach( $posts as $post ) : setup_postdata( $post );
			add_filter( 'stag_showing_related_posts', '__return_true' );
			get_template_part( 'content', get_post_format() );
		endforeach;

		echo '</section>';

		wp_reset_postdata();

		remove_all_filters( 'stag_showing_related_posts' );

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

add_action( 'widgets_init', array( 'Stag_Widget_Recent_Posts_Grid', 'register' ) );
