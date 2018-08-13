<?php
/**
 * The template for displaying pages
 *
 * @package unapp
 */

get_header();

unapp_page_header();
?>
<?php
$layout = Unapp_Helper::get_layout( 'unapp_page_layout' );
if( class_exists( 'Epsilon_Page_Generator' ) ){
	$unapp_fp = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
}

if ( ! empty( $unapp_fp->sections ) ) :
	$unapp_fp->generate_output();
else :
	?>
    <div class="colorlib-blog">
        <div class="container">
            <div class="row">
				<?php if ( 'left-sidebar' === $layout['type'] && is_active_sidebar( 'sidebar-1' ) ) { ?>
                    <div class="col-sm-<?php echo esc_attr( $layout['columns']['sidebar']['span'] ); ?> animate-box">
						<?php get_sidebar(); ?>
                    </div>
				<?php } ?>
                <div class="<?php echo ( 1 === $layout['columnsCount'] && ! is_active_sidebar( 'sidebar' ) ) ? 'col-sm-12' : 'col-sm-' . esc_attr( $layout['columns']['content']['span'] ); ?> animate-box">
                    <!-- MAIN CONTENT -->
                    <div class="page-content">
						<?php
						while (have_posts()) : the_post();
							get_template_part( 'template-parts/content', 'page' );
						endwhile;
						?>
                    </div>
					<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'unapp' ),
						'after'  => '</div>',
					) );
					?>
					<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
                </div>
				<?php if ( 'right-sidebar' === $layout['type'] && is_active_sidebar( 'sidebar-1' ) ) { ?>
                    <div class="col-sm-<?php echo esc_attr( $layout['columns']['sidebar']['span'] ); ?> animate-box">
						<?php get_sidebar(); ?>
                    </div>
				<?php } ?>
            </div>
        </div>
    </div>
<?php
endif;
get_footer();
