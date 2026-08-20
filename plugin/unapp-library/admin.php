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

/**
 * The AI screen: pick a provider, paste a key, describe the business.
 */
function unapp_ai_menu() {
	if ( ! unapp_library_theme_active() ) {
		return;
	}

	add_theme_page(
		__( 'Rewrite with AI', 'unapp-library' ),
		__( 'Rewrite with AI', 'unapp-library' ),
		'edit_theme_options',
		'unapp-ai',
		'unapp_ai_render'
	);
}
add_action( 'admin_menu', 'unapp_ai_menu', 12 );

/**
 * Save the provider and key.
 */
function unapp_ai_save() {
	check_admin_referer( 'unapp_ai_settings' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to change these settings.', 'unapp-library' ), 403 );
	}

	$providers = unapp_ai_providers();
	$provider  = isset( $_POST['provider'] ) ? sanitize_key( wp_unslash( $_POST['provider'] ) ) : '';
	$key       = isset( $_POST['api_key'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) ) : '';
	$model     = isset( $_POST['model'] ) ? sanitize_text_field( wp_unslash( $_POST['model'] ) ) : '';
	$existing  = unapp_ai_settings();

	update_option(
		UNAPP_AI_SETTINGS,
		array(
			'provider' => isset( $providers[ $provider ] ) ? $provider : $existing['provider'],
			// An empty field means "leave the stored key alone", so the key
			// never has to be pasted twice or rendered back into the page.
			'key'      => '' === $key ? $existing['key'] : $key,
			'model'    => $model,
		)
	);

	wp_safe_redirect( admin_url( 'themes.php?page=unapp-ai&saved=1' ) );
	exit;
}
add_action( 'admin_post_unapp_ai_settings', 'unapp_ai_save' );

/**
 * Run the rewrite.
 */
function unapp_ai_run() {
	check_admin_referer( 'unapp_ai_run' );

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to rewrite the site.', 'unapp-library' ), 403 );
	}

	$description = isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '';
	$applied     = get_option( 'unapp_active_starter', array() );
	$page_ids    = array();

	foreach ( (array) ( $applied['pages'] ?? array() ) as $key => $id ) {
		if ( 'blog' !== $key ) {
			$page_ids[] = (int) $id;
		}
	}

	if ( ! $page_ids || '' === $description ) {
		set_transient( 'unapp_ai_result', array( 'error' => __( 'Describe the business, and apply a starter first.', 'unapp-library' ) ), MINUTE_IN_SECONDS * 5 );
	} else {
		$result = unapp_ai_rewrite_pages( $page_ids, $description );
		set_transient(
			'unapp_ai_result',
			is_wp_error( $result ) ? array( 'error' => $result->get_error_message() ) : $result,
			MINUTE_IN_SECONDS * 5
		);
	}

	wp_safe_redirect( admin_url( 'themes.php?page=unapp-ai' ) );
	exit;
}
add_action( 'admin_post_unapp_ai_run', 'unapp_ai_run' );

/**
 * Render the AI screen.
 */
