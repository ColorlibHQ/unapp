<?php
/**
 * Title: Pull quote
 * Slug: unapp/pull-quote
 * Categories: unapp, unapp_proof, text
 * Keywords: quote, pull quote, editorial, testimonial
 * Viewport Width: 1400
 * Description: A single oversized quote set in the display face, for breaking up a long page.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","fontFamily":"heading","fontSize":"xxx-large","style":{"typography":{"lineHeight":"1.25"}}} -->
<p class="has-text-align-center has-heading-font-family has-xxx-large-font-size" style="line-height:1.25;"><?php esc_html_e( '&#8220;We stopped having the same meeting every Monday. That is the whole review.&#8221;', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"small"} -->
<p class="has-text-align-center has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'Elena Marsh · Head of Delivery, Northbank', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
