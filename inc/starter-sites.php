<?php
/**
 * Starter sites.
 *
 * Each starter site is a complete look for one kind of website: a colour
 * palette, a typography preset, a home page built from patterns, the supporting
 * pages and a matching menu. Applying one writes the style variation into
 * Global Styles, creates the pages and points Settings → Reading at them.
 *
 * Nothing is ever deleted. Applying a second starter creates a new home page
 * and leaves the previous one in the Pages list.
 *
 * @package Unapp
 * @since   2.3.0
 */

defined( 'ABSPATH' ) || exit;

/** Option holding the slug of the starter site last applied. */
const UNAPP_STARTER_OPTION = 'unapp_active_starter';

/** Transient holding the result of the last apply ("done" | "error"). */
const UNAPP_STARTER_RESULT = 'unapp_starter_result';

/**
 * The starter site definitions.
 *
 * @return array[] Keyed by slug.
 */
function unapp_get_starter_sites() {
	$sites = array(
		'saas'      => array(
			'title'    => __( 'SaaS & app', 'unapp' ),
			'summary'  => __( 'The default: a product landing page with features, proof, pricing and a free-trial call to action.', 'unapp' ),
			'cta'      => _x( 'Get Premium', 'Header call-to-action button', 'unapp' ),
			'style'    => 'indigo',
			'colors'   => 'colors-1-indigo',
			'type'     => 'typography-1-product',
			'swatches' => array( '#5468d8', '#4aca85' ),
			'home'     => 'unapp/demo-saas',
			'thumb'    => 'saas',
			'pages'    => array(
				'features' => array(
					'title'    => __( 'Features', 'unapp' ),
					'patterns' => array( 'unapp/page-features' ),
				),
				'pricing'  => array(
					'title'    => __( 'Pricing', 'unapp' ),
					'patterns' => array( 'unapp/page-pricing-full' ),
				),
				'about'    => array(
					'title'    => __( 'About', 'unapp' ),
					'patterns' => array( 'unapp/page-about' ),
				),
				'contact'  => array(
					'title'    => __( 'Contact', 'unapp' ),
					'patterns' => array( 'unapp/page-contact' ),
				),
			),
		),
		'portfolio' => array(
			'title'    => __( 'Portfolio', 'unapp' ),
			'summary'  => __( 'Work first: a project grid, how you run a job, rates, a client quote and what you are booking.', 'unapp' ),
			'cta'      => __( 'Start a project', 'unapp' ),
			'style'    => 'mono',
			'colors'   => 'colors-10-mono',
			'type'     => 'typography-2-interface',
			'swatches' => array( '#111111', '#e8e6e1' ),
			'home'     => 'unapp/demo-portfolio',
			'thumb'    => 'portfolio',
			'footer'   => 'unapp/footer-portfolio',
			'pages'    => array(
				'work'    => array(
					'title'    => __( 'Work', 'unapp' ),
					'patterns' => array( 'unapp/portfolio-work', 'unapp/portfolio-process', 'unapp/portfolio-testimonial', 'unapp/portfolio-contact' ),
				),
				'about'   => array(
					'title'    => __( 'About', 'unapp' ),
					'patterns' => array( 'unapp/portfolio-about', 'unapp/portfolio-process', 'unapp/portfolio-services', 'unapp/portfolio-testimonial' ),
				),
				'contact' => array(
					'title'    => __( 'Contact', 'unapp' ),
					'patterns' => array( 'unapp/portfolio-contact', 'unapp/portfolio-services' ),
				),
			),
		),
		'church'    => array(
			'title'    => __( 'Church', 'unapp' ),
			'summary'  => __( 'Service times, what to expect on a first visit, ministries, staff, events, giving and directions.', 'unapp' ),
			'cta'      => __( 'Plan your visit', 'unapp' ),
			'style'    => 'stone',
			'colors'   => 'colors-7-stone',
			'type'     => 'typography-3-editorial',
			'swatches' => array( '#7d5f38', '#5cb39a' ),
			'home'     => 'unapp/demo-church',
			'thumb'    => 'church',
			'footer'   => 'unapp/footer-church',
			'pages'    => array(
				'visit'   => array(
					'title'    => __( 'Plan your visit', 'unapp' ),
					'patterns' => array( 'unapp/church-visit', 'unapp/church-times', 'unapp/church-story', 'unapp/church-faq', 'unapp/church-contact' ),
				),
				'about'   => array(
					'title'    => __( 'About us', 'unapp' ),
					'patterns' => array( 'unapp/church-story', 'unapp/church-events', 'unapp/church-beliefs', 'unapp/church-staff', 'unapp/church-cta' ),
				),
				'give'    => array(
					'title'    => __( 'Give', 'unapp' ),
					'patterns' => array( 'unapp/church-giving', 'unapp/church-faq', 'unapp/church-contact' ),
				),
				'contact' => array(
					'title'    => __( 'Contact', 'unapp' ),
					'patterns' => array( 'unapp/church-contact', 'unapp/church-times', 'unapp/church-visit' ),
				),
			),
		),
		'blog'      => array(
			'title'    => __( 'Blog & magazine', 'unapp' ),
			'summary'  => __( 'A masthead, a featured post, category tiles that read your real categories, and a subscribe band.', 'unapp' ),
			'cta'      => __( 'Subscribe', 'unapp' ),
			'style'    => 'sunset',
			'colors'   => 'colors-3-sunset',
			'type'     => 'typography-3-editorial',
			'swatches' => array( '#c9412c', '#f9a826' ),
			'home'     => 'unapp/demo-blog',
			'thumb'    => 'blog',
			'footer'   => 'unapp/footer-blog',
			'pages'    => array(
				'about'   => array(
					'title'    => __( 'About', 'unapp' ),
					'patterns' => array( 'unapp/blog-about', 'unapp/blog-author-intro', 'unapp/blog-subscribe' ),
				),
				'contact' => array(
					'title'    => __( 'Contact', 'unapp' ),
					'patterns' => array( 'unapp/blog-contact', 'unapp/blog-subscribe' ),
				),
			),
		),
		'fitness'   => array(
			'title'    => __( 'Fitness studio', 'unapp' ),
			'summary'  => __( 'A class timetable, coaches, membership prices, member stories and a free first session.', 'unapp' ),
			'cta'      => __( 'Book a class', 'unapp' ),
			'style'    => 'ember',
			'colors'   => 'colors-8-ember',
			'type'     => 'typography-5-geometric',
			'swatches' => array( '#c23b26', '#f2b705' ),
			'home'     => 'unapp/demo-fitness',
			'thumb'    => 'fitness',
			'footer'   => 'unapp/footer-fitness',
			'pages'    => array(
				'timetable'   => array(
					'title'    => __( 'Timetable', 'unapp' ),
					'patterns' => array( 'unapp/fitness-schedule', 'unapp/fitness-faq', 'unapp/fitness-cta' ),
				),
				'memberships' => array(
					'title'    => __( 'Memberships', 'unapp' ),
					'patterns' => array( 'unapp/fitness-memberships', 'unapp/fitness-testimonials', 'unapp/fitness-location', 'unapp/fitness-faq', 'unapp/fitness-cta' ),
				),
				'contact'     => array(
					'title'    => __( 'Contact', 'unapp' ),
					'patterns' => array( 'unapp/fitness-location', 'unapp/fitness-faq', 'unapp/fitness-cta' ),
				),
			),
		),
		'restaurant' => array(
			'title'    => __( 'Restaurant', 'unapp' ),
			'summary'  => __( 'A cover hero, the week’s menu, the kitchen, reviews, opening hours and a booking band.', 'unapp' ),
			'cta'      => __( 'Book a table', 'unapp' ),
			'style'    => 'harvest',
			'colors'   => 'colors-11-harvest',
			'type'     => 'typography-3-editorial',
			'swatches' => array( '#6b6122', '#e2a33c' ),
			'home'     => 'unapp/demo-restaurant',
			'thumb'    => 'restaurant',
			'footer'   => 'unapp/footer-restaurant',
			'pages'    => array(
				'menu'    => array(
					'title'    => __( 'Menu', 'unapp' ),
					'patterns' => array( 'unapp/restaurant-menu', 'unapp/restaurant-kitchen', 'unapp/restaurant-cta' ),
				),
				'about'   => array(
					'title'    => __( 'The kitchen', 'unapp' ),
					'patterns' => array( 'unapp/restaurant-kitchen', 'unapp/restaurant-reviews', 'unapp/restaurant-hours' ),
				),
				'contact' => array(
					'title'    => __( 'Book a table', 'unapp' ),
					'patterns' => array( 'unapp/restaurant-hours', 'unapp/contact-split', 'unapp/restaurant-cta' ),
				),
			),
		),
		'agency'    => array(
			'title'    => __( 'Agency', 'unapp' ),
			'summary'  => __( 'A studio introduction, capabilities, a client list, published rates, the team and an enquiry form.', 'unapp' ),
			'cta'      => __( 'Start a project', 'unapp' ),
			'style'    => 'slate',
			'colors'   => 'colors-12-slate',
			'type'     => 'typography-4-technical',
			'swatches' => array( '#1f4e56', '#57c2b4' ),
			'home'     => 'unapp/demo-agency',
			'thumb'    => 'agency',
			'footer'   => 'unapp/footer-agency',
			'pages'    => array(
				'work'    => array(
					'title'    => __( 'Work', 'unapp' ),
					'patterns' => array( 'unapp/agency-clients', 'unapp/case-study', 'unapp/agency-services', 'unapp/agency-contact' ),
				),
				'studio'  => array(
					'title'    => __( 'Studio', 'unapp' ),
					'patterns' => array( 'unapp/agency-team', 'unapp/agency-services', 'unapp/agency-engagements' ),
				),
				'contact' => array(
					'title'    => __( 'Start a project', 'unapp' ),
					'patterns' => array( 'unapp/agency-contact', 'unapp/agency-engagements' ),
				),
			),
		),
		'shop'      => array(
			'title'    => __( 'Shop', 'unapp' ),
			'summary'  => __( 'A storefront: hero, newest products, the promises that matter, how it is made and delivery answers. Needs WooCommerce.', 'unapp' ),
			'cta'      => __( 'Shop everything', 'unapp' ),
			'style'    => 'emerald',
			'colors'   => 'colors-2-emerald',
			'type'     => 'typography-5-geometric',
			'swatches' => array( '#12805a', '#f7b32b' ),
			'home'     => 'unapp/demo-shop',
			'thumb'    => 'shop',
			'footer'   => 'unapp/footer-shop',
			'requires' => 'woocommerce/woocommerce.php',
			'pages'    => array(
				'about'   => array(
					'title'    => __( 'How it is made', 'unapp' ),
					'patterns' => array( 'unapp/shop-workshop', 'unapp/shop-promise', 'unapp/shop-cta' ),
				),
				'help'    => array(
					'title'    => __( 'Delivery and returns', 'unapp' ),
					'patterns' => array( 'unapp/shop-faq', 'unapp/shop-promise', 'unapp/contact-split' ),
				),
			),
		),
		'realestate' => array(
			'title'    => __( 'Property', 'unapp' ),
			'summary'  => __( 'An estate agency: current listings with prices on them, published fees and a valuation enquiry.', 'unapp' ),
			'cta'      => __( 'Book a valuation', 'unapp' ),
			'style'    => 'graphite',
			'colors'   => 'colors-4-graphite',
			'type'     => 'typography-3-editorial',
			'swatches' => array( '#3d4351', '#22c8b4' ),
			'home'     => 'unapp/demo-realestate',
			'thumb'    => 'realestate',
			'footer'   => 'unapp/footer-realestate',
			'pages'    => array(
				'listings' => array(
					'title'    => __( 'For sale', 'unapp' ),
					'patterns' => array( 'unapp/realestate-listings', 'unapp/realestate-fees', 'unapp/realestate-valuation' ),
				),
				'fees'     => array(
					'title'    => __( 'Our fees', 'unapp' ),
					'patterns' => array( 'unapp/realestate-fees', 'unapp/faq', 'unapp/realestate-valuation' ),
				),
				'contact'  => array(
					'title'    => __( 'Book a valuation', 'unapp' ),
					'patterns' => array( 'unapp/realestate-valuation', 'unapp/realestate-fees' ),
				),
			),
		),
		'medical'   => array(
			'title'    => __( 'Practice', 'unapp' ),
			'summary'  => __( 'A clinic or dental practice: treatments in plain English, clinicians with qualifications, patient questions and opening hours.', 'unapp' ),
			'cta'      => __( 'Book an appointment', 'unapp' ),
			'style'    => 'slate',
			'colors'   => 'colors-12-slate',
			'type'     => 'typography-2-interface',
			'swatches' => array( '#1f4e56', '#57c2b4' ),
			'home'     => 'unapp/demo-medical',
			'thumb'    => 'medical',
			'footer'   => 'unapp/footer-medical',
			'pages'    => array(
				'treatments' => array(
					'title'    => __( 'Treatments', 'unapp' ),
					'patterns' => array( 'unapp/medical-services', 'unapp/medical-team', 'unapp/medical-faq' ),
				),
				'team'       => array(
					'title'    => __( 'The team', 'unapp' ),
					'patterns' => array( 'unapp/medical-team', 'unapp/medical-services', 'unapp/medical-hours' ),
				),
				'contact'    => array(
					'title'    => __( 'Find us', 'unapp' ),
					'patterns' => array( 'unapp/medical-hours', 'unapp/contact-split', 'unapp/medical-faq' ),
				),
			),
		),
		'education' => array(
			'title'    => __( 'Courses', 'unapp' ),
			'summary'  => __( 'A course provider: the term’s timetable with prices, the tutors who teach it and how booking works.', 'unapp' ),
			'cta'      => __( 'See the courses', 'unapp' ),
			'style'    => 'violet',
			'colors'   => 'colors-5-violet',
			'type'     => 'typography-1-product',
			'swatches' => array( '#7c3aed', '#0ea5a5' ),
			'home'     => 'unapp/demo-education',
			'thumb'    => 'education',
			'footer'   => 'unapp/footer-education',
			'pages'    => array(
				'courses' => array(
					'title'    => __( 'Courses', 'unapp' ),
					'patterns' => array( 'unapp/education-courses', 'unapp/education-tutors', 'unapp/education-faq', 'unapp/education-cta' ),
				),
				'tutors'  => array(
					'title'    => __( 'Tutors', 'unapp' ),
					'patterns' => array( 'unapp/education-tutors', 'unapp/education-courses', 'unapp/education-cta' ),
				),
				'contact' => array(
					'title'    => __( 'Contact', 'unapp' ),
					'patterns' => array( 'unapp/contact-split', 'unapp/education-faq' ),
				),
			),
		),
		'events'    => array(
			'title'    => __( 'Conference', 'unapp' ),
			'summary'  => __( 'A two-day event: the programme, announced speakers, three ticket tiers and the venue with its access provision.', 'unapp' ),
			'cta'      => __( 'Buy a ticket', 'unapp' ),
			'style'    => 'midnight',
			'colors'   => 'colors-6-midnight',
			'type'     => 'typography-4-technical',
			'swatches' => array( '#8b9df0', '#4fd391' ),
			'home'     => 'unapp/demo-events',
			'thumb'    => 'events',
			'footer'   => 'unapp/footer-events',
			'pages'    => array(
				'programme' => array(
					'title'    => __( 'Programme', 'unapp' ),
					'patterns' => array( 'unapp/events-programme', 'unapp/events-speakers', 'unapp/events-tickets' ),
				),
				'tickets'   => array(
					'title'    => __( 'Tickets', 'unapp' ),
					'patterns' => array( 'unapp/events-tickets', 'unapp/events-venue', 'unapp/events-programme' ),
				),
				'venue'     => array(
					'title'    => __( 'Venue and access', 'unapp' ),
					'patterns' => array( 'unapp/events-venue', 'unapp/events-programme' ),
				),
			),
		),
		'finance'   => array(
			'title'    => __( 'Finance & advisory', 'unapp' ),
			'summary'  => __( 'Credentials, plain-English services, how advice works, fees, advisers and the risk warning.', 'unapp' ),
			'cta'      => __( 'Book a call', 'unapp' ),
			'style'    => 'navy',
			'colors'   => 'colors-9-navy',
			'type'     => 'typography-3-editorial',
			'swatches' => array( '#1e4272', '#d1a33f' ),
			'home'     => 'unapp/demo-finance',
			'thumb'    => 'finance',
			'footer'   => 'unapp/footer-finance',
			'pages'    => array(
				'services' => array(
					'title'    => __( 'Services', 'unapp' ),
					'patterns' => array( 'unapp/finance-services', 'unapp/finance-process', 'unapp/finance-team', 'unapp/finance-fees', 'unapp/finance-faq', 'unapp/finance-disclaimer' ),
				),
				'about'    => array(
					'title'    => __( 'About', 'unapp' ),
					'patterns' => array( 'unapp/finance-team', 'unapp/finance-credentials', 'unapp/finance-process', 'unapp/finance-disclaimer' ),
				),
				'contact'  => array(
					'title'    => __( 'Contact', 'unapp' ),
					'patterns' => array( 'unapp/finance-contact', 'unapp/finance-process', 'unapp/finance-faq', 'unapp/finance-disclaimer' ),
				),
			),
		),
	);

	/**
	 * Filters the starter sites offered by the theme.
	 *
	 * @since 2.3.0
	 *
	 * @param array[] $sites Starter site definitions keyed by slug.
	 */
	return apply_filters( 'unapp_starter_sites', $sites );
}

