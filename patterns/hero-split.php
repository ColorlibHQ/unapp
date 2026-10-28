<?php
/**
 * Title: Hero: split with screenshot
 * Slug: unapp/hero-split
 * Categories: unapp, unapp_hero, banner, featured
 * Keywords: hero, banner, split, screenshot, landing
 * Viewport Width: 1400
 * Description: Two-column hero: copy and buttons on the left, a product screenshot on the right.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","className":"unapp-starter-hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull unapp-starter-hero" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center","width":"46%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:46%;">
<!-- wp:paragraph {"align":"left","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-left has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'One workspace. Real progress.', 'Hero eyebrow', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"textAlign":"left","fontSize":"xxx-large","style":{"typography":{"lineHeight":"1.1"}}} -->
<h1 class="wp-block-heading has-text-align-left has-xxx-large-font-size" style="line-height:1.1;"><?php esc_html_e( 'Less chasing. More shipping.', 'unapp' ); ?></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"large"} -->
<p class="has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Bring projects, files and conversations together. Give your team a clear plan and see what is ready to ship.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"className":"unapp-starter-actions","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons unapp-starter-actions">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>"><?php esc_html_e( 'Explore plans', 'unapp' ); ?></a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#how-it-works' ) ); ?>"><?php esc_html_e( 'See how it works', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'Start with the free plan. Grow when you are ready.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"54%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:54%;">
<!-- wp:image {"width":"100%","aspectRatio":"4/3","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"20px"},"shadow":"var:preset|shadow|card-strong"}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/showcase/workspace-board.svg' ) ); ?>" alt="<?php esc_attr_e( 'Unapp website launch board with tasks grouped into To do, In progress and Ready', 'unapp' ); ?>" style="border-radius:20px;box-shadow:var(--wp--preset--shadow--card-strong);aspect-ratio:4/3;width:100%;height:auto"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
