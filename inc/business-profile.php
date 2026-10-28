<?php
/** Shared, editable business details and incremental starter growth. @package Unapp */
defined( 'ABSPATH' ) || exit;
const UNAPP_BUSINESS_OPTION = 'unapp_business_details';

/** @return array Public profile field labels. */
function unapp_business_fields() {
	return array( 'name' => __( 'Business name', 'unapp' ), 'description' => __( 'Short description', 'unapp' ), 'location' => __( 'Location or address', 'unapp' ), 'services' => __( 'Services', 'unapp' ), 'hours' => __( 'Opening hours', 'unapp' ), 'email' => __( 'Public email', 'unapp' ), 'phone' => __( 'Public phone', 'unapp' ) );
}

/** @return array Sanitized scalar public values. */
function unapp_business_sanitize( $source ) {
	$out = array();
	foreach ( unapp_business_fields() as $field => $unused ) {
		$value = isset( $source[ $field ] ) && is_string( $source[ $field ] ) ? $source[ $field ] : '';
		$out[ $field ] = 'email' === $field ? sanitize_email( $value ) : ( in_array( $field, array( 'description', 'services', 'hours', 'location' ), true ) ? sanitize_textarea_field( $value ) : sanitize_text_field( $value ) );
	}
	if ( ! preg_match( '/[0-9]/', $out['phone'] ) ) {
		$out['phone'] = '';
	}
	if ( isset( $source['service_cards'] ) || isset( $source['service_cards_present'] ) ) { $out['service_cards'] = unapp_service_cards_sanitize( $source ); }
	return $out;
}

/** @return true|WP_Error Reject mistyped contacts rather than silently clearing them. */
function unapp_business_validate( $source ) {
	$services = unapp_service_cards_validate( $source );
	if ( is_wp_error( $services ) ) { return $services; }
	if ( ! empty( $source['email'] ) && ( ! is_string( $source['email'] ) || ! is_email( $source['email'] ) ) ) {
		return new WP_Error( 'unapp_business_contact', __( 'Enter a valid public email address, or leave it empty.', 'unapp' ) );
	}
	if ( ! empty( $source['phone'] ) && ( ! is_string( $source['phone'] ) || ! preg_match( '/[0-9]/', $source['phone'] ) ) ) {
		return new WP_Error( 'unapp_business_contact', __( 'Enter a public phone number containing digits, or leave it empty.', 'unapp' ) );
	}
	return true;
}

/** @return string Public profile value, including the hero's brand line. */
function unapp_business_value( $profile, $field ) {
	if ( 'intro' === $field ) {
		$location = strtok( $profile['location'] ?? '', "\r\n" );
		return implode( ' · ', array_filter( array( $profile['name'] ?? '', strlen( (string) $location ) < 70 ? $location : '' ) ) );
	}
	if ( 'contact' === $field ) {
		return implode( ' · ', array_filter( array( $profile['email'] ?? '', $profile['phone'] ?? '' ) ) );
	}
	if ( 'email_link' === $field || 'phone_link' === $field ) {
		return $profile[ str_replace( '_link', '', $field ) ] ?? '';
	}
	return $profile[ $field ] ?? '';
}

/** @return string Safe rich text for a shared paragraph. */
function unapp_business_html( $profile, $field ) {
	$value = unapp_business_value( $profile, $field );
	if ( 'contact' === $field ) {
		return implode( ' · ', array_filter( array( unapp_business_html( $profile, 'email' ), unapp_business_html( $profile, 'phone' ) ) ) );
	}
	if ( 'email' === $field && $value ) {
		return '<a href="' . esc_url( 'mailto:' . $value ) . '">' . esc_html( $value ) . '</a>';
	}
	if ( 'phone' === $field && $value ) {
		return '<a href="' . esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $value ) ) . '">' . esc_html( $value ) . '</a>';
	}
	$label = in_array( $field, array( 'location', 'services', 'hours' ), true ) && $value ? '<strong>' . esc_html( unapp_business_fields()[ $field ] ) . '</strong><br>' : '';
	return $label . nl2br( esc_html( $value ), false );
}

