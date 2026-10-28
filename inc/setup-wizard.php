<?php
/**
 * Setup wizard.
 *
 * Picking a starter is only half of setting up a site. The wizard adds the
 * other half: the site's own name and logo, which palette and typeface to use,
 * and the plugins the chosen starter actually depends on — a contact form has
 * to come from somewhere, and the Shop starter is not much use without
 * WooCommerce.
 *
 * Plugins are never installed silently. The wizard links to core's own
 * installer with a proper nonce, so WordPress does the installing and the user
 * sees what is happening.
 *
 * @package Unapp
 */

defined( 'ABSPATH' ) || exit;

/**
 * Plugins the wizard offers, per starter.
 *
 * 'form' is offered for every starter, because every starter builds a Contact
 * page and the theme renders whichever form plugin is present.
 *
 * @return array[]
 */
function unapp_wizard_plugins() {
	$plugins = array(
		'form'      => array(
			'slug'   => 'contact-form-7',
			'file'   => 'contact-form-7/wp-contact-form-7.php',
			'label'  => __( 'Contact Form 7', 'unapp' ),
			'reason' => __( 'Your Contact page renders whichever form plugin you have. Any of the ten Unapp recognises will do — this is the smallest.', 'unapp' ),
		),
		'shop'      => array(
			'slug'      => 'woocommerce',
			'file'      => 'woocommerce/woocommerce.php',
			'label'     => __( 'WooCommerce', 'unapp' ),
			'reason'    => __( 'The Shop starter builds a storefront. Without WooCommerce there are no products to put in it.', 'unapp' ),
			'starters'  => array( 'shop' ),
		),
	);

	/**
	 * Filters the plugins the setup wizard offers.
	 *
	 * @param array[] $plugins Plugin definitions.
	 */
	return apply_filters( 'unapp_wizard_plugins', $plugins );
}

/**
 * Whether a plugin file is active.
 *
 * @param string $file Plugin file, relative to the plugins directory.
 * @return bool
 */
function unapp_plugin_active( $file ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	return is_plugin_active( $file );
}

/**
 * The plugins worth offering for a starter, minus anything already covered.
 *
 * @param string $starter Starter slug.
 * @return array[]
 */
function unapp_wizard_plugin_suggestions( $starter ) {
	$out = array();

	foreach ( unapp_wizard_plugins() as $key => $plugin ) {
		if ( ! empty( $plugin['starters'] ) && ! in_array( $starter, (array) $plugin['starters'], true ) ) {
			continue;
		}

		// A form plugin is only worth suggesting when none is present at all.
		if ( 'form' === $key && ( 'email' === get_option( UNAPP_CONTACT_FORM_OPTION, 'auto' ) || unapp_detect_form() ) ) {
			continue;
		}

		if ( unapp_plugin_active( $plugin['file'] ) ) {
			continue;
		}

		$out[ $key ] = $plugin;
	}

	return $out;
}

/**
 * Install-and-activate URL for a plugin, handled by core.
 *
 * @param string $slug Plugin directory slug.
 * @return string
 */
function unapp_plugin_install_url( $slug ) {
	return wp_nonce_url(
		self_admin_url( 'update.php?action=install-plugin&plugin=' . rawurlencode( $slug ) ),
		'install-plugin_' . $slug
	);
}

/**
 * Whether a plugin's files are present, active or not.
 *
 * @param string $file Plugin file, relative to the plugins directory.
 * @return bool
 */
function unapp_plugin_installed( $file ) {
	return file_exists( WP_PLUGIN_DIR . '/' . $file );
}

/**
 * The action a user can take for a suggested plugin: install it, activate it,
 * or nothing when their role allows neither.
 *
 * Core refuses to install over an existing folder ("Destination folder already
 * exists"), so an installed-but-inactive plugin needs the activate link instead.
 *
 * @param array $plugin Plugin definition from unapp_wizard_plugins().
 * @return array|null array( 'url', 'label' ), or null.
 */
function unapp_plugin_action( $plugin ) {
	if ( unapp_plugin_installed( $plugin['file'] ) ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return null;
		}

		return array(
			'url'   => wp_nonce_url(
				self_admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $plugin['file'] ) ),
				'activate-plugin_' . $plugin['file']
			),
			'label' => __( 'Activate', 'unapp' ),
		);
	}

	if ( ! current_user_can( 'install_plugins' ) ) {
		return null;
	}

	return array(
		'url'   => unapp_plugin_install_url( $plugin['slug'] ),
		'label' => __( 'Install', 'unapp' ),
	);
}

