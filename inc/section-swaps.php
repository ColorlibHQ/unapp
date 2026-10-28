<?php
/** Content-preserving layouts for native starter sections. @package Unapp */
defined( 'ABSPATH' ) || exit;
const UNAPP_SECTION_UNDO = 'unapp_section_undo';

/** Find a compatible native layout frame, without interpreting or replacing copy. */
function unapp_swap_frame( $block, $role, $path = array() ) {
	$children = $block['innerBlocks'];
	if ( 'services' === $role && 'core/group' === $block['blockName'] && 'grid' === ( $block['attrs']['layout']['type'] ?? '' ) && count( $children ) >= 2 ) {
		return $path;
	}
	if ( 'core/columns' === $block['blockName'] && 2 === count( $children ) ) {
		$heading = array_map( static function ( $child ) { return (bool) unapp_design_find_block( array( $child ), 'core/heading' ); }, $children );
		$media = array_map( static function ( $child ) { return (bool) unapp_content_guide_images( $child ); }, $children );
		$buttons = array_map( static function ( $child ) { return (bool) unapp_design_find_block( array( $child ), 'core/buttons' ); }, $children );
		if ( ( 'hero' === $role && ( ( $heading[0] && $media[1] && ! $heading[1] ) || ( $heading[1] && $media[0] && ! $heading[0] ) ) ) || ( 'cta' === $role && ( ( $heading[0] && $buttons[1] && ! $heading[1] ) || ( $heading[1] && $buttons[0] && ! $heading[0] ) ) ) ) {
			return $path;
		}
	}
	if ( 'cta' === $role && 'core/group' === $block['blockName'] ) {
		$types = array_column( $children, 'blockName' );
		if ( preg_match( '~^\s*<([a-z][a-z0-9]*)\b[^>]*>\s*</\1>\s*$~is', $block['innerHTML'] ) && in_array( 'core/heading', $types, true ) && in_array( 'core/buttons', $types, true ) && ! array_diff( $types, array( 'core/heading', 'core/paragraph', 'core/buttons', 'core/image', 'core/spacer' ) ) ) {
			return $path;
		}
	}
	foreach ( $children as $index => $child ) {
		$found = unapp_swap_frame( $child, $role, array_merge( $path, array( $index ) ) );
		if ( null !== $found ) { return $found; }
	}
	return null;
}

/** Only offer roles with a compatible structure in the current saved section. */
function unapp_swap_role( $section, $block ) {
	$key = strtolower( $section['id'] . ' ' . $section['title'] );
	$key .= ' ' . ( $block['attrs']['className'] ?? '' );
	$role = preg_match( '/(?:^|[- ])cta(?:[- ]|$)|main action/', $key ) ? 'cta' : ( preg_match( '/hero|masthead|introduction/', $key ) ? 'hero' : ( preg_match( '/services|features|programmes|treatments/', $key ) ? 'services' : '' ) );
	return $role && null !== unapp_swap_frame( $block, $role ) ? $role : '';
}

function unapp_swap_choices( $role ) {
	$choices = array(
		'hero' => array( 'image-right' => __( 'Image on the right', 'unapp' ), 'image-left' => __( 'Image on the left', 'unapp' ), 'stacked' => __( 'Text above image', 'unapp' ) ),
		'services' => array( 'grid-2' => __( 'Two-column grid', 'unapp' ), 'grid-3' => __( 'Three-column grid', 'unapp' ), 'grid-1' => __( 'Single-column list', 'unapp' ) ),
		'cta' => array( 'side-by-side' => __( 'Text beside buttons', 'unapp' ), 'stacked' => __( 'Text above buttons', 'unapp' ) ),
	);
	return $choices[ $role ] ?? array();
}

