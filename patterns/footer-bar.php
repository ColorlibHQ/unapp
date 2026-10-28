<?php
/**
 * Title: Footer: compact bar
 * Slug: unapp/footer-bar
 * Categories: unapp, footer
 * Keywords: footer, branding, navigation, template part
 * Block Types: core/template-part/footer
 * Viewport Width: 1400
 * Description: A compact row of site branding and navigation, with copyright and the site's privacy-policy link.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","className":"unapp-preset-footer","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull unapp-preset-footer has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--50);">
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide">
<!-- wp:site-title {"level":0} /-->
<!-- wp:site-tagline {"fontSize":"small"} /-->
<!-- wp:navigation {"overlayMenu":"never","ariaLabel":"<?php echo esc_attr_x( 'Footer menu', 'Navigation landmark label', 'unapp' ); ?>","layout":{"type":"flex","flexWrap":"wrap"},"fontSize":"small"} /-->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide">
<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html( sprintf( /* translators: %s: site name. */ _x( '© %s', 'Copyright with site name', 'unapp' ), get_bloginfo( 'name' ) ) ); ?></p>
<!-- /wp:paragraph -->
<?php if ( get_privacy_policy_url() ) : ?>
<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy policy', 'unapp' ); ?></a></p>
<!-- /wp:paragraph -->
<?php endif; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
