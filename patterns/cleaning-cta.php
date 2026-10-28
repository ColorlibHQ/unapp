<?php
/**
 * Title: Cleaning: contact request
 * Slug: unapp/cleaning-cta
 * Categories: unapp, unapp_cleaning, unapp_content
 * Keywords: cleaning, local, services
 * Viewport Width: 1400
 * Description: Cleaning: contact request using editable native WordPress blocks.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","gradient":"primary-to-accent","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}},"heading":{"color":{"text":"var:preset|color|base"}}}},"layout":{"type":"constrained"},"anchor":"cleaning-enquiry"} -->
<div class="wp-block-group alignfull has-primary-to-accent-gradient-background has-background has-base-color has-text-color has-link-color" id="cleaning-enquiry" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textAlign":"center","textColor":"base","fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color has-xx-large-font-size"><?php esc_html_e( 'Let us know what needs a fresh start', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"base","fontSize":"large"} -->
<p class="has-text-align-center has-base-color has-text-color has-large-font-size"><?php esc_html_e( 'Send your postcode, property size and the kind of clean you need. We will confirm the checklist, price and a suitable time with you.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"className":"unapp-starter-actions","style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons unapp-starter-actions" style="margin-top:var(--wp--preset--spacing--20);">
<!-- wp:button {"textColor":"contrast","backgroundColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-text-color has-base-background-color has-background wp-element-button" href="<?php echo esc_url( 'mailto:hello@clearday.example?subject=' . rawurlencode( _x( 'Cleaning quote', 'Email subject line', 'unapp' ) ) ); ?>"><?php esc_html_e( 'Get a cleaning quote', 'unapp' ); ?></a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline","textColor":"base"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-base-color has-text-color wp-element-button" href="tel:+441145550128"><?php esc_html_e( 'Ask a cleaning question', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