/** @return array Shared-field metadata. A changed block is never overwritten. */
function unapp_business_marker( $field, $html ) {
	return array( 'field' => $field, 'baseline' => hash( 'sha256', $html ) );
}

/** @return string Portable core blocks, with empty rows reserved for later details. */
function unapp_business_card( $profile, $anchor = '', $compact = false ) {
	$class = 'unapp-business-profile' . ( $compact ? ' unapp-business-profile--footer' : '' );
	$attrs = array( 'className' => $class, 'layout' => array( 'type' => 'constrained' ) );
	$attrs['metadata']['name'] = __( 'Business details', 'unapp' );
	if ( $anchor ) {
		$attrs['anchor'] = $anchor;
		$attrs['metadata']['unappSection'] = $anchor;
	}
	$inside = '';
	foreach ( unapp_business_fields() as $field => $label ) {
		if ( $compact && ! in_array( $field, array( 'location', 'hours', 'email', 'phone' ), true ) ) {
			continue;
		}
		$html = '<p>' . unapp_business_html( $profile, $field ) . '</p>';
		$meta = array( 'metadata' => array( 'name' => $label, 'unappBusiness' => unapp_business_marker( $field, $html ) ) );
		$inside .= '<!-- wp:paragraph ' . serialize_block_attributes( $meta ) . ' -->' . $html . '<!-- /wp:paragraph -->';
	}
	return '<!-- wp:group ' . serialize_block_attributes( $attrs ) . ' --><div' . ( $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '' ) . ' class="wp-block-group ' . $class . '">' . $inside . '</div><!-- /wp:group -->';
}

/** Nest shared contact details into the native brand column when available. */
function unapp_business_footer( $markup, $profile ) {
	$card = unapp_business_card( $profile, '', true );
	$inserted = false;
	$walk = static function ( $blocks ) use ( &$walk, &$inserted, $card ) {
		foreach ( $blocks as &$block ) {
			if ( ! $inserted && 'core/group' === $block['blockName'] && in_array( 'unapp-footer-brand', explode( ' ', $block['attrs']['className'] ?? '' ), true ) ) {
				$last = count( $block['innerContent'] ) - 1;
				$end = $block['innerContent'][ $last ];
				$position = strrpos( $end, '</div>' );
				if ( false !== $position ) {
					array_splice( $block['innerContent'], $last, 1, array( substr( $end, 0, $position ), null, substr( $end, $position ) ) );
					$block['innerBlocks'][] = parse_blocks( $card )[0];
					$inserted = true;
				}
			} else {
				$block['innerBlocks'] = $walk( $block['innerBlocks'] );
			}
		}
		return $blocks;
	};
	$blocks = $walk( parse_blocks( $markup ) );
	return $inserted ? serialize_blocks( $blocks ) : $card . $markup;
}

