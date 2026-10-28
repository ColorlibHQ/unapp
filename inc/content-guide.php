<?php
/** Optional homepage sections and a guided pass through native starter pages. @package Unapp */
defined( 'ABSPATH' ) || exit;
const UNAPP_CONTENT_REVIEWS = 'unapp_content_reviews';

/** Include nested anchors when an entire section is omitted. */
function unapp_section_anchors( $blocks ) {
	$anchors = array();
	foreach ( $blocks as $block ) {
		if ( ! empty( $block['attrs']['anchor'] ) ) { $anchors[] = $block['attrs']['anchor']; }
		$anchors = array_merge( $anchors, unapp_section_anchors( $block['innerBlocks'] ) );
	}
	return $anchors;
}

/** The starter composition is the source of truth, including translated titles. */
function unapp_section_choices( $site ) {
	$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( $site['home'] );
	$choices = array();
	$last_action = '';
	foreach ( parse_blocks( $pattern['content'] ?? '' ) as $block ) {
		$slug = $block['attrs']['slug'] ?? '';
		if ( 'core/pattern' !== $block['blockName'] || ! $slug ) {
			continue;
		}
		$source = WP_Block_Patterns_Registry::get_instance()->get_registered( $slug );
		$key = sanitize_key( str_replace( 'unapp/', '', $slug ) );
		$title = $source['title'] ?? $key;
		if ( preg_match( '/hero|masthead/', $key ) ) {
			$title = __( 'Introduction', 'unapp' );
		} elseif ( preg_match( '/(?:^|-)cta(?:-|$)/', $key ) ) {
			$title = __( 'Main action', 'unapp' );
		} elseif ( false !== strpos( $key, 'faq' ) ) {
			$title = __( 'Questions and answers', 'unapp' );
		} elseif ( false !== strpos( $key, 'testimonial' ) ) {
			$title = __( 'Testimonials', 'unapp' );
		} elseif ( 'logo-cloud' === $key ) {
			$title = __( 'Logos', 'unapp' );
		} elseif ( 'screens' === $key ) {
			$title = __( 'App screenshots', 'unapp' );
		}
		$choices[ $key ] = array( 'pattern' => $slug, 'title' => preg_replace( '/:.*$/u', '', $title ), 'required' => false );
		if ( unapp_design_find_block( parse_blocks( unapp_get_pattern_markup( $slug ) ), 'core/button' ) ) {
			$last_action = $key;
		}
	}
	foreach ( array_filter( array( array_key_first( $choices ), array_key_last( $choices ), $last_action ) ) as $key ) {
		$choices[ $key ]['required'] = true;
	}
	return $choices;
}

/** Name new root groups for WordPress List View; do not rewrite existing pages. */
function unapp_section_home( $site, $selected = null ) {
	$choices = unapp_section_choices( $site );
	if ( null !== $selected && ( ! is_array( $selected ) || array_filter( $selected, static function ( $key ) use ( $choices ) { return ! is_string( $key ) || ! isset( $choices[ $key ] ); } ) ) ) {
		return new WP_Error( 'unapp_sections_invalid', __( 'Choose sections belonging to this starter, then try again.', 'unapp' ) );
	}
	$markup = '';
	$removed = array();
	foreach ( $choices as $key => $choice ) {
		$blocks = parse_blocks( unapp_get_pattern_markup( $choice['pattern'] ) );
		if ( null !== $selected && ! $choice['required'] && ! in_array( $key, $selected, true ) ) {
			$removed = array_merge( $removed, unapp_section_anchors( $blocks ) );
			continue;
		}
		$part = 0;
		foreach ( $blocks as &$block ) {
			if ( $block['blockName'] ) {
				$block['attrs']['metadata']['name'] = $choice['title'];
				$block['attrs']['metadata']['unappSection'] = $key . ( $part ? '-' . $part : '' );
				++$part;
			}
		}
		unset( $block );
		$markup .= serialize_blocks( $blocks );
	}
	return array( 'markup' => $markup, 'removed' => array_diff( $removed, unapp_section_anchors( parse_blocks( $markup ) ) ) );
}

