<?php
/**
 * Unapp Theme Autoloader
 *
 * @package unapp
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class unapp_Autoloader
 */
class Unapp_Autoloader {
	/**
	 * Unapp_Autoloader constructor.
	 */
	public function __construct() {
		spl_autoload_register( array( $this, 'load' ) );
	}

	/**
	 * This function loads the necessary files
	 *
	 * @param string $class CLASS NAME.
	 */
	public function load( $class = '' ) {
		/**
		 * All classes are prefixed with unapp_
		 */
		$parts = explode( '_', $class );
		$bind  = implode( '-', $parts );

		/**
		 * We provide working directories
		 */
		$directories = array(
			get_template_directory() . '/inc/',
			get_template_directory() . '/inc/libraries/',
			get_template_directory() . '/inc/libraries/widgets/',
			get_template_directory() . '/inc/libraries/epsilon-framework/',
			get_template_directory() . '/inc/libraries/epsilon-theme-dashboard/',
			get_template_directory() . '/inc/libraries/epsilon-theme-dashboard/inc/',
			get_template_directory() . '/inc/libraries/epsilon-theme-dashboard/inc/helpers/',
			get_template_directory() . '/inc/libraries/epsilon-theme-dashboard/inc/misc/',
			get_template_directory() . '/inc/libraries/epsilon-theme-dashboard/inc/misc/demo-generators/',
			get_template_directory() . '/inc/libraries/epsilon-theme-dashboard/inc/misc/epsilon-tracking/',
			get_template_directory() . '/inc/libraries/epsilon-theme-dashboard/inc/misc/epsilon-tracking/trackers/',
			get_template_directory() . '/inc/helpers/',
			get_template_directory() . '/inc/customizer/',
			get_template_directory() . '/inc/libraries/widgets',
		);

		/**
		 * Loop through them, if we find the class .. we load it !
		 */
		foreach ( $directories as $directory ) {
			if ( file_exists( $directory . 'class-' . strtolower( $bind ) . '.php' ) ) {
				require_once $directory . 'class-' . strtolower( $bind ) . '.php';

				return;
			}
		}
	}
}

new Unapp_Autoloader();
