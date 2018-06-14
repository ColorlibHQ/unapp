<?php
/**
 * The template for displaying all single portfolio posts
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
			<div class="col-md-8 animate-box">
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
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
