<?php
/**
 * Title: Hero: heading and search
 * Slug: unapp/hero-minimal
 * Categories: unapp, unapp_hero, banner, featured
 * Keywords: hero, heading, search, docs, simple, minimal
 * Viewport Width: 1400
 * Description: Compact type-only hero with a search field — good for documentation, help centres and blog homes.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--60);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Documentation, changelog and everything in between', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Everything you need to run Unapp day to day — guides, API reference and release notes.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"layout":{"type":"constrained","contentSize":"520px"}} -->
<div class="wp-block-group">
<!-- wp:search {"label":"<?php echo esc_html_x( 'Search', 'Search form label', 'unapp' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search the docs…', 'unapp' ); ?>","width":100,"widthUnit":"%","buttonText":"<?php echo esc_html_x( 'Search', 'Search button text', 'unapp' ); ?>","buttonUseIcon":true} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
