<?php
/** A resumable finishing workspace and native main-action buttons. @package Unapp */
defined( 'ABSPATH' ) || exit;

function unapp_finish_url() {
	return admin_url( 'themes.php?page=unapp-finish' );
}

/** Choices describe visitor intent; all saved output remains ordinary core blocks. */
function unapp_main_action_choices() {
	return array( 'starter' => __( 'Keep starter buttons', 'unapp' ), 'call' => __( 'Call us', 'unapp' ), 'email' => __( 'Email us', 'unapp' ), 'book' => __( 'Book an appointment', 'unapp' ), 'quote' => __( 'Get a quote', 'unapp' ), 'shop' => __( 'Shop', 'unapp' ) );
}

/** Validate locally without requesting the supplied destination. */
function unapp_main_action_target( $source, $profile = null, $context = null ) {
	$type = unapp_design_field( $source, 'type' );
	$choices = unapp_main_action_choices();
	if ( ! isset( $choices[ $type ] ) ) {
		return new WP_Error( 'unapp_action_invalid', __( 'Choose an available main action.', 'unapp' ) );
	}
	$profile = null === $profile ? unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION, array() ) ) : $profile;
	$label = sanitize_text_field( unapp_design_field( $source, 'label' ) );
	$url = trim( unapp_design_field( $source, 'url' ) );
	if ( 'starter' === $type ) {
		return array( 'type' => $type, 'label' => '', 'url' => '' );
	}
	if ( 'call' === $type ) {
		$url = ! empty( $profile['phone'] ) ? 'tel:' . preg_replace( '/[^0-9+]/', '', $profile['phone'] ) : '';
	} elseif ( 'email' === $type ) {
		$url = ! empty( $profile['email'] ) && is_email( $profile['email'] ) ? 'mailto:' . $profile['email'] : '';
	} elseif ( 'shop' === $type ) {
		$id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
		$url = $id > 0 && 'publish' === get_post_status( $id ) ? get_permalink( $id ) : '';
	} elseif ( 'quote' === $type && '' === $url ) {
		if ( null !== $context ) {
			$url = $context['contact_url'] ?? '';
		} else {
		$active = unapp_launch_active_starter();
		$contact = $active['pages']['contact'] ?? 0;
		$home = get_post( $active['pages']['home'] ?? 0 );
		$url = $contact && 'publish' === get_post_status( $contact ) ? get_permalink( $contact ) : ( $home && false !== strpos( $home->post_content, 'id="business-details"' ) ? get_permalink( $home ) . '#business-details' : '' );
		}
	}
	if ( in_array( $type, array( 'book', 'quote' ), true ) && $url ) {
		if ( ! in_array( strtolower( (string) wp_parse_url( $url, PHP_URL_SCHEME ) ), array( 'http', 'https' ), true ) || ! wp_parse_url( $url, PHP_URL_HOST ) ) {
			return new WP_Error( 'unapp_action_url', __( 'Use a complete http or https booking or quote URL.', 'unapp' ) );
		}
		$url = esc_url_raw( $url, array( 'http', 'https' ) );
	}
	if ( ! $url ) {
		return new WP_Error( 'unapp_action_missing', __( 'Add the public phone or email in Business profile, a booking or quote URL, or a published WooCommerce Shop page for this action.', 'unapp' ) );
	}
	return array( 'type' => $type, 'label' => wp_html_excerpt( $label ?: $choices[ $type ], 60, '' ), 'url' => $url );
}

/** Replace only the link and label, retaining native block styling and layout. */
function unapp_main_action_button( $html, $target ) {
	$tags = new WP_HTML_Tag_Processor( $html );
	if ( ! $tags->next_tag( 'a' ) ) {
		return $html;
	}
	$tags->set_attribute( 'href', $target['url'] );
	return preg_replace_callback( '~(<a\b[^>]*>).*?(</a>)~s', static function ( $m ) use ( $target ) { return $m[1] . esc_html( $target['label'] ) . $m[2]; }, $tags->get_updated_html(), 1 );
}

/** Run once on the first primary button, never secondary or state controls. */
function unapp_main_action_first( &$blocks, $callback ) {
	foreach ( $blocks as &$block ) {
		if ( 'core/button' === $block['blockName'] && ! preg_match( '/is-style-outline|unapp-(?:scheme|period)/', $block['attrs']['className'] ?? '' ) ) {
			$block = $callback( $block );
			return true;
		}
		if ( unapp_main_action_first( $block['innerBlocks'], $callback ) ) {
			return true;
		}
	}
	return false;
}

