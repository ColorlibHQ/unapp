<?php
/**
 * Epsilon User Tracking
 *
 * @package Epsilon Framework
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_User_Tracking
 */
class Epsilon_User_Tracking extends Epsilon_Tracking {
	/**
	 * Generate the data
	 */
	public function generate_data() {
		$admin = get_user_by( 'email', get_bloginfo( 'admin_email' ) );

		if ( ! $admin ) {
			return array(
				'email'      => '',
				'first_name' => '',
				'last_name'  => '',
			);
		}

		return array(
			'email'      => get_bloginfo( 'admin_email' ),
			'first_name' => $admin->first_name,
			'last_name'  => $admin->last_name,
		);
	}
}