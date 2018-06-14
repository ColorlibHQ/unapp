<?php
$frontpage = Epsilon_Page_Generator::get_instance_page( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];
?>
<div class="container" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
	<div class="row row-pb-lg about_top">
		<?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'collaborate' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
		<div class="col-md-6 animate-box">
			<div class="video colorlib-video" style="background-image: url(<?php echo esc_url( $fields[ 'about_image' ] ); ?>);">
				<?php if( $fields[ 'about_video_link' ] != '' ){ ?>
                    <a href="<?php echo esc_url( $fields[ 'about_video_link' ] ); ?>" class="popup-vimeo"><i class="icon-play3"></i></a>
				<?php } ?>
                <div class="overlay"></div>
			</div>
		</div>
		<div class="col-md-6 animate-box">
			<h2><?php print $fields['about_title']; ?></h2>
			<p><?php print $fields['about_text']; ?></p>

		</div>
	</div>
</div>