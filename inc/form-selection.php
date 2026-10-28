<?php
/**
 * Select the contact form used by dynamic starter form slots.
 *
 * @package Unapp
 */
defined( 'ABSPATH' ) || exit;
const UNAPP_CONTACT_FORM_OPTION = 'unapp_contact_form';

/** @return int Per-request form cache revision. */
function unapp_form_cache_revision( $reset = false ) {
	static $revision = 0;
	if ( $reset ) {
		++$revision;
	}
	return $revision;
}

/** Invalidate detection when a form post or the saved selection changes. */
function unapp_reset_detected_form() {
	unapp_form_cache_revision( true );
}
add_action( 'clean_post_cache', 'unapp_reset_detected_form' );
add_action( 'update_option_' . UNAPP_CONTACT_FORM_OPTION, 'unapp_reset_detected_form' );
add_action( 'add_option_' . UNAPP_CONTACT_FORM_OPTION, 'unapp_reset_detected_form' );
add_action( 'delete_option_' . UNAPP_CONTACT_FORM_OPTION, 'unapp_reset_detected_form' );

/** @return array Gravity Forms IDs and titles, excluding inactive/trashed forms. */
function unapp_gravity_forms() {
	$forms = array();
	if ( class_exists( 'GFAPI' ) ) {
		foreach ( GFAPI::get_forms( true, false, 'id', 'ASC' ) as $form ) {
			$forms[ absint( $form['id'] ) ] = $form['title'];
		}
	}
	return $forms;
}

/** @return array Fluent Forms IDs and titles for published forms. */
function unapp_fluent_forms() {
	global $wpdb;
	$forms = array();
	if ( defined( 'FLUENTFORM' ) ) {
		$table = $wpdb->prefix . 'fluentform_forms';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery -- read the plugin's existing form table; no submission data.
		$rows = $wpdb->get_results( "SELECT id, title FROM {$table} WHERE status = 'published' ORDER BY id ASC", ARRAY_A );
		foreach ( (array) $rows as $form ) {
			$forms[ absint( $form['id'] ) ] = $form['title'];
		}
	}
	return $forms;
}

/** @return array Ninja Forms IDs and titles from its form model API. */
function unapp_ninja_forms() {
	$forms = array();
	if ( function_exists( 'Ninja_Forms' ) ) {
		foreach ( Ninja_Forms()->form()->get_forms() as $form ) {
			if ( method_exists( $form, 'get_id' ) && method_exists( $form, 'get_setting' ) ) {
				$forms[ absint( $form->get_id() ) ] = $form->get_setting( 'title' );
			}
		}
	}
	return $forms;
}

/**
 * List saved forms without executing their shortcodes or processing submissions.
 *
 * @param array $provider Provider definition; forms_callback returns ID => title.
 * @return array
 */
function unapp_form_provider_forms( $provider ) {
	if ( ! empty( $provider['forms_callback'] ) && is_callable( $provider['forms_callback'] ) ) {
		$forms = call_user_func( $provider['forms_callback'] );
		return is_array( $forms ) ? $forms : array();
	}
	$forms = array();
	if ( ! empty( $provider['post_type'] ) && post_type_exists( $provider['post_type'] ) ) {
		foreach ( get_posts( array( 'post_type' => $provider['post_type'], 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'ID', 'order' => 'ASC', 'suppress_filters' => false ) ) as $post ) {
			$forms[ $post->ID ] = $post->post_title;
		}
	} elseif ( ! empty( $provider['callback'] ) ) {
		// Existing custom providers that expose only a first-form callback still work.
		$id = unapp_form_provider_id( $provider );
		if ( $id ) {
			$forms[ $id ] = '';
		}
	}
	return $forms;
}

/**
 * Resolve one known form, validating the provider and the saved form's availability.
 *
 * @param string   $key      Provider key.
 * @param array    $provider Provider definition.
 * @param int|null $id       Specific form ID, null for the provider's first form.
 * @return array|null
 */
