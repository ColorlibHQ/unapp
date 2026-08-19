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
			'summary'  => __( 'The default: a product landing page with pricing, proof and a free-trial call to action.', 'unapp' ),
			'cta'      => _x( 'Get Premium', 'Header call-to-action button', 'unapp' ),
			'style'    => 'indigo',
			'colors'   => 'colors-1-indigo',
			'type'     => 'typography-1-product',
			'swatches' => array( '#5468d8', '#4aca85' ),
			'home'     => 'unapp/demo-saas',
			'pages'    => array(
				'features' => array( 'title' => __( 'Features', 'unapp' ), 'pattern' => 'unapp/page-features' ),
				'pricing'  => array( 'title' => __( 'Pricing', 'unapp' ), 'pattern' => 'unapp/page-pricing-full' ),
				'about'    => array( 'title' => __( 'About', 'unapp' ), 'pattern' => 'unapp/page-about' ),
				'contact'  => array( 'title' => __( 'Contact', 'unapp' ), 'pattern' => 'unapp/page-contact' ),
			),
		),
		'portfolio' => array(
			'title'    => __( 'Portfolio', 'unapp' ),
			'summary'  => __( 'A designer or studio portfolio: work grid, about, services and rates.', 'unapp' ),
			'cta'      => __( 'Start a project', 'unapp' ),
			'style'    => 'mono',
			'colors'   => 'colors-10-mono',
			'type'     => 'typography-2-interface',
			'swatches' => array( '#111111', '#e8e6e1' ),
			'home'     => 'unapp/demo-portfolio',
			'pages'    => array(
				'work'    => array( 'title' => __( 'Work', 'unapp' ), 'pattern' => 'unapp/portfolio-work' ),
				'about'   => array( 'title' => __( 'About', 'unapp' ), 'pattern' => 'unapp/page-about' ),
				'contact' => array( 'title' => __( 'Contact', 'unapp' ), 'pattern' => 'unapp/page-contact' ),
			),
		),
		'church'    => array(
			'title'    => __( 'Church', 'unapp' ),
			'summary'  => __( 'A welcoming church site: service times, ministries, staff, giving and directions.', 'unapp' ),
			'cta'      => __( 'Plan your visit', 'unapp' ),
			'style'    => 'stone',
			'colors'   => 'colors-7-stone',
			'type'     => 'typography-3-editorial',
			'swatches' => array( '#7d5f38', '#5cb39a' ),
			'home'     => 'unapp/demo-church',
			'pages'    => array(
				'visit'   => array( 'title' => __( 'Plan your visit', 'unapp' ), 'pattern' => 'unapp/church-times' ),
				'about'   => array( 'title' => __( 'About us', 'unapp' ), 'pattern' => 'unapp/page-about' ),
				'give'    => array( 'title' => __( 'Give', 'unapp' ), 'pattern' => 'unapp/church-giving' ),
				'contact' => array( 'title' => __( 'Contact', 'unapp' ), 'pattern' => 'unapp/page-contact' ),
			),
		),
		'blog'      => array(
			'title'    => __( 'Blog & magazine', 'unapp' ),
			'summary'  => __( 'A writer’s publication: masthead, featured post, topics and a newsletter.', 'unapp' ),
			'cta'      => __( 'Subscribe', 'unapp' ),
			'style'    => 'sunset',
			'colors'   => 'colors-3-sunset',
			'type'     => 'typography-3-editorial',
			'swatches' => array( '#c9412c', '#f9a826' ),
			'home'     => 'unapp/demo-blog',
			'pages'    => array(
				'about'   => array( 'title' => __( 'About', 'unapp' ), 'pattern' => 'unapp/page-about' ),
				'contact' => array( 'title' => __( 'Contact', 'unapp' ), 'pattern' => 'unapp/page-contact' ),
			),
		),
		'fitness'   => array(
			'title'    => __( 'Fitness studio', 'unapp' ),
			'summary'  => __( 'A gym or studio: timetable, coaches, memberships and a free-trial band.', 'unapp' ),
			'cta'      => __( 'Book a class', 'unapp' ),
			'style'    => 'ember',
			'colors'   => 'colors-8-ember',
			'type'     => 'typography-5-geometric',
			'swatches' => array( '#c23b26', '#f2b705' ),
			'home'     => 'unapp/demo-fitness',
			'pages'    => array(
				'timetable'   => array( 'title' => __( 'Timetable', 'unapp' ), 'pattern' => 'unapp/fitness-schedule' ),
				'memberships' => array( 'title' => __( 'Memberships', 'unapp' ), 'pattern' => 'unapp/page-pricing' ),
				'contact'     => array( 'title' => __( 'Contact', 'unapp' ), 'pattern' => 'unapp/page-contact' ),
			),
		),
		'finance'   => array(
			'title'    => __( 'Finance & advisory', 'unapp' ),
			'summary'  => __( 'A professional practice: credentials, services, process, advisers and a risk warning.', 'unapp' ),
			'cta'      => __( 'Book a call', 'unapp' ),
			'style'    => 'navy',
			'colors'   => 'colors-9-navy',
			'type'     => 'typography-3-editorial',
			'swatches' => array( '#1e4272', '#d1a33f' ),
			'home'     => 'unapp/demo-finance',
			'pages'    => array(
				'services' => array( 'title' => __( 'Services', 'unapp' ), 'pattern' => 'unapp/finance-services' ),
				'about'    => array( 'title' => __( 'About', 'unapp' ), 'pattern' => 'unapp/page-about' ),
				'contact'  => array( 'title' => __( 'Contact', 'unapp' ), 'pattern' => 'unapp/page-contact' ),
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
	$content = unapp_get_pattern_markup( $site['home'] );

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
		$markup = unapp_get_pattern_markup( $page['pattern'] );
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

	update_option( UNAPP_STARTER_OPTION, array( 'slug' => $slug, 'pages' => $created, 'time' => time() ) );
	update_option(
		UNAPP_SETUP_OPTION,
		array( 'home' => $home_id, 'blog' => $blog_id, 'version' => UNAPP_VERSION, 'time' => time() )
	);
	delete_option( UNAPP_OFFER_OPTION );

	return $created;
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
	$path = get_theme_file_path( 'styles/' . $site['style'] . '.json' );

	if ( ! file_exists( $path ) ) {
		return;
	}

	$variation = wp_json_file_decode( $path, array( 'associative' => true ) );
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

	wp_update_post(
		array(
			'ID'           => $user_cpt['ID'],
			'post_content' => wp_slash( wp_json_encode( $variation ) ),
		)
	);
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
 * The customised header template part saved by a starter site, if any.
 *
 * @return WP_Post|null
 */
function unapp_get_customised_header() {
	$parts = get_posts(
		array(
			'post_type'      => 'wp_template_part',
			'post_status'    => 'publish',
			'name'           => 'header',
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
			<?php esc_html_e( 'Each starter builds a complete site for one kind of business: a colour palette, a typeface, a home page and its supporting pages, and a matching menu. Your existing pages are never deleted — applying a starter adds new ones and points the front page at them.', 'unapp' ); ?>
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
					<div class="unapp-starter__preview" aria-hidden="true"
						style="background:linear-gradient(135deg, <?php echo esc_attr( $site['swatches'][0] ); ?> 0%, <?php echo esc_attr( $site['swatches'][1] ); ?> 100%)">
						<span class="unapp-starter__preview-card"></span>
					</div>
					<div class="unapp-starter__body">
						<h2><?php echo esc_html( $site['title'] ); ?><?php echo $is_active ? ' <span class="unapp-starter__badge">' . esc_html__( 'Applied', 'unapp' ) . '</span>' : ''; ?></h2>
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
						<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
							<?php wp_nonce_field( 'unapp_apply_starter' ); ?>
							<input type="hidden" name="action" value="unapp_apply_starter">
							<input type="hidden" name="starter" value="<?php echo esc_attr( $slug ); ?>">
							<button type="submit" class="button button-primary">
								<?php echo $is_active ? esc_html__( 'Apply again', 'unapp' ) : esc_html__( 'Apply this starter', 'unapp' ); ?>
							</button>
						</form>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<style>
		.unapp-starters__intro { max-width: 70ch; }
		.unapp-starters__grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 24px; }
		.unapp-starter { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; }
		.unapp-starter.is-active { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; }
		.unapp-starter__preview { height: 140px; display: flex; align-items: flex-end; justify-content: center; padding: 0 24px; }
		.unapp-starter__preview-card { display: block; width: 100%; height: 62px; background: #fff; border-radius: 8px 8px 0 0; box-shadow: 0 -6px 18px rgba(0,0,0,.12); }
		.unapp-starter__body { padding: 16px 20px 20px; display: flex; flex-direction: column; gap: 8px; flex: 1; }
		.unapp-starter__body h2 { font-size: 15px; margin: 0; }
		.unapp-starter__badge { display: inline-block; margin-inline-start: 6px; padding: 1px 8px; border-radius: 999px; background: #2271b1; color: #fff; font-size: 11px; vertical-align: middle; }
		.unapp-starter__body p { margin: 0; color: #50575e; font-size: 13px; }
		.unapp-starter__meta { color: #787c82 !important; font-size: 12px !important; }
		.unapp-starter form { margin-top: auto; padding-top: 8px; }
	</style>
	<?php
}
