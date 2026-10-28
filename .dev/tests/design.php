<?php
/** Included by import.php inside its transaction and authenticated theme context. */
$design_admin = get_current_user_id();
$design_policy = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Design privacy policy' ) );
update_option( 'wp_page_for_privacy_policy', $design_policy );
// The harness eagerly evaluated patterns before this fixture page existed.
// Refresh conditional legal links as a normal new admin request would.
foreach ( array( 'footer-columns', 'footer-bar', 'footer-minimal' ) as $slug ) {
	ob_start();
	include $root . '/patterns/' . $slug . '.php';
	$content = ob_get_clean();
	$registry->unregister( 'unapp/' . $slug );
	$registry->register( 'unapp/' . $slug, array( 'title' => $slug, 'content' => $content ) );
}
$design_pages = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'page'" );
$design_reading = array( get_option( 'page_on_front' ), get_option( 'show_on_front' ) );
$design_nav = wp_insert_post( array( 'post_type' => 'wp_navigation', 'post_status' => 'publish', 'post_title' => 'Design fixture menu', 'post_content' => '<!-- wp:navigation-link {"label":"Our work","url":"https://example.test/work"} /-->' ) );
$design_header = unapp_get_pattern_markup( 'unapp/header' );
$design_header = preg_replace( '/<!-- wp:navigation (\{.*?\}) \/-->/', '<!-- wp:navigation {"ref":' . $design_nav . ',"ariaLabel":"Main menu"} /-->', $design_header );
$design_header = str_replace( '"width":40', '"width":72', $design_header );
$design_header = str_replace( 'href="#"', 'href="https://example.test/enquiry"', $design_header );
$design_header = str_replace( esc_html_x( 'Get Premium', 'Header call-to-action button', 'unapp' ), 'Talk to our team', $design_header );
$design_id = unapp_save_part( 'header', 'Header', $design_header );
$expect( is_int( $design_id ), 'design: native header fixture saved' );
$design_hash = unapp_design_fingerprint( 'header' );
$design_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}" );
$design_patterns = array();
foreach ( unapp_design_presets() as $area => $choices ) {
	foreach ( $choices as $key => $unused ) {
		$prepared = unapp_design_prepare( $area, $key );
		$expect( is_string( $prepared ) && has_blocks( $prepared ), "design: $area/$key is native block markup" );
		$design_patterns[ "$area-$key" ] = $prepared;
		$preview = unapp_design_preview_data( $area, $key );
		$expect( is_array( $preview ) && false !== strpos( $preview['markup'], $prepared ), "design: $area/$key preview uses the actual apply markup" );
	}
}
$expect( $design_count === (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}" ) && $design_hash === unapp_design_fingerprint( 'header' ), 'design: preparing and previewing presets writes no content' );
$expect( is_wp_error( unapp_design_prepare( 'header', 'arbitrary-pattern' ) ) && is_wp_error( unapp_design_preview_data( '../../', 'standard' ) ), 'design: unknown presets and areas rejected' );
$expect( '' === unapp_design_field( array( 'area' => array( 'header' ) ), 'area' ), 'design: array payloads are not coerced to strings' );
wp_set_current_user( 0 );
$expect( is_wp_error( unapp_design_apply( 'header', 'simple', $design_hash ) ) && is_wp_error( unapp_design_undo( 'anything' ) ), 'design: apply and undo require design capability' );
wp_set_current_user( $design_admin );
$expect( true === unapp_design_apply( 'header', 'standard', $design_hash ), 'design: reviewed header can be applied' );
$saved = get_post( $design_id )->post_content;
$expect( false !== strpos( $saved, 'Talk to our team' ) && false !== strpos( $saved, 'https://example.test/enquiry' ), 'design: custom header action survives preset selection' );
$nav_block = unapp_design_find_block( parse_blocks( $saved ), 'core/navigation' );
$logo_block = unapp_design_find_block( parse_blocks( $saved ), 'core/site-logo' );
$expect( $design_nav === $nav_block['attrs']['ref'] && 72 === $logo_block['attrs']['width'], 'design: menu reference and custom logo size preserved' );
$record = get_option( UNAPP_DESIGN_UNDO );
$expect( true === unapp_design_apply( 'header', 'standard', unapp_design_fingerprint( 'header' ) ) && $record === get_option( UNAPP_DESIGN_UNDO ), 'design: reapplying the same preset preserves its useful Undo record' );
$expect( is_wp_error( unapp_design_undo( 'stale-tab' ) ), 'design: stale undo identifier rejected' );
wp_update_post( array( 'ID' => $design_id, 'post_content' => wp_slash( $saved . '<!-- edited in WordPress -->' ) ) );
$expect( is_wp_error( unapp_design_apply( 'header', 'simple', $record['hash'] ) ), 'design: preset never overwrites an edit made after review' );
$expect( is_wp_error( unapp_design_undo( $record['id'] ) ), 'design: undo never overwrites a later editor change' );
wp_update_post( array( 'ID' => $design_id, 'post_content' => wp_slash( $saved ) ) );
$expect( true === unapp_design_undo( $record['id'] ) && get_post( $design_id )->post_content === $design_header, 'design: undo restores the preceding native template part' );
$expect( ! get_option( UNAPP_DESIGN_UNDO ), 'design: used undo record cleared' );

$design_footer_before = unapp_design_part_content( 'footer' );
$expect( true === unapp_design_apply( 'header', 'centered', unapp_design_fingerprint( 'header' ) ), 'design: centered header applies' );
$expect( unapp_design_part_content( 'footer' ) === $design_footer_before, 'design: header selection leaves footer untouched' );
$expect( false === strpos( unapp_design_part_content( 'header' ), 'Talk to our team' ), 'design: centered layout intentionally omits the action' );
$record = get_option( UNAPP_DESIGN_UNDO );
$expect( true === unapp_design_undo( $record['id'] ), 'design: omitted custom content can be recovered with undo' );
$revisions = wp_get_post_revisions( $design_id );
$expect( ! wp_revisions_enabled( get_post( $design_id ) ) || (bool) array_filter( $revisions, static function ( $revision ) use ( $design_header ) { return $revision->post_content === $design_header; } ), 'design: original customization retained in native revisions when enabled' );

$fail_design = static function ( $empty, $post ) use ( $design_id ) { return (int) ( $post['ID'] ?? 0 ) === $design_id ? true : $empty; };
add_filter( 'wp_insert_post_empty_content', $fail_design, 10, 2 );
$failed = unapp_design_apply( 'header', 'simple', unapp_design_fingerprint( 'header' ) );
remove_filter( 'wp_insert_post_empty_content', $fail_design, 10 );
$expect( is_wp_error( $failed ) && get_post( $design_id )->post_content === $design_header, 'design: failed part save retains the existing header' );
$fail_recovery = static function ( $value, $old ) { return $old; };
add_filter( 'pre_update_option_' . UNAPP_DESIGN_UNDO, $fail_recovery, 10, 2 );
$failed = unapp_design_apply( 'header', 'simple', unapp_design_fingerprint( 'header' ) );
remove_filter( 'pre_update_option_' . UNAPP_DESIGN_UNDO, $fail_recovery, 10 );
$expect( is_wp_error( $failed ) && get_post( $design_id )->post_content === $design_header, 'design: failed recovery-record save rolls back the preset' );
$expect( false === get_option( UNAPP_STARTER_LOCK ), 'design: lock released after save failure' );

$header_before = unapp_design_part_content( 'header' );
$expect( true === unapp_design_apply( 'footer', 'columns', unapp_design_fingerprint( 'footer' ) ), 'design: footer preset applies independently' );
$expect( $header_before === unapp_design_part_content( 'header' ), 'design: footer selection leaves header untouched' );
$record = get_option( UNAPP_DESIGN_UNDO );
$child_filter = static function () { return 'unapp-child-test'; };
add_filter( 'pre_option_stylesheet', $child_filter );
$expect( is_wp_error( unapp_design_undo( $record['id'] ) ), 'design: undo is scoped to the active parent or child theme' );
$expect( false !== strpos( unapp_design_editor_url( 'header' ), 'unapp-child-test' ), 'design: native editor links address the active child theme' );
remove_filter( 'pre_option_stylesheet', $child_filter );
$expect( true === unapp_design_undo( $record['id'] ), 'design: footer undo restores the preceding layout' );
$footer_part = unapp_get_customised_part( 'footer' );
$expect( true === unapp_design_apply( 'footer', 'minimal', unapp_design_fingerprint( 'footer' ) ), 'design: minimal footer applies from theme file defaults' );
$record = get_option( UNAPP_DESIGN_UNDO );
$expect( $footer_part || ! empty( $record['new'] ), 'design: a newly created customization is tracked for recovery' );
$expect( false !== strpos( unapp_design_part_content( 'footer' ), esc_url( get_privacy_policy_url() ) ), 'design: legal links use the published site privacy policy' );
$expect( true === unapp_design_undo( $record['id'] ) && ( $footer_part || ! unapp_get_customised_part( 'footer' ) ), 'design: undo removes only the customization it created and restores file defaults' );
$expect( $design_pages === (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'page'" ) && $design_reading === array( get_option( 'page_on_front' ), get_option( 'show_on_front' ) ), 'design: applying presets never creates pages or changes Reading settings' );

// Export actual admin markup and serialized presets for offline browser checks.
$design_output = getenv( 'UNAPP_DESIGN_PREVIEW_DIR' );
if ( $design_output && is_dir( $design_output ) ) {
	$_SERVER['SERVER_NAME'] = wp_parse_url( home_url(), PHP_URL_HOST );
	$expect( true === unapp_design_apply( 'footer', 'bar', unapp_design_fingerprint( 'footer' ) ), 'design: export includes a real Undo form' );
	$_GET = array( 'area' => 'header', 'preset' => 'standard' );
	ob_start();
	unapp_render_design_screen();
	$screen = ob_get_clean();
	$css = '';
	foreach ( array( 'common', 'forms', 'buttons' ) as $handle ) {
		wp_enqueue_style( $handle );
	}
	unapp_design_assets( 'appearance_page_unapp-design' );
	ob_start();
	wp_print_styles( array( 'common', 'forms', 'buttons', 'unapp-design' ) );
	$css = ob_get_clean();
	$script = '<script src="' . esc_url( get_parent_theme_file_uri( 'assets/js/design.js' ) ) . '"></script>';
	file_put_contents( $design_output . '/screen.html', '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $css . '<style>body{margin:0;padding:20px;background:#f0f0f1}</style></head><body class="wp-admin wp-core-ui">' . $screen . $script . '</body></html>' );
	file_put_contents( $design_output . '/patterns.json', wp_json_encode( $design_patterns ) );
	file_put_contents( $design_output . '/paths.json', wp_json_encode( array( 'theme_url' => get_template_directory_uri(), 'theme_path' => $root, 'site_url' => site_url(), 'wp_path' => ABSPATH ) ) );
	foreach ( unapp_design_presets() as $area => $choices ) {
		foreach ( $choices as $key => $unused ) {
			wp_styles()->done = array();
			wp_scripts()->done = array();
			ob_start();
			unapp_render_starter_preview_document( unapp_design_preview_data( $area, $key ) );
			file_put_contents( $design_output . '/' . $area . '-' . $key . '.html', ob_get_clean() );
		}
	}
	ob_start();
	wp_print_scripts( array( 'wp-block-library' ) );
	$editor_scripts = ob_get_clean();
	file_put_contents( $design_output . '/editor.html', '<!doctype html><html><head>' . $editor_scripts . '</head><body><script>wp.blockLibrary.registerCoreBlocks();window.designPatterns=' . wp_json_encode( $design_patterns, JSON_HEX_TAG ) . ';</script></body></html>' );
	$_GET = array();
}
