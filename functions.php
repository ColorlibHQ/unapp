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

	// WooCommerce renders through its own block templates in a block theme; declaring
	// support keeps its product gallery features available and silences the
	// "theme does not declare support" notice.
	add_theme_support( 'woocommerce' );

	// Post formats, so format-specific styling and patterns work on blogs.
	add_theme_support(
		'post-formats',
		array( 'aside', 'audio', 'gallery', 'image', 'link', 'quote', 'status', 'video' )
	);
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
 * WooCommerce compatibility styles, loaded only when WooCommerce is active.
 */
function unapp_woocommerce_styles() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	wp_enqueue_style(
		'unapp-woocommerce',
		get_theme_file_uri( 'assets/css/woocommerce.css' ),
		array( 'unapp-style' ),
		UNAPP_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'unapp_woocommerce_styles', 20 );

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
		'unapp_portfolio' => array(
			'label'       => _x( 'Unapp: Portfolio', 'Block pattern category', 'unapp' ),
			'description' => __( 'Sections for designers, studios and freelancers: work grids, rates and process.', 'unapp' ),
		),
		'unapp_church'   => array(
			'label'       => _x( 'Unapp: Church', 'Block pattern category', 'unapp' ),
			'description' => __( 'Sections for a church site: service times, ministries, staff, giving and first-visit answers.', 'unapp' ),
		),
		'unapp_fitness'  => array(
			'label'       => _x( 'Unapp: Fitness', 'Block pattern category', 'unapp' ),
			'description' => __( 'Sections for gyms and studios: timetables, coaches, memberships and member stories.', 'unapp' ),
		),
		'unapp_finance'  => array(
			'label'       => _x( 'Unapp: Finance', 'Block pattern category', 'unapp' ),
			'description' => __( 'Sections for advisers and accountants: services, process, fees, credentials and risk warnings.', 'unapp' ),
		),
		'unapp_restaurant' => array(
			'label'       => _x( 'Unapp: Restaurant', 'Block pattern category', 'unapp' ),
			'description' => __( 'Sections for restaurants and cafés: menus, opening hours, the kitchen and reviews.', 'unapp' ),
		),
		'unapp_agency'   => array(
			'label'       => _x( 'Unapp: Agency', 'Block pattern category', 'unapp' ),
			'description' => __( 'Sections for studios and agencies: capabilities, clients, engagements and enquiries.', 'unapp' ),
		),
		'unapp_shop'     => array(
			'label'       => _x( 'Unapp: Shop', 'Block pattern category', 'unapp' ),
			'description' => __( 'Storefront sections for WooCommerce: featured products, promises, the workshop and delivery answers.', 'unapp' ),
		),
		'unapp_blog'     => array(
			'label'       => _x( 'Unapp: Blog & magazine', 'Block pattern category', 'unapp' ),
			'description' => __( 'Editorial furniture: mastheads, category tiles, author introductions and subscribe panels.', 'unapp' ),
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
 * Hide patterns that depend on blocks the current WordPress does not have.
 *
 * Unapp supports WordPress 6.6 upwards, but some patterns are built on blocks
 * that only arrived in 7.0 (Accordion, Breadcrumbs, Query Total, Time to Read).
 * On older versions those patterns are unregistered rather than shown broken,
 * and the templates fall back to the equivalents that have always existed.
 */
function unapp_unregister_unsupported_patterns() {
	$requirements = array(
		'unapp/faq-accordion' => 'core/accordion',
	);

	foreach ( $requirements as $pattern => $block ) {
		if ( ! WP_Block_Type_Registry::get_instance()->is_registered( $block )
			&& WP_Block_Patterns_Registry::get_instance()->is_registered( $pattern ) ) {
			unregister_block_pattern( $pattern );
		}
	}
}
add_action( 'init', 'unapp_unregister_unsupported_patterns', 20 );

/**
 * Strip blocks that need a newer WordPress out of templates and patterns.
 *
 * `render_block` returns an empty string for an unregistered block, so the only
 * thing needed is to make sure nothing else breaks around it. Breadcrumbs,
 * Query Total and Time to Read all degrade to nothing, which is the intended
 * behaviour on 6.6–6.9.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block.
 * @return string Block HTML, or an empty string when the block is unavailable.
 */
function unapp_skip_unsupported_blocks( $block_content, $block ) {
	$optional = array( 'core/breadcrumbs', 'core/query-total', 'core/post-time-to-read', 'core/accordion' );

	if ( in_array( $block['blockName'] ?? '', $optional, true )
		&& ! WP_Block_Type_Registry::get_instance()->is_registered( $block['blockName'] ) ) {
		return '';
	}

	return $block_content;
}
add_filter( 'render_block', 'unapp_skip_unsupported_blocks', 10, 2 );

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

/**
 * Starter sites: complete designs for different kinds of website.
 */
require get_theme_file_path( 'inc/starter-sites.php' );

/**
 * Contact forms: render whichever form plugin is active, styled to the palette.
 */
require get_theme_file_path( 'inc/forms.php' );
