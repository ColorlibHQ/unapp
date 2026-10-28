<?php
/** Optional sections and owner content reviews, transaction-isolated. */
$guide_blocks = array();
foreach ( unapp_get_starter_sites() as $slug => $site ) {
	$choices = unapp_section_choices( $site );
	$expect( count( $choices ) >= 4 && reset( $choices )['required'] && end( $choices )['required'], "sections: $slug preserves intro and closing section" );
	if ( 'shop' === $slug ) { continue; }
	$built = unapp_apply_starter_site( $slug, array_merge( $finish_profile, array( 'sections' => array() ) ) );
	$expect( is_array( $built ), "sections: $slug builds with no optional sections" );
	$markup = get_post( $built['home'] )->post_content;
	foreach ( $choices as $key => $choice ) {
		$expect( $choice['required'] === ( false !== strpos( $markup, '"unappSection":"' . $key . '"' ) ), "sections: $slug/$key matches the selected composition" );
	}
	preg_match_all( '/href="([^"]+)"/', $markup . get_post( get_option( UNAPP_STARTER_NAV_OPTION ) )->post_content, $links );
	$dead = array();
	foreach ( $links[1] as $link ) {
		$url = html_entity_decode( $link, ENT_QUOTES, 'UTF-8' );
		$anchor = wp_parse_url( $url, PHP_URL_FRAGMENT );
		if ( $anchor && ( 0 === strpos( $url, '#' ) || untrailingslashit( strtok( $url, '#' ) ) === untrailingslashit( home_url( '/' ) ) ) && false === strpos( $markup, 'id="' . $anchor . '"' ) ) { $dead[] = $url; }
	}
	$expect( ! $dead, "sections: $slug has no links to removed homepage anchors: " . wp_json_encode( $dead ) );
	$guide_blocks[ $slug ] = $markup;
}
$count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}" );
$expect( is_wp_error( unapp_apply_starter_site( 'saas', array( 'sections' => array( 'not-a-section' ) ) ) ) && $count === (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}" ), 'sections: unknown choices fail before writing content' );
$built = unapp_apply_starter_site( 'saas', array_merge( $finish_profile, array( 'sections' => array( 'testimonials' ) ) ) );
$home = $built['home'];
$markup = get_post( $home )->post_content;
$expect( false !== strpos( $markup, '"unappSection":"testimonials"' ) && false === strpos( $markup, '"unappSection":"pricing"' ), 'sections: specific optional choices are included independently' );
$expect( false !== strpos( $markup, '>Contact us</a>' ) && false === strpos( $markup, 'href="' . home_url( '/#pricing' ) . '"' ), 'sections: omitted primary destinations get an honest working contact action' );
$source = array( 'sections_present' => '1', 'sections' => array( 'testimonials' ), 'layout' => 'one-page' );
$carried = unapp_wizard_brand_from( $source );
$expect( array( 'testimonials' ) === $carried['sections'] && array() === unapp_wizard_brand_from( array( 'sections_present' => '1' ) )['sections'] && null === unapp_wizard_brand_from( array() )['sections'], 'sections: omitted, empty and explicit selections remain distinct' );
$_GET = array( 'back' => '1' ) + $source;
ob_start(); unapp_wizard_render_brand( 'saas' ); $picker_html = ob_get_clean();
$expect( (bool) preg_match( '/value="testimonials"[^>]*checked/', $picker_html ) && ! preg_match( '/value="pricing"[^>]*checked/', $picker_html ), 'sections: wizard Back retains selected and unselected checkboxes' );
$_GET = array();

$data = unapp_content_guide_data();
$expect( 4 === count( $data['sections'] ) && 0 === $data['complete'], 'guide: only actual selected sections and shared contacts appear' );
$ids = array_keys( $data['sections'] );
$next = unapp_review_content_section( $ids[0], $data['sections'][ $ids[0] ]['hash'] );
$expect( $ids[1] === $next && 1 === unapp_content_guide_data()['complete'] && $markup === get_post( $home )->post_content, 'guide: explicit review advances without changing native content' );
$expect( ! is_wp_error( unapp_review_content_section( $ids[1], $data['sections'][ $ids[1] ]['hash'] ) ), 'guide: another section can be reviewed' );
$edited = parse_blocks( get_post( $home )->post_content );
foreach ( $edited as &$block ) {
	if ( $ids[0] === ( $block['attrs']['metadata']['unappSection'] ?? '' ) ) {
		$block['attrs']['metadata']['name'] = 'My rewritten introduction';
	}
}
unset( $block );
wp_update_post( array( 'ID' => $home, 'post_content' => wp_slash( serialize_blocks( $edited ) ) ) );
$current = unapp_content_guide_data();
$expect( ! $current['sections'][ $ids[0] ]['reviewed'] && $current['sections'][ $ids[1] ]['reviewed'], 'guide: an edited section resets independently of other reviews' );
$expect( is_wp_error( unapp_review_content_section( $ids[0], $data['sections'][ $ids[0] ]['hash'] ) ), 'guide: stale review forms cannot approve newer content' );
$old_review = get_option( UNAPP_CONTENT_REVIEWS );
$fail_review = static function ( $value, $old ) { return $old; };
add_filter( 'pre_update_option_' . UNAPP_CONTENT_REVIEWS, $fail_review, 10, 2 );
$failed = unapp_review_content_section( $ids[0], $current['sections'][ $ids[0] ]['hash'] );
remove_filter( 'pre_update_option_' . UNAPP_CONTENT_REVIEWS, $fail_review, 10 );
$expect( is_wp_error( $failed ) && get_option( UNAPP_CONTENT_REVIEWS ) === $old_review, 'guide: failed review saves retain previous progress' );
$admin = get_current_user_id(); wp_set_current_user( 0 );
$expect( is_wp_error( unapp_review_content_section( $ids[0], $current['sections'][ $ids[0] ]['hash'] ) ), 'guide: reviewing requires design and page editing permissions' );
wp_set_current_user( $admin );
foreach ( unapp_content_guide_data()['sections'] as $id => $section ) { unapp_review_content_section( $id, $section['hash'] ); }
$expect( 4 === unapp_content_guide_data()['complete'], 'guide: all homepage sections can be explicitly reviewed' );
$guide_checks = unapp_launch_checklist();
$expect( ! $guide_checks['checks']['content']['complete'], 'guide: homepage review does not silently approve supporting pages, header or footer' );
$built_full = unapp_apply_starter_site( 'saas', array_merge( $finish_profile, array( 'layout' => 'full', 'sections' => array() ) ) );
$expect( isset( $built_full['pricing'], $built_full['contact'] ) && false === strpos( get_post( $built_full['home'] )->post_content, '"unappSection":"pricing"' ), 'sections: full-site supporting pages stay available when homepage sections are omitted' );
$expect( 0 === unapp_content_guide_data()['complete'], 'guide: a new starter homepage has its own review progress' );
$modern = get_post( $built_full['home'] )->post_content;
$legacy_blocks = parse_blocks( $modern );
foreach ( $legacy_blocks as &$block ) { unset( $block['attrs']['metadata']['name'], $block['attrs']['metadata']['unappSection'] ); }
unset( $block );
$legacy = serialize_blocks( $legacy_blocks );
wp_update_post( array( 'ID' => $built_full['home'], 'post_content' => wp_slash( $legacy ) ) );
$expect( 3 === count( unapp_content_guide_data()['sections'] ) && $legacy === get_post( $built_full['home'] )->post_content, 'guide: older unlabelled imports are readable without rewriting their content' );
wp_update_post( array( 'ID' => $built_full['home'], 'post_content' => wp_slash( $modern ) ) );

// Supporting-page reviews and image targets use current saved native blocks.
$pages = unapp_content_guide_pages();
$expect( count( $built_full ) - 1 === count( $pages ) && ! isset( $pages[ $built_full['blog'] ] ), 'guide: imported content pages are listed, while the posts index is excluded' );
$support_id = $built_full['features'];
$support_original = get_post( $support_id )->post_content;
$image_markup = unapp_get_pattern_markup( 'unapp/photographer-work-h1' ) . unapp_get_pattern_markup( 'unapp/hero-cover' );
wp_update_post( array( 'ID' => $support_id, 'post_content' => wp_slash( $image_markup ) ) );
$support = unapp_content_guide_data( $support_id );
$support_keys = array_keys( $support['sections'] );
$expect( $support_id === $support['page'] && 2 === count( $support['sections'] ), 'guide: a supporting page exposes its own current sections' );
$home_before_support = unapp_content_guide_data();
$home_first_section = reset( $home_before_support['sections'] );
unapp_review_content_section( $home_first_section['id'], $home_first_section['hash'] );
$first = $support['sections'][ $support_keys[0] ];
$cover = $support['sections'][ $support_keys[1] ];
$expect( 3 === $first['images'] && 3 === count( array_unique( array_column( $first['image_items'], 'path' ) ) ) && 1 === $cover['images'] && $cover['image_items'][0]['cover'] && '' === $cover['image_items'][0]['path'], 'guide: every gallery image and root Cover has an exact native block path' );
$expect( ! array_filter( $first['image_items'], static function ( $image ) { return ! $image['demo']; } ), 'guide: bundled portfolio imagery is identified as demo content' );
$expect( $support_keys[1] === unapp_review_content_section( $first['id'], $first['hash'], $support_id ) && 1 === unapp_content_guide_data( $support_id )['complete'] && 1 === unapp_content_guide_data()['complete'], 'guide: supporting-page progress is separate from homepage progress' );
$expect( is_wp_error( unapp_review_content_section( $first['id'], $first['hash'], $built_full['pricing'] ) ), 'guide: a review hash cannot approve another page' );
$expect( true === ( $image_markup === get_post( $support_id )->post_content ), 'guide: reviewing never rewrites supporting page blocks' );
wp_update_post( array( 'ID' => $support_id, 'post_content' => wp_slash( str_replace( 'A few ways of seeing', 'My selected photographs', $image_markup ) ) ) );
$expect( 0 === unapp_content_guide_data( $support_id )['complete'] && is_wp_error( unapp_review_content_section( $first['id'], $first['hash'], $support_id ) ), 'guide: supporting-page changes invalidate stale reviews' );
wp_update_post( array( 'ID' => $support_id, 'post_content' => wp_slash( $image_markup ) ) );
$unrelated = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Unrelated page', 'post_content' => $image_markup ) );
$expect( ! unapp_content_guide_data( $unrelated )['page'] && is_wp_error( unapp_review_content_section( $first['id'], $first['hash'], $unrelated ) ), 'guide: arbitrary page IDs cannot enter the starter guide' );
wp_update_post( array( 'ID' => $support_id, 'post_status' => 'trash' ) );
$expect( ! unapp_content_guide_data( $support_id )['page'], 'guide: trashed supporting pages cannot be reviewed' );
wp_update_post( array( 'ID' => $support_id, 'post_status' => 'publish' ) );
$admin = get_current_user_id(); wp_set_current_user( 0 );
$expect( ! unapp_content_guide_pages() && is_wp_error( unapp_review_content_section( $first['id'], $first['hash'], $support_id ) ), 'guide: supporting pages require page-editing permissions' );
wp_set_current_user( $admin );
foreach ( unapp_content_guide_data()['sections'] as $key => $section ) { unapp_review_content_section( $key, $section['hash'] ); }
$expect( $support_id === unapp_content_guide_next_page(), 'guide: completing the homepage advances to supporting pages' );
$next = unapp_finish_next( array_merge( unapp_launch_active_starter(), array( 'main_action' => array( 'type' => 'starter' ) ) ), unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION ) ), unapp_launch_checklist() );
$expect( unapp_content_guide_url( '', $support_id ) === $next[1], 'guide: Finish your website resumes at the next supporting page' );
// Leave the home fixture unreviewed to exercise both kinds of screen.
update_option( UNAPP_CONTENT_REVIEWS, array( 'home' => $built_full['home'], 'theme' => get_stylesheet(), 'reviews' => array() ), false );

