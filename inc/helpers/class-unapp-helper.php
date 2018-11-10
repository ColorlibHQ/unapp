<?php
/**
 * MedZone_Lite Theme Helpers
 *
 * @package MedZone_Lite
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Unapp_Helper
 */
class Unapp_Helper {

	public static $section_ids = array();

	/**
	 * Generate different ids for each section
	 */
	public static function generate_section_id( $key ) {

		$random = rand( 1, 999999 );
		$id     = $key . '-' . $random;

		while ( in_array( $id, self::$section_ids ) ) {
			$random = rand( 1, 999999 );
			$id     = $key . '-' . $random;
		}

		self::$section_ids[] = $id;

		return $id;

	}

	/**
	 * Create a "default" value for the footer layout
	 */
	public static function get_footer_default() {
		return wp_json_encode(
			array(
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
			)
		);
	}

	/**
	 * Create a "default" value for the blog layout
	 */
	public static function get_blog_default() {
		return wp_json_encode(
			array(
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
			)
		);
	}

	/**
	 * Get blog layout
	 *
	 * @param string $option Option to retrieve in the backend
	 *
	 * @return array|mixed|object|string
	 */
	public static function get_layout( $option = '' ) {
		$layout = empty( $option ) ? get_theme_mod( 'unapp_layout', false ) : get_theme_mod( $option, false );

		if ( ! $layout ) {
			$layout = Unapp_Helper::get_blog_default();
		}

		if ( ! is_array( $layout ) ) {
			$layout = json_decode( $layout, true );
		}

		$layout['type'] = 'right-sidebar';

		$layout['columns']['content'] = isset( $layout['columns'][1] ) ? $layout['columns'][1] : null;
		$layout['columns']['sidebar'] = isset( $layout['columns'][2] ) ? $layout['columns'][2] : null;

		unset( $layout['columns'][1] );
		unset( $layout['columns'][2] );

		if ( $layout['columns']['content']['span'] < $layout['columns']['sidebar']['span'] ) {
			$layout['type'] = 'left-sidebar';
			$temp           = $layout['columns']['content']['span'];

			$layout['columns']['content']['span'] = $layout['columns']['sidebar']['span'];
			$layout['columns']['sidebar']['span'] = $temp;
		}

		if ( 1 === $layout['columnsCount'] ) {
			$layout['type'] = 'fullwidth';
		}

		return $layout;
	}

	/**
	 * Get the footer layout
	 *
	 * @return array|mixed|object|string
	 */
	public static function get_footer_layout() {
		$footer_layout = get_theme_mod( 'medzone_lite_footer_columns', false );
		if ( ! $footer_layout ) {
			$footer_layout = Unapp_Helper::get_footer_default();
		}
		if ( ! is_array( $footer_layout ) ) {
			$footer_layout = json_decode( $footer_layout, true );
		}

		return $footer_layout;
	}

	/**
	 * Render the post meta
	 *
	 * @param string $element Element that we need to render in the frontend.
	 */
	public static function posted_on( $element = 'default' ) {
		$comments = wp_count_comments( get_the_ID() );

		switch ( $element ) {
			case 'author':
				$html = '<span class="byline">' . esc_html_e( 'by', 'unapp' );
				$html .= '<a class="post-author" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a>';
				$html .= '</span>';

				echo wp_kses_post( $html );
				break;
			case 'category':
				$html = '<div class="cat-links">';
				$html .= get_the_category_list( ' ' );
				$html .= '</div><!-- .cat-links -->';

				echo wp_kses_post( $html );
				break;
			case 'comments':
				echo ' <span class="comments-link"><a title="' . esc_attr__( 'Comment on Post', 'unapp' ) . '" href="' . esc_url( get_the_permalink( get_the_ID() ) ) . '#comments">' . esc_html( $comments->approved ) . '</a></span>';
				break;
			case 'tags':
				$html = '<div class="tags-links">';
				$html .= get_the_tag_list( '', ' ' );
				$html .= '</div><!-- .tags-links -->';
				echo wp_kses_post( $html );
				break;
			default:
				echo '';
				break;
		}
	}

