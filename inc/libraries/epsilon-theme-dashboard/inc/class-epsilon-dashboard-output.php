<?php
/**
 * Epsilon Backend Page
 *
 * @package Epsilon Framework
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Dashboard_Output
 */
class Epsilon_Dashboard_Output {
	/**
	 * @var array
	 */
	protected $theme = array();
	/**
	 * @var array
	 */
	protected $actions = array();
	/**
	 * @var array
	 */
	protected $plugins = array();
	/**
	 * @var array
	 */
	protected $tabs = array();

	/**
	 * Epsilon_Dashboard_Output constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		foreach ( $args as $k => $v ) {

			if ( ! in_array(
				$k,
				array(
					'theme',
					'actions',
					'tabs',
					'plugins',
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
		add_action( 'admin_menu', array( $this, 'dashboard_menu' ) );

		if ( ! empty( $_GET ) && isset( $_GET['page'] ) && $this->theme['theme-slug'] . '-dashboard' === $_GET['page'] ) {
			/**
			 * Admin enqueue script
			 */
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		}
	}

	public function dashboard_menu() {
		/* Translators: Menu Title */
		$title = sprintf( esc_html__( 'About %1$s', 'epsilon-framework' ), esc_html( $this->theme['theme-name'] ) );

		if ( 0 < count( $this->actions ) ) {
			$title .= '<span class="badge-action-count">' . absint( count( $this->actions ) ) . '</span>';
		}

		add_theme_page(
			$this->theme['theme-name'],
			$title,
			'edit_theme_options',
			$this->theme['theme-slug'] . '-dashboard',
			array(
				$this,
				'render_app_container',
			)
		);
	}

	/**
	 * Enqueue styles and scripts
	 */
	public function enqueue() {
		wp_enqueue_style(
			'epsilon-dashboard',
			get_template_directory_uri() . '/inc/libraries/epsilon-theme-dashboard/assets/css/dashboard.css'

		);
		wp_enqueue_script(
			'epsilon-dashboard',
			get_template_directory_uri() . '/inc/libraries/epsilon-theme-dashboard/assets/js/epsilon-dashboard.js',
			array(),
			false,
			true
		);

		wp_enqueue_style( 'plugin-install' );
		wp_enqueue_script( 'plugin-install' );
		wp_enqueue_script( 'updates' );

		/**
		 * Use the localize script to send data from the backend to our app
		 */
		wp_localize_script( 'epsilon-dashboard', 'EpsilonDashboard', $this->epsilon_dashboard_setup() );
	}

	/**
	 * Filter the dashboard setup so we can access it in theme
	 *
	 * @return mixed
	 */
	public function epsilon_dashboard_setup() {
		return apply_filters(
			'epsilon-dashboard-setup',
			array(
				/**
				 * App entry point
				 */
				'entrypoint'   => 'dashboard',
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
				 * Dashboard plugins, actions and tabs
				 */
				'plugins'      => $this->plugins,
				'actions'      => $this->actions,
				'tabs'         => $this->tabs,
				/**
				 * Setting up a default tab
				 */
				'activeTab'    => 0,
			)
		);
	}

	/**
	 * Render the app's container
	 */
	public function render_app_container() {
		echo '<div id="epsilon-dashboard-app"></div>';
	}
}
