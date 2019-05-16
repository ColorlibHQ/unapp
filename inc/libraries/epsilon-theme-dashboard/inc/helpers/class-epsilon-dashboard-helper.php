<?php
/**
 * Epsilon Dashboard  Helper
 *
 * @package Epsilon Framework
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Epsilon_Dashboard_Helper {
	/**
	 * Creates a plugin activation link, that we can use in our ajax request
	 *
	 * @param array $args
	 *
	 * @return array | string
	 */
	public static function create_plugin_activation_link( $args = array() ) {
		if ( empty( $args ) || empty( $args['slug'] ) ) {
			return 'nok';
		}

		$string = add_query_arg(
			array(
				'action'        => 'activate',
				'plugin'        => rawurlencode( Epsilon_Notify_System::_get_plugin_basename_from_slug( $args['slug'] ) ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . Epsilon_Notify_System::_get_plugin_basename_from_slug( $args['slug'] ) ),
			),
			network_admin_url( 'plugins.php' )
		);

		return array(
			'message' => 'ok',
			'url'     => $string,
		);
	}

	/**
	 * Formats the plugins
	 *
	 * @param array $args
	 *
	 * @return array | string
	 */
	public static function format_plugins( $args = array() ) {
		$instance = Epsilon_Plugin_Helper::get_instance( $args );

		return array(
			'status'  => true,
			'plugins' => $instance->handle_plugins(),
		);
	}

	/**
	 * Sets an option in wordpress
	 *
	 * @param array $args
	 *
	 * @return string;
	 */
	public static function set_options( $args = array() ) {
		if ( ! empty( $args['option'] ) ) {
			self::_set_options( $args['option'] );
		}

		if ( ! empty( $args['theme_mod'] ) ) {
			self::_set_theme_mods( $args['theme_mod'] );
		}

		return 'ok';
	}

	/**
	 * Gets the value of an worpdress option
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function get_options( $args = array() ) {
		if ( ! empty( $args['option'] ) ) {
			return array(
				'status' => true,
				'value'  => get_option( $args['option'], false )
			);
		}

		if ( ! empty( $args['theme_mod'] ) ) {
			return array(
				'status' => true,
				'value'  => get_theme_mod( $args['theme_mod'], false )
			);
		}

		return array(
			'status' => false,
			'value'  => null,
		);
	}

	/**
	 * Get visibility options
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function get_visibility_options( $args = array() ) {
		return array(
			'status' => true,
			'option' => get_option( $args['theme']['theme-slug'] . $args['option'], array() )
		);
	}

	/**
	 * Set visibility option for a required action
	 *
	 * @param array $args
	 *
	 * @return string;
	 */
	public static function set_visibility_option( $args = array() ) {
		$option = get_option( $args['theme']['theme-slug'] . $args['option'], array() );

		foreach ( $args['actions'] as $action => $value ) {
			$args['actions'][ $action ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
		}

		update_option( $args['theme']['theme-slug'] . $args['option'], wp_parse_args( $args['actions'], $option ) );

		return 'ok';
	}

	/**
	 * Sets options
	 *
	 * @param array $options
	 */
	public static function _set_options( $options = array() ) {
		foreach ( $options as $option => $value ) {
			update_option( $option, $value );
		}
	}

	/**
	 * Sets theme mods
	 *
	 * @param array $options
	 */
	public static function _set_theme_mods( $options = array() ) {
		foreach ( $options as $option => $value ) {
			set_theme_mod( $option, $value );
		}
	}

	/**
	 * Lets find the demos
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function get_demos( $args = array() ) {
		$path = get_template_directory() . '/inc/libraries/epsilon-theme-dashboard/assets/data/demo.json';
		if ( ! empty( $args ) && ! empty( $args['path'] ) && file_exists( $args['path'] ) ) {
			$path = $args['path'];
		}

		$importer = Epsilon_Import_Data::get_instance(
			array(
				'path' => $path,
			)
		);

		$importer->set_demos();

		/**
		 * In case the json could not be decoded, we return a new stdClass
		 */
		if ( null === $importer ) {
			return array(
				'status' => 'nok',
				'demos'  => $importer->demos_js,
			);
		}

		return array(
			'status' => 'ok',
			'demos'  => $importer->demos_js,
		);
	}

	/**
	 * Setup the user meta ( Instead of using cookies, we can use the user meta )
	 *
	 * @param array $args
	 *
	 * @return string;
	 */
	public static function set_user_meta( $args = array() ) {
		update_user_meta( get_current_user_id(), $args['option'], absint( $args['tab'] ) );

		return 'ok';
	}
}