	/**
	 * Generates the section title properly formatted
	 *
	 * @param string $subtitle
	 * @param string $title
	 * @param array  $args
	 *
	 * @return string;
	 */
	public static function generate_section_title(
		$title = '',
		$subtitle = '',
		$args = array(
			'center'  		=> false,
		)
	) {
		$class = 'colorlib-heading';
		if ( $args['center'] ) {
			$class .= ' text-center';
		}
		$html = '<div class="' . $class . '">';
		if ( ! empty( $title ) ) {
			$html .= '<h2>' . $title . '</h2>';
		}
		if ( ! empty( $subtitle ) ) {
			$html .= '<p>' . $subtitle . '</p>';
		}
		$html .= '</div>';

		return $html;
	}

	/**
	 * Retrieve values saved in another customizer field
	 *
	 * @param string $field  Repeater field.
	 * @param string $filter Filtering.
	 *
	 * @return array
	 */
	public static function get_group_values( $field = '', $filter = '' ) {
		$groups = get_theme_mod( $field, array() );
		$arr    = array(
			'all' => esc_html__( 'All', 'unapp' ),
		);

		if ( empty( $groups ) ) {
			return $arr;
		}

		foreach ( $groups as $k => $v ) {
			if ( isset( $groups[ $k ][ $filter ] ) ) {
				$arr[ $v[ $filter ] ] = $v[ $filter ];
			}
		}

		$arr = array_unique( $arr );

		return $arr;
	}

	/**
	 * @param string $key    Repeater field.
	 * @param string $filter Filtering.
	 *
	 * @return array|string
	 */
	public static function get_group_values_from_meta( $key = '', $filter = '' ) {
		$data = get_post_meta( Epsilon_Content_Backup::get_instance()->setting_page, $key, true );
		$arr  = array(
			'all' => esc_html__( 'All', 'unapp' ),
		);

		if ( empty( $data ) ) {
			return $arr;
		}

		if ( ! isset( $data[ $key ] ) ) {
			return $arr;
		}

		$data = $data[ $key ];

		foreach ( $data as $k => $v ) {
			if ( isset( $data[ $k ][ $filter ] ) ) {
				$arr[ $v[ $filter ] ] = $v[ $filter ];
			}
		}

		$arr = array_unique( $arr );

		return $arr;
	}

	/**
	 * Search in an array for a certain value
	 *
	 * @param array  $array  Array.
	 * @param string $column Column to search for.
	 * @param string $value  Value.
	 *
	 * @return array;
	 */
	public static function search_in_array( $array = array(), $column = '', $value = '' ) {
		foreach ( $array as $index => $element ) {
			if ( ! array_key_exists( $column, $element ) ) {
				continue;
			}

			if ( ! array_search( $value, $element, true ) ) {
				unset( $array[ $index ] );
			}
		}

		return $array;
	}

	/**
	 * Returns the class of the container
	 *
	 * @param $key
	 *
	 * @return string
	 */
	public static function container_class( $key, $fields ) {
		$class = array(
			'boxedin'     => 'container',
			'boxedcenter' => 'container container-boxedcenter',
			'fullwidth'   => '', // container-fluid
		);

		if ( ! empty( $fields[ $key . '_column_stretch' ] ) ) {
			return isset( $class[ $fields[ $key . '_column_stretch' ] ] ) ? $class[ $fields[ $key . '_column_stretch' ] ] : 'container';
		}

		return 'container';
	}

	/**
	 * Generate an edit shortcut for the frontend sections
	 */
	public static function generate_pencil( $class_name = '', $section_type = '' ) {
		if ( is_customize_preview() ) {
			return '<a href="#" class="epsilon-section-editor"><span class="dashicons dashicons-edit"></span></a>';
		}

		return '';
	}
//	public static function generate_pencil( $class_name = '', $section_type = '' ) {
//		return Epsilon_Helper::generate_pencil( $class_name, $section_type );
//	}
}
