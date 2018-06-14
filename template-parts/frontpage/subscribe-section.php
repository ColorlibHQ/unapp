<?php
$frontpage  = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields     = $frontpage->sections[ $section_id ];

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'subscribe', Unapp_Repeatable_Sections::get_instance() );

$parent_attr = array(
	'id'    => ! empty( $fields['subscribe_section_unique_id'] ) ? array( $fields['subscribe_section_unique_id'] ) : array(),
	'class' => array( 'colorlib-subscribe', 'ewf-section' ),
	'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat' ),
);
?>

<section class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
	<?php echo wp_kses( unapp_Helper::generate_pencil( 'unapp_Repeatable_Sections', 'subscribe' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
    <div id="colorlib-subscribe" <?php $attr_helper->generate_attributes( $parent_attr ); ?> >
        <div class="overlay"></div>
	    <?php
	    $attr_helper->generate_video_overlay();
	    $attr_helper->generate_color_overlay();
	    ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 text-center colorlib-heading animate-box">
	                <?php
                        echo wp_kses_post(
                            Unapp_Helper::generate_section_title(
                                $fields['subscribe_title'],
                                $fields['subscribe_subtitle'],
                                array(
                                    'doubled' => false,
                                    'center'  => false,
                                )
                            )
                        );
	                ?>
                </div>
            </div>
            <div class="row animate-box">
                <div class="col-md-6 col-md-offset-3">
                    <div class="row">
                        <div class="col-md-12">
	                        <?php echo do_shortcode( $fields[ 'subscribe_shortcode' ] ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>