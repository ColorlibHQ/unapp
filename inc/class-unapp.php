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
		$this->start_color_schemes();
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
	 * Set color scheme controls
	 */
	 
	public function get_color_scheme() {

        return 	array(
            'epsilon_general_separator' => array(
                'label'     => esc_html__( 'Accent Colors', 'unapp' ),
                'section'   => 'colors',
                'separator' => true,
            ),

            'epsilon_accent_color' => array(
                'label'       => esc_html__( 'Accent Color #1', 'unapp' ),
                'description' => esc_html__( 'Theme main color.', 'unapp' ),
                'default'     => '#798eea',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_accent_color_second' => array(
                'label'       => esc_html__( 'Accent Color #2', 'unapp' ),
                'description' => esc_html__( 'The second main color.', 'unapp' ),
                'default'     => '#4aca85',
                'section'     => 'colors',
                'hover-state' => false,
            ),
            'epsilon_accent_color_third' => array(
                'label'       => esc_html__( 'Accent Color #3', 'unapp' ),
                'description' => esc_html__( 'The third main color.', 'unapp' ),
                'default'     => '#499bea',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_text_separator' => array(
                'label'     => esc_html__( 'Typography Colors', 'unapp' ),
                'section'   => 'colors',
                'separator' => true,
            ),

            'epsilon_title_color' => array(
                'label'       => esc_html__( 'Title Color', 'unapp' ),
                'description' => esc_html__( 'The color used for titles.', 'unapp' ),
                'default'     => '#303133',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_text_color' => array(
                'label'       => esc_html__( 'Text Color', 'unapp' ),
                'description' => esc_html__( 'The color used for paragraphs.', 'unapp' ),
                'default'     => '#808080',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_link_color' => array(
                'label'       => esc_html__( 'Link Color', 'unapp' ),
                'description' => esc_html__( 'The color used for links.', 'unapp' ),
                'default'     => '#4aca85',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_link_hover_color' => array(
                'label'       => esc_html__( 'Link Hover Color', 'unapp' ),
                'description' => esc_html__( 'The color used for hovered links.', 'unapp' ),
                'default'     => '#5ed092',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_menu_separator' => array(
                'label'     => esc_html__( 'Navigation Colors', 'unapp' ),
                'section'   => 'colors',
                'separator' => true,
            ),

            

            'epsilon_menu_item_color' => array(
                'label'       => esc_html__( 'Menu item color', 'unapp' ),
                'description' => esc_html__( 'The color used for the menu item color.', 'unapp' ),
                'default'     => '#ffffff',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_menu_item_hover_color' => array(
                'label'       => esc_html__( 'Menu item hover color', 'unapp' ),
                'description' => esc_html__( 'The color used for the menu item hover color.', 'unapp' ),
                'default'     => '#ffffff',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_menu_item_active_color' => array(
                'label'       => esc_html__( 'Menu item active color', 'unapp' ),
                'description' => esc_html__( 'The color used for the menu item active color.', 'unapp' ),
                'default'     => '#ffffff',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_dropdown_menu_background' => array(
                'label'       => esc_html__( 'Dropdown background', 'unapp' ),
                'description' => esc_html__( 'The color used for the menu background.', 'unapp' ),
                'default'     => '#000000',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_dropdown_menu_item_color' => array(
                'label'       => esc_html__( 'Dropdown menu item color', 'unapp' ),
                'description' => esc_html__( 'The color used for the menu item color.', 'unapp' ),
                'default'     => '#999999',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_dropdown_menu_item_hover_color' => array(
                'label'       => esc_html__( 'Dropdown menu item hover color', 'unapp' ),
                'description' => esc_html__( 'The color used for the menu item hover color.', 'unapp' ),
                'default'     => '#ffffff',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_dropdown_menu_item_active_color' => array(
                'label'       => esc_html__( 'Dropdown menu item active color', 'unapp' ),
                'description' => esc_html__( 'The color used for the menu item active color.', 'unapp' ),
                'default'     => '#ffffff',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_footer_separator' => array(
                'label'     => esc_html__( 'Footer Colors', 'unapp' ),
                'section'   => 'colors',
                'separator' => true,
            ),

            'epsilon_footer_contact_background' => array(
                'label'       => esc_html__( 'Footer Widget Background', 'unapp' ),
                'description' => esc_html__( 'The color used for the footer widget background.', 'unapp' ),
                'default'     => '#303133',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_footer_background' => array(
                'label'       => esc_html__( 'Footer Copyright Background', 'unapp' ),
                'description' => esc_html__( 'The color used for the footer copyright background.', 'unapp' ),
                'default'     => '#262626',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_footer_title_color' => array(
                'label'       => esc_html__( 'Footer Title Color', 'unapp' ),
                'description' => esc_html__( 'The color used for the footer widget title.', 'unapp' ),
                'default'     => '#e6e6e6',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_footer_text_color' => array(
                'label'       => esc_html__( 'Text Color', 'unapp' ),
                'description' => esc_html__( 'The color used for the footer text.', 'unapp' ),
                'default'     => '#808080',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_footer_link_color' => array(
                'label'       => esc_html__( 'Link Color', 'unapp' ),
                'description' => esc_html__( 'The color used for the footer link.', 'unapp' ),
                'default'     => '#4aca85',
                'section'     => 'colors',
                'hover-state' => false,
            ),

            'epsilon_footer_link_hover_color' => array(
                'label'       => esc_html__( 'Link Hover Color', 'unapp' ),
                'description' => esc_html__( 'The color used for the footer link hover.', 'unapp' ),
                'default'     => '#5ed092',
                'section'     => 'colors',
                'hover-state' => false,
            ),

        );
	}
	
	/**
	 * Load color scheme controls
	 */
	private function start_color_schemes() {
		$handler = 'unapp-style-overrides';

		$args = array(
			'fields' => $this->get_color_scheme(),
			'css' => Epsilon_Color_Scheme::load_css_overrides( get_template_directory() . '/assets/css/style-overrides.css' ),
		);

		Epsilon_Color_Scheme::get_instance( $handler, $args );
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
        wp_enqueue_style( 'unapp-style-overrides', get_template_directory_uri() . '/assets/css/overrides.css' );
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
