<?php
// Don't allow directly access this file.
if( !defined( 'ABSPATH' ) ) exit();
/**
 * Template Name: Portfolio
 */
get_header(); ?>

<?php unapp_page_header(); ?>
<div class="colorlib-work">
    <div class="container">
        <div class="row">
	        <?php
	        $arg = array(
		        'post_type' => 'portfolio',
	        );
	        $counter = 0;
	        $portfolio_query = new WP_Query( $arg );
	        while ( $portfolio_query->have_posts() ) :
	        $portfolio_query->the_post();
	        $counter ++;
	        if( $counter % 2 == 0 ):
	        ?>
                <div class="col-md-12">
                    <div class="work-flex">
                        <div class="half animate-box">
                            <div class="row no-gutters">
                                <div class="col-md-12 no-gutters">
                                    <a href="<?php the_permalink(); ?>" class="work-img">
								        <?php the_post_thumbnail( 'unapp-portfolio-big' ); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="half animate-box">
                            <div class="row no-gutters">
                                <div class="col-md-12 no-gutters">
                                    <div class="display-t desc">
                                        <div class="display-tc">
                                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            <p><?php echo wp_trim_words( get_the_content(), 18, '...' ); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-12">
                    <div class="work-flex">
                        <div class="half animate-box">
                            <div class="row no-gutters">
                                <div class="col-md-12 col-md-push-12 no-gutters">
                                    <a href="<?php the_permalink(); ?>" class="work-img">
								        <?php the_post_thumbnail( 'unapp-portfolio-big' ); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="half animate-box">
                            <div class="row no-gutters">
                                <div class="col-md-12 col-md-pull-12 no-gutters">
                                    <div class="display-t desc">
                                        <div class="display-tc">
                                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            <p><?php echo wp_trim_words( get_the_content(), 18, '...' ); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                endif;
                endwhile;
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
