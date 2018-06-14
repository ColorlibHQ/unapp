<?php
/**
 * unapp Theme Customizer Fields
 *
 * @package unapp
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Layout section options
 */
Epsilon_Customizer::add_field(
	'unapp_layout',
	array(
		'type'     => 'epsilon-layouts',
		'section'  => 'unapp_layout_section',
		'layouts'  => array(
			1 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/one-column.png',
			2 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/two-column.png',
		),
		'default'  => array(
			'columnsCount' => 2,
			'columns'      => array(
				1 => array(
					'index' => 1,
					'span'  => 8,
				),
				2 => array(
					'index' => 2,
					'span'  => 4,
				),
			),
		),
		'min_span' => 4,
		'fixed'    => true,
		'label'    => esc_html__( 'Blog Layout', 'unapp' ),
	)
);
Epsilon_Customizer::add_field(
	'unapp_page_layout',
	array(
		'type'     => 'epsilon-layouts',
		'section'  => 'unapp_layout_section',
		'layouts'  => array(
			1 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/one-column.png',
			2 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/two-column.png',
		),
		'default'  => array(
			'columnsCount' => 2,
			'columns'      => array(
				1 => array(
					'index' => 1,
					'span'  => 8,
				),
				2 => array(
					'index' => 2,
					'span'  => 4,
				),
			),
		),
		'min_span' => 4,
		'fixed'    => true,
		'label'    => esc_html__( 'Page Layout', 'unapp' ),
	)
);
/**
 * Typography section options
 */
Epsilon_Customizer::add_field(
	'unapp_typography_headings',
	array(
		'type'          => 'epsilon-typography',
		'transport'     => 'postMessage',
		'label'         => esc_html__( 'Headings', 'unapp' ),
		'section'       => 'unapp_layout_section',
		'description'   => esc_html__( 'Note: Current typography controls will only be affecting the blog.', 'unapp' ),
		'stylesheet'    => 'unapp-main',
		'choices'       => array(
			'font-family',
			'font-weight',
			'font-style',
			'letter-spacing',
		),
		'selectors'     => array(
			'.post-title',
			'.post-content h1',
			'.post-content h2',
			'.post-content h3',
			'.post-content h4',
			'.post-content h5',
			'.post-content h6',
		),
		'font_defaults' => array(
			'letter-spacing' => '0',
			'font-family'    => '',
			'font-weight'    => '',
			'font-style'     => '',
		),
	)
);
Epsilon_Customizer::add_field(
	'unapp_paragraphs_typography',
	array(
		'type'          => 'epsilon-typography',
		'transport'     => 'postMessage',
		'section'       => 'unapp_layout_section',
		'label'         => esc_html__( 'Paragraphs', 'unapp' ),
		'description'   => esc_html__( 'Note: Current typography controls will only be affecting the blog.', 'unapp' ),
		'stylesheet'    => 'unapp-main',
		'choices'       => array(
			'font-family',
			'font-weight',
			'font-style',
		),
		'selectors'     => array(
			'.post-content p',
		),
		'font_defaults' => array(
			'font-family' => '',
			'font-weight' => '',
			'font-style'  => '',
		),
	)
);

/**
 * Header section options
 */

Epsilon_Customizer::add_field(
	'unapp_header_columns',
	array(
		'type'            => 'epsilon-layouts',
		'section'         => 'unapp_header_section',
		'priority'        => 2,
		'layouts'         => array(
			1 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/one-column.png',
			2 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/two-column.png',
			3 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/three-column.png',
			4 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/four-column.png',
		),
		'default'         => array(
			'columnsCount' => 2,
			'columns'      => array(
				array(
					'index' => 1,
					'span'  => 6,
				),
				array(
					'index' => 2,
					'span'  => 6,
				),
			),
		),
		'min_span'        => 2,
		'label'           => esc_html__( 'Top Bar Columns', 'unapp' ),
		'active_callback' => array( 'Unapp_Customizer', 'header_top_bar_enabled_callback' ),
	)
);


