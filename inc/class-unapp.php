<?php
/**
 * unapp Theme Framework
 *
 * @package unapp
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class unapp
 */
class Unapp {
	/**
	 * @var bool
	 */
	public $top_bar = false;

	/**
	 * unapp constructor.
	 *
	 * Theme specific actions and filters
	 *
	 * @param array $theme
	 */
	public function __construct( $theme = array() ) {
		$this->theme = $theme;

		$theme = wp_get_theme();
		$arr   = array(
			'theme-name'    => $theme->get( 'Name' ),
			'theme-slug'    => $theme->get( 'TextDomain' ),
			'theme-version' => $theme->get( 'Version' ),
		);

		$this->theme = wp_parse_args( $this->theme, $arr );
		/**
		 * If PHP Version is older than 5.3, we switch back to default theme
		 */
		add_action( 'admin_init', array( $this, 'php_version_check' ) );
		/**
		 * Init epsilon dashboard
		 */
		add_filter( 'epsilon-dashboard-setup', array( $this, 'epsilon_dashboard' ) );
		add_filter( 'epsilon-onboarding-setup', array( $this, 'epsilon_onboarding' ) );
        /**
         * Enqueue styles and scripts
         */
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueues' ) );
		/**
		 * Customizer enqueues & controls
		 */
		add_action( 'customize_register', array( $this, 'customize_register_init' ) );
		/**
		 * Declare content width
		 */
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 10 );

		add_action( 'wp_enqueue_scripts', array( $this, 'customizer_styles' ), 99 );
		/**
		 * Grab all class methods and initiate automatically
		 */
		$methods = get_class_methods( 'unapp' );
		foreach ( $methods as $method ) {
			if ( false !== strpos( $method, 'init_' ) ) {
				$this->$method();
			}
		}
	}


	/**
	 * unapp instance
	 *
	 * @param array $theme
	 *
	 * @return unapp
	 */
	public static function get_instance( $theme = array() ) {
		static $inst;
		if ( ! $inst ) {
			$inst = new Unapp( $theme );
		}

		return $inst;
	}

	/**
	 * Check PHP Version and switch theme
	 */
	public function php_version_check() {
		if ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) {
			return true;
		}

		switch_theme( WP_DEFAULT_THEME );

		return false;
	}


	/**
	 * Initiate the epsilon framework
	 */
	public function init_epsilon() {
		new Epsilon_Framework();

		$this->start_typography_controls();

	}

	/**
	 *
	 */
	public function init_nav_menus() {
		//new Epsilon_Section_Navigation_Menu( 'unapp_frontpage_sections_' );
	}

	/**
	 * Initiate the setting helper
	 */
	public function customize_register_init() {
		new Unapp_Customizer();
	}

	/**
	 * Customizer styles ( from repeater )
	 */
	public function customizer_styles() {
		//new Epsilon_Section_Styling( 'unapp-main', 'unapp_frontpage_sections_', Unapp_Repeatable_Sections::get_instance() );
	}



	/**
	 * Loads the typography controls required scripts
	 */
	public function start_typography_controls() {
		/**
		 * Instantiate the Epsilon Typography object
		 */
		$options = array(
			'unapp_typography_headings',
			'unapp_paragraphs_typography',
		);

		$handler = 'unapp-main';
		Epsilon_Typography::get_instance( $options, $handler );
	}

	/**
	 * Initiate the welcome screen
	 */
	public function init_dashboard() {
		Epsilon_Dashboard::get_instance(
			array(
				'theme'    => array(
					'download-id' => '212499'
				),
				'tracking' => $this->theme['theme-slug'] . '_tracking_enable',
			)
		);

		$dashboard = Unapp_Dashboard_Setup::get_instance();
		$dashboard->add_admin_notice();

		$upsells = get_option( $this->theme['theme-slug'] . '_theme_upsells', false );
		if ( $upsells ) {
			add_filter( 'epsilon_upsell_control_display', '__return_false' );
		}
	}

	/**
	 * Separate setup from init
	 *
	 * @param array $setup
	 *
	 * @return array
	 */
	public function epsilon_dashboard( $setup = array() ) {
		$dashboard = new Unapp_Dashboard_Setup();

		$setup['actions'] = $dashboard->get_actions();
		$setup['tabs']    = $dashboard->get_tabs( $setup );
		$setup['plugins'] = $dashboard->get_plugins();
		$setup['privacy'] = $dashboard->get_privacy_options();

		$setup['edd'] = $dashboard->get_edd( $setup );

		$tab = get_user_meta( get_current_user_id(), 'epsilon_active_tab', true );

		$setup['activeTab'] = ! empty( $tab ) ? absint( $tab ) : 0;

		return $setup;
	}

	/**
	 * Add steps to onboarding
	 *
	 * @param array $setup
	 *
	 * @return array
	 */
	public function epsilon_onboarding( $setup = array() ) {
		$dashboard = new Unapp_Dashboard_Setup();

		$setup['steps']   = $dashboard->get_steps();
		$setup['plugins'] = $dashboard->get_plugins( true );
		$setup['privacy'] = $dashboard->get_privacy_options();

		return $setup;
	}

	public function enqueues(){
        
    }

	/**
	 * Content width
	 */
	public function content_width() {
		if ( ! isset( $GLOBALS['content_width'] ) ) {
			$GLOBALS['content_width'] = 600;
		}
	}
}
