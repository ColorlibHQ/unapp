<?php
$frontpage          = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields             = $frontpage->sections[ $section_id ];
$grouping           = array( 'values'   => $fields['slider_grouping'], 'group_by' => 'slides_title');
$fields['slider'] = $frontpage->get_repeater_field( 'unapp_slides', array(), $grouping );
$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'slider' );

?>

<div id="<?php echo $id ?>" class="colorlib-featured" data-customizer-section-id="unapp_repeatable_section" data-saection="<?php echo esc_attr( $section_id ); ?>">
	<?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'slider' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
    <div class="row animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'slider_animate' ] ); ?>">
		<div class="featured-wrap">
			<div class="unapp_slider owl-carousel">
                <?php
                foreach ( $fields['slider'] as $key => $value ){
                ?>
				<div class="item">
					<div class="col-md-8 col-md-offset-2">
						<div class="featured-entry">
							<img class="img-responsive" src="<?php echo esc_url( $value['slides_image'] ); ?>" alt="<?php echo wp_kses_post( $value[ 'slides_title' ] ); ?>">
						</div>
					</div>
				</div>
                <?php } ?>
			</div>
		</div>
	</div>
</div>