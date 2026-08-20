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
		if ( 'form' === $key && unapp_detect_form() ) {
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
	if ( current_user_can( 'manage_options' ) ) {
		if ( '' !== $brand['title'] ) {
			update_option( 'blogname', $brand['title'] );
		}
		// An empty tagline is a deliberate choice, so it is written either way.
		update_option( 'blogdescription', $brand['tagline'] );
	}

	if ( $brand['logo'] ) {
		set_theme_mod( 'custom_logo', $brand['logo'] );
	}
}

/**
 * Handle the wizard's final submit: brand, then starter.
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

	$brand = array(
		'title'   => isset( $_POST['site_title'] ) ? sanitize_text_field( wp_unslash( $_POST['site_title'] ) ) : '',
		'tagline' => isset( $_POST['tagline'] ) ? sanitize_text_field( wp_unslash( $_POST['tagline'] ) ) : '',
		'logo'    => isset( $_POST['logo_id'] ) ? absint( $_POST['logo_id'] ) : 0,
		'colors'  => isset( $_POST['colors'] ) ? sanitize_key( wp_unslash( $_POST['colors'] ) ) : '',
		'type'    => isset( $_POST['typography'] ) ? sanitize_key( wp_unslash( $_POST['typography'] ) ) : '',
	);

	unapp_wizard_apply_brand( $brand );

	// A palette or typeface chosen in the wizard overrides the starter's own.
	if ( $brand['colors'] ) {
		$sites[ $starter ]['colors'] = $brand['colors'];
	}
	if ( $brand['type'] ) {
		$sites[ $starter ]['type'] = $brand['type'];
	}

	$override = $sites[ $starter ];
	$filter   = function ( $all ) use ( $starter, $override ) {
		$all[ $starter ] = $override;
		return $all;
	};

	add_filter( 'unapp_starter_sites', $filter, 99 );
	$result = unapp_apply_starter_site( $starter );
	remove_filter( 'unapp_starter_sites', $filter, 99 );

	set_transient( UNAPP_STARTER_RESULT, is_wp_error( $result ) ? 'error' : 'done', HOUR_IN_SECONDS );
	wp_safe_redirect( admin_url( 'themes.php?page=unapp-starter-sites&step=done' ) );
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
	$logo_id    = (int) get_theme_mod( 'custom_logo' );
	$logo_src   = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';
	?>
	<form method="get" action="<?php echo esc_url( admin_url( 'themes.php' ) ); ?>" class="unapp-wizard">
		<input type="hidden" name="page" value="unapp-starter-sites">
		<input type="hidden" name="step" value="plugins">
		<input type="hidden" name="starter" value="<?php echo esc_attr( $starter ); ?>">

		<h2>
			<?php
			/* translators: %s: starter site name. */
			printf( esc_html__( 'Make the %s starter yours', 'unapp' ), esc_html( $site['title'] ) );
			?>
		</h2>
		<p class="unapp-wizard__lede"><?php esc_html_e( 'Everything here is optional and everything can be changed later. Leaving a field alone keeps what the site already has.', 'unapp' ); ?></p>

		<div class="unapp-wizard__panel">
			<h3><?php esc_html_e( 'Name and logo', 'unapp' ); ?></h3>
			<p class="unapp-field">
				<label for="unapp-site-title"><?php esc_html_e( 'Site title', 'unapp' ); ?></label>
				<input type="text" id="unapp-site-title" name="site_title" class="regular-text"
					value="<?php echo esc_attr( get_option( 'blogname' ) ); ?>">
			</p>
			<p class="unapp-field">
				<label for="unapp-tagline"><?php esc_html_e( 'Tagline', 'unapp' ); ?></label>
				<input type="text" id="unapp-tagline" name="tagline" class="regular-text"
					value="<?php echo esc_attr( get_option( 'blogdescription' ) ); ?>">
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
 * Step three: the plugins this starter depends on, then build.
 *
 * @param string $starter Chosen starter slug.
 * @param array  $brand   Branding carried from step two.
 */
