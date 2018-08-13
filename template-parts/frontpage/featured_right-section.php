<?php
$frontpage          = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields             = $frontpage->sections[ $section_id ];

$grouping           = array( 'values'   => $fields['featured_grouping'], 'group_by' => 'featured_icon');
$fields['featured_right'] = $frontpage->get_repeater_field( $fields['featured_repeater_field'], array(), $grouping );
$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'featured_right', Unapp_Repeatable_Sections::get_instance() );
$parent_attr = array(
	'id'    => ! empty( $fields['featured_right_section_unique_id'] ) ? array( $fields['featured_right_section_unique_id'] ) : array(),
	'class' => array( 'colorlib-work-featured-right', 'ewf-section' ),
	'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat' ),
);
?>

<div class="colorlib-work-featured colorlib-bg-white-right" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
    <div <?php $attr_helper->generate_attributes( $parent_attr ); ?>>
	    <?php
            $attr_helper->generate_color_overlay();
	        $section_content_col = ( $fields['featured_image'] ? '7' : '12' );
        ?>
        <div class="<?php echo esc_attr( Unapp_Helper::container_class( 'featured_right', $fields ) ); ?>">
		    <?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'featured_right' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
            <div class="row mobile-wrap">
	            <?php if ($fields['featured_right_row_title_align'] == 'right'){ ?>
                    <?php if( ! empty( $fields['featured_image'] ) ) { ?>
                        <div class="col-md-5 animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'featured_right_image_animate' ] ); ?>">
                            <div class="mobile-img">
                                <img src="<?php echo esc_url( $fields['featured_image'] ); ?>" alt="<?php echo wp_kses_post( $fields['featured_title'] ); ?>">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-md-<?php print $section_content_col; ?> animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'featured_right_text_animate' ] ); ?>">
                        <div class="desc">
                            <?php echo wp_kses_post( Unapp_Helper::generate_section_title( $fields['featured_title'] ) ); ?>
                            <?php
                            foreach ( $fields['featured_right'] as $key => $featured ){
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
                <?php
                }
                else{
	            ?>
                    <div class="col-md-<?php print $section_content_col; ?> animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'featured_right_text_animate' ] ); ?>">
                        <div class="desc">
                            <?php echo wp_kses_post( Unapp_Helper::generate_section_title( $fields['featured_title'] ) ); ?>
                            <?php
                            foreach ( $fields['featured_right'] as $key => $featured ){
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
                    <?php if( !empty( $fields['featured_image'] ) ) { ?>
                    <div class="col-md-5 animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'featured_right_image_animate' ] ); ?>">
                        <div class="mobile-img">
                            <img src="<?php echo esc_url( $fields['featured_image'] ); ?>" alt="<?php echo wp_kses_post( $fields['featured_title'] ); ?>">
                        </div>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>