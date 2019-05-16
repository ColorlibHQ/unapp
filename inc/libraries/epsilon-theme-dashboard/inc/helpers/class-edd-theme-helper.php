<?php
/**
 * Epsilon Framework EDD Related Theme Helpers
 *
 * @package Epsilon Framework
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class EDD_Theme_Helper
 */
class EDD_Theme_Helper {
	/**
	 * Returns a translation string array
	 *
	 * @return array
	 */
	public static function get_strings() {
		return array(
			/* Translators: Registration */
			'theme-license'             => __( 'Registration', 'epsilon-framework' ),
			/* Translators: Enter Key field label */
			'enter-key'                 => __( 'Enter your theme license key.', 'epsilon-framework' ),
			/* Translators: License Key */
			'license-key'               => __( 'License Key', 'epsilon-framework' ),
			/* Translators: Action */
			'license-action'            => __( 'License Action', 'epsilon-framework' ),
			/* Translators: Deactivate License Label */
			'deactivate-license'        => __( 'Deactivate License', 'epsilon-framework' ),
			/* Translators: Activate License Label */
			'activate-license'          => __( 'Activate License', 'epsilon-framework' ),
			/* Translators: Unknown License Label */
			'status-unknown'            => __( 'License status is unknown.', 'epsilon-framework' ),
			/* Translators: Renewal Label */
			'renew'                     => __( 'Renew?', 'epsilon-framework' ),
			/* Translators: Unlimited activations */
			'unlimited'                 => __( 'unlimited', 'epsilon-framework' ),
			/* Translators: Active key */
			'license-key-is-active'     => __( 'License key is active.', 'epsilon-framework' ),
			/* Translators: expires */
			'expires%s'                 => __( 'Expires %s.', 'epsilon-framework' ),
			/* Translators: websites activated */
			'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'epsilon-framework' ),
			/* Translators: License expired*/
			'license-key-expired-%s'    => __( 'License key expired %s.', 'epsilon-framework' ),
			/* Translators: Expired License Key */
			'license-key-expired'       => __( 'License key has expired.', 'epsilon-framework' ),
			/* Translators: Match failed */
			'license-keys-do-not-match' => __( 'License keys do not match.', 'epsilon-framework' ),
			/* Translators: Inactive license */
			'license-is-inactive'       => __( 'License is inactive.', 'epsilon-framework' ),
			/* Translators: Disabled license */
			'license-key-is-disabled'   => __( 'License key is disabled.', 'epsilon-framework' ),
			/* Translators: Inactive website */
			'site-is-inactive'          => __( 'Site is inactive.', 'epsilon-framework' ),
			/* Translators: Unknown license key */
			'license-status-unknown'    => __( 'License status is unknown.', 'epsilon-framework' ),
			/* Translators: Update notice */
			'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'epsilon-framework' ),
			/* Translators: license sites, title, update link */
			'update-available'          => __( '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4$s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'epsilon-framework' ),
		);
	}

	/**
	 * Checks if license is valid and gets expire date.
	 *
	 * @since 1.0.0
	 *
	 */
	public static function check_license( $args = array() ) {
		$license    = trim( $args['license'] );
		$strings    = self::get_strings();
		$message    = '';
		$expires    = false;
		$renew_link = false;

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => rawurlencode( $args['theme']['theme-slug'] ),
			'url'        => home_url(),
		);

		$license_data = self::get_api_response( $api_params );

		// If response doesn't include license data, return.
		if ( ! isset( $license_data->license ) ) {
			$message = $strings['license-unknown'];

			return array(
				'status'  => true,
				'message' => $message,
			);
		}

		if ( isset( $license_data->expires ) ) {
			$expires    = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) );
			$renew_link = '<a href="' . esc_url( self::get_renewal_link( $args['theme']['theme-slug'], $license ) ) . '" target="_blank">' . $strings['renew'] . '</a>';
		}

		$arr = array(
			'status'        => true,
			'message'       => $message,
			'expires'       => $expires,
			'renew'         => $renew_link,
			'licenseStatus' => $license_data->license
		);

		update_option( $args['theme']['theme-slug'] . '_license_object', array() );
		update_option( $args['theme']['theme-slug'] . '_license_object', $arr );

		$arr['status'] = true;

		return $arr;
	}

	/**
	 * Returns a renewal link
	 *
	 * @return string
	 */
	public static function get_renewal_link( $slug = '', $license = '' ) {
		$theme    = wp_get_theme();
		$instance = Epsilon_Dashboard::get_instance();
		if ( '' !== $instance->theme['download-id'] && ! empty( $license ) ) {
			$url = esc_url( $theme->get( 'AuthorURI' ) );
			$url .= '/checkout/?edd_license_key=' . $license . '&download_id=' . $instance->theme['download-id'];

			return $url;
		}

		return $theme->get( 'AuthorURI' );
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public static function activate_license( $args = array() ) {
		$expires    = false;
		$message    = '';
		$renew_link = '';
		$strings    = self::get_strings();
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $args['license'],
			'item_name'  => rawurlencode( $args['theme']['theme-slug'] ),
		);

		$license_data = self::get_api_response( $api_params );
		if ( 'valid' === $license_data->license ) {
			if ( isset( $license_data->expires ) ) {
				$expires    = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) );
				$renew_link = '<a href="' . esc_url( self::get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
			}

			$arr = array(
				'status'        => true,
				'message'       => '',
				'expires'       => $expires,
				'renew'         => $renew_link,
				'licenseStatus' => $license_data->license
			);

			update_option( $args['theme']['theme-slug'] . '_license_object', array() );
			update_option( $args['theme']['theme-slug'] . '_license_object', $arr );

			return $arr;
		}

		return array( 'status' => false, 'message' => 'nok' );
	}

	/**
	 * Deactivates license
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function deactivate_license( $args = array() ) {
		$expires    = false;
		$message    = '';
		$renew_link = '';
		$strings    = self::get_strings();
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $args['license'],
			'item_name'  => rawurlencode( $args['theme']['theme-slug'] ),
		);

		$license_data = self::get_api_response( $api_params );
		if ( 'deactivated' === $license_data->license ) {
			if ( isset( $license_data->expires ) ) {
				$expires    = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) );
				$renew_link = '<a href="' . esc_url( self::get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
			}

			$arr = array(
				'status'        => true,
				'message'       => '',
				'expires'       => $expires,
				'renew'         => $renew_link,
				'licenseStatus' => 'inactive'
			);

			update_option( $args['theme']['theme-slug'] . '_license_object', array() );
			update_option( $args['theme']['theme-slug'] . '_license_object', $arr );

			return $arr;
		}

		return array( 'status' => false, 'message' => 'nok' );
	}

	/**
	 * Get a response from our website.
	 *
	 * @param array $params Configuration array.
	 *
	 * @return mixed
	 */
	public static function get_api_response( $params ) {
		$theme = wp_get_theme();

		// Call the custom API.
		$response = wp_remote_post(
			$theme->get( 'AuthorURI' ),
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $params,
			)
		);

		// Make sure the response came back okay.
		if ( is_wp_error( $response ) ) {
			return false;
		}

		$response = json_decode( wp_remote_retrieve_body( $response ) );

		return $response;
	}
}
