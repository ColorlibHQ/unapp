<?php
/**
 * Title: Blog: masthead
 * Slug: unapp/blog-masthead
 * Categories: unapp, unapp_blog, unapp_content, posts, query, header
 * Keywords: blog, masthead, title, magazine, header
 * Viewport Width: 1400
 * Description: A magazine-style masthead: publication name, standfirst and a gradient rule.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textAlign":"center","fontSize":"xxx-large","style":{"typography":{"lineHeight":"1.05"}}} -->
<h2 class="wp-block-heading has-text-align-center has-xxx-large-font-size" style="line-height:1.05;"><?php esc_html_e( 'The Slow Build', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Essays on making software carefully, published most Fridays.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:separator {"className":"is-style-gradient"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-gradient"/>
<!-- /wp:separator -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
