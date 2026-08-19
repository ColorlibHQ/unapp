<?php
/**
 * Front page setup.
 *
 * Creates a real "Home" page (content = the "Home landing page" pattern,
 * template "Page (No Title)") and a "Blog" posts page, then points
 * Settings → Reading at them. Runs automatically right after activation when
 * the site has no static front page yet; otherwise an admin notice offers a
 * one-click setup so an existing front page is never overridden silently.
 *
 * Disable the automatic run with:
 *   add_filter( 'unapp_auto_setup_front_page', '__return_false' );
 *
 * @package Unapp
 * @since   2.0.0
 */

defined( 'ABSPATH' ) || exit;

/** Option holding the IDs of the pages this theme created. */
const UNAPP_SETUP_OPTION = 'unapp_front_page_setup';

/** Option flag: offer the one-click setup notice (site already had a front page). */
const UNAPP_OFFER_OPTION = 'unapp_offer_front_page_setup';

/** Transient: result notice to show once after a setup run ("done" | "error"). */
const UNAPP_RESULT_TRANSIENT = 'unapp_front_page_setup_result';

/**
 * Return the fully expanded block markup of a registered pattern.
 *
 * Nested `<!-- wp:pattern {"slug":"…"} /-->` references are resolved
 * recursively so the result can be stored as ordinary post content.
 *
 * @param string $slug  Pattern slug, e.g. `unapp/page-home`.
 * @param int    $depth Recursion guard.
 * @return string Block markup, or empty string when the pattern is unknown.
 */
function unapp_get_pattern_markup( $slug, $depth = 0 ) {
	if ( $depth > 5 ) {
		return '';
	}

	$registry = WP_Block_Patterns_Registry::get_instance();
	if ( ! $registry->is_registered( $slug ) ) {
		return '';
	}

	$pattern = $registry->get_registered( $slug );
	$content = isset( $pattern['content'] ) ? $pattern['content'] : '';

	return preg_replace_callback(
		'/<!--\s+wp:pattern\s+(\{.*?\})\s+\/-->/',
		static function ( $matches ) use ( $depth ) {
			$attrs = json_decode( $matches[1], true );
			if ( empty( $attrs['slug'] ) ) {
				return '';
			}
			return unapp_get_pattern_markup( $attrs['slug'], $depth + 1 );
		},
		$content
	);
}

/**
 * Create the Home and Blog pages (when missing) and assign them in Settings → Reading.
 *
 * Idempotent: pages recorded in the option (or an existing posts page) are reused.
 *
 * @return array|WP_Error Array with `home` and `blog` page IDs, or WP_Error.
 */