/** Mark generated hero copy and simple contact fields without custom blocks. */
function unapp_business_prepare_markup( $markup, $profile, $hero = false ) {
	$heading_seen = false;
	$eyebrow_seen = false;
	$lead_seen = false;
	$walk = static function ( $blocks ) use ( &$walk, $profile, $hero, &$heading_seen, &$eyebrow_seen, &$lead_seen ) {
		foreach ( $blocks as &$block ) {
			if ( 'core/heading' === $block['blockName'] && 1 === ( $block['attrs']['level'] ?? 2 ) ) {
				$heading_seen = true;
			}
			$field = '';
			if ( 'core/paragraph' === $block['blockName'] && empty( $block['attrs']['metadata']['unappBusiness'] ) && empty( $block['attrs']['metadata']['unappService'] ) ) {
				$text = trim( html_entity_decode( wp_strip_all_tags( $block['innerHTML'] ), ENT_QUOTES, 'UTF-8' ) );
				if ( $hero && ! $heading_seen && ! $eyebrow_seen ) {
					$field = 'intro';
					$eyebrow_seen = true;
				} elseif ( $hero && $heading_seen && ! $lead_seen ) {
					$field = 'description';
					$lead_seen = true;
				} else {
					foreach ( array( 'email', 'phone' ) as $contact ) {
						if ( ! empty( $profile[ $contact ] ) && $profile[ $contact ] === $text ) {
							$field = $contact;
						}
					}
					if ( ! $field && ! empty( $profile['email'] ) && ! empty( $profile['phone'] ) && $text === unapp_business_value( $profile, 'contact' ) ) {
						$field = 'contact';
					}
					if ( ! $field && preg_match( '~^[a-z0-9._%+-]+@(?:[a-z0-9.-]+\.example|example\.com)$~i', $text ) ) {
						$field = 'email';
					}
					if ( ! $field && preg_match( '/^[+0-9 ()-]+$/', $text ) && unapp_launch_demo_phone( $text ) ) {
						$field = 'phone';
					}
					if ( ! $field && ( preg_match( '~@(?:[a-z0-9.-]+\.example|example\.com)\b~i', $text ) || ( ! empty( $profile['email'] ) && false !== strpos( $text, $profile['email'] ) ) ) ) {
						$rest = preg_replace( '~[a-z0-9._%+-]+@[a-z0-9.-]+~i', '', $text );
						$rest = preg_replace( '/\+?[0-9][0-9 ()-]{6,}[0-9]/', '', $rest );
						if ( '' === preg_replace( '/[\s·|,;—–]+/u', '', $rest ) ) {
							$field = 'contact';
						}
					}
				}
				if ( $field ) {
					if ( unapp_business_value( $profile, $field ) ) {
						$block['innerHTML'] = preg_replace_callback( '~(<p\b[^>]*>).*?(</p>)~s', static function ( $m ) use ( $profile, $field ) { return $m[1] . unapp_business_html( $profile, $field ) . $m[2]; }, $block['innerHTML'], 1 );
						$block['innerContent'] = array( $block['innerHTML'] );
					}
					$block['attrs']['metadata']['unappBusiness'] = unapp_business_marker( $field, $block['innerHTML'] );
				}
			}
			if ( 'core/button' === $block['blockName'] && empty( $block['attrs']['metadata']['unappBusiness'] ) ) {
				$tags = new WP_HTML_Tag_Processor( $block['innerHTML'] );
				if ( $tags->next_tag( 'a' ) ) {
					$url = (string) $tags->get_attribute( 'href' );
					if ( 0 === strpos( $url, 'mailto:' ) && ( preg_match( '~@(?:[a-z0-9.-]+\.example|example\.com)(?:\?|$)~i', $url ) || ( ! empty( $profile['email'] ) && 0 === strpos( $url, 'mailto:' . $profile['email'] ) ) ) ) {
						$block['attrs']['metadata']['unappBusiness'] = unapp_business_marker( 'email_link', $block['innerHTML'] );
					} elseif ( 0 === strpos( $url, 'tel:' ) ) {
						$block['attrs']['metadata']['unappBusiness'] = unapp_business_marker( 'phone_link', $block['innerHTML'] );
					}
				}
			}
			$block['innerBlocks'] = $walk( $block['innerBlocks'] );
		}
		return $blocks;
	};
	return serialize_blocks( $walk( parse_blocks( $markup ) ) );
}

/** Collect real section anchors, with labels suitable for a one-page menu. */
function unapp_one_page_links( $markup ) {
	$links = array();
	foreach ( parse_blocks( $markup ) as $block ) {
		$anchor = $block['attrs']['anchor'] ?? '';
		if ( ! $anchor ) {
			continue;
		}
		$heading = unapp_design_find_block( array( $block ), 'core/heading' );
		$eyebrow = unapp_design_find_block( array( $block ), 'core/paragraph' );
		$label = $eyebrow ? wp_strip_all_tags( $eyebrow['innerHTML'] ) : ( $heading ? wp_strip_all_tags( $heading['innerHTML'] ) : __( 'Details', 'unapp' ) );
		$label = 'business-details' === $anchor ? __( 'Contact', 'unapp' ) : wp_html_excerpt( $label, 28, '…' );
		$links[ $anchor ] = trim( html_entity_decode( $label, ENT_QUOTES, 'UTF-8' ) );
	}
	return $links;
}

