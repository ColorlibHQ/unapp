<?php
/** Optional, read-only launch recommendations; separate from required progress. @package Unapp */
defined( 'ABSPATH' ) || exit;

/** Resolve saved native references without rendering blocks or fetching URLs. */
function unapp_recommend_reference( $block, $edit ) {
	$name = $block['blockName']; $attrs = $block['attrs'];
	if ( 'core/pattern' === $name ) {
		$key = 'pattern:' . ( $attrs['slug'] ?? '' );
		$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( $attrs['slug'] ?? '' );
		return array( 'key' => $key, 'content' => $pattern['content'] ?? '', 'edit' => $edit );
	}
	if ( in_array( $name, array( 'core/block', 'core/navigation' ), true ) && ! empty( $attrs['ref'] ) ) {
		$post = get_post( absint( $attrs['ref'] ) );
		$type = 'core/block' === $name ? 'wp_block' : 'wp_navigation';
		return array( 'key' => 'post:' . absint( $attrs['ref'] ), 'content' => $post && $type === $post->post_type && 'publish' === $post->post_status ? $post->post_content : '', 'edit' => 'wp_navigation' === $type ? add_query_arg( 'path', '/navigation', admin_url( 'site-editor.php' ) ) : ( $post && current_user_can( 'edit_post', $post->ID ) ? get_edit_post_link( $post->ID, 'raw' ) : $edit ) );
	}
	if ( 'core/template-part' === $name && ! empty( $attrs['slug'] ) ) {
		$id = ( $attrs['theme'] ?? get_stylesheet() ) . '//' . $attrs['slug'];
		$part = get_block_template( $id, 'wp_template_part' );
		return array( 'key' => 'part:' . $id, 'content' => $part ? $part->content : '', 'edit' => add_query_arg( array( 'postType' => 'wp_template_part', 'postId' => $id ), admin_url( 'site-editor.php' ) ) );
	}
	return null;
}

/** A hint, never an HTTP availability check or a certificate of link health. */
function unapp_recommend_nav_reason( $attrs ) {
	$url = is_string( $attrs['url'] ?? null ) ? trim( html_entity_decode( $attrs['url'], ENT_QUOTES, 'UTF-8' ) ) : '';
	$id = absint( $attrs['id'] ?? 0 );
	$kind = $attrs['kind'] ?? '';
	$post = null;
	if ( $id && 'post-type' === $kind ) {
		$post = get_post( $id );
		if ( ! $post || ! is_post_publicly_viewable( $post ) ) { return __( 'The linked content is missing or is not publicly viewable.', 'unapp' ); }
	} elseif ( $id && 'taxonomy' === $kind ) {
		$term = get_term( $id, $attrs['type'] ?? '' );
		if ( ! $term || is_wp_error( $term ) ) { return __( 'The linked category or term is unavailable.', 'unapp' ); }
	}
	if ( '' === $url || '#' === $url ) { return __( 'Choose a destination for this menu item.', 'unapp' ); }
	$scheme = strtolower( (string) wp_parse_url( $url, PHP_URL_SCHEME ) );
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ( $scheme && ! in_array( $scheme, array( 'http', 'https', 'mailto', 'tel' ), true ) ) || ( $host && preg_match( '/(?:^|\.)(?:example\.(?:com|org|net)|example|invalid)$/i', $host ) ) ) { return __( 'Review this placeholder or unsupported destination.', 'unapp' ); }
	if ( ( 'mailto' === $scheme && ! is_email( explode( '?', substr( $url, 7 ), 2 )[0] ) ) || ( 'tel' === $scheme && ! preg_match( '/[0-9]/', substr( $url, 4 ) ) ) ) { return __( 'Check this email or telephone destination.', 'unapp' ); }
	$home = wp_parse_url( home_url( '/' ) );
	$parts = wp_parse_url( $url );
	if ( false === $parts || ( in_array( $scheme, array( 'http', 'https' ), true ) && ! $host ) ) { return __( 'Review this destination’s format.', 'unapp' ); }
	// Never resolve external hosts, protocols or ports, and leave custom routes to owners.
	$local = ! $scheme || in_array( $scheme, array( 'http', 'https' ), true );
	$local = $local && ( ! $host || strtolower( $host ) === strtolower( $home['host'] ) ) && ( $parts['port'] ?? null ) === ( $home['port'] ?? null );
	if ( ! $local ) { return ''; }
	parse_str( $parts['query'] ?? '', $query );
	foreach ( array( 'page_id', 'p' ) as $parameter ) {
		if ( isset( $query[ $parameter ] ) && is_scalar( $query[ $parameter ] ) && ctype_digit( (string) $query[ $parameter ] ) && (int) $query[ $parameter ] > 0 ) {
			$target = get_post( (int) $query[ $parameter ] );
			if ( ! $target || ! is_post_publicly_viewable( $target ) ) { return __( 'The linked content is missing or is not publicly viewable.', 'unapp' ); }
		}
	}
	$absolute = $host ? $url : ( 0 === strpos( $url, '#' ) || 0 === strpos( $url, '?' ) ? home_url( '/' ) . $url : ( 0 === strpos( $url, '/' ) ? ( $home['scheme'] . '://' . $home['host'] . ( isset( $home['port'] ) ? ':' . $home['port'] : '' ) . $url ) : home_url( '/' . $url ) ) );
	if ( ! $post || ! empty( $parts['fragment'] ) ) {
		$resolved = url_to_postid( $absolute );
		$post = $resolved ? get_post( $resolved ) : $post;
	}
	if ( $post && ! is_post_publicly_viewable( $post ) ) { return __( 'The linked content is not publicly viewable.', 'unapp' ); }
	if ( $post && ! empty( $parts['fragment'] ) ) {
		$anchor = rawurldecode( $parts['fragment'] );
		$anchors = unapp_recommend_anchors( $post->post_content );
		if ( ! in_array( $anchor, $anchors, true ) ) { return __( 'The section anchor was not found in saved content. Check this link on the live page; plugins may add it dynamically.', 'unapp' ); }
	}
	return '';
}