/** Change a saved wrapper's classes while retaining all its HTML and children. */
function unapp_swap_classes( $block, $remove, $add = '' ) {
	$classes = preg_split( '/\s+/', $block['attrs']['className'] ?? '', -1, PREG_SPLIT_NO_EMPTY );
	$classes = array_values( array_filter( $classes, static function ( $class ) use ( $remove ) { return ! preg_match( $remove, $class ); } ) );
	if ( $add ) { $classes[] = $add; }
	if ( $classes ) { $block['attrs']['className'] = implode( ' ', array_unique( $classes ) ); } else { unset( $block['attrs']['className'] ); }
	foreach ( $block['innerContent'] as &$chunk ) {
		if ( ! is_string( $chunk ) || false === strpos( $chunk, '<' ) ) { continue; }
		$tags = new WP_HTML_Tag_Processor( $chunk );
		if ( $tags->next_tag() ) {
			foreach ( preg_split( '/\s+/', (string) $tags->get_attribute( 'class' ), -1, PREG_SPLIT_NO_EMPTY ) as $class ) {
				if ( preg_match( $remove, $class ) ) { $tags->remove_class( $class ); }
			}
			if ( $add ) { $tags->add_class( $add ); }
			$chunk = $tags->get_updated_html();
		}
		break;
	}
	unset( $chunk );
	$block['innerHTML'] = implode( '', array_filter( $block['innerContent'], 'is_string' ) );
	return $block;
}

/** New structural wrappers only; existing content blocks are transferred whole. */
function unapp_swap_wrap( $name, $children ) {
	$class = 'wp-block-' . str_replace( 'core/', '', $name );
	return array( 'blockName' => $name, 'attrs' => array(), 'innerBlocks' => $children, 'innerHTML' => '<div class="' . $class . '"></div>', 'innerContent' => array_merge( array( '<div class="' . $class . '">' ), array_fill( 0, count( $children ), null ), array( '</div>' ) ) );
}

/** Resolve a guide root to its actual parsed index, including freeform whitespace. */
function unapp_swap_source( $page_id, $section_id ) {
	if ( ! absint( $page_id ) ) { return new WP_Error( 'unapp_swap_forbidden', __( 'Choose an editable starter page.', 'unapp' ) ); }
	$data = unapp_content_guide_data( $page_id );
	if ( ! current_user_can( 'edit_theme_options' ) || ! $data['page'] || ! isset( $data['sections'][ $section_id ] ) ) {
		return new WP_Error( 'unapp_swap_forbidden', __( 'Choose an editable section belonging to your current starter.', 'unapp' ) );
	}
	$blocks = parse_blocks( get_post( $data['page'] )->post_content );
	$root = 0;
	foreach ( $blocks as $index => $block ) {
		if ( ! $block['blockName'] ) { continue; }
		if ( $root++ === $data['sections'][ $section_id ]['root'] ) {
			return array( 'data' => $data, 'section' => $data['sections'][ $section_id ], 'blocks' => $blocks, 'index' => $index, 'block' => $block );
		}
	}
	return new WP_Error( 'unapp_swap_changed', __( 'This section changed. Refresh the guide before choosing a layout.', 'unapp' ) );
}