/**
 * Copyright contents
 */
 
 Epsilon_Customizer::add_field(
	'unapp_enable_go_top',
	array(
		'type'        => 'epsilon-toggle',
		'label'       => esc_html__( 'Go to top button', 'unapp' ),
		'description' => esc_html__( 'Toggle the display of the go to top button.', 'unapp' ),
		'section'     => 'unapp_footer_section',
		'default'     => false,
	)
);
Epsilon_Customizer::add_field(
	'unapp_enable_footer_copyright',
	array(
		'type'        => 'epsilon-toggle',
		'label'       => esc_html__( 'Footer Copyright Section', 'unapp' ),
		'section'     => 'unapp_footer_section',
		'default'     => true,
	)
);

Epsilon_Customizer::add_field(
	'unapp_copyright_contents',
	array(
		'type'    => 'epsilon-text-editor',
		'default' => 'uanpp Themes - 2018. All rights reserved.',
		'label'   => esc_html__( 'Copyright Text', 'unapp' ),
		'section' => 'unapp_footer_section',
	)
);
/**
 * Theme Content
 */

/**
 * Slides
 */
Epsilon_Customizer::add_field(
	'unapp_slides',
	array(
		'type'         => 'epsilon-repeater',
		'section'      => 'unapp_slides_section',
		'save_as_meta' => Epsilon_Content_Backup::get_instance()->setting_page,
		'label'        => esc_html__( 'Slides', 'unapp' ),
		'button_label' => esc_html__( 'Add new slides', 'unapp' ),
		'row_label'    => array(
			'type'  => 'field',
			'field' => 'slides_title',
		),
		'fields'       => array(
			'slides_title'       => array(
				'label'             => esc_html__( 'Title', 'unapp' ),
				'type'              => 'text',
				'sanitize_callback' => 'wp_kses_post',
				'default'           => 'Growing your business',
			),
			'slides_image'       => array(
				'label'   => esc_html__( 'Portrait', 'unapp' ),
				'type'    => 'epsilon-image',
				'size'    => 'unapp-main-slider',
				'default' => esc_url( get_template_directory_uri() . '/assets/images/dashboard_full_1.jpg' ),
			),
		),
	)
);
/**
 * Services
 */

Epsilon_Customizer::add_field(
	'unapp_services',
	array(
		'type'         => 'epsilon-repeater',
		'section'      => 'unapp_services_section',
		'save_as_meta' => Epsilon_Content_Backup::get_instance()->setting_page,
		'label'        => esc_html__( 'Services', 'unapp' ),
		'button_label' => esc_html__( 'Add new service', 'unapp' ),
		'row_label'    => array(
			'type'  => 'field',
			'field' => 'service_title',
		),
		'fields'       => array(
			'service_title'       => array(
				'label'             => esc_html__( 'Title', 'unapp' ),
				'type'              => 'text',
				'sanitize_callback' => 'wp_kses_post',
				'default'           => 'Create your own template',
			),
			'service_desc' => array(
				'label'             => esc_html__( 'Description', 'unapp' ),
				'type'              => 'epsilon-text-editor',
				'sanitize_callback' => 'wp_kses_post',
				'default'           => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
			),
			'service_icon'        => array(
				'label'   => esc_html__( 'Icon', 'unapp' ),
				'type'    => 'epsilon-icon-picker',
				'default' => 'fa fa-users',
			),
		),
	)
);

/**
 * Page Services
 */

Epsilon_Customizer::add_field(
	'unapp_page_services',
	array(
		'type'         => 'epsilon-repeater',
		'section'      => 'unapp_page_services_section',
		'save_as_meta' => Epsilon_Content_Backup::get_instance()->setting_page,
		'label'        => esc_html__( 'Services', 'unapp' ),
		'button_label' => esc_html__( 'Add new service', 'unapp' ),
		'row_label'    => array(
			'type'  => 'field',
			'field' => 'service_title',
		),
		'fields'       => array(
			'service_page_title'       => array(
				'label'             => esc_html__( 'Title', 'unapp' ),
				'type'              => 'text',
				'sanitize_callback' => 'wp_kses_post',
				'default'           => 'Create your own template',
			),
			'service_page_desc' => array(
				'label'             => esc_html__( 'Description', 'unapp' ),
				'type'              => 'epsilon-text-editor',
				'sanitize_callback' => 'wp_kses_post',
				'default'           => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
			),
			'service_page_icon'        => array(
				'label'   => esc_html__( 'Icon', 'unapp' ),
				'type'    => 'epsilon-icon-picker',
				'default' => 'fa fa-users',
			),
		),
	)
);

