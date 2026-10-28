<?php
/** Publish the reviewed article-format correction, keeping a private rollback copy. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'https://colorlib.com/wp' !== rtrim( home_url(), '/' ) ) {
	throw new RuntimeException( 'Use WP-CLI on the Colorlib /wp/ site.' );
}
$patches = json_decode( file_get_contents( '/tmp/colorlib-consistency-patches.json' ), true );
$assets = json_decode( file_get_contents( '/tmp/colorlib-consistency-assets.json' ), true );
$expected = array( 9446, 10966, 116484, 229751, 12945, 109209, 116669, 3158, 9352, 116741, 13365, 3639, 116713, 230474, 8891, 30174, 15476, 90803, 328812, 15402, 4198, 176118, 2420, 95536, 163549, 106592, 3321, 12439, 15626 );
$backup = '/root/colorlib-consistency-20261028/before';
$theme = get_stylesheet_directory();
if ( is_dir( $backup ) || 'v3ct' !== get_stylesheet() || array_column( $patches, 'id' ) !== $expected || array_keys( $assets ) !== array( 'style', 'functions' ) ) {
	throw new RuntimeException( 'Unexpected batch, theme or existing backup.' );
}
foreach ( $patches as $patch ) {
	$id = (int) $patch['id'];
	if ( 'publish' !== get_post_status( $id ) || 'post' !== get_post_type( $id ) || hash( 'sha256', get_post_field( 'post_content', $id ) ) !== $patch['before_sha256'] || get_post_field( 'post_title', $id ) !== $patch['post_title'] || false !== strpos( $patch['post_content'], 'colorlib-editorial-actions' ) || substr_count( $patch['post_content'], 'class="wp-block-group colorlib-theme-entry ' ) !== count( $patch['products'] ) ) {
		throw new RuntimeException( 'Article preflight failed: ' . $id );
	}
}
foreach ( $assets as $name => $asset ) {
	$target = 'style' === $name ? 'style.css' : 'functions.php';
	if ( $asset['target'] !== $target || hash_file( 'sha256', $theme . '/' . $target ) !== $asset['sha256'] || ! is_file( $asset['source'] ) ) {
		throw new RuntimeException( 'Shared asset preflight failed: ' . $name );
	}
}
if ( ! mkdir( $backup, 0700, true ) ) { throw new RuntimeException( 'Cannot create private backups.' ); }
foreach ( $patches as $patch ) {
	$id = (int) $patch['id'];
	$file = $backup . '/' . $id . '.json';
	if ( ! file_put_contents( $file, wp_json_encode( array( 'post' => get_post( $id, ARRAY_A ), 'meta' => get_post_meta( $id ) ) ) ) ) { throw new RuntimeException( 'Backup failed.' ); }
	chmod( $file, 0600 );
}
foreach ( $assets as $asset ) {
	$file = $backup . '/' . $asset['target'];
	if ( ! copy( $theme . '/' . $asset['target'], $file ) ) { throw new RuntimeException( 'Asset backup failed.' ); }
	chmod( $file, 0600 );
}
// Shared assets first: the old entries remain styled until their replacements are saved.
foreach ( $assets as $asset ) {
	$target = $theme . '/' . $asset['target'];
	if ( ! copy( $asset['source'], $target ) || hash_file( 'sha256', $target ) !== hash_file( 'sha256', $asset['source'] ) ) { throw new RuntimeException( 'Asset write failed.' ); }
	if ( function_exists( 'opcache_invalidate' ) ) { opcache_invalidate( $target, true ); }
}
add_filter( 'pre_wp_mail', '__return_false' );
kses_remove_filters();
foreach ( $patches as $patch ) {
	$id = (int) $patch['id'];
	$result = wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( $patch['post_content'] ) ), true );
	if ( is_wp_error( $result ) || get_post_field( 'post_content', $id ) !== $patch['post_content'] ) { throw new RuntimeException( 'Saved article mismatch: ' . $id ); }
	clean_post_cache( $id );
	echo wp_json_encode( array( 'id' => $id, 'format' => $patch['format'], 'url' => $patch['url'] ) ) . "\n";
}
