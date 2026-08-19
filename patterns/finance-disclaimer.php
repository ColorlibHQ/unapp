<?php
/**
 * Title: Finance: risk warning
 * Slug: unapp/finance-disclaimer
 * Categories: unapp, unapp_finance, featured, text
 * Keywords: finance, disclaimer, risk, compliance, legal
 * Viewport Width: 1400
 * Description: A bordered compliance note for regulated firms — replace with your own wording.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|0"}},"layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);">
<!-- wp:group {"className":"is-style-outline","style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-outline" style="border-radius:12px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40);">
<!-- wp:paragraph {"textColor":"muted","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.08em","textTransform":"uppercase"}}} -->
<p class="has-muted-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.08em;text-transform:uppercase;"><?php esc_html_e( 'Risk warning', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'The value of investments can fall as well as rise and you may get back less than you invested. Past performance is not a guide to future returns. Tax treatment depends on individual circumstances and may change. This page is a placeholder — replace it with your own regulated wording before publishing.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
