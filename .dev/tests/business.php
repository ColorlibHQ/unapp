<?php
/** One-page setup, shared core-block fields and incremental growth. Transactional. */
$minimal = unapp_apply_starter_site( 'saas', array( 'layout' => 'one-page' ) );
$expect( is_array( $minimal ) && array( 'home' ) === array_keys( $minimal ), 'business: minimal API input can create a one-page starter using default branding' );
$record = get_option( UNAPP_STARTER_UNDO_OPTION );
$expect( true === unapp_undo_starter_site( $record['id'] ), 'business: minimal one-page setup has a complete recovery record' );
$profile = array_merge( $brand, array( 'title' => 'Harbor Studio', 'description' => 'Thoughtful design for growing teams.', 'location' => "Bristol\n12 Harbour Road", 'services' => "Branding\nWeb design", 'hours' => 'Monday–Friday, 09:00–17:00', 'email' => 'studio@harbor.test', 'phone' => '+44 117 123 4567', 'layout' => 'one-page', 'profile_enabled' => true ) );
$full = unapp_apply_starter_site( 'saas', array_merge( $profile, array( 'layout' => 'full' ) ) );
$expect( is_array( $full ) && 6 === count( $full ) && isset( $full['contact'], $full['blog'] ), 'business: full-site choice retains supporting pages and blog' );
$expect( false !== strpos( get_post( $full['contact'] )->post_content, 'unapp-business-profile' ), 'business: full-site contact page includes shared details' );
$record = get_option( UNAPP_STARTER_UNDO_OPTION );
$expect( true === unapp_undo_starter_site( $record['id'] ), 'business: full-site profile setup can be undone' );
$empty_contacts = unapp_apply_starter_site( 'fitness', array_merge( $profile, array( 'email' => '', 'phone' => '' ) ) );
$first_profile = unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION ) );
$filled = unapp_save_business_profile( array_merge( $first_profile, array( 'email' => 'later@harbor.test' ) ), unapp_business_snapshot() );
$expect( is_array( $filled ) && false !== strpos( get_post( $empty_contacts['home'] )->post_content, 'later@harbor.test' ) && false === strpos( get_post( $empty_contacts['home'] )->post_content, 'hello@archtwelve.example' ), 'business: contact details can be supplied after a blank-contact setup' );
$filled_profile = unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION ) );
$cleared = unapp_save_business_profile( array_merge( $filled_profile, array( 'email' => '', 'phone' => '' ) ), unapp_business_snapshot() );
$expect( is_array( $cleared ) && false === strpos( get_post( $empty_contacts['home'] )->post_content, 'mailto:later@harbor.test' ), 'business: clearing contact details removes old destinations' );
$one_page_results = array();
foreach ( unapp_get_starter_sites() as $slug => $unused ) {
	if ( 'shop' === $slug ) {
		continue; // Its required plugin is separately checked by preflight.
	}
	$count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='page'" );
	$built = unapp_apply_starter_site( $slug, $profile );
	$expect( is_array( $built ) && array( 'home' ) === array_keys( $built ), "business: $slug creates only its home page" );
	$expect( $count + 1 === (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='page'" ), "business: $slug creates no hidden blog or supporting pages" );
	$content = get_post( $built['home'] )->post_content;
	$expect( false !== strpos( $content, 'id="business-details"' ) && false !== strpos( $content, 'Harbor Studio' ), "business: $slug has real shared details and a contact anchor" );
	$menu = get_post( get_option( UNAPP_STARTER_NAV_OPTION ) )->post_content;
	preg_match_all( '/#([a-z0-9-]+)/', $menu, $anchors );
	$expect( ! empty( $anchors[1] ) && ! array_filter( $anchors[1], static function ( $anchor ) use ( $content ) { return false === strpos( $content, 'id="' . $anchor . '"' ); } ), "business: $slug section menu destinations exist" );
	$button = unapp_design_find_block( parse_blocks( unapp_design_part_content( 'header' ) ), 'core/button' );
	$tags = new WP_HTML_Tag_Processor( $button['innerHTML'] );
	$tags->next_tag( 'a' );
	$anchor = wp_parse_url( $tags->get_attribute( 'href' ), PHP_URL_FRAGMENT );
	$expect( $anchor && false !== strpos( $content, 'id="' . $anchor . '"' ), "business: $slug header action points to an existing section" );
	$one_page_results[ $slug ] = $built;
}
$built = unapp_apply_starter_site( 'saas', $profile, 'business-one-page-test' );
$expect( $built === unapp_apply_starter_site( 'saas', $profile, 'business-one-page-test' ), 'business: one-page build is idempotent per submitted request' );
$home = $built['home'];
$old = unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION ) );
$expect( 'Harbor Studio' === $old['name'] && $profile['hours'] === $old['hours'], 'business: full public profile persisted' );
$expect( 'one-page' === get_option( UNAPP_STARTER_OPTION )['layout'], 'business: one-page mode recorded for future growth' );