/** Repair only same-homepage links to omitted section anchors. */
function unapp_section_repair_links( $markup, $removed ) {
	$is_removed = static function ( $url ) use ( $removed ) {
		$url = html_entity_decode( $url, ENT_QUOTES, 'UTF-8' );
		$fragment = wp_parse_url( $url, PHP_URL_FRAGMENT );
		$base = strtok( $url, '#' );
		return in_array( $fragment, $removed, true ) && ( 0 === strpos( $url, '#' ) || untrailingslashit( $base ) === untrailingslashit( home_url( '/' ) ) );
	};
	$walk = static function ( $blocks ) use ( &$walk, $is_removed ) {
		$out = array();
		foreach ( $blocks as $block ) {
			if ( 'core/button' === $block['blockName'] ) {
				$tags = new WP_HTML_Tag_Processor( $block['innerHTML'] );
				if ( $tags->next_tag( 'a' ) && $is_removed( (string) $tags->get_attribute( 'href' ) ) ) {
					if ( empty( $block['attrs']['metadata']['unappAction'] ) ) {
						continue;
					}
					$target = array( 'label' => __( 'Contact us', 'unapp' ), 'url' => home_url( '/#business-details' ) );
					$block['innerHTML'] = unapp_main_action_button( $block['innerHTML'], $target );
					$block['innerContent'] = array( $block['innerHTML'] );
					$block['attrs']['metadata']['unappAction'] = array( 'baseline' => hash( 'sha256', $block['innerHTML'] ), 'original' => $target );
				}
			}
			// Preserve the parent's serialized HTML while omitting removed child slots.
			$children = array();
			$content = array();
			$index = 0;
			foreach ( $block['innerContent'] as $chunk ) {
				if ( null === $chunk ) {
					$child = $walk( array( $block['innerBlocks'][ $index++ ] ) );
					if ( $child ) {
						$children[] = $child[0];
						$content[] = null;
					}
				} else {
					$content[] = preg_replace_callback( '~<a\b[^>]*href=["\']([^"\']*)["\'][^>]*>(.*?)</a>~s', static function ( $m ) use ( $is_removed ) { return $is_removed( $m[1] ) ? $m[2] : $m[0]; }, $chunk );
				}
			}
			$block['innerBlocks'] = $children;
			$block['innerContent'] = $content;
			$block['innerHTML'] = implode( '', array_filter( $content, 'is_string' ) );
			$out[] = $block;
		}
		return $out;
	};
	return serialize_blocks( $walk( parse_blocks( $markup ) ) );
}

function unapp_content_guide_url( $section = '', $page_id = 0 ) {
	$url = admin_url( 'themes.php?page=unapp-content-guide' );
	if ( $page_id ) { $url = add_query_arg( 'page_id', absint( $page_id ), $url ); }
	return $section ? add_query_arg( 'section', $section, $url ) : $url;
}

