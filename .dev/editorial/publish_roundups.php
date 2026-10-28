<?php
/** Publish the prepared editorial batch after a full preflight and private backup. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'https://colorlib.com/wp' !== rtrim( home_url(), '/' ) ) {
	throw new RuntimeException( 'Use WP-CLI on the Colorlib /wp/ site.' );
}
$patches = json_decode( file_get_contents( '/tmp/colorlib-editorial-patches.json' ), true );
$allowed = array( 9446, 10966, 116484, 229751, 12945, 109209, 116669, 3158, 9352, 116741, 13365, 3639, 116713, 230474, 8891, 30174, 15476, 90803, 328812, 15402, 4198, 176118, 2420, 95536, 163549, 106592, 3321, 12439, 15626 );
$backup = '/root/colorlib-editorial-20261028/before';
if ( count( $patches ) !== count( $allowed ) || is_dir( $backup ) ) {
	throw new RuntimeException( 'Unexpected batch size or an existing backup; do not overwrite it.' );
}
foreach ( $patches as $patch ) {
	$id = (int) $patch['id'];
	if ( ! in_array( $id, $allowed, true ) || 'post' !== get_post_type( $id ) || 'publish' !== get_post_status( $id ) || hash( 'sha256', get_post_field( 'post_content', $id ) ) !== $patch['before_sha256'] ) {
		throw new RuntimeException( 'Article changed after preparation: ' . $id );
	}
	if ( count( array_unique( array_column( $patch['products'], 'canonical' ) ) ) !== count( $patch['products'] ) || ! strpos( $patch['post_content'], 'class="wp-block-group colorlib-theme-entry ' ) ) {
		throw new RuntimeException( 'Invalid or duplicate proposed entries: ' . $id );
	}
}
if ( ! mkdir( $backup, 0700, true ) ) { throw new RuntimeException( 'Cannot create private backups.' ); }
foreach ( $patches as $patch ) {
	$id = (int) $patch['id'];
	$file = $backup . '/' . $id . '.json';
	if ( ! file_put_contents( $file, wp_json_encode( array( 'post' => get_post( $id, ARRAY_A ), 'meta' => get_post_meta( $id ) ) ) ) ) {
		throw new RuntimeException( 'Backup failed before publication: ' . $id );
	}
	chmod( $file, 0600 );
}
add_filter( 'pre_wp_mail', '__return_false' );
kses_remove_filters();
foreach ( $patches as $patch ) {
	$id = (int) $patch['id'];
	$result = wp_update_post( array( 'ID' => $id, 'post_title' => wp_slash( $patch['post_title'] ), 'post_content' => wp_slash( $patch['post_content'] ) ), true );
	if ( is_wp_error( $result ) ) { throw new RuntimeException( $result->get_error_message() ); }
	foreach ( $patch['seo'] as $key => $value ) {
		if ( 0 !== strpos( $key, '_yoast_wpseo_' ) ) { throw new RuntimeException( 'Unexpected metadata.' ); }
		update_post_meta( $id, $key, wp_slash( $value ) );
	}
	// Refresh derived SEO metadata after storing its source fields.
	wp_update_post( array( 'ID' => $id ) );
	clean_post_cache( $id );
	if ( get_post_field( 'post_content', $id ) !== $patch['post_content'] || get_post_field( 'post_title', $id ) !== $patch['post_title'] ) {
		throw new RuntimeException( 'Saved content mismatch; inspect the backup: ' . $id );
	}
	echo wp_json_encode( array( 'id' => $id, 'url' => $patch['url'], 'entries' => $patch['total_entries'], 'leading' => array_column( $patch['products'], 'canonical' ) ) ) . "\n";
}
