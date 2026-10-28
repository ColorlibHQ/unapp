<?php
/** Read-only preview equivalence, request-local branding and wizard payloads. */
$preview_logo = wp_insert_post( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'post_mime_type' => 'image/png', 'post_title' => 'Preview test logo', 'guid' => includes_url( 'images/w-logo-blue-white-bg.png' ) ) );
update_post_meta( $preview_logo, '_wp_attached_file', 'preview-logo.png' );
$expect( $preview_logo && wp_attachment_is_image( $preview_logo ) && current_user_can( 'read_post', $preview_logo ), 'personalized: logo fixture is readable: ' . wp_json_encode( array( $preview_logo, get_post_mime_type( $preview_logo ), current_user_can( 'read_post', $preview_logo ) ) ) );
wp_update_attachment_metadata( $preview_logo, array( 'width' => 100, 'height' => 100, 'file' => 'preview-logo.png', 'sizes' => array() ) );
$preview_brand = array_merge( $finish_profile, array( 'title' => 'Harbor & Co.', 'tagline' => 'Independent and local.', 'description' => 'Thoughtful products for busy teams.', 'location' => "Bristol\n12 Harbour Road", 'services' => "Websites\nBranding", 'hours' => 'Monday–Friday, 10–6', 'logo' => $preview_logo, 'logo_was' => 0, 'colors' => 'emerald', 'type' => 'inter', 'sections' => array( 'features-zigzag' ), 'main_action' => array( 'type' => 'book', 'label' => 'Book a visit', 'url' => 'https://booking.test/visit?team=one&service=two' ) ) );
// Match real allowlisted style slugs rather than assuming their filenames.
$variations = unapp_wizard_variations();
$preview_brand['colors'] = array_key_first( $variations['colors'] );
$preview_brand['type'] = array_key_first( $variations['typography'] );
foreach ( $variations['colors'] as $key => $value ) { if ( false !== strpos( $key, 'emerald' ) ) { $preview_brand['colors'] = $key; break; } }
foreach ( $variations['typography'] as $key => $value ) { if ( false !== strpos( $key, 'inter' ) ) { $preview_brand['type'] = $key; break; } }
// A first-ever Custom CSS lookup must stay request-local in previews.
remove_theme_mod( 'custom_css_post_id' );
$before_posts = $wpdb->get_results( "SELECT ID, post_content, post_status, post_title FROM {$wpdb->posts} ORDER BY ID", ARRAY_A );
$before_options = array();
foreach ( array( 'blogname', 'blogdescription', UNAPP_STARTER_OPTION, UNAPP_STARTER_NAV_OPTION, UNAPP_STARTER_UNDO_OPTION, UNAPP_BUSINESS_OPTION, 'page_on_front', 'page_for_posts', 'show_on_front', 'theme_mods_' . get_stylesheet() ) as $option ) { $before_options[ $option ] = get_option( $option, null ); }
$preview = unapp_personalized_preview_data( 'saas', $preview_brand );
$expect( is_array( $preview ) && false !== strpos( $preview['markup'], '"unappSection":"features-zigzag"' ) && false === strpos( $preview['markup'], '"unappSection":"pricing"' ), 'personalized: only selected homepage sections appear: ' . ( is_wp_error( $preview ) ? $preview->get_error_message() : '' ) );
$expect( false !== strpos( $preview['markup'], 'Thoughtful products for busy teams.' ) && 3 === substr_count( $preview['markup'], '>Book a visit</a>' ), 'personalized: business description and shared main action appear before import' );
$prepared = unapp_prepare_starter_choices( unapp_get_starter_sites()['saas'], $preview_brand );
$preview_blocks = array( 'preview' => $preview['markup'], 'home' => $prepared['pages']['home'] );
foreach ( unapp_get_starter_sites() as $slug => $site ) {
	if ( 'shop' === $slug ) { continue; }
	$case = unapp_personalized_preview_data( $slug, array_merge( $preview_brand, array( 'sections' => array(), 'main_action' => array( 'type' => 'email', 'label' => 'Contact our team', 'url' => '' ) ) ) );
	$expect( is_array( $case ) && 3 === substr_count( $case['markup'], '>Contact our team</a>' ), 'personalized: ' . $slug . ' previews selected sections and all three shared actions' );
	$preview_blocks[ $slug ] = $case['markup'];
}
$expect( $before_posts === $wpdb->get_results( "SELECT ID, post_content, post_status, post_title FROM {$wpdb->posts} ORDER BY ID", ARRAY_A ), 'personalized: preparing previews creates or edits no posts' );
$_SERVER['SERVER_NAME'] = wp_parse_url( home_url(), PHP_URL_HOST );
wp_styles()->done = array(); wp_scripts()->done = array();
ob_start(); unapp_render_starter_preview_document( $preview ); $preview_document = ob_get_clean();
$expect( false !== strpos( $preview_document, 'Harbor &amp; Co.' ) && false !== strpos( $preview_document, 'preview-logo.png' ), 'personalized: native site title and selected Media Library logo render using draft branding' );
$expect( false !== strpos( $preview_document, 'data-unapp-preview-destination="https://booking.test/visit?team=one&amp;service=two"' ) && false === strpos( $preview_document, 'href="https://booking.test/' ), 'personalized: booking actions are visible but cannot navigate or submit in preview' );
$disabled = unapp_personalized_preview_links( '<form><button>Submit</button></form><a class="add_to_cart_button" href="/?add-to-cart=42">Buy</a><button class="wp-block-navigation__responsive-container-open">Menu</button>' );
$expect( 3 === substr_count( $disabled, 'aria-disabled="true"' ) && false !== strpos( $disabled, '<button class="wp-block-navigation__responsive-container-open">' ), 'personalized: purchase/submission controls are inert while native menu controls stay usable' );
foreach ( $before_options as $option => $value ) { $expect( $value === get_option( $option, null ), 'personalized: preview leaves ' . $option . ' unchanged and removes temporary filters: ' . wp_json_encode( array( 'before' => $value, 'after' => get_option( $option, null ) ) ) ); }
$expect( $before_posts === $wpdb->get_results( "SELECT ID, post_content, post_status, post_title FROM {$wpdb->posts} ORDER BY ID", ARRAY_A ), 'personalized: rendering previews creates or edits no content' );
$built = unapp_apply_starter_site( 'saas', $preview_brand );
$expect( is_array( $built ) && get_post( $built['home'] )->post_content === $prepared['pages']['home'], 'personalized: import saves exactly the homepage markup prepared for preview' );
$expect( get_option( UNAPP_STARTER_OPTION )['main_action']['type'] === 'book' && 1 === substr_count( unapp_design_part_content( 'header' ), '>Book a visit</a>' ), 'personalized: chosen action is saved on the header and finishing workspace' );
$record = get_option( UNAPP_STARTER_UNDO_OPTION );
$expect( true === unapp_undo_starter_site( $record['id'] ) && $before_options['blogname'] === get_option( 'blogname' ), 'personalized: applying the previewed starter still supports complete setup Undo' );
$full_brand = array_merge( $preview_brand, array( 'layout' => 'full', 'main_action' => array( 'type' => 'quote', 'label' => '', 'url' => '' ) ) );
$full = unapp_personalized_preview_data( 'saas', $full_brand );
$expect( is_array( $full ) && home_url( '/#business-details' ) === $full['action']['url'] && false === strpos( $full['markup'], (string) get_permalink( unapp_launch_active_starter()['pages']['contact'] ?? 0 ) . '#business-details' ), 'personalized: quote defaults use the new homepage, independently of the old starter' );
$expect( is_wp_error( unapp_personalized_preview_data( 'saas', array_merge( $preview_brand, array( 'logo' => $home ) ) ) ), 'personalized: non-image logo IDs are rejected' );
$expect( is_wp_error( unapp_personalized_preview_data( 'saas', array_merge( $preview_brand, array( 'main_action' => array( 'type' => 'book', 'url' => 'javascript:alert(1)' ) ) ) ) ), 'personalized: unsafe action URLs fail preview validation' );
$expect( is_wp_error( unapp_personalized_preview_data( 'shop', $preview_brand ) ), 'personalized: Shop requires WooCommerce in preview as it does during import' );
$admin = get_current_user_id(); wp_set_current_user( 0 );
$expect( is_wp_error( unapp_personalized_preview_data( 'saas', $preview_brand ) ), 'personalized: draft preview data requires design permissions' );
wp_set_current_user( $admin );
ob_start(); unapp_wizard_brand_fields( 'saas', $preview_brand ); $fields = ob_get_clean();
$expect( false !== strpos( $fields, 'name="main_action_type" value="book"' ) && false !== strpos( $fields, 'name="main_action_url" value="https://booking.test/visit?team=one&amp;service=two"' ), 'personalized: preview and build share the exact escaped choice payload' );
$_GET = array( 'back' => 1, 'main_action_type' => 'book', 'main_action_label' => 'Book a visit', 'main_action_url' => 'https://booking.test/visit' );
ob_start(); unapp_wizard_render_brand( 'saas' ); $back_screen = ob_get_clean();
$expect( (bool) preg_match( '/value="book"[^>]*selected/', $back_screen ) && false !== strpos( $back_screen, 'value="Book a visit"' ), 'personalized: returning to setup retains the main action' );
$_GET = array();
$output = getenv( 'UNAPP_PERSONALIZED_PREVIEW_DIR' );
if ( $output && is_dir( $output ) ) {
	file_put_contents( $output . '/home.html', $preview_document );
	file_put_contents( $output . '/blocks.json', wp_json_encode( $preview_blocks ) );
	file_put_contents( $output . '/variation.json', wp_json_encode( unapp_compose_variation( $preview_brand['colors'], $preview_brand['type'] ) ) );
	file_put_contents( $output . '/paths.json', wp_json_encode( array( 'theme_url' => get_template_directory_uri(), 'theme_path' => $root, 'site_url' => site_url(), 'wp_path' => ABSPATH, 'logo_url' => wp_get_attachment_url( $preview_logo ) ) ) );
	ob_start(); unapp_wizard_render_plugins( 'saas', $preview_brand ); unapp_starter_styles(); $screen = ob_get_clean();
	wp_styles()->done = array();
	foreach ( array( 'common', 'forms', 'buttons' ) as $handle ) { wp_enqueue_style( $handle ); }
	ob_start(); wp_print_styles( array( 'common', 'forms', 'buttons' ) ); $css = ob_get_clean();
	file_put_contents( $output . '/screen.html', '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $css . '<style>:root{--wp-admin-theme-color:#2271b1}body{margin:0;padding:20px;background:#f0f0f1}</style></head><body class="wp-admin wp-core-ui">' . $screen . '<script src="' . esc_url( get_theme_file_uri( 'assets/js/setup-preview.js' ) ) . '"></script></body></html>' );
	wp_scripts()->done = array();
	ob_start(); wp_print_scripts( array( 'wp-block-library' ) ); $scripts = ob_get_clean();
	file_put_contents( $output . '/editor.html', '<!doctype html><html><head>' . $scripts . '</head><body><script>wp.blockLibrary.registerCoreBlocks();</script></body></html>' );
}