/**
 * Apply a starter site.
 *
 * @param string $slug Starter site slug.
 * @return array|WP_Error Array of created page IDs, or WP_Error.
 */
function unapp_apply_starter_site( $slug ) {
	$sites = unapp_get_starter_sites();

	if ( ! isset( $sites[ $slug ] ) ) {
		return new WP_Error( 'unapp_unknown_starter', __( 'That starter site does not exist.', 'unapp' ) );
	}

	$site    = $sites[ $slug ];
	$content = unapp_lock_starter_sections( unapp_get_pattern_markup( $site['home'] ) );

	if ( '' === $content ) {
		return new WP_Error( 'unapp_missing_pattern', __( 'The starter site’s home page pattern could not be loaded.', 'unapp' ) );
	}

	$kses_active = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	if ( $kses_active ) {
		kses_remove_filters();
	}

	$author_id = unapp_starter_author_id();
	$created   = array();

	$home_id = wp_insert_post(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_title'     => $site['title'],
			'post_author'    => $author_id,
			'post_content'   => $content,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'page_template'  => 'page-no-title',
		),
		true
	);

	if ( is_wp_error( $home_id ) ) {
		if ( $kses_active ) {
			kses_init_filters();
		}
		return $home_id;
	}

	$created['home'] = $home_id;

	foreach ( $site['pages'] as $key => $page ) {
		$markup = unapp_get_starter_page_markup( $page );
		if ( '' === $markup ) {
			continue;
		}

		$page_id = wp_insert_post(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_title'     => $page['title'],
				'post_author'    => $author_id,
				'post_content'   => $markup,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'page_template'  => 'page-no-title',
			),
			true
		);

		if ( ! is_wp_error( $page_id ) ) {
			$created[ $key ] = $page_id;
		}
	}

	if ( $kses_active ) {
		kses_init_filters();
	}

	$blog_id = unapp_starter_blog_page( $author_id );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
	if ( $blog_id ) {
		update_option( 'page_for_posts', $blog_id );
		$created['blog'] = $blog_id;
	}

	unapp_apply_starter_styles( $site );
	unapp_build_starter_menu( $site, $created );
	unapp_apply_starter_header( $site );
	unapp_apply_starter_footer( $site );

	update_option( UNAPP_STARTER_OPTION, array( 'slug' => $slug, 'pages' => $created, 'time' => time() ) );
	update_option(
		UNAPP_SETUP_OPTION,
		array( 'home' => $home_id, 'blog' => $blog_id, 'version' => UNAPP_VERSION, 'time' => time() )
	);
	delete_option( UNAPP_OFFER_OPTION );

	return $created;
}