/**
 * Featured Left
 */
Epsilon_Customizer::add_field(
	'unapp_featured_left',
	array(
		'type'          => 'epsilon-repeater',
		'section'       => 'unapp_featured_section_left',
		'save_as_meta'  => Epsilon_Content_Backup::get_instance()->setting_page,
		'label'         => esc_html__( 'Featured Work', 'unapp' ),
		'button_label'  => esc_html__( 'Add new items', 'unapp' ),
		'row_label'     => array(
			'type'  => 'field',
			'field' => 'featured_icon'
		),
		'fields' => array(
			'featured_icon'        => array(
				'label'   => esc_html__( 'Icon', 'unapp' ),
				'type'    => 'epsilon-icon-picker',
				'default' => 'fa fa-users',
			),
			'featured_description' => array(
				'label' => esc_html__( 'Description', 'unapp' ),
				'type' => 'epsilon-text-editor',
				'sanitize_callback' => 'wp_kses_post',
				'default' => 'Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.',
			),
		),
	)
);

/**
 * Featured Right
 */
Epsilon_Customizer::add_field(
	'unapp_featured_right',
	array(
		'type'          => 'epsilon-repeater',
		'section'       => 'unapp_featured_section_right',
		'save_as_meta'  => Epsilon_Content_Backup::get_instance()->setting_page,
		'label'         => esc_html__( 'Featured Work', 'unapp' ),
		'button_label'  => esc_html__( 'Add new items', 'unapp' ),
		'row_label'     => array(
			'type'  => 'field',
			'field' => 'featured_icon'
		),
		'fields' => array(
			'featured_icon'        => array(
				'label'   => esc_html__( 'Icon', 'unapp' ),
				'type'    => 'epsilon-icon-picker',
				'default' => 'fa fa-users',
			),
			'featured_description' => array(
				'label' => esc_html__( 'Description', 'unapp' ),
				'type' => 'epsilon-text-editor',
				'sanitize_callback' => 'wp_kses_post',
				'default' => 'Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.',
			),
		),
	)
);

/*
 * Unapp Counter
 */
Epsilon_Customizer::add_field(
	'unapp_counter',
	array(
		'type'          => 'epsilon-repeater',
		'section'       => 'unapp_counter_section',
		'save_as_meta'  => Epsilon_Content_Backup::get_instance()->setting_page,
		'label'         => esc_html__( 'Featured Work', 'unapp' ),
		'button_label'  => esc_html__( 'Add new items', 'unapp' ),
		'row_label'     => array(
			'type'  => 'field',
			'field' => 'counter_title'
		),
		'fields' => array(
			'counter_title'        => array(
				'label'             => esc_html__( 'Title', 'unapp' ),
				'type'              => 'text',
				'sanitize_callback' => 'wp_kses_post',
				'default'           => '1500',
			),
			'counter_description' => array(
				'label' => esc_html__( 'Description', 'unapp' ),
				'type' => 'epsilon-text-editor',
				'sanitize_callback' => 'wp_kses_post',
				'default' => 'Of customers are satisfied with our professional support',
			),
		)
	)
);

/**
 * Unapp Pricing
 */
