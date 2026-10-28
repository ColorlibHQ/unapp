<?php
/** Native template-part presets and a guided route into the Site Editor. @package Unapp */
defined( 'ABSPATH' ) || exit;
const UNAPP_DESIGN_UNDO = 'unapp_design_undo';

/** @return string Appearance workspace URL. */
function unapp_design_url() {
	return admin_url( 'themes.php?page=unapp-design' );
}

/** @return array Allowlisted layouts; no uploaded or arbitrary block markup. */
function unapp_design_presets() {
	return array(
		'header' => array(
			'standard' => array( 'pattern' => 'unapp/header', 'title' => __( 'Navigation and action', 'unapp' ), 'text' => __( 'Branding on the left, menu and an action button on the right.', 'unapp' ) ),
			'centered' => array( 'pattern' => 'unapp/header-centered', 'title' => __( 'Centered branding', 'unapp' ), 'text' => __( 'A centered logo and site name, with the menu underneath.', 'unapp' ) ),
			'simple' => array( 'pattern' => 'unapp/header-simple', 'title' => __( 'Simple navigation', 'unapp' ), 'text' => __( 'Branding and a menu, with no action button.', 'unapp' ) ),
		),
		'footer' => array(
			'columns' => array( 'pattern' => 'unapp/footer-columns', 'title' => __( 'Brand and menu', 'unapp' ), 'text' => __( 'Site name and tagline beside navigation, with a legal row below.', 'unapp' ) ),
			'bar' => array( 'pattern' => 'unapp/footer-bar', 'title' => __( 'Compact bar', 'unapp' ), 'text' => __( 'Branding and navigation share a compact, wrapping row.', 'unapp' ) ),
			'minimal' => array( 'pattern' => 'unapp/footer-minimal', 'title' => __( 'Minimal footer', 'unapp' ), 'text' => __( 'Site branding and a legal row, without navigation.', 'unapp' ) ),
		),
	);
}

/** @return string Current database content, or the child/parent theme file. */
function unapp_design_part_content( $area ) {
	$part = unapp_get_customised_part( $area );
	return $part ? $part->post_content : unapp_part_file_content( $area );
}

/** @return string Theme-scoped fingerprint, including patterns behind file defaults. */
function unapp_design_fingerprint( $area ) {
	$part = unapp_get_customised_part( $area );
	return hash( 'sha256', get_stylesheet() . '|' . $area . '|' . ( $part ? unapp_starter_post_fingerprint( $part->ID ) : unapp_design_expand( unapp_design_part_content( $area ) ) ) );
}

/** Resolve file-default pattern references without rendering dynamic blocks. */
function unapp_design_expand( $markup ) {
	return preg_replace_callback( '/<!-- wp:pattern (\{.*?\})\s*\/-->/', static function ( $match ) {
		$attrs = json_decode( $match[1], true );
		return ! empty( $attrs['slug'] ) ? unapp_get_pattern_markup( $attrs['slug'] ) : '';
	}, $markup );
}

/** Find a block while preserving its attributes and inline content. */
function unapp_design_find_block( $blocks, $name ) {
	foreach ( $blocks as $block ) {
		if ( $name === $block['blockName'] && ( 'core/button' !== $name || ! preg_match( '/unapp-(?:scheme|period)/', $block['attrs']['className'] ?? '' ) ) ) {
			return $block;
		}
		$found = unapp_design_find_block( $block['innerBlocks'], $name );
		if ( $found ) {
			return $found;
		}
	}
	return null;
}

