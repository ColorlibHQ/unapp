<?php
/**
 * Title: Call to action: colour band
 * Slug: unapp/cta-band
 * Categories: unapp, unapp_cta, call-to-action, banner
 * Keywords: cta, banner, band, gradient, conversion
 * Viewport Width: 1400
 * Description: A compact full-width gradient band with a headline and one button.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","className":"is-style-section-gradient","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-gradient" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);">
<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center","width":"62%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:62%;">
<!-- wp:heading {"textColor":"base","fontSize":"x-large"} -->
<h2 class="wp-block-heading has-base-color has-text-color has-x-large-font-size"><?php esc_html_e( 'Ready to see it with your own projects?', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"base"} -->
<p class="has-base-color has-text-color"><?php esc_html_e( 'Import a board and watch it come to life. It takes about five minutes.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"38%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:38%;">
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"textColor":"primary","backgroundColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-text-color has-base-background-color has-background wp-element-button" href="#"><?php esc_html_e( 'Start free trial', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
