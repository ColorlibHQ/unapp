<?php
/**
 * The template for displaying archive pages
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
			<?php
			if( have_posts() ) :
				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile;
			endif;
			?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
