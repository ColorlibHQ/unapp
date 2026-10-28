<?php
/** Shared setup preparation and a private POST-only personalized homepage. @package Unapp */
defined( 'ABSPATH' ) || exit;

/** Pure preparation: preview and import consume the same generated core blocks. */
function unapp_prepare_starter_choices( $site, $brand ) {
	$valid = unapp_business_validate( $brand );
	if ( is_wp_error( $valid ) ) { return $valid; }
	if ( ! empty( $brand['logo'] ) && ( ! wp_attachment_is_image( $brand['logo'] ) || ! current_user_can( 'read_post', $brand['logo'] ) ) ) {
		return new WP_Error( 'unapp_setup_logo', __( 'Choose an image from your Media Library for the logo.', 'unapp' ) );
	}
	if ( 'one-page' === ( $brand['layout'] ?? '' ) ) {
		$site = unapp_one_page_site( $site );
		$brand['profile_enabled'] = true;
	}
	if ( is_array( $brand['sections'] ?? null ) || ! empty( $brand['main_action'] ) ) {
		$brand['profile_enabled'] = true;
	}
	foreach ( array( 'colors' => 'colors', 'type' => 'type' ) as $field => $target ) {
		if ( ! empty( $brand[ $field ] ) ) { $site[ $target ] = $brand[ $field ]; }
	}
	$pages = unapp_prepare_starter( $site );
	if ( is_wp_error( $pages ) ) { return $pages; }
	if ( ! empty( $brand['service_cards'] ) ) { $brand['profile_enabled'] = true; }
	$business = unapp_starter_business_from( $brand );
	$service_spec = unapp_service_card_spec( $site );
	foreach ( $business['service_cards'] ?? array() as $index => $card ) {
		if ( ! isset( $service_spec['cards'][ $index ] ) ) { return new WP_Error( 'unapp_service_slots', __( 'Choose service cards offered by this starter, or leave them blank.', 'unapp' ) ); }
	}
	$sections = unapp_section_home( $site, $brand['sections'] ?? null );
	if ( is_wp_error( $sections ) ) { return $sections; }
	$pages['home'] = $sections['markup'];
	foreach ( $pages as $key => $markup ) {
		$pages[ $key ] = unapp_service_cards_prepare( unapp_personalize_starter( $markup, $business ), $site, $business );
		if ( ! empty( $brand['profile_enabled'] ) ) {
			$pages[ $key ] = unapp_business_prepare_markup( $pages[ $key ], $business, 'home' === $key );
			if ( in_array( $key, array( 'home', 'contact' ), true ) ) {
				$pages[ $key ] .= unapp_business_card( $business, 'home' === $key ? 'business-details' : '' );
			}
		}
		if ( 'home' === $key ) {
			$pages[ $key ] = unapp_section_repair_links( unapp_main_action_mark( $pages[ $key ] ), $sections['removed'] );
		}
	}
	$site['business'] = $business;
	$site['profile_enabled'] = ! empty( $brand['profile_enabled'] );
	if ( ! empty( $site['one_page'] ) ) { $site['section_links'] = unapp_one_page_links( $pages['home'] ); }
	if ( ! empty( $brand['main_action'] ) ) {
		$target = unapp_main_action_target( $brand['main_action'], $business, array( 'contact_url' => home_url( '/#business-details' ) ) );
		if ( is_wp_error( $target ) ) { return $target; }
		$site['main_action'] = $target;
		$site['main_action_choice'] = array( 'type' => $target['type'], 'label' => wp_html_excerpt( sanitize_text_field( unapp_design_field( $brand['main_action'], 'label' ) ), 60, '' ), 'url' => in_array( $target['type'], array( 'book', 'quote' ), true ) ? esc_url_raw( unapp_design_field( $brand['main_action'], 'url' ), array( 'http', 'https' ) ) : '' );
		if ( 'starter' !== $target['type'] ) {
			$changed = 0; $kept = 0;
			$pages['home'] = unapp_main_action_update( $pages['home'], $target, $changed, $kept );
		}
	}
	return array( 'site' => $site, 'pages' => $pages, 'business' => $business );
}

