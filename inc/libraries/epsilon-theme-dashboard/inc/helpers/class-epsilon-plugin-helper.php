<?php
/**
 * Epsilon Dashboard  Helper
 *
 * @package Epsilon Framework
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Plugin_Helper
 */
class Epsilon_Plugin_Helper {
	/**
	 * @var array
	 */
	protected $plugins = array();
	/**
	 * @var array
	 */
	protected $theme = array();

	/**
	 * Epsilon_Plugin_Helper constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		foreach ( $args as $k => $v ) {

			if ( ! in_array(
				$k,
				array(
					'theme',
					'plugins',
				)
			)
			) {
				continue;
			}

			$this->$k = $v;
		}
	}

	/**
	 * Instance creator
	 *
	 * @param array $args
	 *
	 * @return Epsilon_Plugin_Helper
	 */
	public static function get_instance( $args = array() ) {
		static $inst;
		if ( ! $inst ) {
			$inst = new Epsilon_Plugin_Helper( $args );
		}

		return $inst;
	}

	/**
	 * Return an array with plugin info
	 */
	public function handle_plugins() {
		$arr = array();

		foreach ( $this->plugins as $plugin => $recommended ) {
			$arr[] = $this->get_plugin_information( $plugin, $recommended['recommended'], $recommended['integration'] );
		}

		return $arr;
	}

	/**
	 * Return information of a plugin
	 *
	 * @param string $slug        Plugin slug.
	 * @param string $recommended Recommended flag
	 * @param string $integration Integration flag
	 *
	 * @return array
	 */
	private function get_plugin_information( $slug = '', $recommended = '', $integration = '' ) {
		$arr = array(
			'id'          => $slug,
			'info'        => $this->call_plugin_api( $slug ),
			'url'         => null,
			'state'       => 'waiting',
			'recommended' => '1' === $recommended ? true : false,
			'integration' => '1' === $integration ? true : false,
			'installing'  => false,
		);

		$arr['icon'] = $this->check_for_icon( $arr['info']->icons );
		$merge       = $this->check_plugin( $slug );

		$arr = array_merge( $arr, $merge );

		return $arr;
	}

	/**
	 * Get information about a plugin
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return array|mixed|object|WP_Error
	 */
	private function call_plugin_api( $slug = '' ) {
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		$call_api = get_transient( $this->theme['theme-slug'] . '_plugin_information_transient_' . $slug );

		if ( false === $call_api ) {
			$call_api = plugins_api( 'plugin_information', array(
				'slug'   => $slug,
				'fields' => array(
					'downloaded'        => false,
					'rating'            => false,
					'description'       => false,
					'short_description' => true,
					'donate_link'       => false,
					'tags'              => false,
					'sections'          => true,
					'homepage'          => true,
					'added'             => false,
					'last_updated'      => false,
					'compatibility'     => false,
					'tested'            => false,
					'requires'          => false,
					'downloadlink'      => false,
					'icons'             => true,
				),
			) );
			set_transient( $this->theme['theme-slug'] . '_plugin_information_transient_' . $slug, $call_api, 24 * HOUR_IN_SECONDS );
		}

		return $call_api;
	}

	/**
	 * Will return an array with everything that we need to render the action info
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @returns array
	 */
	private function check_plugin( $slug = '' ) {
		$arr = array(
			'installed' => Epsilon_Notify_System::check_plugin_is_installed( $slug ),
			'active'    => Epsilon_Notify_System::check_plugin_is_active( $slug ),
		);

		if ( null === $arr['active'] ) {
			$arr['active'] = false;
		}

		if ( $arr['installed'] ) {
			$url        = $this->create_plugin_activation_link( array( 'slug' => $slug ) );
			$arr['url'] = $url['url'];
		}

		return $arr;
	}

	/**
	 * Searches icons for the plugin
	 *
	 * @param object $object Icon object.
	 *
	 * @return string;
	 */
	private function check_for_icon( $object ) {
		if ( ! empty( $object['svg'] ) ) {
			$plugin_icon_url = $object['svg'];
		} elseif ( ! empty( $object['2x'] ) ) {
			$plugin_icon_url = $object['2x'];
		} elseif ( ! empty( $object['1x'] ) ) {
			$plugin_icon_url = $object['1x'];
		} else {
			$plugin_icon_url = $object['default'];
		}

		return $plugin_icon_url;
	}

	/**
	 * Create plugin activation links
	 *
	 * @param array $args
	 *
	 * @return array|string
	 */
	public function create_plugin_activation_link( $args = array() ) {
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
}