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
	// The parent's version, even when a child theme is active: wp_get_theme()
	// alone returns the child, which would pin every asset URL to the child's
	// version and report the wrong version to the update check.
	define( 'UNAPP_VERSION', wp_get_theme( get_template() )->get( 'Version' ) );
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
 *
 * Always the parent's style.css. get_stylesheet_uri() points at the child
 * theme's file when one is active, which silently dropped the parent's rules
 * (header, alignment, query cards, the price toggle) from every child theme.
 * A child's own style.css is added after it, so its rules still win.
 */
function unapp_enqueue_styles() {
	wp_enqueue_style( 'unapp-style', get_parent_theme_file_uri( 'style.css' ), array(), UNAPP_VERSION );

	if ( is_child_theme() ) {
		wp_enqueue_style(
			'unapp-child-style',
			get_stylesheet_uri(),
			array( 'unapp-style' ),
			wp_get_theme()->get( 'Version' )
		);
	}
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
		'unapp_realestate' => array(
			'label'       => _x( 'Unapp: Property', 'Block pattern category', 'unapp' ),
			'description' => __( 'Sections for estate agents: listings, published fees and valuation enquiries.', 'unapp' ),
		),
		'unapp_medical'  => array(
			'label'       => _x( 'Unapp: Practice', 'Block pattern category', 'unapp' ),
			'description' => __( 'Sections for clinics and practices: treatments, clinicians, patient questions and opening hours.', 'unapp' ),
		),
		'unapp_education' => array(
			'label'       => _x( 'Unapp: Courses', 'Block pattern category', 'unapp' ),
			'description' => __( 'Sections for course providers: timetables, tutors and booking answers.', 'unapp' ),
		),
		'unapp_events'   => array(
			'label'       => _x( 'Unapp: Events', 'Block pattern category', 'unapp' ),
			'description' => __( 'Sections for conferences and events: programmes, speakers, tickets and venue details.', 'unapp' ),
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
		// Built from WooCommerce's product blocks; without WooCommerce it inserts
		// an empty grid of missing-block placeholders.
		'unapp/shop-featured'    => 'woocommerce/product-collection',
		'unapp/shop-featured-h1' => 'woocommerce/product-collection',
	);

	foreach ( $requirements as $pattern => $block ) {
		if ( ! WP_Block_Type_Registry::get_instance()->is_registered( $block )
			&& WP_Block_Patterns_Registry::get_instance()->is_registered( $pattern ) ) {
			unregister_block_pattern( $pattern );
		}
	}
}
// Priority 20 runs after WooCommerce registers its blocks on init.
add_action( 'init', 'unapp_unregister_unsupported_patterns', 20 );

/**
 * Keep the WooCommerce templates out of sight while WooCommerce is inactive.
 *
 * WordPress does not know "archive-product" or "page-cart" without WooCommerce,
 * so it treated the theme's shop templates as custom page templates and offered
 * them by raw slug in the page editor's Template menu.
 *
 * @param WP_Block_Template[] $templates Templates found.
 * @return WP_Block_Template[]
 */
function unapp_hide_woocommerce_templates( $templates ) {
	if ( class_exists( 'WooCommerce' ) ) {
		return $templates;
	}

	$woo = array( 'archive-product', 'single-product', 'page-cart', 'page-checkout', 'order-confirmation', 'product-search-results' );

	return array_values(
		array_filter(
			$templates,
			static function ( $template ) use ( $woo ) {
				return ! ( isset( $template->theme, $template->slug ) && get_stylesheet() === $template->theme && in_array( $template->slug, $woo, true ) );
			}
		)
	);
}
add_filter( 'get_block_templates', 'unapp_hide_woocommerce_templates' );

/**
 * Give the comment form's "Leave a Reply" heading the right level.
 *
 * Core prints it as an h3 straight after the post's h1, which skips a level.
 *
 * @param array $defaults Comment form arguments.
 * @return array
 */
function unapp_comment_form_heading( $defaults ) {
	$defaults['title_reply_before'] = '<h2 id="reply-title" class="comment-reply-title">';
	$defaults['title_reply_after']  = '</h2>';
	return $defaults;
}
add_filter( 'comment_form_defaults', 'unapp_comment_form_heading' );

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
 * Give the theme's own images width and height attributes when they render.
 *
 * core/image's save() never writes them, so a pattern cannot carry them (the
 * block would fail validation). WordPress only adds loading="lazy" and
 * fetchpriority="high" to images that have both, so without them every theme
 * image loaded eagerly and the hero never got priority. The generator already
 * reserves each box with an aspect ratio; this supplies the attributes from the
 * file itself, for images served from this theme's assets/images only.
 *
 * @param string $block_content Rendered block HTML.
 * @return string
 */