/** Build a native preset, carrying over the site's menu, logo and header action. */
function unapp_design_prepare( $area, $key ) {
	$presets = unapp_design_presets();
	if ( ! isset( $presets[ $area ][ $key ] ) ) {
		return new WP_Error( 'unapp_design_unknown', __( 'Choose an available header or footer preset.', 'unapp' ) );
	}
	$pattern = $presets[ $area ][ $key ]['pattern'];
	$valid = unapp_validate_starter_pattern( $pattern );
	if ( is_wp_error( $valid ) ) {
		return $valid;
	}
	$current = parse_blocks( unapp_design_expand( unapp_design_part_content( $area ) ) );
	$nav = unapp_design_find_block( $current, 'core/navigation' );
	if ( ! $nav && 'footer' === $area ) {
		$nav = unapp_design_find_block( parse_blocks( unapp_design_expand( unapp_design_part_content( 'header' ) ) ), 'core/navigation' );
	}
	$logo = unapp_design_find_block( $current, 'core/site-logo' );
	$action = 'header' === $area ? unapp_design_find_block( $current, 'core/button' ) : null;
	if ( $action ) {
		$link = new WP_HTML_Tag_Processor( serialize_block( $action ) );
		$link->next_tag( 'a' );
		if ( ! $link->get_attribute( 'href' ) || '#' === $link->get_attribute( 'href' ) ) {
			$action = null;
		}
	}
	$walk = static function ( $blocks ) use ( &$walk, $nav, $logo, $action, $area ) {
		foreach ( $blocks as &$block ) {
			if ( 'core/navigation' === $block['blockName'] && $nav ) {
				// Keep the selected layout's styling; transfer only menu identity.
				if ( ! empty( $nav['attrs']['ref'] ) ) {
					$block['attrs']['ref'] = $nav['attrs']['ref'];
				} else {
					$block['innerBlocks'] = $nav['innerBlocks'];
					$block['innerContent'] = $nav['innerContent'];
					$block['innerHTML'] = $nav['innerHTML'];
				}
			} elseif ( 'core/site-logo' === $block['blockName'] && $logo ) {
				$block = $logo;
			} elseif ( 'core/button' === $block['blockName'] && 'header' === $area ) {
				$block = $action ?: parse_blocks( '<!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'View the site', 'unapp' ) . '</a></div><!-- /wp:button -->' )[0];
			}
			$block['innerBlocks'] = $walk( $block['innerBlocks'] );
		}
		return $blocks;
	};
	return serialize_blocks( $walk( parse_blocks( unapp_get_pattern_markup( $pattern ) ) ) );
}

/** Apply only the reviewed part; record a guarded Undo and retain native revisions. */
function unapp_design_apply( $area, $key, $expected ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return new WP_Error( 'unapp_design_forbidden', __( 'You are not allowed to change the site design.', 'unapp' ) );
	}
	$content = unapp_design_prepare( $area, $key );
	if ( is_wp_error( $content ) ) {
		return $content;
	}
	$lock = unapp_acquire_starter_lock();
	if ( is_wp_error( $lock ) ) {
		return $lock;
	}
	try {
		if ( ! hash_equals( unapp_design_fingerprint( $area ), $expected ) ) {
			return new WP_Error( 'unapp_design_changed', __( 'This template part changed after the screen was opened. Refresh and preview it again before applying a preset.', 'unapp' ) );
		}
		$part = unapp_get_customised_part( $area );
		if ( $part && $part->post_content === $content ) {
			return true; // Reapplying the same layout must retain the useful Undo.
		}
		$before = $part ? array( $part->ID => get_post( $part->ID, ARRAY_A ) ) : array();
		$new = array();
		$track = static function ( $id, $post, $update ) use ( &$new, $area ) {
			if ( ! $update && 'wp_template_part' === $post->post_type && $area === $post->post_name ) {
				$new[ $id ] = 'wp_template_part';
			}
		};
		add_action( 'wp_insert_post', $track, 10, 3 );
		try {
			$id = unapp_save_part( $area, 'header' === $area ? __( 'Header', 'unapp' ) : __( 'Footer', 'unapp' ), $content );
		} finally {
			remove_action( 'wp_insert_post', $track, 10 );
		}
		$saved = ! is_wp_error( $id ) ? get_post( $id ) : null;
		if ( ! $saved || $saved->post_content !== $content ) {
			$restored = unapp_restore_starter_state( $before, $new, array() );
			return new WP_Error( 'unapp_design_save', $restored ? __( 'The preset could not be saved. The previous template part was restored.', 'unapp' ) : __( 'The preset could not be saved or fully restored. Review the template part in the Site Editor.', 'unapp' ) );
		}
		$record = array( 'id' => wp_generate_uuid4(), 'theme' => get_stylesheet(), 'area' => $area, 'post' => $id, 'before' => $before, 'new' => $new, 'hash' => unapp_design_fingerprint( $area ) );
		update_option( UNAPP_DESIGN_UNDO, $record, false );
		if ( get_option( UNAPP_DESIGN_UNDO ) !== $record ) {
			$restored = unapp_restore_starter_state( $before, $new, array() );
			return new WP_Error( 'unapp_design_save', $restored ? __( 'The recovery copy could not be saved. The previous template part was restored.', 'unapp' ) : __( 'The recovery copy could not be saved and restoration is incomplete. Review the template part in the Site Editor.', 'unapp' ) );
		}
		return true;
	} finally {
		delete_option( UNAPP_STARTER_LOCK );
	}
}

