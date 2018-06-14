<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Autoloader
 */
class Epsilon_Autoloader {
	public function __construct() {
		spl_autoload_register( array( $this, 'load' ) );
	}

	/**
	 * @param $class
	 */
	public function load( $class ) {

		$parts = explode( '_', $class );
		$bind  = implode( '-', $parts );

		$directories = array(
			plugin_dir_path( __FILE__ ) . '/',
			plugin_dir_path( __FILE__ ) . '/classes/',
			plugin_dir_path( __FILE__ ) . '/classes/backend/',
			plugin_dir_path( __FILE__ ) . '/classes/helpers/',
			plugin_dir_path( __FILE__ ) . '/classes/output/',
			plugin_dir_path( __FILE__ ) . '/customizer/',
			plugin_dir_path( __FILE__ ) . '/customizer/controls/',
			plugin_dir_path( __FILE__ ) . '/customizer/panels/',
			plugin_dir_path( __FILE__ ) . '/customizer/sections/',
			plugin_dir_path( __FILE__ ) . '/customizer/settings/',
		);

		foreach ( $directories as $directory ) {
			if ( file_exists( $directory . 'class-' . strtolower( $bind ) . '.php' ) ) {
				require_once $directory . 'class-' . strtolower( $bind ) . '.php';

				return;
			}
		}

	}
}

new Epsilon_Autoloader();
