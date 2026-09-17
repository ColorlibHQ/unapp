<?php
/**
 * Title: Waitlist / coming soon
 * Slug: unapp/waitlist
 * Categories: unapp, unapp_cta, call-to-action, banner
 * Keywords: waitlist, coming soon, launch, early access, signup
 * Viewport Width: 1400
 * Description: Full-bleed gradient panel with a join-the-waitlist button and social links — the whole page for a pre-launch site.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","className":"is-style-section-gradient","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"620px"}} -->
<div class="wp-block-group alignfull is-style-section-gradient" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"620px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"base","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-base-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Coming soon', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","textColor":"base"} -->
<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color"><?php esc_html_e( 'Something new is nearly ready', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"base","fontSize":"large"} -->
<p class="has-text-align-center has-base-color has-text-color has-large-font-size"><?php esc_html_e( 'We are putting the finishing touches to the next version of Unapp. Send us a note and you will be first through the door.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"textColor":"primary","backgroundColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-text-color has-base-background-color has-background wp-element-button" href="<?php echo esc_url( 'mailto:hello@example.com?subject=' . rawurlencode( _x( 'Waitlist', 'Email subject line', 'unapp' ) ) ); ?>"><?php esc_html_e( 'Join the waitlist by email', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
<!-- wp:social-links {"iconColor":"base","iconColorValue":"#ffffff","className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links has-icon-color is-style-logos-only">
<!-- wp:social-link {"url":"https://x.com","service":"x"} /-->
<!-- wp:social-link {"url":"https://linkedin.com","service":"linkedin"} /-->
<!-- wp:social-link {"url":"https://github.com","service":"github"} /-->
</ul>
<!-- /wp:social-links -->
</div>
<!-- /wp:group -->
