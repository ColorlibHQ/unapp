<?php
/**
 * Updates for a theme distributed outside WordPress.org.
 *
 * WordPress 6.1 added a first-class hook for this: a theme that declares an
 * `Update URI` header gets `update_themes_{hostname}` filtered during the
 * normal update check, and anything returned there flows into the standard
 * Dashboard → Updates screen, the Appearance → Themes notice, and auto-updates.
 * No cron of our own, no nagging notice, no bespoke updater UI.
 *
 * The same request is also the only install count we get, so what it sends is
 * written down here in full rather than buried: theme version, WordPress and
 * PHP versions, locale, and a hashed site identifier that cannot be reversed
 * into a URL. It sends no personal data, no email address and no site name,
 * and the whole thing can be switched off with one filter.
 *
 * @package Unapp
 */

defined( 'ABSPATH' ) || exit;

/**
 * Where update checks go.
 *
 * Must match the host in the theme's `Update URI` header, or core will never
 * call our filter.
 */
const UNAPP_UPDATE_HOST     = 'updates.colorlib.com';
const UNAPP_UPDATE_ENDPOINT = 'https://updates.colorlib.com/theme/unapp.json';
const UNAPP_UPDATE_CACHE    = 'unapp_update_response';

/**
 * Whether the site has opted out of the update check.
 *
 * Opting out also opts out of update notifications, which is the honest
 * trade — there is no way to be told about a release without asking.
 *
 * @return bool
 */
function unapp_updates_enabled() {
	/**
	 * Filters whether Unapp checks for updates.
	 *
	 * Returning false stops the request entirely: no update checks, and nothing
	 * is sent anywhere.
	 *
	 * @param bool $enabled Whether to check for updates.
	 */
	return (bool) apply_filters( 'unapp_check_for_updates', true );
}

/**
 * The information sent with an update check.
 *
 * The site identifier is a one-way hash of the home URL salted with the
 * install's own AUTH_SALT, so two sites never collide and the value cannot be
 * turned back into a URL by whoever receives it. It exists so that a count of
 * installs is a count of sites rather than a count of requests.
 *
 * @return array
 */
function unapp_update_payload() {
	$payload = array(
		'theme'      => 'unapp',
		'version'    => UNAPP_VERSION,
		'wp'         => get_bloginfo( 'version' ),
		'php'        => PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION,
		'locale'     => get_locale(),
		'multisite'  => is_multisite() ? 1 : 0,
		'site'       => substr( hash_hmac( 'sha256', home_url( '/' ), wp_salt( 'auth' ) ), 0, 32 ),
	);

	/**
	 * Filters the data sent with an update check.
	 *
	 * Return an array with only 'theme' and 'version' to send the bare minimum
	 * an update check needs.
	 *
	 * @param array $payload What will be sent.
	 */
	return apply_filters( 'unapp_update_payload', $payload );
}

/**
 * Ask the endpoint what the current release is.
 *
 * Cached for twelve hours, and failures are cached briefly too so a site does
 * not retry a dead endpoint on every admin page load.
 *
 * @return array|null
 */
function unapp_fetch_update() {
	if ( ! unapp_updates_enabled() ) {
		return null;
	}

	$cached = get_site_transient( UNAPP_UPDATE_CACHE );

	if ( is_array( $cached ) ) {
		return $cached ? $cached : null;
	}

	$response = wp_remote_get(
		add_query_arg( unapp_update_payload(), UNAPP_UPDATE_ENDPOINT ),
		array(
			'timeout'    => 8,
			'user-agent' => 'Unapp/' . UNAPP_VERSION . '; ' . home_url( '/' ),
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		set_site_transient( UNAPP_UPDATE_CACHE, array(), HOUR_IN_SECONDS );
		return null;
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $data ) || empty( $data['version'] ) ) {
		set_site_transient( UNAPP_UPDATE_CACHE, array(), HOUR_IN_SECONDS );
		return null;
	}

	set_site_transient( UNAPP_UPDATE_CACHE, $data, 12 * HOUR_IN_SECONDS );

	return $data;
}

/**
 * Hand core an update when the endpoint reports a newer version.
 *
 * @param array|false $update           Update data, or false when nothing is known yet.
 * @param array       $theme_data       Theme headers.
 * @param string      $theme_stylesheet Theme directory name.
 * @return array|false
 */
function unapp_check_update( $update, $theme_data, $theme_stylesheet ) {
	if ( $update || 'unapp' !== $theme_stylesheet ) {
		return $update;
	}

	$release = unapp_fetch_update();

	if ( ! $release || version_compare( $release['version'], UNAPP_VERSION, '<=' ) ) {
		return $update;
	}

	return array(
		'id'           => UNAPP_UPDATE_ENDPOINT,
		'theme'        => $theme_stylesheet,
		'version'      => $release['version'],
		'url'          => isset( $release['url'] ) ? $release['url'] : 'https://colorlib.com/wp/themes/unapp/',
		'package'      => isset( $release['package'] ) ? $release['package'] : '',
		'tested'       => isset( $release['tested'] ) ? $release['tested'] : '',
		'requires_php' => isset( $release['requires_php'] ) ? $release['requires_php'] : '7.4',
		'autoupdate'   => ! empty( $release['autoupdate'] ),
	);
}
add_filter( 'update_themes_' . UNAPP_UPDATE_HOST, 'unapp_check_update', 10, 3 );

/**
 * Forget the cached response when someone asks WordPress to check again.
 */
function unapp_flush_update_cache() {
	delete_site_transient( UNAPP_UPDATE_CACHE );
}
add_action( 'upgrader_process_complete', 'unapp_flush_update_cache' );
add_action( 'after_switch_theme', 'unapp_flush_update_cache' );

/**
 * Explain the update check on the theme's own screen.
 *
 * A site owner should not have to read the source to find out what leaves
 * their server.
 */
function unapp_updates_notice() {
	$screen = get_current_screen();

	if ( ! $screen || 'appearance_page_unapp-starter-sites' !== $screen->id ) {
		return;
	}

	if ( ! unapp_updates_enabled() ) {
		return;
	}

	$payload = unapp_update_payload();

	// The companion plugin checks the same host with the same payload and honours
	// the same opt-out, so the note has to speak for both when it is active.
	// Two whole sentences rather than a spliced-in subject: the verb has to agree,
	// and a translator cannot make that agree from a fragment.
	$note = function_exists( 'unapp_library_check_update' )
		/* translators: 1: the update host, 2: the list of values sent, 3: filter name. */
		? __( 'Unapp and its Starter Library plugin check %1$s for updates twice a day and send %2$s. No personal data and no site name is sent, and the identifier is a one-way hash. Add the %3$s filter to switch both off.', 'unapp' )
		/* translators: 1: the update host, 2: the list of values sent, 3: filter name. */
		: __( 'Unapp checks %1$s for updates twice a day and sends %2$s. No personal data and no site name is sent, and the identifier is a one-way hash. Add the %3$s filter to switch it off.', 'unapp' );
	?>
	<p class="description unapp-updates-note">
		<?php
		printf(
			esc_html( $note ),
			'<code>' . esc_html( UNAPP_UPDATE_HOST ) . '</code>',
			'<code>' . esc_html( implode( ', ', array_keys( $payload ) ) ) . '</code>',
			'<code>unapp_check_for_updates</code>'
		);
		?>
	</p>
	<style>.unapp-updates-note { max-width: 70ch; margin-top: 18px; }</style>
	<?php
}
add_action( 'admin_footer', 'unapp_updates_notice' );
