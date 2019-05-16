<?php
/**
 * Epsilon Theme Uninstall Feedback Class
 *
 * @package Epsilon Framework
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Uninstall_Feedback
 */
class Epsilon_Uninstall_Feedback {
	/**
	 * @var array
	 */
	public $theme = array();

	/**
	 * Epsilon_Uninstall_Feedback constructor.
	 *
	 * @param array $theme
	 */
	public function __construct( $theme = array() ) {
		$this->theme = $theme;
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'admin_footer', array( $this, 'create_app_container' ) );
	}

	/**
	 * Enqueue styles and scripts
	 */
	public function enqueue() {
		wp_enqueue_style(
			'epsilon-feedback',
			get_template_directory_uri() . '/inc/libraries/epsilon-theme-dashboard/assets/css/feedback.css'

		);
		wp_enqueue_script(
			'epsilon-feedback',
			get_template_directory_uri() . '/inc/libraries/epsilon-theme-dashboard/assets/js/epsilon-feedback.js',
			array(),
			false,
			true
		);

		/**
		 * Use the localize script to send data from the backend to our app
		 */
		wp_localize_script( 'epsilon-feedback', 'EpsilonFeedback', $this->epsilon_feedback_setup() );
	}

	/**
	 * Filter the dashboard setup so we can access it in theme
	 *
	 * @return mixed
	 */
	public function epsilon_feedback_setup() {
		return apply_filters(
			'epsilon_feedback_setup',
			array(
				/**
				 * App entry point
				 */
				'entrypoint'   => 'feedback',
				/**
				 * Security nonce
				 */
				'ajax_nonce'   => wp_create_nonce( 'epsilon_dashboard_nonce' ),
				/**
				 * Admin url
				 */
				'adminUrl'     => get_admin_url(),
				/**
				 * Theme variables and usual translations
				 */
				'theme'        => Epsilon_Dashboard_Translations::get_theme_array( $this->theme ),
				'translations' => Epsilon_Dashboard_Translations::get_usual_strings(),
				/**
				 * Should this be visible?
				 */
				'visibility'   => $this->modal_visibility(),
				/**
				 * Options
				 */
				'options'      => array(
					array(
						'type'  => 'radio',
						'value' => 'options',
						'id'    => 'feedback-options',
						'label' => esc_html__( 'It lacks options.', 'epsilon-framework' ),
					),
					array(
						'type'  => 'radio',
						'value' => 'design',
						'id'    => 'feedback-design',
						'label' => esc_html__( 'I want to try a new design, I do not like this theme style.', 'epsilon-framework' ),
					),
					array(
						'type'  => 'radio',
						'value' => 'plugin',
						'id'    => 'feedback-plugin',
						'label' => esc_html__( 'Is not working with a plugin that I need.', 'epsilon-framework' ),
					),
					array(
						'type'  => 'radio',
						'value' => 'other',
						'id'    => 'feedback-other',
						'label' => esc_html__( 'Other', 'epsilon-framework' ),
					),
					array(
						'type'  => 'textarea',
						'value' => '',
						'id'    => 'feedback-other-text',
						'label' => esc_html__( 'Specify reason', 'epsilon-framework' ),
					)
				),
			)
		);
	}

	/**
	 * Echoes a div container
	 */
	public function create_app_container() {
		echo '<div id="epsilon-feedback-app"></div>';
	}

	/**
	 * Modal visibility
	 *
	 * @return bool
	 */
	public function modal_visibility() {
		if ( get_user_meta( get_current_user_id(), 'epsilon-framework-uninstall-feedback-' . $this->theme['theme-slug'], true ) ) {
			return 'false';
		}

		return 'true';
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public static function set_feedback_visibility( $args = array() ) {
		$theme = wp_get_theme();
		add_user_meta( get_current_user_id(), 'epsilon-framework-uninstall-feedback-' . $theme->get( 'TextDomain' ), 'true', true );

		return array(
			'status'  => false,
			'message' => 'ok',
		);
	}

	/**
	 * Sends the data to our tracking server
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function send_data( $args = array() ) {
		$user      = new Epsilon_User_Tracking();
		$server    = new Epsilon_Server_Tracking();
		$wordpress = new Epsilon_Wordpress_Tracking();
		$wordpress = $wordpress->generate_data();

		$wordpressData = array(
			'theme_name'    => $wordpress['themes']['active']['name'],
			'theme_slug'    => $wordpress['themes']['active']['slug'],
			'theme_version' => $wordpress['themes']['active']['version'],
			'theme_author'  => $wordpress['themes']['active']['author'],
			'wp_version'    => $wordpress['wp_version'],
			'multisite'     => $wordpress['multisite'],
			'language'      => $wordpress['locale'],
		);

		$body = call_user_func(
			'array_merge',
			array(),
			$user->generate_data(),
			$server->generate_data(),
			$wordpressData,
			array(
				'uninstall_reason'  => $args['reason'],
				'uninstall_details' => $args['otherReason'],
			)
		);

		/**
		 * Clean up crew
		 */
		unset( $server );
		unset( $wordpress );
		unset( $user );
		unset( $wordpressData );

		$body['customer_id'] = md5( $body['url'] . '-' . $body['email'] );

		$result = Epsilon_Request::send_manual_request(
			array(
				'url'  => 'http://tamewp.com/wp-json/epsilon/v1/add-uninstall-theme-data',
				'body' => $body,
			)
		);

		self::set_feedback_visibility();

		if ( ! is_wp_error( $result ) ) {
			return array(
				'status'  => true,
				'message' => 'ok',
			);
		}

		return array(
			'status'  => false,
			'message' => 'nok',
		);
	}
}
