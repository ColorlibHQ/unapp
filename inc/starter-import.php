<?php
/**
 * Validate and recover starter imports before changing the live site.
 *
 * @package Unapp
 */

defined( 'ABSPATH' ) || exit;

const UNAPP_STARTER_LOCK = 'unapp_starter_import_lock';

/**
 * Check every nested reference instead of silently dropping missing sections.
 *
 * @param string $slug Pattern slug.
 * @param array  $path Ancestor slugs, for cycle detection.
 * @return true|WP_Error
 */
function unapp_validate_starter_pattern( $slug, $path = array() ) {
	$registry = WP_Block_Patterns_Registry::get_instance();
	if ( count( $path ) > 5 || in_array( $slug, $path, true ) || ! $registry->is_registered( $slug ) ) {
		return new WP_Error( 'unapp_missing_pattern', __( 'A required starter section is missing or contains a circular reference.', 'unapp' ) );
	}
	$pattern = $registry->get_registered( $slug );
	if ( empty( $pattern['content'] ) ) {
		return new WP_Error( 'unapp_missing_pattern', __( 'A required starter section is empty.', 'unapp' ) );
	}
	$path[] = $slug;
	preg_match_all( '/<!--\s+wp:pattern\s+(\{.*?\})\s+\/-->/', $pattern['content'], $matches );
	foreach ( $matches[1] as $json ) {
		$attrs  = json_decode( $json, true );
		$result = unapp_validate_starter_pattern( $attrs['slug'] ?? '', $path );
		if ( is_wp_error( $result ) ) {
			return $result;
		}
	}
	return true;
}

/**
 * Prepare every page before creating the first one.
 *
 * @param array $site Starter definition.
 * @return array|WP_Error Prepared page markup keyed by page key.
 */
function unapp_prepare_starter( $site ) {
	if ( unapp_starter_missing_requirement( $site ) ) {
		return new WP_Error( 'unapp_missing_requirement', __( 'A required starter plugin is not active.', 'unapp' ) );
	}
	$templates = wp_get_theme()->get_page_templates();
	if ( ! isset( $templates['page-no-title'] ) ) {
		return new WP_Error( 'unapp_missing_template', __( 'The starter page template is unavailable.', 'unapp' ) );
	}
	$pages    = array( 'home' => array( 'patterns' => array( $site['home'] ) ) ) + $site['pages'];
	$prepared = array();
	foreach ( $pages as $key => $page ) {
		$slugs = $page['patterns'] ?? array( $page['pattern'] ?? '' );
		if ( ! $slugs ) {
			return new WP_Error( 'unapp_missing_pattern', __( 'A starter page has no sections.', 'unapp' ) );
		}
		foreach ( $slugs as $slug ) {
			$result = unapp_validate_starter_pattern( $slug );
			if ( is_wp_error( $result ) ) {
				return $result;
			}
		}
		$prepared[ $key ] = unapp_get_starter_page_markup( $page );
		if ( '' === $prepared[ $key ] ) {
			return new WP_Error( 'unapp_missing_pattern', __( 'A starter page could not be prepared.', 'unapp' ) );
		}
	}
	foreach ( array_filter( array( 'unapp/header', $site['footer'] ?? 'unapp/footer' ) ) as $slug ) {
		$result = unapp_validate_starter_pattern( $slug );
		if ( is_wp_error( $result ) ) {
			return $result;
		}
	}
	$variation = unapp_compose_variation( $site['colors'] ?? '', $site['type'] ?? '' );
	if ( ! $variation ) {
		$file = get_theme_file_path( 'styles/' . ( $site['style'] ?? '' ) . '.json' );
		if ( ! is_file( $file ) || ! is_array( wp_json_file_decode( $file, array( 'associative' => true ) ) ) ) {
			return new WP_Error( 'unapp_missing_style', __( 'The starter style could not be loaded.', 'unapp' ) );
		}
	}
	return $prepared;
}

/**
 * Apply a starter once per submitted request, with a site-wide concurrency lock.
 *
 * Failed runs restore existing posts and options; newly created pages stay in
 * the Trash for recovery. No pre-existing page, post or image is deleted.
 *
 * @param string $slug       Starter slug.
 * @param array  $brand      Optional wizard branding and public contact details.
 * @param string $request_id Form submission identifier, empty for deliberate API runs.
 * @return array|WP_Error Created page IDs or an actionable error.
 */