$home_content = get_post( $home )->post_content;
$manual = static function ( $blocks ) use ( &$manual ) {
	foreach ( $blocks as &$block ) {
		if ( 'services' === ( $block['attrs']['metadata']['unappBusiness']['field'] ?? '' ) ) {
			$block['innerHTML'] = '<p>My handwritten service list.</p>';
			$block['innerContent'] = array( $block['innerHTML'] );
		}
		$block['innerBlocks'] = $manual( $block['innerBlocks'] );
	}
	return $blocks;
};
wp_update_post( array( 'ID' => $home, 'post_content' => wp_slash( serialize_blocks( $manual( parse_blocks( $home_content ) ) ) ) ) );
$snapshot = unapp_business_snapshot();
$changed_profile = array_merge( $old, array( 'name' => 'Harbor & Co.', 'email' => 'hello@harbor.test', 'phone' => '+44 117 123 9999', 'services' => 'Digital products', 'hours' => 'Tuesday–Saturday, 10:00–18:00' ) );
$saved = unapp_save_business_profile( $changed_profile, $snapshot );
$expect( is_array( $saved ) && $saved['updated'] > 0 && $saved['preserved'] > 0, 'business: shared details update while customized fields are counted and kept: ' . ( is_wp_error( $saved ) ? $saved->get_error_message() : wp_json_encode( $saved ) ) );
$content = get_post( $home )->post_content;
$expect( false !== strpos( $content, 'My handwritten service list.' ) && false !== strpos( $content, 'hello@harbor.test' ) && false === strpos( $content, 'studio@harbor.test' ), 'business: direct edits survive and generated contact values stay synchronized' );
$expect( sanitize_option( 'blogname', 'Harbor & Co.' ) === get_option( 'blogname' ) && false !== strpos( unapp_design_part_content( 'footer' ), 'hello@harbor.test' ), 'business: site branding and footer follow the shared profile, including WordPress entity normalization' );
$expect( false === strpos( get_post( $one_page_results['portfolio']['home'] )->post_content, 'hello@harbor.test' ), 'business: earlier starter pages are not silently rewritten' );
$expect( is_wp_error( unapp_save_business_profile( $old, $snapshot ) ), 'business: stale profile forms do not overwrite newer values' );
$expect( is_wp_error( unapp_save_business_profile( array_merge( $changed_profile, array( 'email' => 'not-an-email' ) ), unapp_business_snapshot() ) ) && get_option( UNAPP_BUSINESS_OPTION ) === $changed_profile, 'business: invalid email never silently clears the saved contact' );
$expect( is_wp_error( unapp_save_business_profile( array_merge( $changed_profile, array( 'phone' => 'call us' ) ), unapp_business_snapshot() ) ), 'business: invalid phone input is reported without rewriting content' );
$user = get_current_user_id();
wp_set_current_user( 0 );
$expect( is_wp_error( unapp_save_business_profile( $old, unapp_business_snapshot() ) ) && is_wp_error( unapp_expand_starter( unapp_business_snapshot() ) ), 'business: profile and page expansion require capabilities' );
wp_set_current_user( $user );

$before_home = get_post( $home )->post_content;
$fail_profile = static function ( $value, $old ) { return $old; };
add_filter( 'pre_update_option_' . UNAPP_BUSINESS_OPTION, $fail_profile, 10, 2 );
$failed = unapp_save_business_profile( array_merge( $changed_profile, array( 'email' => 'failure@harbor.test' ) ), unapp_business_snapshot() );
remove_filter( 'pre_update_option_' . UNAPP_BUSINESS_OPTION, $fail_profile, 10 );
$expect( is_wp_error( $failed ) && get_post( $home )->post_content === $before_home && get_option( UNAPP_BUSINESS_OPTION ) === $changed_profile, 'business: failed profile save restores all shared content and options' );