function unapp_contact_form_candidate( $key, $provider, $id = null ) {
	if ( ! unapp_form_provider_active( $provider ) ) {
		return null;
	}
	if ( ! empty( $provider['block'] ) ) {
		return null === $id || 0 === $id ? array( 'key' => $key, 'id' => 0, 'label' => $provider['label'], 'markup' => '<!-- wp:' . $provider['block'] . ' /-->' ) : null;
	}
	if ( empty( $provider['shortcode'] ) || ! preg_match( '/^\[([a-zA-Z0-9_-]+)/', $provider['shortcode'], $match ) || ! shortcode_exists( $match[1] ) ) {
		return null;
	}
	$forms = null;
	if ( null === $id && ! empty( $provider['forms_callback'] ) ) {
		$forms = unapp_form_provider_forms( $provider );
		$id = $forms ? absint( array_key_first( $forms ) ) : 0;
	} else {
		$id = null === $id ? unapp_form_provider_id( $provider ) : $id;
	}
	if ( ! $id ) {
		return null;
	}
	if ( ! empty( $provider['post_type'] ) && empty( $provider['forms_callback'] ) ) {
		$post = get_post( $id );
		if ( ! $post || $post->post_type !== $provider['post_type'] || 'publish' !== $post->post_status ) {
			return null;
		}
		$title = $post->post_title;
	} else {
		$forms = null === $forms ? unapp_form_provider_forms( $provider ) : $forms;
		if ( ! array_key_exists( $id, $forms ) ) {
			return null;
		}
		$title = $forms[ $id ];
	}
	return array( 'key' => $key, 'id' => $id, 'label' => $provider['label'], 'title' => $title, 'markup' => sprintf( $provider['shortcode'], $id ) );
}

/**
 * Preserve automatic detection unless the owner explicitly chooses another mode.
 *
 * @param mixed $choice Saved choice: auto, email, or provider:ID.
 * @return array|null Missing selected forms deliberately fall back to email.
 */
function unapp_resolve_contact_form( $choice ) {
	$providers = unapp_form_providers();
	if ( 'auto' === $choice ) {
		foreach ( $providers as $key => $provider ) {
			$form = unapp_contact_form_candidate( $key, $provider );
			if ( $form ) {
				return $form;
			}
		}
	} elseif ( is_string( $choice ) && preg_match( '/^([a-z0-9_-]+):([0-9]+)$/', $choice, $match ) && isset( $providers[ $match[1] ] ) ) {
		return unapp_contact_form_candidate( $match[1], $providers[ $match[1] ], (int) $match[2] );
	}
	return null;
}

/** @return array Valid selector choices, keyed by provider:ID. */
function unapp_available_contact_forms() {
	$choices = array();
	foreach ( unapp_form_providers() as $key => $provider ) {
		if ( ! unapp_form_provider_active( $provider ) ) {
			continue;
		}
		if ( ! empty( $provider['block'] ) ) {
			$choices[ $key . ':0' ] = $provider['label'];
			continue;
		}
		if ( empty( $provider['shortcode'] ) || ! preg_match( '/^\[([a-zA-Z0-9_-]+)/', $provider['shortcode'], $match ) || ! shortcode_exists( $match[1] ) ) {
			continue;
		}
		foreach ( unapp_form_provider_forms( $provider ) as $id => $title ) {
			$id = absint( $id );
			if ( $id ) {
				/* translators: 1: form plugin, 2: saved form title, 3: form ID. */
				$choices[ $key . ':' . $id ] = sprintf( __( '%1$s — %2$s (#%3$d)', 'unapp' ), $provider['label'], $title ? wp_strip_all_tags( $title ) : __( 'Untitled form', 'unapp' ), $id );
			}
		}
	}
	return $choices;
}

/**
 * Save only a currently available form, never caller-supplied shortcode markup.
 *
 * @param string $choice Selection value.
 * @return true|WP_Error
 */
function unapp_set_contact_form( $choice ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return new WP_Error( 'unapp_form_forbidden', __( 'You are not allowed to change the contact form.', 'unapp' ) );
	}
	if ( ! is_string( $choice ) || ( ! in_array( $choice, array( 'auto', 'email' ), true ) && ! isset( unapp_available_contact_forms()[ $choice ] ) ) ) {
		return new WP_Error( 'unapp_form_missing', __( 'That form is no longer available. Choose an available form, automatic detection, or email only.', 'unapp' ) );
	}
	update_option( UNAPP_CONTACT_FORM_OPTION, $choice, false );
	unapp_reset_detected_form();
	return get_option( UNAPP_CONTACT_FORM_OPTION ) === $choice ? true : new WP_Error( 'unapp_form_save', __( 'The contact form choice could not be saved.', 'unapp' ) );
}

/** @return string Context-aware, administrator-only fallback guidance. */
function unapp_contact_form_hint() {
	$choice = get_option( UNAPP_CONTACT_FORM_OPTION, 'auto' );
	if ( 'email' === $choice ) {
		return __( 'Only you can see this: email-only contact is selected.', 'unapp' );
	}
	return 'auto' === $choice
		? __( 'Only you can see this: create a form in a supported plugin, then choose it in the launch checklist.', 'unapp' )
		: __( 'Only you can see this: the selected form is unavailable, so visitors see the email fallback.', 'unapp' );
}