function unapp_setup_front_page() {
	$state = get_option( UNAPP_SETUP_OPTION, array() );

	// Pages need an author even when this runs without a logged-in user (WP-CLI, cron):
	// fall back to the first administrator.
	$author_id = get_current_user_id();
	if ( ! $author_id ) {
		$admins    = get_users(
			array(
				'role'    => 'administrator',
				'number'  => 1,
				'orderby' => 'ID',
				'order'   => 'ASC',
				'fields'  => 'ID',
			)
		);
		$author_id = $admins ? (int) $admins[0] : 0;
	}

	// The theme's own markup is trusted; keep kses from mangling block markup when
	// this runs without a logged-in user (WP-CLI, cron).
	$kses_active = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	if ( $kses_active ) {
		kses_remove_filters();
	}

	// 1. Home page.
	$home_id = isset( $state['home'] ) ? absint( $state['home'] ) : 0;
	if ( ! $home_id || 'page' !== get_post_type( $home_id ) || 'publish' !== get_post_status( $home_id ) ) {
		$content = unapp_get_pattern_markup( 'unapp/page-home' );
		if ( '' === $content ) {
			if ( $kses_active ) {
				kses_init_filters();
			}
			return new WP_Error( 'unapp_missing_pattern', __( 'The "Home landing page" pattern could not be loaded.', 'unapp' ) );
		}

		$home_id = wp_insert_post(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_title'     => _x( 'Home', 'Title of the front page created on activation', 'unapp' ),
				'post_name'      => 'home',
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
	}

	// 2. Blog (posts) page — reuse whatever the site already uses.
	$blog_id = absint( get_option( 'page_for_posts' ) );
	if ( ! $blog_id || 'publish' !== get_post_status( $blog_id ) ) {
		$blog_id = isset( $state['blog'] ) ? absint( $state['blog'] ) : 0;
	}
	if ( ! $blog_id || 'publish' !== get_post_status( $blog_id ) ) {
		$existing = get_page_by_path( 'blog' );
		$blog_id  = ( $existing instanceof WP_Post && 'publish' === $existing->post_status ) ? $existing->ID : 0;
	}
	if ( ! $blog_id ) {
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
		if ( is_wp_error( $blog_id ) ) {
			if ( $kses_active ) {
				kses_init_filters();
			}
			return $blog_id;
		}
	}

	if ( $kses_active ) {
		kses_init_filters();
	}

	// 3. Reading settings.
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
	update_option( 'page_for_posts', $blog_id );

	update_option(
		UNAPP_SETUP_OPTION,
		array(
			'home'    => $home_id,
			'blog'    => $blog_id,
			'version' => UNAPP_VERSION,
			'time'    => time(),
		)
	);
	delete_option( UNAPP_OFFER_OPTION );

	return array(
		'home' => $home_id,
		'blog' => $blog_id,
	);
}

/**
 * On activation decide between automatic setup and offering it.
 *
 * `after_switch_theme` itself fires from check_theme_switched() on `init` (99), so the
 * work is done on `init` (100) — patterns are registered by then and the result
 * notice appears on the very request that activated the theme.
 */
function unapp_after_switch_theme() {
	if ( get_option( UNAPP_SETUP_OPTION ) ) {
		return; // Already set up once on this site; never touch it again.
	}

	$has_static_front = 'page' === get_option( 'show_on_front' ) && absint( get_option( 'page_on_front' ) ) > 0;

	if ( ! $has_static_front && apply_filters( 'unapp_auto_setup_front_page', true ) ) {
		update_option( 'unapp_pending_front_page_setup', 1 );
		return;
	}

	update_option( UNAPP_OFFER_OPTION, 1 );
}
add_action( 'after_switch_theme', 'unapp_after_switch_theme' );

/**
 * Run a pending automatic setup once patterns are available.
 */
function unapp_run_pending_setup() {
	if ( ! get_option( 'unapp_pending_front_page_setup' ) ) {
		return;
	}
	delete_option( 'unapp_pending_front_page_setup' );

	if ( get_option( UNAPP_SETUP_OPTION ) ) {
		return;
	}

	$result = unapp_setup_front_page();
	set_transient( UNAPP_RESULT_TRANSIENT, is_wp_error( $result ) ? 'error' : 'done', HOUR_IN_SECONDS );
}
add_action( 'init', 'unapp_run_pending_setup', 100 );

/**
 * Forget the pending/offer state when the theme is switched away.
 */
function unapp_switch_theme_cleanup() {
	delete_option( 'unapp_pending_front_page_setup' );
	delete_option( UNAPP_OFFER_OPTION );
	delete_transient( UNAPP_RESULT_TRANSIENT );
}
add_action( 'switch_theme', 'unapp_switch_theme_cleanup' );

/**
 * Handle the "Set up front page" button (admin-post).
 */
function unapp_handle_setup_request() {
	check_admin_referer( 'unapp_setup_front_page' );
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to change the front page.', 'unapp' ), 403 );
	}

	$result = unapp_setup_front_page();
	set_transient( UNAPP_RESULT_TRANSIENT, is_wp_error( $result ) ? 'error' : 'done', HOUR_IN_SECONDS );

	wp_safe_redirect( admin_url( 'themes.php' ) );
	exit;
}
add_action( 'admin_post_unapp_setup_front_page', 'unapp_handle_setup_request' );

/**
 * Handle the "Not now" link (admin-post).
 */
