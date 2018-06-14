<?php
$frontpage = Epsilon_Page_Generator::get_instance_page( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$grouping = array( 'values'   => $fields['services_page_grouping'], 'group_by' => 'service_page_title');

$fields['pageservices'] = $frontpage->get_repeater_field( $fields['services_page_repeater_field'], array(), $grouping );
?>

<section class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
	<?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'pageservices' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
    <div class="colorlib-services colorlib-bg-white">
        <div class="container">
            <div class="row">
				<?php
				foreach ( $fields['pageservices'] as $key => $service ){
					?>
                    <div class="col-md-4 text-center animate-box">
                        <div class="services">
							<span class="icon">
								<i class="<?php echo esc_html( $service[ 'service_page_icon' ] ); ?>"></i>
							</span>
                            <div class="desc">
                                <h3><?php echo esc_html( $service[ 'service_page_title' ] ); ?></h3>
                                <p><?php echo esc_html( $service[ 'service_page_desc' ] ); ?></p>
                            </div>
                        </div>
                    </div>
				<?php } ?>
            </div>
        </div>
    </div>
</section>