/** Choose a complete one-page site without inventing missing page destinations. */
function unapp_one_page_site( $site ) {
	$site['one_page'] = true;
	$site['supporting_pages'] = $site['pages'];
	$site['pages'] = array();
	// The neutral footer avoids links to supporting pages that do not exist yet.
	$site['footer'] = 'unapp/footer-columns';
	return $site;
}

/** @return string Snapshot of profile and all editable starter sources. */
function unapp_business_snapshot() {
	$states = array();
	foreach ( unapp_business_posts() as $id ) {
		$states[ $id ] = unapp_starter_post_fingerprint( $id );
	}
	$nav = (int) get_option( UNAPP_STARTER_NAV_OPTION );
	return hash( 'sha256', wp_json_encode( array( get_option( UNAPP_BUSINESS_OPTION, array() ), get_option( UNAPP_STARTER_OPTION ), get_option( 'blogname' ), get_option( 'page_on_front' ), $states, $nav ? unapp_starter_post_fingerprint( $nav ) : '' ) ) );
}

/** @return int[] Only the current starter's pages and theme-owned shared parts. */
function unapp_business_posts() {
	$active = unapp_launch_active_starter();
	$ids = array_values( $active['pages'] ?? array() );
	foreach ( array( 'header', 'footer' ) as $area ) {
		$part = unapp_get_customised_part( $area );
		if ( $part ) {
			$ids[] = $part->ID;
		}
	}
	return array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );
}

/** Update generated shared fields only. Direct edits remain ordinary core content. */
function unapp_business_update_markup( $markup, $profile, $old, &$changed, &$skipped ) {
	$walk = static function ( $blocks ) use ( &$walk, $profile, $old, &$changed, &$skipped ) {
		foreach ( $blocks as &$block ) {
			$action_baseline = $block['attrs']['metadata']['unappAction']['baseline'] ?? '';
			$action_owned = $action_baseline && hash_equals( $action_baseline, hash( 'sha256', $block['innerHTML'] ) );
			$marker = $block['attrs']['metadata']['unappBusiness'] ?? null;
			if ( is_array( $marker ) && isset( $marker['field'], $marker['baseline'] ) ) {
				$field = $marker['field'];
				if ( ! in_array( $field, array_merge( array_keys( unapp_business_fields() ), array( 'intro', 'contact', 'email_link', 'phone_link' ) ), true ) ) {
					continue;
				}
				if ( unapp_business_value( $profile, $field ) !== unapp_business_value( $old, $field ) ) {
					if ( ! hash_equals( $marker['baseline'], hash( 'sha256', $block['innerHTML'] ) ) ) {
						++$skipped;
					} else {
						if ( 'core/button' === $block['blockName'] ) {
							$contact = str_replace( '_link', '', $field );
							$tags = new WP_HTML_Tag_Processor( $block['innerHTML'] );
							$tags->next_tag( 'a' );
							$url = (string) $tags->get_attribute( 'href' );
							$query = strstr( $url, '?' );
							$tags->set_attribute( 'href', $profile[ $contact ] ? ( 'email' === $contact ? 'mailto:' . $profile['email'] . ( $query ?: '' ) : 'tel:' . preg_replace( '/[^0-9+]/', '', $profile['phone'] ) ) : home_url( '/#business-details' ) );
							$block['innerHTML'] = unapp_map_html_text( $tags->get_updated_html(), static function ( $text ) use ( $old, $profile, $contact ) {
								$demo = 'email' === $contact ? preg_match( '~^[a-z0-9._%+-]+@(?:[a-z0-9.-]+\.example|example\.com)$~i', $text ) : unapp_launch_demo_phone( $text );
								return $text === ( $old[ $contact ] ?? '' ) || $demo ? ( $profile[ $contact ] ?: __( 'Contact details', 'unapp' ) ) : $text;
							} );
						} else {
							$block['innerHTML'] = preg_replace_callback( '~(<p\b[^>]*>).*?(</p>)~s', static function ( $m ) use ( $profile, $field ) { return $m[1] . unapp_business_html( $profile, $field ) . $m[2]; }, $block['innerHTML'], 1 );
						}
						$block['innerContent'] = array( $block['innerHTML'] );
						$block['attrs']['metadata']['unappBusiness'] = unapp_business_marker( $field, $block['innerHTML'] );
						if ( $action_owned ) {
							$block['attrs']['metadata']['unappAction']['baseline'] = hash( 'sha256', $block['innerHTML'] );
						}
						++$changed;
					}
				}
			}
			$block['innerBlocks'] = $walk( $block['innerBlocks'] );
		}
		return $blocks;
	};
	$blocks = $walk( parse_blocks( $markup ) );
	return serialize_blocks( $blocks );
}

