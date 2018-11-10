<?php
$frontpage          = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields             = $frontpage->sections[ $section_id ];

$grouping           = array( 'values'   => $fields['features_grouping'], 'group_by' => 'features_icon');
$fields['features'] = $frontpage->get_repeater_field( 'unapp_features', array(), $grouping );

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'features', Unapp_Repeatable_Sections::get_instance() );
$parent_attr = array(
	'class' => array( 'colorlib-work-featured-left', 'ewf-section' ),
	'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat', 'background-parallax' ),
);

$section_content_col = ( $fields['features_image'] ? '7' : '12' );

$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'features' );

?>

<div class="colorlib-work-featured colorlib-work-featured-left colorlib-bg-white" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
    <div id="<?php echo $id ?>" <?php $attr_helper->generate_attributes( $parent_attr ); ?>>

        <?php if ( '' != $fields['features_background_video'] ): ?>
            <a class="player" data-property="{videoURL:'<?php echo esc_url( $fields[ 'features_background_video' ] ); ?>',containment:'#<?php echo $id ?>', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, quality:'default'}"></a>
        <?php endif ?>

	    <?php $attr_helper->generate_color_overlay(); ?>

        <div class="<?php echo esc_attr( Unapp_Helper::container_class( 'features', $fields ) ); ?>">
		    <?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'features' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
            <div class="row mobile-wrap">
	            <?php if ( $fields['features_row_title_align'] == 'left' ){ ?>
                <?php if( ! empty( $fields['features_image'] ) ) { ?>
                <div class="col-md-5 animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'features_image_animate' ] ); ?>">
                    <div class="mobile-img">
                        <img src="<?php echo esc_url( $fields['features_image'] ); ?>" alt="<?php echo wp_kses_post( $fields['features_title'] ); ?>">
                    </div>
                </div>
                <?php } ?>
                <div class="col-md-<?php print $section_content_col; ?> animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'features_text_animate' ] ); ?>">
                    <div class="desc">
					    <?php echo wp_kses_post( Unapp_Helper::generate_section_title( $fields['features_title'] ) ); ?>
					    <?php
					    foreach ( $fields['features'] as $key => $featured ){
						    ?>
                            <div class="features">
                                <span class="icon"><i class="<?php echo esc_attr( $featured[ 'features_icon' ] ); ?>"></i></span>
                                <div class="f-desc">
                                    <p><?php echo esc_html( $featured[ 'features_description' ] ); ?></p>
                                </div>
                            </div>
					    <?php } ?>
                        <p><a href="<?php echo esc_url( $fields['features_btn_link'] ); ?>" class="btn btn-primary btn-outline with-arrow"><?php echo wp_kses_post( $fields['features_btn_text'] ); ?> <i class="icon-arrow-right3"></i></a></p>
                    </div>
                </div>
                <?php } else {
	                ?>
                    <div class="col-md-<?php print $section_content_col; ?> animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'features_text_animate' ] ); ?>">
                        <div class="desc">
				            <?php echo wp_kses_post( Unapp_Helper::generate_section_title( $fields['features_title'] ) ); ?>
				            <?php
				            foreach ( $fields['features'] as $key => $featured ){
					            ?>
                                <div class="features">
                                    <span class="icon"><i class="<?php echo esc_attr( $featured[ 'features_icon' ] ); ?>"></i></span>
                                    <div class="f-desc">
                                        <p><?php echo esc_html( $featured[ 'features_description' ] ); ?></p>
                                    </div>
                                </div>
				            <?php } ?>
                            <p><a href="<?php echo esc_url( $fields['features_btn_link'] ); ?>" class="btn btn-primary btn-outline with-arrow"><?php echo wp_kses_post( $fields['features_btn_text'] ); ?> <i class="icon-arrow-right3"></i></a></p>
                        </div>
                    </div>
	                <?php if( ! empty( $fields['features_image'] ) ) { ?>
                        <div class="col-md-5 animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'features_image_animate' ] ); ?>">
                            <div class="mobile-img">
                                <img src="<?php echo esc_url( $fields['features_image'] ); ?>"
                                     alt="<?php echo wp_kses_post( $fields['features_title'] ); ?>">
                            </div>
                        </div>
			            <?php
		            }
                } ?>
            </div>
        </div>
    </div>
</div>