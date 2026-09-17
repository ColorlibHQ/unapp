<?php
/**
 * Remove the plugin's settings, including any saved AI key, when it is deleted.
 *
 * @package Unapp_Library
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'unapp_library_ai' );
delete_option( 'unapp_library_enabled' );
delete_option( 'unapp_ai_running' );
delete_transient( 'unapp_library_remote' );
delete_transient( 'unapp_ai_result' );
delete_site_transient( 'unapp_library_update' );
