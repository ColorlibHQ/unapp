<?php
/** Export the public starter/pattern catalogue from the actual theme definitions. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { throw new RuntimeException( 'Use WP-CLI eval-file.' ); }
$root = dirname( __DIR__ );
$out = getenv( 'UNAPP_CATALOG_OUTPUT' );
if ( ! $out ) { throw new RuntimeException( 'Set UNAPP_CATALOG_OUTPUT.' ); }
global $wpdb;
$wpdb->query( 'START TRANSACTION' );
try {
	add_filter( 'pre_option_template', static function () { return 'unapp'; } );
	add_filter( 'pre_option_stylesheet', static function () { return 'unapp'; } );
	add_filter( 'theme_root', static function () use ( $root ) { return dirname( $root ); } );
	register_theme_directory( dirname( $root ) );
	wp_clean_themes_cache( false );
	require $root . '/functions.php';
	$aliases = array( 'saas' => '', 'realestate' => 'property', 'medical' => 'practice', 'education' => 'courses' );
	$data = array( 'version' => UNAPP_VERSION, 'starters' => array(), 'patterns' => array() );
	foreach ( unapp_get_starter_sites() as $slug => $site ) {
		$demo = 'unapp' . ( 'saas' === $slug ? '' : '-' . ( $aliases[ $slug ] ?? $slug ) );
		$data['starters'][] = array( 'slug' => $slug, 'title' => $site['title'], 'summary' => $site['summary'], 'cta' => $site['cta'], 'style' => preg_replace( '/^colors-\d+-/', '', $site['colors'] ?? ( $site['style'] ?? '' ) ), 'swatches' => $site['swatches'], 'pages' => count( $site['pages'] ) + 1, 'pagelist' => array_column( $site['pages'], 'title' ), 'demo' => 'https://colorlibhub.com/' . $demo . '/', 'shot' => $demo, 'categories' => $site['categories'], 'keywords' => $site['keywords'], 'requires' => empty( $site['requires'] ) ? '' : 'WooCommerce' );
	}
	foreach ( glob( $root . '/patterns/*.php' ) as $file ) {
		$head = get_file_data( $file, array( 'title' => 'Title', 'slug' => 'Slug', 'categories' => 'Categories', 'inserter' => 'Inserter' ) );
		if ( 'no' === $head['inserter'] ) { continue; }
		$category = explode( ',', $head['categories'] )[0];
		$data['patterns'][] = array( 'slug' => $head['slug'], 'title' => $head['title'], 'group' => ucwords( str_replace( array( 'unapp_', '-' ), array( '', ' ' ), $category ) ) );
	}
	file_put_contents( $out, wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "\n" );
	echo 'Exported ' . count( $data['starters'] ) . ' starters and ' . count( $data['patterns'] ) . " patterns.\n";
} finally { $wpdb->query( 'ROLLBACK' ); wp_cache_flush(); }