/** Keep each step focused on the kind of content in that section. */
function unapp_content_guide_prompt( $section ) {
	$key = strtolower( $section['id'] . ' ' . $section['title'] . ' ' . ( $section['page_key'] ?? '' ) );
	if ( 0 === $section['root'] && empty( $section['supporting'] ) ) {
		return __( 'Start with the headline: say what you offer and who it is for. Make the short description and main button match that promise.', 'unapp' );
	}
	if ( preg_match( '/business-details|contact|hours|booking|venue|location/', $key ) ) {
		return __( 'Check public email, phone, address and opening hours. Use Business profile for shared details, and follow every contact or booking link.', 'unapp' );
	}
	if ( ! empty( $section['supporting'] ) && 'about' === ( $section['page_key'] ?? '' ) ) {
		return __( 'Tell your real story: explain who you are, who you help and how you work. Replace sample people, photos and credentials with information you have permission to publish.', 'unapp' );
	}
	if ( 0 === strpos( $section['id'], 'coach-' ) ) {
		return __( 'Describe your actual coaching or consulting offer, relevant experience and how scope, format and fees are agreed. Check discovery-call links and avoid promising outcomes you cannot guarantee.', 'unapp' );
	}
	if ( 0 === strpos( $section['id'], 'photographer-' ) ) {
		return __( 'Replace the illustrative portfolio with your own work, check captions and alternative text, and explain your commissions and image-use arrangements. Make project enquiry links work for your business.', 'unapp' );
	}
	if ( 0 === strpos( $section['id'], 'nonprofit-' ) ) {
		return __( 'Describe your actual programmes, current needs and ways to take part. Check volunteering and giving details, and publish only verified information about your organisation and its work.', 'unapp' );
	}
	if ( preg_match( '/testimonial|review|result|credential|client|logo|staff|team|tutor|speaker|coach/', $key ) ) {
		return __( 'Replace sample people, logos, quotations and results with accurate information you have permission to publish. Remove unsupported claims.', 'unapp' );
	}
	if ( preg_match( '/pricing|fees|membership|ticket|engagement|menu/', $key ) ) {
		return __( 'Check every price, currency, offer and condition. Make it clear what visitors receive and how they can enquire or buy.', 'unapp' );
	}
	return __( 'Describe your actual services, products or work in clear language. Replace sample names and descriptions, and check each button destination.', 'unapp' );
}

/** Editable pages belonging to the current starter; never arbitrary page IDs. */
function unapp_content_guide_pages() {
	$active = unapp_launch_active_starter();
	$pages = array();
	foreach ( $active['pages'] ?? array() as $key => $id ) {
		if ( 'blog' === $key || (int) $id === (int) get_option( 'page_for_posts' ) ) { continue; }
		$post = $id ? get_post( $id ) : null;
		if ( $post && 'page' === $post->post_type && ! in_array( $post->post_status, array( 'trash', 'auto-draft' ), true ) && current_user_can( 'edit_post', $post->ID ) ) {
			$pages[ $post->ID ] = array( 'title' => $post->post_title, 'key' => $key );
		}
	}
	return $pages;
}

/** Native image and Cover blocks, in editor tree order, with exact focus paths. */
function unapp_content_guide_images( $block, $path = array() ) {
	$images = array();
	if ( in_array( $block['blockName'], array( 'core/image', 'core/cover' ), true ) ) {
		$tags = new WP_HTML_Tag_Processor( $block['innerHTML'] );
		$has_image = $tags->next_tag( 'img' );
		$src = $has_image ? (string) $tags->get_attribute( 'src' ) : '';
		if ( 'core/cover' === $block['blockName'] ) {
			$src = 'video' === ( $block['attrs']['backgroundType'] ?? '' ) ? '' : ( $block['attrs']['url'] ?? $src );
		}
		if ( $src ) {
			$images[] = array(
				'path' => implode( '.', $path ), 'src' => $src,
				'alt' => $has_image ? (string) $tags->get_attribute( 'alt' ) : ( $block['attrs']['alt'] ?? '' ),
				'cover' => 'core/cover' === $block['blockName'],
				'demo' => 0 === strpos( $src, trailingslashit( get_theme_file_uri( 'assets/images' ) ) ),
				'ratio' => $block['attrs']['aspectRatio'] ?? '',
			);
		}
	}
	foreach ( $block['innerBlocks'] as $index => $child ) {
		$images = array_merge( $images, unapp_content_guide_images( $child, array_merge( $path, array( $index ) ) ) );
	}
	return $images;
}

