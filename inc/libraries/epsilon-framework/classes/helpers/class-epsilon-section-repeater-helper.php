<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Section_Repeater_Helper
 */
class Epsilon_Section_Repeater_Helper {
	/**
	 * @var null
	 */
	public $id = null;

	/**
	 * @var array
	 */
	public $column_alignment = array();

	/**
	 * @var array
	 */
	public $column_vertical_alignment = array();

	/**
	 * @var array
	 */
	public $column_stretch = array();

	/**
	 * @var array
	 */
	public $column_spacing = array();

	/**
	 * @var array
	 */
	public $column_group = array();

	/**
	 * @var array
	 */
	public $row_spacing = array();
	/**
	 * @var array
	 */
	public $row_spacing_bottom = array();

	/**
	 * @var array
	 */
	public $title_align = array();

	/**
	 * Epsilon_Section_Repeater_Helper constructor.
	 */
	public function __construct( $args = array() ) {
		$this->id = $args['id'];

		$this->set_column_alignment();
		$this->set_column_vertical_alignment();
		$this->set_column_group();
		$this->set_column_stretch();
		$this->set_column_spacing();
		$this->set_row_spacing();
		$this->set_row_spacing_bottom();
		$this->set_title_align();
	}

	/**
	 * @param array $args
	 *
	 * @return Epsilon_Section_Repeater_Helper
	 */
	public static function get_instance( $args = array() ) {
		static $inst;
		if ( ! $inst ) {
			$inst = new Epsilon_Section_Repeater_Helper( $args );
		}

		return $inst;
	}

	/**
	 *
	 */
	public function set_column_alignment() {
		$this->column_alignment = array(
			'left'   => array(
				'icon'  => 'dashicons-editor-alignleft',
				'value' => 'left',
			),
			'center' => array(
				'icon'  => 'dashicons-editor-aligncenter',
				'value' => 'center',
			),
			'right'  => array(
				'icon'  => 'dashicons-editor-alignright',
				'value' => 'right',
			),
		);
	}

	/**
	 *
	 */
	public function set_column_vertical_alignment() {
		$this->column_vertical_alignment = array(
			'top'    => array(
				'value' => 'top',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-aligntop.png',
			),
			'middle' => array(
				'value' => 'middle',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-alignmiddle.png',
			),
			'bottom' => array(
				'value' => 'bottom',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-alignbottom.png',
			),
		);
	}

	/**
	 *
	 */
	public function set_column_stretch() {
		$this->column_stretch = array(
			'boxedcenter' => array(
				'value' => 'boxedcenter',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-boxedcenter.png',
			),
			'boxedin'     => array(
				'value' => 'boxedin',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-boxedin.png',
			),
			'fullwidth'   => array(
				'value' => 'fullwidth',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-fullwidth.png',
			),
		);
	}

	/**
	 *
	 */
	public function set_column_spacing() {
		$this->column_spacing = array(
			'spaced' => array(
				'value' => 'spaced',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-colspaced.png',
			),
			'colfit' => array(
				'value' => 'colfit',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-colfit.png',
			),
		);
	}

	/**
	 *
	 */
	public function set_column_group() {
		$this->column_group = array(
			1 => array(
				'value' => 1,
				'png'   => EPSILON_URI . '/assets/img/one-column.png',
			),
			2 => array(
				'value' => 2,
				'png'   => EPSILON_URI . '/assets/img/two-column.png',
			),
			3 => array(
				'value' => 3,
				'png'   => EPSILON_URI . '/assets/img/three-column.png',
			),
			4 => array(
				'value' => 4,
				'png'   => EPSILON_URI . '/assets/img/four-column.png',
			),
		);
	}

	/**
	 *
	 */
	public function set_row_spacing() {
		$this->row_spacing = array(
			'none' => array(
				'value' => 'none',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-spacenone.jpg',
			),
			'sm'   => array(
				'value' => 'sm',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-spacesm.jpg',
			),
			'md'   => array(
				'value' => 'md',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-spacemd.jpg',
			),
			'lg'   => array(
				'value' => 'lg',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-spacelg.jpg',
			),
		);
	}

	/**
	 *
	 */
	public function set_row_spacing_bottom() {
		$this->row_spacing_bottom = array(
			'none' => array(
				'value' => 'none',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-bottom-spacenone.jpg',
			),
			'sm'   => array(
				'value' => 'sm',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-bottom-spacesm.jpg',
			),
			'md'   => array(
				'value' => 'md',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-bottom-spacemd.jpg',
			),
			'lg'   => array(
				'value' => 'lg',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-bottom-spacelg.jpg',
			),
		);
	}