function unapp_handle_dismiss_request() {
	check_admin_referer( 'unapp_dismiss_front_page_setup' );
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to change the front page.', 'unapp' ), 403 );
	}

	delete_option( UNAPP_OFFER_OPTION );

	$back = wp_get_referer();
	wp_safe_redirect( $back ? $back : admin_url( 'themes.php' ) );
	exit;
}
add_action( 'admin_post_unapp_dismiss_front_page_setup', 'unapp_handle_dismiss_request' );

/**
 * Admin notices: setup result, or the offer to set up.
 */
function unapp_front_page_notices() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$screen  = get_current_screen();
	$allowed = array( 'dashboard', 'themes', 'options-reading', 'edit-page' );
	if ( ! $screen || ! in_array( $screen->id, $allowed, true ) ) {
		return;
	}

	$result = get_transient( UNAPP_RESULT_TRANSIENT );
	if ( $result ) {
		delete_transient( UNAPP_RESULT_TRANSIENT );
		$state = get_option( UNAPP_SETUP_OPTION, array() );

		if ( 'done' === $result && ! empty( $state['home'] ) ) {
			printf(
				'<div class="notice notice-success is-dismissible"><p><strong>%1$s</strong> %2$s</p><p><a class="button button-primary" href="%3$s">%4$s</a> <a class="button" href="%5$s">%6$s</a> <a class="button" href="%7$s">%8$s</a></p></div>',
				esc_html__( 'Unapp is ready.', 'unapp' ),
				esc_html__( 'A "Home" page with the Unapp landing page and a "Blog" page were created and assigned in Settings → Reading. Everything on the front page is editable as normal page content.', 'unapp' ),
				esc_url( home_url( '/' ) ),
				esc_html__( 'View front page', 'unapp' ),
				esc_url( get_edit_post_link( absint( $state['home'] ) ) ),
				esc_html__( 'Edit front page', 'unapp' ),
				esc_url( admin_url( 'site-editor.php' ) ),
				esc_html__( 'Open Site Editor', 'unapp' )
			);
		} else {
			printf(
				'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
				esc_html__( 'Unapp could not create the front page automatically. Create a page, insert the "Home landing page" pattern and choose it under Settings → Reading.', 'unapp' )
			);
		}
		return;
	}

	if ( ! get_option( UNAPP_OFFER_OPTION ) ) {
		return;
	}

	$front_id    = absint( get_option( 'page_on_front' ) );
	$front_title = $front_id ? get_the_title( $front_id ) : '';
	$setup_url   = wp_nonce_url( admin_url( 'admin-post.php?action=unapp_setup_front_page' ), 'unapp_setup_front_page' );
	$dismiss_url = wp_nonce_url( admin_url( 'admin-post.php?action=unapp_dismiss_front_page_setup' ), 'unapp_dismiss_front_page_setup' );

	printf(
		'<div class="notice notice-info"><p><strong>%1$s</strong> %2$s</p><p><a class="button button-primary" href="%7$s">%8$s</a> <a class="button" href="%3$s">%4$s</a> <a class="button" href="%5$s">%6$s</a></p></div>',
		esc_html__( 'Thanks for choosing Unapp!', 'unapp' ),
		$front_title
			/* translators: %s: title of the current front page. */
			? sprintf( esc_html__( 'Your site already uses "%s" as its front page, so it was left untouched. Unapp can create a "Home" page with its landing-page design and make it the front page — you can switch back any time under Settings → Reading.', 'unapp' ), esc_html( $front_title ) )
			: esc_html__( 'Unapp can create a "Home" page with its landing-page design and a "Blog" page, and set them under Settings → Reading.', 'unapp' ),
		esc_url( $setup_url ),
		esc_html__( 'Set up the Unapp front page', 'unapp' ),
		esc_url( $dismiss_url ),
		esc_html__( 'Not now', 'unapp' ),
		esc_url( admin_url( 'themes.php?page=unapp-starter-sites' ) ),
		esc_html__( 'Choose a starter site', 'unapp' )
	);
}
add_action( 'admin_notices', 'unapp_front_page_notices' );