/** Only the hero and closing call to action are shared, not pricing/product cards. */
function unapp_main_action_mark( $markup, $header = false, $expected = null, &$kept = null ) {
	$blocks = parse_blocks( $markup );
	$roots = array();
	foreach ( $blocks as $index => $block ) {
		$copy = array( $block );
		if ( unapp_main_action_first( $copy, static function ( $button ) { return $button; } ) ) {
			$roots[] = $index;
		}
	}
	$roots = $roots ? array_unique( array( reset( $roots ), $header ? reset( $roots ) : end( $roots ) ) ) : array();
	$originals = array();
	if ( null !== $expected ) {
		$collect = static function ( $items ) use ( &$collect, &$originals ) {
			foreach ( $items as $item ) {
				if ( isset( $item['attrs']['metadata']['unappAction'] ) ) {
					$originals[] = $item['innerHTML'];
				}
				$collect( $item['innerBlocks'] );
			}
		};
		$collect( parse_blocks( unapp_main_action_mark( $expected, $header ) ) );
	}
	foreach ( $roots as $order => $index ) {
		$section = array( $blocks[ $index ] );
		unapp_main_action_first( $section, static function ( $button ) use ( $expected, $originals, $order, &$kept ) {
			if ( isset( $button['attrs']['metadata']['unappAction'] ) ) {
				return $button;
			}
			if ( null !== $expected && ( $originals[ $order ] ?? '' ) !== $button['innerHTML'] ) {
				++$kept;
				return $button;
			}
			$tags = new WP_HTML_Tag_Processor( $button['innerHTML'] );
			if ( $tags->next_tag( 'a' ) ) {
				$button['attrs']['metadata']['unappAction'] = array( 'baseline' => hash( 'sha256', $button['innerHTML'] ), 'original' => array( 'url' => (string) $tags->get_attribute( 'href' ), 'label' => html_entity_decode( wp_strip_all_tags( $button['innerHTML'] ), ENT_QUOTES, 'UTF-8' ) ) );
			}
			return $button;
		} );
		$blocks[ $index ] = $section[0];
	}
	return serialize_blocks( $blocks );
}

/** Respect direct edits, and retain the starter destination for a later reset. */
function unapp_main_action_update( $markup, $target, &$updated, &$kept ) {
	$walk = static function ( $blocks ) use ( &$walk, $target, &$updated, &$kept ) {
		foreach ( $blocks as &$block ) {
			$marker = $block['attrs']['metadata']['unappAction'] ?? null;
			if ( 'core/button' === $block['blockName'] && is_array( $marker ) && isset( $marker['baseline'], $marker['original'] ) ) {
				if ( ! hash_equals( $marker['baseline'], hash( 'sha256', $block['innerHTML'] ) ) ) {
					++$kept;
				} else {
					$html = unapp_main_action_button( $block['innerHTML'], 'starter' === $target['type'] ? $marker['original'] : $target );
					if ( $html !== $block['innerHTML'] ) {
						$block['innerHTML'] = $html;
						$block['innerContent'] = array( $html );
						++$updated;
					}
					$block['attrs']['metadata']['unappAction']['baseline'] = hash( 'sha256', $html );
					if ( 'starter' !== $target['type'] ) {
						unset( $block['attrs']['metadata']['unappBusiness'] );
					} else {
						foreach ( array( 'mailto:' => 'email_link', 'tel:' => 'phone_link' ) as $prefix => $field ) {
							if ( 0 === strpos( $marker['original']['url'], $prefix ) ) {
								$block['attrs']['metadata']['unappBusiness'] = unapp_business_marker( $field, $html );
							}
						}
					}
				}
			}
			$block['innerBlocks'] = $walk( $block['innerBlocks'] );
		}
		return $blocks;
	};
	return serialize_blocks( $walk( parse_blocks( $markup ) ) );
}

