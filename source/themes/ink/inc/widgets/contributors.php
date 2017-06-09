<?php
/**
 * Custom Recent Posts
 *
 * @since Ink 1.0
 */
class Stag_Widget_Contributors extends Stag_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_id          = 'stag_widget_site_contributors';
		$this->widget_cssclass    = 'site-contributors full-wrap';
		$this->widget_description = __( 'Print a list of all site contributors who published at least one post.', 'stag' );
		$this->widget_name        = __( 'Section: Contributors', 'stag' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => 'Our Contributors',
				'label' => __( 'Title:', 'stag' ),
			),
			'description' => array(
				'type'  => 'textarea',
				'std'   => null,
				'rows'  => '5',
				'label' => __( 'Description:', 'stag' ),
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

		$title       = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$description = $instance['description'];

		echo $before_widget;

		if ( $title ) echo $before_title . $title . $after_title;

		?>

		<div class="entry-content">
			<?php echo apply_filters( 'the_content', $description ); ?>
		</div>

		<div class="contributors-list">
			<div class="inside">
				<?php stag_list_authors(); ?>
			</div>
		</div>

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

add_action( 'widgets_init', array( 'Stag_Widget_Contributors', 'register' ) );
