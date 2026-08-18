<?php
/**
 * Title: Blog heading
 * Slug: unapp/hidden-blog-heading
 * Inserter: no
 * Description: Heading and intro shown above the posts grid on the blog home.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:heading {"textAlign":"center","level":1,"fontSize":"xx-large"} -->
	<h1 class="wp-block-heading has-text-align-center has-xx-large-font-size"><?php esc_html_e( 'Latest blog posts', 'unapp' ); ?></h1>
	<!-- /wp:heading -->
	<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
	<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Product updates, customer stories and practical tips on running projects with less friction.', 'unapp' ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
