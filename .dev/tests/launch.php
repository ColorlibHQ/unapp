<?php
/** Included by import.php inside its rollback-only WordPress transaction. */
$launch_original = get_post( $result['home'] )->post_content;
$launch_user = get_current_user_id();
$initial_choice = get_option( UNAPP_CONTACT_FORM_OPTION, null );
$initial_reviews = get_option( UNAPP_LAUNCH_REVIEWS, null );

$scan = unapp_launch_inspect( '<!-- wp:paragraph --><p>hello@studio.example and 0151 555 0188</p><!-- /wp:paragraph --><!-- wp:button --><div class="wp-block-button"><a href="#">More</a></div><!-- /wp:button -->' );
$expect( $scan['contacts'] && $scan['placeholders'], 'launch checks detect demo contacts and unfinished links' );
$scan = unapp_launch_inspect( '<!-- wp:paragraph --><p>owner@company.co.uk, 0151 444 0188, 0151 555 9999 and hello@example.com.au</p><!-- /wp:paragraph -->' );
$expect( ! $scan['contacts'], 'genuine-looking contact details are not flagged as demo numbers or partial example domains' );
$scan = unapp_launch_inspect( '<!-- wp:navigation-link {"label":"Contact","url":"mailto:team@demo.example.com"} /-->' );
$expect( $scan['contacts'], 'navigation block attributes and reserved email subdomains are checked' );
$scan = unapp_launch_inspect( '<!-- wp:button {"className":"unapp-period"} --><div><a href="#">Yearly</a></div><!-- /wp:button --><!-- wp:button {"className":"unapp-scheme-toggle"} --><div><a href="#">Dark</a></div><!-- /wp:button -->' );
$expect( ! $scan['placeholders'], 'working pricing and dark-mode controls are excluded from unfinished-link checks' );
$scan = unapp_launch_inspect( '<!-- wp:html --><pre><code>hello@example.com</code></pre><!-- /wp:html -->' );
$expect( ! $scan['contacts'], 'documentation code samples are not treated as public contact details' );
$scan = unapp_launch_inspect( '<!-- wp:paragraph --><p><a href="tel:+441175550192">Call the studio</a></p><!-- /wp:paragraph -->' );
$expect( $scan['contacts'], 'a demo telephone destination is detected even when its visible label is real copy' );
$scan = unapp_launch_inspect( '<!-- wp:pattern {"slug":"unapp/missing-launch-fixture"} /-->' );
$expect( $scan['placeholders'], 'unavailable shared content is surfaced for review' );
$scan = unapp_launch_inspect( '<!-- wp:paragraph --><p><a href="mailto:">Email</a><a href="tel:">Call</a></p><!-- /wp:paragraph -->' );
$expect( $scan['placeholders'], 'empty email and telephone destinations need attention' );
$scan = unapp_launch_inspect( '<!-- wp:navigation-link {"label":"hello@example.com","url":"https://business.test/contact"} /-->' );
$expect( $scan['contacts'], 'visible navigation labels are checked for sample contacts' );

$shared = wp_insert_post( array( 'post_type' => 'wp_block', 'post_status' => 'publish', 'post_title' => 'Shared launch fixture', 'post_content' => '<!-- wp:paragraph --><p>Contact hello@shared.example</p><!-- /wp:paragraph -->' ) );
$shared_markup = '<!-- wp:block {"ref":' . $shared . '} /-->';
$before_shared = unapp_launch_inspect( $shared_markup );
wp_update_post( array( 'ID' => $shared, 'post_content' => '<!-- wp:paragraph --><p>Contact owner@real.test</p><!-- /wp:paragraph -->' ) );
$after_shared = unapp_launch_inspect( $shared_markup );
$expect( $before_shared['contacts'] && ! $after_shared['contacts'] && $before_shared['fingerprint'] !== $after_shared['fingerprint'], 'shared block edits update findings and review fingerprints' );
wp_update_post( array( 'ID' => $shared, 'post_content' => $shared_markup ) );
$expect( unapp_launch_inspect( $shared_markup )['placeholders'], 'circular shared references terminate safely' );