/**
 * Merge two style-variation arrays.
 *
 * Associative arrays merge key by key; lists are replaced outright, because a
 * palette is a list and merging two palettes item by item would interleave
 * them.
 *
 * @param array $base  Base array.
 * @param array $added Array to merge in.
 * @return array
 */
function unapp_merge_variation( $base, $added ) {
	foreach ( $added as $key => $value ) {
		if ( is_array( $value ) && isset( $base[ $key ] ) && is_array( $base[ $key ] )
			&& ! wp_is_numeric_array( $value ) && ! wp_is_numeric_array( $base[ $key ] ) ) {
			$base[ $key ] = unapp_merge_variation( $base[ $key ], $value );
			continue;
		}

		$base[ $key ] = $value;
	}

	return $base;
}

/**
 * Compose a style variation from a colour partial and a typography partial.
 *
 * @param string $colors Colour variation slug, e.g. colors-7-stone.
 * @param string $type   Typography variation slug, e.g. typography-3-editorial.
 * @return array|null Composed variation, or null when either part is missing.
 */
function unapp_compose_variation( $colors, $type ) {
	if ( ! $colors || ! $type ) {
		return null;
	}

	$out = array();

	foreach ( array( 'colors/' . $colors, 'typography/' . $type ) as $relative ) {
		$path = get_theme_file_path( 'styles/' . $relative . '.json' );

		if ( ! file_exists( $path ) ) {
			return null;
		}

		$part = wp_json_file_decode( $path, array( 'associative' => true ) );

		if ( ! is_array( $part ) ) {
			return null;
		}

		unset( $part['$schema'], $part['title'], $part['slug'], $part['version'] );
		$out = unapp_merge_variation( $out, $part );
	}

	return $out;
}

