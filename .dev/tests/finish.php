<?php
/** Finishing workspace and native main actions, inside import.php's transaction. */
$finish_profile = array_merge( $profile, array( 'layout' => 'one-page', 'email' => 'owner@finish.test', 'phone' => '+44 117 123 8888' ) );
$finish_blocks = array();
foreach ( unapp_get_starter_sites() as $slug => $unused ) {
	if ( 'shop' === $slug ) {
		continue;
	}
	$pages = unapp_apply_starter_site( $slug, $finish_profile );
	$expect( is_array( $pages ), "finish: $slug setup succeeds" );
	$saved = unapp_save_main_action( array( 'type' => 'email', 'label' => 'Talk to us' ), unapp_business_snapshot() );
	$expect( is_array( $saved ) && 3 === $saved['updated'], "finish: $slug updates hero, closing and header actions: " . ( is_wp_error( $saved ) ? $saved->get_error_message() : wp_json_encode( $saved ) ) );
	$home = get_post( $pages['home'] )->post_content;
	$expect( 2 === substr_count( $home, '>Talk to us</a>' ) && false !== strpos( $home, 'mailto:owner@finish.test' ), "finish: $slug homepage contains two shared actions" );
	$finish_blocks[ $slug ] = $home;
}
$pages = unapp_apply_starter_site( 'restaurant', $finish_profile );
$home = $pages['home'];
$original = get_post( $home )->post_content;
$header_original = unapp_design_part_content( 'header' );
$snapshot = unapp_business_snapshot();
$saved = unapp_save_main_action( array( 'type' => 'book', 'url' => 'https://booking.test/reserve?team=one&service=two', 'label' => 'Book a visit' ), $snapshot );
$expect( is_array( $saved ) && 3 === $saved['updated'], 'finish: external booking applies consistently' );
$finish_blocks['booking'] = get_post( $home )->post_content;
$expect( false !== strpos( $finish_blocks['booking'], '>Explore the menu</a>' ), 'finish: secondary homepage actions retain their original destinations and wording' );
$expect( is_wp_error( unapp_save_main_action( array( 'type' => 'email' ), $snapshot ) ), 'finish: stale action forms cannot overwrite newer settings' );
$again = unapp_save_main_action( array( 'type' => 'book', 'url' => 'https://booking.test/reserve?team=one&service=two', 'label' => 'Book a visit' ), unapp_business_snapshot() );
$expect( is_array( $again ) && 0 === $again['updated'], 'finish: replaying the same action is a no-op' );
$saved = unapp_save_main_action( array( 'type' => 'starter' ), unapp_business_snapshot() );
$expect( is_array( $saved ) && 3 === $saved['updated'] && false !== strpos( get_post( $home )->post_content, '>Request a table</a>' ) && false !== strpos( get_post( $home )->post_content, 'subject=Table%20booking' ), 'finish: reset restores starter-specific labels and email subjects' );

