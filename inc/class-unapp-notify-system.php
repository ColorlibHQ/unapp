<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Unapp_Notify_System
 */
class Unapp_Notify_System extends Epsilon_Notify_System {
	/**
	 * Check installed data
	 */
	public static function check_installed_data() {
		$stylesheet = get_stylesheet();
		$imported   = get_theme_mod( $stylesheet . '_content_imported', false );

		if ( in_array( $imported, array( true, 1, '1' ) ) ) {
			return true;
		}

		if ( in_array( $imported, array( false, 0, '0' ) ) ) {
			return false;
		}

		return $imported;
	}

	/**
	 * Verify the status of a plugin
	 *
	 * @param string      $get         Return title/description/etc.
	 * @param string      $slug        Plugin slug.
	 * @param string      $plugin_name Plugin name.
	 * @param bool|string $special     Callback to verify a certain plugin
	 *
	 * @return mixed
	 */
	public static function plugin_verifier( $slug = '', $get = '', $plugin_name = '', $special = false ) {
		if ( false !== $special ) {
			$arr = self::$special();
		} else {
			$arr = array(
				'installed' => Epsilon_Notify_System::check_plugin_is_installed( $slug ),
				'active'    => Epsilon_Notify_System::check_plugin_is_active( $slug ),
			);

			if ( empty( $get ) ) {
				$arr = array_filter( $arr );

				return 2 === count( $arr );
			}
		}

		// Translators: %s is the plugin name.
		$arr['title'] = sprintf( __( 'Install: %s', 'unapp' ), $plugin_name );
		// Translators: %s is the plugin name.
		$arr['description'] = sprintf( __( 'Please install %s in order to create the demo content.', 'unapp' ), $plugin_name );

		if ( $arr['installed'] ) {
			// Translators: %s is the plugin name
			$arr['title'] = sprintf( __( 'Activate: %s', 'unapp' ), $plugin_name );
			// Translators: %s is the plugin name
			$arr['description'] = sprintf( __( 'Please activate %s in order to create the demo content.', 'unapp' ), $plugin_name );
		}

		return $arr[ $get ];
	}

	/**
	 * @return array
	 */
	public static function verify_cf7() {
		$arr = array(
			'installed' => false,
			'active'    => false,
		);

		if ( file_exists( ABSPATH . 'wp-content/plugins/contact-form-7' ) ) {
			$arr['installed'] = true;
			$arr['active']    = defined( 'WPCF7_VERSION' );
		}

		return $arr;
	}
	public static function verify_mc4wp() {
		$arr = array(
			'installed' => false,
			'active'    => false,
		);

		if ( file_exists( ABSPATH . 'wp-content/plugins/mailchimp-for-wp' ) ) {
			$arr['installed'] = true;
			$arr['active']    = defined( 'MC4WP_VERSION' );
		}

		return $arr;
	}
	public static function verify_upPortfolio() {
		$arr = array(
			'installed' => false,
			'active'    => false,
		);

		if ( file_exists( ABSPATH . 'wp-content/plugins/unapp-portfolio' ) ) {
			$arr['installed'] = true;
			$arr['active']    = defined( 'UPWP_VERSION' );
		}

		return $arr;
	}
}