Epsilon_Customizer::add_field(
	'unapp_price_boxes',
	array(
		'type'         => 'epsilon-repeater',
		'section'      => 'unapp_pricing_section',
		'save_as_meta' => Epsilon_Content_Backup::get_instance()->setting_page,
		'label'        => esc_html__( 'Price Boxes', 'unapp' ),
		'button_label' => esc_html__( 'Add new price box', 'unapp' ),
		'row_label'    => array(
			'type'  => 'field',
			'field' => 'price_box_title',
		),
		'fields'       => array(
			'price_box_title'    => array(
				'label'             => esc_html__( 'Name', 'unapp' ),
				'type'              => 'text',
				'default'           => esc_html__( 'Standard', 'unapp' ),
				'sanitize_callback' => 'wp_kses_post',
			),
			'price_box_currency' => array(
				'label'   => esc_html__( 'Currency', 'unapp' ),
				'type'    => 'text',
				'default' => '$',
			),
			'price_box_price'    => array(
				'label'   => esc_html__( 'Price', 'unapp' ),
				'type'    => 'text',
				'default' => '59',
			),
			'price_box_period'   => array(
				'label'   => esc_html__( 'Period', 'unapp' ),
				'type'    => 'text',
				'default' => 'month',
			),
			'price_btn_text'     => array(
				'label'             => esc_html__( 'Button Text', 'unapp' ),
				'type'              => 'text',
				'default'           => esc_html__( 'Get started', 'unapp' ),
				'sanitize_callback' => 'wp_kses_post',
			),
			'price_box_url'      => array(
				'label'             => esc_html__( 'Button URL', 'unapp' ),
				'type'              => 'text',
				'default'           => '#',
				'sanitize_callback' => 'wp_kses_post',
			),
			'price_box_features' => array(
				'label'             => esc_html__( 'Features', 'unapp' ),
				'type'              => 'epsilon-text-editor',
				'default'           => esc_html__( 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.', 'unapp' ),
				'sanitize_callback' => 'wp_kses_post',
			),
		),
	)
);

/**
 * Unapp Team Members
 */
Epsilon_Customizer::add_field(
	'unapp_team_members',
	array(
		'type'         => 'epsilon-repeater',
		'section'      => 'unapp_team_members_section',
		'save_as_meta' => Epsilon_Content_Backup::get_instance()->setting_page,
		'label'        => esc_html__( 'Team Members', 'unapp' ),
		'button_label' => esc_html__( 'Add new member', 'unapp' ),
		'row_label'    => array(
			'type'  => 'field',
			'field' => 'member_title',
		),
		'fields'       => array(
			'member_title'            => array(
				'label'             => esc_html__( 'Name', 'unapp' ),
				'type'              => 'text',
				'default'           => esc_html__( 'James Austin', 'unapp' ),
				'sanitize_callback' => 'wp_kses_post',
			),
			'member_designation'            => array(
				'label'             => esc_html__( 'Designation', 'unapp' ),
				'type'              => 'text',
				'default'           => esc_html__( 'Developer', 'unapp' ),
				'sanitize_callback' => 'wp_kses_post',
			),
			'member_text'             => array(
				'label'   => esc_html__( 'Text', 'unapp' ),
				'type'    => 'epsilon-text-editor',
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris lacinia velit quis sem dignissim porta.', 'unapp' ),
			),
			'member_image'            => array(
				'label'   => esc_html__( 'Portrait', 'unapp' ),
				'type'    => 'epsilon-image',
				'size'    => 'unapp-team-image',
				'default' => esc_url( get_template_directory_uri() . '/assets/images/person4.jpg' ),
			),
			'member_social_facebook'  => array(
				'label'   => esc_html__( 'Facebook', 'unapp' ),
				'type'    => 'url',
				'default' => 'https://facebook.com',
			),
			'member_social_twitter'   => array(
				'label'   => esc_html__( 'Twitter', 'unapp' ),
				'type'    => 'url',
				'default' => 'https://twitter.com',
			),
			'member_social_pinterest' => array(
				'label'   => esc_html__( 'Pinterest', 'unapp' ),
				'type'    => 'url',
				'default' => 'https://pinterest.com',
			),
			'member_social_linkedin'  => array(
				'label'   => esc_html__( 'LinkedIn', 'unapp' ),
				'type'    => 'url',
				'default' => 'https://linkedin.com',
			),
		),
	)
);

/**
 * Section builder page changer ( acts as a menu )
 */
Epsilon_Customizer::add_field(
	'unapp_page_changer',
	array(
		'type'     => 'epsilon-page-changer',
		'label'    => esc_html__( 'Available pages', 'unapp' ),
		'section'  => 'unapp_repeatable_section',
		'priority' => 0,
	)
);

Epsilon_Customizer::add_field(
	'unapp_logo_dimensions',
	array(
		'type'           => 'epsilon-image-dimensions',
		'label'          => esc_html__( 'Logo Dimensions', 'unapp' ),
		'linked_control' => 'custom_logo',
		'section'        => 'title_tagline',
		'priority'       => 1,
	)
);

/**
 * Repeatable sections
 */
Epsilon_Customizer::add_field(
	'unapp_frontpage_sections',
	array(
		'type'                => 'epsilon-section-repeater',
		'label'               => esc_html__( 'Sections', 'unapp' ),
		'section'             => 'unapp_repeatable_section',
		'page_builder'        => true,
		'selective_refresh'   => true,
		'transport'           => 'postMessage',
		'repeatable_sections' => Unapp_Repeatable_Sections::get_instance()->sections,
	)
);

/**
 * Color Schemes
 */
Epsilon_Customizer::add_field(
	'unapp_color_scheme',
	array(
		'label'       => esc_html__( 'Color scheme', 'unapp' ),
		'description' => esc_html__( 'Select a color scheme', 'unapp' ),
		'type'        => 'epsilon-color-scheme',
		'priority'    => 0,
		'default'     => 'primary',
		'section'     => 'colors',
		'transport'   => 'postMessage',
		'choices'     => array(
			array(
				'id'     => 'primary',
				'name'   => 'Primary',
				'colors' => array(
					// 'epsilon_general_separator'         		=> '',
					'epsilon_accent_color'              		=> '#0385d0',
					'epsilon_accent_color_second'       		=> '#a1083a',

					// 'epsilon_text_separator'            		=> '',
					'epsilon_title_color'               		=> '#1a171c',
					'epsilon_text_color'               			=> '#777777',
					'epsilon_link_color'                		=> '#0385d0',
					'epsilon_link_hover_color'          		=> '#a1083a',
					'epsilon_link_active_color'         		=> '#333333',

					// 'epsilon_menu_separator'            		=> '',
					'epsilon_header_background'           		=> '#151c1f',
					'epsilon_dropdown_menu_background'          => '#a1083a',
					'epsilon_dropdown_menu_hover_background'	=> '#940534',
					'epsilon_menu_item_color'    				=> '#ebebeb',
					'epsilon_menu_item_hover_color' 			=> '#ffffff',
					'epsilon_menu_item_active_color'        	=> '#0385d0',

					// 'epsilon_footer_separator'         			=> '',
					'epsilon_footer_contact_background'         => '#0377bb',
					'epsilon_footer_background'         		=> '#192229',
					'epsilon_footer_title_color'         		=> '#ffffff',
					'epsilon_footer_text_color'         		=> '#a9afb1',
					'epsilon_footer_link_color'         		=> '#a9afb1',
					'epsilon_footer_link_hover_color'         	=> '#ffffff',
					'epsilon_footer_link_active_color'         	=> '#a9afb1',
				),
			),
			array(
				'id'     => 'yellow',
				'name'   => 'Yellow',
				'colors' => array(
					// 'epsilon_general_separator'         		=> '',
					'epsilon_accent_color'              		=> '#FFC000',
					'epsilon_accent_color_second'       		=> '#3E4346',

					// 'epsilon_text_separator'            		=> '',
					'epsilon_title_color'               		=> '#3E4346',
					'epsilon_text_color'               			=> '#777777',
					'epsilon_link_color'                		=> '#3e4346',
					'epsilon_link_hover_color'          		=> '#ffc000',
					'epsilon_link_active_color'         		=> '#3e4346',

					// 'epsilon_menu_separator'            		=> '',
					'epsilon_header_background'           		=> '#ffffff',
					'epsilon_dropdown_menu_background'          => '#ffffff',
					'epsilon_dropdown_menu_hover_background'	=> '#ffc000',
					'epsilon_menu_item_color'    				=> '#3e4346',
					'epsilon_menu_item_hover_color' 			=> '#ffc000',
					'epsilon_menu_item_active_color'        	=> '#ffc000',

					// 'epsilon_footer_separator'         			=> '',
					'epsilon_footer_contact_background'         => '#ffc000',
					'epsilon_footer_background'         		=> '#3e4346',
					'epsilon_footer_title_color'         		=> '#ffffff',
					'epsilon_footer_text_color'         		=> '#a9afb1',
					'epsilon_footer_link_color'         		=> '#a9afb1',
					'epsilon_footer_link_hover_color'         	=> '#ffffff',
					'epsilon_footer_link_active_color'         	=> '#a9afb1',
				),
			),
		),
	)
);
