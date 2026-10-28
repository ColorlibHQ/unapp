<?php
/**
 * Title: Footer: brand and menu
 * Slug: unapp/footer-columns
 * Categories: unapp, footer
 * Keywords: footer, branding, navigation, template part
 * Block Types: core/template-part/footer
 * Viewport Width: 1400
 * Description: Site name, tagline and a footer menu in two columns, followed by copyright and the site's privacy-policy link.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","className":"unapp-preset-footer","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull unapp-preset-footer has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--50);">
<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top alignwide">
<!-- wp:column {"verticalAlignment":"top","width":"45%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:45%;">
<!-- wp:group {"className":"unapp-footer-brand","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group unapp-footer-brand">
<!-- wp:site-title {"level":0} /-->
<!-- wp:site-tagline {"fontSize":"small"} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"top","width":"55%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:55%;">
<!-- wp:navigation {"overlayMenu":"never","ariaLabel":"<?php echo esc_attr_x( 'Footer menu', 'Navigation landmark label', 'unapp' ); ?>","layout":{"type":"flex","flexWrap":"wrap"},"fontSize":"small"} /-->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
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
