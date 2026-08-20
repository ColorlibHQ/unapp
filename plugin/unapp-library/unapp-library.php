<?php
/**
 * Plugin Name: Unapp Starter Library
 * Description: Ships additional Unapp starter sites as self-contained packs, so a new kind of site can be added without updating the theme. Packs can be bundled with the plugin or fetched from a library endpoint.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * Author: Colorlib
 * Author URI: https://colorlib.com
 * Update URI: https://updates.colorlib.com/plugin/unapp-library.json
 * Text Domain: unapp-library
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Unapp_Library
 */

defined( 'ABSPATH' ) || exit;

const UNAPP_LIBRARY_VERSION = '1.0.0';
const UNAPP_LIBRARY_ENABLED = 'unapp_library_enabled';
const UNAPP_LIBRARY_CACHE   = 'unapp_library_remote';

/**
 * Whether the Unapp theme (or a child of it) is active.
 *
 * The plugin adds to the theme rather than replacing anything, so it stays
 * quiet when the theme is not in use.
 *
 * @return bool
 */
function unapp_library_theme_active() {
	$theme = wp_get_theme();

	return 'unapp' === $theme->get_stylesheet() || 'unapp' === $theme->get_template();
}

/**
 * Every pack the plugin can see: bundled first, then any from the endpoint.
 *
 * A pack is one JSON document describing a starter site — its patterns, its
 * pages, its palette and typeface — so adding a kind of site is a file rather
 * than a theme release.
 *
 * @return array[] Keyed by pack slug.
 */
function unapp_library_packs() {
	static $packs = null;

	if ( null !== $packs ) {
		return $packs;
	}

	$packs = array();

	foreach ( (array) glob( plugin_dir_path( __FILE__ ) . 'packs/*.json' ) as $file ) {
		$pack = unapp_library_read_pack( $file );
		if ( $pack ) {
			$pack['source']        = 'bundled';
			$packs[ $pack['slug'] ] = $pack;
		}
	}

	foreach ( unapp_library_remote_packs() as $pack ) {
		if ( ! isset( $packs[ $pack['slug'] ] ) ) {
			$pack['source']         = 'library';
			$packs[ $pack['slug'] ] = $pack;
		}
	}

	/**
	 * Filters the packs the library offers.
	 *
	 * @param array[] $packs Pack definitions keyed by slug.
	 */
	$packs = apply_filters( 'unapp_library_packs', $packs );

	return $packs;
}

/**
 * Read and validate one pack file.
 *
 * @param string $file Absolute path to a pack JSON file.
 * @return array|null
 */
function unapp_library_read_pack( $file ) {
	if ( ! is_readable( $file ) ) {
		return null;
	}

	$data = wp_json_file_decode( $file, array( 'associative' => true ) );

	return unapp_library_validate_pack( $data );
}

/**
 * Check a pack has the parts the theme needs to build a site from it.
 *
 * @param mixed $data Decoded pack.
 * @return array|null The pack, or null when it is unusable.
 */
function unapp_library_validate_pack( $data ) {
	if ( ! is_array( $data ) ) {
		return null;
	}

	foreach ( array( 'slug', 'title', 'starter', 'patterns' ) as $key ) {
		if ( empty( $data[ $key ] ) ) {
			return null;
		}
	}

	if ( ! is_array( $data['patterns'] ) || ! is_array( $data['starter'] ) ) {
		return null;
	}

	$data['slug'] = sanitize_key( $data['slug'] );

	return $data;
}

/**
 * Packs from the library endpoint, cached for a day.
 *
 * A site with no endpoint configured, or no network, simply has no remote
 * packs — the bundled ones keep working.
 *
 * @return array[]
 */
function unapp_library_remote_packs() {
	/**
	 * Filters the library endpoint. Return an empty string to disable it.
	 *
	 * @param string $url Endpoint returning a JSON array of packs.
	 */
	$url = apply_filters( 'unapp_library_endpoint', '' );

	if ( ! $url ) {
		return array();
	}

	$cached = get_transient( UNAPP_LIBRARY_CACHE );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	$response = wp_remote_get( $url, array( 'timeout' => 8 ) );

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		// Cache the failure briefly so a broken endpoint does not slow every load.
		set_transient( UNAPP_LIBRARY_CACHE, array(), 10 * MINUTE_IN_SECONDS );
		return array();
	}

	$decoded = json_decode( wp_remote_retrieve_body( $response ), true );
	$packs   = array();

	foreach ( (array) $decoded as $pack ) {
		$pack = unapp_library_validate_pack( $pack );
		if ( $pack ) {
			$packs[] = $pack;
		}
	}

	set_transient( UNAPP_LIBRARY_CACHE, $packs, DAY_IN_SECONDS );

	return $packs;
}

/**
 * The packs the site has switched on.
 *
 * @return string[]
 */
