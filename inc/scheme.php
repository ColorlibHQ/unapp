<?php
/**
 * Visitor colour scheme.
 *
 * A site owner picks the palette; this lets a visitor choose whether to read it
 * light or dark. The neutrals swap to a dark set, and the palette's own primary,
 * secondary and accent are lightened just enough to stay legible on the dark
 * ground — so an Emerald site stays green in the dark rather than becoming a
 * different theme.
 *
 * Nothing is enabled until the site owner adds the toggle pattern, and no
 * script or stylesheet loads on pages without it.
 *
 * @package Unapp
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the current page renders a scheme toggle.
 *
 * @param bool|null $set Set the flag; omit to read it.
 * @return bool
 */
function unapp_has_scheme_toggle( $set = null ) {
	static $has = false;

	if ( true === $set ) {
		$has = true;
	}

	return $has;
}

/**
 * Apply the stored scheme before the first paint.
 *
 * Printed in the head rather than enqueued, because a deferred script would let
 * the page paint in the wrong scheme and then flip.
 */
function unapp_scheme_boot() {
	if ( ! unapp_has_scheme_toggle() ) {
		return;
	}

	// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- must run before paint.
	echo '<script>(function(){try{var s=localStorage.getItem("unapp-scheme");if(s==="dark"||s==="light"){document.documentElement.setAttribute("data-unapp-scheme",s);}}catch(e){}})();</script>' . "\n";
}
add_action( 'wp_head', 'unapp_scheme_boot', 1 );

/**
 * Load the toggle script and the dark tokens only where the toggle is used.
 */
function unapp_scheme_assets() {
	if ( ! unapp_has_scheme_toggle() ) {
		return;
	}

	wp_enqueue_style(
		'unapp-scheme',
		get_theme_file_uri( 'assets/css/scheme.css' ),
		array(),
		UNAPP_VERSION
	);

	wp_enqueue_script(
		'unapp-scheme-toggle',
		get_theme_file_uri( 'assets/js/scheme-toggle.js' ),
		array(),
		UNAPP_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'unapp_scheme_assets', 20 );

/**
 * Mark the page as carrying a toggle when the pattern renders.
 *
 * @param string $content Rendered block content.
 * @param array  $block   Parsed block.
 * @return string
 */
function unapp_scheme_detect( $content, $block ) {
	if ( isset( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'unapp-scheme-toggle' ) ) {
		unapp_has_scheme_toggle( true );
	}

	return $content;
}
add_filter( 'render_block_core/button', 'unapp_scheme_detect', 10, 2 );
