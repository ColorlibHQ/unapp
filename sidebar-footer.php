<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @since unapp 1.0
 */

$unapp_sidebar = array(
    'footer-sidebar-1',
    'footer-sidebar-2',
    'footer-sidebar-3',
    'footer-sidebar-4',
);

$sidebars = array();
foreach ( $unapp_sidebar as $column ){
    if( is_active_sidebar( $column ) ) {
        $sidebars[] = $column;
    }
}

$footer_layout = get_theme_mod( 'unapp_footer_columns', false );

if( ! $footer_layout ){
	$footer_layout = Unapp_Helper::get_footer_default();
}

if ( ! is_array( $footer_layout ) ) {
	$footer_layout = json_decode( $footer_layout, true );
}
?>

<?php
if( ! empty( $sidebars ) ) {
	foreach ( $footer_layout['columns'] as $sidebar ) {

		if ( is_active_sidebar( 'footer-sidebar-' . $sidebar['index'] ) ) {
			?>
            <div id="footer-widget-<?php echo esc_attr( $sidebar['span'] ); ?>" class="col-md-<?php echo esc_attr( $sidebar['span'] ); ?> colorlib-widget">
				<?php dynamic_sidebar( 'footer-sidebar-' . $sidebar['index'] ); ?>
            </div>
			<?php
		}
	}
}
?>