<?php
/**
 * Title: Shop: delivery and returns
 * Slug: unapp/shop-faq
 * Categories: unapp, unapp_shop, unapp_utility, faq
 * Keywords: shop, faq, delivery, returns, repairs, shipping
 * Viewport Width: 1400
 * Description: The four questions asked before every online order.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Before you order', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Delivery, returns and repairs', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<!-- wp:details {"summary":"<?php echo esc_html_x( 'When will it arrive?', 'FAQ question', 'unapp' ); ?>","className":"is-style-faq-card"} -->
<details class="wp-block-details is-style-faq-card"><summary><?php echo esc_html_x( 'When will it arrive?', 'FAQ question', 'unapp' ); ?></summary>
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html_x( 'Ordered before 2pm on a weekday, it leaves the same afternoon and usually lands the next day in the UK. Europe is three to five days, the rest of the world a week or so. Tracking goes out with the dispatch email.', 'FAQ answer', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details {"summary":"<?php echo esc_html_x( 'What if it is not right?', 'FAQ question', 'unapp' ); ?>","className":"is-style-faq-card"} -->
<details class="wp-block-details is-style-faq-card"><summary><?php echo esc_html_x( 'What if it is not right?', 'FAQ question', 'unapp' ); ?></summary>
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html_x( 'Send it back within sixty days, unworn and unwashed, and we refund the lot including what you paid to have it delivered. The return postage is on us.', 'FAQ answer', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details {"summary":"<?php echo esc_html_x( 'Do you repair things?', 'FAQ question', 'unapp' ); ?>","className":"is-style-faq-card"} -->
<details class="wp-block-details is-style-faq-card"><summary><?php echo esc_html_x( 'Do you repair things?', 'FAQ question', 'unapp' ); ?></summary>
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html_x( 'Yes, at cost, for as long as we are still here. Post it to the workshop with a note about what happened and we will quote before doing anything.', 'FAQ answer', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details {"summary":"<?php echo esc_html_x( 'Is it really made where you say?', 'FAQ question', 'unapp' ); ?>","className":"is-style-faq-card"} -->
<details class="wp-block-details is-style-faq-card"><summary><?php echo esc_html_x( 'Is it really made where you say?', 'FAQ question', 'unapp' ); ?></summary>
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html_x( 'Cut, stitched and finished in Leeds by four people whose names are on the About page. Materials come from Devon, Dundee and one German thread mill.', 'FAQ answer', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
