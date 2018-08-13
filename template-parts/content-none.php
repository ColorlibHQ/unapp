<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
?>

<div class="col-md-12 animate-box">
	<div class="page-content">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'unapp' ); ?></h1>
        </header>
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :
			?>
            <p>
				<?php
				$wp_kses_args = array(
					'a' => array(
						'href' => array(),
					),
				);
				printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'unapp' ), $wp_kses_args ), esc_url( admin_url( 'post-new.php' ) ) );
				?>
            </p>
		<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'unapp' ); ?></p>
			<?php
			get_search_form();

		endif;
		?>
	</div><!-- .page-content -->
</div>
