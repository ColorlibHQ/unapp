<?php
/** Real service copy, guarded profile updates, and editor return destinations. */
$service_unchanged = static function ( $blocks ) use ( &$service_unchanged ) {
	$out = array();
	foreach ( $blocks as $block ) {
		if ( ! $block['innerBlocks'] && empty( $block['attrs']['metadata']['unappService'] ) ) { $out[] = serialize_block( $block ); }
		$out = array_merge( $out, $service_unchanged( $block['innerBlocks'] ) );
	}
	sort( $out ); return $out;
};
$service_marked = static function ( $blocks ) use ( &$service_marked ) {
	$out = array();
	foreach ( $blocks as $block ) {
		if ( ! empty( $block['attrs']['metadata']['unappService'] ) ) { $out[] = $block; }
		$out = array_merge( $out, $service_marked( $block['innerBlocks'] ) );
	}
	return $out;
};
$service_exports = array(); $service_supported = array();
foreach ( unapp_get_starter_sites() as $service_slug => $service_site ) {
	$service_spec = unapp_service_card_spec( $service_site );
	if ( ! $service_spec ) { continue; }
	$service_supported[] = $service_slug;
	$service_rows = array();
	foreach ( $service_spec['cards'] as $service_index => $unused ) { $service_rows[ $service_index ] = array( 'title' => 'Real service ' . ( $service_index + 1 ), 'description' => "Practical work & clear next steps.\nAgreed together." ); }
	$service_brand = array_merge( $finish_profile, array( 'layout' => 'full', 'service_cards' => $service_rows ) );
	$service_base = unapp_prepare_starter_choices( $service_site, array_merge( $finish_profile, array( 'layout' => 'full' ) ) );
	$service_prepared = unapp_prepare_starter_choices( $service_site, $service_brand );
	$expect( is_array( $service_prepared ), "services: $service_slug accepts real service cards" );
	foreach ( $service_prepared['pages'] as $service_page => $service_markup ) {
		$expect( $service_unchanged( parse_blocks( $service_markup ) ) === $service_unchanged( parse_blocks( $service_base['pages'][ $service_page ] ) ), "services: $service_slug/$service_page keeps all unrelated content, prices, images and actions" );
		foreach ( $service_marked( parse_blocks( $service_markup ) ) as $service_block ) {
			$service_marker = $service_block['attrs']['metadata']['unappService'];
			$expect( false !== strpos( $service_block['innerHTML'], 'title' === $service_marker['field'] ? 'Real service ' : 'Practical work &amp; clear next steps.' ), "services: $service_slug/$service_page personalizes marked {$service_marker['field']}" );
		}
		$service_exports[ "$service_slug-$service_page" ] = $service_markup;
	}
}
$expect( 9 === count( $service_supported ), 'services: nine compatible service-based starters have explicit card slots' );
$service_bad = array_merge( $finish_profile, array( 'service_cards' => array( array( 'title' => 'Incomplete', 'description' => '' ) ) ) );
$expect( is_wp_error( unapp_prepare_starter_choices( unapp_get_starter_sites()['coach'], $service_bad ) ) && is_wp_error( unapp_prepare_starter_choices( unapp_get_starter_sites()['saas'], array_merge( $finish_profile, array( 'service_cards' => array( array( 'title' => 'Real', 'description' => 'A real service.' ) ) ) ) ) ), 'services: partial cards and unsupported starters fail before import' );
ob_start(); unapp_wizard_render_plugins( 'coach', $service_bad ); $service_invalid_review = ob_get_clean();
$expect( false !== strpos( $service_invalid_review, 'Use Back to finish the card' ) && (bool) preg_match( '/<button[^>]*disabled[^>]*>Build my site<\/button>/', $service_invalid_review ) && false !== strpos( $service_invalid_review, 'value="Incomplete"' ), 'services: incomplete review explains the fix, disables Build and retains entered copy for Back' );
$expect( is_wp_error( unapp_business_validate( array( 'service_cards' => array( 6 => array( 'title' => 'Out of range', 'description' => 'Not a slot.' ) ) ) ) ), 'services: out-of-range profile card slots are rejected' );
$service_rows = array( array( 'title' => 'Repairs & maintenance', 'description' => 'Practical help, with scope agreed first.' ), array( 'title' => 'Assembly', 'description' => 'Furniture assembled with care.' ) );
$service_brand = array_merge( $finish_profile, array( 'layout' => 'one-page', 'service_cards' => $service_rows ) );
$service_preview = unapp_personalized_preview_data( 'home-services', $service_brand );
$service_built = unapp_apply_starter_site( 'home-services', $service_brand );
$service_id = $service_built['home'];
$expect( is_array( $service_built ) && false !== strpos( $service_preview['markup'], 'Repairs &amp; maintenance' ) && false !== strpos( get_post( $service_id )->post_content, 'Repairs &amp; maintenance' ), 'services: preview and import both show the real cards' );
$service_before = get_post( $service_id )->post_content;
$service_blocks = parse_blocks( $service_before );
$service_manual = static function ( $blocks ) use ( &$service_manual ) {
	foreach ( $blocks as &$block ) {
		$marker = $block['attrs']['metadata']['unappService'] ?? array();
		if ( 0 === ( $marker['index'] ?? -1 ) && 'title' === ( $marker['field'] ?? '' ) ) {
			$block['innerHTML'] = preg_replace( '~(<h3[^>]*>).*?(</h3>)~s', '$1My direct title$2', $block['innerHTML'] ); $block['innerContent'] = array( $block['innerHTML'] );
		}
		$block['innerBlocks'] = $service_manual( $block['innerBlocks'] );
	}
	return $blocks;
};
wp_update_post( array( 'ID' => $service_id, 'post_content' => wp_slash( serialize_blocks( $service_manual( $service_blocks ) ) ) ) );
$service_profile = unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION ) );
$service_profile['service_cards'][0] = array( 'title' => 'A newer profile title', 'description' => 'Updated shared description.' );
$service_save = unapp_save_business_profile( $service_profile, unapp_business_snapshot() );
$expect( is_array( $service_save ) && $service_save['preserved'] > 0 && false !== strpos( get_post( $service_id )->post_content, 'My direct title' ) && false !== strpos( get_post( $service_id )->post_content, 'Updated shared description.' ), 'services: profile updates preserve an edited title while updating its untouched description' );
$service_expanded = unapp_expand_starter( unapp_business_snapshot() );
$service_active = unapp_launch_active_starter();
$expect( is_array( $service_expanded ) && false !== strpos( get_post( $service_active['pages']['services'] )->post_content, 'A newer profile title' ), 'services: pages added later use saved real service copy without rebuilding the homepage' );
$service_saved_content = get_post( $service_id )->post_content;
$service_saved_profile = get_option( UNAPP_BUSINESS_OPTION );
$service_failure_profile = $service_saved_profile;
$service_failure_profile['service_cards'][1]['description'] = 'A change that must roll back.';
$service_fail_option = static function ( $value, $old ) { return $old; };
add_filter( 'pre_update_option_' . UNAPP_BUSINESS_OPTION, $service_fail_option, 10, 2 );
$service_failed = unapp_save_business_profile( $service_failure_profile, unapp_business_snapshot() );
remove_filter( 'pre_update_option_' . UNAPP_BUSINESS_OPTION, $service_fail_option, 10 );
$expect( is_wp_error( $service_failed ) && $service_saved_content === get_post( $service_id )->post_content && $service_saved_profile === get_option( UNAPP_BUSINESS_OPTION ), 'services: a failed profile save restores personalized content and the previous profile' );
$service_legacy = str_replace( 'One-to-one coaching', 'Owner-written offer', unapp_get_pattern_markup( 'unapp/coach-services' ) );
$service_legacy_updated = unapp_service_cards_prepare( $service_legacy, unapp_get_starter_sites()['coach'], array( 'service_cards' => array( array( 'title' => 'Profile offer', 'description' => 'Real shared description.' ) ) ) );
$expect( false !== strpos( $service_legacy_updated, 'Owner-written offer' ) && false !== strpos( $service_legacy_updated, 'Real shared description.' ) && false === strpos( $service_legacy_updated, '>Profile offer<' ), 'services: older unlinked direct edits survive while untouched starter descriptions can be personalized' );
$service_same = unapp_prepare_starter_choices( unapp_get_starter_sites()['coach'], array_merge( $finish_profile, array( 'service_cards' => array( array( 'title' => 'Real coaching', 'description' => "First line.\nSecond line." ) ) ) ) );
$service_same_changes = 0; $service_same_kept = 0;
$service_same_after = unapp_service_cards_update( $service_same['pages']['home'], $service_same['business'], $service_same_changes, $service_same_kept );
$expect( 0 === $service_same_changes && $service_same_after === $service_same['pages']['home'], 'services: unchanged multiline descriptions are true no-op updates' );
$service_request_backup = $_REQUEST; $service_post_backup = $_POST; $service_method_backup = $_SERVER['REQUEST_METHOD'] ?? null;
$service_long = array( 'service_cards' => array( array( 'title' => '相談と計画', 'description' => str_repeat( '一緒に計画。', 80 ) ) ) );
$_POST = wp_slash( array_merge( unapp_service_card_payload( $service_long ), array( 'unapp_choices_nonce' => wp_create_nonce( 'unapp_wizard_choices' ), 'back' => '1' ) ) );
$_REQUEST = $_POST; $_SERVER['REQUEST_METHOD'] = 'POST';
$service_posted = unapp_wizard_brand_from( unapp_wizard_choice_source() );
$expect( $service_long['service_cards'] === $service_posted['service_cards'], 'services: long multilingual descriptions survive nonce-protected POST wizard transitions' );
$_REQUEST = $service_request_backup; $_POST = $service_post_backup;
if ( null === $service_method_backup ) { unset( $_SERVER['REQUEST_METHOD'] ); } else { $_SERVER['REQUEST_METHOD'] = $service_method_backup; }
$service_payload = unapp_service_card_payload( $service_profile );
$service_carried = unapp_wizard_brand_from( $service_payload );
$expect( $service_carried['service_cards'] === $service_profile['service_cards'], 'services: flat Back/preview/build payload preserves service card rows' );
$service_guide = unapp_content_guide_data( $service_id );
$service_section = reset( $service_guide['sections'] );
$service_links = unapp_content_return_links( $service_guide, $service_section['id'] );
$expect( count( $service_links ) === 3 && $service_links[0]['url'] === unapp_content_guide_url( $service_section['id'], $service_id ) && $service_links[1]['url'] === unapp_finish_url() && false !== strpos( $service_links[2]['url'], 'section=' ), 'editor returns: server builds guide, setup and next-section shortcuts for the actual starter page' );
$service_last = end( $service_guide['sections'] );
$expect( 2 === count( unapp_content_return_links( $service_guide, $service_last['id'] ) ), 'editor returns: the last section does not offer a nonexistent next section' );