/** Read current blocks without changing saved content or requiring new metadata. */
function unapp_content_guide_data( $page_id = 0 ) {
	$active = unapp_launch_active_starter();
	$home_id = absint( $active['pages']['home'] ?? 0 );
	$pages = unapp_content_guide_pages();
	$page_id = $page_id ? absint( $page_id ) : $home_id;
	$post = isset( $pages[ $page_id ] ) ? get_post( $page_id ) : null;
	$empty = array( 'home' => $home_id, 'page' => 0, 'title' => '', 'sections' => array(), 'complete' => 0 );
	if ( ! $post ) { return $empty; }
	$record = get_option( UNAPP_CONTENT_REVIEWS, array() );
	$reviews = array();
	if ( ( $record['home'] ?? 0 ) === $home_id && ( $record['theme'] ?? '' ) === get_stylesheet() ) {
		// Keep existing homepage progress; supporting pages have separate records.
		$reviews = $page_id === $home_id ? ( $record['reviews'] ?? array() ) : ( $record['pages'][ $page_id ] ?? array() );
	}
	$sections = array();
	$root = 0;
	foreach ( parse_blocks( $post->post_content ) as $block ) {
		if ( ! $block['blockName'] ) { continue; }
		$id = sanitize_key( $block['attrs']['metadata']['unappSection'] ?? ( $block['attrs']['anchor'] ?? 'section-' . $root ) );
		if ( isset( $sections[ $id ] ) ) { $id .= '-' . $root; }
		$heading = unapp_design_find_block( array( $block ), 'core/heading' );
		$title = $block['attrs']['metadata']['name'] ?? ( $heading ? wp_strip_all_tags( $heading['innerHTML'] ) : __( 'Page section', 'unapp' ) );
		if ( 'business-details' === ( $block['attrs']['anchor'] ?? '' ) ) { $title = __( 'Your contact details', 'unapp' ); }
		$markup = serialize_block( $block );
		$hash = hash( 'sha256', get_stylesheet() . '|' . $page_id . '|' . $id . '|' . $markup . '|' . get_option( 'blogname' ) );
		$edit = add_query_arg( 'unapp_section', $id, get_edit_post_link( $page_id, 'raw' ) );
		$images = unapp_content_guide_images( $block );
		$sections[ $id ] = array( 'id' => $id, 'title' => html_entity_decode( $title, ENT_QUOTES, 'UTF-8' ), 'hash' => $hash, 'reviewed' => ( $reviews[ $id ] ?? '' ) === $hash, 'edit' => $edit, 'root' => $root++, 'images' => count( $images ), 'image_items' => $images, 'page_key' => $pages[ $page_id ]['key'], 'supporting' => $page_id !== $home_id );
	}
	return array( 'home' => $home_id, 'page' => $page_id, 'title' => $post->post_title, 'sections' => $sections, 'complete' => count( array_filter( $sections, static function ( $section ) { return $section['reviewed']; } ) ) );
}

/** First unfinished starter page, for both the guide and Finish your website. */
function unapp_content_guide_next_page() {
	foreach ( unapp_content_guide_pages() as $id => $page ) {
		$data = unapp_content_guide_data( $id );
		if ( ! $data['sections'] || $data['complete'] < count( $data['sections'] ) ) { return $id; }
	}
	return 0;
}

