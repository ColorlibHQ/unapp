<?php
/** Section layout swaps: content fidelity, guarded saves, scoped Undo and previews. */
$swap_leaves = static function ( $block ) use ( &$swap_leaves ) {
	if ( ! $block['innerBlocks'] ) { return array( serialize_block( $block ) ); }
	$leaves = array();
	foreach ( $block['innerBlocks'] as $child ) { $leaves = array_merge( $leaves, $swap_leaves( $child ) ); }
	sort( $leaves );
	return $leaves;
};
$swap_blocks = array();
$swap_role_counts = array();
foreach ( unapp_get_starter_sites() as $swap_slug => $swap_site ) {
	if ( 'shop' === $swap_slug ) { continue; }
	$swap_built = unapp_apply_starter_site( $swap_slug, array_merge( $finish_profile, array( 'layout' => 'full' ) ) );
	$expect( is_array( $swap_built ), "swaps: $swap_slug imports for compatibility checks" );
	foreach ( unapp_content_guide_pages() as $swap_page_id => $swap_page ) {
		foreach ( unapp_content_guide_data( $swap_page_id )['sections'] as $swap_section ) {
			$swap_source = unapp_swap_source( $swap_page_id, $swap_section['id'] );
			$swap_role = unapp_swap_role( $swap_section, $swap_source['block'] );
			foreach ( unapp_swap_choices( $swap_role ) as $swap_key => $swap_label ) {
				$swap_prepared = unapp_swap_prepare( $swap_page_id, $swap_section['id'], $swap_key, $swap_section['hash'] );
				$expect( is_array( $swap_prepared ) && $swap_leaves( $swap_source['block'] ) === $swap_leaves( $swap_prepared['replacement'] ) && unapp_section_anchors( array( $swap_source['block'] ) ) === unapp_section_anchors( array( $swap_prepared['replacement'] ) ), "swaps: $swap_slug/$swap_page_id/{$swap_section['id']}/$swap_key keeps every content block and anchor" );
				$swap_blocks[ "$swap_slug-$swap_page_id-{$swap_section['id']}-$swap_key" ] = serialize_block( $swap_prepared['replacement'] );
				$swap_role_counts[ $swap_role ] = ( $swap_role_counts[ $swap_role ] ?? 0 ) + 1;
			}
		}
	}
}
$expect( count( $swap_role_counts ) === 3, 'swaps: hero, service-grid and action layouts all have compatible sections' );
$swap_built = unapp_apply_starter_site( 'coach', array_merge( $finish_profile, array( 'layout' => 'full' ) ) );
$swap_page_id = $swap_built['home'];
$swap_data = unapp_content_guide_data( $swap_page_id );
$swap_hero = $swap_data['sections']['coach-hero'];
$swap_service = $swap_data['sections']['coach-services'];
$swap_cta = $swap_data['sections']['coach-cta'];
$swap_original = get_post( $swap_page_id )->post_content;
$swap_before_options = array( get_option( UNAPP_STARTER_OPTION ), get_option( UNAPP_BUSINESS_OPTION ), get_option( UNAPP_SECTION_UNDO, null ) );
$swap_preview = unapp_swap_preview_data( $swap_page_id, $swap_hero['id'], 'image-left', $swap_hero['hash'] );
$expect( is_array( $swap_preview ) && $swap_preview['inert'] && $swap_original === get_post( $swap_page_id )->post_content && $swap_before_options === array( get_option( UNAPP_STARTER_OPTION ), get_option( UNAPP_BUSINESS_OPTION ), get_option( UNAPP_SECTION_UNDO, null ) ), 'swaps: preview prepares the real section without saving content or options' );
$expect( is_wp_error( unapp_swap_apply( $swap_page_id, $swap_hero['id'], 'invented', $swap_hero['hash'] ) ) && is_wp_error( unapp_swap_apply( 0, $swap_hero['id'], 'image-left', $swap_hero['hash'] ) ), 'swaps: unknown presets and missing page IDs cannot write content' );
$expect( true === unapp_swap_apply( $swap_page_id, $swap_hero['id'], 'image-left', $swap_hero['hash'] ), 'swaps: a reviewed hero layout applies' );
$swap_after = unapp_swap_source( $swap_page_id, $swap_hero['id'] );
$expect( serialize_block( $swap_after['block'] ) === $swap_preview['markup'], 'swaps: apply equals the exact content shown in preview' );
$expect( $swap_service['hash'] === unapp_content_guide_data( $swap_page_id )['sections'][ $swap_service['id'] ]['hash'], 'swaps: neighboring sections remain unchanged' );
$swap_undo = get_option( UNAPP_SECTION_UNDO );
$expect( is_wp_error( unapp_swap_apply( $swap_page_id, $swap_hero['id'], 'stacked', $swap_hero['hash'] ) ) && $swap_undo === get_option( UNAPP_SECTION_UNDO ), 'swaps: stale previews are rejected without replacing Undo' );
$expect( true === unapp_swap_apply( $swap_page_id, $swap_hero['id'], 'image-left', $swap_after['section']['hash'] ) && $swap_undo === get_option( UNAPP_SECTION_UNDO ), 'swaps: reapplying the same layout retains useful Undo' );
$swap_other_edit = str_replace( 'Support shaped around the work in front of you', 'My updated services heading', get_post( $swap_page_id )->post_content );
wp_update_post( array( 'ID' => $swap_page_id, 'post_content' => wp_slash( $swap_other_edit ) ) );
$expect( true === unapp_swap_undo( $swap_undo['id'] ) && false !== strpos( get_post( $swap_page_id )->post_content, 'My updated services heading' ) && serialize_block( unapp_swap_source( $swap_page_id, $swap_hero['id'] )['block'] ) === $swap_undo['before'], 'swaps: Undo restores only its section and keeps later edits elsewhere' );
$swap_current = unapp_content_guide_data( $swap_page_id )['sections'][ $swap_hero['id'] ];
unapp_review_content_section( $swap_current['id'], $swap_current['hash'], $swap_page_id );
$expect( true === unapp_swap_apply( $swap_page_id, $swap_hero['id'], 'stacked', $swap_current['hash'] ) && ! unapp_content_guide_data( $swap_page_id )['sections'][ $swap_hero['id'] ]['reviewed'], 'swaps: a changed layout needs its own content review' );
$swap_undo = get_option( UNAPP_SECTION_UNDO );
$swap_edited_hero = str_replace( 'Make room for your next move.', 'My directly edited headline', get_post( $swap_page_id )->post_content );
wp_update_post( array( 'ID' => $swap_page_id, 'post_content' => wp_slash( $swap_edited_hero ) ) );
$expect( is_wp_error( unapp_swap_undo( $swap_undo['id'] ) ) && $swap_edited_hero === get_post( $swap_page_id )->post_content, 'swaps: Undo refuses to overwrite subsequent edits to its section' );
$swap_admin = get_current_user_id(); wp_set_current_user( 0 );
$expect( is_wp_error( unapp_swap_prepare( $swap_page_id, $swap_hero['id'], 'image-right', $swap_current['hash'] ) ) && is_wp_error( unapp_swap_undo( $swap_undo['id'] ) ), 'swaps: layout preparation and Undo require design and page capabilities' );
wp_set_current_user( $swap_admin );
$swap_current = unapp_content_guide_data( $swap_page_id )['sections'][ $swap_hero['id'] ];
$swap_before_failure = get_post( $swap_page_id )->post_content;
$swap_fail_option = static function ( $value, $old ) { return $old; };
add_filter( 'pre_update_option_' . UNAPP_SECTION_UNDO, $swap_fail_option, 10, 2 );
$swap_failed = unapp_swap_apply( $swap_page_id, $swap_hero['id'], 'image-right', $swap_current['hash'] );
remove_filter( 'pre_update_option_' . UNAPP_SECTION_UNDO, $swap_fail_option, 10 );
$expect( is_wp_error( $swap_failed ) && $swap_before_failure === get_post( $swap_page_id )->post_content && $swap_undo === get_option( UNAPP_SECTION_UNDO ), 'swaps: recovery-copy failure restores the page and previous Undo' );
// A filtered/partial WordPress save must not leave half-applied content.
$swap_fail_once = 0;
$swap_fail_post = static function ( $data, $postarr ) use ( &$swap_fail_once, $swap_page_id ) {
	if ( (int) ( $postarr['ID'] ?? 0 ) === $swap_page_id && 0 === $swap_fail_once++ ) { $data['post_content'] = '<!-- wp:paragraph --><p>Filtered save</p><!-- /wp:paragraph -->'; }
	return $data;
};
add_filter( 'wp_insert_post_data', $swap_fail_post, 10, 2 );
$swap_failed = unapp_swap_apply( $swap_page_id, $swap_hero['id'], 'image-right', $swap_current['hash'] );
remove_filter( 'wp_insert_post_data', $swap_fail_post, 10 );
$expect( is_wp_error( $swap_failed ) && $swap_before_failure === get_post( $swap_page_id )->post_content, 'swaps: a filtered partial save restores the full previous page' );
$expect( true === unapp_swap_apply( $swap_page_id, $swap_hero['id'], 'image-right', $swap_current['hash'] ), 'swaps: successful saves remain available after a failed save' );
$swap_retry_undo = get_option( UNAPP_SECTION_UNDO );
$swap_before_failed_undo = get_post( $swap_page_id )->post_content;
$swap_fail_once = 0;
add_filter( 'wp_insert_post_data', $swap_fail_post, 10, 2 );
$swap_failed = unapp_swap_undo( $swap_retry_undo['id'] );
remove_filter( 'wp_insert_post_data', $swap_fail_post, 10 );
$expect( is_wp_error( $swap_failed ) && $swap_before_failed_undo === get_post( $swap_page_id )->post_content && $swap_retry_undo === get_option( UNAPP_SECTION_UNDO ) && true === unapp_swap_undo( $swap_retry_undo['id'] ), 'swaps: failed Undo keeps the current page and recovery copy for retry' );

