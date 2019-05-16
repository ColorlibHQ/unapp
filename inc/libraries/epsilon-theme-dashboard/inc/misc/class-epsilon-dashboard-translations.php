<?php
/**
 * Epsilon Dashboard Translations
 *
 * @package Epsilon Framework
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Epsilon_Dashboard_Translations {
	/**
	 * Returns an array of strings
	 *
	 * @return array
	 */
	public static function get_usual_strings() {
		return array(
			'select'                 => esc_html__( 'Select', 'epsilon-framework' ),
			'import'                 => esc_html__( 'Import', 'epsilon-framework' ),
			'cancel'                 => esc_html__( 'Cancel', 'epsilon-framework' ),
			'selectImport'           => esc_html__( 'Select what you want to install', 'epsilon-framework' ),
			'waitImport'             => esc_html__( 'Please wait while we’re installing!', 'epsilon-framework' ),
			'contentImported'        => esc_html__( 'Content imported!', 'epsilon-framework' ),
			'waiting'                => esc_html__( 'Waiting', 'epsilon-framework' ),
			'installing'             => esc_html__( 'Installing', 'epsilon-framework' ),
			'activating'             => esc_html__( 'Activating', 'epsilon-framework' ),
			'skipping'               => esc_html__( 'Skipping', 'epsilon-framework' ),
			'completePlugin'         => esc_html__( 'Installed and Activated', 'epsilon-framework' ),
			'pluginsFinished'        => esc_html__( 'Plugins installed', 'epsilon-framework' ),
			'noActionsLeft'          => esc_html__( 'Hooray! There are no required actions for you right now.', 'epsilon-framework' ),
			'skipAction'             => esc_html__( 'Skip Action', 'epsilon-framework' ),
			'activateOnly'           => esc_html__( 'Activate', 'epsilon-framework' ),
			'installAndActivate'     => esc_html__( 'Install and Activate', 'epsilon-framework' ),
			'recommended'            => esc_html__( 'Recommended', 'epsilon-framework' ),
			'integration'            => esc_html__( 'Integration', 'epsilon-framework' ),
			'version'                => esc_html__( 'Version: ', 'epsilon-framework' ),
			'licenseKey'             => esc_html__( 'License Key', 'epsilon-framework' ),
			'checkLicense'           => esc_html__( 'Check License', 'epsilon-framework' ),
			'activateLicense'        => esc_html__( 'Activate License', 'epsilon-framework' ),
			'deactivateLicense'      => esc_html__( 'Deactivate License', 'epsilon-framework' ),
			'saveLicense'            => esc_html__( 'Save', 'epsilon-framework' ),
			'changeLicense'          => esc_html__( 'Change License', 'epsilon-framework' ),
			'expires'                => esc_html__( 'Expires: ', 'epsilon-framework' ),
			'status'                 => esc_html__( 'License Status: ', 'epsilon-framework' ),
			'installPlugins'         => esc_html__( 'Install Plugins', 'epsilon-framework' ),
			'notNow'                 => esc_html__( 'Not right now!', 'epsilon-framework' ),
			/**
			 * Uninstall feedback
			 */
			'sendData'               => esc_html__( 'Send data', 'epsilon-framework' ),
			'closeModal'             => esc_html__( 'Close modal', 'epsilon-framework' ),
			'uninstallFeedbackTitle' => esc_html__( 'Uninstall Feedback', 'epsilon-framework' ),
		);
	}

	/**
	 * Returns an array of strings
	 *
	 * @return array
	 */
	public static function get_theme_array( $theme = array() ) {
		$arr = array(
			'logo'   => esc_url( get_template_directory_uri() . '/inc/libraries/epsilon-theme-dashboard/assets/images/macho-themes-logo.png' ),
			/* Translators: Dashboard Header Title. */
			'header' => sprintf( esc_html__( 'Welcome to %1$s - v', 'epsilon-framework' ), esc_html( $theme['theme-name'] ) ) . esc_html( $theme['theme-version'] ),
			/* Translators: Dashboard Header Intro. */
			'intro'  => sprintf( esc_html__( '%1$s is now installed and ready to use! Get ready to build something beautiful. We hope you enjoy it! We want to make sure you have the best experience using %1$s and that is why we gathered here all the necessary information for you. We hope you will enjoy using %1$s, as much as we enjoy creating great products.', 'epsilon-framework' ), esc_html( $theme['theme-name'] ) ),
		);

		$arr = apply_filters(
			'epsilon-dashboard-theme',
			wp_parse_args( $arr, $theme )
		);

		return $arr;
	}
}
