<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @since unapp 1.0
 */

get_header(); ?>

<?php unapp_page_header(); ?>

<div class="colorlib-blog">
	<div class="container">
		<div class="row">
            <?php $layout = Unapp_Helper::get_layout(); ?>
			<div class="<?php echo ( 1 === $layout['columnsCount'] && ! is_active_sidebar( 'sidebar-1' ) ) ? 'col-sm-12' : 'col-sm-' . esc_attr( $layout['columns']['content']['span'] ); ?> animate-box">
				<?php
				/* Start the Loop */
				if( have_posts() ) :
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content', get_post_format() );
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile;
				endif; // End loop
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

<?php get_footer(); ?>