function unapp_apply_starter_site( $slug, $brand = array(), $request_id = '' ) {
	$cache_key = 'unapp_import_' . md5( $request_id );
	$completed = $request_id ? get_transient( $cache_key ) : false;
	if ( is_array( $completed ) ) {
		return ! empty( $completed['undone'] ) ? new WP_Error( 'unapp_import_undone', __( 'This setup was undone. Choose a starter again to create a new site.', 'unapp' ) ) : $completed;
	}
	$lock = unapp_acquire_starter_lock();
	if ( is_wp_error( $lock ) ) {
		return $lock;
	}

	$before = array();
	$new    = array();
	$options = array();
	$capture = static function ( $id ) use ( &$before, &$new ) {
		$post = get_post( $id, ARRAY_A );
		if ( $post && ! isset( $before[ $id ] ) && ! isset( $new[ $id ] )
			&& in_array( $post['post_type'], array( 'page', 'wp_navigation', 'wp_template_part', 'wp_global_styles' ), true ) ) {
			$before[ $id ] = $post;
		}
	};
	$record = static function ( $id, $post, $update ) use ( &$new ) {
		if ( ! $update && in_array( $post->post_type, array( 'page', 'wp_navigation', 'wp_template_part', 'wp_global_styles' ), true ) ) {
			$new[ $id ] = $post->post_type;
		}
	};
	$kses_active = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	$watching    = false;
	$result     = null;
	$previous_undo = get_option( UNAPP_STARTER_UNDO_OPTION, null );

	try {
		// Recheck after taking the lock: the preceding request may have just finished.
		$completed = $request_id ? get_transient( $cache_key ) : false;
		if ( is_array( $completed ) ) {
			return ! empty( $completed['undone'] ) ? new WP_Error( 'unapp_import_undone', __( 'This setup was undone. Choose a starter again to create a new site.', 'unapp' ) ) : $completed;
		}
		$sites = unapp_get_starter_sites();
		if ( ! isset( $sites[ $slug ] ) ) {
			return new WP_Error( 'unapp_unknown_starter', __( 'That starter site does not exist.', 'unapp' ) );
		}
		$draft = unapp_prepare_starter_choices( $sites[ $slug ], $brand );
		if ( is_wp_error( $draft ) ) { return $draft; }
		$site = $draft['site'];
		$prepared = $draft['pages'];
		$business = $draft['business'];

		foreach ( array( 'show_on_front', 'page_on_front', 'page_for_posts', UNAPP_STARTER_OPTION, UNAPP_SETUP_OPTION, UNAPP_OFFER_OPTION, UNAPP_STARTER_NAV_OPTION, 'blogname', 'blogdescription', 'theme_mods_' . get_option( 'stylesheet' ), 'unapp_business_details' ) as $name ) {
			$options[ $name ] = get_option( $name, null );
		}
		add_action( 'pre_post_update', $capture );
		add_action( 'wp_after_insert_post', $record, 10, 3 );
		$watching = true;
		if ( $kses_active ) {
			kses_remove_filters();
		}
		$result = unapp_build_starter_site( $slug, $site, $prepared );
		if ( ! is_wp_error( $result ) && $brand ) {
			$branding = unapp_wizard_apply_brand( $brand );
			if ( is_wp_error( $branding ) ) {
				$result = $branding;
			} else {
				update_option( 'unapp_business_details', $business, false );
				if ( get_option( 'unapp_business_details' ) !== $business ) {
					$result = new WP_Error( 'unapp_starter_save', __( 'The public contact details could not be saved.', 'unapp' ) );
				}
			}
		}
		if ( ! is_wp_error( $result ) ) {
			$history = unapp_record_starter_undo( $before, $new, $options, $request_id );
			if ( is_wp_error( $history ) ) {
				$result = $history;
			}
		}
		if ( $request_id && ! is_wp_error( $result ) ) {
			set_transient( $cache_key, $result, DAY_IN_SECONDS );
		}
	} catch ( Throwable $error ) {
		$result = new WP_Error( 'unapp_starter_save', __( 'Setup stopped before it finished. Please try again.', 'unapp' ) );
	} finally {
		remove_action( 'pre_post_update', $capture );
		remove_action( 'wp_after_insert_post', $record, 10 );
		try {
			if ( is_wp_error( $result ) && $watching ) {
				$restored = unapp_restore_starter_state( $before, $new, $options );
				if ( null === $previous_undo ) {
					delete_option( UNAPP_STARTER_UNDO_OPTION );
				} else {
					update_option( UNAPP_STARTER_UNDO_OPTION, $previous_undo, false );
				}
				$result = new WP_Error( $restored ? 'unapp_import_restored' : 'unapp_import_recovery', $result->get_error_message() );
			}
		} catch ( Throwable $recovery_error ) {
			$result = new WP_Error( 'unapp_import_recovery', __( 'Some setup changes could not be restored.', 'unapp' ) );
		}
		if ( $kses_active ) {
			kses_init_filters();
		}
		if ( get_option( UNAPP_STARTER_LOCK ) === $lock ) {
			delete_option( UNAPP_STARTER_LOCK );
		}
	}

	return $result;
}

