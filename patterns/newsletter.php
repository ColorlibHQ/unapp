<?php
/**
 * Title: Newsletter sign-up
 * Slug: unapp/newsletter
 * Categories: unapp, unapp_cta, call-to-action, text
 * Keywords: newsletter, subscribe, email, signup, inline
 * Viewport Width: 1400
 * Description: Inline newsletter row: pitch on the left, email field on the right. Swap the field for your mail plugin's block.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);">
<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center","width":"52%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:52%;">
<!-- wp:heading {"level":3,"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-x-large-font-size"><?php esc_html_e( 'The Friday changelog', 'unapp' ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php esc_html_e( 'One short email a week: what shipped, what broke, what we learned. No marketing.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"48%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:48%;">
<!-- wp:search {"label":"<?php echo esc_html_x( 'Email address', 'Newsletter field label', 'unapp' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'you@company.com', 'unapp' ); ?>","width":100,"widthUnit":"%","buttonText":"<?php echo esc_html_x( 'Subscribe', 'Newsletter button', 'unapp' ); ?>","buttonPosition":"button-inside"} /-->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'Roughly 900 readers. Unsubscribe in one click.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
