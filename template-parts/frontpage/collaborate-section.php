<?php
$frontpage = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'collaborate', Unapp_Repeatable_Sections::get_instance() );

$parent_attr = array(
	'class' => array( 'colorlib-intro', 'ewf-section' ),
	'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat', 'background-parallax' ),
);

$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'collaborate' );

?>
<section class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
    <div id="<?php echo $id ?>" <?php $attr_helper->generate_attributes( $parent_attr ); ?>>

        <?php $attr_helper->generate_color_overlay(); ?>

        <?php if ( '' != $fields['collaborate_background_video'] ): ?>
            <a class="player" data-property="{videoURL:'<?php echo esc_url( $fields[ 'collaborate_background_video' ] ); ?>',containment:'#<?php echo $id ?>', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, quality:'default'}"></a>
        <?php endif ?>

        <div class="<?php echo esc_attr( Unapp_Helper::container_class( 'collaborate', $fields ) ); ?>">
	        <?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'collaborate' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'collaborate_text_animate' ] ); ?>">
	                <?php
                        echo wp_kses_post(
                            Unapp_Helper::generate_section_title(
                                $fields['collaborate_title'],
                                $fields['collaborate_subtitle'],
                                array(
                                    'doubled' => false,
                                    'center'  => false,
                                )
                            )
                        );
	                ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'collaborate_video_animate' ] ); ?>">
                    <span class="play"><a href="<?php echo esc_url( $fields[ 'collaborate_video_link' ] ); ?>" class="pulse popup-vimeo"><i class="icon-play3"></i></a></span>
                </div>
            </div>
        </div>
    </div>
</section>