	/**
	 *
	 */
	public function set_title_align() {
		$this->title_align = array(
			'left'  => array(
				'value' => 'left',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-titleleft.jpg',
			),
			'top'   => array(
				'value' => 'top',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-titletop.jpg',
			),
			'right' => array(
				'value' => 'right',
				'png'   => EPSILON_URI . '/assets/img/epsilon-section-titleright.jpg',
			),
		);
	}

	/**
	 * Set group type
	 */
	public function set_group_type( $choices = array() ) {
		$arr = array(
			0 => 'none',
			1 => 'one',
			2 => 'two',
			3 => 'three',
			4 => 'four',
		);

		return $arr[ count( $choices ) ];
	}

	/**
	 * Create from a field of keys, "usable" fields
	 *
	 * @param array $styling
	 */
	public function create_color_fields( $colors = array(), $key ) {
		$arr = array();
		foreach ( $colors as $prop => $values ) {
			switch ( $prop ) {
				case 'text-color':
					$temp = array(
						'id'          => $key . '_text_color',
						'label'       => esc_html__( 'Text Color', 'unapp' ),
						'description' => '',
						'default'     => isset( $values['default'] ) ? $values['default'] : '',
						'type'        => 'epsilon-color-picker',
						'mode'        => 'hex',
						'defaultVal'  => isset( $values['default'] ) ? $values['default'] : '',
						'selectors'   => ! empty( $values['selectors'] ) ? $values['selectors'] : array( 'p' ),
						'group'       => 'colors',
					);

					$arr[ $key . '_text_color' ] = $temp;
					break;
				case 'heading-color':
					$temp = array(
						'id'          => $key . '_heading_color',
						'label'       => esc_html__( 'Heading Color', 'unapp' ),
						'description' => '',
						'default'     => isset( $values['default'] ) ? $values['default'] : '',
						'type'        => 'epsilon-color-picker',
						'mode'        => 'hex',
						'defaultVal'  => isset( $values['default'] ) ? $values['default'] : '',
						'selectors'   => ! empty( $values['selectors'] ) ? $values['selectors'] : array(
							'h1',
							'h2',
							'h3',
							'h4',
							'h5',
							'h6',
						),
						'group'       => 'colors',
					);

					$arr[ $key . '_heading_color' ] = $temp;
					break;
				default:
					break;
			}// End switch().
		}// End foreach().

		return $arr;
	}

