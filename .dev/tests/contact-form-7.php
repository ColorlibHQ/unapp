<?php
/** Optional real-plugin smoke test, inside the import suite's transaction. */
$cf7_path = ABSPATH . 'wp-content/plugins/contact-form-7/wp-contact-form-7.php';
if ( ! is_file( $cf7_path ) ) {
	return;
}
$cf7_header = get_file_data( $cf7_path, array( 'php' => 'Requires PHP', 'wp' => 'Requires at least' ) );
if ( version_compare( PHP_VERSION, $cf7_header['php'], '<' ) || version_compare( get_bloginfo( 'version' ), $cf7_header['wp'], '<' ) ) {
	return;
}
$cf7_original_choice = get_option( UNAPP_CONTACT_FORM_OPTION, null );
require_once $cf7_path;
// This fixture is loaded after init; initialize only CF7 rather than rerunning WP hooks.
wpcf7();
wpcf7_init();
$cf7_first = WPCF7_ContactForm::get_template( array( 'title' => 'Launch fixture general contact' ) );
$cf7_first->save();
$cf7_second = WPCF7_ContactForm::get_template( array( 'title' => 'Launch fixture booking' ) );
$cf7_second->save();
$expect( true === unapp_set_contact_form( 'cf7:' . $cf7_second->id() ), 'a real Contact Form 7 form can be selected' );
$cf7_slot = do_blocks( unapp_contact_form( array( 'email' => 'owner@real.test' ) ) );
$expect( false !== strpos( $cf7_slot, 'wpcf7-form' ) && preg_match( '/name="_wpcf7" value="' . $cf7_second->id() . '"/', $cf7_slot ), 'the selected Contact Form 7 instance renders through a dynamic contact slot' );
$expect( isset( unapp_available_contact_forms()[ 'cf7:' . $cf7_first->id() ], unapp_available_contact_forms()[ 'cf7:' . $cf7_second->id() ] ), 'the real-plugin picker lists multiple saved forms' );
if ( null === $cf7_original_choice ) { delete_option( UNAPP_CONTACT_FORM_OPTION ); } else { update_option( UNAPP_CONTACT_FORM_OPTION, $cf7_original_choice, false ); }
unapp_reset_detected_form();
