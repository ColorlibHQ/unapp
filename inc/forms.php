<?php
/**
 * Contact forms.
 *
 * A theme must not process form submissions — that is plugin territory, and
 * WordPress.org rejects themes that do it. So Unapp does the next best thing:
 * it finds whichever form plugin is already active, renders that plugin's
 * first form, and styles the result to match the palette. When no form plugin
 * is installed, the contact patterns fall back to an email panel that still
 * gives a visitor a way to get in touch.
 *
 * @package Unapp
 */

defined( 'ABSPATH' ) || exit;

/**
 * The form plugins Unapp knows how to render, in the order they are preferred.
 *
 * Each provider declares how to find a form and how to render it. 'block' is
 * checked first because a block keeps the form editable in the Site Editor;
 * 'shortcode' is the fallback for plugins whose block needs configuration.
 *
 * @return array[]
 */
function unapp_form_providers() {
	$providers = array(
		'wpforms'     => array(
			'label'     => 'WPForms',
			'active'    => array( 'wpforms', 'WPFORMS_VERSION' ),
			'post_type' => 'wpforms',
			'shortcode' => '[wpforms id="%d"]',
		),
		'gravity'     => array(
			'label'     => 'Gravity Forms',
			'active'    => array( 'GFForms', 'GFAPI' ),
			'callback'  => 'unapp_gravity_first_form',
			'shortcode' => '[gravityform id="%d" title="false"]',
		),
		'cf7'         => array(
			'label'     => 'Contact Form 7',
			'active'    => array( 'WPCF7', 'WPCF7_VERSION' ),
			'post_type' => 'wpcf7_contact_form',
			'shortcode' => '[contact-form-7 id="%d"]',
		),
		'fluent'      => array(
			'label'     => 'Fluent Forms',
			'active'    => array( 'FLUENTFORM', 'FLUENTFORM_VERSION' ),
			'callback'  => 'unapp_fluent_first_form',
			'shortcode' => '[fluentform id="%d"]',
		),
		'forminator'  => array(
			'label'     => 'Forminator',
			'active'    => array( 'Forminator', 'FORMINATOR_VERSION' ),
			'post_type' => 'forminator_forms',
			'shortcode' => '[forminator_form id="%d"]',
		),
		'ninja'       => array(
			'label'     => 'Ninja Forms',
			'active'    => array( 'Ninja_Forms', 'NF_PLUGIN_VERSION' ),
			'callback'  => 'unapp_ninja_first_form',
			'shortcode' => '[ninja_form id=%d]',
		),
		'kali'        => array(
			'label'     => 'Kali Forms',
			'active'    => 'KALIFORMS_VERSION',
			'post_type' => 'kaliforms_forms',
			'shortcode' => '[kaliform id=%d]',
		),
		'everest'     => array(
			'label'     => 'Everest Forms',
			'active'    => array( 'EVF', 'EVF_VERSION' ),
			'post_type' => 'everest_form',
			'shortcode' => '[everest_form id="%d"]',
		),
		'happyforms'  => array(
			'label'     => 'HappyForms',
			'active'    => array( 'HappyForms', 'HAPPYFORMS_VERSION' ),
			'post_type' => 'happyform',
			'shortcode' => '[happyforms id="%d"]',
		),
		'jetpack'     => array(
			'label' => 'Jetpack',
			'block' => 'jetpack/contact-form',
		),
	);

	/**
	 * Filters the form plugins Unapp can render.
	 *
	 * @param array[] $providers Provider definitions.
	 */
	return apply_filters( 'unapp_form_providers', $providers );
}

/**
 * Whether a provider's plugin is active.
 *
 * @param array $provider Provider definition.
 * @return bool
 */
function unapp_form_provider_active( $provider ) {
	if ( ! empty( $provider['block'] ) ) {
		return WP_Block_Type_Registry::get_instance()->is_registered( $provider['block'] );
	}

	// A registered post type is the most reliable signal: plugins namespace
	// their classes differently between versions, but the post type is public
	// API and does not move.
	if ( ! empty( $provider['post_type'] ) && post_type_exists( $provider['post_type'] ) ) {
		return true;
	}

	if ( empty( $provider['active'] ) ) {
		return false;
	}

	foreach ( (array) $provider['active'] as $symbol ) {
		if ( class_exists( $symbol ) || defined( $symbol ) || function_exists( $symbol ) ) {
			return true;
		}
	}

	return false;
}

/**
 * The newest published form for a provider, if it has one.
 *
 * @param array $provider Provider definition.
 * @return int Form ID, or 0.
 */
function unapp_form_provider_id( $provider ) {
	if ( ! empty( $provider['callback'] ) && is_callable( $provider['callback'] ) ) {
		return absint( call_user_func( $provider['callback'] ) );
	}

	if ( empty( $provider['post_type'] ) || ! post_type_exists( $provider['post_type'] ) ) {
		return 0;
	}

	$forms = get_posts(
		array(
			'post_type'        => $provider['post_type'],
			'post_status'      => 'publish',
			'numberposts'      => 1,
			'orderby'          => 'ID',
			'order'            => 'ASC',
			'fields'           => 'ids',
			'suppress_filters' => false,
		)
	);

	return $forms ? absint( $forms[0] ) : 0;
}

/**
 * First Gravity Forms form.
 *
 * @return int
 */
