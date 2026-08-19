<?php
/**
 * Title: Shop: the workshop
 * Slug: unapp/shop-workshop
 * Categories: unapp, unapp_shop, unapp_company, about
 * Keywords: shop, about, workshop, making, materials, craft
 * Viewport Width: 1400
 * Description: Where the goods are made and what from, beside a photograph.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center","width":"45%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%;">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"20px"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/abstract/desk.svg' ) ); ?>" alt="<?php esc_attr_e( 'The bench', 'unapp' ); ?>" style="border-radius:20px;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"55%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%;">
<!-- wp:paragraph {"align":"left","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-left has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'How it is made', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Four people, two benches, no factory', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"large"} -->
<p class="has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Leather from a tannery in Devon that has been at it since 1863, canvas from Dundee, and thread we buy in quantities that embarrass our accountant.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist">
<!-- wp:list-item -->
<li><?php esc_html_e( 'Cut, stitched and finished in Leeds', 'unapp' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><?php esc_html_e( 'Every piece signed by whoever made it', 'unapp' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><?php esc_html_e( 'Repairs at cost, for as long as we exist', 'unapp' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><?php esc_html_e( 'Offcuts go to a bookbinder down the road', 'unapp' ); ?></li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