/**
 * Build the content of one starter page.
 *
 * A page is defined either as a single 'pattern' or as a 'patterns' list of
 * section slugs, which are concatenated in order. Missing patterns are skipped
 * so a page still renders on WordPress versions where one of its blocks is
 * unavailable.
 *
 * @param array $page Page definition from the starter registry.
 * @return string Block markup, or an empty string when nothing resolved.
 */
function unapp_get_starter_page_markup( $page ) {
	$slugs = array();

	if ( ! empty( $page['patterns'] ) && is_array( $page['patterns'] ) ) {
		$slugs = $page['patterns'];
	} elseif ( ! empty( $page['pattern'] ) ) {
		$slugs = array( $page['pattern'] );
	}

	$parts = array();

	foreach ( $slugs as $slug ) {
		$markup = unapp_get_pattern_markup( $slug );
		if ( '' !== $markup ) {
			$parts[] = $markup;
		}
	}

	return unapp_lock_starter_sections( implode( "\n\n", $parts ) );
}

/**
 * Lock the structure of a starter page, leaving its text and images editable.
 *
 * A starter page is a stack of finished sections. Without this, opening one in
 * the editor exposes every group, column and spacer, and the usual result is a
 * layout pulled apart by accident. Marking each top-level section
 * 'contentOnly' turns the page into fields to fill in: headings, paragraphs,
 * images and buttons stay editable, the scaffolding does not move.
 *
 * A user who wants the structure back can unlock a section from the block
 * toolbar, so this is a default rather than a restriction.
 *
 * Note: core/cover accepts the attribute but does not pass it to its inner
 * blocks, so a Cover hero stays fully editable. Its children are a heading, a
 * paragraph and buttons — content rather than scaffolding — so little is lost.
 *
 * @param string $markup Serialized block markup.
 * @return string Markup with top-level sections locked.
 */