function unapp_wizard_render_plugins( $starter, $brand ) {
	$sites       = unapp_get_starter_sites();
	$site        = $sites[ $starter ];
	$suggestions = unapp_wizard_plugin_suggestions( $starter );
	?>
	<div class="unapp-wizard">
		<h2><?php esc_html_e( 'One or two things this starter needs', 'unapp' ); ?></h2>

		<?php if ( $suggestions ) : ?>
			<p class="unapp-wizard__lede"><?php esc_html_e( 'WordPress installs these, not the theme, and you can skip them and come back later. Nothing here is required to build the site.', 'unapp' ); ?></p>
			<div class="unapp-wizard__panel">
				<?php foreach ( $suggestions as $plugin ) : ?>
					<div class="unapp-plugin">
						<div>
							<strong><?php echo esc_html( $plugin['label'] ); ?></strong>
							<p><?php echo esc_html( $plugin['reason'] ); ?></p>
						</div>
						<a class="button" target="_blank" rel="noopener"
							href="<?php echo esc_url( unapp_plugin_install_url( $plugin['slug'] ) ); ?>">
							<?php esc_html_e( 'Install', 'unapp' ); ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="unapp-wizard__lede"><?php esc_html_e( 'Nothing to install — everything this starter needs is already active.', 'unapp' ); ?></p>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'unapp_wizard_build' ); ?>
			<input type="hidden" name="action" value="unapp_wizard_build">
			<input type="hidden" name="starter" value="<?php echo esc_attr( $starter ); ?>">
			<input type="hidden" name="site_title" value="<?php echo esc_attr( $brand['title'] ); ?>">
			<input type="hidden" name="tagline" value="<?php echo esc_attr( $brand['tagline'] ); ?>">
			<input type="hidden" name="logo_id" value="<?php echo esc_attr( $brand['logo'] ); ?>">
			<input type="hidden" name="colors" value="<?php echo esc_attr( $brand['colors'] ); ?>">
			<input type="hidden" name="typography" value="<?php echo esc_attr( $brand['type'] ); ?>">

			<div class="unapp-wizard__summary">
				<h3><?php esc_html_e( 'What happens when you press the button', 'unapp' ); ?></h3>
				<?php $unapp_page_count = count( $site['pages'] ) + 1; ?>
				<ul>
					<li>
						<?php
						/* translators: %d: number of pages. */
						printf( esc_html( _n( '%d page is created and filled in', '%d pages are created and filled in', $unapp_page_count, 'unapp' ) ), absint( $unapp_page_count ) );
						?>
					</li>
					<li><?php esc_html_e( 'A navigation menu is built from those pages', 'unapp' ); ?></li>
					<li><?php esc_html_e( 'The palette and typeface are written into Global Styles', 'unapp' ); ?></li>
					<li><?php esc_html_e( 'The front page is pointed at the new home page', 'unapp' ); ?></li>
					<li><?php esc_html_e( 'Nothing you already have is deleted or overwritten', 'unapp' ); ?></li>
				</ul>
			</div>

			<p class="unapp-wizard__actions">
				<a class="button" href="<?php echo esc_url( add_query_arg( array( 'page' => 'unapp-starter-sites', 'step' => 'brand', 'starter' => $starter ), admin_url( 'themes.php' ) ) ); ?>">
					<?php esc_html_e( 'Back', 'unapp' ); ?>
				</a>
				<button type="submit" class="button button-primary button-hero"><?php esc_html_e( 'Build my site', 'unapp' ); ?></button>
			</p>
		</form>
	</div>
	<?php
}

/**
 * The finished screen.
 */
function unapp_wizard_render_done() {
	$applied = get_option( UNAPP_STARTER_OPTION, array() );
	$home    = isset( $applied['pages']['home'] ) ? (int) $applied['pages']['home'] : 0;
	?>
	<div class="unapp-wizard unapp-wizard--done">
		<h2><?php esc_html_e( 'Your site is built', 'unapp' ); ?></h2>
		<p class="unapp-wizard__lede"><?php esc_html_e( 'The pages are written, the menu is made and the front page points at the new home page. Every section is locked to its content, so you can rewrite the words without pulling the layout apart.', 'unapp' ); ?></p>
		<p class="unapp-wizard__actions">
			<a class="button button-primary button-hero" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'View your site', 'unapp' ); ?></a>
			<?php if ( $home ) : ?>
				<a class="button button-hero" href="<?php echo esc_url( get_edit_post_link( $home, 'raw' ) ); ?>"><?php esc_html_e( 'Edit the home page', 'unapp' ); ?></a>
			<?php endif; ?>
			<a class="button button-hero" href="<?php echo esc_url( admin_url( 'site-editor.php' ) ); ?>"><?php esc_html_e( 'Open the Site Editor', 'unapp' ); ?></a>
		</p>
		<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=unapp-starter-sites' ) ); ?>"><?php esc_html_e( 'Choose a different starter', 'unapp' ); ?></a></p>
	</div>
	<?php
}
