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
	$styles = array(
		'core/list'      => array(
			'stylesheet' => 'list-styles',
			'variations' => array(
				'checklist' => _x( 'Checklist', 'Block style label', 'unapp' ),
				'dash'      => _x( 'Dashed', 'Block style label', 'unapp' ),
				'steps'     => _x( 'Numbered steps', 'Block style label', 'unapp' ),
				'two-col'   => _x( 'Two columns', 'Block style label', 'unapp' ),
			),
		),
		'core/image'     => array(
			'stylesheet' => 'image-styles',
			'variations' => array(
				'device'  => _x( 'Device frame', 'Block style label', 'unapp' ),
				'browser' => _x( 'Browser frame', 'Block style label', 'unapp' ),
				'framed'  => _x( 'Framed', 'Block style label', 'unapp' ),
			),
		),
		'core/quote'     => array(
			'stylesheet' => 'quote-styles',
			'variations' => array(
				'testimonial' => _x( 'Testimonial card', 'Block style label', 'unapp' ),
			),
		),
		'core/details'   => array(
			'stylesheet' => 'details-styles',
			'variations' => array(
				'faq-card' => _x( 'FAQ card', 'Block style label', 'unapp' ),
			),
		),
		'core/table'     => array(
			'stylesheet' => 'table-styles',
			'variations' => array(
				'compare' => _x( 'Comparison', 'Block style label', 'unapp' ),
			),
		),
		'core/separator' => array(
			'stylesheet' => 'separator-styles',
			'variations' => array(
				'gradient' => _x( 'Gradient line', 'Block style label', 'unapp' ),
			),
		),
		'core/button'    => array(
			'stylesheet' => 'button-styles',
			'variations' => array(
				'arrow' => _x( 'Text link with arrow', 'Block style label', 'unapp' ),
			),
		),
		'core/columns'   => array(
			'stylesheet' => 'columns-styles',
			'variations' => array(
				'divided' => _x( 'Divided', 'Block style label', 'unapp' ),
			),
		),
	);

	foreach ( $styles as $block => $config ) {
		foreach ( $config['variations'] as $name => $label ) {
			register_block_style(
				$block,
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}

		$handle = 'unapp-' . $config['stylesheet'];
		wp_enqueue_block_style(
			$block,
			array(
				'handle' => $handle,
				'src'    => get_theme_file_uri( 'assets/css/' . $config['stylesheet'] . '.css' ),
				'path'   => get_theme_file_path( 'assets/css/' . $config['stylesheet'] . '.css' ),
				'ver'    => UNAPP_VERSION,
			)
		);
	}
}
add_action( 'init', 'unapp_block_styles' );

/**
 * Register pattern categories used by the theme's patterns.
 *
 * `unapp` holds every section so the whole library can be browsed in one place;
 * the rest narrow it down by job.
 */
function unapp_pattern_categories() {
	$categories = array(
		'unapp'          => array(
			'label'       => _x( 'Unapp', 'Block pattern category', 'unapp' ),
			'description' => __( 'Every Unapp section: heroes, features, pricing, social proof, company and calls to action.', 'unapp' ),
		),
		'unapp_hero'     => array(
			'label'       => _x( 'Unapp: Heroes', 'Block pattern category', 'unapp' ),
			'description' => __( 'Opening sections for a landing page.', 'unapp' ),
		),
		'unapp_features' => array(
			'label'       => _x( 'Unapp: Features', 'Block pattern category', 'unapp' ),
			'description' => __( 'Ways to explain what the product does.', 'unapp' ),
		),
		'unapp_proof'    => array(
			'label'       => _x( 'Unapp: Social proof', 'Block pattern category', 'unapp' ),
			'description' => __( 'Testimonials, logos, ratings and customer stories.', 'unapp' ),
		),
		'unapp_pricing'  => array(
			'label'       => _x( 'Unapp: Pricing', 'Block pattern category', 'unapp' ),
			'description' => __( 'Plan tables and comparisons.', 'unapp' ),
		),
		'unapp_cta'      => array(
			'label'       => _x( 'Unapp: Calls to action', 'Block pattern category', 'unapp' ),
			'description' => __( 'Conversion sections: sign-up bands, newsletters and app downloads.', 'unapp' ),
		),
		'unapp_content'  => array(
			'label'       => _x( 'Unapp: Content & blog', 'Block pattern category', 'unapp' ),
			'description' => __( 'Post loops, author boxes, changelogs and documentation.', 'unapp' ),
		),
		'unapp_company'  => array(
			'label'       => _x( 'Unapp: Company', 'Block pattern category', 'unapp' ),
			'description' => __( 'About, team, values, careers, offices and press.', 'unapp' ),
		),
		'unapp_utility'  => array(
			'label'       => _x( 'Unapp: Utility', 'Block pattern category', 'unapp' ),
			'description' => __( 'Contact, FAQ, legal and help-centre sections.', 'unapp' ),
		),
		'unapp_page'     => array(
			'label'       => _x( 'Unapp: Full pages', 'Block pattern category', 'unapp' ),
			'description' => __( 'Complete page layouts. Insert one into an empty page to start from a finished design.', 'unapp' ),
		),
	);

	foreach ( $categories as $slug => $args ) {
		register_block_pattern_category( $slug, $args );
	}
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
