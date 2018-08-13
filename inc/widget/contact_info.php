<?php

/**
 * Adds Unapp_Contact_Widget widget.
 */
class Unapp_Contact_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'unapp_contact', // Base ID
			esc_html__( 'Unapp Contact Info', 'unapp' ), // Name
			array( 'description' => esc_html__( 'A contact Widget', 'unapp' ), ) // Args
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
			<h4><?php print $instance['title']; ?></h4>
			<ul class="colorlib-footer-links">
				<?php if( ! empty( $instance['address'] ) ) { ?>
				<li><?php print $instance['address']; ?></li>
				<?php } ?>
				<?php if( ! empty( $instance['phone'] ) ) { ?>
				<li><a href="#"><i class="icon-phone"></i> <?php print $instance['phone']; ?></a></li>
				<?php } ?>
				<?php if( ! empty( $instance['email'] ) ) { ?>
				<li><a href="mailto:<?php print $instance['email']; ?>"><i class="icon-envelope"></i><?php print $instance['email']; ?></a></li>
				<?php } ?>
				<?php if( ! empty( $instance['web_site'] ) ) { ?>
				<li><a href="<?php print $instance['web_site']; ?>"><i class="icon-location4"></i><?php print $instance['web_site']; ?></a></li>
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
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Contact Info', 'unapp' );
		$address = ! empty( $instance['address'] ) ? $instance['address'] : esc_html__( '291 South 21th Street, Suite 721 New York NY 10016', 'unapp' );
		$phone = ! empty( $instance['phone'] ) ? $instance['phone'] : esc_html__( '+ 1235 2355 98', 'unapp' );
		$email = ! empty( $instance['email'] ) ? $instance['email'] : esc_html__( 'info@yoursite.com', 'unapp' );
		$web_site = ! empty( $instance['web_site'] ) ? $instance['web_site'] : esc_html__( 'yourwebsite.com', 'unapp' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'unapp' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_attr_e( 'Address:', 'unapp' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" type="text"><?php echo esc_attr( $address ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_attr_e( 'Phone Number:', 'unapp' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_attr_e( 'Email Address:', 'unapp' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'web_site' ) ); ?>"><?php esc_attr_e( 'Web Address:', 'unapp' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'web_site' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'web_site' ) ); ?>" type="text" value="<?php echo esc_attr( $web_site ); ?>">
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
		$instance['address'] = ( ! empty( $new_instance['address'] ) ) ? sanitize_text_field( $new_instance['address'] ) : '';
		$instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? sanitize_text_field( $new_instance['phone'] ) : '';
		$instance['email'] = ( ! empty( $new_instance['email'] ) ) ? sanitize_text_field( $new_instance['email'] ) : '';
		$instance['web_site'] = ( ! empty( $new_instance['web_site'] ) ) ? sanitize_text_field( $new_instance['web_site'] ) : '';

		return $instance;
	}

} // class Unapp_Contact_Widget

// register Unapp_Contact_Widget widget
function register_unapp_contact() {
	register_widget( 'Unapp_Contact_Widget' );
}
add_action( 'widgets_init', 'register_unapp_contact' );