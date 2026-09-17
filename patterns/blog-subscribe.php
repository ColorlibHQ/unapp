<?php
/**
 * Title: Blog: subscribe band
 * Slug: unapp/blog-subscribe
 * Categories: unapp, unapp_blog, unapp_cta, newsletter
 * Keywords: blog, subscribe, newsletter, email, rss
 * Viewport Width: 1400
 * Description: A newsletter band in an editorial voice, with subscribe-by-email and RSS buttons.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","gradient":"primary-to-accent","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}},"heading":{"color":{"text":"var:preset|color|base"}}}},"layout":{"type":"constrained"},"anchor":"subscribe"} -->
<div class="wp-block-group alignfull has-primary-to-accent-gradient-background has-background has-base-color has-text-color has-link-color" id="subscribe" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"620px"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textAlign":"center","textColor":"base","fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color has-xx-large-font-size"><?php esc_html_e( 'One essay, most Fridays', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"large","style":{"color":{"text":"rgba(255,255,255,0.86)"}}} -->
<p class="has-text-align-center has-text-color has-large-font-size" style="color:rgba(255,255,255,0.86);"><?php esc_html_e( 'Four thousand people read it over coffee. No tracking pixels, no sequence, and one reply to leave.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--20);">
<!-- wp:button {"textColor":"contrast","backgroundColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-text-color has-base-background-color has-background wp-element-button" href="<?php echo esc_url( 'mailto:editor@theslowbuild.example?subject=' . rawurlencode( _x( 'Subscribe', 'Email subject line', 'unapp' ) ) ); ?>"><?php esc_html_e( 'Subscribe by email', 'unapp' ); ?></a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline","textColor":"base"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-base-color has-text-color wp-element-button" href="<?php echo esc_url( get_feed_link() ); ?>"><?php esc_html_e( 'Follow the RSS feed', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