/** Pure layout builder shared by preview and apply; never inserts demo content. */
function unapp_swap_prepare( $page_id, $section_id, $preset, $expected ) {
	$source = unapp_swap_source( $page_id, $section_id );
	if ( is_wp_error( $source ) ) { return $source; }
	if ( ! is_string( $expected ) || ! hash_equals( $source['section']['hash'], $expected ) ) {
		return new WP_Error( 'unapp_swap_changed', __( 'This section changed. Refresh and preview its current content before applying a layout.', 'unapp' ) );
	}
	$role = unapp_swap_role( $source['section'], $source['block'] );
	if ( ! isset( unapp_swap_choices( $role )[ $preset ] ) ) {
		return new WP_Error( 'unapp_swap_unknown', __( 'Choose a compatible layout from this section’s preview options.', 'unapp' ) );
	}
	$block = $source['block'];
	$path = unapp_swap_frame( $block, $role );
	$frame =& $block;
	foreach ( $path as $index ) {
		if ( 'cta' === $role && isset( $frame['attrs']['layout']['contentSize'] ) ) { $frame['attrs']['layout']['contentSize'] = 'stacked' === $preset ? '720px' : '1100px'; }
		$frame =& $frame['innerBlocks'][ $index ];
	}
	if ( 'services' === $role ) {
		$count = (int) substr( $preset, -1 );
		$frame['attrs']['layout'] = array( 'type' => 'grid', 'columnCount' => $count );
		$frame = unapp_swap_classes( $frame, '/^(?:unapp-grid-[0-9]+|unapp-section-grid)$/', 'unapp-section-grid' );
	} else {
		if ( 'core/group' === $frame['blockName'] ) {
			$copy = array(); $actions = array();
			foreach ( $frame['innerBlocks'] as $child ) {
				if ( 'core/buttons' === $child['blockName'] ) { $actions[] = $child; } else { $copy[] = $child; }
			}
			$frame['attrs']['layout']['contentSize'] = 'stacked' === $preset ? '720px' : '1100px';
			$columns = unapp_swap_wrap( 'core/columns', array( unapp_swap_wrap( 'core/column', $copy ), unapp_swap_wrap( 'core/column', $actions ) ) );
			$columns['attrs']['verticalAlignment'] = 'center';
			$columns['innerContent'][0] = '<div class="wp-block-columns are-vertically-aligned-center">';
			$columns['innerHTML'] = implode( '', array_filter( $columns['innerContent'], 'is_string' ) );
			$frame['innerBlocks'] = array( $columns );
			$frame['innerContent'] = array( $frame['innerContent'][0], null, end( $frame['innerContent'] ) );
			$frame['innerHTML'] = implode( '', array_filter( $frame['innerContent'], 'is_string' ) );
			$frame =& $frame['innerBlocks'][0];
		}
		if ( in_array( $role, array( 'hero', 'cta' ), true ) ) {
			$first_is_copy = (bool) unapp_design_find_block( array( $frame['innerBlocks'][0] ), 'core/heading' );
			$want_copy_first = 'image-left' !== $preset;
			if ( $first_is_copy !== $want_copy_first ) { $frame['innerBlocks'] = array_reverse( $frame['innerBlocks'] ); }
		}
		$frame = unapp_swap_classes( $frame, '/^is-style-unapp-section-stack$/', 'stacked' === $preset ? 'is-style-unapp-section-stack' : '' );
	}
	unset( $frame );
	$block['attrs']['metadata']['unappSwapLayout'] = $preset;
	$source['replacement'] = $block;
	$source['preset'] = $preset;
	return $source;
}

/** Respect another editor's native WordPress page lock. */
function unapp_swap_editor_locked( $page_id ) {
	if ( ! function_exists( 'wp_check_post_lock' ) ) { require_once ABSPATH . 'wp-admin/includes/post.php'; }
	return (bool) wp_check_post_lock( $page_id );
}

