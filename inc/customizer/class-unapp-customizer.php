<?php
/**
 * Unapp Theme Customizer settings
 *
 * @package Unapp
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Unapp_Customizer
 */
class Unapp_Customizer {

	/**
	 * The basic constructor of the helper
	 * It changes the default panels of the customizer
	 *
	 * Unapp_Customizer_Helper constructor.
	 */
	public function __construct() {
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_enqueue_scripts' ) );
		add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
		/**
		 * Customizer enqueues & controls
		 */
		add_action( 'customize_register', array( $this, 'add_theme_options' ), 99 );
		$this->change_default_panels();
	}

	/**
	 * Loads the settings for the panels
	 */
	public function add_theme_options() {
		$path = get_template_directory() . '/inc/customizer/settings';

		require_once $path . '/sections.php';
		require_once $path . '/fields.php';
	}

	/**
	 * Runs on initialization, changes the default panels to the Theme options
	 */
	public function change_default_panels() {
		global $wp_customize;

		/**
		 * Change transports
		 */
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		$wp_customize->get_setting( 'custom_logo' )->transport     = 'refresh';

		/**
		 * Change panels
		 */
		$wp_customize->get_section( 'header_image' )->panel      = 'unapp_panel_general';
		$wp_customize->get_section( 'background_image' )->panel  = 'unapp_panel_general';
		//$wp_customize->get_section( 'colors' )->panel            = 'unapp_panel_general';
		$wp_customize->get_section( 'title_tagline' )->panel     = 'unapp_panel_general';
		$wp_customize->get_section( 'static_front_page' )->panel = 'unapp_panel_content';

		/**
		 * Change priorities
		 */
		$wp_customize->get_section( 'title_tagline' )->priority     = 0;
		$wp_customize->get_control( 'custom_logo' )->priority       = 0;
		$wp_customize->get_control( 'blogname' )->priority          = 2;
		$wp_customize->get_section( 'header_image' )->priority      = 4;
		$wp_customize->get_control( 'blogdescription' )->priority   = 17;
		$wp_customize->get_control( 'header_textcolor' )->priority  = 15;
		$wp_customize->get_section( 'static_front_page' )->priority = 0;
		/**
		 * Change labels
		 */
		$wp_customize->get_control( 'custom_logo' )->description   = esc_html__( 'The image logo, if set, will override the text logo. You can not have both at the same time. A tagline can be displayed under the text logo.', 'unapp' );
		$wp_customize->get_section( 'header_image' )->title        = esc_html__( 'Page Header', 'unapp' );
		$wp_customize->get_control( 'page_on_front' )->description = esc_html__( 'If you have front-end sections, those will be displayed instead. Consider adding a "Content Section" if you need to display the page content as well.', 'unapp' );

		if ( ! isset( $wp_customize->selective_refresh ) ) {
			return;
		}

		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title',
			'render_callback' => function () {
				bloginfo( 'name' );
			},
		) );

		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => function () {
				bloginfo( 'description' );
			},
		) );
	}

	/**
	 * Our Customizer script
	 *
	 * Dependencies: Customizer Controls script (core)
	 */
	public function customizer_enqueue_scripts() {
		wp_enqueue_script( 'unapp-customizer-scripts', get_template_directory_uri() . '/inc/customizer/assets/js/customizer.js', array( 'customize-controls' ) );

		wp_localize_script(
			'unapp-customizer-scripts',
			'unappCustomizer',
			array(
				'templateDirectory' => esc_url( get_template_directory_uri() ),
				'ajaxNonce'         => wp_create_nonce( 'unapp_nonce' ),
				'siteUrl'           => esc_url( get_site_url() ),
				'blogPage'          => esc_url( get_permalink( get_option( 'page_for_posts', false ) ) ),
				'frontPage'         => esc_url( get_permalink( get_option( 'page_on_front', false ) ) ),
			)
		);
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	public function customize_preview_js() {
		wp_enqueue_script( 'unapp-previewer', get_template_directory_uri() . '/inc/customizer/assets/js/previewer.js', array( 'customize-preview' ), '211215', true );
	}

	/**
	 * Active Callback for copyright
	 */
	public static function copyright_enabled_callback( $control ) {
		if ( $control->manager->get_setting( 'unapp_enable_copyright' )->value() == true ) {
			return true;
		}

		return false;
	}

	/**
	 * Active callback for unapp_header_top_bar
	 */
	public static function header_top_bar_enabled_callback( $control ) {
		if ( $control->manager->get_setting( 'unapp_header_top_bar' )->value() == true ) {
			return true;
		}

		return false;
	}
}