function unapp_lock_starter_sections( $markup ) {
	$blocks = parse_blocks( $markup );
	$locked = array();

	foreach ( $blocks as $block ) {
		if ( in_array( $block['blockName'], array( 'core/group', 'core/cover', 'core/columns' ), true ) ) {
			$block['attrs']['templateLock'] = 'contentOnly';
		}
		$locked[] = $block;
	}

	return serialize_blocks( $locked );
}

/**
 * Author for starter content: the current user, or the first administrator.
 *
 * @return int User ID, or 0.
 */
function unapp_starter_author_id() {
	$author_id = get_current_user_id();

	if ( ! $author_id ) {
		$admins    = get_users(
			array( 'role' => 'administrator', 'number' => 1, 'orderby' => 'ID', 'order' => 'ASC', 'fields' => 'ID' )
		);
		$author_id = $admins ? (int) $admins[0] : 0;
	}

	return $author_id;
}

/**
 * Find or create the posts page.
 *
 * @param int $author_id Author for a newly created page.
 * @return int Page ID, or 0.
 */
function unapp_starter_blog_page( $author_id ) {
	$blog_id = absint( get_option( 'page_for_posts' ) );
	if ( $blog_id && 'publish' === get_post_status( $blog_id ) ) {
		return $blog_id;
	}

	$existing = get_page_by_path( 'blog' );
	if ( $existing instanceof WP_Post && 'publish' === $existing->post_status ) {
		return $existing->ID;
	}

	$blog_id = wp_insert_post(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_title'     => _x( 'Blog', 'Title of the posts page created on activation', 'unapp' ),
			'post_name'      => 'blog',
			'post_author'    => $author_id,
			'post_content'   => '',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		),
		true
	);

	return is_wp_error( $blog_id ) ? 0 : $blog_id;
}

/**
 * Write the starter's palette and typography into Global Styles.
 *
 * The theme's own style variation is merged into the user's global styles post,
 * which is exactly what choosing it in Appearance → Editor → Styles would do.
 *
 * @param array $site Starter site definition.
 */
function unapp_apply_starter_styles( $site ) {
	// A starter names both a palette and a typeface. Composing the variation
	// from those two partials — rather than loading the curated look that pairs
	// them — is what lets the setup wizard swap either one independently. The
	// curated file remains the fallback for a starter that names only a look.
	$variation = unapp_compose_variation(
		isset( $site['colors'] ) ? $site['colors'] : '',
		isset( $site['type'] ) ? $site['type'] : ''
	);

	if ( ! $variation ) {
		$path = get_theme_file_path( 'styles/' . $site['style'] . '.json' );

		if ( ! file_exists( $path ) ) {
			return;
		}

		$variation = wp_json_file_decode( $path, array( 'associative' => true ) );
	}

	if ( ! is_array( $variation ) ) {
		return;
	}

	unset( $variation['$schema'], $variation['title'], $variation['slug'] );
	$variation['version']                     = WP_Theme_JSON::LATEST_SCHEMA;
	$variation['isGlobalStylesUserThemeJSON'] = true;

	$user_cpt = WP_Theme_JSON_Resolver::get_user_data_from_wp_global_styles( wp_get_theme(), true );

	if ( empty( $user_cpt['ID'] ) ) {
		return;
	}

	// Core finds the global styles post through the `wp_theme` taxonomy, and
	// creates it with `tax_input` — which wp_insert_post() silently discards
	// when there is no logged-in user with permission to assign terms. Under
	// WP-CLI there is no such user, so the term never lands, the next lookup
	// finds nothing and creates *another* post, and the variation is written to
	// one the site never reads. Attaching the term ourselves is idempotent in
	// the browser and is what makes headless provisioning work at all.
	wp_set_object_terms( $user_cpt['ID'], wp_get_theme()->get_stylesheet(), 'wp_theme' );

	wp_update_post(
		array(
			'ID'           => $user_cpt['ID'],
			'post_content' => wp_slash( wp_json_encode( $variation ) ),
		)
	);

	// The resolver caches user data per request; a starter applied and then read
	// back in the same run would otherwise see the old styles.
	WP_Theme_JSON_Resolver::clean_cached_data();
}

/**
 * Build a navigation menu for the starter's pages and make it the site menu.
 *
 * @param array $site    Starter site definition.
 * @param array $created Created page IDs keyed by page key.
 */
function unapp_build_starter_menu( $site, $created ) {
	$items = '';

	foreach ( $site['pages'] as $key => $page ) {
		if ( empty( $created[ $key ] ) ) {
			continue;
		}
		$items .= sprintf(
			'<!-- wp:navigation-link {"label":"%1$s","url":"%2$s","kind":"post-type","type":"page","id":%3$d} /-->' . "\n",
			esc_attr( $page['title'] ),
			esc_url( get_permalink( $created[ $key ] ) ),
			absint( $created[ $key ] )
		);
	}

	if ( ! empty( $created['blog'] ) ) {
		$items .= sprintf(
			'<!-- wp:navigation-link {"label":"%1$s","url":"%2$s","kind":"post-type","type":"page","id":%3$d} /-->' . "\n",
			esc_attr__( 'Blog', 'unapp' ),
			esc_url( get_permalink( $created['blog'] ) ),
			absint( $created['blog'] )
		);
	}

	if ( '' === $items ) {
		return;
	}

	$kses_active = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	if ( $kses_active ) {
		kses_remove_filters();
	}

	$existing = get_posts(
		array(
			'post_type'      => 'wp_navigation',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	$args = array(
		'post_type'    => 'wp_navigation',
		'post_status'  => 'publish',
		'post_title'   => __( 'Navigation', 'unapp' ),
		'post_content' => wp_slash( trim( $items ) ),
	);

	if ( $existing ) {
		$args['ID'] = $existing[0]->ID;
		wp_update_post( $args );
	} else {
		wp_insert_post( $args );
	}

	if ( $kses_active ) {
		kses_init_filters();
	}
}

/**
 * A template part saved into the database by a starter site, if any.
 *
 * @param string $name Part slug: 'header' or 'footer'.
 * @return WP_Post|null
 */
function unapp_get_customised_part( $name ) {
	$parts = get_posts(
		array(
			'post_type'      => 'wp_template_part',
			'post_status'    => 'publish',
			'name'           => $name,
			'posts_per_page' => 1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'wp_theme',
					'field'    => 'name',
					'terms'    => get_stylesheet(),
				),
			),
		)
	);

	return $parts ? $parts[0] : null;
}