/** Keep reset destinations current when the public contact details change. */
function unapp_main_action_profile( $markup, $profile, $old, &$changed ) {
	$walk = static function ( $blocks ) use ( &$walk, $profile, $old, &$changed ) {
		foreach ( $blocks as &$block ) {
			$original = $block['attrs']['metadata']['unappAction']['original'] ?? null;
			if ( is_array( $original ) ) {
				foreach ( array( 'email' => 'mailto:', 'phone' => 'tel:' ) as $field => $prefix ) {
					$previous = 'phone' === $field ? preg_replace( '/[^0-9+]/', '', $old[ $field ] ?? '' ) : ( $old[ $field ] ?? '' );
					$destination = strtok( $original['url'], '?' );
					$demo = 'email' === $field ? preg_match( '~^mailto:[a-z0-9._%+-]+@(?:[a-z0-9.-]+\.example|example\.com)$~i', $destination ) : ( 0 === strpos( $destination, 'tel:' ) && unapp_launch_demo_phone( substr( $destination, 4 ) ) );
					if ( ( ( $previous && $destination === $prefix . $previous ) || $demo ) && $profile[ $field ] !== ( $old[ $field ] ?? '' ) ) {
						$value = 'phone' === $field ? preg_replace( '/[^0-9+]/', '', $profile[ $field ] ) : $profile[ $field ];
						$query = strstr( $original['url'], '?' );
						$original['url'] = $value ? $prefix . $value . ( $query ?: '' ) : home_url( '/#business-details' );
						if ( $original['label'] === $old[ $field ] ) {
							$original['label'] = $profile[ $field ] ?: __( 'Contact details', 'unapp' );
						}
						$block['attrs']['metadata']['unappAction']['original'] = $original;
						++$changed;
					}
				}
			}
			$block['innerBlocks'] = $walk( $block['innerBlocks'] );
		}
		return $blocks;
	};
	return serialize_blocks( $walk( parse_blocks( $markup ) ) );
}

/** Expected markup allows safe adoption of older, unchanged generated buttons. */
function unapp_main_action_expected( $active, $profile ) {
	$site = unapp_get_starter_sites()[ $active['slug'] ?? '' ] ?? null;
	if ( ! $site ) {
		return array();
	}
	$home = unapp_business_prepare_markup( unapp_personalize_starter( unapp_get_pattern_markup( $site['home'] ), $profile ), $profile, true );
	if ( 'one-page' === ( $active['layout'] ?? '' ) ) {
		$site = unapp_one_page_site( $site );
		$site['section_links'] = unapp_one_page_links( $home . unapp_business_card( $profile, 'business-details' ) );
	}
	$header = unapp_get_pattern_markup( 'unapp/header' );
	$blocks = parse_blocks( $header );
	unapp_main_action_first( $blocks, static function ( $block ) use ( $site, $active ) {
		$block['innerHTML'] = unapp_main_action_button( $block['innerHTML'], array( 'label' => $site['cta'], 'url' => unapp_starter_cta_url( $site, $active['pages'] ) ) );
		$block['innerContent'] = array( $block['innerHTML'] );
		return $block;
	} );
	return array( 'home' => $home, 'header' => serialize_blocks( $blocks ) );
}

