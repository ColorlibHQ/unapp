<?php
/**
 * Epsilon Tracking
 *
 * @package Epsilon Framework
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Tracking
 */
abstract class Epsilon_Tracking {
	/**
	 * Server data
	 *
	 * @var array
	 */
	public $data = array();

	/**
	 * Epsilon_Server_Tracking constructor.
	 */
	public function __construct() {
		$this->data = $this->generate_data();
	}

	/**
	 * Generate the data array
	 *
	 * @return array
	 */
	public function generate_data() {
		return array();
	}
}