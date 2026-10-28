<?php
/**
 * Private, read-only starter previews using the same blocks and style variations.
 *
 * @package Unapp
 */
defined( 'ABSPATH' ) || exit;

/**
 * @param string $slug Starter slug.
 * @param string $page Page key, or 'home'.
 * @return string Private preview URL.
 */
function unapp_starter_preview_url( $slug, $page = 'home' ) {
	return add_query_arg( array( 'action' => 'unapp_starter_preview', 'starter' => $slug, 'preview_page' => $page, '_wpnonce' => wp_create_nonce( 'unapp_preview_' . $slug ) ), admin_url( 'admin-post.php' ) );
}

/**
 * Prepare a complete preview without writing pages, menus or styles.
 *
 * @param string $slug Starter slug.
 * @param string $page Starter page key.
 * @return array|WP_Error Preview markup, title and style data.
 */
function unapp_starter_preview_data( $slug, $page = 'home' ) {
	$sites = unapp_get_starter_sites();
	if ( ! isset( $sites[ $slug ] ) ) {
		return new WP_Error( 'unapp_unknown_starter', __( 'That starter site does not exist.', 'unapp' ) );
	}
	$site = $sites[ $slug ];
	$pages = unapp_prepare_starter( $site );
	if ( is_wp_error( $pages ) ) {
		return $pages;
	}
	if ( ! isset( $pages[ $page ] ) ) {
		return new WP_Error( 'unapp_unknown_page', __( 'That starter page does not exist.', 'unapp' ) );
	}
	$variation = unapp_compose_variation( $site['colors'] ?? '', $site['type'] ?? '' );
	if ( ! $variation ) {
		$variation = wp_json_file_decode( get_theme_file_path( 'styles/' . $site['style'] . '.json' ), array( 'associative' => true ) );
	}
	unset( $variation['$schema'], $variation['title'], $variation['slug'] );
	$variation = unapp_key_presets_by_origin( $variation );
	$variation['version'] = WP_Theme_JSON::LATEST_SCHEMA;
	$header = unapp_get_pattern_markup( 'unapp/header' );
	$menu = '';
	foreach ( array( 'home' => array( 'title' => __( 'Home', 'unapp' ) ) ) + $site['pages'] as $key => $definition ) {
		$menu .= '<!-- wp:navigation-link ' . serialize_block_attributes( array( 'label' => $definition['title'], 'url' => unapp_starter_preview_url( $slug, $key ) ) ) . ' /-->';
	}
	$header = preg_replace_callback( '/<!-- wp:navigation (\{.*?\})\s+\/-->/', static function ( $match ) use ( $menu ) { return '<!-- wp:navigation ' . $match[1] . ' -->' . $menu . '<!-- /wp:navigation -->'; }, $header );
	$default = _x( 'Get Premium', 'Header call-to-action button', 'unapp' );
	$header = unapp_map_html_text( $header, static function ( $text ) use ( $default, $site ) { return $text === $default ? ( $site['cta'] ?? $default ) : $text; } );
	$tags = new WP_HTML_Tag_Processor( $header );
	while ( $tags->next_tag( 'a' ) ) {
		if ( '#' === $tags->get_attribute( 'href' ) ) {
			$tags->set_attribute( 'href', unapp_starter_preview_url( $slug, $site['cta_page'] ?? 'home' ) );
		}
	}
	$footer = unapp_get_pattern_markup( $site['footer'] ?? 'unapp/footer' );
	$markup = '<!-- wp:group {"tagName":"header","layout":{"type":"default"}} --><header class="wp-block-group">' . $tags->get_updated_html() . '</header><!-- /wp:group -->'
		. '<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} --><main class="wp-block-group">' . $pages[ $page ] . '</main><!-- /wp:group -->'
		. '<!-- wp:group {"tagName":"footer","layout":{"type":"default"}} --><footer class="wp-block-group">' . $footer . '</footer><!-- /wp:group -->';
	$markup = str_replace( esc_url( home_url( '/#' ) ), esc_url( unapp_starter_preview_url( $slug ) ) . '#', $markup );
	return array( 'title' => $site['title'], 'markup' => $markup, 'variation' => $variation );
}