wp_update_post( array( 'ID' => $result['home'], 'post_content' => wp_slash( $launch_original . '<!-- wp:paragraph --><p>hello@unfinished.example</p><!-- /wp:paragraph -->' ) ) );
$data = unapp_launch_checklist();
$expect( ! $data['checks']['contacts']['complete'] && isset( $data['issues']['contacts'][ 'page:' . $result['home'] ] ), 'findings identify the page that needs editing' );
$expect( ! $data['checks']['content']['complete'] && $data['issues']['content'], 'starter wording is offered as an owner-review hint' );
$expect( true === unapp_save_launch_reviews( $data['snapshot'], array( 'content', 'delivery', 'mobile', 'contacts' ) ), 'manual reviews can be saved for the displayed snapshot' );
$reviewed = unapp_launch_checklist();
$expect( $reviewed['checks']['content']['complete'] && ! $reviewed['checks']['contacts']['complete'], 'manual reviews cannot override failed automatic checks' );
wp_update_post( array( 'ID' => $result['home'], 'post_content' => wp_slash( $launch_original ) ) );
$fresh = unapp_launch_checklist();
$expect( ! $fresh['checks']['content']['complete'] && $fresh['snapshot'] !== $data['snapshot'], 'editing starter content resets owner reviews' );
$expect( is_wp_error( unapp_save_launch_reviews( $data['snapshot'], array( 'content', 'delivery', 'mobile' ) ) ), 'stale browser tabs cannot certify a newer snapshot' );
$expect( true === unapp_save_launch_reviews( $fresh['snapshot'], array( 'content', 'delivery', 'mobile' ) ), 'fresh reviews can be saved after editing' );
wp_cache_flush();
$expect( unapp_launch_checklist()['checks']['content']['complete'], 'owner reviews persist across fresh WordPress caches' );
$active_fixture = get_option( UNAPP_STARTER_OPTION );
delete_option( UNAPP_STARTER_OPTION );
$expect( unapp_launch_checklist() && unapp_launch_active_starter()['automatic'], 'activation-created Home and Blog sites also receive a checklist' );
update_option( UNAPP_STARTER_OPTION, $active_fixture );
$custom_home = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'New homepage', 'post_content' => '<!-- wp:paragraph --><p>hello@new.example</p><!-- /wp:paragraph -->' ) );
update_option( 'page_on_front', $custom_home );
$expect( isset( unapp_launch_checklist()['issues']['contacts'][ 'page:' . $custom_home ] ), 'a replacement static home page is included in the scan' );
update_option( 'page_on_front', $result['home'] );

// A plugin-like fixture exercises published form enumeration, selection and rendering.
register_post_type( 'unapp_test_form', array( 'public' => false, 'label' => 'Test Forms' ) );
$provider_filter = static function ( $providers ) {
	$providers['testforms'] = array( 'label' => 'Fixture Forms', 'post_type' => 'unapp_test_form', 'shortcode' => '[unapp_test_form id="%d"]' );
	return $providers;
};
add_filter( 'unapp_form_providers', $provider_filter );
add_shortcode( 'unapp_test_form', static function ( $atts ) { return '<form data-selected-form="' . absint( $atts['id'] ) . '"><input name="name"></form>'; } );
$first_form = wp_insert_post( array( 'post_type' => 'unapp_test_form', 'post_status' => 'publish', 'post_title' => 'General enquiries' ) );
$second_form = wp_insert_post( array( 'post_type' => 'unapp_test_form', 'post_status' => 'publish', 'post_title' => 'Booking <script>alert(1)</script>' ) );
$draft_form = wp_insert_post( array( 'post_type' => 'unapp_test_form', 'post_status' => 'draft', 'post_title' => 'Unfinished form' ) );
$choices = unapp_available_contact_forms();
$expect( isset( $choices[ 'testforms:' . $first_form ], $choices[ 'testforms:' . $second_form ] ) && ! isset( $choices[ 'testforms:' . $draft_form ] ), 'only published forms appear in the selector' );
$expect( true === unapp_set_contact_form( 'auto' ) && $first_form === unapp_detect_form()['id'], 'automatic mode retains first-form detection' );
$expect( true === unapp_set_contact_form( 'testforms:' . $second_form ) && $second_form === unapp_detect_form()['id'], 'the owner can explicitly select a different saved form' );
ob_start();
unapp_render_contact_form_selection();
$valid_picker = ob_get_clean();
$expect( false === strpos( $valid_picker, '&lt;script&gt;' ) && false === strpos( $valid_picker, '<script>' ), 'form titles are displayed as plain text in both the summary and selector' );
$slot = unapp_contact_form( array( 'email' => 'owner@real.test' ) );
$rendered = do_blocks( $slot );
$expect( false !== strpos( $rendered, 'data-selected-form="' . $second_form . '"' ) && false === strpos( $rendered, 'mailto:owner@real.test' ), 'the selected form replaces an existing dynamic email fallback' );
$expect( get_post( $result['home'] )->post_content === $launch_original, 'choosing a form does not rewrite starter pages' );
$expect( ! unapp_launch_checklist()['checks']['delivery']['complete'], 'changing the contact form resets delivery review' );
require $root . '/.dev/tests/launch-recommendations.php';
if ( getenv( 'UNAPP_LAUNCH_PREVIEW_PATH' ) ) {
	ob_start();
	echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><style>';
	foreach ( array( 'wp-includes/css/buttons.css', 'wp-admin/css/common.css', 'wp-admin/css/forms.css' ) as $css ) {
		echo file_get_contents( ABSPATH . $css );
	}
	echo 'body{margin:24px;background:#f0f0f1;font:14px/1.5 -apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.wrap{margin:auto}</style></head><body class="wp-core-ui">';
	unapp_render_launch_screen();
	echo '</body></html>';
	file_put_contents( getenv( 'UNAPP_LAUNCH_PREVIEW_PATH' ), ob_get_clean() );
}

