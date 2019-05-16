<?php
/**
 * Epsilon Request
 *
 * @package Epsilon Framework
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Epsilon_Request {
	/**
	 * Url for the request
	 *
	 * @var string
	 */
	private $url = '';
	/**
	 * Api endpoint
	 *
	 * @var string
	 */
	private $endpoint = 'wp-json/epsilon/v1/add-tracking-data';
	/**
	 * Private data
	 *
	 * @var array
	 */
	private $data = array();
	/**
	 * Create a unique key so we know what to update
	 *
	 * @var string
	 */
	private $unique_key = '';

	/**
	 * Epsilon_Request constructor.
	 *
	 * @param string $url
	 * @param array  $data
	 */
	public function __construct( $url = 'https://tamewp.com/', $data = array() ) {
		$this->url  = $url;
		$this->data = $data;

		$this->generate_url();
		$this->generate_unique_key();

		$this->_schedule_send();
	}

	/**
	 * Generate the url
	 */
	protected function generate_unique_key() {
		$url   = $this->data['server']['url'];
		$email = $this->data['user']['email'];

		$this->data['unique']        = md5( $url . '-' . $email );
		$this->data['first_request'] = strtotime( 'now' );

		$this->unique_key = $this->data['unique'];
	}

	/**
	 * Generate the url
	 */
	protected function generate_url() {
		$this->url = $this->url . $this->endpoint;
	}

	/**
	 * Check database, we need to know if we init the sending now or post pone it
	 */
	private function _schedule_send() {
		$last = $this->_last_request();
		/**
		 * In case no request has been made, do it now
		 */
		if ( ! $last ) {
			$this->_do_it();

			return;
		}

		/**
		 * Get last request, and format it properly
		 */
		$last_request = DateTime::createFromFormat( 'U', $last );
		/**
		 * Get today date
		 */
		$today = new DateTime( 'today' );
		/**
		 * Check how many days passed since the last request
		 */
		$interval = $today->diff( $last_request )->format( '%d' );
		/**
		 * If 1 day or more passed, ping our server
		 */
		if ( 1 <= absint( $interval ) ) {
			$this->_do_it();
		}
	}

	/**
	 * Make the request
	 *
	 * @return WP_Error | boolean
	 */
	private function _do_it() {
		$request = wp_remote_post( $this->url, array(
			'method'      => 'POST',
			'timeout'     => 20,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'body'        => $this->data,
			'user-agent'  => 'MT/EPSILON-CUSTOMER-TRACKING/' . esc_url( home_url() )
		) );

		if ( is_wp_error( $request ) ) {
			return $request;
		}

		$this->_save_request_time();

		return true;
	}

	/**
	 * Get the last request time
	 */
	private function _last_request() {
		return get_option( 'epsilon_customer_tracking_last_request', false );
	}

	/**
	 * Save the current request time
	 */
	private function _save_request_time() {
		update_option( 'epsilon_customer_tracking_last_request', time() );
	}

	/**
	 * @param array $args
	 *
	 * @return array|bool|WP_Error
	 */
	public static function send_manual_request( $args = array() ) {

		$request = wp_remote_post( $args['url'], array(
			'method'      => 'POST',
			'timeout'     => 20,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'body'        => $args['body'],
			'user-agent'  => 'MT/EPSILON-CUSTOMER-TRACKING/' . esc_url( home_url() )
		) );

		if ( is_wp_error( $request ) ) {
			return $request;
		}

		return true;
	}
}