/** Render a nonce-protected preview document in the admin iframe. */
function unapp_handle_starter_preview() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to preview the site design.', 'unapp' ), 403 );
	}
	$slug = isset( $_GET['starter'] ) ? sanitize_key( wp_unslash( $_GET['starter'] ) ) : '';
	check_admin_referer( 'unapp_preview_' . $slug );
	$page = isset( $_GET['preview_page'] ) ? sanitize_key( wp_unslash( $_GET['preview_page'] ) ) : 'home';
	$data = unapp_starter_preview_data( $slug, $page );
	if ( is_wp_error( $data ) ) {
		wp_die( esc_html( $data->get_error_message() ) );
	}
	nocache_headers();
	header( 'X-Robots-Tag: noindex, nofollow', true );
	unapp_render_starter_preview_document( $data );
	exit;
}
add_action( 'admin_post_unapp_starter_preview', 'unapp_handle_starter_preview' );

/**
 * Render the complete document using the same block assets as the front end.
 *
 * @param array $data Validated preview data.
 */
function unapp_render_starter_preview_document( $data ) {
	$filters = array();
	$add = static function ( $hook, $callback ) use ( &$filters ) {
		add_filter( $hook, $callback, PHP_INT_MAX );
		$filters[] = array( $hook, $callback );
	};
	// Core normally persists its first Custom CSS lookup in theme mods. Preview
	// resolves that ID for this request instead, including sites with no CSS yet.
	$css_id = (int) get_theme_mod( 'custom_css_post_id' );
	if ( ! $css_id || ( $css_id > 0 && ! get_post( $css_id ) ) ) {
		$css_posts = get_posts( array( 'post_type' => 'custom_css', 'post_status' => get_post_stati(), 'name' => sanitize_title( get_stylesheet() ), 'numberposts' => 1, 'fields' => 'ids' ) );
		$css_id = $css_posts ? (int) $css_posts[0] : -1;
	}
	$add( 'theme_mod_custom_css_post_id', static function () use ( $css_id ) { return $css_id; } );
	$add( 'pre_get_document_title', static function () use ( $data ) { return $data['title']; } );
	$add( 'show_admin_bar', static function () { return false; } );
	if ( isset( $data['variation'] ) ) {
		$add( 'wp_theme_json_data_user', static function () use ( $data ) { return new WP_Theme_JSON_Data( $data['variation'], 'custom' ); } );
	}
	if ( ! empty( $data['personalized'] ) ) {
		$brand = $data['brand'];
		if ( current_user_can( 'manage_options' ) ) {
			if ( ! empty( $brand['title'] ) ) { $add( 'pre_option_blogname', static function () use ( $brand ) { return sanitize_option( 'blogname', $brand['title'] ); } ); }
			$add( 'pre_option_blogdescription', static function () use ( $brand ) { return sanitize_option( 'blogdescription', $brand['tagline'] ?? '' ); } );
		}
		$logo = ! empty( $brand['logo'] ) ? (int) $brand['logo'] : ( ! empty( $brand['logo_was'] ) ? 0 : (int) get_theme_mod( 'custom_logo' ) );
		$add( 'theme_mod_custom_logo', static function () use ( $logo ) { return $logo; } );
		$add( 'unapp_detected_form', static function () { return null; } );
	}
	try {
		WP_Theme_JSON_Resolver::clean_cached_data();
		wp_cache_delete( 'wp_get_global_stylesheet', 'theme_json' );
		foreach ( array( 'global-styles', 'global-styles-css-custom-properties' ) as $handle ) { wp_dequeue_style( $handle ); wp_deregister_style( $handle ); }
		// Render first so WordPress knows which native block assets to print.
		$content = do_blocks( $data['markup'] );
		if ( ! empty( $data['personalized'] ) || ! empty( $data['inert'] ) ) { $content = unapp_personalized_preview_links( $content ); }
		?>
		<!doctype html><html <?php language_attributes(); ?>><head>
		<meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><meta name="referrer" content="no-referrer">
		<?php wp_head(); ?>
		<?php if ( ! empty( $data['personalized'] ) ) : ?><style>#unapp-preview-notice{padding:24px;font:14px/1.5 system-ui;background:#f0f0f1;color:#1e1e1e;overflow-wrap:anywhere}form[inert]{opacity:.65}</style><?php endif; ?>
		</head><body id="unapp-preview-top" <?php body_class(); ?>><?php wp_body_open(); ?>
		<div class="wp-site-blocks"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered registered blocks. ?>
		<?php if ( ! empty( $data['personalized'] ) ) : ?><aside id="unapp-preview-notice"><p><?php esc_html_e( 'Private homepage preview. Supporting-page links and external actions are inactive; forms are not submitted. Your live site is unchanged.', 'unapp' ); ?></p><?php if ( ! empty( $data['action']['url'] ) ) : ?><p><?php echo esc_html( $data['action']['label'] . ' → ' . $data['action']['url'] ); ?></p><?php endif; ?></aside><?php endif; ?>
		</div><?php wp_footer(); ?></body></html>
		<?php
	} finally {
		foreach ( $filters as $filter ) { remove_filter( $filter[0], $filter[1], PHP_INT_MAX ); }
		WP_Theme_JSON_Resolver::clean_cached_data();
		wp_cache_delete( 'wp_get_global_stylesheet', 'theme_json' );
	}
}

