<?php
/**
 * Unapp functions and definitions.
 *
 * Unapp is a block theme: layout, colors, typography and spacing live in
 * theme.json, templates in /templates and /parts, and section content in
 * /patterns. This file only wires up the few things theme.json cannot express.
 *
 * @package Unapp
 * @since   2.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'UNAPP_VERSION' ) ) {
	define( 'UNAPP_VERSION', wp_get_theme()->get( 'Version' ) );
}

/**
 * Theme setup.
 *
 * Block themes get post-thumbnails, responsive-embeds, editor-styles, html5,
 * automatic-feed-links, block-templates and wide alignment automatically, so
 * only the extras are declared here.
 */
function unapp_setup() {
	load_theme_textdomain( 'unapp', get_template_directory() . '/languages' );

	// Load the small stylesheet inside the editor too, so cards/thumbnails match the front end.
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'unapp_setup' );

/**
 * Enqueue the front-end stylesheet.
 */
function unapp_enqueue_styles() {
	wp_enqueue_style( 'unapp-style', get_stylesheet_uri(), array(), UNAPP_VERSION );
}
add_action( 'wp_enqueue_scripts', 'unapp_enqueue_styles' );

/**
 * Register block styles and their per-block stylesheets.
 *
 * Styles registered with wp_enqueue_block_style() are only loaded (and inlined,
 * because a `path` is given) on pages that actually render that block.
 */
function unapp_block_styles() {
	register_block_style(
		'core/list',
		array(
			'name'  => 'checklist',
			'label' => _x( 'Checklist', 'Block style label', 'unapp' ),
		)
	);
	wp_enqueue_block_style(
		'core/list',
		array(
			'handle' => 'unapp-list-checklist',
			'src'    => get_theme_file_uri( 'assets/css/list-checklist.css' ),
			'path'   => get_theme_file_path( 'assets/css/list-checklist.css' ),
			'ver'    => UNAPP_VERSION,
		)
	);

	register_block_style(
		'core/image',
		array(
			'name'  => 'device',
			'label' => _x( 'Device frame', 'Block style label', 'unapp' ),
		)
	);
	wp_enqueue_block_style(
		'core/image',
		array(
			'handle' => 'unapp-image-device',
			'src'    => get_theme_file_uri( 'assets/css/image-device.css' ),
			'path'   => get_theme_file_path( 'assets/css/image-device.css' ),
			'ver'    => UNAPP_VERSION,
		)
	);
}
add_action( 'init', 'unapp_block_styles' );

/**
 * Register pattern categories used by the theme's patterns.
 */
function unapp_pattern_categories() {
	register_block_pattern_category(
		'unapp',
		array(
			'label'       => _x( 'Unapp', 'Block pattern category', 'unapp' ),
			'description' => __( 'Landing page sections designed for Unapp: hero, features, pricing, team, stats and more.', 'unapp' ),
		)
	);
	register_block_pattern_category(
		'unapp_page',
		array(
			'label'       => _x( 'Pages', 'Block pattern category', 'unapp' ),
			'description' => __( 'Full page layouts. Insert one into an empty page to start from a complete design.', 'unapp' ),
		)
	);
}
add_action( 'init', 'unapp_pattern_categories' );

/**
 * Lazily enqueue the stat counter script.
 *
 * The Stats pattern marks its numbers with the `unapp-count` class. The script
 * is only loaded on requests that actually render such a paragraph, so plain
 * blog pages ship zero JavaScript from the theme.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block.
 * @return string Unchanged block HTML.
 */
function unapp_maybe_enqueue_counter( $block_content, $block ) {
	if ( isset( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'unapp-count' ) ) {
		wp_enqueue_script(
			'unapp-counter',
			get_theme_file_uri( 'assets/js/counter.js' ),
			array(),
			UNAPP_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
	return $block_content;
}
add_filter( 'render_block_core/paragraph', 'unapp_maybe_enqueue_counter', 10, 2 );

/**
 * Front page setup (Home + Blog pages, Settings → Reading) on activation.
 */
require get_theme_file_path( 'inc/front-page-setup.php' );