/** Prepare the current draft, independently of any previously applied starter. */
function unapp_personalized_preview_data( $slug, $brand ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return new WP_Error( 'unapp_preview_forbidden', __( 'You are not allowed to preview the site design.', 'unapp' ) );
	}
	$sites = unapp_get_starter_sites();
	if ( ! isset( $sites[ $slug ] ) ) {
		return new WP_Error( 'unapp_unknown_starter', __( 'That starter site does not exist.', 'unapp' ) );
	}
	$brand = wp_parse_args( $brand, array( 'tagline' => html_entity_decode( get_option( 'blogdescription' ), ENT_QUOTES, 'UTF-8' ), 'logo' => 0, 'logo_was' => 0 ) );
	$draft = unapp_prepare_starter_choices( $sites[ $slug ], $brand );
	if ( is_wp_error( $draft ) ) { return $draft; }
	$site = $draft['site'];
	$menu = '';
	if ( ! empty( $site['one_page'] ) ) {
		$links = array_slice( $site['section_links'], 0, 4, true );
		if ( isset( $site['section_links']['business-details'] ) ) { $links['business-details'] = __( 'Contact', 'unapp' ); }
		foreach ( $links as $anchor => $label ) {
			$menu .= '<!-- wp:navigation-link ' . serialize_block_attributes( array( 'label' => $label, 'url' => '#' . $anchor ) ) . ' /-->';
		}
	} else {
		foreach ( $site['pages'] + array( 'blog' => array( 'title' => __( 'Blog', 'unapp' ) ) ) as $page ) {
			$menu .= '<!-- wp:navigation-link ' . serialize_block_attributes( array( 'label' => $page['title'], 'url' => '#unapp-preview-notice' ) ) . ' /-->';
		}
	}
	$inline_menu = static function ( $markup ) use ( $menu ) {
		$walk = static function ( $blocks ) use ( &$walk, $menu ) {
			foreach ( $blocks as &$block ) {
				if ( 'core/navigation' === $block['blockName'] ) {
					unset( $block['attrs']['ref'] );
					$block['innerBlocks'] = parse_blocks( $menu );
					$block['innerContent'] = array_fill( 0, count( $block['innerBlocks'] ), null );
					$block['innerHTML'] = '';
				} else { $block['innerBlocks'] = $walk( $block['innerBlocks'] ); }
			}
			return $blocks;
		};
		return serialize_blocks( $walk( parse_blocks( $markup ) ) );
	};
	$header = unapp_starter_header_markup( $site );
	if ( is_wp_error( $header ) ) { return $header; }
	$footer = unapp_starter_footer_markup( $site );
	$markup = '<!-- wp:group {"tagName":"header","layout":{"type":"default"}} --><header class="wp-block-group">' . $inline_menu( $header ) . '</header><!-- /wp:group -->'
		. '<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} --><main class="wp-block-group">' . $draft['pages']['home'] . '</main><!-- /wp:group -->'
		. '<!-- wp:group {"tagName":"footer","layout":{"type":"default"}} --><footer class="wp-block-group">' . $inline_menu( $footer ) . '</footer><!-- /wp:group -->';
	$variation = unapp_compose_variation( $site['colors'] ?? '', $site['type'] ?? '' );
	if ( ! $variation ) { $variation = wp_json_file_decode( get_theme_file_path( 'styles/' . $site['style'] . '.json' ), array( 'associative' => true ) ); }
	unset( $variation['$schema'], $variation['title'], $variation['slug'] );
	$variation = unapp_key_presets_by_origin( $variation );
	$variation['version'] = WP_Theme_JSON::LATEST_SCHEMA;
	return array( 'title' => $site['title'], 'markup' => $markup, 'variation' => $variation, 'brand' => $brand, 'personalized' => true, 'action' => $site['main_action'] ?? array( 'type' => 'starter', 'label' => '', 'url' => '' ) );
}

/** Remove live navigation and submissions from the rendered homepage preview. */
function unapp_personalized_preview_links( $html ) {
	$tags = new WP_HTML_Tag_Processor( $html );
	while ( $tags->next_tag() ) {
		if ( 'A' === $tags->get_tag() ) {
			$url = (string) $tags->get_attribute( 'href' );
			$fragment = wp_parse_url( $url, PHP_URL_FRAGMENT );
			$base = strtok( $url, '#' );
			if ( $fragment && false !== strpos( $html, 'id="' . esc_attr( $fragment ) . '"' ) && ( 0 === strpos( $url, '#' ) || untrailingslashit( $base ) === untrailingslashit( home_url( '/' ) ) ) ) {
				$tags->set_attribute( 'href', '#' . $fragment );
			} elseif ( ! $fragment && untrailingslashit( $url ) === untrailingslashit( home_url( '/' ) ) ) {
				$tags->set_attribute( 'href', '#unapp-preview-top' );
			} else {
				$tags->set_attribute( 'data-unapp-preview-destination', $url );
				$tags->set_attribute( 'title', $url );
				$tags->set_attribute( 'href', '#unapp-preview-notice' );
				$tags->set_attribute( 'inert', '' );
				$tags->set_attribute( 'aria-disabled', 'true' );
			}
			$tags->remove_attribute( 'target' );
		} elseif ( 'FORM' === $tags->get_tag() ) {
			$tags->set_attribute( 'inert', '' );
			$tags->set_attribute( 'aria-disabled', 'true' );
		} elseif ( 'BUTTON' === $tags->get_tag() && false === strpos( (string) $tags->get_attribute( 'class' ), 'wp-block-navigation' ) ) {
			$tags->set_attribute( 'inert', '' );
			$tags->set_attribute( 'aria-disabled', 'true' );
		}
	}
	return $tags->get_updated_html();
}