	/**
	 * Create from a field of keys, "usable" fields
	 *
	 * @param array $styling
	 */
	public function create_styling_fields( $styling = array(), $key ) {
		$sizes = Epsilon_Helper::get_image_sizes();
		$arr   = array();
		foreach ( $styling as $prop => $values ) {
			switch ( $prop ) {
				case 'background-color':
					$temp = array(
						'id'          => $key . '_background_color',
						'label'       => esc_html__( 'Background color', 'unapp' ),
						'description' => '',
						'default'     => isset( $values['default'] ) ? $values['default'] : '',
						'type'        => 'epsilon-color-picker',
						'mode'        => 'hex',
						'defaultVal'  => isset( $values['default'] ) ? $values['default'] : '',
						'group'       => 'styling',
					);

					$arr[ $key . '_background_color' ] = $temp;
					break;
				case 'background-color-opacity':
					$temp = array(
						'id'          => $key . '_background_color_opacity',
						'label'       => esc_html__( 'Background color opacity', 'unapp' ),
						'description' => '',
						'type'        => 'epsilon-slider',
						'default'     => isset( $values['default'] ) ? $values['default'] : 1,
						'choices'     => array(
							'step' => .05,
							'min'  => 0,
							'max'  => 1,
						),
						'group'       => 'styling',
					);

					$arr[ $key . '_background_color_opacity' ] = $temp;
					break;
				case 'background-image':
					$temp = array(
						'id'          => $key . '_background_image',
						'label'       => esc_html__( 'Background image', 'unapp' ),
						'description' => '',
						'type'        => 'epsilon-image',
						'default'     => isset( $values['default'] ) ? $values['default'] : '',
						'group'       => 'styling',
						'size'        => 'full',
						'sizeArray'   => $sizes,
						'mode'        => 'url',
					);

					$arr[ $key . '_background_image' ] = $temp;
					break;
				case 'background-position':
					$temp = array(
						'id'          => $key . '_background_position',
						'label'       => esc_html__( 'Background position', 'unapp' ),
						'description' => '',
						'default'     => isset( $values['default'] ) ? $values['default'] : '',
						'type'        => 'select',
						'group'       => 'styling',
						'choices'     => array(
							'topleft'     => esc_html__( 'Top Left', 'unapp' ),
							'top'         => esc_html__( 'Top', 'unapp' ),
							'topright'    => esc_html__( 'Top Right', 'unapp' ),
							'left'        => esc_html__( 'Left', 'unapp' ),
							'center'      => esc_html__( 'Center', 'unapp' ),
							'right'       => esc_html__( 'Right', 'unapp' ),
							'bottomleft'  => esc_html__( 'Bottom Left', 'unapp' ),
							'bottom'      => esc_html__( 'Bottom', 'unapp' ),
							'bottomright' => esc_html__( 'Bottom Right', 'unapp' ),
						),
					);

					$arr[ $key . '_background_position' ] = $temp;
					break;
				case 'background-repeat':
					$temp = array(
						'id'          => $key . '_background_repeat',
						'label'       => esc_html__( 'Background repeat', 'unapp' ),
						'description' => '',
						'default'     => isset( $values['default'] ) ? $values['default'] : '',
						'type'        => 'select',
						'group'       => 'styling',
						'choices'     => array(
							'no-repeat' => esc_html__( 'No Repeat', 'unapp' ),
							'repeat'    => esc_html__( 'Repeat', 'unapp' ),
							'repeat-y'  => esc_html__( 'Repeat Y', 'unapp' ),
							'repeat-x'  => esc_html__( 'Repeat X', 'unapp' ),
						),
					);

					$arr[ $key . '_background_repeat' ] = $temp;
					break;
				case 'background-size':
					$temp = array(
						'id'          => $key . '_background_size',
						'label'       => esc_html__( 'Background size', 'unapp' ),
						'description' => '',
						'default'     => isset( $values['default'] ) ? $values['default'] : '',
						'type'        => 'select',
						'group'       => 'styling',
						'choices'     => array(
							'cover'   => esc_html__( 'Cover', 'unapp' ),
							'contain' => esc_html__( 'Contain', 'unapp' ),
							'initial' => esc_html__( 'Initial', 'unapp' ),
						),
					);

					$arr[ $key . '_background_size' ] = $temp;
					break;
				case 'background-parallax':
					$temp = array(
						'id'          => $key . '_background_parallax',
						'label'       => esc_html__( 'Background parallax', 'unapp' ),
						'description' => '',
						'default'     => isset( $values['default'] ) ? $values['default'] : false,
						'type'        => 'epsilon-toggle',
						'group'       => 'styling',
					);

					$arr[ $key . '_background_parallax' ] = $temp;
					break;
				case 'background-video':
					$temp = array(
						'id'          => $key . '_background_video',
						'label'       => esc_html__( 'Background video', 'unapp' ),
						'description' => '',
						'default'     => isset( $values['default'] ) ? $values['default'] : '',
						'type'        => 'text',
						'group'       => 'styling',
					);

					$arr[ $key . '_background_video' ] = $temp;
					break;
				default:
					break;
			}// End switch().
		}// End foreach().

		return $arr;

	}


	/**
	 * Create the choices array
	 *
	 * @param string $key
	 * @param array  $choices
	 *
	 * @return array
	 */
	public function create_choices_array( $key = '', $choices = array() ) {
		$arr = array();

		foreach ( $choices as $choice ) {
			$exists = array_key_exists( $choice, $this->{$key} );
			if ( $exists ) {
				$arr[ $choice ] = $this->{$key}[ $choice ];
			}
		}

		return $arr;
	}

