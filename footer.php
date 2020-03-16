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

    if( ! empty( $sidebars ) ) {
        ?>
        <footer id="colorlib-footer">
            <div class="container">
                <div class="row row-pb-md">
                    <?php
                    foreach ($footer_layout['columns'] as $sidebar) {

                        if (is_active_sidebar('footer-sidebar-' . $sidebar['index'])) {
                            ?>
                            <div id="footer-widget-<?php echo esc_attr($sidebar['span']); ?>"
                                 class="col-md-<?php echo esc_attr($sidebar['span']); ?> colorlib-widget">
                                <?php dynamic_sidebar('footer-sidebar-' . $sidebar['index']); ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </footer>
        <?php
    }

    if( get_theme_mod( 'unapp_enable_footer_copyright', 'true' ) ) { ?>
        <div class="copy">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p>
                            <?php echo wp_kses_post(get_theme_mod( 'unapp_copyright_contents',
                                'Copyright &copy;' . date("Y") . '
                            All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i>
                            by <a href="https://colorlib.com" target="_blank">Colorlib</a>') ); ?>
                        </p>
                        
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

</div><!-- end #page -->

<?php if( get_theme_mod( 'unapp_enable_go_top', true ) ) { ?>
<div class="gototop js-top">
	<a href="#" class="js-gotop"><i class="icon-arrow-up2"></i></a>
</div>
<?php } ?>

<?php wp_footer(); ?>
</body>
</html>
