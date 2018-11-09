<?php
$frontpage = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$grouping = array( 'values'   => $fields['services_grouping'], 'group_by' => 'service_title');
$fields['services'] = $frontpage->get_repeater_field( 'unapp_services', array(), $grouping );

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'services', Unapp_Repeatable_Sections::get_instance() );
$parent_attr = array(
	'class' => array( 'colorlib-services', 'ewf-section' ),
	'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat', 'background-parallax' ),
);
$span      = 12 / absint( $fields['services_column_group'] );

$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'services' );

?>

<section class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
	<?php $attr_helper->generate_color_overlay(); ?>
    <div id="<?php echo $id ?>" <?php $attr_helper->generate_attributes( $parent_attr ); ?>>

    	<?php if ( '' != $fields['services_background_video'] ): ?>
			<a class="player" data-property="{videoURL:'<?php echo esc_url( $fields[ 'services_background_video' ] ); ?>',containment:'#<?php echo $id ?>', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, quality:'default'}"></a>
		<?php endif ?>

	    

        <div class="<?php echo esc_attr( Unapp_Helper::container_class( 'services', $fields ) ); ?>">
		    <?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'services' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
            <div class="row">
			    <?php
			    if( $fields['services'] != '' ){
				    foreach ( $fields['services'] as $key => $service ){ ?>
                        <div class="col-md-<?php echo absint( $span ); ?> animate-box" data-animate-effect="<?php echo esc_attr( $service[ 'services_animate' ] ); ?>">
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