/** Undo only the most recent preset and refuse to overwrite newer editor changes. */
function unapp_design_undo( $request ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return new WP_Error( 'unapp_design_forbidden', __( 'You are not allowed to change the site design.', 'unapp' ) );
	}
	$lock = unapp_acquire_starter_lock();
	if ( is_wp_error( $lock ) ) {
		return $lock;
	}
	try {
		$record = get_option( UNAPP_DESIGN_UNDO );
		if ( ! is_array( $record ) || $record['id'] !== $request || $record['theme'] !== get_stylesheet() || ! hash_equals( $record['hash'], unapp_design_fingerprint( $record['area'] ) ) ) {
			return new WP_Error( 'unapp_design_changed', __( 'Undo is unavailable because the template part changed. Use the Site Editor and its revisions to review earlier versions.', 'unapp' ) );
		}
		if ( ! unapp_restore_starter_state( $record['before'], $record['new'], array() ) ) {
			return new WP_Error( 'unapp_design_save', __( 'The template part could not be fully restored. Review it in the Site Editor.', 'unapp' ) );
		}
		delete_option( UNAPP_DESIGN_UNDO );
		return true;
	} finally {
		delete_option( UNAPP_STARTER_LOCK );
	}
}

/** @return string Stable core template-part editor URL, including child themes. */
function unapp_design_editor_url( $area ) {
	return add_query_arg( array( 'postType' => 'wp_template_part', 'postId' => get_stylesheet() . '//' . $area ), admin_url( 'site-editor.php' ) );
}

/** Read scalar request fields without coercing arrays into strings. */
function unapp_design_field( $source, $name ) {
	return isset( $source[ $name ] ) && is_string( $source[ $name ] ) ? wp_unslash( $source[ $name ] ) : '';
}

/** Authenticated POST: a single reviewed template part, or its latest Undo. */
function unapp_handle_design_save() {
	check_admin_referer( 'unapp_design_save' );
	$result = 'undo' === unapp_design_field( $_POST, 'operation' )
		? unapp_design_undo( unapp_design_field( $_POST, 'record' ) )
		: unapp_design_apply( sanitize_key( unapp_design_field( $_POST, 'area' ) ), sanitize_key( unapp_design_field( $_POST, 'preset' ) ), unapp_design_field( $_POST, 'snapshot' ) );
	set_transient( 'unapp_design_notice_' . get_current_user_id(), is_wp_error( $result ) ? $result->get_error_message() : true, 5 * MINUTE_IN_SECONDS );
	wp_safe_redirect( unapp_design_url() );
	exit;
}
add_action( 'admin_post_unapp_design_save', 'unapp_handle_design_save' );

/** @return string Private, allowlisted preview URL. */
function unapp_design_preview_url( $area, $key ) {
	return wp_nonce_url( add_query_arg( array( 'action' => 'unapp_design_preview', 'area' => $area, 'preset' => $key ), admin_url( 'admin-post.php' ) ), 'unapp_design_preview' );
}

/** @return array|WP_Error Preview without posts, options or template-part writes. */
function unapp_design_preview_data( $area, $key ) {
	$content = unapp_design_prepare( $area, $key );
	if ( is_wp_error( $content ) ) {
		return $content;
	}
	$sample = '<!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} --><main class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">' . esc_html__( 'Your site, your style', 'unapp' ) . '</h1><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html__( 'This is a layout preview using your current site styles. Apply the preset, then edit every block in WordPress’s Site Editor.', 'unapp' ) . '</p><!-- /wp:paragraph --></main><!-- /wp:group -->';
	$part = '<!-- wp:group {"tagName":"' . $area . '","layout":{"type":"default"}} --><' . $area . ' class="wp-block-group">' . $content . '</' . $area . '><!-- /wp:group -->';
	return array( 'title' => unapp_design_presets()[ $area ][ $key ]['title'], 'markup' => 'header' === $area ? $part . $sample : $sample . $part );
}

/** Authenticated preview endpoint, never an apply action. */
function unapp_handle_design_preview() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to preview the site design.', 'unapp' ), 403 );
	}
	check_admin_referer( 'unapp_design_preview' );
	$data = unapp_design_preview_data( sanitize_key( unapp_design_field( $_GET, 'area' ) ), sanitize_key( unapp_design_field( $_GET, 'preset' ) ) );
	if ( is_wp_error( $data ) ) {
		wp_die( esc_html( $data->get_error_message() ), 400 );
	}
	nocache_headers();
	header( 'X-Frame-Options: SAMEORIGIN' );
	unapp_render_starter_preview_document( $data );
	exit;
}
add_action( 'admin_post_unapp_design_preview', 'unapp_handle_design_preview' );

