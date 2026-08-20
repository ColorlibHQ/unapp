<?php
/**
 * Title: Church: closing invitation
 * Slug: unapp/church-cta
 * Categories: unapp, unapp_church, unapp_cta, call-to-action
 * Keywords: church, cta, invitation, visit, sunday
 * Viewport Width: 1400
 * Description: A warm closing band inviting a visit, on the palette gradient.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","gradient":"primary-to-accent","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}},"heading":{"color":{"text":"var:preset|color|base"}}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-primary-to-accent-gradient-background has-background has-base-color has-text-color has-link-color" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textAlign":"center","textColor":"base","fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color has-xx-large-font-size"><?php esc_html_e( 'There is a service this Sunday at 9:30 and 11:15', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"large","style":{"color":{"text":"rgba(255,255,255,0.86)"}}} -->
<p class="has-text-align-center has-text-color has-large-font-size" style="color:rgba(255,255,255,0.86);"><?php esc_html_e( 'Come on your own, come late, come and sit at the back. All of that is completely normal here.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--20);">
<!-- wp:button {"textColor":"contrast","backgroundColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-text-color has-base-background-color has-background wp-element-button" href="#visit"><?php esc_html_e( 'Plan your visit', 'unapp' ); ?></a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline","textColor":"base"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-base-color has-text-color wp-element-button" href="#watch"><?php esc_html_e( 'Watch a service online', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