/**
 * The customised header template part saved by a starter site, if any.
 *
 * @return WP_Post|null
 */
function unapp_get_customised_header() {
	return unapp_get_customised_part( 'header' );
}

/**
 * Point a template part at one of the theme's own patterns.
 *
 * Starters swap the footer this way: the saved part is a single pattern
 * reference, exactly what parts/footer.html contains, so the wording stays
 * translatable and the user can still edit it in the Site Editor afterwards.
 *
 * @param string $name    Part slug: 'header' or 'footer'.
 * @param string $title   Part title.
 * @param string $pattern Pattern slug to reference, or '' to restore the theme file.
 */
function unapp_set_part_to_pattern( $name, $title, $pattern ) {
	$existing = unapp_get_customised_part( $name );

	if ( '' === $pattern ) {
		if ( $existing ) {
			wp_delete_post( $existing->ID, true );
		}
		return;
	}

	if ( ! WP_Block_Patterns_Registry::get_instance()->is_registered( $pattern ) ) {
		return;
	}

	$args = array(
		'post_type'    => 'wp_template_part',
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_name'    => $name,
		'post_content' => wp_slash( '<!-- wp:pattern {"slug":"' . $pattern . '"} /-->' ),
	);

	if ( $existing ) {
		$args['ID'] = $existing->ID;
		$part_id    = wp_update_post( $args );
	} else {
		$part_id = wp_insert_post( $args );
	}

	if ( $part_id && ! is_wp_error( $part_id ) ) {
		wp_set_object_terms( $part_id, get_stylesheet(), 'wp_theme' );
		wp_set_object_terms( $part_id, $name, 'wp_template_part_area' );
	}
}

/**
 * Give the footer the starter's own wording, links and contact details.
 *
 * @param array $site Starter site definition.
 */
function unapp_apply_starter_footer( $site ) {
	$pattern = isset( $site['footer'] ) ? $site['footer'] : '';
	unapp_set_part_to_pattern( 'footer', __( 'Footer', 'unapp' ), $pattern );
}

/**
 * Give the header the starter's own call-to-action label.
 *
 * The header pattern ships with a SaaS label, which reads oddly on a church or
 * a gym. This saves a customised copy of the header template part with the
 * starter's wording — the same thing the user would get by editing the header
 * in the Site Editor.
 *
 * @param array $site Starter site definition.
 */
function unapp_apply_starter_header( $site ) {
	$default  = _x( 'Get Premium', 'Header call-to-action button', 'unapp' );
	$existing = unapp_get_customised_header();

	// A starter that uses the theme's own wording needs no customised part. Remove
	// any left over from a previous starter so the header file takes over again.
	if ( empty( $site['cta'] ) || $site['cta'] === $default ) {
		if ( $existing ) {
			wp_delete_post( $existing->ID, true );
		}
		return;
	}

	$markup = unapp_get_pattern_markup( 'unapp/header' );
	if ( '' === $markup || false === strpos( $markup, $default ) ) {
		return;
	}

	$markup = str_replace( '>' . $default . '<', '>' . $site['cta'] . '<', $markup );

	$kses_active = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	if ( $kses_active ) {
		kses_remove_filters();
	}

	$args = array(
		'post_type'    => 'wp_template_part',
		'post_status'  => 'publish',
		'post_title'   => __( 'Header', 'unapp' ),
		'post_name'    => 'header',
		'post_content' => wp_slash( $markup ),
	);

	if ( $existing ) {
		$args['ID'] = $existing->ID;
		$part_id    = wp_update_post( $args );
	} else {
		$part_id = wp_insert_post( $args );
	}

	if ( $kses_active ) {
		kses_init_filters();
	}

	if ( $part_id && ! is_wp_error( $part_id ) ) {
		wp_set_object_terms( $part_id, get_stylesheet(), 'wp_theme' );
		wp_set_object_terms( $part_id, 'header', 'wp_template_part_area' );
	}
}

/**
 * Register the starter sites screen under Appearance.
 */
function unapp_starter_menu() {
	add_theme_page(
		__( 'Starter Sites', 'unapp' ),
		__( 'Starter Sites', 'unapp' ),
		'edit_theme_options',
		'unapp-starter-sites',
		'unapp_render_starter_screen'
	);
}
add_action( 'admin_menu', 'unapp_starter_menu' );

/**
 * Handle the apply request.
 */
function unapp_handle_starter_request() {
	check_admin_referer( 'unapp_apply_starter' );

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to change the site design.', 'unapp' ), 403 );
	}

	$slug   = isset( $_POST['starter'] ) ? sanitize_key( wp_unslash( $_POST['starter'] ) ) : '';
	$result = unapp_apply_starter_site( $slug );

	set_transient( UNAPP_STARTER_RESULT, is_wp_error( $result ) ? 'error' : 'done', HOUR_IN_SECONDS );
	wp_safe_redirect( admin_url( 'themes.php?page=unapp-starter-sites' ) );
	exit;
}
add_action( 'admin_post_unapp_apply_starter', 'unapp_handle_starter_request' );

/**
 * Render the starter sites screen.
 */
