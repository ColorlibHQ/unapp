<?php
$frontpage = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'banner', Unapp_Repeatable_Sections::get_instance() );

$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'home' );

$parent_attr = array(
	'class' => array( 'video-hero', 'ewf-section' ),
	'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat', 'background-parallax' ),
);

?>

<section id="<?php echo $id ?>" <?php $attr_helper->generate_attributes( $parent_attr ); ?> data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
	<?php $attr_helper->generate_color_overlay(); ?>
	
	<?php if ( '' != $fields['banner_background_video'] ): ?>
		<a class="player" data-property="{videoURL:'<?php echo esc_url( $fields[ 'banner_background_video' ] ); ?>',containment:'#<?php echo $id ?>', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, quality:'default'}"></a>
	<?php endif ?>
	
	<div class="display-t text-center">
		<div class="display-tc">
			<div class="container">
				<?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'banner' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
				<div class="col-md-12 col-md-offset-0">
					<div class="animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'banner_animate' ] ); ?>">
						<?php
						echo wp_kses_post(
							Unapp_Helper::generate_section_title(
								$fields['banner_title'],
								$fields['banner_subtitle'],
								array(
									'doubled' => false,
									'center'  => false,
								)
							)
						);
						?>
                        <?php if( $fields[ 'banner_button_text' ] != '' ){ ?>
						<p><a href="<?php echo esc_url( $fields['banner_button_link'] ); ?>" class="btn btn-primary btn-lg btn-custom"><?php echo wp_kses_post( $fields[ 'banner_button_text' ] ); ?></a></p>
                        <?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>