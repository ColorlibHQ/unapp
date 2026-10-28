<?php
/**
 * Title: Salon & Barber: enquiry details
 * Slug: unapp/salon-contact
 * Categories: unapp, unapp_salon, unapp_content
 * Keywords: salon, local, services
 * Viewport Width: 1400
 * Description: Salon & Barber: enquiry details using editable native WordPress blocks.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Get in touch', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Ready for your next look?', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Email the service you want and a few preferred times, or call to talk it through. We will confirm availability and the right next step.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<?php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup; each part is escaped where unapp_contact_form() builds it.
echo unapp_contact_form(
	array(
		'title' => _x( 'Request an appointment', 'Contact form heading', 'unapp' ),
		'email' => 'hello@juniperhair.example',
	)
);
?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