/** Explicit owner review, scoped to one unchanged section and starter page. */
function unapp_review_content_section( $id, $hash, $page_id = 0 ) {
	$data = unapp_content_guide_data( $page_id );
	if ( ! current_user_can( 'edit_theme_options' ) || ! $data['page'] || ! current_user_can( 'edit_post', $data['page'] ) ) {
		return new WP_Error( 'unapp_content_forbidden', __( 'You are not allowed to review this starter page.', 'unapp' ) );
	}
	$lock = unapp_acquire_starter_lock();
	if ( is_wp_error( $lock ) ) {
		return $lock;
	}
	try {
		$data = unapp_content_guide_data( $page_id );
		if ( ! $data['page'] || ! current_user_can( 'edit_post', $data['page'] ) || ! isset( $data['sections'][ $id ] ) || ! is_string( $hash ) || ! hash_equals( $data['sections'][ $id ]['hash'], $hash ) ) {
			return new WP_Error( 'unapp_content_changed', __( 'This section changed. Refresh and review its current content.', 'unapp' ) );
		}
		$old = get_option( UNAPP_CONTENT_REVIEWS, array() );
		$record = ( $old['home'] ?? 0 ) === $data['home'] && ( $old['theme'] ?? '' ) === get_stylesheet() ? $old : array( 'home' => $data['home'], 'theme' => get_stylesheet(), 'reviews' => array() );
		if ( $data['page'] === $data['home'] ) {
			$record['reviews'][ $id ] = $hash;
		} else {
			$record['pages'][ $data['page'] ][ $id ] = $hash;
		}
		update_option( UNAPP_CONTENT_REVIEWS, $record, false );
		if ( get_option( UNAPP_CONTENT_REVIEWS ) !== $record ) {
			update_option( UNAPP_CONTENT_REVIEWS, $old, false );
			return new WP_Error( 'unapp_content_save', __( 'The review could not be saved. Please try again.', 'unapp' ) );
		}
		foreach ( $data['sections'] as $key => $section ) {
			if ( $key !== $id && ! $section['reviewed'] ) {
				return $key;
			}
		}
		return '';
	} finally {
		if ( get_option( UNAPP_STARTER_LOCK ) === $lock ) {
			delete_option( UNAPP_STARTER_LOCK );
		}
	}
}

function unapp_content_guide_menu() {
	add_theme_page( __( 'Guided content pass', 'unapp' ), __( 'Guided content pass', 'unapp' ), 'edit_theme_options', 'unapp-content-guide', 'unapp_render_content_guide' );
}
add_action( 'admin_menu', 'unapp_content_guide_menu' );

function unapp_handle_content_review() {
	check_admin_referer( 'unapp_content_review' );
	$page_id = absint( unapp_design_field( $_POST, 'page_id' ) );
	$result = unapp_review_content_section( sanitize_key( unapp_design_field( $_POST, 'section' ) ), unapp_design_field( $_POST, 'snapshot' ), $page_id );
	if ( is_wp_error( $result ) ) {
		set_transient( 'unapp_content_notice_' . get_current_user_id(), $result->get_error_message(), 5 * MINUTE_IN_SECONDS );
	}
	wp_safe_redirect( unapp_content_guide_url( is_wp_error( $result ) ? '' : $result, ! is_wp_error( $result ) && '' === $result ? unapp_content_guide_next_page() : $page_id ) );
	exit;
}
add_action( 'admin_post_unapp_content_review', 'unapp_handle_content_review' );

/** Show exact native image targets; do not fetch external images in the dashboard. */
function unapp_render_content_images( $section ) {
	if ( ! $section['image_items'] ) { return; }
	?>
	<details><summary><?php esc_html_e( 'Replace images in this section', 'unapp' ); ?></summary>
	<p><?php esc_html_e( 'Choose an image below. In WordPress, use Replace → Media Library or Upload; for a background, select its Cover block and use Replace. Save the page, then refresh this guide.', 'unapp' ); ?></p>
	<p><?php esc_html_e( 'For large photos, aim for at least 1200 pixels wide; smaller cards usually need 600 pixels. Match the existing shape, adjust the crop or focal point, and check mobile preview. Describe meaningful images in alternative text; leave decorative images empty.', 'unapp' ); ?></p>
	<ul class="unapp-guide-images"><?php foreach ( $section['image_items'] as $index => $image ) :
		$url = add_query_arg( array( 'unapp_focus' => 'image', 'unapp_image' => $image['path'] ), $section['edit'] );
		$local = wp_parse_url( $image['src'], PHP_URL_HOST ) === wp_parse_url( home_url(), PHP_URL_HOST );
		?><li>
		<?php if ( $local ) : ?><img src="<?php echo esc_url( $image['src'] ); ?>" alt="" loading="lazy"><?php endif; ?>
		<p><strong><?php /* translators: %d: image position within the section. */ printf( esc_html__( 'Image %d', 'unapp' ), $index + 1 ); ?></strong> — <?php echo esc_html( $image['cover'] ? __( 'Cover background', 'unapp' ) : __( 'Image block', 'unapp' ) ); ?></p>
		<?php if ( $image['demo'] ) : ?><p><?php esc_html_e( 'Bundled demo image. Replace it when it does not represent your business or work.', 'unapp' ); ?></p><?php endif; ?>
		<?php if ( $image['ratio'] ) : ?><p><?php /* translators: %s: saved image aspect ratio. */ printf( esc_html__( 'Current shape: %s', 'unapp' ), esc_html( $image['ratio'] ) ); ?></p><?php endif; ?>
		<p><?php /* translators: %s: current image alternative text or an empty-text label. */ printf( esc_html__( 'Alternative text: %s', 'unapp' ), esc_html( $image['alt'] ?: __( 'Empty — check whether this image is decorative.', 'unapp' ) ) ); ?></p>
		<a class="button" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php /* translators: %d: image position within the section. */ printf( esc_html__( 'Replace image %d in WordPress', 'unapp' ), $index + 1 ); ?></a>
		</li><?php endforeach; ?></ul></details>
	<?php
}

