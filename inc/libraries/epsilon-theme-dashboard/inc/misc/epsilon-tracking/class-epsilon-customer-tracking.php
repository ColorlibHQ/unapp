<?php
/**
 * Epsilon Customer Tracking
 *
 * @package Epsilon Framework
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Customer_Tracking
 */
class Epsilon_Customer_Tracking {
	/**
	 * Allow tracking? Can be "checked off" through the dashboard
	 *
	 * @var bool
	 */
	private $allowed = true;
	/**
	 * Tracking option ( comes from dashboard )
	 *
	 * @var string
	 */
	protected $tracking_option = '';
	/**
	 * URL where we send data
	 *
	 * @var string
	 */
	private $url = 'https://www.tamewp.com/';
	/**
	 * Data array
	 *
	 * @var array
	 */
	private $data = array(
		/**
		 * Epsilon customer tracking
		 */
		'epsilon-customer-tracking' => false,
		/**
		 * Server data
		 */
		'server'                    => array(),
		/**
		 * WordPress data
		 */
		'wordpress'                 => array(),
		/**
		 * User data
		 */
		'user'                      => array(),
		/**
		 * Behavior data
		 */
		'behavior'                  => array(),
	);

	/**
	 * Epsilon_Tracking constructor.
	 *
	 * @param $args array
	 *
	 * @return boolean
	 */
	public function __construct( $args = array() ) {
		$this->tracking_option = $args['tracking_option'];

		$this->allowed_tracking();

		if ( isset( $args['url'] ) ) {
			$this->url = $args['url'];
		}

		if ( $this->allowed ) {
			$this->collect_data();
		}

		$user = array_filter( $this->data['user'] );
		if ( empty( $user ) ) {
			return false;
		}

		$this->handle_data();

		return true;
	}

	/**
	 * Creates an instance of the customer tracking service
	 *
	 * @param array $args
	 *
	 * @return Epsilon_Customer_Tracking
	 */
	public static function get_instance( $args = array() ) {
		static $inst;
		if ( ! $inst ) {
			$inst = new Epsilon_Customer_Tracking( $args );
		}

		return $inst;
	}

	/**
	 * Let's see if we're allowed to track user data
	 */
	public function allowed_tracking() {
		$allowed = get_option( $this->tracking_option, false );

		if ( in_array( $allowed, array( true, 1, '1' ) ) ) {
			$this->allowed = true;
		}

		if ( in_array( $allowed, array( false, 0, '0' ) ) ) {
			$this->allowed = false;
		}
	}

	/**
	 * Collect data
	 */
	private function collect_data() {
		foreach ( $this->data as $key => $arr ) {
			$class = 'Epsilon_' . $key . '_Tracking';
			if ( class_exists( $class ) ) {
				$tracking           = new $class();
				$this->data[ $key ] = $tracking->data;
			}
		}
	}

	/**
	 * Handles data, and sends it to our server
	 */
	private function handle_data() {
		new Epsilon_Request( $this->url, $this->data );
	}
}