$header_before = unapp_design_part_content( 'header' );
$footer_before = unapp_design_part_content( 'footer' );
$front_before = get_option( 'page_on_front' );
$expanded = unapp_expand_starter( unapp_business_snapshot() );
$expect( is_array( $expanded ) && 4 === $expanded['created'] && $expanded['menu_updated'], 'business: expansion adds the four missing SaaS pages and updates an untouched section menu' );
$expect( get_post( $home )->post_content === $before_home && $header_before === unapp_design_part_content( 'header' ) && $footer_before === unapp_design_part_content( 'footer' ) && $front_before === get_option( 'page_on_front' ), 'business: expansion preserves homepage, header, footer and front-page selection' );
$expect( is_wp_error( unapp_expand_starter( unapp_business_snapshot() ) ), 'business: repeated expansion cannot create duplicate pages' );
$record = get_option( UNAPP_STARTER_UNDO_OPTION );
$expect( true === unapp_undo_starter_site( $record['id'] ) && 'one-page' === get_option( UNAPP_STARTER_OPTION )['layout'], 'business: page addition can be undone independently of the original setup' );
$nav_id = get_option( UNAPP_STARTER_NAV_OPTION );
$custom_menu = get_post( $nav_id )->post_content . '<!-- wp:navigation-link {"label":"External","url":"https://example.test/custom"} /-->';
wp_update_post( array( 'ID' => $nav_id, 'post_content' => wp_slash( $custom_menu ) ) );
$expanded = unapp_expand_starter( unapp_business_snapshot() );
$expect( is_array( $expanded ) && ! $expanded['menu_updated'] && $custom_menu === get_post( $nav_id )->post_content, 'business: expansion preserves a manually edited navigation menu' );
$record = get_option( UNAPP_STARTER_UNDO_OPTION );
$expect( true === unapp_undo_starter_site( $record['id'] ), 'business: expansion with a custom menu can be undone' );
$fail_page = static function ( $empty, $post ) { return 'page' === $post['post_type'] && 'Contact' === $post['post_title'] ? true : $empty; };
add_filter( 'wp_insert_post_empty_content', $fail_page, 10, 2 );
$failed = unapp_expand_starter( unapp_business_snapshot() );
remove_filter( 'wp_insert_post_empty_content', $fail_page, 10 );
$expect( is_wp_error( $failed ) && 'one-page' === get_option( UNAPP_STARTER_OPTION )['layout'] && get_post( $home )->post_content === $before_home && $custom_menu === get_post( $nav_id )->post_content, 'business: failed expansion rolls back partial page creation without changing existing content' );
$previous_undo = get_option( UNAPP_STARTER_UNDO_OPTION );
$fail_recovery = static function ( $value, $old ) { return $old; };
add_filter( 'pre_update_option_' . UNAPP_STARTER_UNDO_OPTION, $fail_recovery, 10, 2 );
$failed = unapp_expand_starter( unapp_business_snapshot() );
remove_filter( 'pre_update_option_' . UNAPP_STARTER_UNDO_OPTION, $fail_recovery, 10 );
$expect( is_wp_error( $failed ) && 'one-page' === get_option( UNAPP_STARTER_OPTION )['layout'] && get_option( UNAPP_STARTER_UNDO_OPTION ) === $previous_undo && $custom_menu === get_post( $nav_id )->post_content, 'business: failed expansion recovery save rolls back pages and retains the prior Undo record' );

$fixture = getenv( 'UNAPP_BUSINESS_PREVIEW_DIR' );
if ( $fixture && is_dir( $fixture ) ) {
	$_SERVER['SERVER_NAME'] = 'unapp-design.test';
	foreach ( array( 'wizard' => static function () { unapp_wizard_render_brand( 'saas' ); unapp_starter_styles(); }, 'profile' => 'unapp_render_business_screen' ) as $name => $render ) {
		ob_start(); $render(); $html = ob_get_clean();
		file_put_contents( $fixture . '/' . $name . '.html', '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><style>body{font:14px/1.5 system-ui;padding:20px;margin:0}.regular-text,.large-text{max-width:100%;box-sizing:border-box}label{display:block}input,textarea{font:inherit}.button{display:inline-block;padding:8px;border:1px solid #aaa;color:#135e96}table{width:100%}@media(max-width:600px){th,td{display:block;text-align:left}}</style></head><body>' . $html . '</body></html>' );
	}
	file_put_contents( $fixture . '/profile-blocks.json', wp_json_encode( array( 'home' => get_post( $home )->post_content, 'footer' => unapp_design_part_content( 'footer' ) ) ) );
	file_put_contents( $fixture . '/paths.json', wp_json_encode( array( 'theme_url' => get_template_directory_uri(), 'theme_path' => $root, 'site_url' => site_url(), 'wp_path' => ABSPATH ) ) );
	wp_scripts()->done = array();
	ob_start(); wp_print_scripts( array( 'wp-block-library' ) ); $scripts = ob_get_clean();
	file_put_contents( $fixture . '/editor.html', '<!doctype html><html><head>' . $scripts . '</head><body><script>wp.blockLibrary.registerCoreBlocks();</script></body></html>' );
	wp_styles()->done = array(); wp_scripts()->done = array();
	$markup = '<!-- wp:group {"tagName":"header","layout":{"type":"default"}} --><header class="wp-block-group">' . unapp_design_part_content( 'header' ) . '</header><!-- /wp:group --><!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} --><main class="wp-block-group">' . get_post( $home )->post_content . '</main><!-- /wp:group --><!-- wp:group {"tagName":"footer","layout":{"type":"default"}} --><footer class="wp-block-group">' . unapp_design_part_content( 'footer' ) . '</footer><!-- /wp:group -->';
	ob_start(); unapp_render_starter_preview_document( array( 'title' => 'One-page website', 'markup' => $markup ) );
	file_put_contents( $fixture . '/home.html', ob_get_clean() );
}