$swap_other_editor = wp_insert_user( array( 'user_login' => 'swap-test-' . wp_generate_uuid4(), 'user_pass' => wp_generate_password(), 'role' => 'editor' ) );
update_post_meta( $swap_page_id, '_edit_lock', time() . ':' . $swap_other_editor );
$swap_current = unapp_content_guide_data( $swap_page_id )['sections'][ $swap_hero['id'] ];
$expect( is_wp_error( unapp_swap_apply( $swap_page_id, $swap_hero['id'], 'image-left', $swap_current['hash'] ) ), 'swaps: another person’s native editing lock prevents layout changes' );
delete_post_meta( $swap_page_id, '_edit_lock' );

$swap_support = unapp_content_guide_data( $swap_built['services'] );
$swap_support_section = reset( $swap_support['sections'] );
$expect( true === unapp_swap_apply( $swap_built['services'], $swap_support_section['id'], 'grid-2', $swap_support_section['hash'] ), 'swaps: supporting-page service grids can be changed too' );
$swap_support_undo = get_option( UNAPP_SECTION_UNDO );
$expect( is_wp_error( unapp_swap_undo( $swap_undo['id'] ) ) && true === unapp_swap_undo( $swap_support_undo['id'] ), 'swaps: only the latest swap can be undone' );

