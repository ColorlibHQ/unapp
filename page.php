<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @since unapp 1.0
 */

get_header(); ?>

<?php unapp_page_header(); ?>

<div class="colorlib-blog">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 animate-box">
                <!-- MAIN CONTENT -->
                <div class="page-content">
			        <?php
			        while (have_posts()) : the_post();
				        the_content();
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
        </div>
    </div>
</div>

<?php get_footer(); ?>
