<?php
/**
 * Title: Portfolio: about (opening a page)
 * Slug: unapp/portfolio-about-h1
 * Inserter: no
 * Categories: unapp, unapp_portfolio, portfolio, featured
 * Viewport Width: 1400
 * Description: The same section with its heading as the page's h1, for the first section of a page.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%;">
<!-- wp:image {"width":"100%","aspectRatio":"3/2","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"20px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/abstract/desk.svg' ) ); ?>" alt="<?php esc_attr_e( 'Studio placeholder', 'unapp' ); ?>" style="border-radius:20px;aspect-ratio:3/2;width:100%;height:auto"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"55%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%;">
<!-- wp:paragraph {"align":"left","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-left has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'About', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"xx-large","style":{"typography":{"lineHeight":"1.2"}}} -->
<h1 class="wp-block-heading has-xx-large-font-size" style="line-height:1.2;"><?php esc_html_e( 'Fifteen years, three cities, one obsession', 'unapp' ); ?></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"large"} -->
<p class="has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'I started in editorial, spent a decade in agencies and now work directly with founders. Most projects run six to ten weeks, start to finish.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns">
<!-- wp:column {} -->
<div class="wp-block-column">
<!-- wp:list {"className":"is-style-dash"} -->
<ul class="wp-block-list is-style-dash">
<!-- wp:list-item -->
<li><?php esc_html_e( 'Brand identity', 'unapp' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><?php esc_html_e( 'Packaging', 'unapp' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><?php esc_html_e( 'Art direction', 'unapp' ); ?></li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column {} -->
<div class="wp-block-column">
<!-- wp:list {"className":"is-style-dash"} -->
<ul class="wp-block-list is-style-dash">
<!-- wp:list-item -->
<li><?php esc_html_e( 'Web design', 'unapp' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><?php esc_html_e( 'Typography', 'unapp' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><?php esc_html_e( 'Print', 'unapp' ); ?></li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