// Simulate a starter imported before the action marker existed.
$strip = static function ( $blocks ) use ( &$strip ) {
	foreach ( $blocks as &$block ) {
		unset( $block['attrs']['metadata']['unappAction'] );
		$block['innerBlocks'] = $strip( $block['innerBlocks'] );
	}
	return $blocks;
};
wp_update_post( array( 'ID' => $home, 'post_content' => wp_slash( serialize_blocks( $strip( parse_blocks( $original ) ) ) ) ) );
$header_id = unapp_get_customised_part( 'header' )->ID;
wp_update_post( array( 'ID' => $header_id, 'post_content' => wp_slash( serialize_blocks( $strip( parse_blocks( $header_original ) ) ) ) ) );
$saved = unapp_save_main_action( array( 'type' => 'call' ), unapp_business_snapshot() );
$expect( is_array( $saved ) && 3 === $saved['updated'], 'finish: unchanged older generated buttons can be adopted safely' );
$public = unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION ) );
$saved = unapp_save_business_profile( array_merge( $public, array( 'phone' => '+44 117 123 7777' ) ), unapp_business_snapshot() );
$expect( is_array( $saved ) && false !== strpos( unapp_design_part_content( 'header' ), 'tel:+441171237777' ), 'finish: selected call buttons follow a changed public phone' );
$custom = static function ( $blocks ) use ( &$custom ) {
	unapp_main_action_first( $blocks, static function ( $block ) {
		$block['innerHTML'] = unapp_main_action_button( $block['innerHTML'], array( 'url' => 'https://custom.test/visit', 'label' => 'My custom action' ) );
		$block['innerContent'] = array( $block['innerHTML'] );
		return $block;
	} );
	return $blocks;
};
wp_update_post( array( 'ID' => $home, 'post_content' => wp_slash( serialize_blocks( $custom( parse_blocks( get_post( $home )->post_content ) ) ) ) ) );
$saved = unapp_save_main_action( array( 'type' => 'email' ), unapp_business_snapshot() );
$expect( is_array( $saved ) && 2 === $saved['updated'] && 1 === $saved['kept'] && false !== strpos( get_post( $home )->post_content, 'https://custom.test/visit' ), 'finish: direct button edits survive action changes' );
$public = unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION ) );
$saved = unapp_save_business_profile( array_merge( $public, array( 'email' => 'updated@finish.test' ) ), unapp_business_snapshot() );
$expect( is_array( $saved ) && false !== strpos( unapp_design_part_content( 'header' ), 'mailto:updated@finish.test' ) && false !== strpos( get_post( $home )->post_content, 'https://custom.test/visit' ), 'finish: selected email follows the profile while direct edits stay intact' );
$expect( is_wp_error( unapp_save_business_profile( array_merge( $public, array( 'email' => '' ) ), unapp_business_snapshot() ) ), 'finish: removing a contact used by the main action requires choosing another action first' );
$saved = unapp_save_main_action( array( 'type' => 'starter' ), unapp_business_snapshot() );
$expect( is_array( $saved ) && false !== strpos( get_post( $home )->post_content, 'mailto:updated@finish.test?subject=' ), 'finish: restoring starter actions uses the current public email' );
foreach ( array( array( 'type' => 'unknown' ), array( 'type' => 'book', 'url' => 'javascript:alert(1)' ), array( 'type' => 'book' ), array( 'type' => 'shop' ) ) as $invalid ) {
	$expect( is_wp_error( unapp_save_main_action( $invalid, unapp_business_snapshot() ) ), 'finish: unavailable or unsafe destinations are rejected' );
}
$expect( is_wp_error( unapp_main_action_target( array( 'type' => 'call' ), array( 'phone' => '' ) ) ), 'finish: call needs a public phone' );
$expect( is_wp_error( unapp_main_action_target( array( 'type' => 'email' ), array( 'email' => '' ) ) ), 'finish: email needs a public email' );
$quote = unapp_main_action_target( array( 'type' => 'quote' ) );
$expect( is_array( $quote ) && get_permalink( $home ) . '#business-details' === $quote['url'], 'finish: one-page quote uses real homepage contact details' );

