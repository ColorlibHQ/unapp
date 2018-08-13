<?php
/**
 * MedZone_Lite Theme Customizer repeatable sections
 *
 * @package MedZone_Lite
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Unapp_Repeatable_Sections
 */
class Unapp_Repeatable_Sections {
	/**
	 * Holds the sections
	 *
	 * @var array
	 */
	public $sections = array();

	/**
	 * Unapp_Repeatable_Sections constructor.
	 */
	public function __construct() {
		$this->collect_sections();
	}

	/**
	 * Grab an instance of the sections
	 *
	 * @return Unapp_Repeatable_Sections
	 */
	public static function get_instance() {
		static $inst;
		if ( ! $inst ) {
			$inst = new Unapp_Repeatable_Sections();
		}

		return $inst;
	}

	/**
	 * Create the section array
	 */
	public function collect_sections() {
		$methods = get_class_methods( 'Unapp_Repeatable_Sections' );
		foreach ( $methods as $method ) {
			if ( false !== strpos( $method, 'repeatable_' ) ) {
				$section = $this->$method();

				if ( ! empty( $section ) ) {
					$this->sections[ $section['id'] ] = $section;
				}
			}
		}

		$this->sections = apply_filters( 'medzone_lite_section_collection', $this->sections );
	}

	/**
	 * Banner Section
	 */
	private function repeatable_banner(){
		return array(
			'id'            => 'banner',
			'title'         => esc_html__( 'Banner Section', 'unapp' ),
			'description'   => esc_html__( 'Banner section retrieves content from theme options', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-video-pt.png' ),
			'customization' => array(
				'enabled'   => true,
				'layout'    => array(
					'row-spacing-top'           => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'row-spacing-bottom'        => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
				),
				'styling'   => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'banner_title'             => array(
					'label'             => esc_html__( 'Title', 'unapp' ),
					'type'              => 'text',
					'default'           => wp_kses_post( 'Take on your biggest projects and goals' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'banner_subtitle'          => array(
					'label'             => esc_html__( 'Subtitle', 'unapp' ),
					'type'              => 'epsilon-text-editor',
					'default'           => wp_kses_post( 'with Unapp high quality features' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'banner_button_text'             => array(
					'label'             => esc_html__( 'Button Text', 'unapp' ),
					'type'              => 'text',
					'default'           => wp_kses_post( 'Get Premium' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'banner_button_link' => array(
					'label' => esc_html__( 'Button Link', 'unapp' ),
					'type' => 'text',
					'default' => esc_url( '#' ),
					'sanitize_callback' => 'esc_url_raw'
				),
				'banner_bg_link' => array(
					'label' => esc_html__( 'Background Link', 'unapp' ),
					'type' => 'text',
					'default' => esc_url( 'https://www.youtube.com/watch?v=vqqt5p0q-eU' ),
					'sanitize_callback' => 'esc_url_raw'
				),
				'banner_animate'  => array(
					'label' => esc_html__( 'Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				)
			)
		);
	}

	/**
	 * Repeatable slider section
	 *
	 */
	private function repeatable_slider() {
		return array(
			'id'          => 'slider',
			'title'       => esc_html__( 'Slider Section', 'unapp' ),
			'description' => esc_html__( 'A slider section. It retrieves content from Theme Content / Slides.', 'unapp' ),
			'image'       => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-hero-pt.png'),
			'fields'      => array(
				'slider_navigation'        => array(
					'type'            => 'epsilon-customizer-navigation',
					'opensDoubled'    => true,
					'navigateToId'    => 'unapp_slides_section',
					'navigateToLabel' => esc_html__( 'Add Slides &rarr;', 'unapp' ),
				),
				'slider_grouping'          => array(
					'label'       => esc_html__( 'Slides to show', 'unapp' ),
					'description' => esc_html__( 'Only selected items will be shown in the frontend.', 'unapp' ),
					'type'        => 'selectize',
					'multiple'    => true,
					'choices'     => Unapp_Helper::get_group_values_from_meta( 'unapp_slides', 'slides_title' ),
					'default'     => array( 'all' ),
				),
				'slider_animate'  => array(
					'label' => esc_html__( 'Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
				'slider_repeater_field'    => array(
					'type'    => 'hidden',
					'default' => 'unapp_slides',
				),
			),
		);
	}

	/**
	 * Repeatable services section
	 *
	 * @return array
	 */
	private function repeatable_services() {
		return array(
			'id'            => 'services',
			'title'         => esc_html__( 'Services Section', 'unapp' ),
			'description'   => esc_html__( 'Services section. It retrieves content from Theme Content / Services', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-services-pt.png' ),
			'customization' => array(
				'enabled' => true,
				'layout'  => array(
					'column-stretch'            => array(
						'default' => 'boxedin',
						'choices' => array( 'boxedcenter', 'boxedin', 'fullwidth', ),
					),
					'row-spacing-top'           => array(
						'default' => 'none',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'row-spacing-bottom'        => array(
						'default' => 'none',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'column-group'              => array(
						'default' => 3,
						'choices' => array( 2, 3, 4, ),
					),
					'column-alignment'          => array(
						'default' => 'center',
						'choices' => array( 'left', 'center', 'right', ),
					),
				),
				'styling' => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'services_grouping'          => array(
					'label'       => esc_html__( 'Services Item To Show', 'unapp' ),
					'description' => esc_html__( 'Only selected items will be shown in the frontend.', 'unapp' ),
					'type'        => 'selectize',
					'multiple'    => true,
					'choices'     => Unapp_Helper::get_group_values_from_meta( 'unapp_services', 'service_title' ),
					'default'     => array( 'all' ),
				),
				'services_navigation'        => array(
					'type'            => 'epsilon-customizer-navigation',
					'opensDoubled'    => true,
					'navigateToId'    => 'unapp_services_section',
					'navigateToLabel' => esc_html__( 'Add Services &rarr;', 'unapp' ),
				),
				'services_repeater_field'    => array(
					'type'    => 'hidden',
					'default' => 'unapp_services',
				),
			),
		);
	}

	/**
	 * Collaborate Section
	 */
	private function repeatable_collaborate(){
		return array(
			'id'            => 'collaborate',
			'title'         => esc_html__( 'Collaborate Section', 'unapp' ),
			'description'   => esc_html__( 'Collaborate section retrieves content from theme options', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-cta.png' ),
			'customization' => array(
				'enabled'   => true,
				'layout'    => array(
					'column-stretch'            => array(
						'default' => 'boxedin',
						'choices' => array( 'boxedcenter', 'boxedin', 'fullwidth', ),
					),
					'row-spacing-top'           => array(
						'default' => 'lg',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'row-spacing-bottom'        => array(
						'default' => 'lg',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
				),
				'styling'   => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'collaborate_title'             => array(
					'label'             => esc_html__( 'Title', 'unapp' ),
					'type'              => 'text',
					'default'           => wp_kses_post( 'Collaborate with your design team in a new way' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'collaborate_subtitle'          => array(
					'label'             => esc_html__( 'Subtitle', 'unapp' ),
					'type'              => 'epsilon-text-editor',
					'default'           => wp_kses_post( 'Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'collaborate_text_animate'  => array(
					'label' => esc_html__( 'Text Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
				'collaborate_video_link' => array(
					'label' => esc_html__( 'Youtube Link', 'unapp' ),
					'type' => 'text',
					'default' => esc_url( 'https://vimeo.com/channels/staffpicks/93951774' ),
					'sanitize_callback' => 'esc_url_raw'
				),
				'collaborate_video_animate'  => array(
					'label' => esc_html__( 'Video Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
			)
		);
	}

	/**
	 * Featured Section Left
	 */
	private function repeatable_featured_left(){
		return array(
			'id'            => 'featured_left',
			'title'         => esc_html__( 'Featured Section Left', 'unapp' ),
			'description'   => esc_html__( 'Featured section retrieves content from theme options', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-expertise-pt.png' ),
			'customization' => array(
				'enabled'   => true,
				'layout'    => array(
					'row-title-align'           => array(
						'default' => 'left',
						'choices' => array( 'left', 'right', ),
					),
					'column-stretch'            => array(
						'default' => 'boxedin',
						'choices' => array( 'boxedcenter', 'boxedin', 'fullwidth', ),
					),
					'row-spacing-top'           => array(
						'default' => 'none',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'row-spacing-bottom'        => array(
						'default' => 'none',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'column-alignment'          => array(
						'default' => 'left',
						'choices' => array( 'left', 'center', 'right', ),
					),
					'column-vertical-alignment' => array(
						'default' => 'middle',
						'choices' => array( 'top', 'middle', 'bottom', ),
					),
				),
				'styling'   => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'featured_title' => array(
					'label'             => esc_html__( 'Title', 'unapp' ),
					'type'              => 'text',
					'default'           => wp_kses_post( 'Real template creation' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'featured_image' => array(
					'label'   => esc_html__( 'Image', 'unapp' ),
					'type'    => 'epsilon-image',
					'size'    => 'large',
					'default' => esc_url( get_template_directory_uri() . '/assets/images/mobile-2.jpg' ),
				),
				'featured_left_image_animate'  => array(
					'label' => esc_html__( 'Image Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
				'featured_btn_text' => array(
					'label'             => esc_html__( 'Button Label', 'unapp' ),
					'type'              => 'text',
					'default'           => wp_kses_post( 'Start collaborating' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'featured_btn_link' => array(
					'label' => esc_html__( 'Button Link', 'unapp' ),
					'type' => 'text',
					'default' => esc_url( '#' ),
					'sanitize_callback' => 'esc_url_raw'
				),
				'featured_grouping'          => array(
					'label'       => esc_html__( 'Featured Work To Show', 'unapp' ),
					'description' => esc_html__( 'Only selected items will be shown in the frontend.', 'unapp' ),
					'type'        => 'selectize',
					'multiple'    => true,
					'choices'     => Unapp_Helper::get_group_values_from_meta( 'unapp_featured_left', 'featured_icon' ),
					'default'     => array( 'all' ),
				),
				'featured_left_text_animate'  => array(
					'label' => esc_html__( 'Text Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
				'featured_navigation'        => array(
					'type'            => 'epsilon-customizer-navigation',
					'opensDoubled'    => true,
					'navigateToId'    => 'unapp_featured_section_left',
					'navigateToLabel' => esc_html__( 'Add Featured Work &rarr;', 'unapp' ),
				),
				'featured_repeater_field'    => array(
					'type'    => 'hidden',
					'default' => 'unapp_featured_left',
				),
			)
		);
	}

	/**
	 * Featured Section Right
	 */
	private function repeatable_featured_right(){
		return array(
			'id'            => 'featured_right',
			'title'         => esc_html__( 'Featured Section Right', 'unapp' ),
			'description'   => esc_html__( 'Featured section retrieves content from theme options', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-services-pt.png' ),
			'customization' => array(
				'enabled'   => true,
				'layout'    => array(
					'row-title-align'           => array(
						'default' => 'left',
						'choices' => array( 'right', 'left', ),
					),
					'column-stretch'            => array(
						'default' => 'boxedin',
						'choices' => array( 'boxedcenter', 'boxedin', 'fullwidth', ),
					),
					'column-alignment'          => array(
						'default' => 'left',
						'choices' => array( 'left', 'center', 'right', ),
					),
					'column-vertical-alignment' => array(
						'default' => 'middle',
						'choices' => array( 'top', 'middle', 'bottom', ),
					),
				),
				'styling'   => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'featured_title' => array(
					'label'             => esc_html__( 'Title', 'unapp' ),
					'type'              => 'text',
					'default'           => wp_kses_post( 'Finish template creation' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'featured_image' => array(
					'label'   => esc_html__( 'Image', 'unapp' ),
					'type'    => 'epsilon-image',
					'size'    => 'large',
					'default' => esc_url( get_template_directory_uri() . '/assets/images/mobile-1.jpg' ),
				),
				'featured_right_image_animate'  => array(
					'label' => esc_html__( 'Image Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
				'featured_btn_text' => array(
					'label'             => esc_html__( 'Button Label', 'unapp' ),
					'type'              => 'text',
					'default'           => wp_kses_post( 'Start collaborating' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'featured_btn_link' => array(
					'label' => esc_html__( 'Button Link', 'unapp' ),
					'type' => 'text',
					'default' => esc_url( '#' ),
					'sanitize_callback' => 'esc_url_raw'
				),
				'featured_grouping'          => array(
					'label'       => esc_html__( 'Featured Work To Show', 'unapp' ),
					'description' => esc_html__( 'Only selected items will be shown in the frontend.', 'unapp' ),
					'type'        => 'selectize',
					'multiple'    => true,
					'choices'     => Unapp_Helper::get_group_values_from_meta( 'unapp_featured_right', 'featured_icon' ),
					'default'     => array( 'all' ),
				),
				'featured_right_text_animate'  => array(
					'label' => esc_html__( 'Text Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
				'featured_navigation'        => array(
					'type'            => 'epsilon-customizer-navigation',
					'opensDoubled'    => true,
					'navigateToId'    => 'unapp_featured_section_right',
					'navigateToLabel' => esc_html__( 'Add Featured Work &rarr;', 'unapp' ),
				),
				'featured_repeater_field'    => array(
					'type'    => 'hidden',
					'default' => 'unapp_featured_right',
				),
			)
		);
	}

	/**
	 * Repeatable Counter
	 */
	private function repeatable_counter(){
		return array(
			'id'            => 'counter',
			'title'         => esc_html__( 'Counter Section', 'unapp' ),
			'description'   => esc_html__( 'Counter section retrieves content from theme options', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-services-pt.png' ),
			'customization' => array(
				'enabled'   => true,
				'layout'    => array(
					'column-stretch'            => array(
						'default' => 'boxedin',
						'choices' => array( 'boxedcenter', 'boxedin', 'fullwidth', ),
					),
					'column-group'              => array(
						'default' => 3,
						'choices' => array( 2, 3, 4, ),
					),
					'column-alignment'          => array(
						'default' => 'center',
						'choices' => array( 'left', 'center', 'right', ),
					),
				),
				'styling'   => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'counter_grouping'          => array(
					'label'       => esc_html__( 'Counter To Show', 'unapp' ),
					'description' => esc_html__( 'Only selected items will be shown in the frontend.', 'unapp' ),
					'type'        => 'selectize',
					'multiple'    => true,
					'choices'     => Unapp_Helper::get_group_values_from_meta( 'unapp_counter', 'counter_title' ),
					'default'     => array( 'all' ),
				),
				'counter_navigation'        => array(
					'type'            => 'epsilon-customizer-navigation',
					'opensDoubled'    => true,
					'navigateToId'    => 'unapp_counter_section',
					'navigateToLabel' => esc_html__( 'Add Counter Work &rarr;', 'unapp' ),
				),
				'counter_repeater_field'    => array(
					'type'    => 'hidden',
					'default' => 'unapp_counter',
				),
			)
		);
	}

	/**
	 * Blog Section
	 */
	private function repeatable_blog() {
		return array(
			'id'            => 'blog',
			'title'         => esc_html__( 'Blog Section', 'unapp' ),
			'description'   => esc_html__( 'Blog Area Section', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-blog-pt.png' ),
			'customization' => array(
				'enabled' => true,
				'layout'  => array(
					'row-spacing-top'    => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'row-spacing-bottom' => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
				),
				'styling' => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'blog_title'             => array(
					'label'             => esc_html__( 'Title', 'unapp' ),
					'type'              => 'text',
					'default'           => esc_html__( 'News from our Blog', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'blog_subtitle'          => array(
					'label'             => esc_html__( 'Subtitle', 'unapp' ),
					'type'              => 'epsilon-text-editor',
					'default'           => esc_html__( 'Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'blog_post_count'        => array(
					'label'       => esc_html__( 'Post Count', 'unapp' ),
					'description' => esc_html__( 'Only posts with featured image are loaded', 'unapp' ),
					'type'        => 'epsilon-slider',
					'default'     => 3,
					'choices'     => array(
						'min' => 1,
						'max' => 9,
					),
				),
				'blog_animate'  => array(
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
		);
	}

	/**
	 * Subscribe Section
	 */
	private function repeatable_subscribe(){
		return array(
			'id'            => 'subscribe',
			'title'         => esc_html__( 'Subscribe Section', 'unapp' ),
			'description'   => esc_html__( 'Subscribe section retrieves content from theme options', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-services-pt.png' ),
			'customization' => array(
				'enabled'   => true,
				'layout'    => array(

					'column-stretch'            => array(
						'default' => 'boxedin',
						'choices' => array( 'boxedcenter', 'boxedin', 'fullwidth', ),
					),
					'row-spacing-top'           => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'row-spacing-bottom'        => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
				),
				'styling'   => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'subscribe_title'             => array(
					'label'             => esc_html__( 'Title', 'unapp' ),
					'type'              => 'text',
					'default'           => wp_kses_post( 'Already trusted by over 10,000 users' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'subscribe_subtitle'          => array(
					'label'             => esc_html__( 'Subtitle', 'unapp' ),
					'type'              => 'epsilon-text-editor',
					'default'           => wp_kses_post( 'Subscribe to receive unapp tips from instructors right to your inbox.' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'subscribe_text_animate'  => array(
					'label' => esc_html__( 'Text Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
				'subscribe_shortcode' => array(
					'label'             => esc_html__( 'Mailchimp Shortcode', 'unapp' ),
					'type'              => 'text',
					//'default'           => wp_kses_post( 'Already trusted by over 10,000 users' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'subscribe_btn_animate'  => array(
					'label' => esc_html__( 'Button Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
			)
		);
	}

	/**
	 * Repeatable pricing section
	 *
	 */
	private function repeatable_pricing() {
		return array(
			'id'            => 'pricing',
			'title'         => esc_html__( 'Pricing Section', 'unapp' ),
			'description'   => esc_html__( 'Pricing section. It retrieves content from Theme Content / Pricing', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-pricing-pt.png' ),
			'customization' => array(
				'enabled' => true,
				'layout'  => array(
					'column-stretch'     => array(
						'default' => 'boxedin',
						'choices' => array( 'boxedcenter', 'boxedin', 'fullwidth', ),
					),
					'row-spacing-top'    => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'row-spacing-bottom' => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'column-group'       => array(
						'default' => 4,
						'choices' => array( 2, 3, 4, ),
					),
					'column-alignment'          => array(
						'default' => 'center',
						'choices' => array( 'left', 'center', 'right', ),
					),
				),
				'styling' => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'pricing_title'             => array(
					'label'             => esc_html__( 'Title', 'unapp' ),
					'type'              => 'text',
					'default'           => esc_html__( 'Pricing', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'pricing_subtitle'          => array(
					'label'             => esc_html__( 'Subtitle', 'unapp' ),
					'type'              => 'text',
					'default'           => esc_html__( 'Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'pricing_grouping'          => array(
					'label'       => esc_html__( 'Price boxes to show', 'unapp' ),
					'description' => esc_html__( 'Only selected items will be shown in the frontend.', 'unapp' ),
					'type'        => 'selectize',
					'multiple'    => true,
					'choices'     => Unapp_Helper::get_group_values_from_meta( 'unapp_pricing', 'price_box_title' ),
					'default'     => array( 'all' ),
				),
				'pricing_navigation'        => array(
					'type'            => 'epsilon-customizer-navigation',
					'opensDoubled'    => true,
					'navigateToId'    => 'unapp_pricing_section',
					'navigateToLabel' => esc_html__( 'Add Price Boxes &rarr;', 'unapp' ),
				),
				'pricing_repeater_field'    => array(
					'type'    => 'hidden',
					'default' => 'unapp_pricing',
				),
			),
		);
	}

	/**
	 * Repeatable About section
	 */
	private function repeatable_about() {
		return array(
			'id'            => 'about',
			'title'         => esc_html__( 'About Section', 'unapp' ),
			'description'   => esc_html__( 'About section. It retrieves content from Theme Content / Services', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-about-pt.png' ),
			'customization' => array(
				'enabled' => true,
				'layout'  => array(
					'row-title-align'           => array(
						'default' => 'left',
						'choices' => array( 'left', 'right', ),
					),
					'column-stretch'            => array(
						'default' => 'boxedin',
						'choices' => array( 'boxedcenter', 'boxedin', 'fullwidth', ),
					),
					'row-spacing-top'           => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'row-spacing-bottom'        => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'column-alignment'          => array(
						'default' => 'left',
						'choices' => array( 'left', 'center', 'right', ),
					),
					'column-vertical-alignment' => array(
						'default' => 'middle',
						'choices' => array( 'top', 'middle', 'bottom', ),
					),
				),
				'styling' => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'about_title'             => array(
					'label'             => esc_html__( 'Title', 'unapp' ),
					'type'              => 'text',
					'default'           => esc_html__( 'About unapp', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',

				),
				'about_text'              => array(
					'label'             => esc_html__( 'Information', 'unapp' ),
					'type'              => 'epsilon-text-editor',
					'default'           => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris lacinia velit quis sem dignissim porta. Aliquam risus lorem, ornare sed diam at, ultrices vehicula enim. Morbi pharetra ligula nulla, non blandit velit tempor vel.', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'about_text_animate'  => array(
					'label' => esc_html__( 'Text Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
				'about_image'             => array(
					'label'   => esc_html__( 'Image', 'unapp' ),
					'type'    => 'epsilon-image',
					'size'    => 'large',
					'default' => esc_url( get_template_directory_uri() . '/assets/images/about.jpg' ),
				),
				'about_video_link' => array(
					'label'             => esc_html__( 'Video Link', 'unapp' ),
					'type'              => 'text',
					'default' => esc_url( 'https://vimeo.com/channels/staffpicks/93951774' ),
					'sanitize_callback' => 'esc_url_raw'
				),
				'about_image_animate'  => array(
					'label' => esc_html__( 'Image Animate', 'unapp' ),
					'type' => 'select',
					'choices' => array(
						'fadeIn' => esc_html__( 'fadeIn', 'unapp' ),
						'fadeInLeft' => esc_html__( 'fadeInLeft', 'unapp' ),
						'fadeInRight' => esc_html__( 'fadeInRight', 'unapp' ),
						'fadeInUp' => esc_html__( 'fadeInUp', 'unapp' ),
					),
				),
			),
		);
	}

	/**
	 * Repeatable Team Member section
	 */
	private function repeatable_team() {
		return array(
			'id'            => 'team',
			'title'         => esc_html__( 'Team Section', 'unapp' ),
			'description'   => esc_html__( 'Team members section. It retrieves content from Theme Content / Portfolio', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-team-pt.png' ),
			'customization' => array(
				'enabled' => true,
				'layout'  => array(
					'column-stretch'            => array(
						'default' => 'boxedin',
						'choices' => array( 'boxedcenter', 'boxedin', 'fullwidth', ),
					),
					'row-spacing-top'    => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'row-spacing-bottom' => array(
						'default' => 'md',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'column-group'       => array(
						'default' => 4,
						'choices' => array( 2, 3, 4, ),
					),
					'column-alignment'          => array(
						'default' => 'center',
						'choices' => array( 'left', 'center', 'right', ),
					),
					'column-vertical-alignment' => array(
						'default' => 'middle',
						'choices' => array( 'top', 'middle', 'bottom', ),
					),
				),
				'styling' => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'team_title'             => array(
					'label'             => esc_html__( 'Title', 'unapp' ),
					'type'              => 'text',
					'default'           => esc_html__( 'Collaborate with your design team in a new way', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'team_subtitle'          => array(
					'label'             => esc_html__( 'Subtitle', 'unapp' ),
					'type'              => 'text',
					'default'           => esc_html__( 'Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'team_grouping'          => array(
					'label'       => esc_html__( 'Members to show', 'unapp' ),
					'description' => esc_html__( 'Only selected items will be shown in the frontend.', 'unapp' ),
					'type'        => 'selectize',
					'multiple'    => true,
					'choices'     => Unapp_Helper::get_group_values_from_meta( 'unapp_team_members', 'member_title' ),
					'default'     => array( 'all' ),
				),
				'team_navigation'        => array(
					'type'            => 'epsilon-customizer-navigation',
					'opensDoubled'    => true,
					'navigateToId'    => 'unapp_team_members_section',
					'navigateToLabel' => esc_html__( 'Add Members &rarr;', 'unapp' ),
				),
				'team_repeater_field'    => array(
					'type'    => 'hidden',
					'default' => 'unapp_team_members',
				),
			),
		);
	}

	/**
	 * Repeatable Contact section
	 */
	private function repeatable_contact() {
		return array(
			'id'            => 'contact',
			'title'         => esc_html__( 'Contact Section', 'unapp' ),
			'description'   => esc_html__( 'Contact section. It retrieves content from Theme Content / Services', 'unapp' ),
			'image'         => esc_url( get_template_directory_uri() . '/assets/images/sections/ewf-icon-section-about-pt.png' ),
			'customization' => array(
				'enabled' => true,
				'layout'  => array(
					'row-title-align'           => array(
						'default' => 'right',
						'choices' => array( 'left', 'right', ),
					),
					'column-stretch'            => array(
						'default' => 'boxedin',
						'choices' => array( 'boxedcenter', 'boxedin', 'fullwidth', ),
					),
					'row-spacing-top'           => array(
						'default' => 'none',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
					'row-spacing-bottom'        => array(
						'default' => 'none',
						'choices' => array( 'lg', 'md', 'sm', 'none', ),
					),
				),
				'styling' => array(
					'background-color'         => array(
						'default' => false,
					),
					'background-color-opacity' => array(
						'default' => 1,
					),
					'background-image'         => array(
						'default' => false,
					),
					'background-position'      => array(
						'default' => 'center',
					),
					'background-size'          => array(
						'default' => 'initial',
					),
					'background-repeat'        => array(
						'default' => 'repeat'
					),
					'background-parallax'      => array(
						'default' => false,
					),
					'background-video'         => array(
						'default' => '',
					),
				)
			),
			'fields'        => array(
				'contact_shortcode'             => array(
					'label'             => esc_html__( 'Shortcode', 'unapp' ),
					'type'              => 'text',
					'sanitize_callback' => 'wp_kses_post',
				),
				'contact_address'              => array(
					'label'             => esc_html__( 'Address', 'unapp' ),
					'type'              => 'epsilon-text-editor',
					'default'           => esc_html__( '198 West 21th Street, Suite 721 New York NY 10016', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'contact_phone'              => array(
					'label'             => esc_html__( 'Phone', 'unapp' ),
					'type'              => 'text',
					'default'           => esc_html__( '+ 1235 2355 98', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'contact_email'              => array(
					'label'             => esc_html__( 'Email', 'unapp' ),
					'type'              => 'text',
					'default'           => esc_html__( 'info@yoursite.com', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'contact_web'              => array(
					'label'             => esc_html__( 'Web Address', 'unapp' ),
					'type'              => 'text',
					'default'           => esc_html__( 'http://yourwebsite.com', 'unapp' ),
					'sanitize_callback' => 'wp_kses_post',
				),
				'contact_map_api'             => array(
					'label'   => esc_html__( 'Google API KEY ', 'unapp' ),
					'type'    => 'text',
					'size'    => 'large',
					'default' => 'AIzaSyCefOgb1ZWqYtj7raVSmN4PL2WkTrc-KyA',
				),
				'contact_map_lat'             => array(
					'label'   => esc_html__( 'Google Map Latitude', 'unapp' ),
					'type'    => 'text',
					'size'    => 'large',
					'default' => '40.69847032728747',
				),
				'contact_map_unapp_long' => array(
					'label'   => esc_html__( 'Google Map Longitude', 'unapp' ),
					'type'    => 'text',
					'size'    => 'large',
					'default' => '-73.9514422416687',
				),
				'contact_map_zoom'             => array(
					'label'   => esc_html__( 'Google Map Longitude ', 'unapp' ),
					'type'    => 'text',
					'size'    => 'large',
					'default' => '7',
				),
			),
		);
	}
}