/** Save both native blocks and the selected intent, with stale-edit/failure recovery. */
function unapp_save_main_action( $source, $snapshot ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return new WP_Error( 'unapp_action_forbidden', __( 'You are not allowed to change the main action.', 'unapp' ) );
	}
	$target = unapp_main_action_target( $source );
	if ( is_wp_error( $target ) ) {
		return $target;
	}
	$lock = unapp_acquire_starter_lock();
	if ( is_wp_error( $lock ) ) {
		return $lock;
	}
	$before = array();
	$options = array( UNAPP_STARTER_OPTION => get_option( UNAPP_STARTER_OPTION, null ) );
	$result = null;
	$success = false;
	$writing = false;
	try {
		if ( ! is_string( $snapshot ) || ! hash_equals( unapp_business_snapshot(), $snapshot ) ) {
			return new WP_Error( 'unapp_action_changed', __( 'The site changed. Refresh before saving the main action.', 'unapp' ) );
		}
		$active = unapp_launch_active_starter();
		$home = get_post( $active['pages']['home'] ?? 0 );
		if ( ! $home || 'page' !== $home->post_type || ! current_user_can( 'edit_post', $home->ID ) ) {
			return new WP_Error( 'unapp_action_home', __( 'Apply a starter and make sure you can edit its homepage first.', 'unapp' ) );
		}
		$expected = unapp_main_action_expected( $active, unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION, array() ) ) );
		$parts = array( 'home' => $home );
		$header = unapp_get_customised_part( 'header' );
		if ( $header ) {
			$parts['header'] = $header;
		}
		$updated = 0;
		$kept = 0;
		$writing = true;
		foreach ( $parts as $area => $post ) {
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return new WP_Error( 'unapp_action_forbidden', __( 'You are not allowed to edit these action buttons.', 'unapp' ) );
			}
			$content = unapp_main_action_mark( $post->post_content, 'header' === $area, $expected[ $area ] ?? '', $kept );
			$content = unapp_main_action_update( $content, $target, $updated, $kept );
			if ( $content === $post->post_content ) {
				continue;
			}
			$before[ $post->ID ] = get_post( $post->ID, ARRAY_A );
			unapp_keep_revision( $post->ID );
			$saved = wp_update_post( array( 'ID' => $post->ID, 'post_content' => wp_slash( $content ) ), true );
			if ( is_wp_error( $saved ) || ! $saved || get_post( $post->ID )->post_content !== $content ) {
				$result = new WP_Error( 'unapp_action_save', __( 'The action buttons could not be saved completely.', 'unapp' ) );
				return $result;
			}
		}
		$active['main_action'] = array( 'type' => $target['type'], 'label' => sanitize_text_field( unapp_design_field( $source, 'label' ) ), 'url' => in_array( $target['type'], array( 'book', 'quote' ), true ) ? esc_url_raw( unapp_design_field( $source, 'url' ), array( 'http', 'https' ) ) : '' );
		update_option( UNAPP_STARTER_OPTION, $active );
		if ( get_option( UNAPP_STARTER_OPTION ) !== $active ) {
			$result = new WP_Error( 'unapp_action_save', __( 'The main action could not be saved.', 'unapp' ) );
			return $result;
		}
		$success = true;
		return array( 'updated' => $updated, 'kept' => $kept );
	} catch ( Throwable $error ) {
		$result = new WP_Error( 'unapp_action_save', __( 'The action update stopped before it finished.', 'unapp' ) );
		return $result;
	} finally {
		if ( ! $success && $writing ) {
			unapp_restore_starter_state( $before, array(), $options );
		}
		if ( get_option( UNAPP_STARTER_LOCK ) === $lock ) {
			delete_option( UNAPP_STARTER_LOCK );
		}
	}
}

function unapp_finish_menu() {
	add_theme_page( __( 'Finish your website', 'unapp' ), __( 'Finish your website', 'unapp' ), 'edit_theme_options', 'unapp-finish', 'unapp_render_finish_screen' );
}
add_action( 'admin_menu', 'unapp_finish_menu' );

function unapp_handle_main_action() {
	check_admin_referer( 'unapp_main_action' );
	$source = array_filter( $_POST, 'is_string' );
	$result = unapp_save_main_action( $source, unapp_design_field( $_POST, 'snapshot' ) );
	$notice = $result;
	if ( is_wp_error( $result ) ) {
		$notice = array( 'error' => $result->get_error_message(), 'values' => array( 'type' => sanitize_key( unapp_design_field( $source, 'type' ) ), 'label' => sanitize_text_field( unapp_design_field( $source, 'label' ) ), 'url' => esc_url_raw( unapp_design_field( $source, 'url' ), array( 'http', 'https' ) ) ) );
	}
	set_transient( 'unapp_finish_notice_' . get_current_user_id(), $notice, 5 * MINUTE_IN_SECONDS );
	wp_safe_redirect( unapp_finish_url() . '#unapp-main-action' );
	exit;
}
add_action( 'admin_post_unapp_main_action', 'unapp_handle_main_action' );

/** One next step, derived from the current site rather than another checklist. */
function unapp_finish_next( $active, $profile, $checks ) {
	if ( empty( $active['pages']['home'] ) ) {
		return array( __( 'Choose a starter', 'unapp' ), admin_url( 'themes.php?page=unapp-starter-sites' ) );
	}
	if ( current_user_can( 'manage_options' ) && ( empty( $profile['name'] ) || ( empty( $profile['email'] ) && empty( $profile['phone'] ) ) ) ) {
		return array( __( 'Add your business details', 'unapp' ), unapp_business_url() );
	}
	if ( empty( $active['main_action'] ) || is_wp_error( unapp_main_action_target( $active['main_action'] ) ) ) {
		return array( __( 'Choose your main action', 'unapp' ), '#unapp-main-action' );
	}
	if ( empty( $checks['checks']['content']['complete'] ) ) {
		$page_id = unapp_content_guide_next_page();
		return $page_id ? array( __( 'Review your website content', 'unapp' ), unapp_content_guide_url( '', $page_id ) ) : array( __( 'Review the rest of your site', 'unapp' ), unapp_launch_url() );
	}
	return ! empty( $checks ) && $checks['complete'] === $checks['total'] ? array( __( 'View your website', 'unapp' ), home_url( '/' ) ) : array( __( 'Finish your launch checks', 'unapp' ), unapp_launch_url() );
}

