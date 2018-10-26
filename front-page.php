<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @version 1.0
 */
get_header();

$show_on_front = get_option( 'show_on_front' );
if ( 'posts' === $show_on_front ) { ?>

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
                        else:
	                        get_template_part( 'template-parts/content', 'none' );
                    endif;
                ?>
			</div>
		</div>
	</div>
	
<?php
} else {

    $unapp_builder = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );

    if ( ! empty( $unapp_builder->sections ) ) {

    	$unapp_builder->generate_output();
    	
    }else{
    	unapp_page_header(); ?>

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
	                        else:
		                        get_template_part( 'template-parts/content', 'none' );
	                    endif;
	                ?>
				</div>
			</div>
		</div>
	<?php
    }
    
}

get_footer();
