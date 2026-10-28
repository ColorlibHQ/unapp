<?php
/** Finder and new niche starter regressions within the isolated transaction. */
$finder_sites = unapp_get_starter_sites();
$expect( 19 === count( $finder_sites ), 'finder: all nineteen free starters are available' );
foreach ( array( 'electrician' => 'home-services', 'plumber' => 'home-services', 'handyman' => 'home-services', 'housekeeping' => 'cleaning', 'barber' => 'salon', 'hairdresser' => 'salon', 'bárber' => 'salon', 'SALON/BARBER' => 'salon', 'leadership coaching' => 'coach', 'editorial photography' => 'photographer', 'volunteering' => 'nonprofit', 'not-for-profit' => 'nonprofit' ) as $query => $slug ) {
	$expect( unapp_starter_matches( $slug, $finder_sites[ $slug ], $query, '' ), 'finder: niche alias ' . $query . ' finds ' . $slug );
}
$expect( ! unapp_starter_matches( 'salon', $finder_sites['salon'], 'barber', 'shop' ) && unapp_starter_matches( 'salon', $finder_sites['salon'], 'barber', 'local' ), 'finder: query and category combine rather than replace each other' );
$expect( ! unapp_starter_matches( 'cleaning', $finder_sites['cleaning'], 'unfindable spaceship', '' ), 'finder: unrelated queries have no false matches' );
$expect( unapp_starter_matches( 'shop', $finder_sites['shop'], 'woocommerce', 'shop' ), 'finder: required plugins are searchable' );
$expect( unapp_starter_matches( 'custom', array( 'title' => 'Custom studio', 'summary' => 'A specialist service.' ), 'custom studio', '' ), 'finder: third-party starters remain searchable without optional finder metadata' );
foreach ( array( 'home-services', 'cleaning', 'salon', 'coach', 'photographer', 'nonprofit' ) as $slug ) {
	$site = $finder_sites[ $slug ];
	if ( in_array( $slug, array( 'coach', 'photographer', 'nonprofit' ), true ) ) {
		$labels = array_column( unapp_section_choices( $site ), 'title' );
		$expect( count( $labels ) === count( array_unique( $labels ) ), 'niches: ' . $slug . ' has distinct, useful native List View section names' );
		$prompt = unapp_content_guide_prompt( array( 'id' => $slug . '-services', 'title' => 'Services', 'root' => 1 ) );
		$expect( false === strpos( $prompt, 'sample people, logos' ), 'niches: ' . $slug . ' gets a relevant content-review prompt' );
	}
	$expect( empty( $site['requires'] ) && is_array( unapp_compose_variation( $site['colors'], $site['type'] ) ), 'niches: ' . $slug . ' uses valid native styles without required plugins' );
	$brand = array_merge( $finish_profile, array( 'layout' => 'full', 'title' => 'Local & Co.', 'email' => 'owner@local.test', 'phone' => '+44 117 123 5555' ) );
	$built = unapp_apply_starter_site( $slug, $brand );
	$first_support = array_key_first( $site['pages'] );
	$expect( is_array( $built ) && count( $site['pages'] ) + 2 === count( $built ) && isset( $built[ $first_support ], $built['contact'], $built['blog'] ), 'niches: ' . $slug . ' full-site import creates its native supporting pages' );
	$contact = get_post( $built['contact'] )->post_content;
	$expect( false !== strpos( $contact, 'unapp-form-slot' ) && false !== strpos( $contact, 'owner@local.test' ), 'niches: ' . $slug . ' supports optional forms and works with public email alone' );
	$expect( false !== strpos( get_post( $built['home'] )->post_content, 'niche-' . $slug . '.webp' ), 'niches: ' . $slug . ' uses its own bundled photograph' );
	$record = get_option( UNAPP_STARTER_UNDO_OPTION );
	$expect( true === unapp_undo_starter_site( $record['id'] ), 'niches: ' . $slug . ' complete import can be undone' );
}
$before_finder = unapp_business_snapshot();
$_GET = array( 'starter_search' => 'barber', 'starter_category' => 'local' );
ob_start(); unapp_render_starter_screen(); $filtered_html = ob_get_clean();
$expect( false !== strpos( $filtered_html, 'value="barber"' ) && (bool) preg_match( '/data-starter="home-services"[^>]*hidden/', $filtered_html ) && ! preg_match( '/data-starter="salon"[^>]*hidden/', $filtered_html ), 'finder: native GET filtering retains fields and hides nonmatching cards on the server' );
$expect( $before_finder === unapp_business_snapshot(), 'finder: browsing does not alter the site setup or shared content' );
$_GET = array();
$output = getenv( 'UNAPP_FINDER_PREVIEW_DIR' );
if ( $output && is_dir( $output ) ) {
	foreach ( array( 'all' => array(), 'filtered' => array( 'starter_search' => 'barber', 'starter_category' => 'local' ), 'empty' => array( 'starter_search' => 'unfindable spaceship' ) ) as $name => $query ) {
		$_GET = $query;
		ob_start(); unapp_render_starter_screen(); $screen = ob_get_clean();
		wp_styles()->done = array();
		foreach ( array( 'common', 'forms', 'buttons' ) as $handle ) { wp_enqueue_style( $handle ); }
		ob_start(); wp_print_styles( array( 'common', 'forms', 'buttons' ) ); $css = ob_get_clean();
		file_put_contents( $output . '/' . $name . '.html', '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $css . '<style>:root{--wp-admin-theme-color:#2271b1}body{margin:0;padding:20px;background:#f0f0f1}</style></head><body class="wp-admin wp-core-ui">' . $screen . '<script src="' . esc_url( get_theme_file_uri( 'assets/js/starter-finder.js' ) ) . '"></script></body></html>' );
	}
	$_GET = array();
	file_put_contents( $output . '/paths.json', wp_json_encode( array( 'theme_url' => get_template_directory_uri(), 'theme_path' => $root, 'site_url' => site_url(), 'wp_path' => ABSPATH ) ) );
}