$fixture = getenv( 'UNAPP_CONTENT_PREVIEW_DIR' );
if ( $fixture && is_dir( $fixture ) ) {
	$_SERVER['SERVER_NAME'] = wp_parse_url( home_url(), PHP_URL_HOST );
	$_GET = array();
	foreach ( array( 'guide' => 'unapp_render_content_guide', 'supporting' => static function () use ( $support_id ) { $_GET = array( 'page_id' => (string) $support_id ); unapp_render_content_guide(); $_GET = array(); }, 'picker' => static function () { unapp_wizard_render_brand( 'saas' ); unapp_starter_styles(); }, 'confirmation' => static function () use ( $carried ) { unapp_wizard_render_plugins( 'saas', $carried ); unapp_starter_styles(); } ) as $name => $render ) {
		ob_start(); $render(); $screen = ob_get_clean();
		wp_styles()->done = array();
		foreach ( array( 'common', 'forms', 'buttons' ) as $handle ) { wp_enqueue_style( $handle ); }
		ob_start(); wp_print_styles( array( 'common', 'forms', 'buttons' ) ); $css = ob_get_clean();
		file_put_contents( $fixture . '/' . $name . '.html', '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $css . '<style>:root{--wp-admin-theme-color:#2271b1}body{margin:0;padding:20px;background:#f0f0f1}input,textarea{max-width:100%;box-sizing:border-box}</style></head><body class="wp-admin wp-core-ui">' . $screen . '</body></html>' );
	}
	file_put_contents( $fixture . '/paths.json', wp_json_encode( array( 'theme_url' => get_template_directory_uri(), 'theme_path' => $root, 'site_url' => site_url(), 'wp_path' => ABSPATH ) ) );
	$guide_blocks['selected'] = $markup;
	$guide_blocks['focus-supporting'] = get_post( $support_id )->post_content;
	file_put_contents( $fixture . '/focus-supporting.json', wp_json_encode( unapp_content_guide_data( $support_id ) ) );
	$guide_blocks['focus-home'] = get_post( $built_full['home'] )->post_content;
	file_put_contents( $fixture . '/blocks.json', wp_json_encode( $guide_blocks ) );
	file_put_contents( $fixture . '/focus.json', wp_json_encode( unapp_content_guide_data() ) );
	wp_scripts()->done = array();
	ob_start(); wp_print_scripts( array( 'wp-block-library', 'wp-dom-ready' ) ); $scripts = ob_get_clean();
	file_put_contents( $fixture . '/editor.html', '<!doctype html><html><head>' . $scripts . '</head><body><script>wp.blockLibrary.registerCoreBlocks();</script></body></html>' );
	$guide_previous_post = $GLOBALS['post'] ?? null;
	$GLOBALS['post'] = get_post( $built_full['home'] );
	$_GET = array( 'unapp_section' => array_key_first( unapp_content_guide_data()['sections'] ) );
	unapp_content_guide_editor_assets( 'post.php' );
	$expect( wp_script_is( 'unapp-content-guide', 'enqueued' ), 'guide: native homepage editor receives the scoped section-selection script' );
	wp_dequeue_script( 'unapp-content-guide' );
	$GLOBALS['post'] = get_post( $support_id );
	$_GET = array( 'unapp_section' => $first['id'], 'unapp_focus' => 'image', 'unapp_image' => $first['image_items'][1]['path'] );
	unapp_content_guide_editor_assets( 'post.php' );
	$expect( wp_script_is( 'unapp-content-guide', 'enqueued' ) && false !== strpos( wp_scripts()->get_data( 'unapp-content-guide', 'data' ), '"imagePath":"' . $first['image_items'][1]['path'] . '"' ), 'guide: supporting-page editor receives the exact requested image target' );
	wp_dequeue_script( 'unapp-content-guide' );
	$_GET['unapp_image'] = '999.999';
	unapp_content_guide_editor_assets( 'post.php' );
	$expect( false !== strpos( wp_scripts()->get_data( 'unapp-content-guide', 'data' ), '"imagePath":null' ), 'guide: an unknown image path falls back to normal section focus' );
	wp_dequeue_script( 'unapp-content-guide' );
	$GLOBALS['post'] = get_post( $unrelated );
	unapp_content_guide_editor_assets( 'post.php' );
	$expect( ! wp_script_is( 'unapp-content-guide', 'enqueued' ), 'guide: unrelated editor pages do not receive guide assets' );
	wp_dequeue_script( 'wp-edit-post' );
	$_GET = array();
	$GLOBALS['post'] = $guide_previous_post;
}

$guide_saved_reviews = get_option( UNAPP_CONTENT_REVIEWS );
foreach ( unapp_content_guide_pages() as $guide_page_id => $guide_page ) {
	foreach ( unapp_content_guide_data( $guide_page_id )['sections'] as $guide_section ) {
		unapp_review_content_section( $guide_section['id'], $guide_section['hash'], $guide_page_id );
	}
}
$expect( 0 === unapp_content_guide_next_page() && ! unapp_launch_checklist()['checks']['content']['complete'], 'guide: all pages complete without silently approving broader launch checks' );
update_option( UNAPP_CONTENT_REVIEWS, $guide_saved_reviews, false );
wp_update_post( array( 'ID' => $support_id, 'post_content' => wp_slash( $support_original ) ) );