/** Persist a section change, with a recovery copy and normal WordPress revisions. */
function unapp_swap_apply( $page_id, $section_id, $preset, $expected ) {
	$page_id = absint( $page_id );
	$lock = unapp_acquire_starter_lock();
	if ( is_wp_error( $lock ) ) { return $lock; }
	try {
		$prepared = unapp_swap_prepare( $page_id, $section_id, $preset, $expected );
		if ( is_wp_error( $prepared ) ) { return $prepared; }
		if ( unapp_swap_editor_locked( $page_id ) ) { return new WP_Error( 'unapp_swap_locked', __( 'Another person is editing this page. Wait until they finish, then refresh and preview again.', 'unapp' ) ); }
		$before = serialize_block( $prepared['block'] );
		$after = serialize_block( $prepared['replacement'] );
		if ( $before === $after ) { return true; }
		$prepared['blocks'][ $prepared['index'] ] = $prepared['replacement'];
		$content = serialize_blocks( $prepared['blocks'] );
		$old_content = get_post( $page_id )->post_content;
		$old_undo = get_option( UNAPP_SECTION_UNDO, null );
		unapp_keep_revision( $page_id );
		$saved = wp_update_post( array( 'ID' => $page_id, 'post_content' => wp_slash( $content ) ), true );
		$record = array( 'id' => wp_generate_uuid4(), 'theme' => get_stylesheet(), 'home' => $prepared['data']['home'], 'page' => $page_id, 'section' => $section_id, 'before' => $before, 'after' => hash( 'sha256', $after ) );
		if ( ! is_wp_error( $saved ) && $saved && get_post( $page_id )->post_content === $content ) {
			update_option( UNAPP_SECTION_UNDO, $record, false );
			if ( get_option( UNAPP_SECTION_UNDO ) === $record ) { return true; }
		}
		wp_update_post( array( 'ID' => $page_id, 'post_content' => wp_slash( $old_content ) ), true );
		if ( null === $old_undo ) { delete_option( UNAPP_SECTION_UNDO ); } else { update_option( UNAPP_SECTION_UNDO, $old_undo, false ); }
		return new WP_Error( 'unapp_swap_save', get_post( $page_id )->post_content === $old_content ? __( 'The layout or recovery copy could not be saved. Your previous content was restored.', 'unapp' ) : __( 'The layout could not be fully restored. Review this page and its revisions in WordPress.', 'unapp' ) );
	} finally {
		if ( get_option( UNAPP_STARTER_LOCK ) === $lock ) { delete_option( UNAPP_STARTER_LOCK ); }
	}
}

/** Undo only the swapped section, preserving later edits elsewhere on the page. */
function unapp_swap_undo( $request ) {
	$lock = unapp_acquire_starter_lock();
	if ( is_wp_error( $lock ) ) { return $lock; }
	try {
		$record = get_option( UNAPP_SECTION_UNDO );
		if ( ! is_array( $record ) || ( $record['id'] ?? '' ) !== $request || ( $record['theme'] ?? '' ) !== get_stylesheet() ) {
			return new WP_Error( 'unapp_swap_undo', __( 'This is no longer the latest section swap. Refresh the guide.', 'unapp' ) );
		}
		$source = unapp_swap_source( $record['page'], $record['section'] );
		if ( is_wp_error( $source ) || $source['data']['home'] !== $record['home'] || ! hash_equals( $record['after'], hash( 'sha256', serialize_block( $source['block'] ) ) ) ) {
			return new WP_Error( 'unapp_swap_undo', __( 'This section changed after the swap. Undo is unavailable to protect your edits; use WordPress revisions to review earlier versions.', 'unapp' ) );
		}
		if ( unapp_swap_editor_locked( $record['page'] ) ) { return new WP_Error( 'unapp_swap_locked', __( 'Another person is editing this page. Wait until they finish before using Undo.', 'unapp' ) ); }
		$old_content = get_post( $record['page'] )->post_content;
		$source['blocks'][ $source['index'] ] = parse_blocks( $record['before'] )[0];
		$content = serialize_blocks( $source['blocks'] );
		unapp_keep_revision( $record['page'] );
		$saved = wp_update_post( array( 'ID' => $record['page'], 'post_content' => wp_slash( $content ) ), true );
		if ( is_wp_error( $saved ) || ! $saved || get_post( $record['page'] )->post_content !== $content ) {
			wp_update_post( array( 'ID' => $record['page'], 'post_content' => wp_slash( $old_content ) ), true );
			return new WP_Error( 'unapp_swap_save', __( 'The section could not be restored. Review its WordPress revisions; the recovery copy is still available.', 'unapp' ) );
		}
		delete_option( UNAPP_SECTION_UNDO );
		return true;
	} finally {
		if ( get_option( UNAPP_STARTER_LOCK ) === $lock ) { delete_option( UNAPP_STARTER_LOCK ); }
	}
}

function unapp_swap_preview_url( $page_id, $section, $preset ) {
	return wp_nonce_url( add_query_arg( array( 'action' => 'unapp_section_preview', 'page_id' => $page_id, 'section' => $section['id'], 'preset' => $preset, 'snapshot' => $section['hash'] ), admin_url( 'admin-post.php' ) ), 'unapp_section_preview' );
}