/** Handle a nonce-protected contact form selection. */
function unapp_handle_contact_form_selection() {
	check_admin_referer( 'unapp_contact_form' );
	$choice = isset( $_POST['contact_form'] ) && is_string( $_POST['contact_form'] ) ? wp_unslash( $_POST['contact_form'] ) : '';
	$result = unapp_set_contact_form( $choice );
	set_transient( 'unapp_launch_notice_' . get_current_user_id(), is_wp_error( $result ) ? array( 'error' => $result->get_error_message() ) : array( 'saved' => true ), 5 * MINUTE_IN_SECONDS );
	wp_safe_redirect( admin_url( 'themes.php?page=unapp-launch-checklist#unapp-contact-form' ) );
	exit;
}
add_action( 'admin_post_unapp_contact_form', 'unapp_handle_contact_form_selection' );

/** Render the selector without rendering or submitting any plugin form. */
function unapp_render_contact_form_selection() {
	$choice = get_option( UNAPP_CONTACT_FORM_OPTION, 'auto' );
	$choice = is_string( $choice ) ? $choice : 'invalid';
	$forms  = unapp_available_contact_forms();
	$current = unapp_detect_form();
	?>
	<section class="unapp-launch__panel" id="unapp-contact-form" aria-labelledby="unapp-form-heading">
		<h2 id="unapp-form-heading"><?php esc_html_e( 'Contact form', 'unapp' ); ?></h2>
		<p><?php esc_html_e( 'Choose the form used by Unapp contact sections across your site. You can change the form without editing those pages. Your form plugin handles submissions and email delivery.', 'unapp' ); ?></p>
		<?php if ( ! in_array( $choice, array( 'auto', 'email' ), true ) && ! isset( $forms[ $choice ] ) ) : ?>
			<p class="unapp-launch__warning"><?php esc_html_e( 'The selected form is unavailable. Visitors see the saved email fallback until you choose another form. Other forms are not selected automatically.', 'unapp' ); ?></p>
		<?php endif; ?>
		<p><strong><?php esc_html_e( 'Currently shown:', 'unapp' ); ?></strong> <?php echo esc_html( $current ? wp_strip_all_tags( $current['title'] ?? $current['label'] ?? __( 'Custom form', 'unapp' ) ) : __( 'Email fallback', 'unapp' ) ); ?></p>
		<?php if ( false !== has_filter( 'unapp_detected_form' ) ) : ?>
			<p><?php esc_html_e( 'A developer filter can override this selection.', 'unapp' ); ?></p>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'unapp_contact_form' ); ?>
			<input type="hidden" name="action" value="unapp_contact_form">
			<label for="unapp-contact-form-choice"><?php esc_html_e( 'Form to display', 'unapp' ); ?></label>
			<select id="unapp-contact-form-choice" name="contact_form">
				<?php if ( ! in_array( $choice, array( 'auto', 'email' ), true ) && ! isset( $forms[ $choice ] ) ) : ?><option value="" selected disabled><?php esc_html_e( 'Previously selected form (unavailable)', 'unapp' ); ?></option><?php endif; ?>
				<option value="auto" <?php selected( $choice, 'auto' ); ?>><?php esc_html_e( 'Automatic — first available form', 'unapp' ); ?></option>
				<option value="email" <?php selected( $choice, 'email' ); ?>><?php esc_html_e( 'Email only — use the saved email links', 'unapp' ); ?></option>
				<?php foreach ( $forms as $value => $label ) : ?><option value="<?php echo esc_attr( $value ); ?>" <?php selected( $choice, $value ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?>
			</select>
			<p><button type="submit" class="button button-primary"><?php esc_html_e( 'Save contact form', 'unapp' ); ?></button></p>
		</form>
		<p class="description"><?php esc_html_e( 'Missing a form? Create and publish it in its plugin, then reload this screen. Forms added directly in the page editor keep their own settings. Jetpack offers its default contact-form block.', 'unapp' ); ?></p>
		<?php if ( current_user_can( 'install_plugins' ) ) : ?><p><a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=search&s=contact%20form' ) ); ?>"><?php esc_html_e( 'Find a form plugin', 'unapp' ); ?></a></p><?php endif; ?>
	</section>
	<?php
}