/** Saved anchors, including shared patterns and synced content, with cycle guards. */
function unapp_recommend_anchors( $markup, $seen = array() ) {
	if ( count( $seen ) > 8 ) { return array(); }
	$anchors = array();
	foreach ( parse_blocks( $markup ) as $block ) {
		if ( ! empty( $block['attrs']['anchor'] ) ) { $anchors[] = $block['attrs']['anchor']; }
		$tags = new WP_HTML_Tag_Processor( $block['innerHTML'] );
		while ( $tags->next_tag() ) { $id = $tags->get_attribute( 'id' ); if ( is_string( $id ) ) { $anchors[] = $id; } }
		$ref = unapp_recommend_reference( $block, '' );
		if ( $ref && ! in_array( $ref['key'], $seen, true ) ) { $anchors = array_merge( $anchors, unapp_recommend_anchors( $ref['content'], array_merge( $seen, array( $ref['key'] ) ) ) ); }
		$anchors = array_merge( $anchors, unapp_recommend_anchors( serialize_blocks( $block['innerBlocks'] ), $seen ) );
	}
	return array_unique( $anchors );
}

/** Inspect navigation attributes and image HTML; ordinary page links stay out of scope. */
function unapp_recommend_scan( $markup, $source, &$findings, $seen = array(), $location = '' ) {
	if ( count( $seen ) > 8 ) { return; }
	foreach ( parse_blocks( $markup ) as $index => $block ) {
		$name = $block['blockName']; $attrs = $block['attrs'];
		$position = $location . '.' . $index;
		if ( in_array( $name, array( 'core/navigation-link', 'core/navigation-submenu' ), true ) && ( 'core/navigation-link' === $name || ! empty( $attrs['url'] ) || ! empty( $attrs['id'] ) ) ) {
			$reason = unapp_recommend_nav_reason( $attrs );
			if ( $reason ) {
				$label = wp_strip_all_tags( is_string( $attrs['label'] ?? null ) ? $attrs['label'] : '' ) ?: __( 'Menu item', 'unapp' );
				$key = hash( 'sha256', $source['edit'] . '|' . $label . '|' . ( $attrs['url'] ?? '' ) . '|' . $reason );
				$findings['navigation'][ $key ] = array( 'title' => $source['title'] . ' — ' . $label, 'reason' => $reason, 'edit' => $source['edit'] );
			}
		}
		$tags = new WP_HTML_Tag_Processor( $block['innerHTML'] );
		$image_index = 0;
		while ( $tags->next_tag( 'img' ) ) {
			++$image_index;
			$alt = $tags->get_attribute( 'alt' );
			$src = (string) $tags->get_attribute( 'src' );
			$decorative = 'true' === $tags->get_attribute( 'aria-hidden' ) || in_array( $tags->get_attribute( 'role' ), array( 'presentation', 'none' ), true );
			$bundled_icon = 0 === strpos( $src, trailingslashit( get_parent_theme_file_uri( 'assets/images/icons' ) ) ) || 0 === strpos( $src, trailingslashit( get_theme_file_uri( 'assets/images/icons' ) ) );
			if ( $decorative || ( '' === $alt && ( 'core/cover' === $name || $bundled_icon ) ) || ( is_string( $alt ) && '' !== trim( $alt ) ) ) { continue; }
			$key = hash( 'sha256', $source['edit'] . '|' . $position . '|' . $image_index );
			$filename = wp_basename( (string) wp_parse_url( $src, PHP_URL_PATH ) );
			$image_title = $source['title'] . ' — ' . ( $filename ? mb_substr( rawurldecode( $filename ), 0, 80 ) : __( 'Image', 'unapp' ) );
			$findings['images'][ $key ] = array( 'title' => $image_title, 'reason' => null === $alt ? __( 'An image has no alternative-text attribute. Add a description if it conveys information, or empty alternative text if it is decorative.', 'unapp' ) : __( 'An image has empty alternative text. This is correct for decoration; add a description if the image conveys information.', 'unapp' ), 'edit' => $source['edit'] );
		}
		$ref = unapp_recommend_reference( $block, $source['edit'] );
		if ( $ref ) {
			if ( ! $ref['content'] || in_array( $ref['key'], $seen, true ) ) {
				if ( 'core/navigation' !== $name ) { continue; }
				$findings['navigation'][ hash( 'sha256', $ref['key'] . $source['edit'] ) ] = array( 'title' => $source['title'], 'reason' => __( 'A shared reference is unavailable or circular. Review it in WordPress.', 'unapp' ), 'edit' => $source['edit'] );
			} else {
				$nested = array( 'title' => $source['title'], 'edit' => $ref['edit'] );
				unapp_recommend_scan( $ref['content'], $nested, $findings, array_merge( $seen, array( $ref['key'] ) ), $ref['key'] );
			}
		}
		unapp_recommend_scan( serialize_blocks( $block['innerBlocks'] ), $source, $findings, $seen, $position );
	}
}

