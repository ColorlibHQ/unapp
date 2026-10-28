<?php
/** Optional launch hints cannot alter required progress, content or settings. */
$recommend_home_id = $result['home'];
$recommend_original = get_post( $recommend_home_id )->post_content;
$recommend_original_icon = get_option( 'site_icon', null );
$recommend_original_visibility = get_option( 'blog_public', null );
$recommend_nav_id = (int) get_option( UNAPP_STARTER_NAV_OPTION );
$recommend_nav_original = get_post( $recommend_nav_id )->post_content;
$recommend_before = unapp_launch_checklist();
update_option( 'blog_public', '0' );
delete_option( 'site_icon' );
$recommend_before_reads = array( get_option( UNAPP_LAUNCH_REVIEWS ), get_option( 'blog_public' ), get_option( 'site_icon', null ), get_post( $recommend_home_id )->post_content, get_post( $recommend_nav_id )->post_content );
$recommend_no_network = 0;
$recommend_network_guard = static function () use ( &$recommend_no_network ) { ++$recommend_no_network; return new WP_Error( 'no_network', 'Network disabled for read-only recommendations.' ); };
add_filter( 'pre_http_request', $recommend_network_guard );
$recommend_data = unapp_launch_recommendations();
remove_filter( 'pre_http_request', $recommend_network_guard );
$expect( 0 === $recommend_no_network && $recommend_before_reads === array( get_option( UNAPP_LAUNCH_REVIEWS ), get_option( 'blog_public' ), get_option( 'site_icon', null ), get_post( $recommend_home_id )->post_content, get_post( $recommend_nav_id )->post_content ), 'recommended checks: scanning performs no HTTP requests, content writes or setting changes' );
$recommend_after = unapp_launch_checklist();
$expect( $recommend_before['total'] === $recommend_after['total'] && $recommend_before['complete'] === $recommend_after['complete'] && $recommend_before['snapshot'] === $recommend_after['snapshot'] && 4 === count( $recommend_data ), 'recommended checks: missing icon and discouraged indexing do not affect launch totals, reviews or snapshots' );
$expect( 'Optional branding' === $recommend_data['icon']['status'] && 'Indexing is discouraged' === $recommend_data['visibility']['status'], 'recommended checks: missing branding and intentional staging visibility are informative states' );
$recommend_icon_id = wp_insert_post( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'post_mime_type' => 'image/png', 'post_title' => 'Optional icon fixture', 'guid' => includes_url( 'images/w-logo-blue-white-bg.png' ) ) );
update_post_meta( $recommend_icon_id, '_wp_attached_file', 'optional-icon.png' );
update_option( 'site_icon', $recommend_icon_id );
$expect( 'Configured' === unapp_launch_recommendations()['icon']['status'], 'recommended checks: a saved image attachment is recognized as the icon' );
wp_update_post( array( 'ID' => $recommend_icon_id, 'post_status' => 'trash' ) );
$expect( 'Optional branding' === unapp_launch_recommendations()['icon']['status'], 'recommended checks: a trashed icon is not marked configured' );
update_option( 'site_icon', $recommend_home_id );
$expect( 'Optional branding' === unapp_launch_recommendations()['icon']['status'], 'recommended checks: non-image icon IDs are not accepted' );
update_option( 'blog_public', '1' );
$expect( 'Indexing is not discouraged' === unapp_launch_recommendations()['visibility']['status'], 'recommended checks: indexing allowed is reported without promising indexing' );