function unapp_render_starter_screen() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only navigation between wizard steps.
	$step    = isset( $_GET['step'] ) ? sanitize_key( wp_unslash( $_GET['step'] ) ) : '';
	$starter = isset( $_GET['starter'] ) ? sanitize_key( wp_unslash( $_GET['starter'] ) ) : '';
	$sites   = unapp_get_starter_sites();

	if ( 'done' === $step ) {
		echo '<div class="wrap unapp-starters">';
		unapp_wizard_render_done();
		unapp_starter_styles();
		echo '</div>';
		return;
	}

	if ( isset( $sites[ $starter ] ) && in_array( $step, array( 'brand', 'plugins' ), true ) ) {
		echo '<div class="wrap unapp-starters">';
		echo '<h1>' . esc_html__( 'Set up your site', 'unapp' ) . '</h1>';
		unapp_wizard_steps( $step );

		if ( 'brand' === $step ) {
			unapp_wizard_render_brand( $starter );
		} else {
			unapp_wizard_render_plugins(
				$starter,
				array(
					'title'   => isset( $_GET['site_title'] ) ? sanitize_text_field( wp_unslash( $_GET['site_title'] ) ) : '',
					'tagline' => isset( $_GET['tagline'] ) ? sanitize_text_field( wp_unslash( $_GET['tagline'] ) ) : '',
					'logo'    => isset( $_GET['logo_id'] ) ? absint( $_GET['logo_id'] ) : 0,
					'colors'  => isset( $_GET['colors'] ) ? sanitize_key( wp_unslash( $_GET['colors'] ) ) : '',
					'type'    => isset( $_GET['typography'] ) ? sanitize_key( wp_unslash( $_GET['typography'] ) ) : '',
				)
			);
		}

		unapp_starter_styles();
		echo '</div>';
		return;
	}
	// phpcs:enable WordPress.Security.NonceVerification.Recommended

	$sites  = unapp_get_starter_sites();
	$active = get_option( UNAPP_STARTER_OPTION, array() );
	$result = get_transient( UNAPP_STARTER_RESULT );

	if ( $result ) {
		delete_transient( UNAPP_STARTER_RESULT );
	}
	?>
	<div class="wrap unapp-starters">
		<h1><?php esc_html_e( 'Unapp starter sites', 'unapp' ); ?></h1>
		<p class="unapp-starters__intro">
			<?php esc_html_e( 'Each starter builds a complete site for one kind of business: a colour palette, a typeface, a home page and its supporting pages, and a matching menu. Setting one up takes three short steps — the starter, your name and look, then anything it needs installing. Your existing pages are never deleted.', 'unapp' ); ?>
		</p>

		<?php if ( 'done' === $result ) : ?>
			<div class="notice notice-success"><p>
				<?php
				printf(
					/* translators: %s: link to the site front page. */
					wp_kses_post( __( 'Starter site applied. <a href="%s">View your site</a>.', 'unapp' ) ),
					esc_url( home_url( '/' ) )
				);
				?>
			</p></div>
		<?php elseif ( 'error' === $result ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'That starter site could not be applied. Please try again.', 'unapp' ); ?></p></div>
		<?php endif; ?>

		<div class="unapp-starters__grid">
			<?php foreach ( $sites as $slug => $site ) : ?>
				<?php $is_active = isset( $active['slug'] ) && $active['slug'] === $slug; ?>
				<div class="unapp-starter<?php echo $is_active ? ' is-active' : ''; ?>">
					<div class="unapp-starter__preview"
						style="background:linear-gradient(135deg, <?php echo esc_attr( $site['swatches'][0] ); ?> 0%, <?php echo esc_attr( $site['swatches'][1] ); ?> 100%)">
						<?php
						// A starter added by a plugin carries its own thumbnail URL;
						// the theme's own starters name a bundled file.
						$unapp_thumb = '';
						if ( ! empty( $site['thumb_url'] ) ) {
							$unapp_thumb = $site['thumb_url'];
						} elseif ( ! empty( $site['thumb'] ) && file_exists( get_theme_file_path( 'assets/images/starters/' . $site['thumb'] . '.webp' ) ) ) {
							$unapp_thumb = get_theme_file_uri( 'assets/images/starters/' . $site['thumb'] . '.webp' );
						}
						?>
						<?php if ( $unapp_thumb ) : ?>
							<img src="<?php echo esc_url( $unapp_thumb ); ?>"
								alt="<?php
								/* translators: %s: starter site name. */
								echo esc_attr( sprintf( __( 'The home page the %s starter builds', 'unapp' ), $site['title'] ) );
								?>" loading="lazy" decoding="async" width="640" height="480">
						<?php else : ?>
							<span class="unapp-starter__preview-card" aria-hidden="true"></span>
						<?php endif; ?>
					</div>
					<div class="unapp-starter__body">
						<h2><?php echo esc_html( $site['title'] ); ?><?php echo $is_active ? ' <span class="unapp-starter__badge">' . esc_html__( 'Applied', 'unapp' ) . '</span>' : ''; ?></h2>
						<p class="unapp-starter__swatches" aria-hidden="true">
							<?php foreach ( $site['swatches'] as $unapp_swatch ) : ?>
								<span style="background:<?php echo esc_attr( $unapp_swatch ); ?>"></span>
							<?php endforeach; ?>
						</p>
						<p><?php echo esc_html( $site['summary'] ); ?></p>
						<?php $unapp_page_count = count( $site['pages'] ) + 1; ?>
						<p class="unapp-starter__meta">
							<?php
							printf(
								/* translators: %d: number of pages the starter creates. */
								esc_html( _n( 'Creates %d page', 'Creates %d pages', $unapp_page_count, 'unapp' ) ),
								absint( $unapp_page_count )
							);
							?>
						</p>
						<div class="unapp-starter__buttons">
							<a class="button button-primary" href="<?php echo esc_url( add_query_arg( array( 'page' => 'unapp-starter-sites', 'step' => 'brand', 'starter' => $slug ), admin_url( 'themes.php' ) ) ); ?>">
								<?php echo $is_active ? esc_html__( 'Set up again', 'unapp' ) : esc_html__( 'Set up this starter', 'unapp' ); ?>
							</a>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
								<?php wp_nonce_field( 'unapp_apply_starter' ); ?>
								<input type="hidden" name="action" value="unapp_apply_starter">
								<input type="hidden" name="starter" value="<?php echo esc_attr( $slug ); ?>">
								<button type="submit" class="button-link unapp-starter__skip">
									<?php esc_html_e( 'Apply without setup', 'unapp' ); ?>
								</button>
							</form>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	unapp_starter_styles();
}

