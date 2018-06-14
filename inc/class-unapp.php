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
		//$this->start_color_schemes();
	}

	/**
	 *
	 */
	public function init_nav_menus() {
		new Epsilon_Section_Navigation_Menu( 'unapp_frontpage_sections_' );
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
		new Epsilon_Section_Styling( 'unapp-main', 'unapp_frontpage_sections_', Unapp_Repeatable_Sections::get_instance() );
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
					'default'     => '#0385D0',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_accent_color_second' => array(
					'label'       => esc_html__( 'Accent Color #2', 'unapp' ),
					'description' => esc_html__( 'The second main color.', 'unapp' ),
					'default'     => '#A1083A',
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
					'default'     => '#1a171c',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_text_color' => array(
					'label'       => esc_html__( 'Text Color', 'unapp' ),
					'description' => esc_html__( 'The color used for paragraphs.', 'unapp' ),
					'default'     => '#777777',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_link_color' => array(
					'label'       => esc_html__( 'Link Color', 'unapp' ),
					'description' => esc_html__( 'The color used for links.', 'unapp' ),
					'default'     => '#0385d0',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_link_hover_color' => array(
					'label'       => esc_html__( 'Link Hover Color', 'unapp' ),
					'description' => esc_html__( 'The color used for hovered links.', 'unapp' ),
					'default'     => '#a1083a',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_link_active_color' => array(
					'label'       => esc_html__( 'Link Active Color', 'unapp' ),
					'description' => esc_html__( 'The color used for active links.', 'unapp' ),
					'default'     => '#333333',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_menu_separator' => array(
					'label'     => esc_html__( 'Navigation Colors', 'unapp' ),
					'section'   => 'colors',
					'separator' => true,
				),

				'epsilon_header_background' => array(
					'label'       => esc_html__( 'Header background color', 'unapp' ),
					'description' => esc_html__( 'The color used for the header background.', 'unapp' ),
					'default'     => '#151C1F',
					'section'     => 'colors',
					'hover-state' => false,
				),
				
				'epsilon_dropdown_menu_background' => array(
					'label'       => esc_html__( 'Dropdown background', 'unapp' ),
					'description' => esc_html__( 'The color used for the menu background.', 'unapp' ),
					'default'     => '#A1083A',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_dropdown_menu_hover_background' => array(
					'label'       => esc_html__( 'Dropdown Hover background', 'unapp' ),
					'description' => esc_html__( 'The color used for the menu hover background.', 'unapp' ),
					'default'     => '#940534',
					'section'     => 'colors',
					'hover-state' => false,
				),
				
				'epsilon_menu_item_color' => array(
					'label'       => esc_html__( 'Menu item color', 'unapp' ),
					'description' => esc_html__( 'The color used for the menu item color.', 'unapp' ),
					'default'     => '#ebebeb',
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
					'default'     => '#0385D0',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_footer_separator' => array(
					'label'     => esc_html__( 'Footer Colors', 'unapp' ),
					'section'   => 'colors',
					'separator' => true,
				),

				'epsilon_footer_contact_background' => array(
					'label'       => esc_html__( 'Contact Background Color', 'unapp' ),
					'description' => esc_html__( 'The color used for the footer contact background.', 'unapp' ),
					'default'     => '#0377bb',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_footer_background' => array(
					'label'       => esc_html__( 'Background Color', 'unapp' ),
					'description' => esc_html__( 'The color used for the footer background.', 'unapp' ),
					'default'     => '#192229',
					'section'     => 'colors',
					'hover-state' => false,
				),
				
				'epsilon_footer_title_color' => array(
					'label'       => esc_html__( 'Title Color', 'unapp' ),
					'description' => esc_html__( 'The color used for the footer title color.', 'unapp' ),
					'default'     => '#ffffff',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_footer_text_color' => array(
					'label'       => esc_html__( 'Text Color', 'unapp' ),
					'description' => esc_html__( 'The color used for the footer text color.', 'unapp' ),
					'default'     => '#a9afb1',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_footer_link_color' => array(
					'label'       => esc_html__( 'Link Color', 'unapp' ),
					'description' => esc_html__( 'The color used for the footer text color.', 'unapp' ),
					'default'     => '#a9afb1',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_footer_link_hover_color' => array(
					'label'       => esc_html__( 'Link Hover Color', 'unapp' ),
					'description' => esc_html__( 'The color used for the footer text color.', 'unapp' ),
					'default'     => '#ffffff',
					'section'     => 'colors',
					'hover-state' => false,
				),

				'epsilon_footer_link_active_color' => array(
					'label'       => esc_html__( 'Link Active Color', 'unapp' ),
					'description' => esc_html__( 'The color used for the footer text color.', 'unapp' ),
					'default'     => '#a9afb1',
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
	 * Content width
	 */
	public function content_width() {
		if ( ! isset( $GLOBALS['content_width'] ) ) {
			$GLOBALS['content_width'] = 600;
		}
	}
}
