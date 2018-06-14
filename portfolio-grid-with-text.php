<?php
if( ! defined( 'ABSPATH' ) ) exit();
/*
 * Template Name: Portfolio grid with text
 */
get_header();
?>

<?php unapp_page_header(); ?>

<div class="colorlib-work">
	<div class="container">
		<div class="row">
			<?php
			$arg = array(
				'post_type' => 'portfolio',
			);
			$portfolio_query = new WP_Query( $arg );
			if( $portfolio_query->have_posts() ) :
			while ( $portfolio_query->have_posts() ) :
				$portfolio_query->the_post();
			?>
			<div class="col-md-4 animate-box">
				<div class="work-grid">
					<a href="<?php the_permalink(); ?>" class="work-img">
						<?php the_post_thumbnail( 'unapp-portfolio-thumb' ); ?>
						<span class="icon"><i class="icon-plus2"></i></span>
					</a>
					<div class="desc">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p><?php echo wp_trim_words( get_the_content(), 18, '...' ); ?></p>
					</div>
				</div>
			</div>
			<?php
			endwhile;
			endif;
			?>
	</div>
</div>

<?php get_footer(); ?>