$before_home = get_post( $home )->post_content;
$before_header = unapp_design_part_content( 'header' );
$before_active = get_option( UNAPP_STARTER_OPTION );
$fail_action = static function ( $value, $old ) { return $old; };
add_filter( 'pre_update_option_' . UNAPP_STARTER_OPTION, $fail_action, 10, 2 );
$failed = unapp_save_main_action( array( 'type' => 'quote' ), unapp_business_snapshot() );
remove_filter( 'pre_update_option_' . UNAPP_STARTER_OPTION, $fail_action, 10 );
$expect( is_wp_error( $failed ) && $before_home === get_post( $home )->post_content && $before_header === unapp_design_part_content( 'header' ) && $before_active === get_option( UNAPP_STARTER_OPTION ), 'finish: failed settings save restores both native documents' );
$fail_header = static function ( $data ) {
	if ( 'wp_template_part' === $data['post_type'] && false !== strpos( $data['post_content'], 'Book a failed visit' ) ) {
		$data['post_content'] = str_replace( 'Book a failed visit', 'Filtered by another plugin', $data['post_content'] );
	}
	return $data;
};
add_filter( 'wp_insert_post_data', $fail_header );
$failed = unapp_save_main_action( array( 'type' => 'book', 'label' => 'Book a failed visit', 'url' => 'https://booking.test/fail' ), unapp_business_snapshot() );
remove_filter( 'wp_insert_post_data', $fail_header );
$expect( is_wp_error( $failed ) && $before_home === get_post( $home )->post_content && $before_header === unapp_design_part_content( 'header' ) && $before_active === get_option( UNAPP_STARTER_OPTION ), 'finish: an incomplete header save rolls back the homepage and settings too' );
$admin = get_current_user_id();
wp_set_current_user( 0 );
$expect( is_wp_error( unapp_save_main_action( array( 'type' => 'quote' ), unapp_business_snapshot() ) ), 'finish: action saving requires design capabilities' );
wp_set_current_user( $admin );
$expect( true === unapp_design_apply( 'header', 'simple', unapp_design_fingerprint( 'header' ) ), 'finish: native header without an action applies' );
$saved = unapp_save_main_action( array( 'type' => 'quote' ), unapp_business_snapshot() );
$expect( is_array( $saved ) && ! unapp_design_find_block( parse_blocks( unapp_design_part_content( 'header' ) ), 'core/button' ), 'finish: action selection never inserts a button into a buttonless header' );
$next = unapp_finish_next( array(), array(), array() );
$expect( false !== strpos( $next[1], 'unapp-starter-sites' ), 'finish: empty sites start with starter selection' );
$next = unapp_finish_next( get_option( UNAPP_STARTER_OPTION ), unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION ) ), unapp_launch_checklist() );
$expect( unapp_content_guide_url( '', unapp_content_guide_next_page() ) === $next[1], 'finish: next step leads to guided native content review after choosing an action' );

$fixture = getenv( 'UNAPP_FINISH_PREVIEW_DIR' );
if ( $fixture && is_dir( $fixture ) ) {
	$_SERVER['SERVER_NAME'] = wp_parse_url( home_url(), PHP_URL_HOST );
	update_option( 'blog_public', '0' );
	ob_start(); unapp_render_finish_screen(); $screen = ob_get_clean();
	foreach ( array( 'common', 'forms', 'buttons' ) as $handle ) { wp_enqueue_style( $handle ); }
	wp_styles()->done = array();
	ob_start(); wp_print_styles( array( 'common', 'forms', 'buttons' ) ); $css = ob_get_clean();
	file_put_contents( $fixture . '/screen.html', '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $css . '<style>body{margin:0;padding:20px;background:#f0f0f1}</style></head><body class="wp-admin wp-core-ui">' . $screen . '</body></html>' );
	file_put_contents( $fixture . '/paths.json', wp_json_encode( array( 'theme_url' => get_template_directory_uri(), 'theme_path' => $root, 'site_url' => site_url(), 'wp_path' => ABSPATH ) ) );
	$finish_blocks['customized-home'] = get_post( $home )->post_content;
	$finish_blocks['header'] = unapp_design_part_content( 'header' );
	file_put_contents( $fixture . '/blocks.json', wp_json_encode( $finish_blocks ) );
	wp_scripts()->done = array();
	ob_start(); wp_print_scripts( array( 'wp-block-library' ) ); $scripts = ob_get_clean();
	file_put_contents( $fixture . '/editor.html', '<!doctype html><html><head>' . $scripts . '</head><body><script>wp.blockLibrary.registerCoreBlocks();</script></body></html>' );
}