$recommend_draft = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'draft', 'post_title' => 'Private destination' ) );
$recommend_public = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Public destination', 'post_content' => '<!-- wp:group {"anchor":"real-section"} --><div class="wp-block-group" id="real-section"></div><!-- /wp:group -->' ) );
$expect( '' !== unapp_recommend_nav_reason( array( 'kind' => 'post-type', 'id' => $recommend_draft, 'url' => get_permalink( $recommend_draft ) ) ), 'recommended checks: native links to drafts are identified' );
$expect( '' !== unapp_recommend_nav_reason( array( 'kind' => 'post-type', 'id' => 99999999, 'url' => home_url( '/deleted' ) ) ), 'recommended checks: native links to deleted posts are identified' );
$expect( '' === unapp_recommend_nav_reason( array( 'kind' => 'post-type', 'id' => $recommend_public, 'url' => get_permalink( $recommend_public ) . '#real-section' ) ) && '' !== unapp_recommend_nav_reason( array( 'kind' => 'post-type', 'id' => $recommend_public, 'url' => get_permalink( $recommend_public ) . '#missing-section' ) ), 'recommended checks: section links are compared with the linked page’s saved anchors' );
$expect( '' === unapp_recommend_nav_reason( array( 'url' => 'https://external.test/#unverified' ) ) && '' === unapp_recommend_nav_reason( array( 'url' => home_url( '/plugin-route/' ) ) ), 'recommended checks: external links and unknown plugin routes are left for manual review' );
$expect( '' !== unapp_recommend_nav_reason( array( 'url' => '#' ) ) && '' !== unapp_recommend_nav_reason( array( 'url' => 'javascript:alert(1)' ) ) && '' !== unapp_recommend_nav_reason( array( 'url' => 'https://example.com/' ) ), 'recommended checks: unfinished, unsafe and example navigation destinations are identified' );
$expect( '' !== unapp_recommend_nav_reason( array( 'url' => home_url( '/?page_id=99999999' ) ) ) && '' !== unapp_recommend_nav_reason( array( 'url' => 'https:' ) ), 'recommended checks: deleted plain-ID links and malformed absolute URLs are identified' );
$recommend_menu_findings = array( 'navigation' => array(), 'images' => array() );
$recommend_bad_nav = wp_insert_post( array( 'post_type' => 'wp_navigation', 'post_status' => 'publish', 'post_title' => 'Bad navigation fixture', 'post_content' => '<!-- wp:navigation-link {"label":"Empty item","url":"#"} /-->' ) );
$recommend_ref_source = array( 'title' => 'Shared menu source', 'edit' => admin_url( 'site-editor.php' ) );
$recommend_ref_markup = '<!-- wp:navigation {"ref":' . $recommend_bad_nav . '} /-->';
unapp_recommend_scan( $recommend_ref_markup . $recommend_ref_markup, $recommend_ref_source, $recommend_menu_findings );
$expect( 1 === count( $recommend_menu_findings['navigation'] ) && false !== strpos( reset( $recommend_menu_findings['navigation'] )['edit'], 'navigation' ), 'recommended checks: reused menus deduplicate findings and open native navigation editing' );
$recommend_done = array( 'checks' => array( 'content' => array( 'complete' => true ) ), 'complete' => 7, 'total' => 7 );
$recommend_active = array_merge( unapp_launch_active_starter(), array( 'main_action' => array( 'type' => 'starter' ) ) );
$recommend_next = unapp_finish_next( $recommend_active, array( 'name' => 'Real business', 'email' => 'owner@real.test' ), $recommend_done );
$expect( home_url( '/' ) === $recommend_next[1], 'recommended checks: completed required checks still lead to viewing the website with optional hints outstanding' );
$recommend_findings = array( 'navigation' => array(), 'images' => array() );
$recommend_scan_source = array( 'title' => 'Fixture <script>alert(1)</script>', 'edit' => get_edit_post_link( $recommend_home_id, 'raw' ) );
$recommend_images = '<!-- wp:html --><img src="missing.jpg"><img src="purpose.jpg" alt=""><img src="good.jpg" alt="Describes its meaning"><img src="hidden.jpg" alt="" aria-hidden="true"><img src="decoration.jpg" alt="" role="presentation"><img src="' . esc_url( get_theme_file_uri( 'assets/images/icons/target.svg' ) ) . '" alt=""><!-- /wp:html --><!-- wp:cover {"url":"background.jpg"} --><div class="wp-block-cover"><img src="background.jpg" alt=""></div><!-- /wp:cover -->';
unapp_recommend_scan( $recommend_images, $recommend_scan_source, $recommend_findings );
$expect( 2 === count( $recommend_findings['images'] ) && ! $recommend_findings['navigation'], 'recommended checks: missing alt and undecided empty alt need review, while descriptions and decorative images do not' );
$recommend_submenu = '<!-- wp:navigation-submenu {"label":"Services"} --><!-- wp:navigation-link {"label":"Contact","url":"https://external.test/"} /--><!-- /wp:navigation-submenu -->';
unapp_recommend_scan( $recommend_submenu, $recommend_scan_source, $recommend_findings );
$expect( ! $recommend_findings['navigation'], 'recommended checks: a submenu grouping label does not require a destination' );
$recommend_shared = wp_insert_post( array( 'post_type' => 'wp_block', 'post_status' => 'publish', 'post_title' => 'Optional shared fixture', 'post_content' => $recommend_images ) );
$recommend_findings = array( 'navigation' => array(), 'images' => array() );
unapp_recommend_scan( '<!-- wp:block {"ref":' . $recommend_shared . '} /-->', $recommend_scan_source, $recommend_findings );
$expect( 2 === count( $recommend_findings['images'] ) && reset( $recommend_findings['images'] )['edit'] === get_edit_post_link( $recommend_shared, 'raw' ), 'recommended checks: synced images are inspected and link to their native editor' );
wp_update_post( array( 'ID' => $recommend_shared, 'post_content' => '<!-- wp:block {"ref":' . $recommend_shared . '} /-->' ) );
$recommend_findings = array( 'navigation' => array(), 'images' => array() );
unapp_recommend_scan( '<!-- wp:block {"ref":' . $recommend_shared . '} /-->', $recommend_scan_source, $recommend_findings );
$expect( ! $recommend_findings['images'], 'recommended checks: cyclic shared content terminates safely' );
$recommend_user = get_current_user_id(); wp_set_current_user( 0 );
$expect( array() === unapp_launch_recommendations(), 'recommended checks: findings are restricted to theme administrators' );
wp_set_current_user( $recommend_user );
$recommend_cap_filter = static function ( $caps ) { $caps['manage_options'] = false; return $caps; };
add_filter( 'user_has_cap', $recommend_cap_filter );
$recommend_restricted = unapp_launch_recommendations();
remove_filter( 'user_has_cap', $recommend_cap_filter );
$expect( '' === $recommend_restricted['icon']['edit'] && '' === $recommend_restricted['visibility']['edit'], 'recommended checks: settings links respect manage_options capabilities' );

