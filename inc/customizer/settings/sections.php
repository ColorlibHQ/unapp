<?php
/**
 * MedZone_Lite Theme Customizer Panels & Sections
 *
 * @package MedZone_Lite
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Register customizer panels
 */
$panels = array(
	/**
	 * General panel
	 */
	array(
		'id'   => 'unapp_panel_general',
		'args' => array(
			'priority'       => 24,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'General options', 'unapp' ),
		),
	),
	/**
	 * Content Panel
	 */
	array(
		'id'   => 'unapp_panel_content',
		'args' => array(
			'priority'       => 27,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'type'           => 'epsilon-panel-regular',
			'title'          => esc_html__( 'Page sections', 'unapp' ),
		),
	),
	/**
	 * Color panel
	 */
	array(
		'id'   => 'unapp_panel_colors',
		'args' => array(
			'priority'       => 29,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Colors', 'unapp' ),
		),
	),
	array(
		'id'   => 'unapp_panel_section_content',
		'args' => array(
			'priority'       => 9999,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'type'           => 'epsilon-panel-regular',
			'title'          => esc_html__( 'Front Page Content', 'unapp' ),
			'panel'          => 'unapp_panel_content',
			'hidden'         => true,
		),
	),
);

/**
 * Register sections
 */
$sections            = array(
	/**
	 * General section
	 */
	array(
		'id'   => 'unapp_header_section',
		'args' => array(
			'title'    => esc_html__( 'Header', 'unapp' ),
			'panel'    => 'unapp_panel_general',
			'priority' => 1,
		),
	),
	array(
		'id'   => 'unapp_layout_section',
		'args' => array(
			'title'    => esc_html__( 'Layout & Typography', 'unapp' ),
			'panel'    => 'unapp_panel_general',
			'priority' => 3,
		),
	),
	array(
		'id'   => 'unapp_footer_section',
		'args' => array(
			'title'    => esc_html__( 'Footer', 'unapp' ),
			'panel'    => 'unapp_panel_general',
			'priority' => 50,
		),
	),

	/**
	 * Repeatable sections container
	 */
	array(
		'id'   => 'unapp_repeatable_section',
		'args' => array(
			'title'       => esc_html__( 'Page Sections', 'unapp' ),
			'description' => esc_html__( 'The `Page sections` area will allow you to build your content with the help of our predefined set of sections. You don\'t need to write a single line of code, just select the sections you want to display in the current page and fill in the required fields .', 'unapp' ),
			'priority'    => 0,
			'panel'       => 'unapp_panel_content',
		),
	),

	/**
	 * Theme Content Sections
	 */
	// Unapp Sliders
	array(
		'id'   => 'unapp_slides_section',
		'args' => array(
			'title'    => esc_html__( 'Slides', 'unapp' ),
			'panel'    => 'unapp_panel_section_content',
			'priority' => 5,
			'type'     => 'epsilon-section-doubled',
		),
	),
	// Services
	array(
		'id'    => 'unapp_services_section',
		'args'  => array(
			'title'     => esc_html__( 'Services', 'unapp' ),
			'panel'     => 'unapp_panel_section_content',
			'priority'  => 6,
			'type'      => 'epsilon-section-doubled',
		),
	),
	//Featured Left
	array(
		'id'    => 'unapp_features_section',
		'args'  => array(
			'title'     => esc_html__( 'Features', 'unapp' ),
			'panel'     => 'unapp_panel_section_content',
			'priority'  => 7,
			'type'      => 'epsilon-section-doubled'
		),
	),
	// Unapp Counter
	array(
		'id'    => 'unapp_counter_section',
		'args'  => array(
			'title'     => esc_html__( 'Counter', 'unapp' ),
			'panel'     => 'unapp_panel_section_content',
			'priority'  => 9,
			'type'      => 'epsilon-section-doubled'
		),
	),
	// Unapp Pricing
	array(
		'id'    => 'unapp_pricing_section',
		'args'  => array(
			'title'     => esc_html__( 'Pricing', 'unapp' ),
			'panel'     => 'unapp_panel_section_content',
			'priority'  => 10,
			'type'      => 'epsilon-section-doubled',
		),
	),
	// Unapp Team
	array(
		'id'   => 'unapp_team_members_section',
		'args' => array(
			'title'    => esc_html__( 'Team Members', 'unapp' ),
			'panel'    => 'unapp_panel_section_content',
			'priority' => 11,
			'type'     => 'epsilon-section-doubled',
		),
	),
	// Unapp Portfolio
	array(
		'id'   => 'unapp_portfolios_section',
		'args' => array(
			'title'    => esc_html__( 'Portfolios', 'unapp' ),
			'panel'    => 'unapp_panel_section_content',
			'priority' => 11,
			'type'     => 'epsilon-section-doubled',
		),
	),
);
$stylesheet          = get_stylesheet();
$visible_recommended = get_option( $stylesheet . '_recommended_actions', false );
if ( $visible_recommended ) {
	unset( $sections[0] );
}

$collection = array(
	'panel'   => $panels,
	'section' => $sections,
);

Epsilon_Customizer::add_multiple( $collection );