/** Save the profile and matching shared fields as one recoverable operation. */
function unapp_save_business_profile( $source, $snapshot ) {
	if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return new WP_Error( 'unapp_profile_forbidden', __( 'You are not allowed to change the business profile.', 'unapp' ) );
	}
	if ( ! array_key_exists( 'service_cards', $source ) && ! array_key_exists( 'service_cards_present', $source ) ) {
		$stored_profile = get_option( UNAPP_BUSINESS_OPTION, array() );
		if ( isset( $stored_profile['service_cards'] ) ) { $source['service_cards'] = $stored_profile['service_cards']; }
	}
	$valid = unapp_business_validate( $source );
	if ( is_wp_error( $valid ) ) {
		return $valid;
	}
	$lock = unapp_acquire_starter_lock();
	if ( is_wp_error( $lock ) ) {
		return $lock;
	}
	$before = array();
	$options = array( UNAPP_BUSINESS_OPTION => get_option( UNAPP_BUSINESS_OPTION, null ), 'blogname' => get_option( 'blogname' ) );
	$result = null;
	try {
		if ( ! hash_equals( unapp_business_snapshot(), $snapshot ) ) {
			return new WP_Error( 'unapp_profile_changed', __( 'The profile or site content changed. Refresh before saving to protect newer edits.', 'unapp' ) );
		}
		$profile = unapp_business_sanitize( $source );
		$old = unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION, array() ) );
		$active = get_option( UNAPP_STARTER_OPTION, array() );
		$action = null;
		if ( in_array( $active['main_action']['type'] ?? '', array( 'call', 'email' ), true ) ) {
			$action = unapp_main_action_target( $active['main_action'], $profile );
			if ( is_wp_error( $action ) ) {
				return new WP_Error( 'unapp_action_missing', __( 'Choose a different main action before removing the contact detail it uses.', 'unapp' ) );
			}
		}
		$changed = 0;
		$skipped = 0;
		foreach ( unapp_business_posts() as $id ) {
			$post = get_post( $id );
			if ( ! $post || ! current_user_can( 'edit_post', $id ) ) {
				continue;
			}
			$count = $changed;
			$content = $post->post_content;
			if ( ! empty( $profile['service_cards'] ) ) {
				$sites = unapp_get_starter_sites(); $site = $sites[ $active['slug'] ?? '' ] ?? array();
				$prepared_cards = unapp_service_cards_prepare( $content, $site, $old );
				$content = unapp_service_cards_update( $prepared_cards, $profile, $changed, $skipped );
			}
			$content = unapp_business_update_markup( $content, $profile, $old, $changed, $skipped );
			$content = unapp_main_action_profile( $content, $profile, $old, $changed );
			if ( $action ) {
				$content = unapp_main_action_update( $content, $action, $changed, $skipped );
			}
			if ( $count === $changed ) {
				continue;
			}
			$before[ $id ] = get_post( $id, ARRAY_A );
			unapp_keep_revision( $id );
			$saved = wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( $content ) ), true );
			if ( is_wp_error( $saved ) || ! $saved || get_post( $id )->post_content !== $content ) {
				$result = new WP_Error( 'unapp_profile_save', __( 'The shared details could not be saved.', 'unapp' ) );
				return $result;
			}
		}
		update_option( UNAPP_BUSINESS_OPTION, $profile, false );
		if ( $profile['name'] ) {
			update_option( 'blogname', $profile['name'] );
		}
		if ( get_option( UNAPP_BUSINESS_OPTION ) !== $profile || ( $profile['name'] && get_option( 'blogname' ) !== sanitize_option( 'blogname', $profile['name'] ) ) ) {
			$result = new WP_Error( 'unapp_profile_save', __( 'The business profile could not be saved.', 'unapp' ) );
			return $result;
		}
		return array( 'updated' => $changed, 'preserved' => $skipped );
	} catch ( Throwable $error ) {
		$result = new WP_Error( 'unapp_profile_save', __( 'The profile update stopped before it finished.', 'unapp' ) );
		return $result;
	} finally {
		if ( is_wp_error( $result ) ) {
			unapp_restore_starter_state( $before, array(), $options );
		}
		if ( get_option( UNAPP_STARTER_LOCK ) === $lock ) {
			delete_option( UNAPP_STARTER_LOCK );
		}
	}
}

