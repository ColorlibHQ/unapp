<?php
/**
 * Title: Hero: photo background
 * Slug: unapp/hero-cover
 * Categories: unapp, unapp_hero, banner, featured
 * Keywords: hero, cover, photo, image, banner, overlay
 * Viewport Width: 1400
 * Description: Full-height Cover hero with a tinted photograph behind the headline and buttons.
 *
 * @package Unapp
 */

?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/crowd.avif' ) ); ?>","dimRatio":80,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":72,"minHeightUnit":"vh","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"780px"}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);min-height:72vh"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-80 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/crowd.avif' ) ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
<!-- wp:heading {"textAlign":"center","textColor":"base","fontSize":"xxx-large"} -->
<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color has-xxx-large-font-size"><?php esc_html_e( 'Built for teams who ship every week', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"base","fontSize":"large"} -->
<p class="has-text-align-center has-base-color has-text-color has-large-font-size"><?php esc_html_e( 'From first sketch to release notes, Unapp keeps everyone pointed at the same goal.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40);">
<!-- wp:button {"textColor":"primary","backgroundColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-text-color has-base-background-color has-background wp-element-button" href="#"><?php esc_html_e( 'Get Premium', 'unapp' ); ?></a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline","textColor":"base"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-base-color has-text-color wp-element-button" href="#"><?php esc_html_e( 'Watch the tour', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div></div>
<!-- /wp:cover -->
