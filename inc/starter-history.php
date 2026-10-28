<?php
/**
 * Restore starter setup without overwriting edits made since it completed.
 *
 * @package Unapp
 */
defined( 'ABSPATH' ) || exit;
const UNAPP_STARTER_UNDO_OPTION = 'unapp_starter_undo';

/** @return array|WP_Error Exclusive import/undo lock. */
function unapp_acquire_starter_lock() {
	global $wpdb;
	$lock = array( 'time' => time(), 'id' => wp_generate_uuid4() );
	$old  = get_option( UNAPP_STARTER_LOCK );
	if ( is_array( $old ) && isset( $old['time'] ) && $old['time'] < time() - 10 * MINUTE_IN_SECONDS ) {
		$wpdb->delete( $wpdb->options, array( 'option_name' => UNAPP_STARTER_LOCK, 'option_value' => maybe_serialize( $old ) ) );
		wp_cache_delete( UNAPP_STARTER_LOCK, 'options' );
	}
	return add_option( UNAPP_STARTER_LOCK, $lock, '', false ) ? $lock : new WP_Error( 'unapp_import_busy', __( 'Another starter is being built. Wait for it to finish before trying again.', 'unapp' ) );
}

/**
 * Fingerprint meaningful edits; merely opening the editor must not block Undo.
 *
 * @param int $id Post ID.
 * @return string
 */
function unapp_starter_post_fingerprint( $id ) {
	$post = get_post( $id, ARRAY_A );
	if ( ! $post ) {
		return '';
	}
	$post = array_intersect_key( $post, array_flip( array( 'ID', 'post_title', 'post_content', 'post_excerpt', 'post_status', 'post_name', 'post_parent', 'menu_order', 'post_author', 'comment_status', 'ping_status', 'post_date', 'post_date_gmt', 'post_type' ) ) );
	ksort( $post );
	$meta = get_post_meta( $id );
	unset( $meta['_edit_lock'], $meta['_edit_last'] );
	ksort( $meta );
	return hash( 'sha256', wp_json_encode( array( $post, $meta ) ) );
}

/**
 * Keep only the most recent setup's snapshot, without nesting earlier backups.
 *
 * @param array  $before     Existing posts before setup.
 * @param array  $new        Created post IDs and types.
 * @param array  $options    Previous option values.
 * @param string $request_id Completed form identifier.
 * @param string $kind       Recovery operation type.
 * @return true|WP_Error
 */
function unapp_record_starter_undo( $before, $new, $options, $request_id, $kind = 'setup' ) {
	$after_options = array();
	foreach ( $options as $name => $unused ) {
		$after_options[ $name ] = get_option( $name, null );
	}
	$hashes = array();
	foreach ( array_unique( array_merge( array_keys( $before ), array_keys( $new ) ) ) as $id ) {
		$hashes[ $id ] = unapp_starter_post_fingerprint( $id );
	}
	$record = array( 'id' => wp_generate_uuid4(), 'theme' => get_stylesheet(), 'before' => $before, 'new' => $new, 'options' => $options, 'hashes' => $hashes, 'after_options' => $after_options, 'request' => $request_id, 'kind' => $kind );
	update_option( UNAPP_STARTER_UNDO_OPTION, $record, false );
	return get_option( UNAPP_STARTER_UNDO_OPTION ) === $record ? true : new WP_Error( 'unapp_starter_save', __( 'The setup recovery copy could not be saved.', 'unapp' ) );
}

/**
 * Restore original records and settings; retain created pages in the Trash.
 *
 * @param array $before  Original post records.
 * @param array $new     New IDs and post types.
 * @param array $options Original options.
 * @return bool Whether every operation succeeded.
 */
function unapp_restore_starter_state( $before, $new, $options ) {
	$restored = true;
	foreach ( $before as $post ) {
		unapp_keep_revision( $post['ID'] );
		$saved = wp_update_post( wp_slash( $post ), true );
		$restored = $restored && $saved && ! is_wp_error( $saved );
	}
	foreach ( $new as $id => $type ) {
		// With Trash disabled, retain a draft rather than permanently delete a page.
		$saved = 'page' === $type
			? ( EMPTY_TRASH_DAYS ? wp_trash_post( $id ) : wp_update_post( array( 'ID' => $id, 'post_status' => 'draft' ), true ) )
			: wp_delete_post( $id, true );
		$restored = $restored && $saved && ! is_wp_error( $saved );
	}
	foreach ( $options as $name => $value ) {
		if ( null === $value ) {
			delete_option( $name );
		} else {
			update_option( $name, $value );
		}
		$restored = $restored && unapp_starter_options_equal( get_option( $name, null ), $value );
	}
	WP_Theme_JSON_Resolver::clean_cached_data();
	return (bool) $restored;
}

/**
 * Compare options as stored; WordPress caches numbers as integers but reads strings.
 *
 * @param mixed $first First value, null means absent.
 * @param mixed $second Second value.
 * @return bool
 */
function unapp_starter_options_equal( $first, $second ) {
	return null === $first || null === $second ? $first === $second : (string) maybe_serialize( $first ) === (string) maybe_serialize( $second );
}