function unapp_ai_render() {
	$settings  = unapp_ai_settings();
	$providers = unapp_ai_providers();
	$applied   = get_option( 'unapp_active_starter', array() );
	$pages     = array_diff_key( (array) ( $applied['pages'] ?? array() ), array( 'blog' => 1 ) );
	$result    = get_transient( 'unapp_ai_result' );

	if ( $result ) {
		delete_transient( 'unapp_ai_result' );
	}
	?>
	<div class="wrap unapp-ai">
		<h1><?php esc_html_e( 'Rewrite with AI', 'unapp-library' ); ?></h1>
		<p class="unapp-ai__intro">
			<?php esc_html_e( 'This rewrites the words on the pages your starter built, and only the words. The layout, the spacing and the images are never sent and never change — the model receives the text on the page and a description of your business, and returns replacements one for one.', 'unapp-library' ); ?>
		</p>

		<?php if ( isset( $result['error'] ) ) : ?>
			<div class="notice notice-error"><p><?php echo esc_html( $result['error'] ); ?></p></div>
		<?php elseif ( isset( $result['pages'] ) ) : ?>
			<div class="notice notice-success"><p>
				<?php
				printf(
					/* translators: 1: number of pages, 2: number of strings. */
					esc_html__( 'Rewrote %1$d pages and %2$d pieces of text. Every page is in your Pages list, and the previous wording is in each page&rsquo;s revision history.', 'unapp-library' ),
					absint( $result['pages'] ),
					absint( $result['strings'] )
				);
				?>
			</p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="unapp-ai__panel">
			<?php wp_nonce_field( 'unapp_ai_settings' ); ?>
			<input type="hidden" name="action" value="unapp_ai_settings">
			<h2><?php esc_html_e( 'Your provider', 'unapp-library' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Use whichever you already have an account with. The key is stored on this site and sent only to that provider.', 'unapp-library' ); ?>
			</p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Provider', 'unapp-library' ); ?></th>
					<td>
						<?php foreach ( $providers as $key => $provider ) : ?>
							<label class="unapp-ai__provider">
								<input type="radio" name="provider" value="<?php echo esc_attr( $key ); ?>"
									<?php checked( $key, $settings['provider'] ); ?>>
								<?php echo esc_html( $provider['label'] ); ?>
								<a href="<?php echo esc_url( $provider['keys_url'] ); ?>" target="_blank" rel="noopener">
									<?php esc_html_e( 'get a key', 'unapp-library' ); ?>
								</a>
							</label>
						<?php endforeach; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="unapp-ai-key"><?php esc_html_e( 'API key', 'unapp-library' ); ?></label></th>
					<td>
						<input type="password" id="unapp-ai-key" name="api_key" class="regular-text" autocomplete="off"
							placeholder="<?php echo $settings['key'] ? esc_attr__( 'Saved — leave blank to keep it', 'unapp-library' ) : ''; ?>">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="unapp-ai-model"><?php esc_html_e( 'Model', 'unapp-library' ); ?></label></th>
					<td>
						<input type="text" id="unapp-ai-model" name="model" class="regular-text"
							value="<?php echo esc_attr( $settings['model'] ); ?>"
							placeholder="<?php echo esc_attr( $providers[ $settings['provider'] ]['model'] ); ?>">
						<p class="description"><?php esc_html_e( 'Optional. Leave blank for the default.', 'unapp-library' ); ?></p>
					</td>
				</tr>
			</table>
			<p><button type="submit" class="button"><?php esc_html_e( 'Save', 'unapp-library' ); ?></button></p>
		</form>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="unapp-ai__panel">
			<?php wp_nonce_field( 'unapp_ai_run' ); ?>
			<input type="hidden" name="action" value="unapp_ai_run">
			<h2><?php esc_html_e( 'Your business', 'unapp-library' ); ?></h2>

			<?php if ( ! $pages ) : ?>
				<p><?php esc_html_e( 'Apply a starter first — there are no starter pages to rewrite yet.', 'unapp-library' ); ?></p>
			<?php else : ?>
				<p class="description">
					<?php
					printf(
						/* translators: %d: number of pages. */
						esc_html( _n( 'Describe what you do. %d page will be rewritten.', 'Describe what you do. %d pages will be rewritten.', count( $pages ), 'unapp-library' ) ),
						absint( count( $pages ) )
					);
					?>
				</p>
				<textarea name="description" rows="4" class="large-text" required
					placeholder="<?php esc_attr_e( 'A two-person physiotherapy clinic in Leeds. Mostly sports injuries and post-operative rehab. Evening appointments, no referral needed.', 'unapp-library' ); ?>"></textarea>
				<p class="description">
					<?php esc_html_e( 'The more specific you are — where you are, who you serve, what makes you different — the less generic the result. Prices and times stay as they are unless you give real ones.', 'unapp-library' ); ?>
				</p>
				<p>
					<button type="submit" class="button button-primary" <?php disabled( ! unapp_ai_ready() ); ?>>
						<?php esc_html_e( 'Rewrite my pages', 'unapp-library' ); ?>
					</button>
					<?php if ( ! unapp_ai_ready() ) : ?>
						<span class="description"><?php esc_html_e( 'Add a key above first.', 'unapp-library' ); ?></span>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</form>

		<style>
			.unapp-ai__intro { max-width: 72ch; }
			.unapp-ai__panel { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 4px 20px 16px; margin: 18px 0; max-width: 820px; }
			.unapp-ai__panel h2 { font-size: 15px; }
			.unapp-ai__provider { display: inline-flex; align-items: center; gap: 6px; margin-inline-end: 20px; }
			.unapp-ai textarea { max-width: 760px; }
		</style>
	</div>
	<?php
}
