<?php
/**
 * Epsilon Server Tracking
 *
 * @package Epsilon Framework
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Server_Tracking
 */
class Epsilon_Server_Tracking extends Epsilon_Tracking {
	/**
	 * Generate the data array
	 *
	 * @return array
	 */
	public function generate_data() {
		return array(
			'url'         => home_url(),
			'php_version' => phpversion(),
			'server'      => isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : '',
		);
	}
}