$bad_choice = unapp_set_contact_form( 'testforms:' . $draft_form );
$expect( is_wp_error( $bad_choice ) && get_option( UNAPP_CONTACT_FORM_OPTION ) === 'testforms:' . $second_form, 'invalid form choices do not replace the previous selection' );
$expect( is_wp_error( unapp_set_contact_form( '[arbitrary-shortcode]' ) ), 'the settings endpoint never accepts arbitrary shortcode markup' );

$form_data = unapp_launch_checklist();
unapp_save_launch_reviews( $form_data['snapshot'], array( 'content', 'delivery', 'mobile' ) );
update_post_meta( $second_form, 'notification_recipient', 'changed@real.test' );
$expect( ! unapp_launch_checklist()['checks']['delivery']['complete'], 'saved form setting changes reset the delivery review for post-based providers' );
wp_trash_post( $second_form );
$expect( null === unapp_detect_form() && get_option( UNAPP_CONTACT_FORM_OPTION ) === 'testforms:' . $second_form, 'deleting a selected form uses email instead of silently choosing another form' );
$unavailable = unapp_launch_checklist();
$expect( ! $unavailable['checks']['method']['complete'] && $unavailable['checks']['delivery']['blocked'], 'unavailable selections are flagged and cannot be marked delivery-tested' );
unapp_save_launch_reviews( $unavailable['snapshot'], array( 'delivery' ) );
$expect( ! unapp_launch_checklist()['checks']['delivery']['complete'], 'posting a blocked delivery checkbox cannot certify an unavailable form' );
ob_start();
unapp_render_contact_form_selection();
$picker = ob_get_clean();
$expect( false !== strpos( $picker, 'Previously selected form (unavailable)' ) && false === strpos( $picker, '<script>alert' ), 'the picker explains unavailable selections and safely renders form titles' );

unapp_set_contact_form( 'testforms:' . $first_form );
unregister_post_type( 'unapp_test_form' );
unapp_reset_detected_form();
$expect( null === unapp_detect_form(), 'a deactivated provider falls back to email' );
$expect( true === unapp_set_contact_form( 'email' ) && null === unapp_detect_form(), 'email-only mode is an explicit supported choice' );
$expect( ! isset( unapp_wizard_plugin_suggestions( 'saas' )['form'] ), 'email-only sites are not prompted to install another form plugin' );
$override = static function () { return array( 'key' => 'custom', 'label' => 'Custom form', 'markup' => '<form></form>' ); };
add_filter( 'unapp_detected_form', $override );
$expect( 'custom' === unapp_detect_form()['key'] && 'custom' === unapp_detect_form()['key'], 'developer overrides are applied even after detection is cached' );
remove_filter( 'unapp_detected_form', $override );
$expect( null === unapp_detect_form(), 'removing an override restores the saved contact choice' );

wp_set_current_user( 0 );
$expect( is_wp_error( unapp_set_contact_form( 'auto' ) ), 'users without theme permissions cannot change contact selection' );
$expect( is_wp_error( unapp_save_launch_reviews( 'anything', array( 'content' ) ) ), 'users without theme permissions cannot save launch reviews' );
$visitor = do_blocks( $slot );
$expect( false !== strpos( $visitor, 'mailto:owner@real.test' ) && false === strpos( $visitor, 'Only you can see this' ) && false === strpos( $visitor, 'unapp-launch-checklist' ), 'anonymous visitors receive the email fallback without admin hints or links' );
$legacy = '<p><em>' . esc_html( unapp_form_admin_hint() ) . '</em></p>';
$expect( '' === unapp_hide_saved_form_hint( $legacy, array() ), 'legacy saved admin hints remain hidden from visitors' );
wp_set_current_user( $launch_user );
remove_shortcode( 'unapp_test_form' );
remove_filter( 'unapp_form_providers', $provider_filter );
if ( null === $initial_choice ) { delete_option( UNAPP_CONTACT_FORM_OPTION ); } else { update_option( UNAPP_CONTACT_FORM_OPTION, $initial_choice, false ); }
if ( null === $initial_reviews ) { delete_option( UNAPP_LAUNCH_REVIEWS ); } else { update_option( UNAPP_LAUNCH_REVIEWS, $initial_reviews, false ); }
unapp_reset_detected_form();
