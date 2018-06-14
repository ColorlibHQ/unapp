<?php
// Don't allow directly access this file.
if( !defined( 'ABSPATH' ) ) exit();
/**
 * Template Name: Portfolio grid
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
			$portfolio_query = new WP_Query( $arg );
			while ( $portfolio_query->have_posts() ) :
			$portfolio_query->the_post();
			?>
			<div class="col-md-4 animate-box">
				<div class="work-grid">
					<a href="<?php the_permalink(); ?>" class="work-img">
						<?php the_post_thumbnail( 'unapp-portfolio-thumb' ); ?>
						<span class="icon"><i class="icon-plus2"></i></span>
					</a>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
