<?php
$frontpage = Epsilon_Page_Generator::get_instance_page( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];
?>

<div id="colorlib-contact" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
	<div class="container">
		<?php echo wp_kses( unapp_Helper::generate_pencil( 'unapp_Repeatable_Sections', 'contact' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
		<div class="row">
			<div class="col-md-4 col-md-push-8 animate-box">
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
			<div class="col-md-8 col-md-pull-4 animate-box">
				<h2><?php esc_html_e( 'Get In Touch', 'unapp' ); ?></h2>
				<?php echo do_shortcode( $fields[ 'contact_shortcode' ]) ; ?>
			</div>
		</div>
	</div>
</div>

<div id="map" class="map-location colorlib-map" data-lat="<?php print $fields[ 'contact_map_lat' ]; ?>" data-lng="<?php print $fields[ 'contact_map_unapp_long' ]; ?>" data-zoom="<?php print $fields[ 'contact_map_zoom' ]; ?>"></div>