/** Recommendations never enter launch totals, snapshots or saved reviews. */
function unapp_launch_recommendations() {
	if ( ! current_user_can( 'edit_theme_options' ) ) { return array(); }
	$findings = array( 'navigation' => array(), 'images' => array() );
	$sources = unapp_launch_sources();
	if ( ! $sources ) {
		foreach ( array( 'header', 'footer' ) as $area ) {
			$sources[ $area ] = array( 'title' => 'header' === $area ? __( 'Header', 'unapp' ) : __( 'Footer', 'unapp' ), 'content' => unapp_design_part_content( $area ), 'edit' => unapp_design_editor_url( $area ) );
		}
		$front_id = (int) get_option( 'page_on_front' );
		$front = 'page' === get_option( 'show_on_front' ) && $front_id > 0 ? get_post( $front_id ) : null;
		if ( $front && current_user_can( 'edit_post', $front->ID ) ) { $sources[] = array( 'title' => $front->post_title, 'content' => $front->post_content, 'edit' => get_edit_post_link( $front->ID, 'raw' ) ); }
	}
	foreach ( $sources as $source ) { unapp_recommend_scan( $source['content'], $source, $findings ); }
	$icon_id = (int) get_option( 'site_icon' );
	$icon = $icon_id && ! in_array( get_post_status( $icon_id ), array( 'trash', 'auto-draft' ), true ) && 'attachment' === get_post_type( $icon_id ) && wp_attachment_is_image( $icon_id ) && (bool) wp_get_attachment_url( $icon_id );
	$settings = current_user_can( 'manage_options' );
	$navigation = add_query_arg( 'path', '/navigation', admin_url( 'site-editor.php' ) );
	return array(
		'icon' => array( 'title' => __( 'Site icon', 'unapp' ), 'status' => $icon ? __( 'Configured', 'unapp' ) : __( 'Optional branding', 'unapp' ), 'description' => __( 'A site icon helps visitors recognize your browser tab. Choose a square image, ideally at least 512 × 512 pixels, in WordPress General Settings.', 'unapp' ), 'edit' => $settings ? admin_url( 'options-general.php' ) : '', 'action' => __( 'Open General Settings', 'unapp' ), 'findings' => array() ),
		'navigation' => array( 'title' => __( 'Navigation destinations', 'unapp' ), 'status' => $findings['navigation'] ? __( 'Worth reviewing', 'unapp' ) : __( 'No obvious saved-link issues', 'unapp' ), 'description' => __( 'Reviews saved native menu links for unfinished destinations, unavailable linked content and missing section anchors. External links, custom routes and dynamically generated menus still need a manual visit; no websites are contacted.', 'unapp' ), 'edit' => $navigation, 'action' => __( 'Edit navigation in WordPress', 'unapp' ), 'findings' => $findings['navigation'] ),
		'visibility' => array( 'title' => __( 'Search visibility', 'unapp' ), 'status' => ! get_option( 'blog_public' ) ? __( 'Indexing is discouraged', 'unapp' ) : __( 'Indexing is not discouraged', 'unapp' ), 'description' => __( 'Keeping indexing discouraged is reasonable while building or on a staging site. Review this setting when your public site is ready. Allowing indexing does not guarantee a search-engine listing or make a private site public.', 'unapp' ), 'edit' => $settings ? admin_url( 'options-reading.php' ) : '', 'action' => __( 'Review Reading settings', 'unapp' ), 'findings' => array() ),
		'images' => array( 'title' => __( 'Image descriptions', 'unapp' ), 'status' => $findings['images'] ? __( 'Confirm image purpose', 'unapp' ) : __( 'No missing descriptions found in saved image markup', 'unapp' ), 'description' => __( 'Meaningful images need descriptive alternative text. Empty text is correct for decorative images, so these are review suggestions, not errors. Decorative Cover backgrounds, bundled icons and explicitly hidden/presentational images are excluded. Dynamic images and the quality of existing descriptions need your own review.', 'unapp' ), 'edit' => unapp_content_guide_url(), 'action' => __( 'Open guided image editing', 'unapp' ), 'findings' => $findings['images'] ),
	);
}

