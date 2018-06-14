<?php
$frontpage          = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields             = $frontpage->sections[ $section_id ];

$grouping           = array( 'values'   => $fields['featured_grouping'], 'group_by' => 'featured_icon');
$fields['featured_left'] = $frontpage->get_repeater_field( $fields['featured_repeater_field'], array(), $grouping );

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'featured_left', Unapp_Repeatable_Sections::get_instance() );
?>

<div class="colorlib-work-featured colorlib-work-featured-left colorlib-bg-white" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
    <?php $attr_helper->generate_color_overlay(); ?>
    <div class="container">
	    <?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'featured_left' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
        <div class="row mobile-wrap">
            <div class="col-md-5 animate-box">
                <div class="mobile-img">
                    <img src="<?php echo esc_url( $fields['featured_image'] ); ?>" alt="<?php echo wp_kses_post( $fields['featured_title'] ); ?>">
                </div>
            </div>
            <div class="col-md-7 animate-box">
                <div class="desc">
                    <?php echo wp_kses_post( Unapp_Helper::generate_section_title( $fields['featured_title'] ) ); ?>
                    <?php
                        foreach ( $fields['featured_left'] as $key => $featured ){
                    ?>
                    <div class="features">
                        <span class="icon"><i class="<?php echo esc_attr( $featured[ 'featured_icon' ] ); ?>"></i></span>
                        <div class="f-desc">
                            <p><?php echo esc_html( $featured[ 'featured_description' ] ); ?></p>
                        </div>
                    </div>
                    <?php } ?>
                    <p><a href="<?php echo esc_url( $fields['featured_btn_link'] ); ?>" class="btn btn-primary btn-outline with-arrow"><?php echo wp_kses_post( $fields['featured_btn_text'] ); ?> <i class="icon-arrow-right3"></i></a></p>
                </div>
            </div>
        </div>
<!--        -->
    </div>
</div>