function unapp_render_finish_screen() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to finish this website.', 'unapp' ), 403 );
	}
	$active = unapp_launch_active_starter();
	$home = get_post( $active['pages']['home'] ?? 0 );
	$profile = unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION, array() ) );
	$checks = unapp_launch_checklist();
	$next = unapp_finish_next( $active, $profile, $checks );
	$action = $active['main_action'] ?? array( 'type' => 'starter', 'label' => '', 'url' => '' );
	$notice = get_transient( 'unapp_finish_notice_' . get_current_user_id() );
	delete_transient( 'unapp_finish_notice_' . get_current_user_id() );
	if ( isset( $notice['values'] ) && isset( unapp_main_action_choices()[ $notice['values']['type'] ] ) ) {
		$action = $notice['values'];
	}
	?>
	<div class="wrap unapp-finish">
		<h1><?php esc_html_e( 'Finish your website', 'unapp' ); ?></h1>
		<p><?php esc_html_e( 'Your next steps, all in one place. Edit with WordPress, then return here whenever you are ready to continue.', 'unapp' ); ?></p>
		<?php if ( ! empty( $notice['error'] ) ) : ?><div class="notice notice-error"><p><?php echo esc_html( $notice['error'] ); ?></p></div><?php elseif ( is_array( $notice ) ) : ?><div class="notice notice-success"><p><?php /* translators: 1: updated buttons, 2: customized buttons preserved. */ printf( esc_html__( 'Main action saved. %1$d buttons updated; %2$d customized buttons kept.', 'unapp' ), (int) $notice['updated'], (int) $notice['kept'] ); ?></p></div><?php endif; ?>
		<section class="unapp-finish__next"><h2><?php esc_html_e( 'Next step', 'unapp' ); ?></h2><p><a class="button button-primary button-hero" href="<?php echo esc_url( $next[1] ); ?>"><?php echo esc_html( $next[0] ); ?></a></p>
		<?php if ( $home ) : ?><p><?php echo 'publish' === $home->post_status ? esc_html__( 'Your starter homepage is published. Review its content before sharing your website.', 'unapp' ) : esc_html__( 'Your starter homepage is not published. Open it in WordPress to review and publish it.', 'unapp' ); ?> <a href="<?php echo esc_url( get_permalink( $home ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View homepage', 'unapp' ); ?></a></p><?php endif; ?>
		<?php if ( $home && (int) get_option( 'page_on_front' ) !== $home->ID ) : ?><p><?php esc_html_e( 'Another page is currently selected as your front page.', 'unapp' ); ?> <a href="<?php echo esc_url( admin_url( 'options-reading.php' ) ); ?>"><?php esc_html_e( 'Review Reading settings', 'unapp' ); ?></a></p><?php endif; ?>
		<?php if ( '0' === (string) get_option( 'blog_public' ) ) : ?><p><?php esc_html_e( 'Search engine indexing is currently discouraged. This is fine while building or on a staging site; review it when your public site is ready.', 'unapp' ); ?> <a href="<?php echo esc_url( admin_url( 'options-reading.php' ) ); ?>"><?php esc_html_e( 'Review search visibility', 'unapp' ); ?></a></p><?php endif; ?>
		</section>
		<?php if ( $home ) : ?>
		<div class="unapp-finish__grid">
			<section><h2><?php esc_html_e( 'Your business', 'unapp' ); ?></h2><p><?php esc_html_e( 'Update shared public details, including your description, contact details and hours.', 'unapp' ); ?></p><?php if ( current_user_can( 'manage_options' ) ) : ?><a class="button" href="<?php echo esc_url( unapp_business_url() ); ?>"><?php esc_html_e( 'Edit business profile', 'unapp' ); ?></a><?php endif; ?></section>
			<section><h2><?php esc_html_e( 'Your look', 'unapp' ); ?></h2><p><?php esc_html_e( 'Choose your logo, colors, fonts, header and footer using native WordPress controls.', 'unapp' ); ?></p><a class="button" href="<?php echo esc_url( unapp_design_url() ); ?>"><?php esc_html_e( 'Make it yours', 'unapp' ); ?></a></section>
			<section><h2><?php esc_html_e( 'Your content', 'unapp' ); ?></h2><p><?php esc_html_e( 'Review your homepage and supporting pages one section at a time. Replace images and edit content using the native WordPress editor.', 'unapp' ); ?></p><?php if ( current_user_can( 'edit_post', $home->ID ) ) : ?><a class="button" href="<?php echo esc_url( unapp_content_guide_url() ); ?>"><?php esc_html_e( 'Start guided content pass', 'unapp' ); ?></a> <a href="<?php echo esc_url( get_edit_post_link( $home->ID, 'raw' ) ); ?>"><?php esc_html_e( 'Edit homepage in WordPress', 'unapp' ); ?></a><?php endif; ?></section>
			<section><h2><?php esc_html_e( 'Your launch checks', 'unapp' ); ?></h2><p><?php if ( $checks ) { /* translators: 1: completed checks, 2: total checks. */ printf( esc_html__( '%1$d of %2$d checks complete. Choose a contact method, test delivery and review your site on mobile.', 'unapp' ), $checks['complete'], $checks['total'] ); } ?></p><a class="button" href="<?php echo esc_url( unapp_launch_url() ); ?>"><?php esc_html_e( 'Review launch checklist', 'unapp' ); ?></a> <a href="<?php echo esc_url( unapp_launch_url() . '#unapp-contact-form' ); ?>"><?php esc_html_e( 'Choose a contact form', 'unapp' ); ?></a> <a href="<?php echo esc_url( unapp_launch_url() . '#unapp-recommended-checks' ); ?>"><?php esc_html_e( 'Recommended checks (optional)', 'unapp' ); ?></a></section>
		</div>
		<section id="unapp-main-action"><h2><?php esc_html_e( 'What should visitors do next?', 'unapp' ); ?></h2><p><?php esc_html_e( 'Choose the main action for your generated header, homepage hero and closing button. Secondary links and directly edited buttons are kept. Layouts without a header button stay that way. Everything remains editable in WordPress.', 'unapp' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="unapp_main_action"><input type="hidden" name="snapshot" value="<?php echo esc_attr( unapp_business_snapshot() ); ?>"><?php wp_nonce_field( 'unapp_main_action' ); ?>
			<p><label for="unapp-action-type"><?php esc_html_e( 'Main action', 'unapp' ); ?></label><select id="unapp-action-type" name="type"><?php foreach ( unapp_main_action_choices() as $type => $label ) : ?><option value="<?php echo esc_attr( $type ); ?>" <?php selected( $action['type'], $type ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></p>
			<p><label for="unapp-action-label"><?php esc_html_e( 'Button wording (optional)', 'unapp' ); ?></label><input id="unapp-action-label" name="label" type="text" maxlength="60" value="<?php echo esc_attr( $action['label'] ); ?>" class="regular-text"><span class="description"><?php esc_html_e( 'Leave blank to use the action name.', 'unapp' ); ?></span></p>
			<p><label for="unapp-action-url"><?php esc_html_e( 'Booking or quote URL', 'unapp' ); ?></label><input id="unapp-action-url" name="url" type="url" value="<?php echo esc_attr( $action['url'] ); ?>" class="regular-text" aria-describedby="unapp-action-help"><span id="unapp-action-help" class="description"><?php esc_html_e( 'Required for bookings. Quotes use your Contact page or homepage contact details when left blank. Call and email use Business profile; Shop uses your published WooCommerce Shop page.', 'unapp' ); ?></span></p>
			<button class="button button-primary" type="submit"><?php esc_html_e( 'Save main action', 'unapp' ); ?></button>
		</form></section>
		<?php endif; ?>
	</div>
	<style>.unapp-finish{max-width:1040px}.unapp-finish section{background:#fff;border:1px solid #c3c4c7;border-radius:8px;padding:24px;margin:20px 0;min-width:0;scroll-margin-top:48px}.unapp-finish section h2{margin-top:0}.unapp-finish__next{border-inline-start:4px solid #2271b1!important}.unapp-finish__grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.unapp-finish__grid section{margin:0}.unapp-finish__grid section>a{display:inline-block;margin:0 8px 8px 0}.unapp-finish label{display:block;font-weight:600;margin-bottom:6px}.unapp-finish .description{display:block;margin-top:6px}.unapp-finish input,.unapp-finish select{max-width:100%;box-sizing:border-box}.unapp-finish a,.unapp-finish p{overflow-wrap:anywhere}.unapp-finish .button{white-space:normal;height:auto;min-height:36px}@media(max-width:700px){.unapp-finish__grid{grid-template-columns:1fr}.unapp-finish section{padding:18px}}</style>
	<?php
}
