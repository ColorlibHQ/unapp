<?php
/** Refresh an allowlisted Colorlib demo in place; requires a private backup directory. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { throw new RuntimeException( 'Use WP-CLI.' ); }
$map = array( 'unapp' => array( 'saas', 'Unapp' ), 'unapp-portfolio' => array( 'portfolio', 'Mara Lindqvist' ), 'unapp-church' => array( 'church', 'Riverside Church' ), 'unapp-blog' => array( 'blog', 'The Slow Build' ), 'unapp-fitness' => array( 'fitness', 'Arch Twelve' ), 'unapp-restaurant' => array( 'restaurant', 'Wharf Street' ), 'unapp-agency' => array( 'agency', 'Northgate Studio' ), 'unapp-shop' => array( 'shop', 'Sheaf Works' ), 'unapp-property' => array( 'realestate', 'Lansdown & Co.' ), 'unapp-practice' => array( 'medical', 'Fore Street Dental' ), 'unapp-courses' => array( 'education', 'The Old School' ), 'unapp-events' => array( 'events', 'Longform' ), 'unapp-finance' => array( 'finance', 'North & Vale' ), 'unapp-home-services' => array( 'home-services', 'Northline Property Care' ), 'unapp-cleaning' => array( 'cleaning', 'Clear Day Cleaning' ), 'unapp-salon' => array( 'salon', 'Juniper Hair Studio' ), 'unapp-coach' => array( 'coach', 'Northward Coaching' ), 'unapp-photographer' => array( 'photographer', 'Ellis Rowan' ), 'unapp-nonprofit' => array( 'nonprofit', 'Common Ground Collective' ) );
$url = wp_parse_url( home_url() );
$key = trim( $url['path'] ?? '', '/' );
$backup = getenv( 'UNAPP_DEMO_BACKUP' );
if ( 'colorlibhub.com' !== $url['host'] || ! isset( $map[ $key ] ) || ! $backup || ! is_dir( $backup ) || 'unapp' !== get_stylesheet() ) { throw new RuntimeException( 'Only allowlisted public Unapp demos with a backup directory may be refreshed.' ); }
list( $slug, $name ) = $map[ $key ];
global $wpdb;
$file = $backup . '/' . $key . '.json';
if ( file_exists( $file ) ) { throw new RuntimeException( 'Backup already exists; use a new backup directory.' ); }
$snapshot = array( 'url' => home_url(), 'posts' => $wpdb->get_results( "SELECT * FROM {$wpdb->posts}", ARRAY_A ), 'postmeta' => $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta}", ARRAY_A ), 'options' => $wpdb->get_results( "SELECT * FROM {$wpdb->options}", ARRAY_A ) );
if ( ! file_put_contents( $file, wp_json_encode( $snapshot ) ) ) { throw new RuntimeException( 'Backup failed.' ); }
chmod( $file, 0600 );
add_filter( 'pre_wp_mail', '__return_false' );
$admins = get_super_admins();
$user = get_user_by( 'login', reset( $admins ) );
wp_set_current_user( $user->ID );
$active = get_option( UNAPP_STARTER_OPTION, array() );
if ( ! empty( $active['slug'] ) && $slug !== $active['slug'] ) { throw new RuntimeException( 'Existing starter does not match demo.' ); }
$wpdb->query( 'START TRANSACTION' );
try {
	kses_remove_filters();
	$draft = unapp_prepare_starter_choices( unapp_get_starter_sites()[ $slug ], array() );
	if ( is_wp_error( $draft ) ) { throw new RuntimeException( $draft->get_error_message() ); }
	$site = $draft['site'];
	$created = $active['pages'] ?? array();
	foreach ( array( 'home' => array( 'title' => $site['title'] ) ) + $site['pages'] as $page_key => $page ) {
		$args = array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $page['title'], 'post_content' => wp_slash( $draft['pages'][ $page_key ] ), 'post_author' => $user->ID, 'page_template' => 'page-no-title', 'comment_status' => 'closed', 'ping_status' => 'closed' );
		if ( ! empty( $created[ $page_key ] ) && 'page' === get_post_type( $created[ $page_key ] ) ) { $args['ID'] = $created[ $page_key ]; }
		$id = wp_insert_post( $args, true );
		if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
		$created[ $page_key ] = $id;
	}
	$created['blog'] = ! empty( $created['blog'] ) ? $created['blog'] : unapp_starter_blog_page( $user->ID );
	foreach ( array( 'unapp_apply_starter_styles', 'unapp_build_starter_menu', 'unapp_apply_starter_header', 'unapp_apply_starter_footer' ) as $save ) {
		$result = call_user_func( $save, $site, $created );
		if ( is_wp_error( $result ) ) { throw new RuntimeException( $result->get_error_message() ); }
	}
	update_option( 'blogname', $name );
	update_option( 'blogdescription', '' );
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $created['home'] );
	update_option( 'page_for_posts', $created['blog'] );
	update_option( UNAPP_STARTER_OPTION, array( 'slug' => $slug, 'pages' => $created, 'layout' => 'full', 'profile' => false, 'time' => time(), 'rebuilt' => UNAPP_VERSION, 'menu_hash' => unapp_starter_post_fingerprint( (int) get_option( UNAPP_STARTER_NAV_OPTION ) ) ) );
	update_option( UNAPP_SETUP_OPTION, array( 'home' => $created['home'], 'blog' => $created['blog'], 'version' => UNAPP_VERSION, 'time' => time() ) );
	delete_option( UNAPP_OFFER_OPTION );
	$wpdb->query( 'COMMIT' );
	wp_cache_flush();
	flush_rewrite_rules( false );
	echo wp_json_encode( array( 'url' => home_url(), 'version' => UNAPP_VERSION, 'pages' => $created ) ) . "\n";
} catch ( Throwable $error ) { $wpdb->query( 'ROLLBACK' ); wp_cache_flush(); throw $error; }
