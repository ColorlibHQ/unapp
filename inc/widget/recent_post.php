<?php

/**
 * Adds Unapp_Recent_Post_Widget widget.
 */
class Unapp_Recent_Post_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'unapp_recent_post', // Base ID
			esc_html__( 'Unapp Recent Post', 'unapp' ), // Name
			array( 'description' => esc_html__( 'A recent post Widget', 'unapp' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		//echo $args['before_widget'];

		$defaults = array(
			'title'       => esc_html__( 'Recent Blog', 'unapp' ),
			'post_number' => 3
		);

		$instance = wp_parse_args( $instance, $defaults );

		?>
		<div class="sidebar-widget">
			<h4><?php print $instance['title']; ?></h4>
			<?php
				$post_args = array(
					'post_type' => 'post',
					'posts_per_page' => $instance['post_number'],
					'ignore_sticky_posts' => 1
				);
				$recent_post = new WP_Query( $post_args );
				while ( $recent_post->have_posts() ) :
					$recent_post->the_post();
			?>
			<div class="f-blog">
				<?php if ( has_post_thumbnail() ) { ?>
				<a href="<?php the_permalink(); ?>" class="blog-img">
					<?php the_post_thumbnail( 'unapp-footer-blog' );?>
				</a>
				<?php } ?>
				<div class="desc">
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="admin"><span><?php unapp_post_date_format(); ?></span></p>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
		<?php
		//echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$defaults = array(
			'title'       => esc_html__( 'Recent Blog', 'unapp' ),
			'post_number' => 3
		);

		$instance = wp_parse_args( $instance, $defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'unapp' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>"><?php esc_attr_e( 'Post Number:', 'unapp' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_number' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['post_number'] ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['post_number'] = ( ! empty( $new_instance['post_number'] ) ) ? sanitize_text_field( $new_instance['post_number'] ) : '';

		return $instance;
	}

} // class Unapp_Recent_Post_Widget

// register Unapp_Recent_Post_Widget widget
function register_unapp_recent_post() {
	register_widget( 'Unapp_Recent_Post_Widget' );
}
add_action( 'widgets_init', 'register_unapp_recent_post' );