	/**
	 * Create from a field of keys, "usable" fields
	 *
	 * @param array $styling
	 */
	public function create_layout_fields( $layout = array(), $key ) {
		$arr = array();
		foreach ( $layout as $prop => $values ) {
			switch ( $prop ) {
				case 'column-alignment':
					$temp = array(
						'id'      => $key . '_column_alignment',
						'type'    => 'epsilon-button-group',
						'label'   => esc_html__( 'Horizontal alignment', 'unapp' ),
						'group'   => 'layout',
						'choices' => $this->create_choices_array( 'column_alignment', $values['choices'] ),
						'default' => isset( $values['default'] ) ? $values['default'] : 'center',
					);

					$temp['groupType'] = $this->set_group_type( $temp['choices'] );

					$arr[ $key . '_column_alignment' ] = $temp;
					break;

				case 'column-vertical-alignment':
					$temp              = array(
						'id'      => $key . '_column_vertical_alignment',
						'type'    => 'epsilon-button-group',
						'label'   => esc_html__( 'Vertical alignment', 'unapp' ),
						'group'   => 'layout',
						'choices' => $this->create_choices_array( 'column_vertical_alignment', $values['choices'] ),
						'default' => isset( $values['default'] ) ? $values['default'] : 'middle',
					);
					$temp['groupType'] = $this->set_group_type( $temp['choices'] );

					$arr[ $key . '_column_vertical_alignment' ] = $temp;
					break;

				case 'column-stretch':
					$temp              = array(
						'id'      => $key . '_column_stretch',
						'type'    => 'epsilon-button-group',
						'label'   => esc_html__( 'Stretch', 'unapp' ),
						'group'   => 'layout',
						'choices' => $this->create_choices_array( 'column_stretch', $values['choices'] ),
						'default' => isset( $values['default'] ) ? $values['default'] : 'boxedin',
					);
					$temp['groupType'] = $this->set_group_type( $temp['choices'] );

					$arr[ $key . '_column_stretch' ] = $temp;
					break;

				case 'column-spacing':
					$temp              = array(
						'id'      => $key . '_column_spacing',
						'type'    => 'epsilon-button-group',
						'label'   => esc_html__( 'Items spacing', 'unapp' ),
						'group'   => 'layout',
						'choices' => $this->create_choices_array( 'column_spacing', $values['choices'] ),
						'default' => isset( $values['default'] ) ? $values['default'] : 'spaced',
					);
					$temp['groupType'] = $this->set_group_type( $temp['choices'] );

					$arr[ $key . '_column_spacing' ] = $temp;
					break;

				case 'column-group':
					$temp              = array(
						'id'      => $key . '_column_group',
						'type'    => 'epsilon-button-group',
						'label'   => esc_html__( 'Items group', 'unapp' ),
						'group'   => 'layout',
						'choices' => $this->create_choices_array( 'column_group', $values['choices'] ),
						'default' => isset( $values['default'] ) ? absint( $values['default'] ) : 4,
					);
					$temp['groupType'] = $this->set_group_type( $temp['choices'] );

					$arr[ $key . '_column_group' ] = $temp;
					break;

				case 'row-spacing-top':
					$temp              = array(
						'id'      => $key . '_row_spacing_top',
						'type'    => 'epsilon-button-group',
						'label'   => esc_html__( 'Spacing top', 'unapp' ),
						'group'   => 'layout',
						'choices' => $this->create_choices_array( 'row_spacing', $values['choices'] ),
						'default' => isset( $values['default'] ) ? $values['default'] : 'none',
					);
					$temp['groupType'] = $this->set_group_type( $temp['choices'] );

					$arr[ $key . '_row_spacing_top' ] = $temp;
					break;
				case 'row-spacing-bottom':
					$temp              = array(
						'id'      => $key . '_row_spacing_bottom',
						'type'    => 'epsilon-button-group',
						'label'   => esc_html__( 'Spacing bottom', 'unapp' ),
						'group'   => 'layout',
						'choices' => $this->create_choices_array( 'row_spacing_bottom', $values['choices'] ),
						'default' => isset( $values['default'] ) ? $values['default'] : 'none',
					);
					$temp['groupType'] = $this->set_group_type( $temp['choices'] );

					$arr[ $key . '_row_spacing_bottom' ] = $temp;
					break;
				case 'row-title-align':
					$temp              = array(
						'id'      => $key . '_row_title_align',
						'type'    => 'epsilon-button-group',
						'label'   => esc_html__( 'Title & description alignment', 'unapp' ),
						'group'   => 'layout',
						'choices' => $this->create_choices_array( 'title_align', $values['choices'] ),
						'default' => isset( $values['default'] ) ? $values['default'] : 'none',
					);
					$temp['groupType'] = $this->set_group_type( $temp['choices'] );

					$arr[ $key . '_row_title_align' ] = $temp;
					break;
				default:
					break;
			}// End switch().
		}// End foreach().

		return $arr;
	}
}
