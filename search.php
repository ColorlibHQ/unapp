<?php get_header(); ?>

<!-- Page Header -->
<?php unapp_page_header(); ?>
<!-- Page Header End -->

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
					?>
                    <div class="col-md-12 text-center">
						<?php
						the_posts_pagination( array(
							'prev_text'  => esc_html__( '&laquo;', 'unapp' ),
							'next_text'  => esc_html__( '&raquo;', 'unapp' ),
							'mid_size'   => 2,
							'screen_reader_text'    => '',
						) );
						?>
                    </div>
				<?php
				else: ?>
                    <h4 class="search-heading"><?php print esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'unapp' ); ?></h4>
                <?php
					get_search_form();
				endif;
				?>
            </div>
        </div>
    </div>

<?php get_footer(); ?>