// Capture recommendations with actual findings, outside the main review form.
wp_update_post( array( 'ID' => $recommend_home_id, 'post_content' => wp_slash( $recommend_original . $recommend_images ) ) );
$recommend_nav_markup = '<!-- wp:navigation-link ' . wp_json_encode( array( 'label' => 'Unfinished <script>alert(1)</script> menu', 'url' => '#', 'kind' => 'custom' ) ) . ' /-->';
wp_update_post( array( 'ID' => $recommend_nav_id, 'post_content' => wp_slash( $recommend_nav_markup ) ) );
update_option( 'blog_public', '0' );
delete_option( 'site_icon' );
if ( getenv( 'UNAPP_RECOMMEND_PREVIEW_PATH' ) ) {
	ob_start();
	echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><style>';
	foreach ( array( 'wp-includes/css/buttons.css', 'wp-admin/css/common.css', 'wp-admin/css/forms.css' ) as $recommend_css ) { echo file_get_contents( ABSPATH . $recommend_css ); }
	echo 'body{margin:20px;background:#f0f0f1;font:14px/1.5 sans-serif}.wrap{margin:auto}</style></head><body class="wp-core-ui">';
	unapp_render_launch_screen();
	echo '</body></html>';
	$recommend_screen = ob_get_clean();
	file_put_contents( getenv( 'UNAPP_RECOMMEND_PREVIEW_PATH' ), $recommend_screen );
	$expect( false === strpos( $recommend_screen, '<script>alert(1)</script>' ), 'recommended checks: saved labels are escaped safely' );
}
wp_update_post( array( 'ID' => $recommend_home_id, 'post_content' => wp_slash( $recommend_original ) ) );
wp_update_post( array( 'ID' => $recommend_nav_id, 'post_content' => wp_slash( $recommend_nav_original ) ) );
foreach ( array( 'site_icon' => $recommend_original_icon, 'blog_public' => $recommend_original_visibility ) as $recommend_option => $recommend_value ) {
	if ( null === $recommend_value ) { delete_option( $recommend_option ); } else { update_option( $recommend_option, $recommend_value ); }
}