/** @return true|WP_Error Whether the last setup is still safe to undo. */
function unapp_starter_can_undo() {
	$record = get_option( UNAPP_STARTER_UNDO_OPTION );
	if ( ! is_array( $record ) || ( $record['theme'] ?? '' ) !== get_stylesheet() ) {
		return new WP_Error( 'unapp_no_undo', __( 'There is no starter setup to undo.', 'unapp' ) );
	}
	foreach ( $record['hashes'] as $id => $hash ) {
		if ( $hash !== unapp_starter_post_fingerprint( $id ) ) {
			return new WP_Error( 'unapp_undo_changed', __( 'Pages or design settings have changed since setup. Undo is unavailable to protect your edits. Use page and style revisions to restore individual changes.', 'unapp' ) );
		}
	}
	foreach ( $record['after_options'] as $name => $value ) {
		if ( ! unapp_starter_options_equal( get_option( $name, null ), $value ) ) {
			return new WP_Error( 'unapp_undo_changed', __( 'Pages or design settings have changed since setup. Undo is unavailable to protect your edits. Use page and style revisions to restore individual changes.', 'unapp' ) );
		}
	}
	return true;
}

/**
 * Undo the reviewed setup only; an old browser tab must not undo a newer run.
 *
 * @param string $record_id Snapshot identifier from the form.
 * @return true|WP_Error
 */
function unapp_undo_starter_site( $record_id ) {
	$lock = unapp_acquire_starter_lock();
	if ( is_wp_error( $lock ) ) {
		return $lock;
	}
	$kses_active = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	try {
		$record = get_option( UNAPP_STARTER_UNDO_OPTION );
		if ( ! is_array( $record ) || $record['id'] !== $record_id ) {
			return new WP_Error( 'unapp_no_undo', __( 'This setup is no longer the latest one. Refresh the starter screen.', 'unapp' ) );
		}
		$allowed = unapp_starter_can_undo();
		if ( is_wp_error( $allowed ) ) {
			return $allowed;
		}
		if ( $kses_active ) {
			kses_remove_filters();
		}
		if ( ! unapp_restore_starter_state( $record['before'], $record['new'], $record['options'] ) ) {
			return new WP_Error( 'unapp_import_recovery', __( 'Some setup changes could not be restored.', 'unapp' ) );
		}
		delete_option( UNAPP_STARTER_UNDO_OPTION );
		if ( $record['request'] ) {
			set_transient( 'unapp_import_' . md5( $record['request'] ), array( 'undone' => true ), DAY_IN_SECONDS );
		}
		return true;
	} catch ( Throwable $error ) {
		return new WP_Error( 'unapp_import_recovery', __( 'Some setup changes could not be restored.', 'unapp' ) );
	} finally {
		if ( $kses_active ) {
			kses_init_filters();
		}
		if ( get_option( UNAPP_STARTER_LOCK ) === $lock ) {
			delete_option( UNAPP_STARTER_LOCK );
		}
	}
}

/** Handle the nonce-protected Undo form. */
function unapp_handle_starter_undo() {
	check_admin_referer( 'unapp_undo_starter' );
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to change the site design.', 'unapp' ), 403 );
	}
	$id = isset( $_POST['record_id'] ) ? sanitize_key( wp_unslash( $_POST['record_id'] ) ) : '';
	$result = unapp_undo_starter_site( $id );
	set_transient( UNAPP_STARTER_RESULT, is_wp_error( $result ) ? 'error:' . $result->get_error_code() : 'undone', HOUR_IN_SECONDS );
	wp_safe_redirect( admin_url( 'themes.php?page=unapp-starter-sites' ) );
	exit;
}
add_action( 'admin_post_unapp_undo_starter', 'unapp_handle_starter_undo' );

/** Show the recovery action and explain edit conflicts. */
function unapp_render_starter_undo() {
	$record = get_option( UNAPP_STARTER_UNDO_OPTION );
	if ( ! is_array( $record ) || ( $record['theme'] ?? '' ) !== get_stylesheet() ) {
		return;
	}
	$allowed = unapp_starter_can_undo();
	?>
	<div class="unapp-wizard__panel">
		<h3><?php echo 'pages' === ( $record['kind'] ?? '' ) ? esc_html__( 'Undo the last page addition', 'unapp' ) : esc_html__( 'Undo the last setup', 'unapp' ); ?></h3>
		<?php if ( is_wp_error( $allowed ) ) : ?>
			<p><?php echo esc_html( $allowed->get_error_message() ); ?></p>
		<?php else : ?>
			<p><?php echo 'pages' === ( $record['kind'] ?? '' ) ? esc_html__( 'Restore the one-page page list and previous menu. Newly added pages move to the Trash or become drafts. Later page or menu edits prevent Undo.', 'unapp' ) : esc_html__( 'Restore the previous design, menu, branding and front-page settings. New starter pages move to the Trash; older pages stay in place. If Trash is disabled, new pages become drafts. Undo becomes unavailable after you edit the new pages or design.', 'unapp' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'unapp_undo_starter' ); ?>
				<input type="hidden" name="action" value="unapp_undo_starter">
				<input type="hidden" name="record_id" value="<?php echo esc_attr( $record['id'] ); ?>">
				<button type="submit" class="button"><?php echo 'pages' === ( $record['kind'] ?? '' ) ? esc_html__( 'Undo page addition', 'unapp' ) : esc_html__( 'Undo this setup', 'unapp' ); ?></button>
			</form>
		<?php endif; ?>
	</div>
	<?php
}