function unapp_render_launch_recommendations() {
	$checks = unapp_launch_recommendations();
	if ( ! $checks ) { return; }
	?>
	<section class="unapp-launch__panel" id="unapp-recommended-checks"><details><summary><strong><?php esc_html_e( 'Recommended checks (optional)', 'unapp' ); ?></strong></summary>
	<p><?php esc_html_e( 'Helpful finishing touches to review when they suit your site. These are optional and do not affect launch progress. They never publish content or change settings automatically.', 'unapp' ); ?></p>
	<?php foreach ( $checks as $key => $check ) : ?><div class="unapp-launch__recommendation"><h3><?php echo esc_html( $check['title'] ); ?></h3><p><strong><?php echo esc_html( $check['status'] ); ?></strong></p><p><?php echo esc_html( $check['description'] ); ?></p>
	<?php if ( $check['edit'] ) : ?><p><a href="<?php echo esc_url( $check['edit'] ); ?>"><?php echo esc_html( $check['action'] ); ?></a></p><?php elseif ( in_array( $key, array( 'icon', 'visibility' ), true ) ) : ?><p><?php esc_html_e( 'An administrator can review this WordPress setting.', 'unapp' ); ?></p><?php endif; ?>
	<?php if ( $check['findings'] ) : ?><details><summary><?php /* translators: %d: saved findings to review. */ printf( esc_html__( 'Review suggestions (%d)', 'unapp' ), count( $check['findings'] ) ); ?></summary><ul><?php foreach ( $check['findings'] as $finding ) : ?><li><a href="<?php echo esc_url( $finding['edit'] ); ?>"><?php echo esc_html( $finding['title'] ); ?></a> — <?php echo esc_html( $finding['reason'] ); ?></li><?php endforeach; ?></ul></details><?php endif; ?></div><?php endforeach; ?>
	</details></section>
	<?php
}
