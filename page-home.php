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
/**
* Template Name: Home
*/
get_header();

$show_on_front = get_option( 'show_on_front' );
if ( 'posts' === $show_on_front ) :

	$img = get_custom_header();
	$img       = $img->url;

	$layout = Unapp_Helper::get_layout();
	?>
<h1>This is front page</h1>
	<div id="content">
		<div id="page-header" <?php echo ( ! empty( $img ) ) ? 'style="background-image:url(' . esc_url( $img ) . '"' : ''; ?>>
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<h2><?php echo esc_html( get_option( 'blogname', 'MedZone_Lite' ) ); ?></h2>
						<?php
						$display = get_bloginfo( 'description', 'display' );
						if ( ! empty( $display ) ) :
							?>
							<p>
								<span class="text-accent-color"><?php echo wp_kses_post( $display ); /* WPCS: xss ok. */ ?></span>
							</p>
						<?php
						endif;
						?>
					</div><!-- end .col -->
				</div><!-- end .row -->
			</div><!-- end .container -->
		</div><!-- end #page-header -->

		<div class="container">
			<div class="row">
				<?php
				if ( 'left-sidebar' === $layout['type'] && is_active_sidebar( 'sidebar' ) ) {
					?>
					<div class="col-sm-<?php echo esc_attr( $layout['columns']['sidebar']['span'] ); ?>">
						<?php dynamic_sidebar( 'sidebar' ); ?>
					</div>
					<?php
				}
				?>

				<div class="<?php echo ( 1 === $layout['columnsCount'] && ! is_active_sidebar( 'sidebar' ) ) ? 'col-sm-12' : 'col-sm-' . esc_attr( $layout['columns']['content']['span'] ); ?>">
					<?php
					if ( have_posts() ) :

						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content/content', get_post_format() );
						endwhile;

					endif;

					the_posts_pagination(
						array(
							'prev_text' => '<span class="fa fa-angle-left"></span><span class="screen-reader-text">' . esc_html__( 'Previous', 'unapp' ) . '</span>',
							'next_text' => '<span class="fa fa-angle-right"></span><span class="screen-reader-text">' . esc_html__( 'Next', 'unapp' ) . '</span>',
						)
					);
					?>
				</div>

				<?php
				if ( 'right-sidebar' === $layout['type'] && is_active_sidebar( 'sidebar' ) ) {
					?>
					<div class="col-sm-<?php echo esc_attr( $layout['columns']['sidebar']['span'] ); ?>">
						<?php dynamic_sidebar( 'sidebar' ); ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
<?php
else :
    if( class_exists( 'Epsilon_Page_Generator' ) ){
	    $medzone_lite_fp = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
	    $medzone_lite_fp->generate_output();
    }
endif;

get_footer();