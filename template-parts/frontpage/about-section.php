<?php
$frontpage = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'about', Unapp_Repeatable_Sections::get_instance() );

$parent_attr = array(
	'class' => array( 'ewf-section' ),
    'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat', 'background-parallax' ),
);

$section_content_col = ( $fields['about_image'] || $fields[ 'about_video_link' ] ? '6' : '12' );

$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'about' );

?>

<div class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">

    <div id="<?php echo $id ?>" <?php $attr_helper->generate_attributes( $parent_attr ); ?>>

        <?php if ( '' != $fields['about_background_video'] ): ?>
            <a class="player" data-property="{videoURL:'<?php echo esc_url( $fields[ 'about_background_video' ] ); ?>',containment:'#<?php echo $id ?>', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, quality:'default'}"></a>
        <?php endif ?>

        <?php $attr_helper->generate_color_overlay(); ?>

        <div class="<?php echo esc_attr( Unapp_Helper::container_class( 'about', $fields ) ); ?>">
            <div class="row about_top">
				<?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'about' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
				<?php if( $fields['about_row_title_align'] == 'left' ) { ?>
                    <div class="col-md-6 animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'about_image_animate' ] ); ?>">
                        <?php if( ! empty( $fields[ 'about_image' ] || $fields[ 'about_video_link' ] ) ){ ?>
                        <div class="video colorlib-video" style="background-image: url(<?php echo esc_url( $fields[ 'about_image' ] ); ?>);">
							<?php if( $fields[ 'about_video_link' ] != '' ){ ?>
                                <a href="<?php echo esc_url( $fields[ 'about_video_link' ] ); ?>" class="popup-vimeo"><i class="icon-play3"></i></a>
							<?php } ?>
                            <div class="overlay"></div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-<?php print $section_content_col; ?> animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'about_text_animate' ] ); ?>">
                        <h2><?php print $fields['about_title']; ?></h2>
                        <p><?php echo wp_kses_post( $fields['about_text'] ); ?></p>
                    </div>
				<?php } else {
					?>
                    <div class="col-md-<?php print $section_content_col; ?> animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'about_text_animate' ] ); ?>">
                        <h2><?php print $fields['about_title']; ?></h2>
                        <p><?php echo wp_kses_post( $fields['about_text'] ); ?></p>
                    </div>
                    <div class="col-md-6 animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'about_image_animate' ] ); ?>">
                        <div class="video colorlib-video" style="background-image: url(<?php echo esc_url( $fields[ 'about_image' ] ); ?>);">
							<?php if( $fields[ 'about_video_link' ] != '' ){ ?>
                                <a href="<?php echo esc_url( $fields[ 'about_video_link' ] ); ?>" class="popup-vimeo"><i class="icon-play3"></i></a>
							<?php } ?>
                            <div class="overlay"></div>
                        </div>
                    </div>
					<?php
				} ?>
            </div>
        </div>
    </div>
</div>