/**
 * Preview shell with native keyboard-accessible viewport controls.
 *
 * @param string $slug Starter slug.
 */
function unapp_render_starter_preview( $slug ) {
	$site = unapp_get_starter_sites()[ $slug ];
	?>
	<div class="unapp-preview">
		<h1><?php echo esc_html( $site['title'] ); ?></h1>
		<p><?php esc_html_e( 'Preview only — your pages, menu and design are unchanged. Use the menu inside the preview to explore supporting pages.', 'unapp' ); ?></p>
		<p>
			<a class="button" href="<?php echo esc_url( admin_url( 'themes.php?page=unapp-starter-sites' ) ); ?>"><?php esc_html_e( 'Back to starters', 'unapp' ); ?></a>
			<a class="button button-primary" href="<?php echo esc_url( add_query_arg( array( 'page' => 'unapp-starter-sites', 'step' => 'brand', 'starter' => $slug ), admin_url( 'themes.php' ) ) ); ?>"><?php esc_html_e( 'Set up this starter', 'unapp' ); ?></a>
		</p>
		<fieldset class="unapp-preview__devices">
			<legend class="screen-reader-text"><?php esc_html_e( 'Preview width', 'unapp' ); ?></legend>
			<label><input type="radio" name="preview_width" value="desktop" checked> <?php esc_html_e( 'Desktop', 'unapp' ); ?></label>
			<label><input type="radio" name="preview_width" value="tablet"> <?php esc_html_e( 'Tablet', 'unapp' ); ?></label>
			<label><input type="radio" name="preview_width" value="mobile"> <?php esc_html_e( 'Mobile', 'unapp' ); ?></label>
		</fieldset>
		<iframe class="unapp-preview__frame" src="<?php echo esc_url( unapp_starter_preview_url( $slug ) ); ?>" title="<?php echo esc_attr( $site['title'] ); ?>" sandbox="allow-scripts allow-same-origin"></iframe>
	</div>
	<style>
		.unapp-preview__devices { display: flex; flex-wrap: wrap; gap: 18px; margin: 18px 0; }
		.unapp-preview__frame { display: block; box-sizing: border-box; width: 100%; max-width: 100%; height: 75vh; min-height: 500px; margin: 0 auto; border: 1px solid #c3c4c7; background: white; }
		.unapp-preview:has(input[value="tablet"]:checked) .unapp-preview__frame { width: 768px; }
		.unapp-preview:has(input[value="mobile"]:checked) .unapp-preview__frame { width: 390px; }
	</style>
	<?php
}
