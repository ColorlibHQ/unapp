<?php
/**
 * Epsilon Behavior Tracking
 *
 * @package Epsilon Framework
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Behavior_Tracking
 */
class Epsilon_Behavior_Tracking extends Epsilon_Tracking {
	/**
	 * Generate the data array
	 *
	 * @return array
	 */
	public function generate_data() {
		$theme = get_stylesheet();
		$arr   = array(
			'imported_demo'   => get_theme_mod( $theme . '_content_imported', false ),
			'used_onboarding' => get_theme_mod( $theme . '_used_onboarding', false ),
			'privacy'         => array(
				'lite_vs_pro'         => get_option( $theme . '_lite_vs_pro', false ),
				'recommended_plugins' => get_option( $theme . '_recommended_plugins', false ),
				'recommended_actions' => get_option( $theme . '_recommended_actions', false ),
				'theme_upsells'       => get_option( $theme . '_theme_upsells', false ),
			),
		);

		foreach ( $arr as $k => $v ) {
			if ( is_array( $v ) ) {
				continue;
			}

			if ( in_array( $v, array( true, 1, '1' ) ) ) {
				$arr[ $k ] = true;
			}

			if ( in_array( $v, array( false, 0, '0' ) ) ) {
				$arr[ $k ] = false;
			}
		}

		return $arr;
	}
}