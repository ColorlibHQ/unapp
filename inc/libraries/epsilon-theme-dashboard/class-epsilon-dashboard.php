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
 * Entry point
 *
 * Class Epsilon_Dashboard
 */
class Epsilon_Dashboard {
	/**
	 * Recommended actions
	 *
	 * @var array
	 */
	public $actions = array();
	/**
	 * Recommended plugins
	 *
	 * @var array
	 */
	public $plugins = array();
	/**
	 * Demos that can be imported
	 *
	 * @var array
	 */
	public $demos = array();
	/**
	 * Tabs created by the dashboard
	 *
	 * @var array tabs
	 */
	public $tabs = array();
	/**
	 * Onboarding steps
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
	 * Does the theme support "onboarding" ?
	 *
	 * @var bool
	 */
	protected $onboarding = true;
	/**
	 * Tracking
	 *
	 * @var bool
	 */
	protected $tracking = '';

	/**
	 * Class constructor
	 *
	 * Epsilon_Dashboard constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		/**
		 * Basic constructor
		 */
		foreach ( $args as $k => $v ) {

			if ( ! in_array(
				$k,
				array(
					/**
					 * Actions
					 */
					'actions',
					/**
					 * Plugins
					 */
					'plugins',
					/**
					 * Demos ( CAN BE MULTIPLE )
					 */
					'demos',
					/**
					 * Theme array
					 */
					'theme',
					/**
					 * Do we support onboarding ?
					 */
					'onboarding',
					/**
					 * Onboarding steps
					 */
					'steps',
					/**
					 * How many tabs do we have ?
					 */
					'tabs',
					/**
					 * Do we allow tracking ?
					 */
					'tracking'
				)
			)
			) {
				continue;
			}

			$this->$k = $v;
		}

		$theme = wp_get_theme();

		$this->theme = wp_parse_args(
			$this->theme,
			array(
				'theme-name'    => $theme->get( 'Name' ),
				'theme-slug'    => $theme->get( 'TextDomain' ),
				'theme-version' => $theme->get( 'Version' ),
				'download-id'   => null,
			)
		);

		/**
		 * Initiate the dashboard
		 */
		$this->init_dashboard();
		/**
		 * Do we have on boarding enabled?
		 */
		if ( $this->onboarding ) {
			$this->init_onboarding();
		}
		/**
		 * Init ajax controller
		 */
		$this->init_ajax();
		/**
		 * Initiate theme updater
		 */
		$this->init_updater();
		/**
		 * Initiate customer tracking
		 */
		$this->init_tracking();
		
		/**
		 * Initiate uninstall feedback
		 * $this->init_uninstall_feedback();
		 */
	}

	/**
	 * Instance creator
	 *
	 * @param array $args
	 *
	 * @return Epsilon_Dashboard
	 */
	public static function get_instance( $args = array() ) {
		static $inst;
		if ( ! $inst ) {
			$inst = new Epsilon_Dashboard( $args );
		}

		return $inst;
	}

	/**
	 * Check if we have a valid license and if so, initiate the updater class
	 */
	public function init_updater() {
		/**
		 * In case we don`t have a valid license, return here
		 */
		$licensing = get_option( $this->theme['theme-slug'] . '_license_object', array() );
		if ( empty( $licensing ) ) {
			return;
		}

		/**
		 * Is it valid?
		 */
		if ( empty( $licensing['licenseStatus'] ) || 'valid' !== $licensing['licenseStatus'] ) {
			return;
		}

		/**
		 * If we get here, we can instantiate the updater class
		 */
		$arr = array(
			'license' => $licensing['licenseStatus'],
		);

		new Epsilon_Updater_Class( $arr );
	}

	/**
	 * Init Ajax Constructor
	 */
	public function init_ajax() {
		new Epsilon_Dashboard_Ajax();
	}

	/**
	 * Init the dashboard
	 */
	public function init_dashboard() {
		new Epsilon_Dashboard_Output(
			array(
				'theme'   => $this->theme,
				'actions' => $this->actions,
				'tabs'    => $this->tabs,
				'plugins' => $this->plugins,
			)
		);
	}

	/**
	 * Start on boarding process
	 */
	public function init_onboarding() {
		$used_onboarding = get_theme_mod( get_stylesheet() . '_used_onboarding', false );
		if ( ! empty( $_GET ) && isset( $_GET['page'] ) && 'epsilon-onboarding' === $_GET['page'] ) {

			if ( $used_onboarding ) {
				wp_redirect( esc_url( admin_url() . '/themes.php?page=' . $this->theme['theme-slug'] . '-dashboard' ) );
				exit();
			}

			new Epsilon_Onboarding_Output(
				array(
					'theme'   => $this->theme,
					'plugins' => $this->plugins,
					'actions' => $this->actions,
					'steps'   => $this->steps,
				)
			);
		}
	}

	/**
	 * Initiate customer tracking ( CAN BE TOGGLED OFF )
	 */
	public function init_tracking() {
		Epsilon_Customer_Tracking::get_instance(
			array(
				'tracking_option' => $this->tracking,
			)
		);
	}

	/**
	 * If user navigates to themes repo, show him a notice
	 */
	public function init_uninstall_feedback() {
		global $pagenow;

		if ( 'themes.php' === $pagenow ) {
			new Epsilon_Uninstall_Feedback( $this->theme );
		}
	}
}