function unapp_swap_preview_data( $page_id, $section_id, $preset, $expected ) {
	$prepared = unapp_swap_prepare( $page_id, $section_id, $preset, $expected );
	if ( is_wp_error( $prepared ) ) { return $prepared; }
	return array( 'title' => __( 'Section layout preview', 'unapp' ), 'markup' => serialize_block( $prepared['replacement'] ), 'inert' => true );
}

function unapp_handle_section_preview() {
	check_admin_referer( 'unapp_section_preview' );
	$data = unapp_swap_preview_data( absint( unapp_design_field( $_GET, 'page_id' ) ), sanitize_key( unapp_design_field( $_GET, 'section' ) ), sanitize_key( unapp_design_field( $_GET, 'preset' ) ), unapp_design_field( $_GET, 'snapshot' ) );
	if ( is_wp_error( $data ) ) { wp_die( esc_html( $data->get_error_message() ), 400 ); }
	nocache_headers();
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'X-Robots-Tag: noindex, nofollow' );
	header( 'Referrer-Policy: no-referrer' );
	unapp_render_starter_preview_document( $data );
	exit;
}
add_action( 'admin_post_unapp_section_preview', 'unapp_handle_section_preview' );

function unapp_handle_section_swap() {
	check_admin_referer( 'unapp_section_swap' );
	$page_id = absint( unapp_design_field( $_POST, 'page_id' ) );
	$section_id = sanitize_key( unapp_design_field( $_POST, 'section' ) );
	$result = 'undo' === unapp_design_field( $_POST, 'operation' ) ? unapp_swap_undo( unapp_design_field( $_POST, 'record' ) ) : unapp_swap_apply( $page_id, $section_id, sanitize_key( unapp_design_field( $_POST, 'preset' ) ), unapp_design_field( $_POST, 'snapshot' ) );
	set_transient( 'unapp_swap_notice_' . get_current_user_id(), array( 'error' => is_wp_error( $result ), 'text' => is_wp_error( $result ) ? $result->get_error_message() : __( 'Section layout saved. Your content stays editable in WordPress.', 'unapp' ) ), 5 * MINUTE_IN_SECONDS );
	wp_safe_redirect( unapp_content_guide_url( $section_id, $page_id ) );
	exit;
}
add_action( 'admin_post_unapp_section_swap', 'unapp_handle_section_swap' );

