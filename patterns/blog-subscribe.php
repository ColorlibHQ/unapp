<?php
/**
 * Title: Blog: subscribe band
 * Slug: unapp/blog-subscribe
 * Categories: unapp, unapp_blog, unapp_cta, newsletter
 * Keywords: blog, subscribe, newsletter, email, rss
 * Viewport Width: 1400
 * Description: A newsletter band in an editorial voice, with an email field and a nod to RSS.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","gradient":"primary-to-accent","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|base"}},"heading":{"color":{"text":"var:preset|color|base"}}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-primary-to-accent-gradient-background has-background has-base-color has-text-color has-link-color" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"620px"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textAlign":"center","textColor":"base","fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color has-xx-large-font-size"><?php esc_html_e( 'One essay, most Fridays', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"large","style":{"color":{"text":"rgba(255,255,255,0.86)"}}} -->
<p class="has-text-align-center has-text-color has-large-font-size" style="color:rgba(255,255,255,0.86);"><?php esc_html_e( 'Four thousand people read it over coffee. No tracking pixels, no sequence, and one click to leave.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:search {"label":"","showLabel":false,"placeholder":"<?php esc_attr_e( 'you@example.com', 'unapp' ); ?>","buttonText":"<?php esc_attr_e( 'Subscribe', 'unapp' ); ?>","align":"center","buttonPosition":"button-inside","style":{"border":{"radius":"999px"}}} /-->
<!-- wp:paragraph {"align":"center","fontSize":"small","style":{"color":{"text":"rgba(255,255,255,0.7)"}}} -->
<p class="has-text-align-center has-text-color has-small-font-size" style="color:rgba(255,255,255,0.7);"><?php esc_html_e( 'Or follow the RSS feed, if you are that sort of person. Many of us are.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
