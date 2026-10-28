<?php
/**
 * Export a private preview without activating the theme or retaining DB writes.
 * wp --skip-themes --skip-plugins eval-file /path/to/.dev/render-starter.php
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	throw new RuntimeException( 'Use WP-CLI eval-file.' );
}
$root = dirname( __DIR__ );
$slug = getenv( 'UNAPP_PREVIEW_STARTER' ) ?: 'saas';
$page = getenv( 'UNAPP_PREVIEW_PAGE' ) ?: 'home';
$out = getenv( 'UNAPP_PREVIEW_OUTPUT' );
if ( ! $out || ! is_dir( $out ) ) {
	throw new RuntimeException( 'Set UNAPP_PREVIEW_OUTPUT to an existing directory.' );
}
global $wpdb;
foreach ( $wpdb->get_results( 'SHOW TABLE STATUS' ) as $table ) {
	if ( 0 === strpos( $table->Name, $wpdb->prefix ) && 'InnoDB' !== $table->Engine ) {
		throw new RuntimeException( 'Preview exports require transactional InnoDB tables.' );
	}
}
$wpdb->query( 'START TRANSACTION' );
try {
	add_filter( 'pre_option_template', static function () { return 'unapp'; } );
	add_filter( 'pre_option_stylesheet', static function () { return 'unapp'; } );
	add_filter( 'theme_root', static function () use ( $root ) { return dirname( $root ); } );
	add_filter( 'theme_root_uri', static function () { return site_url( '/wp-content/themes' ); } );
	add_filter( 'pre_option_active_plugins', static function () { return getenv( 'UNAPP_PREVIEW_WOO' ) ? array( 'woocommerce/woocommerce.php' ) : array(); } );
	add_filter( 'site_option_active_sitewide_plugins', static function () { return array(); } );
	$_SERVER['SERVER_NAME'] = wp_parse_url( site_url(), PHP_URL_HOST );
	$names = array( 'coach' => 'Northward Coaching', 'photographer' => 'Ellis Rowan', 'nonprofit' => 'Common Ground Collective', 'home-services' => 'Northline Property Care', 'cleaning' => 'Clear Day Cleaning', 'salon' => 'Juniper Hair Studio', 'saas' => 'Unapp', 'portfolio' => 'Mara Lindqvist', 'agency' => 'Northgate Studio', 'shop' => 'Sheaf Works', 'restaurant' => 'Wharf Street', 'fitness' => 'Arch Twelve', 'church' => 'Riverside Church', 'blog' => 'The Slow Build', 'realestate' => 'Lansdown & Co.', 'medical' => 'Fore Street Dental', 'education' => 'The Old School', 'events' => 'Longform', 'finance' => 'North & Vale' );
	add_filter( 'pre_option_blogname', static function () use ( $names, $slug ) { return $names[ $slug ] ?? 'Unapp'; } );
	add_filter( 'pre_option_blogdescription', static function () { return ''; } );
	register_theme_directory( dirname( $root ) );
	wp_clean_themes_cache( false );
	WP_Theme_JSON_Resolver::clean_cached_data();
	add_theme_support( 'block-templates' );
	$admins = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
	if ( ! $admins ) {
		throw new RuntimeException( 'Preview exports need an administrator account.' );
	}
	wp_set_current_user( $admins[0]->ID );
	require $root . '/functions.php';
	foreach ( glob( $root . '/patterns/*.php' ) as $file ) {
		$headers = get_file_data( $file, array( 'title' => 'Title', 'slug' => 'Slug' ) );
		ob_start();
		include $file;
		$content = ob_get_clean();
		$registry = WP_Block_Patterns_Registry::get_instance();
		if ( $registry->is_registered( $headers['slug'] ) ) {
			$registry->unregister( $headers['slug'] );
		}
		$registry->register( $headers['slug'], array( 'title' => $headers['title'], 'content' => $content ) );
	}
	// Optional layout fixtures belong only in an isolated test installation.
	// Product rows are rolled back with the rest of this export.
	if ( 'shop' === $slug && getenv( 'UNAPP_PREVIEW_SAMPLE_PRODUCTS' ) && class_exists( 'WC_Product_Simple' ) ) {
		foreach ( array( 'Everyday canvas tote', 'The workshop apron', 'Small-run carryall', 'Canvas tool roll' ) as $index => $name ) {
			$product = new WC_Product_Simple();
			$product->set_name( $name );
			$product->set_status( 'publish' );
			$product->set_regular_price( (string) ( 45 + 15 * $index ) );
			$product->save();
		}
	}
	$data = unapp_starter_preview_data( $slug, $page );
	if ( is_wp_error( $data ) ) {
		throw new RuntimeException( $data->get_error_message() );
	}
	ob_start();
	unapp_render_starter_preview_document( $data );
	$html = ob_get_clean();
	$name = sanitize_key( $slug ) . '-' . sanitize_key( $page );
	file_put_contents( $out . '/' . $name . '.html', $html );
	file_put_contents( $out . '/paths.json', wp_json_encode( array( 'theme_url' => get_template_directory_uri(), 'theme_path' => $root, 'site_url' => site_url(), 'wp_path' => ABSPATH, 'plugins_url' => plugins_url(), 'plugins_path' => WP_PLUGIN_DIR, 'uploads_url' => wp_get_upload_dir()['baseurl'], 'uploads_path' => wp_get_upload_dir()['basedir'] ) ) );
	if ( getenv( 'UNAPP_PREVIEW_EDITOR' ) ) {
		$patterns = array();
		foreach ( WP_Block_Patterns_Registry::get_instance()->get_all_registered() as $pattern ) {
			if ( 0 === strpos( $pattern['name'], 'unapp/' ) && ! preg_match( '/(?:shop-featured|hidden-shop|demo-|page-)/', $pattern['name'] ) ) {
				$patterns[ $pattern['name'] ] = unapp_get_pattern_markup( $pattern['name'] );
			}
		}
		ob_start();
		wp_print_scripts( array( 'wp-block-library' ) );
		$scripts = ob_get_clean();
		file_put_contents( $out . '/editor.html', '<!doctype html><html><head><meta charset="utf-8">' . $scripts . '</head><body><script>wp.blockLibrary.registerCoreBlocks(); window.starterPatterns = ' . wp_json_encode( $patterns, JSON_HEX_TAG | JSON_HEX_AMP ) . ';</script></body></html>' );
	}
	echo 'Rendered ' . $name . "\n";
} finally {
	$wpdb->query( 'ROLLBACK' );
	wp_cache_flush();
}
