<?php
/**
 * Title: Shop: hero
 * Slug: unapp/shop-hero
 * Categories: unapp, unapp_shop, banner, featured
 * Keywords: shop, hero, store, ecommerce, workshop
 * Viewport Width: 1400
 * Description: A storefront introduction with two calls to action and an image.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center","width":"55%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%;">
<!-- wp:paragraph {"align":"left","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-left has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Workshop and shop', 'Shop hero eyebrow', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"fontSize":"xxx-large","style":{"typography":{"lineHeight":"1.05"}}} -->
<h2 class="wp-block-heading has-xxx-large-font-size" style="line-height:1.05;"><?php esc_html_e( 'Things we make, in numbers we can stand behind', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"large"} -->
<p class="has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'A small workshop in Leeds making bags, aprons and a few things that did not fit either category. Two hundred of anything, then we stop and think about it.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/shop/"><?php esc_html_e( 'Shop everything', 'unapp' ); ?></a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#making"><?php esc_html_e( 'How it is made', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"45%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%;">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"20px"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/abstract/studio-1.svg' ) ); ?>" alt="<?php esc_attr_e( 'The workshop', 'unapp' ); ?>" style="border-radius:20px;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