/** Accessible GET selection works without JavaScript; preview shares apply's builder. */
function unapp_render_section_swaps( $page_id, $section ) {
	$source = unapp_swap_source( $page_id, $section['id'] );
	if ( is_wp_error( $source ) ) { return; }
	$choices = unapp_swap_choices( unapp_swap_role( $section, $source['block'] ) );
	if ( ! $choices ) { return; }
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only layout choice.
	$selected = sanitize_key( unapp_design_field( $_GET, 'swap' ) );
	?>
	<details class="unapp-section-swaps" <?php if ( isset( $choices[ $selected ] ) ) : ?>open<?php endif; ?>><summary><?php esc_html_e( 'Try another section layout', 'unapp' ); ?></summary>
	<p><?php esc_html_e( 'Preview a compatible layout using your saved text, images, buttons and links. Save any open editor changes first, then refresh this guide. Only this section changes; every block stays editable in WordPress.', 'unapp' ); ?></p>
	<form method="get" action="<?php echo esc_url( admin_url( 'themes.php' ) ); ?>"><input type="hidden" name="page" value="unapp-content-guide"><input type="hidden" name="page_id" value="<?php echo esc_attr( $page_id ); ?>"><input type="hidden" name="section" value="<?php echo esc_attr( $section['id'] ); ?>"><p><label for="unapp-swap-layout"><?php esc_html_e( 'Section layout', 'unapp' ); ?></label> <select id="unapp-swap-layout" name="swap"><?php foreach ( $choices as $key => $label ) : ?><option value="<?php echo esc_attr( $key ); ?>" <?php selected( $selected, $key ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select> <button type="submit" class="button"><?php esc_html_e( 'Preview layout', 'unapp' ); ?></button></p></form>
	<?php if ( isset( $choices[ $selected ] ) ) : ?>
	<div class="unapp-design__review"><h3><?php echo esc_html( $choices[ $selected ] ); ?></h3><p><?php esc_html_e( 'This private preview does not change your page. External links and contact actions are inactive.', 'unapp' ); ?></p>
	<fieldset class="unapp-design__devices"><legend><?php esc_html_e( 'Preview width', 'unapp' ); ?></legend><?php foreach ( array( 'desktop' => __( 'Desktop', 'unapp' ), 'tablet' => __( 'Tablet', 'unapp' ), 'mobile' => __( 'Mobile', 'unapp' ) ) as $key => $label ) : ?><label><input type="radio" name="design_width" value="<?php echo esc_attr( $key ); ?>" <?php checked( 'desktop', $key ); ?>> <?php echo esc_html( $label ); ?></label><?php endforeach; ?></fieldset>
	<div class="unapp-design__canvas"><iframe class="unapp-design__preview" title="<?php esc_attr_e( 'Your section layout preview', 'unapp' ); ?>" src="<?php echo esc_url( unapp_swap_preview_url( $page_id, $section, $selected ) ); ?>" sandbox="allow-scripts allow-same-origin" referrerpolicy="no-referrer" style="border:0;width:100%;height:480px"></iframe></div>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="unapp_section_swap"><input type="hidden" name="page_id" value="<?php echo esc_attr( $page_id ); ?>"><input type="hidden" name="section" value="<?php echo esc_attr( $section['id'] ); ?>"><input type="hidden" name="preset" value="<?php echo esc_attr( $selected ); ?>"><input type="hidden" name="snapshot" value="<?php echo esc_attr( $section['hash'] ); ?>"><?php wp_nonce_field( 'unapp_section_swap' ); ?><p><button type="submit" class="button button-primary"><?php esc_html_e( 'Apply this layout', 'unapp' ); ?></button></p></form></div>
	<?php endif; ?></details>
	<?php
}

function unapp_render_section_undo() {
	$notice = get_transient( 'unapp_swap_notice_' . get_current_user_id() );
	delete_transient( 'unapp_swap_notice_' . get_current_user_id() );
	if ( is_array( $notice ) ) { ?><div class="notice <?php echo $notice['error'] ? 'notice-error' : 'notice-success'; ?>"><p><?php echo esc_html( $notice['text'] ); ?></p></div><?php }
	$record = get_option( UNAPP_SECTION_UNDO );
	if ( ! is_array( $record ) || ( $record['theme'] ?? '' ) !== get_stylesheet() || ! isset( unapp_content_guide_pages()[ $record['page'] ] ) ) { return; }
	?>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="unapp_section_swap"><input type="hidden" name="operation" value="undo"><input type="hidden" name="record" value="<?php echo esc_attr( $record['id'] ); ?>"><input type="hidden" name="page_id" value="<?php echo esc_attr( $record['page'] ); ?>"><input type="hidden" name="section" value="<?php echo esc_attr( $record['section'] ); ?>"><?php wp_nonce_field( 'unapp_section_swap' ); ?><p><button class="button" type="submit"><?php esc_html_e( 'Undo last section swap', 'unapp' ); ?></button> <?php esc_html_e( 'Restores only the last swapped section. Later edits to that section are protected.', 'unapp' ); ?></p></form>
	<?php
}

function unapp_section_swap_assets( $hook ) {
	if ( 'appearance_page_unapp-content-guide' !== $hook ) { return; }
	wp_enqueue_style( 'unapp-design', get_parent_theme_file_uri( 'assets/css/design.css' ), array(), UNAPP_VERSION );
	wp_enqueue_script( 'unapp-design', get_parent_theme_file_uri( 'assets/js/design.js' ), array(), UNAPP_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'unapp_section_swap_assets' );
add_action( 'init', static function () { register_block_style( 'core/columns', array( 'name' => 'unapp-section-stack', 'label' => __( 'Stacked section', 'unapp' ) ) ); } );
