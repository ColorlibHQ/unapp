<?php

/**
 * Unapp_About_Widget widget.
 */
class Unapp_About_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'unapp_about_widget', // Base ID
			esc_html__( 'Unapp About', 'unapp' ), // Name
			array( 'description' => esc_html__( 'A About Widget', 'unapp' ), ) // Args
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
		?>
		<div class="sidebar-widget">
			<h4><?php print $instance[ 'title' ]; ?></h4>
			<p><?php print $instance[ 'description' ]; ?></p>
            <ul class="colorlib-social-icons">
                <?php if( !empty( $instance['facebook'] ) ) { ?>
                <li><a href="<?php print $instance['facebook']; ?>"><i class="icon-facebook"></i></a></li>
                <?php } ?>
                <?php if( !empty( $instance['twitter'] ) ) { ?>
                <li><a href="<?php print $instance['twitter']; ?>"><i class="icon-twitter"></i></a></li>
                <?php } ?>
                <?php if( !empty( $instance['linkedin'] ) ) { ?>
                <li><a href="<?php print $instance['linkedin']; ?>"><i class="icon-linkedin"></i></a></li>
                <?php } ?>
                <?php if( !empty( $instance['dribbble'] ) ) { ?>
                <li><a href="<?php print $instance['dribbble']; ?>"><i class="icon-dribbble"></i></a></li>
                <?php } ?>
                <?php if( !empty( $instance['instagram'] ) ) { ?>
                <li><a href="<?php print $instance['instagram']; ?>"><i class="icon-instagram"></i></a></li>
                <?php } ?>
            </ul>
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
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'About unapp';
		$description = ! empty( $instance['description'] ) ? $instance['description'] : 'Far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics';
		$facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '#';
		$twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '#';
		$linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '#';
		$dribbble = ! empty( $instance['dribbble'] ) ? $instance['dribbble'] : '#';
		$instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '#';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'unapp' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_attr_e( 'Description:', 'unapp' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" type="text"><?php echo esc_attr( $description ); ?></textarea>
		</p>

		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_attr_e( 'Facebook:', 'unapp' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_attr_e( 'Twitter:', 'unapp' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_attr_e( 'Linkedin:', 'unapp' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="text" value="<?php echo esc_attr( $linkedin ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'dribbble' ) ); ?>"><?php esc_attr_e( 'Dribbble:', 'unapp' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dribbble' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dribbble' ) ); ?>" type="text" value="<?php echo esc_attr( $dribbble ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_attr_e( 'Instagram:', 'unapp' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="text" value="<?php echo esc_attr( $instagram ); ?>">
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
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? sanitize_text_field( $new_instance['description'] ) : '';
		$instance['facebook'] = ( ! empty( $new_instance['facebook'] ) ) ? sanitize_text_field( $new_instance['facebook'] ) : '';
		$instance['twitter'] = ( ! empty( $new_instance['twitter'] ) ) ? sanitize_text_field( $new_instance['twitter'] ) : '';
		$instance['linkedin'] = ( ! empty( $new_instance['linkedin'] ) ) ? sanitize_text_field( $new_instance['linkedin'] ) : '';
		$instance['dribbble'] = ( ! empty( $new_instance['dribbble'] ) ) ? sanitize_text_field( $new_instance['dribbble'] ) : '';
		$instance['instagram'] = ( ! empty( $new_instance['instagram'] ) ) ? sanitize_text_field( $new_instance['instagram'] ) : '';

		return $instance;
	}

} // class Unapp_About_Widget

// register Unapp_About_Widget widget
function register_unapp_about() {
	register_widget( 'Unapp_About_Widget' );
}
add_action( 'widgets_init', 'register_unapp_about' );