/**
 * Sanitize public contact details shared by the wizard and import API.
 *
 * @param array $source Unslashed form values.
 * @return array
 */
function unapp_starter_business_from( $source ) {
	if ( ! empty( $source['profile_enabled'] ) ) {
		$name = ! empty( $source['title'] ) ? $source['title'] : ( $source['name'] ?? html_entity_decode( get_option( 'blogname' ), ENT_QUOTES, 'UTF-8' ) );
		return unapp_business_sanitize( array_merge( $source, array( 'name' => $name ) ) );
	}
	$phone = sanitize_text_field( $source['phone'] ?? '' );
	return array(
		'email' => sanitize_email( $source['email'] ?? '' ),
		'phone' => preg_match( '/[0-9]/', $phone ) ? $phone : '',
	);
}

/**
 * Replace demo contact details in starter content; leave blank choices intact.
 *
 * @param string $markup   Expanded starter markup.
 * @param array  $business Sanitized public contact information.
 * @return string
 */
function unapp_personalize_starter( $markup, $business ) {
	if ( empty( $business['email'] ) && empty( $business['phone'] ) ) {
		return $markup;
	}
	// Navigation links store their URL in a block comment, not an HTML anchor.
	$markup = preg_replace_callback(
		'/(<!-- wp:navigation-link )(\{.*?\})(\s+\/-->)/s',
		static function ( $match ) use ( $business ) {
			$attrs = json_decode( $match[2], true );
			if ( ! is_array( $attrs ) || empty( $attrs['url'] ) ) {
				return $match[0];
			}
			$url = unapp_personalize_starter_url( $attrs['url'], $business );
			if ( $url === $attrs['url'] ) {
				return $match[0];
			}
			$attrs['url'] = $url;
			return $match[1] . serialize_block_attributes( $attrs ) . $match[3];
		},
		$markup
	);
	$tags = new WP_HTML_Tag_Processor( $markup );
	while ( $tags->next_tag( 'a' ) ) {
		$href = (string) $tags->get_attribute( 'href' );
		$url  = unapp_personalize_starter_url( $href, $business );
		if ( $href !== $url ) {
			$tags->set_attribute( 'href', $url );
		}
	}
	return unapp_map_html_text(
		$tags->get_updated_html(),
		static function ( $text ) use ( $business ) {
			if ( $business['email'] ) {
				$text = preg_replace_callback( '~[a-z0-9._%+-]+@(?:[a-z0-9.-]+\.example|example\.com)\b~i', static function () use ( $business ) { return $business['email']; }, $text );
			}
			if ( $business['phone'] ) {
				$text = preg_replace_callback( '/(?:\b0\d{3,4}[ -]\d{3}[ -]\d{4}\b|\+1 235 2355 98)/', static function () use ( $business ) { return $business['phone']; }, $text );
			}
			return $text;
		}
	);
}

/**
 * Personalize a demo contact destination, retaining email subject parameters.
 *
 * @param string $url      Original link.
 * @param array  $business Public contact details.
 * @return string
 */
function unapp_personalize_starter_url( $url, $business ) {
	if ( $business['email'] && preg_match( '~^mailto:[^?]+@(?:[a-z0-9.-]+\.example|example\.com)(\?.*)?$~i', $url, $match ) ) {
		return 'mailto:' . $business['email'] . ( $match[1] ?? '' );
	}
	if ( $business['phone'] && 0 === strpos( $url, 'tel:' ) ) {
		return 'tel:' . preg_replace( '/[^0-9+]/', '', $business['phone'] );
	}
	return $url;
}