/**
 * Apply the branding collected by the wizard.
 *
 * @param array $brand {
 *     @type string $title    Site title.
 *     @type string $tagline  Tagline.
 *     @type int    $logo     Attachment ID.
 *     @type string $colors   Colour variation slug.
 *     @type string $type     Typography variation slug.
 * }
 */
function unapp_wizard_apply_brand( $brand ) {
	$brand = wp_parse_args( $brand, array( 'title' => '', 'tagline' => html_entity_decode( get_option( 'blogdescription' ), ENT_QUOTES, 'UTF-8' ), 'logo' => 0, 'logo_was' => 0 ) );
	if ( current_user_can( 'manage_options' ) ) {
		if ( '' !== $brand['title'] ) {
			update_option( 'blogname', $brand['title'] );
		}
		// An empty tagline is a deliberate choice, so it is written either way.
		update_option( 'blogdescription', $brand['tagline'] );
	}

	if ( $brand['logo'] ) {
		set_theme_mod( 'custom_logo', $brand['logo'] );
	} elseif ( ! empty( $brand['logo_was'] ) ) {
		// The field started with a logo and came back empty: the user pressed Remove.
		remove_theme_mod( 'custom_logo' );
	}
	if ( current_user_can( 'manage_options' ) && ( ( '' !== $brand['title'] && get_option( 'blogname' ) !== sanitize_option( 'blogname', $brand['title'] ) )
		|| get_option( 'blogdescription' ) !== sanitize_option( 'blogdescription', $brand['tagline'] ) ) ) {
		return new WP_Error( 'unapp_starter_save', __( 'The site name could not be saved.', 'unapp' ) );
	}
	if ( ( $brand['logo'] && (int) get_theme_mod( 'custom_logo' ) !== $brand['logo'] )
		|| ( ! $brand['logo'] && ! empty( $brand['logo_was'] ) && get_theme_mod( 'custom_logo' ) ) ) {
		return new WP_Error( 'unapp_starter_save', __( 'The site logo could not be saved.', 'unapp' ) );
	}
	return true;
}

/**
 * Branding values carried between wizard steps in the query string.
 *
 * @param array $source $_GET or $_POST.
 * @return array
 */
function unapp_wizard_brand_from( $source ) {
	// phpcs:disable WordPress.Security.NonceVerification -- callers verify the nonce where state changes.
	return array(
		'title'    => isset( $source['site_title'] ) ? sanitize_text_field( wp_unslash( $source['site_title'] ) ) : '',
		'tagline'  => isset( $source['tagline'] ) ? sanitize_text_field( wp_unslash( $source['tagline'] ) ) : '',
		'logo'     => isset( $source['logo_id'] ) ? absint( $source['logo_id'] ) : 0,
		'logo_was' => isset( $source['logo_was'] ) ? absint( $source['logo_was'] ) : 0,
		'colors'   => isset( $source['colors'] ) ? sanitize_key( wp_unslash( $source['colors'] ) ) : '',
		'type'     => isset( $source['typography'] ) ? sanitize_key( wp_unslash( $source['typography'] ) ) : '',
		'email'    => sanitize_text_field( unapp_design_field( $source, 'public_email' ) ),
		'phone'    => sanitize_text_field( wp_unslash( $source['public_phone'] ?? '' ) ),
		'layout'   => 'one-page' === unapp_design_field( $source, 'layout' ) ? 'one-page' : 'full',
		'profile_enabled' => true,
		'description' => sanitize_textarea_field( unapp_design_field( $source, 'description' ) ),
		'location' => sanitize_textarea_field( unapp_design_field( $source, 'location' ) ),
		'services' => sanitize_textarea_field( unapp_design_field( $source, 'services' ) ),
		'hours' => sanitize_textarea_field( unapp_design_field( $source, 'hours' ) ),
		'service_cards' => unapp_service_cards_sanitize( array_map( 'wp_unslash', array_filter( $source, 'is_string' ) ) ),
		'sections' => '1' === unapp_design_field( $source, 'sections_present' ) || isset( $source['sections'] ) ? array_values( array_map( 'sanitize_key', array_filter( is_array( $source['sections'] ?? null ) ? $source['sections'] : array(), 'is_string' ) ) ) : null,
		'main_action' => array( 'type' => sanitize_key( unapp_design_field( $source, 'main_action_type' ) ?: 'starter' ), 'label' => sanitize_text_field( unapp_design_field( $source, 'main_action_label' ) ), 'url' => sanitize_text_field( unapp_design_field( $source, 'main_action_url' ) ) ),
	);
	// phpcs:enable WordPress.Security.NonceVerification
}