/** Add supporting pages while preserving the home page and edited navigation. */
function unapp_expand_starter( $snapshot ) {
	if ( ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'publish_pages' ) ) {
		return new WP_Error( 'unapp_profile_forbidden', __( 'You are not allowed to add starter pages.', 'unapp' ) );
	}
	$lock = unapp_acquire_starter_lock();
	if ( is_wp_error( $lock ) ) {
		return $lock;
	}
	$new = array();
	$before = array();
	$options = array( UNAPP_STARTER_OPTION => get_option( UNAPP_STARTER_OPTION, null ), 'page_for_posts' => get_option( 'page_for_posts' ) );
	$previous_undo = get_option( UNAPP_STARTER_UNDO_OPTION, null );
	$result = null;
	try {
		if ( ! hash_equals( unapp_business_snapshot(), $snapshot ) ) {
			return new WP_Error( 'unapp_profile_changed', __( 'The site changed. Refresh before adding pages.', 'unapp' ) );
		}
		$active = get_option( UNAPP_STARTER_OPTION, array() );
		$site = unapp_get_starter_sites()[ $active['slug'] ?? '' ] ?? null;
		if ( ! $site || 'one-page' !== ( $active['layout'] ?? '' ) || empty( $active['pages']['home'] ) ) {
			return new WP_Error( 'unapp_profile_mode', __( 'This site already has its supporting pages, or no one-page starter is selected.', 'unapp' ) );
		}
		$prepared = unapp_prepare_starter( $site );
		if ( is_wp_error( $prepared ) ) {
			return $prepared;
		}
		$profile = unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION, array() ) );
		foreach ( $site['pages'] as $key => $page ) {
			if ( isset( $active['pages'][ $key ] ) ) {
				continue;
			}
			$content = unapp_business_prepare_markup( unapp_service_cards_prepare( unapp_personalize_starter( $prepared[ $key ], $profile ), $site, $profile ), $profile );
			if ( 'contact' === $key ) {
				$content .= unapp_business_card( $profile );
			}
			$id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $page['title'], 'post_content' => wp_slash( $content ), 'page_template' => 'page-no-title', 'comment_status' => 'closed', 'ping_status' => 'closed' ), true );
			if ( is_wp_error( $id ) || ! $id ) {
				$result = new WP_Error( 'unapp_profile_save', __( 'The additional pages could not be saved.', 'unapp' ) );
				return $result;
			}
			$new[ $id ] = 'page';
			if ( get_post( $id )->post_content !== $content ) {
				$result = new WP_Error( 'unapp_profile_save', __( 'The additional page content could not be saved completely.', 'unapp' ) );
				return $result;
			}
			$active['pages'][ $key ] = $id;
		}
		$nav = get_post( (int) get_option( UNAPP_STARTER_NAV_OPTION ) );
		$menu_updated = false;
		if ( $nav && isset( $active['menu_hash'] ) && hash_equals( $active['menu_hash'], unapp_starter_post_fingerprint( $nav->ID ) ) ) {
			$before[ $nav->ID ] = get_post( $nav->ID, ARRAY_A );
			$saved = unapp_build_starter_menu( $site, $active['pages'] );
			if ( is_wp_error( $saved ) ) {
				$result = $saved;
				return $result;
			}
			$menu_updated = true;
		}
		$active['layout'] = 'full';
		update_option( UNAPP_STARTER_OPTION, $active );
		if ( get_option( UNAPP_STARTER_OPTION ) !== $active ) {
			$result = new WP_Error( 'unapp_profile_save', __( 'The new page list could not be saved.', 'unapp' ) );
			return $result;
		}
		$saved = unapp_record_starter_undo( $before, $new, $options, '', 'pages' );
		if ( is_wp_error( $saved ) ) {
			$result = $saved;
			return $result;
		}
		return array( 'created' => count( $new ), 'menu_updated' => $menu_updated );
	} catch ( Throwable $error ) {
		$result = new WP_Error( 'unapp_profile_save', __( 'Adding pages stopped before it finished.', 'unapp' ) );
		return $result;
	} finally {
		if ( is_wp_error( $result ) ) {
			unapp_restore_starter_state( $before, $new, $options );
			if ( null === $previous_undo ) {
				delete_option( UNAPP_STARTER_UNDO_OPTION );
			} else {
				update_option( UNAPP_STARTER_UNDO_OPTION, $previous_undo, false );
			}
		}
		if ( get_option( UNAPP_STARTER_LOCK ) === $lock ) {
			delete_option( UNAPP_STARTER_LOCK );
		}
	}
}