/**
 * The step indicator shown above every wizard step.
 *
 * @param string $current Current step key.
 */
function unapp_wizard_steps( $current ) {
	$steps = array(
		'choose'  => __( 'Choose a starter', 'unapp' ),
		'brand'   => __( 'Name and look', 'unapp' ),
		'plugins' => __( 'Finish', 'unapp' ),
	);
	$keys  = array_keys( $steps );
	$at    = array_search( $current, $keys, true );
	$at    = false === $at ? 0 : $at;
	$n     = 0;

	echo '<ol class="unapp-steps">';
	foreach ( $steps as $key => $label ) {
		$state = $n === $at ? ' is-current' : ( $n < $at ? ' is-done' : '' );
		printf(
			'<li class="unapp-step%s"><span class="unapp-step__n">%d</span>%s</li>',
			esc_attr( $state ),
			absint( $n + 1 ),
			esc_html( $label )
		);
		++$n;
	}
	echo '</ol>';
}

/**
 * Styles for the starter screen and the wizard.
 */
function unapp_starter_styles() {
	?>
	<style>
		.unapp-starters__intro { max-width: 70ch; }
		.unapp-starters__grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 24px; }
		.unapp-starter { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; }
		.unapp-starter.is-active { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; }
		.unapp-starter__preview { height: 190px; display: flex; align-items: flex-end; justify-content: center; padding: 0 24px; overflow: hidden; }
		.unapp-starter__preview img { display: block; width: 100%; height: 100%; object-fit: cover; object-position: top center; padding: 0; }
		.unapp-starter__swatches { display: flex; gap: 4px; margin: 0 0 2px !important; }
		.unapp-starter__swatches span { width: 13px; height: 13px; border-radius: 50%; box-shadow: inset 0 0 0 1px rgba(0,0,0,.12); }
		.unapp-starter__preview-card { display: block; width: 100%; height: 62px; background: #fff; border-radius: 8px 8px 0 0; box-shadow: 0 -6px 18px rgba(0,0,0,.12); }
		.unapp-starter__body { padding: 16px 20px 20px; display: flex; flex-direction: column; gap: 8px; flex: 1; }
		.unapp-starter__body h2 { font-size: 15px; margin: 0; }
		.unapp-starter__badge { display: inline-block; margin-inline-start: 6px; padding: 1px 8px; border-radius: 999px; background: #2271b1; color: #fff; font-size: 11px; vertical-align: middle; }
		.unapp-starter__body p { margin: 0; color: #50575e; font-size: 13px; }
		.unapp-starter__meta { color: #787c82 !important; font-size: 12px !important; }
		.unapp-starter form { margin-top: 0; padding-top: 0; }
		.unapp-starter__buttons { margin-top: auto; padding-top: 10px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
		.unapp-starter__skip { color: #787c82 !important; font-size: 12px; text-decoration: underline; }
		.unapp-steps { display: flex; flex-wrap: wrap; gap: 8px 28px; list-style: none; margin: 20px 0 26px; padding: 0; }
		.unapp-step { display: flex; align-items: center; gap: 8px; color: #787c82; font-size: 13px; }
		.unapp-step__n { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #dcdcde; color: #50575e; font-size: 12px; font-weight: 600; }
		.unapp-step.is-current { color: #1d2327; font-weight: 600; }
		.unapp-step.is-current .unapp-step__n { background: #2271b1; color: #fff; }
		.unapp-step.is-done .unapp-step__n { background: #00a32a; color: #fff; }
		.unapp-wizard { max-width: 820px; }
		.unapp-wizard h2 { margin-top: 0; font-size: 1.5rem; }
		.unapp-wizard__lede { max-width: 68ch; color: #50575e; font-size: 14px; }
		.unapp-wizard__hint { color: #787c82; font-size: 13px; margin-top: -6px; }
		.unapp-wizard__panel { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 18px 20px; margin: 18px 0; }
		.unapp-wizard__panel h3 { margin-top: 0; font-size: 14px; }
		.unapp-field { display: block; margin-bottom: 14px; }
		.unapp-field label, .unapp-field__label { display: block; font-weight: 600; font-size: 13px; margin-bottom: 4px; }
		.unapp-field input[type="text"] { width: 100%; max-width: 420px; }
		.unapp-logo { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
		.unapp-logo__preview { max-width: 140px; max-height: 60px; border: 1px solid #dcdcde; border-radius: 6px; padding: 4px; background: #fff; }
		.unapp-swatches { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 10px; }
		.unapp-swatches--type { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); }
		.unapp-swatch { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid #dcdcde; border-radius: 8px; cursor: pointer; font-size: 13px; background: #fff; }
		.unapp-swatch:has(input:checked) { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; }
		.unapp-swatch input { margin: 0; }
		.unapp-swatch__dots { display: inline-flex; }
		.unapp-swatch__dots span { width: 15px; height: 15px; border-radius: 50%; box-shadow: inset 0 0 0 1px rgba(0,0,0,.12); }
		.unapp-swatch__dots span + span { margin-left: -5px; }
		.unapp-plugin { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 12px 0; border-bottom: 1px solid #f0f0f1; }
		.unapp-plugin:last-child { border-bottom: 0; }
		.unapp-plugin p { margin: 4px 0 0; color: #50575e; font-size: 13px; max-width: 60ch; }
		.unapp-wizard__summary { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 18px 20px; margin: 18px 0; }
		.unapp-wizard__summary h3 { margin-top: 0; font-size: 14px; }
		.unapp-wizard__summary ul { margin: 10px 0 0 18px; color: #50575e; font-size: 13px; list-style: disc; }
		.unapp-wizard__actions { display: flex; align-items: center; gap: 10px; margin-top: 22px; }
		.unapp-wizard--done { text-align: left; }
	</style>
	<?php
}
