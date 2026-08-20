<?php
/**
 * The library screen: which packs this site has switched on.
 *
 * @package Unapp_Library
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add the screen under Appearance, beside the theme's own starter screen.
 */
function unapp_library_menu() {
	if ( ! unapp_library_theme_active() ) {
		return;
	}

	add_theme_page(
		__( 'Starter Library', 'unapp-library' ),
		__( 'Starter Library', 'unapp-library' ),
		'edit_theme_options',
		'unapp-library',
		'unapp_library_render'
	);
}
add_action( 'admin_menu', 'unapp_library_menu', 11 );

/**
 * Switch a pack on or off.
 */
function unapp_library_handle() {
	check_admin_referer( 'unapp_library_toggle' );

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to change the starter library.', 'unapp-library' ), 403 );
	}

	$slug  = isset( $_POST['pack'] ) ? sanitize_key( wp_unslash( $_POST['pack'] ) ) : '';
	$packs = unapp_library_packs();

	if ( isset( $packs[ $slug ] ) ) {
		$enabled = unapp_library_enabled();

		if ( in_array( $slug, $enabled, true ) ) {
			$enabled = array_values( array_diff( $enabled, array( $slug ) ) );
		} else {
			$enabled[] = $slug;
		}

		update_option( UNAPP_LIBRARY_ENABLED, $enabled );
	}

	wp_safe_redirect( admin_url( 'themes.php?page=unapp-library' ) );
	exit;
}
add_action( 'admin_post_unapp_library_toggle', 'unapp_library_handle' );

/**
 * Clear the cached endpoint response.
 */
function unapp_library_refresh() {
	check_admin_referer( 'unapp_library_refresh' );

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to change the starter library.', 'unapp-library' ), 403 );
	}

	delete_transient( UNAPP_LIBRARY_CACHE );
	wp_safe_redirect( admin_url( 'themes.php?page=unapp-library' ) );
	exit;
}
add_action( 'admin_post_unapp_library_refresh', 'unapp_library_refresh' );

/**
 * Render the library screen.
 */
function unapp_library_render() {
	$packs   = unapp_library_packs();
	$enabled = unapp_library_enabled();
	?>
	<div class="wrap unapp-library">
		<h1><?php esc_html_e( 'Starter library', 'unapp-library' ); ?></h1>
		<p class="unapp-library__intro">
			<?php esc_html_e( 'Each pack is one more kind of site — its patterns, its pages and its palette in a single file. Switching a pack on adds it to Appearance → Starter Sites; switching it off removes it again without touching anything you have already built.', 'unapp-library' ); ?>
		</p>

		<?php if ( ! $packs ) : ?>
			<div class="notice notice-info inline"><p><?php esc_html_e( 'No packs are available yet.', 'unapp-library' ); ?></p></div>
		<?php endif; ?>

		<div class="unapp-library__grid">
			<?php foreach ( $packs as $slug => $pack ) : ?>
				<?php $on = in_array( $slug, $enabled, true ); ?>
				<div class="unapp-pack<?php echo $on ? ' is-on' : ''; ?>">
					<h2>
						<?php echo esc_html( $pack['title'] ); ?>
						<?php if ( $on ) : ?>
							<span class="unapp-pack__badge"><?php esc_html_e( 'On', 'unapp-library' ); ?></span>
						<?php endif; ?>
					</h2>
					<?php if ( ! empty( $pack['summary'] ) ) : ?>
						<p><?php echo esc_html( $pack['summary'] ); ?></p>
					<?php endif; ?>
					<p class="unapp-pack__meta">
						<?php
						printf(
							/* translators: 1: number of patterns, 2: where the pack came from. */
							esc_html__( '%1$d patterns · %2$s', 'unapp-library' ),
							absint( count( $pack['patterns'] ) ),
							'bundled' === $pack['source']
								? esc_html__( 'bundled with the plugin', 'unapp-library' )
								: esc_html__( 'from the library', 'unapp-library' )
						);
						?>
					</p>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<?php wp_nonce_field( 'unapp_library_toggle' ); ?>
						<input type="hidden" name="action" value="unapp_library_toggle">
						<input type="hidden" name="pack" value="<?php echo esc_attr( $slug ); ?>">
						<button type="submit" class="button <?php echo $on ? '' : 'button-primary'; ?>">
							<?php echo $on ? esc_html__( 'Switch off', 'unapp-library' ) : esc_html__( 'Switch on', 'unapp-library' ); ?>
						</button>
					</form>
				</div>
			<?php endforeach; ?>
		</div>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="unapp-library__refresh">
			<?php wp_nonce_field( 'unapp_library_refresh' ); ?>
			<input type="hidden" name="action" value="unapp_library_refresh">
			<button type="submit" class="button-link"><?php esc_html_e( 'Check the library for new packs', 'unapp-library' ); ?></button>
		</form>

		<style>
			.unapp-library__intro { max-width: 70ch; }
			.unapp-library__grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 18px; margin-top: 22px; }
			.unapp-pack { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 18px 20px; display: flex; flex-direction: column; gap: 6px; }
			.unapp-pack.is-on { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; }
			.unapp-pack h2 { margin: 0; font-size: 15px; display: flex; align-items: center; gap: 8px; }
			.unapp-pack__badge { background: #2271b1; color: #fff; border-radius: 999px; padding: 1px 8px; font-size: 11px; font-weight: 500; }
			.unapp-pack p { margin: 0; color: #50575e; font-size: 13px; }
			.unapp-pack__meta { color: #787c82 !important; font-size: 12px !important; }
			.unapp-pack form { margin-top: auto; padding-top: 10px; }
			.unapp-library__refresh { margin-top: 20px; }
		</style>
	</div>
	<?php
}
