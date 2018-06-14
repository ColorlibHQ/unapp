<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 */

get_header(); ?>

<?php unapp_page_header(); ?>
<div class="colorlib-blog">
    <div class="container">
        <div class="row">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'unapp' ); ?></h1>
            </header><!-- .page-header -->
            <div class="page-content">
                <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'unapp' ); ?></p>
		        <?php get_search_form(); ?>
            </div><!-- .page-content -->
        </div>
    </div>
</div>
<?php get_footer(); ?>
