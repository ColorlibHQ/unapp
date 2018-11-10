<?php
$frontpage = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'google_map', Unapp_Repeatable_Sections::get_instance() );

$parent_attr = array(
	'class' => array( 'ewf-section' ),
);

$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'google-map' );

$adress = '';

if ( '' != $fields['google_map_address'] ) {
    $adress = urlencode( $fields['google_map_address'] );
}

if ( '' != $fields['google_map_lat'] && '' != $fields['google_map_long'] ) {
    $adress = $fields['google_map_lat'] . ',' . $fields['google_map_long'];
}

?>

<div class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">

    <div id="<?php echo $id ?>" <?php $attr_helper->generate_attributes( $parent_attr ); ?>>

        <div class="<?php echo esc_attr( Unapp_Helper::container_class( 'google_map', $fields ) ); ?>">
            <?php echo wp_kses( Unapp_Helper::generate_pencil( 'Unapp_Repeatable_Sections', 'google_map' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
            <?php if ( '' != $adress ): ?>
                <iframe class="portum-map" src="https://maps.google.com/maps?q=<?php echo esc_attr( $adress ); ?>&hl=en&z=<?php echo esc_attr( $fields['google_map_zoom'] ); ?>&amp;output=embed" width="100%" height="<?php echo $fields['google_map_height']; ?>" frameborder="0" style="border:0" allowfullscreen></iframe>
            <?php endif ?>
        </div>

    </div>
    
</div>