$service_fixture = getenv( 'UNAPP_SERVICE_PREVIEW_DIR' );
if ( $service_fixture && is_dir( $service_fixture ) ) {
	$_SERVER['SERVER_NAME'] = wp_parse_url( home_url(), PHP_URL_HOST );
	$_GET = array( 'back' => '1', 'site_title' => $service_brand['title'], 'public_email' => $service_brand['email'], 'public_phone' => $service_brand['phone'], 'layout' => 'one-page' ) + $service_payload;
	ob_start(); unapp_wizard_render_brand( 'home-services' ); unapp_starter_styles(); $service_picker = ob_get_clean(); $_GET = array();
	ob_start(); unapp_render_business_screen(); $service_screen = ob_get_clean();
	ob_start(); unapp_wizard_render_plugins( 'home-services', array_merge( $service_brand, array( 'service_cards' => $service_profile['service_cards'] ) ) ); unapp_starter_styles(); $service_confirmation = ob_get_clean();
	wp_styles()->done = array();
	foreach ( array( 'common', 'forms', 'buttons', 'wp-components' ) as $service_handle ) { wp_enqueue_style( $service_handle ); }
	ob_start(); wp_print_styles( array( 'common', 'forms', 'buttons', 'wp-components' ) ); $service_css = ob_get_clean();
	foreach ( array( 'picker' => $service_picker, 'profile' => $service_screen, 'confirmation' => $service_confirmation ) as $service_name => $service_html ) { file_put_contents( "$service_fixture/$service_name.html", '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $service_css . '<style>body{padding:20px;margin:0;background:#f0f0f1}.unapp-service-fields input,.unapp-service-fields textarea{max-width:100%}</style></head><body class="wp-admin wp-core-ui">' . $service_html . '</body></html>' ); }
	wp_scripts()->done = array();
	ob_start(); wp_print_scripts( array( 'wp-block-library', 'wp-dom-ready', 'wp-editor', 'wp-notices' ) ); $service_scripts = ob_get_clean();
	file_put_contents( "$service_fixture/editor.html", '<!doctype html><html><head>' . $service_css . $service_scripts . '</head><body><div id="shortcuts"></div><script>wp.blockLibrary.registerCoreBlocks();</script></body></html>' );
	file_put_contents( "$service_fixture/blocks.json", wp_json_encode( $service_exports ) );
	file_put_contents( "$service_fixture/paths.json", wp_json_encode( array( 'theme_url' => get_template_directory_uri(), 'theme_path' => $root, 'site_url' => site_url(), 'wp_path' => ABSPATH ) ) );
	file_put_contents( "$service_fixture/returns.json", wp_json_encode( array( 'section' => $service_section['id'], 'root' => $service_section['root'], 'returnLinks' => $service_links, 'returnMessage' => 'Save changes before leaving. Review is confirmed in the guide.', 'saveMessage' => 'Save changes before using this shortcut.' ) ) );
	file_put_contents( "$service_fixture/home.json", wp_json_encode( get_post( $service_id )->post_content ) );
}