$swap_fixture = getenv( 'UNAPP_SWAP_PREVIEW_DIR' );
if ( $swap_fixture && is_dir( $swap_fixture ) ) {
	$_SERVER['SERVER_NAME'] = wp_parse_url( home_url(), PHP_URL_HOST );
	foreach ( array( 'hero' => $swap_hero['id'], 'services' => $swap_service['id'], 'cta' => $swap_cta['id'] ) as $swap_role => $swap_id ) {
		$swap_section = unapp_content_guide_data( $swap_page_id )['sections'][ $swap_id ];
		foreach ( unapp_swap_choices( $swap_role ) as $swap_key => $swap_label ) {
			$swap_preview = unapp_swap_preview_data( $swap_page_id, $swap_id, $swap_key, $swap_section['hash'] );
			wp_styles()->done = array(); wp_scripts()->done = array();
			ob_start(); unapp_render_starter_preview_document( $swap_preview ); $swap_html = ob_get_clean();
			file_put_contents( "$swap_fixture/$swap_role-$swap_key.html", $swap_html );
		}
	}
	$swap_current = unapp_content_guide_data( $swap_page_id )['sections'][ $swap_hero['id'] ];
	unapp_swap_apply( $swap_page_id, $swap_hero['id'], 'image-right', $swap_current['hash'] );
	$_GET = array( 'page_id' => (string) $swap_page_id, 'section' => $swap_hero['id'], 'swap' => 'image-left' );
	ob_start(); unapp_render_content_guide(); $swap_screen = ob_get_clean();
	wp_styles()->done = array();
	foreach ( array( 'common', 'forms', 'buttons' ) as $swap_handle ) { wp_enqueue_style( $swap_handle ); }
	unapp_section_swap_assets( 'appearance_page_unapp-content-guide' );
	ob_start(); wp_print_styles( array( 'common', 'forms', 'buttons', 'unapp-design' ) ); wp_print_scripts( 'unapp-design' ); $swap_assets = ob_get_clean();
	file_put_contents( "$swap_fixture/screen.html", '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $swap_assets . '<style>body{padding:20px;margin:0;background:#f0f0f1}.unapp-section-swaps select{max-width:100%}</style></head><body class="wp-admin wp-core-ui">' . $swap_screen . '<script src="' . esc_url( get_theme_file_uri( 'assets/js/design.js' ) ) . '"></script></body></html>' );
	file_put_contents( "$swap_fixture/blocks.json", wp_json_encode( $swap_blocks ) );
	file_put_contents( "$swap_fixture/paths.json", wp_json_encode( array( 'theme_url' => get_template_directory_uri(), 'theme_path' => $root, 'site_url' => site_url(), 'wp_path' => ABSPATH, 'page' => $swap_page_id ) ) );
	wp_scripts()->done = array();
	ob_start(); wp_print_scripts( array( 'wp-block-library', 'wp-dom-ready' ) ); $swap_scripts = ob_get_clean();
	file_put_contents( "$swap_fixture/editor.html", '<!doctype html><html><head>' . $swap_scripts . '</head><body><script>wp.blockLibrary.registerCoreBlocks();</script></body></html>' );
	$_GET = array();
}
