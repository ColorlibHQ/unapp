<?php
/**
 * WordPress integration tests; no theme activation and no retained test content.
 * wp --skip-themes --skip-plugins eval-file /path/to/unapp/.dev/tests/import.php
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	throw new RuntimeException( 'Run with WP-CLI eval-file.' );
}
$root = dirname( __DIR__, 2 );
global $wpdb;
foreach ( $wpdb->get_results( 'SHOW TABLE STATUS' ) as $table ) {
	if ( 0 === strpos( $table->Name, $wpdb->prefix ) && 'InnoDB' !== $table->Engine ) {
		throw new RuntimeException( 'Tests require transactional InnoDB tables.' );
	}
}
$wpdb->query( 'START TRANSACTION' );
$checks = 0;
$expect = static function ( $condition, $label ) use ( &$checks ) {
	++$checks;
	if ( ! $condition ) {
		throw new RuntimeException( $label );
	}
};
try {
	add_filter( 'pre_option_template', static function () { return 'unapp'; } );
	add_filter( 'pre_option_stylesheet', static function () { return 'unapp'; } );
	add_filter( 'theme_root', static function () use ( $root ) { return dirname( $root ); } );
	register_theme_directory( dirname( $root ) );
	add_filter( 'theme_root_uri', static function () { return site_url( '/wp-content/themes' ); } );
	wp_clean_themes_cache( false );
	WP_Theme_JSON_Resolver::clean_cached_data();
	add_theme_support( 'block-templates' );
	add_filter( 'pre_option_active_plugins', static function () { return array(); } );
	add_filter( 'site_option_active_sitewide_plugins', static function () { return array(); } );
	$admins = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
	wp_set_current_user( $admins[0]->ID );
	require $root . '/functions.php';
	require $root . '/plugin/unapp-library/ai.php';
	$registry = WP_Block_Patterns_Registry::get_instance();
	foreach ( glob( $root . '/patterns/*.php' ) as $file ) {
		$data = get_file_data( $file, array( 'title' => 'Title', 'slug' => 'Slug' ) );
		ob_start();
		include $file;
		$content = ob_get_clean();
		if ( $registry->is_registered( $data['slug'] ) ) {
			$registry->unregister( $data['slug'] );
		}
		$registry->register( $data['slug'], array( 'title' => $data['title'], 'content' => $content ) );
	}
	$sites = unapp_get_starter_sites();
	foreach ( $sites as $slug => $site ) {
		$result = unapp_prepare_starter( $site );
		$expect( 'shop' === $slug ? is_wp_error( $result ) && 'unapp_missing_requirement' === $result->get_error_code() : is_array( $result ), "preflight: $slug " . ( is_wp_error( $result ) ? $result->get_error_message() : '' ) );
	}
	parse_str( wp_parse_url( unapp_starter_preview_url( 'saas', 'features' ), PHP_URL_QUERY ), $preview_query );
	$expect( 'saas' === $preview_query['starter'] && 'features' === $preview_query['preview_page'] && wp_verify_nonce( $preview_query['_wpnonce'], 'unapp_preview_saas' ), 'preview URLs carry valid, unescaped parameters' );
	$preview_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}" );
	$preview_front = get_option( 'page_on_front' );
	$preview = unapp_starter_preview_data( 'saas', 'features' );
	$expect( is_array( $preview ) && false !== strpos( $preview['markup'], 'unapp_starter_preview' ), 'preview uses its own navigation destinations' );
	$expect( $preview_count === (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}" ) && $preview_front === get_option( 'page_on_front' ), 'preview creates no posts and does not change the front page' );
	$expect( is_wp_error( unapp_starter_preview_data( 'saas', 'missing-page' ) ), 'preview rejects unknown supporting pages' );
	$mono_preview = unapp_starter_preview_data( 'portfolio' );
	$mono_tree = new WP_Theme_JSON( $mono_preview['variation'], 'custom' );
	$expect( false !== strpos( $mono_tree->get_stylesheet( array( 'variables' ) ), '--wp--preset--color--primary: #111111' ), 'preview style schema preserves the starter palette' );
	$original_front = get_option( 'page_on_front' );
	$original_title = get_option( 'blogname' );
	$business = array( 'email' => 'owner@real.test', 'phone' => '+371 2000 1234' );
	$contact = unapp_personalize_starter( '<p><a href="mailto:hello@studio.example?subject=Hello%20there">hello@studio.example</a> · <a href="tel:+1235235598">+1 235 2355 98</a> · 0151 555 0188</p>', $business );
	$expect( false !== strpos( $contact, 'mailto:owner@real.test?subject=Hello%20there' ) && false !== strpos( $contact, 'tel:+37120001234' ) && 2 === substr_count( $contact, '+371 2000 1234' ), 'personalized destinations and visible text match' );
	$brand = array_merge( $business, array( 'title' => 'Import test', 'tagline' => 'A real business', 'logo' => 0, 'logo_was' => 0 ) );
	$request = wp_generate_uuid4();
	$result = unapp_apply_starter_site( 'saas', $brand, $request );
	$expect( is_array( $result ), 'successful import: ' . ( is_wp_error( $result ) ? $result->get_error_message() : '' ) );
	$expect( (int) get_option( 'page_on_front' ) === $result['home'] && 'Import test' === get_option( 'blogname' ), 'front page and branding applied after success' );
	$expect( false !== strpos( get_post( $result['contact'] )->post_content, 'owner@real.test' ), 'new pages have public contact details' );
	$expect( false !== strpos( unapp_get_customised_part( 'footer' )->post_content, 'owner@real.test' ), 'footer has public contact details' );
	$expect( unapp_apply_starter_site( 'saas', $brand, $request ) === $result, 'same submitted request returns existing pages' );

	$state = array( get_option( 'page_on_front' ), get_option( 'blogname' ), get_option( UNAPP_STARTER_OPTION ), unapp_get_customised_part( 'header' )->post_content, unapp_get_customised_part( 'footer' )->post_content, get_post( get_option( UNAPP_STARTER_NAV_OPTION ) )->post_content );
	$filter = static function ( $all ) { $all['saas']['home'] = 'unapp/test-missing'; return $all; };
	$registry->register( 'unapp/test-missing', array( 'title' => 'Missing nested section', 'content' => '<!-- wp:pattern {"slug":"unapp/does-not-exist"} /-->' ) );
	add_filter( 'unapp_starter_sites', $filter );
	$bad = unapp_apply_starter_site( 'saas', array_merge( $brand, array( 'title' => 'Must not stick' ) ) );
	remove_filter( 'unapp_starter_sites', $filter );
	$expect( is_wp_error( $bad ) && 'unapp_missing_pattern' === $bad->get_error_code() && get_option( 'blogname' ) === $state[1], 'missing nested patterns do not change branding' );

	$fail_page = static function ( $empty, $post ) use ( $sites ) { return 'page' === $post['post_type'] && $sites['saas']['pages']['features']['title'] === $post['post_title'] ? true : $empty; };
	add_filter( 'wp_insert_post_empty_content', $fail_page, 10, 2 );
	$bad = unapp_apply_starter_site( 'saas', array_merge( $brand, array( 'title' => 'Must not stick' ) ) );
	remove_filter( 'wp_insert_post_empty_content', $fail_page );
	$expect( is_wp_error( $bad ) && 'unapp_import_restored' === $bad->get_error_code(), 'supporting-page failure triggers recovery' );
	$expect( get_option( 'page_on_front' ) === $state[0] && get_option( 'blogname' ) === $state[1], 'failed page import preserves live settings' );

	$fired = false;
	$fail_footer = static function ( $id ) use ( &$fired ) {
		$post = get_post( $id );
		if ( ! $fired && 'wp_template_part' === $post->post_type && 'footer' === $post->post_name ) {
			$fired = true;
			throw new RuntimeException( 'Simulated footer storage failure' );
		}
	};
	add_action( 'pre_post_update', $fail_footer, 9 );
	$bad = unapp_apply_starter_site( 'portfolio', $brand );
	remove_action( 'pre_post_update', $fail_footer, 9 );
	$after = array( get_option( 'page_on_front' ), get_option( 'blogname' ), get_option( UNAPP_STARTER_OPTION ), unapp_get_customised_part( 'header' )->post_content, unapp_get_customised_part( 'footer' )->post_content, get_post( get_option( UNAPP_STARTER_NAV_OPTION ) )->post_content );
	$expect( $fired && is_wp_error( $bad ) && 'unapp_import_restored' === $bad->get_error_code(), 'failure after design writes is reported' );
	$expect( $state === $after, 'existing design, navigation, branding and front page are restored' );
	$expect( false === get_option( UNAPP_STARTER_LOCK ), 'import lock is released after failure' );

	add_option( UNAPP_STARTER_LOCK, array( 'time' => time(), 'id' => 'other-request' ), '', false );
	$busy = unapp_apply_starter_site( 'saas' );
	$expect( is_wp_error( $busy ) && 'unapp_import_busy' === $busy->get_error_code(), 'concurrent imports are rejected' );
	delete_option( UNAPP_STARTER_LOCK );
	add_option( UNAPP_STARTER_LOCK, array( 'time' => time() - HOUR_IN_SECONDS, 'id' => 'expired' ), '', false );
	$unknown = unapp_apply_starter_site( 'does-not-exist' );
	$expect( is_wp_error( $unknown ) && 'unapp_unknown_starter' === $unknown->get_error_code() && false === get_option( UNAPP_STARTER_LOCK ), 'expired lock can be recovered and released' );
	$fail_settings = static function ( $value, $old ) { return $old; };
	add_filter( 'pre_update_option_page_on_front', $fail_settings, 10, 2 );
	$bad = unapp_apply_starter_site( 'portfolio', $brand );
	remove_filter( 'pre_update_option_page_on_front', $fail_settings );
	$expect( is_wp_error( $bad ) && 'unapp_import_restored' === $bad->get_error_code() && get_option( 'page_on_front' ) === $state[0], 'failed Reading settings commit triggers recovery' );
	$_GET['request_id'] = $request;
	delete_transient( UNAPP_STARTER_RESULT );
	ob_start();
	unapp_wizard_render_done();
	$done_screen = ob_get_clean();
	unset( $_GET['request_id'] );
	$expect( false !== strpos( $done_screen, 'Your site is built' ), 'completed setup survives a refreshed result screen' );

	// Mock the provider at WordPress's HTTP boundary: no credentials or API spend.
	update_option( UNAPP_AI_SETTINGS, array( 'provider' => 'anthropic', 'key' => 'test-key', 'model' => '' ), false );
	$reply = '';
	$http = static function () use ( &$reply ) {
		return array( 'response' => array( 'code' => 200 ), 'body' => wp_json_encode( array( 'content' => array( array( 'text' => $reply ) ) ) ) );
	};
	add_filter( 'pre_http_request', $http, PHP_INT_MAX );
	$copy = '<!-- wp:paragraph --><p>Grow <strong>your</strong> business &amp; team.</p><!-- /wp:paragraph -->';
	$ai_page = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'draft', 'post_title' => 'AI test', 'post_content' => wp_slash( $copy ) ) );
	$reply = wp_json_encode( array( 'Build', 'our', 'studio & community.' ) );
	$proposal = unapp_ai_prepare_rewrite( array( $ai_page ), 'A community studio' );
	$expect( is_array( $proposal ) && get_post( $ai_page )->post_content === $copy, 'AI proposal generation leaves pages unchanged' );
	$ai_result = unapp_ai_apply_proposal( $proposal );
	$expect( array( 'pages' => 1, 'strings' => 3 ) === $ai_result, 'AI reports the replacements actually saved: ' . ( is_wp_error( $ai_result ) ? $ai_result->get_error_message() : wp_json_encode( $ai_result ) ) );
	$expect( '<!-- wp:paragraph --><p>Build <strong>our</strong> studio &amp; community.</p><!-- /wp:paragraph -->' === get_post( $ai_page )->post_content, 'AI preserves formatted block content on save' );
	$reviewed = get_post( $ai_page )->post_content;
	$reply = wp_json_encode( array( 'Next', 'our', 'studio & community.' ) );
	$stale_proposal = unapp_ai_prepare_rewrite( array( $ai_page ), 'A community studio' );
	wp_update_post( array( 'ID' => $ai_page, 'post_content' => wp_slash( $reviewed . '\n<!-- newer edit -->' ) ) );
	$stale_ai = unapp_ai_apply_proposal( $stale_proposal );
	$expect( is_wp_error( $stale_ai ) && 'unapp_ai_stale' === $stale_ai->get_error_code(), 'AI review never overwrites a page edited after generation' );
	wp_update_post( array( 'ID' => $ai_page, 'post_content' => wp_slash( $reviewed ) ) );
	$wrong_owner = $stale_proposal;
	$wrong_owner['user'] = 0;
	$expect( is_wp_error( unapp_ai_apply_proposal( $wrong_owner ) ), 'AI previews belong to their generating account' );
	$reply = wp_json_encode( array( 'Wrong', false, 'Wrong' ) );
	$ai_bad = unapp_ai_rewrite_pages( array( $ai_page ), 'A community studio' );
	$expect( is_wp_error( $ai_bad ) && 'unapp_ai_shape' === $ai_bad->get_error_code(), 'AI rejects an incomplete response as a whole' );
	$reply = wp_json_encode( array( 'Next', 'our', 'studio & community.' ) );
	$fail_ai = static function ( $empty, $post ) use ( $ai_page ) { return (int) ( $post['ID'] ?? 0 ) === $ai_page ? true : $empty; };
	add_filter( 'wp_insert_post_empty_content', $fail_ai, 10, 2 );
	$ai_bad = unapp_ai_rewrite_pages( array( $ai_page ), 'A community studio' );
	remove_filter( 'wp_insert_post_empty_content', $fail_ai );
	remove_filter( 'pre_http_request', $http, PHP_INT_MAX );
	$expect( is_wp_error( $ai_bad ) && 'unapp_ai_save' === $ai_bad->get_error_code(), 'AI save failure is not reported as success' );


	require $root . '/.dev/tests/launch.php';
	require $root . '/.dev/tests/contact-form-7.php';
	$undo_record = get_option( UNAPP_STARTER_UNDO_OPTION );
	wp_cache_flush();
	$expect( true === unapp_starter_can_undo(), 'Undo stays available after caches are refreshed' );
	$old_home_content = get_post( $result['home'] )->post_content;
	wp_update_post( array( 'ID' => $result['home'], 'post_content' => wp_slash( $old_home_content . '\n<!-- edited -->' ) ) );
	$conflict = unapp_undo_starter_site( $undo_record['id'] );
	$expect( is_wp_error( $conflict ) && 'unapp_undo_changed' === $conflict->get_error_code() && 'publish' === get_post_status( $result['home'] ), 'Undo refuses to discard edits made after import' );
	wp_update_post( array( 'ID' => $result['home'], 'post_content' => wp_slash( $old_home_content ) ) );
	$stale = unapp_undo_starter_site( 'older-browser-tab' );
	$expect( is_wp_error( $stale ) && 'unapp_no_undo' === $stale->get_error_code(), 'stale Undo forms cannot undo another run' );
	$undone = unapp_undo_starter_site( $undo_record['id'] );
	$expect( true === $undone, 'completed unedited setup can be undone: ' . ( is_wp_error( $undone ) ? $undone->get_error_message() : '' ) );
	$expect( (int) get_option( 'page_on_front' ) === (int) $original_front && get_option( 'blogname' ) === $original_title, 'Undo restores original Reading settings and branding' );
	$expect( in_array( get_post_status( $result['home'] ), array( 'trash', 'draft' ), true ), 'Undo retains the generated home page for recovery' );
	$replay = unapp_apply_starter_site( 'saas', $brand, $request );
	$expect( is_wp_error( $replay ) && 'unapp_import_undone' === $replay->get_error_code(), 'an old submission cannot silently reapply an undone setup' );

	require $root . '/.dev/tests/design.php';
	require $root . '/.dev/tests/business.php';
	require $root . '/.dev/tests/finish.php';
	require $root . '/.dev/tests/content-guide.php';
	require $root . '/.dev/tests/section-swaps.php';
	require $root . '/.dev/tests/service-cards.php';
	require $root . '/.dev/tests/personalized-preview.php';
	require $root . '/.dev/tests/starter-finder.php';
	echo "WordPress import regression checks passed: $checks\n";
} catch ( Throwable $failure ) {
	fwrite( STDERR, get_class( $failure ) . ": " . $failure->getMessage() . "\n" . $failure->getTraceAsString() . "\n" );
} finally {
	$wpdb->query( 'ROLLBACK' );
	wp_cache_flush();
}

if ( isset( $failure ) ) {
	WP_CLI::error( 'Import regression failed.' );
}