function unapp_theme_image_size( $block_content ) {
	if ( false === strpos( $block_content, '/assets/images/' ) ) {
		return $block_content;
	}

	static $sizes = array();

	$base = trailingslashit( get_template_directory_uri() );
	$tags = new WP_HTML_Tag_Processor( $block_content );

	while ( $tags->next_tag( 'img' ) ) {
		$src = (string) $tags->get_attribute( 'src' );

		if ( null !== $tags->get_attribute( 'width' ) || 0 !== strpos( $src, $base . 'assets/images/' ) ) {
			continue;
		}

		$relative = substr( strtok( $src, '?' ), strlen( $base ) );

		if ( ! array_key_exists( $relative, $sizes ) ) {
			$file              = get_parent_theme_file_path( $relative );
			$sizes[ $relative ] = null;

			if ( '.svg' === substr( $file, -4 ) && is_readable( $file ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file, first 2 KB.
				$head = (string) file_get_contents( $file, false, null, 0, 2048 );
				if ( preg_match( '/viewBox="\s*[-\d.]+\s+[-\d.]+\s+([\d.]+)\s+([\d.]+)/', $head, $m ) ) {
					$sizes[ $relative ] = array( (float) $m[1], (float) $m[2] );
				}
			} elseif ( is_readable( $file ) ) {
				$info = wp_getimagesize( $file );
				if ( $info ) {
					$sizes[ $relative ] = array( (float) $info[0], (float) $info[1] );
				}
			}
		}

		$size = $sizes[ $relative ];

		if ( ! $size || ! $size[1] ) {
			continue;
		}

		// Logo rows set only a height; scale the width attribute to match it, or
		// the browser would stretch the logo to the file's full width.
		$style = (string) $tags->get_attribute( 'style' );
		if ( preg_match( '/(?:^|;)\s*height:\s*(\d+)px/', $style, $h ) && false === strpos( $style, 'width:' ) ) {
			$size = array( $size[0] * $h[1] / $size[1], (float) $h[1] );
		}

		$tags->set_attribute( 'width', (string) round( $size[0] ) );
		$tags->set_attribute( 'height', (string) round( $size[1] ) );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/image', 'unapp_theme_image_size' );
add_filter( 'render_block_core/cover', 'unapp_theme_image_size' );

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
require get_parent_theme_file_path( 'inc/front-page-setup.php' );

/**
 * Starter sites: complete designs for different kinds of website.
 */
require get_parent_theme_file_path( 'inc/starter-sites.php' );

/**
 * Contact forms: render whichever form plugin is active, styled to the palette.
 */
require get_parent_theme_file_path( 'inc/forms.php' );

/**
 * Visitor colour-scheme toggle, active only where the toggle pattern is used.
 */
require get_parent_theme_file_path( 'inc/scheme.php' );

/**
 * Setup wizard: name, logo, palette, typeface and the plugins a starter needs.
 */
require get_parent_theme_file_path( 'inc/setup-wizard.php' );

/**
 * Updates for a theme distributed outside WordPress.org.
 */
require get_parent_theme_file_path( 'inc/updates.php' );

/**
 * Load the monthly/yearly price switch only on pages that render one.
 *
 * @param string $content Rendered block content.
 * @param array  $block   Parsed block.
 * @return string
 */
function unapp_period_toggle_script( $content, $block ) {
	if ( isset( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'unapp-period' ) ) {
		wp_enqueue_script(
			'unapp-period-toggle',
			get_theme_file_uri( 'assets/js/period-toggle.js' ),
			array(),
			UNAPP_VERSION,
			true
		);
	}

	return $content;
}
add_filter( 'render_block_core/button', 'unapp_period_toggle_script', 10, 2 );


/**
 * Editor and markup support this theme predates.
 */
if ( ! function_exists( 'unapp_modern_supports' ) ) {
	function unapp_modern_supports() {
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'automatic-feed-links' );
	}
	add_action( 'after_setup_theme', 'unapp_modern_supports', 20 );
}
