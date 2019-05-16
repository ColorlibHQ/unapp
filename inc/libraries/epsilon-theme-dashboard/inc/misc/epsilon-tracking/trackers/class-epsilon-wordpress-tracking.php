<?php
/**
 * Epsilon WordPress Tracking
 *
 * @package Epsilon Framework
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Wordpress_Tracking
 */
class Epsilon_Wordpress_Tracking extends Epsilon_Tracking {
	/**
	 * Generate data
	 *
	 * @return array
	 */
	public function generate_data() {
		return array(
			/**
			 * Gets the WordPress version
			 */
			'wp_version' => get_bloginfo( 'version' ),
			/**
			 * Is it a multisite?
			 */
			'multisite'  => is_multisite(),
			/**
			 * Get the language
			 */
			'locale'     => ( get_bloginfo( 'version' ) >= 4.7 ) ? get_user_locale() : get_locale(),
			/**
			 * Get themes ( installed and currently active )
			 */
			'themes'     => $this->get_themes(),
			/**
			 * Get plugins ( installed and currently active )
			 */
			'plugins'    => $this->get_plugins(),
		);
	}

	/**
	 * Get current themes
	 *
	 * @return array
	 */
	public function get_themes() {
		$theme = wp_get_theme();

		return array(
			'installed' => $this->_get_installed_themes(),
			'active'    => array(
				'slug'    => get_stylesheet(),
				'name'    => $theme->get( 'Name' ),
				'version' => $theme->get( 'Version' ),
				'author'  => $theme->get( 'Author' ),
			),
		);
	}

	/**
	 * Get an array of installed themes
	 */
	private function _get_installed_themes() {
		$installed = wp_get_themes();
		$theme     = get_stylesheet();
		$arr       = array();

		foreach ( $installed as $slug => $info ) {
			if ( $slug === $theme ) {
				continue;
			}
			$arr[ $slug ] = array(
				'slug'    => $slug,
				'name'    => $info->get( 'Name' ),
				'version' => $info->get( 'Version' ),
				'author'  => $info->get( 'Author' )
			);
		};

		return $arr;
	}

	/**
	 * Get a list of installed plugins
	 */
	public function get_plugins() {
		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins   = get_plugins();
		$option    = get_option( 'active_plugins', array() );
		$active    = array();
		$installed = array();
		foreach ( $plugins as $id => $info ) {
			if ( in_array( $id, $active ) ) {
				continue;
			}

			$id = explode( '/', $id );
			$id = ucwords( str_replace( '-', ' ', $id[0] ) );

			$installed[] = $id;
		}

		foreach ( $option as $id ) {
			$id = explode( '/', $id );
			$id = ucwords( str_replace( '-', ' ', $id[0] ) );

			$active[] = $id;
		}

		return array(
			'installed' => $installed,
			'active'    => $active,
		);
	}
}