/** Native Appearance workspace for the shared public profile. */
function unapp_business_url() {
	return admin_url( 'themes.php?page=unapp-business-profile' );
}
function unapp_business_menu() {
	add_theme_page( __( 'Business profile', 'unapp' ), __( 'Business profile', 'unapp' ), 'manage_options', 'unapp-business-profile', 'unapp_render_business_screen' );
}
add_action( 'admin_menu', 'unapp_business_menu' );
function unapp_handle_business_save() {
	check_admin_referer( 'unapp_business_save' );
	$snapshot = unapp_design_field( $_POST, 'snapshot' );
	$result = 'expand' === unapp_design_field( $_POST, 'operation' ) ? unapp_expand_starter( $snapshot ) : unapp_save_business_profile( array_map( 'wp_unslash', array_filter( $_POST, 'is_string' ) ), $snapshot );
	set_transient( 'unapp_business_notice_' . get_current_user_id(), is_wp_error( $result ) ? $result->get_error_message() : $result, 5 * MINUTE_IN_SECONDS );
	wp_safe_redirect( unapp_business_url() );
	exit;
}
add_action( 'admin_post_unapp_business_save', 'unapp_handle_business_save' );
function unapp_render_business_screen() {
	if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to change the business profile.', 'unapp' ), 403 );
	}
	$profile = unapp_business_sanitize( get_option( UNAPP_BUSINESS_OPTION, array() ) );
	$active = get_option( UNAPP_STARTER_OPTION, array() );
	$notice = get_transient( 'unapp_business_notice_' . get_current_user_id() );
	delete_transient( 'unapp_business_notice_' . get_current_user_id() );
	?>
	<div class="wrap unapp-business"><h1><?php esc_html_e( 'Business profile', 'unapp' ); ?></h1>
		<p><a href="<?php echo esc_url( unapp_finish_url() ); ?>"><?php esc_html_e( 'Back to Finish your website', 'unapp' ); ?></a></p>
		<p><?php esc_html_e( 'Enter public details once. Shared fields created by the wizard on your current starter pages and footer follow this profile; directly edited fields and older unlinked content are kept. Review the rest of the starter copy before launch.', 'unapp' ); ?></p>
		<p><a href="<?php echo esc_url( unapp_design_url() ); ?>"><?php esc_html_e( 'Make it yours', 'unapp' ); ?></a> · <a href="<?php echo esc_url( unapp_launch_url() ); ?>"><?php esc_html_e( 'Launch checklist', 'unapp' ); ?></a></p>
		<?php if ( is_string( $notice ) ) : ?><div class="notice notice-error"><p><?php echo esc_html( $notice ); ?></p></div><?php elseif ( is_array( $notice ) ) : ?><div class="notice notice-success"><p><?php if ( isset( $notice['created'] ) ) { esc_html_e( 'Supporting pages added. Your homepage and design were kept. Review Navigation if you customized the menu.', 'unapp' ); } else { /* translators: 1: number of updated shared fields, 2: number of customized fields kept. */ printf( esc_html__( 'Profile saved. %1$d shared fields updated; %2$d customized fields kept.', 'unapp' ), (int) ( $notice['updated'] ?? 0 ), (int) ( $notice['preserved'] ?? 0 ) ); } ?></p></div><?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="unapp_business_save"><input type="hidden" name="snapshot" value="<?php echo esc_attr( unapp_business_snapshot() ); ?>"><?php wp_nonce_field( 'unapp_business_save' ); ?>
			<table class="form-table"><tbody><?php foreach ( unapp_business_fields() as $field => $label ) : ?><tr><th scope="row"><label for="business-<?php echo esc_attr( $field ); ?>"><?php echo esc_html( $label ); ?></label></th><td><?php if ( in_array( $field, array( 'description', 'location', 'services', 'hours' ), true ) ) : ?><textarea id="business-<?php echo esc_attr( $field ); ?>" name="<?php echo esc_attr( $field ); ?>" rows="3" class="large-text"><?php echo esc_textarea( $profile[ $field ] ); ?></textarea><?php else : ?><input class="regular-text" id="business-<?php echo esc_attr( $field ); ?>" type="<?php echo 'email' === $field ? 'email' : ( 'phone' === $field ? 'tel' : 'text' ); ?>" name="<?php echo esc_attr( $field ); ?>" value="<?php echo esc_attr( $profile[ $field ] ); ?>"><?php endif; ?></td></tr><?php endforeach; ?></tbody></table>
			<?php $sites = unapp_get_starter_sites(); unapp_render_service_card_fields( $sites[ $active['slug'] ?? '' ] ?? array(), $profile ); ?>
			<p><button class="button button-primary" type="submit"><?php esc_html_e( 'Save business profile', 'unapp' ); ?></button></p>
		</form>
		<?php if ( 'one-page' === ( $active['layout'] ?? '' ) ) : ?><hr><h2><?php esc_html_e( 'Ready for more pages?', 'unapp' ); ?></h2><p><?php esc_html_e( 'Add this starter’s supporting pages without replacing your homepage, header, footer or styles. An untouched starter menu is updated; an edited menu is left for you to organize in Navigation. The latest page addition can be undone from Starter Sites.', 'unapp' ); ?></p><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="unapp_business_save"><input type="hidden" name="operation" value="expand"><input type="hidden" name="snapshot" value="<?php echo esc_attr( unapp_business_snapshot() ); ?>"><?php wp_nonce_field( 'unapp_business_save' ); ?><button class="button" type="submit"><?php esc_html_e( 'Add supporting pages', 'unapp' ); ?></button></form><?php endif; ?>
	</div>
	<style>.unapp-business{max-width:960px}.unapp-business input,.unapp-business textarea{max-width:100%;box-sizing:border-box}.unapp-business .button{white-space:normal;height:auto;min-height:40px}</style>
	<?php
}
