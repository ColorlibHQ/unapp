<?php
$frontpage          = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields             = $frontpage->sections[ $section_id ];

$grouping           = array( 'values'   => $fields['counter_grouping'], 'group_by' => 'counter_title');
$fields['counter'] = $frontpage->get_repeater_field( $fields['counter_repeater_field'], array(), $grouping );
$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'counter', Unapp_Repeatable_Sections::get_instance() );

$parent_attr = array(
	'id'    => ! empty( $fields['counter_section_unique_id'] ) ? array( $fields['counter_section_unique_id'] ) : array(),
	'class' => array( 'colorlib-counters', 'ewf-section' ),
	'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat' ),
);
$parent_attr_2 = array(
	'id'    => ! empty( $fields['counter_section_unique_id'] ) ? array( $fields['counter_section_unique_id'] ) : array(),
	'class' => array( 'colorlib-counters', 'ewf-section' ),
);
$span      = 12 / absint( $fields['counter_column_group'] );
?>
<section class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
    <div <?php $attr_helper->generate_attributes( $parent_attr_2 ); ?>>
        <div id="colorlib-counter" class="colorlib-counters" <?php $attr_helper->generate_attributes( $parent_attr ); ?>>
            <div class="overlay"></div>
		    <?php
		    $attr_helper->generate_video_overlay();
		    $attr_helper->generate_color_overlay();
		    ?>
            <div class="<?php echo esc_attr( Unapp_Helper::container_class( 'counter', $fields ) ); ?>">
	            <?php echo wp_kses( Unapp_Helper::generate_pencil( 'unapp_Repeatable_Sections', 'counter' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
                <div class="row">
	                <?php
	                if ( ! empty( $fields['counter'] ) ){
		                foreach ( $fields['counter'] as $key => $counter ){
			                ?>
                            <div class="col-md-<?php echo absint( $span ); ?> animate-box" data-animate-effect="<?php echo esc_attr( $counter[ 'counter_animate' ] ); ?>">
                                <div class="counter-entry">
                                    <span class="icon"><i class="flaticon-ribbon"></i></span>
                                    <div class="desc">
                                        <span class="colorlib-counter js-counter" data-from="0" data-to="<?php echo esc_attr( $counter[ 'counter_title' ] ); ?>" data-speed="5000" data-refresh-interval="50"></span>
                                        <span class="colorlib-counter-label"><?php echo wp_kses_post( $counter[ 'counter_description' ] ); ?></span>
                                    </div>
                                </div>
                            </div>
                    <?php } } ?>
                </div>
            </div>
        </div>
    </div>
</section>