function unapp_handle_personalized_preview() {
	if ( ! current_user_can( 'edit_theme_options' ) ) { wp_die( esc_html__( 'You are not allowed to preview the site design.', 'unapp' ), 403 ); }
	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) { wp_die( esc_html__( 'Submit your setup choices to preview them.', 'unapp' ), 405 ); }
	check_admin_referer( 'unapp_personalized_preview' );
	$data = unapp_personalized_preview_data( sanitize_key( unapp_design_field( $_POST, 'starter' ) ), unapp_wizard_brand_from( $_POST ) );
	nocache_headers();
	header( 'X-Robots-Tag: noindex, nofollow', true );
	header( 'Referrer-Policy: no-referrer', true );
	if ( is_wp_error( $data ) ) { wp_die( esc_html( $data->get_error_message() ) ); }
	unapp_render_starter_preview_document( $data );
	exit;
}
add_action( 'admin_post_unapp_personalized_preview', 'unapp_handle_personalized_preview' );

/** Real POST form works without JavaScript; enhancement loads and scales it. */
function unapp_render_personalized_preview( $starter, $brand ) {
	wp_enqueue_script( 'unapp-setup-preview', get_theme_file_uri( 'assets/js/setup-preview.js' ), array(), UNAPP_VERSION, true );
	?>
	<section class="unapp-setup-preview unapp-wizard__panel"><h3><?php esc_html_e( 'Preview your website', 'unapp' ); ?></h3><p><?php esc_html_e( 'Your selected sections, details, logo, colors and main action appear here before you build. This private homepage preview does not change the live site. Supporting-page links and contact actions are inactive.', 'unapp' ); ?></p>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" target="unapp-personalized-preview" class="unapp-setup-preview__form"><input type="hidden" name="action" value="unapp_personalized_preview"><?php wp_nonce_field( 'unapp_personalized_preview' ); unapp_wizard_brand_fields( $starter, $brand ); ?><p><button class="button" type="submit"><?php esc_html_e( 'Reload personalized preview', 'unapp' ); ?></button></p></form>
	<fieldset><legend><?php esc_html_e( 'Preview width', 'unapp' ); ?></legend><?php foreach ( array( 'desktop' => __( 'Desktop', 'unapp' ), 'tablet' => __( 'Tablet', 'unapp' ), 'mobile' => __( 'Mobile', 'unapp' ) ) as $key => $label ) : ?><label><input type="radio" name="setup_preview_width" value="<?php echo esc_attr( $key ); ?>" <?php checked( 'desktop', $key ); ?>> <?php echo esc_html( $label ); ?></label> <?php endforeach; ?></fieldset>
	<div class="unapp-setup-preview__canvas"><iframe name="unapp-personalized-preview" title="<?php esc_attr_e( 'Your personalized homepage preview', 'unapp' ); ?>" sandbox="allow-scripts allow-same-origin" referrerpolicy="no-referrer" src="about:blank"></iframe></div>
	<noscript><p><?php esc_html_e( 'Choose Reload personalized preview to load your choices. Device-width scaling requires JavaScript.', 'unapp' ); ?></p></noscript></section>
	<style>.unapp-setup-preview fieldset{margin:16px 0}.unapp-setup-preview label{display:inline-block;margin:0 16px 8px 0}.unapp-setup-preview__canvas{position:relative;width:100%;height:560px;overflow:hidden;background:#fff;border:1px solid #c3c4c7;box-sizing:border-box}.unapp-setup-preview iframe{position:absolute;inset:0;border:0;width:100%;height:100%;transform-origin:top left}.unapp-setup-preview p{overflow-wrap:anywhere}</style>
	<?php
}
