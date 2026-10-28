<?php
/** Remove reviewed duplicate CSS declarations; retain identical shared rules. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'https://colorlib.com/wp' !== rtrim( home_url(), '/' ) || 'v3ct' !== get_stylesheet() ) {
	throw new RuntimeException( 'Use WP-CLI on the Colorlib /wp/ site.' );
}
$patches = json_decode( file_get_contents( '/tmp/colorlib-shared-css-patches.json' ), true );
$expected = array( 218193, 226945, 226758, 43518, 112630, 226136, 43413, 43724, 144033, 227134, 144037, 116810, 108214, 229267, 116911, 13390, 219246, 34763, 3965, 37874, 116824, 228736, 139554 );
$backup = '/root/colorlib-shared-css-20261028/before';
$target = get_stylesheet_directory() . '/style.css';
if ( is_dir( $backup ) || array_column( $patches, 'id' ) !== $expected || hash_file( 'sha256', $target ) !== hash_file( 'sha256', '/tmp/colorlib-v3ct-after.css' ) ) {
	throw new RuntimeException( 'Unexpected batch, shared stylesheet or existing backup.' );
}
foreach ( $patches as $patch ) {
	$id = (int) $patch['id'];
	if ( 'publish' !== get_post_status( $id ) || 'post' !== get_post_type( $id ) || hash( 'sha256', get_post_field( 'post_content', $id ) ) !== $patch['before_sha256'] ) {
		throw new RuntimeException( 'Article preflight failed: ' . $id );
	}
}
if ( ! mkdir( $backup, 0700, true ) ) { throw new RuntimeException( 'Cannot create backup.' ); }
foreach ( $patches as $patch ) {
	$id = (int) $patch['id'];
	$file = $backup . '/' . $id . '.json';
	if ( ! file_put_contents( $file, wp_json_encode( array( 'post' => get_post( $id, ARRAY_A ), 'meta' => get_post_meta( $id ) ) ) ) ) { throw new RuntimeException( 'Backup failed.' ); }
	chmod( $file, 0600 );
}
if ( ! copy( $target, $backup . '/style.css' ) ) { throw new RuntimeException( 'Stylesheet backup failed.' ); }
chmod( $backup . '/style.css', 0600 );
if ( ! copy( '/tmp/colorlib-v3ct-centralized.css', $target ) || hash_file( 'sha256', $target ) !== hash_file( 'sha256', '/tmp/colorlib-v3ct-centralized.css' ) ) { throw new RuntimeException( 'Shared stylesheet write failed.' ); }
add_filter( 'pre_wp_mail', '__return_false' );
kses_remove_filters();
foreach ( $patches as $patch ) {
	$id = (int) $patch['id'];
	$result = wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( $patch['post_content'] ) ), true );
	if ( is_wp_error( $result ) || get_post_field( 'post_content', $id ) !== $patch['post_content'] ) { throw new RuntimeException( 'Saved article mismatch: ' . $id ); }
	clean_post_cache( $id );
	echo wp_json_encode( array( 'id' => $id, 'url' => $patch['url'] ) ) . "\n";
}
