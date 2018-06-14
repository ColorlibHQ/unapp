<?php
$frontpage          = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields             = $frontpage->sections[ $section_id ];

$grouping           = array( 'values'   => $fields['slider_grouping'], 'group_by' => 'slides_title');
$fields['slider'] = $frontpage->get_repeater_field( $fields['slider_repeater_field'], array(), $grouping );
?>

<div class="colorlib-featured" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
	<?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'slider' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
    <div class="row animate-box">
		<div class="featured-wrap">
			<div class="owl-carousel">
                <?php
                foreach ( $fields['slider'] as $key => $slider ){
                ?>
				<div class="item">
					<div class="col-md-8 col-md-offset-2">
						<div class="featured-entry">
							<img class="img-responsive" src="<?php echo esc_url( $slider['slides_image'] ); ?>" alt="<?php echo wp_kses_post( $slider[ 'slides_title' ] ); ?>">
						</div>
					</div>
				</div>
                <?php } ?>
			</div>
		</div>
	</div>
</div>