<?php
/**
 * Title: Hero: email capture
 * Slug: unapp/hero-email
 * Categories: unapp, unapp_hero, banner, featured
 * Keywords: hero, email, signup, waitlist, early access
 * Viewport Width: 1400
 * Description: Centred hero with an early-access request by email, a reassurance line and a product screenshot.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"760px","wideSize":"1100px"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Early access', 'Hero eyebrow', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"textAlign":"center","fontSize":"xx-large","style":{"typography":{"lineHeight":"1.2"}}} -->
<h1 class="wp-block-heading has-text-align-center has-xx-large-font-size" style="line-height:1.2;"><?php esc_html_e( 'The workspace your team will actually use', 'unapp' ); ?></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Join 10,000 teams planning, shipping and reporting in one calm place.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( 'mailto:hello@example.com?subject=' . rawurlencode( _x( 'Early access', 'Email subject line', 'unapp' ) ) ); ?>"><?php esc_html_e( 'Request early access', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"small"} -->
<p class="has-text-align-center has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'Free while in beta. Every request gets a reply from a person.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:image {"width":"100%","aspectRatio":"30/19","sizeSlug":"full","linkDestination":"none","align":"wide","style":{"border":{"radius":"20px"},"shadow":"var:preset|shadow|card-strong"}} -->
<figure class="wp-block-image alignwide size-full is-resized has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/dashboard-2.avif' ) ); ?>" alt="<?php esc_attr_e( 'Unapp analytics dashboard', 'unapp' ); ?>" style="border-radius:20px;box-shadow:var(--wp--preset--shadow--card-strong);aspect-ratio:30/19;width:100%;height:auto"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