function unapp_library_enabled() {
	$enabled = get_option( UNAPP_LIBRARY_ENABLED, array() );

	return is_array( $enabled ) ? $enabled : array();
}

/**
 * Register the patterns belonging to every enabled pack.
 *
 * Patterns are registered at runtime from the pack's own block markup, which
 * is what lets a new kind of site arrive without touching the theme.
 */
function unapp_library_register_patterns() {
	if ( ! unapp_library_theme_active() ) {
		return;
	}

	$enabled = unapp_library_enabled();

	foreach ( unapp_library_packs() as $slug => $pack ) {
		if ( ! in_array( $slug, $enabled, true ) ) {
			continue;
		}

		foreach ( $pack['patterns'] as $pattern ) {
			if ( empty( $pattern['slug'] ) || ! isset( $pattern['content'] ) ) {
				continue;
			}

			// A pack may reuse the theme's own images rather than shipping
			// copies. {{THEME}} stands in for the theme URL, which is only
			// known at runtime.
			$pattern['content'] = str_replace(
				'{{THEME}}',
				untrailingslashit( get_theme_file_uri() ),
				$pattern['content']
			);

			register_block_pattern(
				'unapp-library/' . sanitize_title( $pattern['slug'] ),
				array(
					'title'      => isset( $pattern['title'] ) ? $pattern['title'] : $pattern['slug'],
					'content'    => $pattern['content'],
					'categories' => isset( $pattern['categories'] ) ? (array) $pattern['categories'] : array( 'unapp' ),
					'inserter'   => ! isset( $pattern['inserter'] ) || (bool) $pattern['inserter'],
				)
			);
		}
	}
}
add_action( 'init', 'unapp_library_register_patterns', 11 );

/**
 * Add every enabled pack's starter to the theme's own list.
 *
 * @param array $sites Starter definitions.
 * @return array
 */
function unapp_library_add_starters( $sites ) {
	$enabled = unapp_library_enabled();

	foreach ( unapp_library_packs() as $slug => $pack ) {
		if ( ! in_array( $slug, $enabled, true ) ) {
			continue;
		}

		$starter = $pack['starter'];

		// Thumbnails travel with the pack rather than the theme, so the starter
		// screen is told to use a URL instead of a bundled file.
		if ( ! empty( $pack['thumb_url'] ) ) {
			$starter['thumb_url'] = esc_url_raw( $pack['thumb_url'] );
		} elseif ( 'bundled' === $pack['source'] ) {
			$file = plugin_dir_path( __FILE__ ) . 'packs/' . $slug . '.webp';
			if ( file_exists( $file ) ) {
				$starter['thumb_url'] = plugins_url( 'packs/' . $slug . '.webp', __FILE__ );
			}
		}

		$sites[ $slug ] = $starter;
	}

	return $sites;
}
add_filter( 'unapp_starter_sites', 'unapp_library_add_starters' );

/**
 * Updates, through the same first-class hook the theme uses.
 *
 * A plugin declaring an Update URI header gets update_plugins_{hostname}
 * filtered during the normal check, so releases appear on the Plugins screen
 * exactly like any other update.
 *
 * @param array|false $update      Update data, or false.
 * @param array       $plugin_data Plugin headers.
 * @param string      $plugin_file Plugin file.
 * @return array|false
 */
function unapp_library_check_update( $update, $plugin_data, $plugin_file ) {
	if ( $update || 'unapp-library/unapp-library.php' !== $plugin_file ) {
		return $update;
	}

	$cached = get_site_transient( 'unapp_library_update' );

	if ( ! is_array( $cached ) ) {
		$response = wp_remote_get(
			add_query_arg(
				array( 'plugin' => 'unapp-library', 'version' => UNAPP_LIBRARY_VERSION ),
				'https://updates.colorlib.com/plugin/unapp-library.json'
			),
			array( 'timeout' => 8 )
		);

		$cached = ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) )
			? (array) json_decode( wp_remote_retrieve_body( $response ), true )
			: array();

		set_site_transient( 'unapp_library_update', $cached, $cached ? 12 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );
	}

	if ( empty( $cached['version'] ) || version_compare( $cached['version'], UNAPP_LIBRARY_VERSION, '<=' ) ) {
		return $update;
	}

	return array(
		'id'      => 'https://updates.colorlib.com/plugin/unapp-library.json',
		'slug'    => 'unapp-library',
		'plugin'  => $plugin_file,
		'version' => $cached['version'],
		'url'     => isset( $cached['url'] ) ? $cached['url'] : 'https://colorlib.com/wp/themes/unapp/',
		'package' => isset( $cached['package'] ) ? $cached['package'] : '',
	);
}
add_filter( 'update_plugins_updates.colorlib.com', 'unapp_library_check_update', 10, 3 );

require_once plugin_dir_path( __FILE__ ) . 'admin.php';
