<?php
$frontpage = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$grouping = array( 'values'   => $fields['services_grouping'], 'group_by' => 'service_title');

$fields['app_services'] = $frontpage->get_repeater_field( $fields['services_repeater_field'], array(), $grouping );

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'app_services', Unapp_Repeatable_Sections::get_instance() );
$parent_attr = array(
	'id'    => ! empty( $fields['services_section_unique_id'] ) ? array( $fields['services_section_unique_id'] ) : array(),
	'class' => array( 'colorlib-services', 'ewf-section' ),
	'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat' ),
);
?>

<section class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
    <div class="colorlib-services colorlib-bg-white">
	    <?php
	    $attr_helper->generate_video_overlay();
	    $attr_helper->generate_color_overlay();
	    ?>
        <div class="container">
	        <?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'app_services' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
            <div class="row">
				<?php
                if( $fields['app_services'] != '' ){
				    foreach ( $fields['app_services'] as $key => $service ){
					?>
                    <div class="col-md-4 text-center animate-box">
                        <div class="services">
							<span class="icon">
								<i class="<?php echo esc_html( $service[ 'service_icon' ] ); ?>"></i>
							</span>
                            <div class="desc">
                                <h3><?php echo esc_html( $service[ 'service_title' ] ); ?></h3>
                                <p><?php echo esc_html( $service[ 'service_desc' ] ); ?></p>
                            </div>
                        </div>
                    </div>
				<?php
				    }
                }
                ?>
            </div>
        </div>
    </div>
</section>