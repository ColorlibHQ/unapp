<?php
/**
 * Title: Footer: slim
 * Slug: unapp/footer-slim
 * Categories: unapp, footer
 * Keywords: footer, slim, simple, minimal, template part
 * Block Types: core/template-part/footer
 * Viewport Width: 1400
 * Description: A one-row footer with the site title, a short menu, copyright and social links.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","backgroundColor":"dark","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|40"},"elements":{"link":{"color":{"text":"var:preset|color|base"},":hover":{"color":{"text":"var:preset|color|secondary"}}}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-dark-background-color has-background has-base-color has-text-color has-link-color" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--50);">
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide">
<!-- wp:site-title {"level":0,"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}}} /-->
<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex"},"style":{"typography":{"textTransform":"none","letterSpacing":"0","fontWeight":"400"}},"fontFamily":"body","fontSize":"small"} -->
<!-- wp:navigation-link {"label":"<?php echo esc_html_x( 'Privacy', 'Footer menu link', 'unapp' ); ?>","url":"#"} /-->
<!-- wp:navigation-link {"label":"<?php echo esc_html_x( 'Terms', 'Footer menu link', 'unapp' ); ?>","url":"#"} /-->
<!-- wp:navigation-link {"label":"<?php echo esc_html_x( 'Status', 'Footer menu link', 'unapp' ); ?>","url":"#"} /-->
<!-- /wp:navigation -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide">
<!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"rgba(255,255,255,0.75)"}}} -->
<p class="has-text-color has-small-font-size" style="color:rgba(255,255,255,0.75);"><?php printf( esc_html__( '© %1$s %2$s', 'unapp' ), esc_html( date_i18n( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:social-links {"iconColor":"base","iconColorValue":"#ffffff","className":"is-style-logos-only","size":"has-small-icon-size"} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only">
<!-- wp:social-link {"url":"https://x.com","service":"x"} /-->
<!-- wp:social-link {"url":"https://linkedin.com","service":"linkedin"} /-->
<!-- wp:social-link {"url":"https://github.com","service":"github"} /-->
</ul>
<!-- /wp:social-links -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
