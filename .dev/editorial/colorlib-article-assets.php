<?php
/** Version Colorlib's shared child-theme CSS when the file changes. */
function colorlib_version_shared_stylesheet() {
	global $wp_styles;
	$path = get_stylesheet_directory() . '/style.css';
	if ( isset( $wp_styles->registered['wpex-style'] ) && is_file( $path ) ) {
		$wp_styles->registered['wpex-style']->ver = (string) filemtime( $path );
	}
}
add_action( 'wp_enqueue_scripts', 'colorlib_version_shared_stylesheet', 100 );
