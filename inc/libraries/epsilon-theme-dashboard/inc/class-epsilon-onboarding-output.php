<?php
/**
 * Epsilon Onboarding
 *
 * @package Epsilon Framework
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Onboarding_Output
 */
class Epsilon_Onboarding_Output {
	/**
	 * Recommended plugins
	 *
	 * @var array
	 */
	public $plugins = array();
	/**
	 * Steps
	 *
	 * @var array
	 */
	public $steps = array();
	/**
	 * Theme
	 *
	 * @var array
	 */
	public $theme = array();

	/**
	 * Epsilon_Onboarding constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		foreach ( $args as $k => $v ) {

			if ( ! in_array(
				$k,
				array(
					'steps',
					'plugins',
					'theme',
				)
			)
			) {
				continue;
			}

			$this->$k = $v;
		}
		/**
		 * Create the dashboard page
		 */
		add_action( 'admin_menu', array( $this, 'onboarding_menu' ) );

		/**
		 * Load the welcome screen styles and scripts
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Onboarding menu
	 */
	public function onboarding_menu() {
		$func = 'add' . '_' . 'submenu' . '_' . 'page';

		$func(
			null,
			'epsilon-onboarding',
			__( 'Onboarding', 'epsilon-framework' ),
			'edit_theme_options',
			'epsilon-onboarding',
			array(
				$this,
				'render_onboarding',
			)
		);
	}

	/**
	 * Enqueue function
	 */
	public function enqueue() {
		wp_enqueue_style( 'plugin-install' );
		wp_enqueue_script( 'plugin-install' );
		wp_enqueue_script( 'updates' );

		wp_enqueue_style(
			'epsilon-onboarding',
			get_template_directory_uri() . '/inc/libraries/epsilon-theme-dashboard/assets/css/onboarding.css'

		);
		wp_enqueue_script(
			'epsilon-onboarding',
			get_template_directory_uri() . '/inc/libraries/epsilon-theme-dashboard/assets/js/epsilon-onboarding.js',
			array( 'jquery' ),
			false,
			true
		);

		/**
		 * Use the localize script to send data from the backend to our app
		 */
		wp_localize_script( 'epsilon-onboarding', 'EpsilonOnboarding', $this->epsilon_onboarding_setup() );
	}

	/**
	 * Filter the on boarding setup so we can access it in theme
	 *
	 * @return mixed
	 */
	public function epsilon_onboarding_setup() {
		return apply_filters(
			'epsilon-onboarding-setup',
			array(
				/**
				 * App entry point
				 */
				'entrypoint'   => 'onboarding',
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
				 * Onboarding steps
				 */
				'steps'        => $this->steps,
				/**
				 * Plugins that should be installed
				 */
				'plugins'      => $this->plugins,
			)
		);
	}

	/**
	 * Render onboarding
	 */
	public function render_onboarding() {
		echo '<div id="epsilon-onboarding-app"></div>';
	}
}