/** Register and style only this Appearance screen. */
function unapp_design_menu() {
	add_theme_page( __( 'Make it yours', 'unapp' ), __( 'Make it yours', 'unapp' ), 'edit_theme_options', 'unapp-design', 'unapp_render_design_screen' );
}
add_action( 'admin_menu', 'unapp_design_menu' );
function unapp_design_assets( $hook ) {
	if ( 'appearance_page_unapp-design' === $hook ) {
		wp_enqueue_style( 'unapp-design', get_parent_theme_file_uri( 'assets/css/design.css' ), array(), UNAPP_VERSION );
		wp_enqueue_script( 'unapp-design', get_parent_theme_file_uri( 'assets/js/design.js' ), array(), UNAPP_VERSION, true );
	}
}
add_action( 'admin_enqueue_scripts', 'unapp_design_assets' );

/** Render native-editor shortcuts and a preview-before-apply preset library. */
function unapp_render_design_screen() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to change the site design.', 'unapp' ), 403 );
	}
	$presets = unapp_design_presets();
	$area = sanitize_key( unapp_design_field( $_GET, 'area' ) );
	$key = sanitize_key( unapp_design_field( $_GET, 'preset' ) );
	$selected = $presets[ $area ][ $key ] ?? null;
	$notice_key = 'unapp_design_notice_' . get_current_user_id();
	$notice = get_transient( $notice_key );
	delete_transient( $notice_key );
	$undo = get_option( UNAPP_DESIGN_UNDO );
	$shortcuts = array(
		array( __( 'Logo and site branding', 'unapp' ), __( 'Select the Site Logo or Site Title block in your header to change your branding.', 'unapp' ), unapp_design_editor_url( 'header' ) ),
		array( __( 'Colors, fonts and buttons', 'unapp' ), __( 'Open Styles to change your palette, typography and the Button block across the site.', 'unapp' ), add_query_arg( 'path', '/wp_global_styles', admin_url( 'site-editor.php' ) ) ),
		array( __( 'Navigation', 'unapp' ), __( 'Edit menu labels, links and order with WordPress’s navigation tools.', 'unapp' ), add_query_arg( 'path', '/navigation', admin_url( 'site-editor.php' ) ) ),
	);
	if ( current_user_can( 'manage_options' ) ) {
		$shortcuts[] = array( __( 'Business profile', 'unapp' ), __( 'Update shared business details, or add supporting pages to a one-page starter.', 'unapp' ), unapp_business_url() );
		$shortcuts[] = array( __( 'Site name and tagline', 'unapp' ), __( 'Change the name and short description used by your branding blocks.', 'unapp' ), admin_url( 'options-general.php' ) );
	}
	?>
	<div class="wrap unapp-design">
		<h1><?php esc_html_e( 'Make it yours', 'unapp' ); ?></h1>
		<p><a href="<?php echo esc_url( unapp_finish_url() ); ?>"><?php esc_html_e( 'Back to Finish your website', 'unapp' ); ?></a></p>
		<p class="unapp-design__intro"><?php esc_html_e( 'Start with your branding, choose a header and footer, then refine the details in WordPress. Everything stays editable with native blocks.', 'unapp' ); ?></p>
		<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=unapp-starter-sites' ) ); ?>"><?php esc_html_e( 'Starter Sites', 'unapp' ); ?></a> · <a href="<?php echo esc_url( unapp_launch_url() ); ?>"><?php esc_html_e( 'Launch checklist', 'unapp' ); ?></a> · <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'View your site', 'unapp' ); ?></a></p>
		<?php if ( $notice ) : ?><div class="notice <?php echo true === $notice ? 'notice-success' : 'notice-error'; ?>"><p><?php echo true === $notice ? esc_html__( 'Saved. You can continue refining the layout in the Site Editor.', 'unapp' ) : esc_html( $notice ); ?></p></div><?php endif; ?>
		<h2><?php esc_html_e( 'Your brand, everywhere', 'unapp' ); ?></h2>
		<div class="unapp-design__shortcuts">
			<?php foreach ( $shortcuts as $shortcut ) : ?>
				<section class="unapp-design__card"><h3><?php echo esc_html( $shortcut[0] ); ?></h3><p><?php echo esc_html( $shortcut[1] ); ?></p><a class="button" href="<?php echo esc_url( $shortcut[2] ); ?>"><?php /* translators: %s: branding task. */ printf( esc_html__( 'Edit %s', 'unapp' ), esc_html( $shortcut[0] ) ); ?></a></section>
			<?php endforeach; ?>
		</div>
		<?php if ( $selected ) : ?>
			<section class="unapp-design__review" id="unapp-preset-review" aria-labelledby="unapp-preset-title">
				<h2 id="unapp-preset-title"><?php /* translators: %s: selected layout name. */ printf( esc_html__( 'Preview: %s', 'unapp' ), esc_html( $selected['title'] ) ); ?></h2>
				<p><?php esc_html_e( 'Applying replaces this template part’s layout and content. Your logo and menu are carried over where the layout includes them. The latest preset can be undone unless the part is edited again; WordPress revisions are also kept when enabled. Review custom text, extra links or widgets before replacing them.', 'unapp' ); ?></p>
				<fieldset class="unapp-design__devices"><legend><?php esc_html_e( 'Preview width', 'unapp' ); ?></legend><label><input type="radio" name="design_width" value="desktop" checked> <?php esc_html_e( 'Desktop', 'unapp' ); ?></label><label><input type="radio" name="design_width" value="tablet"> <?php esc_html_e( 'Tablet', 'unapp' ); ?></label><label><input type="radio" name="design_width" value="mobile"> <?php esc_html_e( 'Mobile', 'unapp' ); ?></label></fieldset>
				<div class="unapp-design__canvas" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Layout preview', 'unapp' ); ?>"><iframe class="unapp-design__preview" src="<?php echo esc_url( unapp_design_preview_url( $area, $key ) ); ?>" title="<?php echo esc_attr( $selected['title'] ); ?>" sandbox="allow-scripts allow-same-origin"></iframe></div>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="unapp_design_save"><input type="hidden" name="area" value="<?php echo esc_attr( $area ); ?>"><input type="hidden" name="preset" value="<?php echo esc_attr( $key ); ?>"><input type="hidden" name="snapshot" value="<?php echo esc_attr( unapp_design_fingerprint( $area ) ); ?>">
					<?php wp_nonce_field( 'unapp_design_save' ); ?>
					<p><button class="button button-primary" type="submit"><?php echo 'header' === $area ? esc_html__( 'Apply this header', 'unapp' ) : esc_html__( 'Apply this footer', 'unapp' ); ?></button> <a class="button" href="<?php echo esc_url( unapp_design_editor_url( $area ) ); ?>"><?php esc_html_e( 'Edit the current layout instead', 'unapp' ); ?></a></p>
				</form>
			</section>
		<?php endif; ?>
		<?php foreach ( $presets as $part => $choices ) : ?>
			<section aria-labelledby="unapp-design-<?php echo esc_attr( $part ); ?>">
				<h2 id="unapp-design-<?php echo esc_attr( $part ); ?>"><?php echo 'header' === $part ? esc_html__( 'Header presets', 'unapp' ) : esc_html__( 'Footer presets', 'unapp' ); ?></h2>
				<p><a href="<?php echo esc_url( unapp_design_editor_url( $part ) ); ?>"><?php echo 'header' === $part ? esc_html__( 'Edit your current header in WordPress', 'unapp' ) : esc_html__( 'Edit your current footer in WordPress', 'unapp' ); ?></a></p>
				<div class="unapp-design__presets">
					<?php foreach ( $choices as $slug => $preset ) : ?>
						<article class="unapp-design__card">
							<div class="unapp-design__sketch unapp-design__sketch--<?php echo esc_attr( $part . '-' . $slug ); ?>" aria-hidden="true"><span></span><span></span><span></span></div>
							<h3><?php echo esc_html( $preset['title'] ); ?></h3><p><?php echo esc_html( $preset['text'] ); ?></p>
							<a class="button" href="<?php echo esc_url( add_query_arg( array( 'area' => $part, 'preset' => $slug ), unapp_design_url() ) . '#unapp-preset-review' ); ?>"><?php /* translators: %s: layout name. */ printf( esc_html__( 'Preview %s', 'unapp' ), esc_html( $preset['title'] ) ); ?></a>
						</article>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endforeach; ?>
		<?php if ( is_array( $undo ) && $undo['theme'] === get_stylesheet() ) : ?>
			<section class="unapp-design__review"><h2><?php esc_html_e( 'Undo the latest preset', 'unapp' ); ?></h2><p><?php esc_html_e( 'Restore the template part from before your last preset change. Undo stops if that part has since been edited.', 'unapp' ); ?></p><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="unapp_design_save"><input type="hidden" name="operation" value="undo"><input type="hidden" name="record" value="<?php echo esc_attr( $undo['id'] ); ?>"><?php wp_nonce_field( 'unapp_design_save' ); ?><button class="button" type="submit"><?php esc_html_e( 'Undo latest preset', 'unapp' ); ?></button></form></section>
		<?php endif; ?>
	</div>
	<?php
}
