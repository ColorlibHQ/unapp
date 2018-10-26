<?php
$frontpage = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'contact', Unapp_Repeatable_Sections::get_instance() );
$parent_attr = array(
	'class' => array( 'colorlib-contact', 'ewf-section' ),
    'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat', 'background-parallax' ),
);

$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'contact' );

?>

<div class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">

    <div id="<?php echo $id ?>" <?php $attr_helper->generate_attributes( $parent_attr ); ?>>

        <?php if ( '' != $fields['contact_background_video'] ): ?>
            <a class="player" data-property="{videoURL:'<?php echo esc_url( $fields[ 'contact_background_video' ] ); ?>',containment:'#<?php echo $id ?>', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, quality:'default'}"></a>
        <?php endif ?>

        <?php $attr_helper->generate_color_overlay(); ?>

        <div class="<?php echo esc_attr( Unapp_Helper::container_class( 'contact', $fields ) ); ?>">
		    <?php echo wp_kses( unapp_Helper::generate_pencil( 'unapp_Repeatable_Sections', 'contact' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
            <div class="row">
	            <?php if ( $fields['contact_row_title_align'] == 'left' ){ ?>
                <div class="col-md-4 animate-box">
                    <h2><?php esc_html_e( 'Contact Information', 'unapp' ); ?></h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="contact-info-wrap-flex">
							    <?php if( $fields[ 'contact_address' ] != '' ){ ?>
                                    <div class="con-info">
                                        <p><span><i class="icon-location-2"></i></span> <?php echo wp_kses_post( $fields[ 'contact_address' ] ); ?></p>
                                    </div>
							    <?php } ?>
							    <?php if( $fields[ 'contact_phone' ] != '' ){ ?>
                                    <div class="con-info">
                                        <p><span><i class="icon-phone3"></i></span> <a href="#"><?php echo wp_kses_post( $fields[ 'contact_phone' ] ); ?></a></p>
                                    </div>
							    <?php } ?>
							    <?php if( $fields[ 'contact_email' ] != '' ){ ?>
                                    <div class="con-info">
                                        <p><span><i class="icon-paperplane"></i></span> <a href="<?php echo wp_kses_post( $fields[ 'contact_email' ] ); ?>"><?php echo wp_kses_post( $fields[ 'contact_email' ] ); ?></a></p>
                                    </div>
							    <?php } ?>
							    <?php if( $fields[ 'contact_web' ] != '' ){ ?>
                                <div class="con-info">
                                    <p><span><i class="icon-globe"></i></span> <a href="<?php echo wp_kses_post( $fields[ 'contact_web' ] ); ?>"><?php echo wp_kses_post( $fields[ 'contact_web' ] ); ?></a></p>
                                </div>
							    <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 animate-box">
                    <h2><?php esc_html_e( 'Get In Touch', 'unapp' ); ?></h2>
				    <?php echo do_shortcode( $fields[ 'contact_shortcode' ]) ; ?>
                </div>
                <?php } else {
	                ?>
                    <div class="col-md-8 animate-box">
                        <h2><?php esc_html_e( 'Get In Touch', 'unapp' ); ?></h2>
			            <?php echo do_shortcode( $fields[ 'contact_shortcode' ]) ; ?>
                    </div>
                    <div class="col-md-4 animate-box">
                        <h2><?php esc_html_e( 'Contact Information', 'unapp' ); ?></h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="contact-info-wrap-flex">
						            <?php if( $fields[ 'contact_address' ] != '' ){ ?>
                                        <div class="con-info">
                                            <p><span><i class="icon-location-2"></i></span> <?php echo wp_kses_post( $fields[ 'contact_address' ] ); ?></p>
                                        </div>
						            <?php } ?>
						            <?php if( $fields[ 'contact_phone' ] != '' ){ ?>
                                        <div class="con-info">
                                            <p><span><i class="icon-phone3"></i></span> <a href="#"><?php echo wp_kses_post( $fields[ 'contact_phone' ] ); ?></a></p>
                                        </div>
						            <?php } ?>
						            <?php if( $fields[ 'contact_email' ] != '' ){ ?>
                                        <div class="con-info">
                                            <p><span><i class="icon-paperplane"></i></span> <a href="<?php echo wp_kses_post( $fields[ 'contact_email' ] ); ?>"><?php echo wp_kses_post( $fields[ 'contact_email' ] ); ?></a></p>
                                        </div>
						            <?php } ?>
						            <?php if( $fields[ 'contact_web' ] != '' ){ ?>
                                        <div class="con-info">
                                            <p><span><i class="icon-globe"></i></span> <a href="<?php echo wp_kses_post( $fields[ 'contact_web' ] ); ?>"><?php echo wp_kses_post( $fields[ 'contact_web' ] ); ?></a></p>
                                        </div>
						            <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                } ?>
            </div>
        </div>
    </div>
</div>