function unapp_gravity_first_form() {
	if ( ! class_exists( 'GFAPI' ) ) {
		return 0;
	}
	$forms = GFAPI::get_forms();
	return $forms && isset( $forms[0]['id'] ) ? absint( $forms[0]['id'] ) : 0;
}

/**
 * First Fluent Forms form.
 *
 * @return int
 */
function unapp_fluent_first_form() {
	global $wpdb;
	if ( ! defined( 'FLUENTFORM' ) ) {
		return 0;
	}
	$table = $wpdb->prefix . 'fluentform_forms';
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery -- Fluent Forms has no API for this.
	$id = $wpdb->get_var( "SELECT id FROM {$table} ORDER BY id ASC LIMIT 1" );
	return absint( $id );
}

/**
 * First Ninja Forms form.
 *
 * @return int
 */
function unapp_ninja_first_form() {
	if ( ! function_exists( 'Ninja_Forms' ) ) {
		return 0;
	}
	$forms = Ninja_Forms()->form()->get_forms();
	return $forms && method_exists( $forms[0], 'get_id' ) ? absint( $forms[0]->get_id() ) : 0;
}

/**
 * The form Unapp will render, if any.
 *
 * @return array|null array( 'key', 'label', 'markup' ), or null when nothing is available.
 */
function unapp_detect_form() {
	static $detected = false;

	if ( false !== $detected ) {
		return $detected;
	}

	$detected = null;

	foreach ( unapp_form_providers() as $key => $provider ) {
		if ( ! unapp_form_provider_active( $provider ) ) {
			continue;
		}

		// A self-contained block needs no form ID.
		if ( ! empty( $provider['block'] ) ) {
			$detected = array(
				'key'    => $key,
				'label'  => $provider['label'],
				'markup' => '<!-- wp:' . $provider['block'] . ' /-->',
			);
			break;
		}

		$id = unapp_form_provider_id( $provider );
		if ( ! $id ) {
			continue;
		}

		$detected = array(
			'key'    => $key,
			'label'  => $provider['label'],
			'markup' => sprintf( $provider['shortcode'], $id ),
		);
		break;
	}

	/**
	 * Filters the form Unapp renders in its contact patterns.
	 *
	 * Return an array with a 'markup' key to render your own form, or null to
	 * force the email fallback.
	 *
	 * @param array|null $detected Detected form.
	 */
	return apply_filters( 'unapp_detected_form', $detected );
}

/**
 * Render the contact form, or the fallback when no form plugin is available.
 *
 * @param array $args {
 *     @type string $title    Heading above the form.
 *     @type string $email    Address used by the fallback panel.
 *     @type string $fallback Sentence shown above the fallback button.
 * }
 * @return string Block markup, ready for do_blocks().
 */
function unapp_contact_form( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'title'    => _x( 'Send a message', 'Contact form heading', 'unapp' ),
			'email'    => 'hello@example.com',
			'fallback' => _x( 'The quickest way to reach us is email — we answer within two working days.', 'Contact fallback text', 'unapp' ),
		)
	);

	$form = unapp_detect_form();
	$out  = '<!-- wp:heading {"level":3,"fontSize":"large"} --><h3 class="wp-block-heading has-large-font-size">'
		. esc_html( $args['title'] ) . '</h3><!-- /wp:heading -->';

	if ( $form && ! empty( $form['markup'] ) ) {
		$inner = 0 === strpos( $form['markup'], '<!-- wp:' )
			? $form['markup']
			// A shortcode has to travel inside a Shortcode block to survive the editor.
			: '<!-- wp:shortcode -->' . $form['markup'] . '<!-- /wp:shortcode -->';

		// The wrapper is what assets/css/forms.css styles: every plugin names its
		// own container differently, but they all render fields inside this one.
		return $out
			. '<!-- wp:group {"className":"unapp-form","layout":{"type":"default"}} -->'
			. '<div class="wp-block-group unapp-form">' . $inner . '</div>'
			. '<!-- /wp:group -->';
	}

	$out .= '<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->'
		. '<p class="has-muted-color has-text-color has-small-font-size">'
		. esc_html( $args['fallback'] ) . '</p><!-- /wp:paragraph -->';
	$out .= '<!-- wp:buttons --><div class="wp-block-buttons">'
		. '<!-- wp:button --><div class="wp-block-button">'
		. '<a class="wp-block-button__link wp-element-button" href="mailto:' . esc_attr( $args['email'] ) . '">'
		. esc_html( $args['email'] ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons -->';

	if ( current_user_can( 'edit_theme_options' ) ) {
		$out .= '<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->'
			. '<p class="has-muted-color has-text-color has-small-font-size"><em>'
			. esc_html__( 'Only you can see this: install any form plugin — WPForms, Contact Form 7, Gravity Forms, Fluent Forms and several others are recognised — and its form replaces this panel automatically.', 'unapp' )
			. '</em></p><!-- /wp:paragraph -->';
	}

	return $out;
}

/**
 * Load the form stylesheet only on pages that render a form.
 *
 * The selectors cover the field markup of the supported plugins, so their
 * inputs and buttons inherit the palette instead of the plugin's own defaults.
 */
function unapp_form_styles() {
	if ( ! unapp_detect_form() ) {
		return;
	}

	wp_enqueue_style(
		'unapp-forms',
		get_theme_file_uri( 'assets/css/forms.css' ),
		array(),
		UNAPP_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'unapp_form_styles' );