function unapp_render_content_guide() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to review this website.', 'unapp' ), 403 );
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only page selection.
	$page_id = absint( unapp_design_field( $_GET, 'page_id' ) );
	$data = unapp_content_guide_data( $page_id ?: unapp_content_guide_next_page() );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only section selection.
	$id = sanitize_key( unapp_design_field( $_GET, 'section' ) );
	if ( ! isset( $data['sections'][ $id ] ) ) {
		$id = key( $data['sections'] );
		foreach ( $data['sections'] as $key => $section ) {
			if ( ! $section['reviewed'] ) { $id = $key; break; }
		}
	}
	$section = $data['sections'][ $id ] ?? null;
	$notice = get_transient( 'unapp_content_notice_' . get_current_user_id() );
	delete_transient( 'unapp_content_notice_' . get_current_user_id() );
	?>
	<div class="wrap unapp-content-guide"><h1><?php esc_html_e( 'Guided content pass', 'unapp' ); ?></h1><p><a href="<?php echo esc_url( unapp_finish_url() ); ?>"><?php esc_html_e( 'Back to Finish your website', 'unapp' ); ?></a></p>
	<p><?php esc_html_e( 'Review your homepage and supporting pages one section at a time. Open it in WordPress, save any changes, then return here and mark it reviewed. Reviews are yours to confirm; editing a section makes it need review again.', 'unapp' ); ?></p>
	<?php if ( $notice ) : ?><div class="notice notice-error"><p><?php echo esc_html( $notice ); ?></p></div><?php endif; ?>
	<?php unapp_render_section_undo(); ?>
	<?php if ( unapp_content_guide_pages() ) : ?><nav aria-label="<?php esc_attr_e( 'Starter pages', 'unapp' ); ?>"><ul><?php foreach ( unapp_content_guide_pages() as $guide_id => $page ) : $progress = unapp_content_guide_data( $guide_id ); ?><li><a href="<?php echo esc_url( unapp_content_guide_url( '', $guide_id ) ); ?>" <?php if ( $guide_id === $data['page'] ) : ?>aria-current="page"<?php endif; ?>><?php echo esc_html( $page['title'] ); ?></a> — <?php /* translators: 1: reviewed sections, 2: total sections. */ printf( esc_html__( '%1$d of %2$d reviewed', 'unapp' ), $progress['complete'], count( $progress['sections'] ) ); ?></li><?php endforeach; ?></ul></nav><?php endif; ?>
	<?php if ( $data['page'] ) : ?><h2><?php echo esc_html( $data['title'] ); ?></h2><?php endif; ?>
	<?php if ( ! $section && $data['page'] ) : ?><p><?php esc_html_e( 'This page has no editable block sections. Open it in WordPress to add content, then refresh this guide.', 'unapp' ); ?> <a href="<?php echo esc_url( get_edit_post_link( $data['page'], 'raw' ) ); ?>"><?php esc_html_e( 'Edit page', 'unapp' ); ?></a></p><?php elseif ( ! $section ) : ?><p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=unapp-starter-sites' ) ); ?>"><?php esc_html_e( 'Choose a starter first', 'unapp' ); ?></a></p><?php else : ?>
	<p><?php /* translators: 1: reviewed sections, 2: total sections. */ printf( esc_html__( '%1$d of %2$d sections on this page reviewed.', 'unapp' ), $data['complete'], count( $data['sections'] ) ); ?></p>
	<?php if ( $data['complete'] === count( $data['sections'] ) ) : $next_page = unapp_content_guide_next_page(); ?><div class="notice notice-success"><p><?php echo esc_html( $next_page ? __( 'Page pass complete. Continue with the next unfinished starter page.', 'unapp' ) : __( 'All starter page sections reviewed. Review your header and footer and test contact delivery and mobile behavior in the launch checklist.', 'unapp' ) ); ?> <a href="<?php echo esc_url( $next_page ? unapp_content_guide_url( '', $next_page ) : unapp_launch_url() ); ?>"><?php echo esc_html( $next_page ? __( 'Continue to next page', 'unapp' ) : __( 'Continue to launch checks', 'unapp' ) ); ?></a></p></div><?php endif; ?>
	<section><h2><?php echo esc_html( $section['title'] ); ?></h2>
	<p><?php echo esc_html( unapp_content_guide_prompt( $section ) ); ?></p>
	<?php if ( $section['images'] ) : ?><p><?php esc_html_e( 'Replace sample images where needed. Check their crop and descriptive alternative text; decorative images can have empty alternative text.', 'unapp' ); ?></p><?php endif; ?>
	<?php if ( $section['images'] ) : ?><p><?php /* translators: %d: images in this section. */ printf( esc_html__( 'Images in this section: %d', 'unapp' ), $section['images'] ); ?></p><?php endif; ?>
	<p><a class="button button-primary" href="<?php echo esc_url( $section['edit'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Edit this section in WordPress', 'unapp' ); ?></a> <?php if ( $section['images'] ) : ?><a class="button" href="<?php echo esc_url( add_query_arg( 'unapp_focus', 'image', $section['edit'] ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Edit section images', 'unapp' ); ?></a><?php endif; ?> <?php if ( current_user_can( 'manage_options' ) ) : ?><a class="button" href="<?php echo esc_url( unapp_business_url() ); ?>"><?php esc_html_e( 'Business profile', 'unapp' ); ?></a><?php endif; ?></p>
	<?php unapp_render_content_images( $section ); ?>
	<?php unapp_render_section_swaps( $data['page'], $section ); ?>
	<p class="description"><?php esc_html_e( 'The editor opens in a new tab with the section selected. Save there, then use the editor shortcuts to return to the guide, or return to this tab and refresh before confirming your review. All content stays in native blocks.', 'unapp' ); ?></p>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="unapp_content_review"><input type="hidden" name="page_id" value="<?php echo esc_attr( $data['page'] ); ?>"><input type="hidden" name="section" value="<?php echo esc_attr( $section['id'] ); ?>"><input type="hidden" name="snapshot" value="<?php echo esc_attr( $section['hash'] ); ?>"><?php wp_nonce_field( 'unapp_content_review' ); ?><button class="button" type="submit"><?php esc_html_e( 'Reviewed, continue', 'unapp' ); ?></button> <a href="<?php echo esc_url( unapp_content_guide_url( $id, $data['page'] ) ); ?>"><?php esc_html_e( 'Refresh after editing', 'unapp' ); ?></a></form></section>
	<nav aria-label="<?php esc_attr_e( 'Page sections', 'unapp' ); ?>"><ol><?php foreach ( $data['sections'] as $item ) : ?><li><a href="<?php echo esc_url( unapp_content_guide_url( $item['id'], $data['page'] ) ); ?>" <?php if ( $item['id'] === $id ) : ?>aria-current="step"<?php endif; ?>><?php echo esc_html( $item['title'] ); ?></a> — <?php echo esc_html( $item['reviewed'] ? __( 'Reviewed', 'unapp' ) : __( 'Needs review', 'unapp' ) ); ?></li><?php endforeach; ?></ol></nav>
	<?php endif; ?></div><style>.unapp-content-guide{max-width:900px}.unapp-content-guide section{background:#fff;border:1px solid #c3c4c7;border-radius:8px;padding:24px;margin:24px 0}.unapp-content-guide a,.unapp-content-guide p{overflow-wrap:anywhere}.unapp-content-guide .button{height:auto;white-space:normal;margin:0 8px 8px 0}.unapp-content-guide li{margin-bottom:12px}.unapp-content-guide .unapp-guide-images{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,240px),1fr));gap:16px;padding:0;list-style:none}.unapp-guide-images li{border:1px solid #c3c4c7;border-radius:6px;padding:16px;min-width:0}.unapp-guide-images img{display:block;max-width:100%;width:100%;height:150px;object-fit:contain;background:#f0f0f1}.unapp-content-guide [aria-current]{font-weight:600}</style>
	<?php
}

/** Focus the requested root in the native post editor, without changing content. */
function unapp_content_guide_editor_assets( $hook ) {
	if ( 'post.php' !== $hook || ! current_user_can( 'edit_theme_options' ) ) { return; }
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only editor selection.
	$id = sanitize_key( unapp_design_field( $_GET, 'unapp_section' ) );
	if ( ! $id ) { return; }
	$page_id = (int) get_the_ID();
	if ( ! $page_id ) { return; }
	$data = unapp_content_guide_data( $page_id );
	if ( ! $data['page'] || ! isset( $data['sections'][ $id ] ) || ! current_user_can( 'edit_post', $data['page'] ) ) { return; }
	wp_enqueue_script( 'unapp-content-guide', get_theme_file_uri( 'assets/js/content-guide.js' ), array( 'wp-data', 'wp-dom-ready', 'wp-edit-post', 'wp-notices', 'wp-editor' ), UNAPP_VERSION, true );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only editor focus.
	$focus = 'image' === unapp_design_field( $_GET, 'unapp_focus' ) ? 'image' : 'content';
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only image selection.
	$image = unapp_design_field( $_GET, 'unapp_image' );
	$paths = array_column( $data['sections'][ $id ]['image_items'], 'path' );
	$path = 'image' === $focus && isset( $_GET['unapp_image'] ) && in_array( $image, $paths, true ) ? $image : null;
	wp_localize_script( 'unapp-content-guide', 'unappContentGuide', array( 'section' => $id, 'root' => $data['sections'][ $id ]['root'], 'focus' => $focus, 'imagePath' => $path, 'returnLinks' => unapp_content_return_links( $data, $id ), 'returnMessage' => __( 'Save your changes before leaving. Returning or moving on does not mark a section reviewed.', 'unapp' ), 'saveMessage' => __( 'Save your changes in WordPress and wait for saving to finish before using this shortcut.', 'unapp' ) ) );
}
add_action( 'admin_enqueue_scripts', 'unapp_content_guide_editor_assets' );

/** Server-built destinations only; no arbitrary return URL from the request. */
function unapp_content_return_links( $data, $id ) {
	$links = array(
		array( 'label' => __( 'Review this section', 'unapp' ), 'url' => unapp_content_guide_url( $id, $data['page'] ) ),
		array( 'label' => __( 'Back to website setup', 'unapp' ), 'url' => unapp_finish_url() ),
	);
	$keys = array_keys( $data['sections'] );
	$position = array_search( $id, $keys, true );
	if ( false !== $position && isset( $keys[ $position + 1 ] ) ) {
		$links[] = array( 'label' => __( 'Next section', 'unapp' ), 'url' => unapp_content_guide_url( $keys[ $position + 1 ], $data['page'] ) );
	}
	return $links;
}
