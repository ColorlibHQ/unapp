<?php
$frontpage = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$grouping           = array( 'values'   => $fields['pricing_grouping'], 'group_by' => 'price_box_title');
$fields['pricing'] = $frontpage->get_repeater_field( $fields['pricing_repeater_field'], array(), $grouping );

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'pricing', Unapp_Repeatable_Sections::get_instance() );
$parent_attr = array(
	'class' => array( 'colorlib-pricing', 'ewf-section' ),
	'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat', 'background-parallax' ),
);

$span      = 12 / absint( $fields['pricing_column_group'] );

$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'pricing' );

?>

<section class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
    <div id="<?php echo $id ?>" <?php $attr_helper->generate_attributes( $parent_attr ); ?>>

    	<?php if ( '' != $fields['pricing_background_video'] ): ?>
			<a class="player" data-property="{videoURL:'<?php echo esc_url( $fields[ 'pricing_background_video' ] ); ?>',containment:'#<?php echo $id ?>', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, quality:'default'}"></a>
		<?php endif ?>

	    <?php $attr_helper->generate_color_overlay(); ?>

        <div class="<?php echo esc_attr( Unapp_Helper::container_class( 'pricing', $fields ) ); ?>">
	        <?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'pricing' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
	                <?php
	                echo wp_kses_post(
		                Unapp_Helper::generate_section_title(
			                $fields['pricing_title'],
			                $fields['pricing_subtitle'],
			                array(
				                'doubled' => false,
				                'center'  => false,
			                )
		                )
	                );
	                ?>
                </div>
            </div>
            <div class="row">
				<?php
				foreach ( $fields['pricing'] as $key => $pricing ){
					?>
                    <div class="col-md-<?php echo absint( $span ); ?> animate-box" data-animate-effect="<?php echo esc_attr( $pricing[ 'pricing_animate' ] ); ?>">
                        <div class="pricing">
                            <h2 class="pricing-heading"><?php echo wp_kses_post( $pricing[ 'price_box_title' ] ); ?></h2>
                            <div class="price"><sup class="currency"><?php echo wp_kses_post( $pricing[ 'price_box_currency' ] ); ?></sup><?php echo wp_kses_post( $pricing[ 'price_box_price' ] ); ?><small>per <?php echo wp_kses_post( $pricing[ 'price_box_period' ] ); ?></small></div>
                            <p><?php echo wp_kses_post( $pricing[ 'price_box_features' ] ); ?></p>
                            <p><a href="<?php echo esc_url( $pricing[ 'price_box_url' ] ); ?>" class="btn btn-primary"><?php echo wp_kses_post( $pricing[ 'price_btn_text' ] ); ?></a></p>
                        </div>
                    </div>
				<?php } ?>
            </div>
        </div>
    </div>
</section>