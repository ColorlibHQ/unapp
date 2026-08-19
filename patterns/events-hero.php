<?php
/**
 * Title: Event: hero
 * Slug: unapp/events-hero
 * Categories: unapp, unapp_events, banner, featured
 * Keywords: events, conference, hero, tickets, programme
 * Viewport Width: 1400
 * Description: A full-bleed conference cover with the dates, the shape of the event and a ticket button.
 *
 * @package Unapp
 */

?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/abstract/gathering.svg' ) ); ?>","dimRatio":70,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":68,"minHeightUnit":"vh","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"860px"}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);min-height:68vh"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/abstract/gathering.svg' ) ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
<!-- wp:paragraph {"align":"center","textColor":"base","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-base-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Bristol · 14–15 May 2027', 'Conference hero eyebrow', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","textColor":"base","fontSize":"xxx-large"} -->
<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color has-xxx-large-font-size"><?php esc_html_e( 'Two days about building things that outlast the team that built them', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"base","fontSize":"large"} -->
<p class="has-text-align-center has-base-color has-text-color has-large-font-size"><?php esc_html_e( 'Sixteen talks, no sponsor keynotes, four hundred people and a bar that opens at five.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40);">
<!-- wp:button {"textColor":"primary","backgroundColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-text-color has-base-background-color has-background wp-element-button" href="#"><?php esc_html_e( 'Buy a ticket', 'unapp' ); ?></a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline","textColor":"base"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-base-color has-text-color wp-element-button" href="#"><?php esc_html_e( 'See the programme', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div></div>
<!-- /wp:cover -->