/**
 * Handle the wizard's final submit; branding is applied only after setup succeeds.
 */
function unapp_wizard_handle() {
	check_admin_referer( 'unapp_wizard_build' );

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to change the site design.', 'unapp' ), 403 );
	}

	$starter = isset( $_POST['starter'] ) ? sanitize_key( wp_unslash( $_POST['starter'] ) ) : '';
	$sites   = unapp_get_starter_sites();

	if ( ! isset( $sites[ $starter ] ) ) {
		wp_safe_redirect( admin_url( 'themes.php?page=unapp-starter-sites' ) );
		exit;
	}

	$brand = unapp_wizard_brand_from( $_POST );

	$request_id = isset( $_POST['request_id'] ) ? sanitize_key( wp_unslash( $_POST['request_id'] ) ) : '';
	$result = unapp_apply_starter_site( $starter, $brand, $request_id );

	set_transient( UNAPP_STARTER_RESULT, is_wp_error( $result ) ? 'error:' . $result->get_error_code() : 'done', HOUR_IN_SECONDS );
	wp_safe_redirect( add_query_arg( array( 'page' => 'unapp-starter-sites', 'step' => 'done', 'request_id' => $request_id ), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_unapp_wizard_build', 'unapp_wizard_handle' );

/**
 * The media picker needs the media modal and a few lines of script.
 *
 * @param string $hook Current admin page.
 */
function unapp_wizard_assets( $hook ) {
	if ( 'appearance_page_unapp-starter-sites' !== $hook ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'unapp-wizard',
		get_theme_file_uri( 'assets/js/wizard.js' ),
		array(),
		UNAPP_VERSION,
		true
	);
	wp_localize_script(
		'unapp-wizard',
		'unappWizard',
		array(
			'chooseLogo' => __( 'Choose a logo', 'unapp' ),
			'useLogo'    => __( 'Use this logo', 'unapp' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'unapp_wizard_assets' );

/**
 * The palettes and typefaces the wizard offers, read from the style variations.
 *
 * @return array[] array( 'colors' => array, 'typography' => array )
 */
function unapp_wizard_variations() {
	$out = array( 'colors' => array(), 'typography' => array() );

	foreach ( array( 'colors', 'typography' ) as $kind ) {
		foreach ( (array) glob( get_theme_file_path( 'styles/' . $kind . '/*.json' ) ) as $file ) {
			$data = wp_json_file_decode( $file, array( 'associative' => true ) );
			if ( ! isset( $data['title'] ) ) {
				continue;
			}

			$slug    = basename( $file, '.json' );
			$entry   = array( 'title' => $data['title'], 'swatches' => array() );

			if ( 'colors' === $kind && isset( $data['settings']['color']['palette'] ) ) {
				foreach ( $data['settings']['color']['palette'] as $colour ) {
					if ( in_array( $colour['slug'], array( 'primary', 'secondary' ), true ) ) {
						$entry['swatches'][] = $colour['color'];
					}
				}
			}

			$out[ $kind ][ $slug ] = $entry;
		}
	}

	return $out;
}

/**
 * Step two: the site's own name, logo and look.
 *
 * @param string $starter Chosen starter slug.
 */
function unapp_wizard_render_brand( $starter ) {
	$sites      = unapp_get_starter_sites();
	$site       = $sites[ $starter ];
	$variations = unapp_wizard_variations();

	// Coming "Back" from step three carries what was typed there; otherwise start
	// from what the site already has.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only prefill.
	$choice_source = unapp_wizard_choice_source();
	$carried  = isset( $choice_source['back'] ) ? unapp_wizard_brand_from( $choice_source ) : null;
	$logo_was = (int) get_theme_mod( 'custom_logo' );
	$logo_id  = $carried ? $carried['logo'] : $logo_was;
	$logo_src = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';
	$title    = $carried ? $carried['title'] : html_entity_decode( get_option( 'blogname' ), ENT_QUOTES, 'UTF-8' );
	$tagline  = $carried ? $carried['tagline'] : html_entity_decode( get_option( 'blogdescription' ), ENT_QUOTES, 'UTF-8' );
	$business = $carried ? $carried : get_option( 'unapp_business_details', array() );

	if ( $carried && $carried['colors'] ) {
		$site['colors'] = $carried['colors'];
	}
	if ( $carried && $carried['type'] ) {
		$site['type'] = $carried['type'];
	}
	?>
	<?php $service_form = (bool) unapp_service_card_spec( $site ); ?>
	<form method="<?php echo $service_form ? 'post' : 'get'; ?>" action="<?php echo esc_url( $service_form ? add_query_arg( array( 'page' => 'unapp-starter-sites', 'step' => 'plugins', 'starter' => $starter ), admin_url( 'themes.php' ) ) : admin_url( 'themes.php' ) ); ?>" class="unapp-wizard">
		<?php if ( $service_form ) { wp_nonce_field( 'unapp_wizard_choices', 'unapp_choices_nonce' ); } ?>
		<input type="hidden" name="page" value="unapp-starter-sites">
		<input type="hidden" name="step" value="plugins">
		<input type="hidden" name="starter" value="<?php echo esc_attr( $starter ); ?>">
		<input type="hidden" name="logo_was" value="<?php echo esc_attr( $logo_was ); ?>">

		<h2>
			<?php
			/* translators: %s: starter site name. */
			printf( esc_html__( 'Make the %s starter yours', 'unapp' ), esc_html( $site['title'] ) );
			?>
		</h2>
		<p class="unapp-wizard__lede"><?php esc_html_e( 'Everything here is optional and everything can be changed later. Leaving a field alone keeps what the site already has.', 'unapp' ); ?></p>
		<fieldset class="unapp-wizard__panel"><legend><h3><?php esc_html_e( 'How much website do you need?', 'unapp' ); ?></h3></legend>
			<p><label><input type="radio" name="layout" value="one-page" <?php checked( $carried ? $carried['layout'] : 'one-page', 'one-page' ); ?>> <strong><?php esc_html_e( 'One-page website (recommended)', 'unapp' ); ?></strong> — <?php esc_html_e( 'A complete homepage with section navigation. Add supporting pages later without rebuilding.', 'unapp' ); ?></label></p>
			<p><label><input type="radio" name="layout" value="full" <?php checked( $carried ? $carried['layout'] : 'one-page', 'full' ); ?>> <strong><?php esc_html_e( 'Full website', 'unapp' ); ?></strong> — <?php esc_html_e( 'The homepage, all supporting pages and a blog page.', 'unapp' ); ?></label></p>
			<?php if ( 'shop' === $starter ) : ?><p class="description"><?php esc_html_e( 'The Shop starter still needs WooCommerce and its store pages in either mode.', 'unapp' ); ?></p><?php endif; ?>
		</fieldset>
		<details class="unapp-wizard__panel"><summary><?php esc_html_e( 'Choose homepage sections (optional)', 'unapp' ); ?></summary>
			<p><?php esc_html_e( 'Keep the complete starter, or uncheck sections you do not need. The introduction, closing action and final notice stay in place. This changes the homepage only; full-site supporting pages are still included.', 'unapp' ); ?></p>
			<input type="hidden" name="sections_present" value="1">
			<?php foreach ( unapp_section_choices( $site ) as $key => $choice ) : ?>
				<?php if ( $choice['required'] ) : ?><p><?php echo esc_html( $choice['title'] ); ?> — <?php esc_html_e( 'Included', 'unapp' ); ?></p><?php else : ?><p><label><input type="checkbox" name="sections[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( ! $carried || null === $carried['sections'] || in_array( $key, $carried['sections'], true ) ); ?>> <?php echo esc_html( $choice['title'] ); ?></label></p><?php endif; ?>
			<?php endforeach; ?>
		</details>
		<div class="unapp-wizard__panel">
			<h3><?php esc_html_e( 'Name and logo', 'unapp' ); ?></h3>
			<p class="unapp-field">
				<label for="unapp-site-title"><?php esc_html_e( 'Site title', 'unapp' ); ?></label>
				<input type="text" id="unapp-site-title" name="site_title" class="regular-text"
					value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p class="unapp-field">
				<label for="unapp-tagline"><?php esc_html_e( 'Tagline', 'unapp' ); ?></label>
				<input type="text" id="unapp-tagline" name="tagline" class="regular-text"
					value="<?php echo esc_attr( $tagline ); ?>">
			</p>
			<div class="unapp-field">
				<span class="unapp-field__label"><?php esc_html_e( 'Logo', 'unapp' ); ?></span>
				<div class="unapp-logo">
					<img src="<?php echo esc_url( $logo_src ); ?>" alt="" class="unapp-logo__preview"
						<?php echo $logo_src ? '' : 'hidden'; ?>>
					<button type="button" class="button unapp-logo__choose"><?php esc_html_e( 'Choose a logo', 'unapp' ); ?></button>
					<button type="button" class="button-link unapp-logo__clear" <?php echo $logo_src ? '' : 'hidden'; ?>>
						<?php esc_html_e( 'Remove', 'unapp' ); ?>
					</button>
					<input type="hidden" name="logo_id" value="<?php echo esc_attr( $logo_id ); ?>" class="unapp-logo__id">
				</div>
			</div>
		</div>

		<div class="unapp-wizard__panel">
			<h3><?php esc_html_e( 'Shared business profile', 'unapp' ); ?></h3>
			<p class="unapp-field"><label for="unapp-description"><?php esc_html_e( 'What do you do?', 'unapp' ); ?></label><textarea id="unapp-description" name="description" class="large-text" rows="2"><?php echo esc_textarea( $business['description'] ?? '' ); ?></textarea></p>
			<p class="unapp-wizard__hint"><?php esc_html_e( 'Use the email and phone visitors should see. These replace sample contact details on the new pages and footer. Leave them blank to keep the examples for now.', 'unapp' ); ?></p>
			<p class="unapp-field">
				<label for="unapp-public-email"><?php esc_html_e( 'Public email', 'unapp' ); ?></label>
				<input type="email" id="unapp-public-email" name="public_email" class="regular-text" autocomplete="email"
					value="<?php echo esc_attr( $business['email'] ?? '' ); ?>">
			</p>
			<p class="unapp-field">
				<label for="unapp-public-phone"><?php esc_html_e( 'Public phone', 'unapp' ); ?></label>
				<input type="tel" id="unapp-public-phone" name="public_phone" class="regular-text" autocomplete="tel"
					value="<?php echo esc_attr( $business['phone'] ?? '' ); ?>">
			</p>
			<details><summary><?php esc_html_e( 'More business details (optional)', 'unapp' ); ?></summary>
				<?php foreach ( array( 'location', 'services', 'hours' ) as $field ) : ?><p class="unapp-field"><label for="unapp-profile-<?php echo esc_attr( $field ); ?>"><?php echo esc_html( unapp_business_fields()[ $field ] ); ?></label><textarea id="unapp-profile-<?php echo esc_attr( $field ); ?>" name="<?php echo esc_attr( $field ); ?>" class="large-text" rows="3"><?php echo esc_textarea( $business[ $field ] ?? '' ); ?></textarea></p><?php endforeach; ?>
			</details>
		</div>
		<?php unapp_render_service_card_fields( $site, $business ); ?>
		<details><summary><?php esc_html_e( 'Customize colors and fonts (optional)', 'unapp' ); ?></summary>
		<div class="unapp-wizard__panel">
			<h3><?php esc_html_e( 'Colours', 'unapp' ); ?></h3>
			<p class="unapp-wizard__hint">
				<?php
				/* translators: %s: the palette the starter uses by default. */
				printf( esc_html__( 'The %s starter uses its own palette. Pick another if it suits your brand better.', 'unapp' ), esc_html( $site['title'] ) );
				?>
			</p>
			<div class="unapp-swatches">
				<?php foreach ( $variations['colors'] as $slug => $variation ) : ?>
					<label class="unapp-swatch<?php echo $slug === $site['colors'] ? ' is-default' : ''; ?>">
						<input type="radio" name="colors" value="<?php echo esc_attr( $slug ); ?>"
							<?php checked( $slug, $site['colors'] ); ?>>
						<span class="unapp-swatch__dots">
							<?php foreach ( $variation['swatches'] as $colour ) : ?>
								<span style="background:<?php echo esc_attr( $colour ); ?>"></span>
							<?php endforeach; ?>
						</span>
						<span class="unapp-swatch__name"><?php echo esc_html( $variation['title'] ); ?></span>
					</label>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="unapp-wizard__panel">
			<h3><?php esc_html_e( 'Typeface', 'unapp' ); ?></h3>
			<div class="unapp-swatches unapp-swatches--type">
				<?php foreach ( $variations['typography'] as $slug => $variation ) : ?>
					<label class="unapp-swatch">
						<input type="radio" name="typography" value="<?php echo esc_attr( $slug ); ?>"
							<?php checked( $slug, $site['type'] ); ?>>
						<span class="unapp-swatch__name"><?php echo esc_html( $variation['title'] ); ?></span>
					</label>
				<?php endforeach; ?>
			</div>
		</div>
		</details>

		<details class="unapp-wizard__panel"><summary><?php esc_html_e( 'Main action (optional)', 'unapp' ); ?></summary>
			<p><?php esc_html_e( 'Keep the starter buttons, or choose what visitors should do next. Call and email use your public details. Quotes use the included homepage contact details; bookings need a URL. Shop requires a published WooCommerce Shop page.', 'unapp' ); ?></p>
			<p><label for="unapp-setup-action"><?php esc_html_e( 'Main action', 'unapp' ); ?></label><select id="unapp-setup-action" name="main_action_type"><?php foreach ( unapp_main_action_choices() as $key => $label ) : ?><option value="<?php echo esc_attr( $key ); ?>" <?php selected( $carried['main_action']['type'] ?? 'starter', $key ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></p>
			<p><label for="unapp-setup-action-label"><?php esc_html_e( 'Button wording (optional)', 'unapp' ); ?></label><input class="regular-text" id="unapp-setup-action-label" name="main_action_label" maxlength="60" value="<?php echo esc_attr( $carried['main_action']['label'] ?? '' ); ?>"></p>
			<p><label for="unapp-setup-action-url"><?php esc_html_e( 'Booking or quote URL', 'unapp' ); ?></label><input class="regular-text" id="unapp-setup-action-url" type="url" name="main_action_url" value="<?php echo esc_attr( $carried['main_action']['url'] ?? '' ); ?>"></p>
		</details>
		<p class="unapp-wizard__actions">
			<a class="button" href="<?php echo esc_url( admin_url( 'themes.php?page=unapp-starter-sites' ) ); ?>">
				<?php esc_html_e( 'Back', 'unapp' ); ?>
			</a>
			<button type="submit" class="button button-primary button-hero"><?php esc_html_e( 'Continue', 'unapp' ); ?></button>
		</p>
	</form>
	<?php
}

/**
 * Identical escaped setup choices for the private preview and build forms.
 *
 * @param string $starter Chosen starter slug.
 * @param array  $brand   Branding carried from step two.
 */
function unapp_wizard_brand_fields( $starter, $brand ) {
	$fields = array( 'starter' => $starter, 'site_title' => $brand['title'] ?? '', 'tagline' => $brand['tagline'] ?? '', 'logo_id' => $brand['logo'] ?? 0, 'logo_was' => $brand['logo_was'] ?? 0, 'colors' => $brand['colors'] ?? '', 'typography' => $brand['type'] ?? '', 'public_email' => $brand['email'] ?? '', 'public_phone' => $brand['phone'] ?? '', 'main_action_type' => $brand['main_action']['type'] ?? 'starter', 'main_action_label' => $brand['main_action']['label'] ?? '', 'main_action_url' => $brand['main_action']['url'] ?? '' );
	foreach ( array( 'layout', 'description', 'location', 'services', 'hours' ) as $field ) { $fields[ $field ] = $brand[ $field ] ?? ''; }
	$fields = array_merge( $fields, unapp_service_card_payload( $brand ) );
	foreach ( $fields as $name => $value ) { echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">'; }
	if ( is_array( $brand['sections'] ?? null ) ) {
		echo '<input type="hidden" name="sections_present" value="1">';
		foreach ( $brand['sections'] as $section ) { echo '<input type="hidden" name="sections[]" value="' . esc_attr( $section ) . '">'; }
	}
}

/** Step three: review the personalized preview, dependencies and import summary. */
function unapp_wizard_render_plugins( $starter, $brand ) {
	$sites       = unapp_get_starter_sites();
	$site        = $sites[ $starter ];
	$suggestions = unapp_wizard_plugin_suggestions( $starter );
	$missing     = unapp_starter_missing_requirement( $site );
	$service_error = unapp_service_cards_validate( $brand );
	$back_args   = array(
		'page'         => 'unapp-starter-sites',
		'step'         => 'brand',
		'starter'      => $starter,
		'back'         => 1,
		'site_title'   => $brand['title'],
		'tagline'      => $brand['tagline'],
		'logo_id'      => $brand['logo'],
		'logo_was'     => $brand['logo_was'],
		'colors'       => $brand['colors'] ?? '',
		'typography'   => $brand['type'] ?? '',
		'public_email' => $brand['email'],
		'public_phone' => $brand['phone'],
		'layout' => $brand['layout'] ?? 'full',
		'description' => $brand['description'] ?? '',
		'location' => $brand['location'] ?? '',
		'services' => $brand['services'] ?? '',
		'hours' => $brand['hours'] ?? '',
		'sections' => $brand['sections'] ?? null,
		'sections_present' => is_array( $brand['sections'] ?? null ) ? '1' : '0',
		'main_action_type' => $brand['main_action']['type'] ?? 'starter',
		'main_action_label' => $brand['main_action']['label'] ?? '',
		'main_action_url' => $brand['main_action']['url'] ?? '',
	);
	$back_args = array_merge( $back_args, unapp_service_card_payload( $brand ) );
	?>
	<div class="unapp-wizard">
		<h2><?php esc_html_e( 'Review and build your website', 'unapp' ); ?></h2>

		<?php if ( $suggestions ) : ?>
			<p class="unapp-wizard__lede">
				<?php
				echo $missing
					? esc_html__( 'WordPress installs these, not the theme. This starter cannot be built until its required plugin is active; the others can be skipped and added later.', 'unapp' )
					: esc_html__( 'WordPress installs these, not the theme, and you can skip them and come back later. Nothing here is required to build the site.', 'unapp' );
				?>
			</p>
			<div class="unapp-wizard__panel">
				<?php foreach ( $suggestions as $plugin ) : ?>
					<?php $unapp_action = unapp_plugin_action( $plugin ); ?>
					<div class="unapp-plugin">
						<div>
							<strong><?php echo esc_html( $plugin['label'] ); ?></strong>
							<p><?php echo esc_html( $plugin['reason'] ); ?></p>
						</div>
						<?php if ( $unapp_action ) : ?>
							<a class="button" target="_blank" rel="noopener" href="<?php echo esc_url( $unapp_action['url'] ); ?>">
								<?php echo esc_html( $unapp_action['label'] ); ?>
							</a>
						<?php else : ?>
							<span class="description"><?php esc_html_e( 'Ask an administrator to install it.', 'unapp' ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="unapp-wizard__lede"><?php esc_html_e( 'Nothing to install — everything this starter needs is already active.', 'unapp' ); ?></p>
		<?php endif; ?>

		<?php if ( is_wp_error( $service_error ) ) : ?><div class="notice notice-error"><p><?php echo esc_html( $service_error->get_error_message() ); ?> <?php esc_html_e( 'Use Back to finish the card or clear both fields. Your entered choices are kept.', 'unapp' ); ?></p></div><?php else : ?><?php unapp_render_personalized_preview( $starter, $brand ); ?><?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'unapp_wizard_build' ); ?>
			<input type="hidden" name="action" value="unapp_wizard_build">
			<input type="hidden" name="request_id" value="<?php echo esc_attr( wp_generate_uuid4() ); ?>">
			<?php unapp_wizard_brand_fields( $starter, $brand ); ?>

			<div class="unapp-wizard__summary">
				<h3><?php esc_html_e( 'What happens when you press the button', 'unapp' ); ?></h3>
				<?php $unapp_page_count = 'one-page' === ( $brand['layout'] ?? '' ) ? 1 : count( $site['pages'] ) + 1; ?>
				<ul>
					<?php if ( is_array( $brand['sections'] ?? null ) ) : ?><li><?php
						$unapp_sections = array_filter( unapp_section_choices( $site ), static function ( $choice, $key ) use ( $brand ) { return $choice['required'] || in_array( $key, $brand['sections'], true ); }, ARRAY_FILTER_USE_BOTH );
						/* translators: %s: selected homepage section names. */
						printf( esc_html__( 'Homepage sections: %s. Shared business details are also included.', 'unapp' ), esc_html( implode( ', ', array_column( $unapp_sections, 'title' ) ) ) );
					?></li><?php endif; ?>
					<li>
						<?php
						/* translators: %d: number of pages. */
						printf( esc_html( _n( '%d page is created and filled in', '%d pages are created and filled in', $unapp_page_count, 'unapp' ) ), absint( $unapp_page_count ) );
						?>
					</li>
					<li><?php echo 'one-page' === ( $brand['layout'] ?? '' ) ? esc_html__( 'A section menu is built for the homepage; supporting pages can be added later', 'unapp' ) : esc_html__( 'A navigation menu is built from those pages', 'unapp' ); ?></li>
					<li><?php esc_html_e( 'The palette and typeface replace the current Global Styles', 'unapp' ); ?></li>
					<li><?php esc_html_e( 'The header and footer take the starter\'s wording', 'unapp' ); ?></li>
					<li><?php esc_html_e( 'Public contact details replace sample emails and phone numbers where supplied', 'unapp' ); ?></li>
					<li><?php esc_html_e( 'The front page is pointed at the new home page', 'unapp' ); ?></li>
					<li><?php esc_html_e( 'No page, post or image is deleted, and the previous styles, header and footer stay in their revision history', 'unapp' ); ?></li>
				</ul>
			</div>

			<p class="unapp-wizard__actions">
				<?php if ( unapp_service_card_spec( $site ) ) : ?><button class="button" type="submit" form="unapp-wizard-back"><?php esc_html_e( 'Back', 'unapp' ); ?></button><?php else : ?>
				<a class="button" href="<?php echo esc_url( add_query_arg( $back_args, admin_url( 'themes.php' ) ) ); ?>"><?php esc_html_e( 'Back', 'unapp' ); ?></a><?php endif; ?>
				<button type="submit" class="button button-primary button-hero" <?php disabled( (bool) $missing || is_wp_error( $service_error ) ); ?>><?php esc_html_e( 'Build my site', 'unapp' ); ?></button>
			</p>
		</form>
		<?php if ( unapp_service_card_spec( $site ) ) : ?><form id="unapp-wizard-back" method="post" action="<?php echo esc_url( add_query_arg( array( 'page' => 'unapp-starter-sites', 'step' => 'brand', 'starter' => $starter ), admin_url( 'themes.php' ) ) ); ?>"><input type="hidden" name="back" value="1"><?php wp_nonce_field( 'unapp_wizard_choices', 'unapp_choices_nonce' ); unapp_wizard_brand_fields( $starter, $brand ); ?></form><?php endif; ?>
	</div>
	<?php
}

/**
 * The finished screen.
 */
function unapp_wizard_render_done() {
	$applied = get_option( UNAPP_STARTER_OPTION, array() );
	$home    = isset( $applied['pages']['home'] ) ? (int) $applied['pages']['home'] : 0;
	$result  = get_transient( UNAPP_STARTER_RESULT );
	// A refresh should still show the completed run after its one-time notice.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only result lookup.
	$request_id = isset( $_GET['request_id'] ) ? sanitize_key( wp_unslash( $_GET['request_id'] ) ) : '';
	$completed  = $request_id ? get_transient( 'unapp_import_' . md5( $request_id ) ) : false;
	if ( is_array( $completed ) && ! empty( $completed['home'] ) ) {
		$home = (int) $completed['home'];
		$result = 'done';
	}

	// Read once here, so it does not reappear as a stale notice on the next visit.
	delete_transient( UNAPP_STARTER_RESULT );

	if ( 'done' !== $result ) {
		?>
		<div class="unapp-wizard unapp-wizard--done">
			<h2><?php esc_html_e( 'The site could not be built', 'unapp' ); ?></h2>
			<p class="unapp-wizard__lede"><?php echo esc_html( unapp_starter_error_message( $result ) ); ?></p>
			<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=unapp-starter-sites' ) ); ?>"><?php esc_html_e( 'Back to the starter sites', 'unapp' ); ?></a></p>
		</div>
		<?php
		return;
	}
	?>
	<div class="unapp-wizard unapp-wizard--done">
		<h2><?php esc_html_e( 'Your site is built', 'unapp' ); ?></h2>
		<p class="unapp-wizard__lede"><?php esc_html_e( 'The pages are written, the menu is made and the front page points at the new home page. Every section is locked to its content, so you can rewrite the words without pulling the layout apart.', 'unapp' ); ?></p>
		<p class="unapp-wizard__actions">
			<a class="button button-primary button-hero" href="<?php echo esc_url( unapp_finish_url() ); ?>"><?php esc_html_e( 'Finish your website', 'unapp' ); ?></a>
			<a class="button button-hero" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'View your site', 'unapp' ); ?></a>
			<?php if ( $home ) : ?>
				<a class="button button-hero" href="<?php echo esc_url( get_edit_post_link( $home, 'raw' ) ); ?>"><?php esc_html_e( 'Edit the home page', 'unapp' ); ?></a>
			<?php endif; ?>
			<a class="button button-hero" href="<?php echo esc_url( unapp_design_url() ); ?>"><?php esc_html_e( 'Make it yours', 'unapp' ); ?></a>
			<a class="button button-hero" href="<?php echo esc_url( unapp_business_url() ); ?>"><?php esc_html_e( 'Business profile', 'unapp' ); ?></a>
		</p>
		<?php unapp_render_launch_summary(); ?>
		<?php unapp_render_starter_undo(); ?>
		<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=unapp-starter-sites' ) ); ?>"><?php esc_html_e( 'Choose a different starter', 'unapp' ); ?></a></p>
	</div>
	<?php
}

/** Long service descriptions travel in native POST forms, never oversized URLs. */
function unapp_wizard_choice_source() {
	if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && isset( $_POST['unapp_choices_nonce'] ) ) {
		check_admin_referer( 'unapp_wizard_choices', 'unapp_choices_nonce' );
		return $_POST;
	}
	return $_GET;
}
