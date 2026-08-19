<?php
/**
 * Title: Portfolio: client quote
 * Slug: unapp/portfolio-testimonial
 * Categories: unapp, unapp_portfolio, unapp_proof, testimonials
 * Keywords: portfolio, testimonial, client, quote, review
 * Viewport Width: 1400
 * Description: A single client quote, centred, with a portrait and attribution.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","fontSize":"xx-large","style":{"typography":{"lineHeight":"1.35"}}} -->
<p class="has-text-align-center has-xx-large-font-size" style="line-height:1.35;"><?php esc_html_e( '&#8220;She showed us two directions in a fortnight and then argued us out of the one we liked. She was right. The thing we shipped is the thing people can actually use.&#8221;', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","align":"center","width":"64px","height":"64px","style":{"border":{"radius":"999px"}}} -->
<figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/avatars/avatar-3.svg' ) ); ?>" alt="<?php esc_attr_e( 'Client portrait placeholder', 'unapp' ); ?>" style="border-radius:999px;width:64px;height:64px;"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-text-align-center" style="font-weight:600;"><?php esc_html_e( 'Ollie Trent', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"small"} -->
<p class="has-text-align-center has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'Founder, Nordwell Coffee', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
