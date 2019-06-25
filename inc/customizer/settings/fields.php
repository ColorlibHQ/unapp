<?php
/**
 * MedZone_Lite Theme Customizer Fields
 *
 * @package MedZone_Lite
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Register customizer fields
 */

/**
 * General section options
 ***************************************************************************************/
Epsilon_Customizer::add_field(
	'unapp_enable_go_top',
	array(
		'type'        => 'epsilon-toggle',
		'label'       => esc_html__( 'Go to top button', 'unapp' ),
		'description' => esc_html__( 'Toggle the display of the go to top button.', 'unapp' ),
		'section'     => 'unapp_footer_section',
		'default'     => true,
	)
);

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
		),
		'selectors'     => array(
			'.post-title',
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'h1 a',
			'h2 a',
			'h3 a',
			'h4 a',
			'h5 a',
			'h6 a',
		),
		'font_defaults' => array(
			'font-family' => '',
			'font-weight' => '',
			'font-style'  => '',
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
		),
		'selectors'     => array(
			'body',
		),
		'font_defaults' => array(
			'font-family' => '',
		),
	)
);

/**
 * Footer section options
 */
Epsilon_Customizer::add_field(
	'unapp_footer_columns',
	array(
		'type'     => 'epsilon-layouts',
		'section'  => 'unapp_footer_section',
		'priority' => 0,
		'layouts'  => array(
			1 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/one-column.png',
			2 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/two-column.png',
			3 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/three-column.png',
			4 => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/four-column.png',
		),
		'default'  => array(
			'columnsCount' => 4,
			'columns'      => array(
				array(
					'index' => 1,
					'span'  => 3,
				),
				array(
					'index' => 2,
					'span'  => 3,
				),
				array(
					'index' => 3,
					'span'  => 3,
				),
				array(
					'index' => 4,
					'span'  => 3,
				),
			),
		),
		'fixed'    => true,
		'min_span' => 2,
		'label'    => esc_html__( 'Footer Columns', 'unapp' ),
	)
);

Epsilon_Customizer::add_field(
	'unapp_copyright_contents',
	array(
		'type'    => 'epsilon-text-editor',
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
			'services_animate'  => array(
				'label' => esc_html__( 'Animate', 'unapp' ),
				'type' => 'select',
				'choices' => array(
					'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
					'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
					'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
					'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
				),
			)
		),
	)
);

/**
 * Features
 */
Epsilon_Customizer::add_field(
	'unapp_features',
	array(
		'type'          => 'epsilon-repeater',
		'section'       => 'unapp_features_section',
		'save_as_meta'  => Epsilon_Content_Backup::get_instance()->setting_page,
		'label'         => esc_html__( 'Featured Work', 'unapp' ),
		'button_label'  => esc_html__( 'Add new items', 'unapp' ),
		'row_label'     => array(
			'type'  => 'field',
			'field' => 'features_icon'
		),
		'fields' => array(
			'features_icon'        => array(
				'label'   => esc_html__( 'Icon', 'unapp' ),
				'type'    => 'epsilon-icon-picker',
				'default' => 'fa fa-users',
			),
			'features_description' => array(
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
		'transport'           => 'postMessage',
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
			'counter_animate'  => array(
				'label' => esc_html__( 'Animate', 'unapp' ),
				'type' => 'select',
				'choices' => array(
					'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
					'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
					'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
					'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
				),
			),
		)
	)
);

/**
 * Unapp Pricing
 */
Epsilon_Customizer::add_field(
	'unapp_pricing',
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
			'pricing_animate'  => array(
				'label' => esc_html__( 'Animate', 'unapp' ),
				'type' => 'select',
				'choices' => array(
					'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
					'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
					'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
					'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
				),
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
				'default'           => esc_html__( 'Dorothy Murphy', 'unapp' ),
				'sanitize_callback' => 'wp_kses_post',
			),
			'member_designation'            => array(
				'label'             => esc_html__( 'Designation', 'unapp' ),
				'type'              => 'text',
				'default'           => esc_html__( 'Designer', 'unapp' ),
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
			'member_social_linkedin'  => array(
				'label'   => esc_html__( 'LinkedIn', 'unapp' ),
				'type'    => 'url',
				'default' => 'https://linkedin.com',
			),
			'member_animate'  => array(
				'label' => esc_html__( 'Animate', 'unapp' ),
				'type' => 'select',
				'choices' => array(
					'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
					'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
					'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
					'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
				),
			),
		),
	)
);

/**
 * Unapp Portfolios
 */
Epsilon_Customizer::add_field(
	'unapp_portfolios',
	array(
		'type'         => 'epsilon-repeater',
		'section'      => 'unapp_portfolios_section',
		'save_as_meta' => Epsilon_Content_Backup::get_instance()->setting_page,
		'label'        => esc_html__( 'Portfolios', 'unapp' ),
		'button_label' => esc_html__( 'Add new portfolio', 'unapp' ),
		'row_label'    => array(
			'type'  => 'field',
			'field' => 'title',
		),
		'fields'       => array(
			'title'            => array(
				'label'             => esc_html__( 'Title', 'unapp' ),
				'type'              => 'text',
				'default'           => esc_html__( 'A beige chair at a basket', 'unapp' ),
				'sanitize_callback' => 'wp_kses_post',
			),
			'content'             => array(
				'label'   => esc_html__( 'Content', 'unapp' ),
				'type'    => 'epsilon-text-editor',
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris lacinia velit quis sem dignissim porta.', 'unapp' ),
			),
            'image_align'  => array(
                'label' => esc_html__( 'Image Alignment', 'unapp' ),
                'type' => 'select',
                'choices' => array(
                    'left'     => esc_html__( 'Left Image', 'unapp' ),
                    'right'    => esc_html__( 'Right Image', 'unapp' ),
                ),
                'default'    => 'left'
            ),
			'featured_image'            => array(
				'label'   => esc_html__( 'Featured Image', 'unapp' ),
				'type'    => 'epsilon-image',
				'size'    => 'unapp-team-image',
				'default' => esc_url( get_template_directory_uri() . '/assets/images/about.jpg' ),
			),
			'url'  => array(
				'label'   => esc_html__( 'URL', 'unapp' ),
				'type'    => 'url',
				'default' => 'https://facebook.com',
			),
		),
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
		'selective_refresh'   => true,
		'page_builder'        => true,
		'transport'           => 'postMessage',
		'repeatable_sections' => Unapp_Repeatable_Sections::get_instance()->sections,
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

/**
 * Logo dimensions
 */
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


