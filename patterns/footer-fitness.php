<?php
/**
 * Title: Footer: fitness studio
 * Slug: unapp/footer-fitness
 * Categories: unapp, unapp_fitness, footer
 * Keywords: footer, fitness, gym, hours, contact
 * Block Types: core/template-part/footer
 * Viewport Width: 1400
 * Description: A gym footer: the studio in a sentence, timetable links, opening hours and the address.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","backgroundColor":"dark","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|60"},"elements":{"link":{"color":{"text":"var:preset|color|base"},":hover":{"color":{"text":"var:preset|color|secondary"}}},"heading":{"color":{"text":"var:preset|color|base"}}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-dark-background-color has-background has-base-color has-text-color has-link-color" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--40);">
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide">
<!-- wp:column {"width":"34%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column" style="flex-basis:34%;">
<!-- wp:site-title {"level":0,"fontSize":"large"} /-->
<?php if ( get_bloginfo( 'description' ) ) : ?>
<!-- wp:site-tagline {"className":"unapp-footer-note","fontSize":"small"} /-->
<?php else : ?>
<!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"rgba(255,255,255,0.72)"}}} -->
<p class="has-text-color has-small-font-size" style="color:rgba(255,255,255,0.72);"><?php echo esc_html_x( 'A barbell gym under the arches. Twelve people per class, a coach on the floor at every session.', 'Footer tagline', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<?php endif; ?>
<!-- wp:social-links {"iconColor":"base","iconColorValue":"#ffffff","className":"is-style-logos-only","size":"has-small-icon-size","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only">
<!-- wp:social-link {"url":"https://facebook.com","service":"facebook"} /-->
<!-- wp:social-link {"url":"https://instagram.com","service":"instagram"} /-->
<!-- wp:social-link {"url":"https://youtube.com","service":"youtube"} /-->
</ul>
<!-- /wp:social-links -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"22%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column" style="flex-basis:22%;">
<!-- wp:heading {"fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em"}}} -->
<h2 class="wp-block-heading has-small-font-size" style="font-weight:600;letter-spacing:0.06em;"><?php echo esc_html_x( 'Train', 'Footer column heading', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:navigation {"overlayMenu":"never","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"fontSize":"small","layout":{"type":"flex","orientation":"vertical"}} -->
<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Timetable', 'unapp' ); ?>","url":"#timetable","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Memberships', 'unapp' ); ?>","url":"#memberships","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Coaches', 'unapp' ); ?>","url":"#coaches","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Book a session', 'unapp' ); ?>","url":"#book","kind":"custom","isTopLevelLink":true} /-->
<!-- /wp:navigation -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"22%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column" style="flex-basis:22%;">
<!-- wp:heading {"fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em"}}} -->
<h2 class="wp-block-heading has-small-font-size" style="font-weight:600;letter-spacing:0.06em;"><?php echo esc_html_x( 'From the blog', 'Footer column heading', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:latest-posts {"postsToShow":3,"displayPostDate":true,"fontSize":"small"} /-->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"22%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column" style="flex-basis:22%;">
<!-- wp:heading {"fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em"}}} -->
<h2 class="wp-block-heading has-small-font-size" style="font-weight:600;letter-spacing:0.06em;"><?php echo esc_html_x( 'The studio', 'Footer column heading', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"rgba(255,255,255,0.72)"}}} -->
<p class="has-text-color has-small-font-size" style="color:rgba(255,255,255,0.72);"><?php echo esc_html_x( 'Arch 12, Bonnington Yard', 'Footer contact line', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"rgba(255,255,255,0.72)"}}} -->
<p class="has-text-color has-small-font-size" style="color:rgba(255,255,255,0.72);"><?php echo esc_html_x( 'Edinburgh EH6 5NX', 'Footer contact line', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"rgba(255,255,255,0.72)"}}} -->
<p class="has-text-color has-small-font-size" style="color:rgba(255,255,255,0.72);"><?php echo esc_html_x( 'Mon–Thu 06:00–21:00, Fri to 20:00', 'Footer contact line', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"rgba(255,255,255,0.72)"}}} -->
<p class="has-text-color has-small-font-size" style="color:rgba(255,255,255,0.72);"><?php echo esc_html_x( 'hello@archtwelve.example', 'Footer contact line', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- wp:group {"align":"wide","style":{"border":{"top":{"color":"rgba(255,255,255,0.15)","width":"1px","style":"solid"}},"spacing":{"padding":{"top":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="border-top-color:rgba(255,255,255,0.15);border-top-style:solid;border-top-width:1px;padding-top:var(--wp--preset--spacing--40);">
<!-- wp:columns {"verticalAlignment":"center","align":"wide","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide is-not-stacked-on-mobile">
<!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%;">
<!-- wp:paragraph {"style":{"color":{"text":"rgba(255,255,255,0.75)"}},"fontSize":"small"} -->
<p class="has-text-color has-small-font-size" style="color:rgba(255,255,255,0.75)">
<?php
printf(
	/* translators: 1: current year, 2: site name. */
	esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'unapp' ),
	esc_html( date_i18n( 'Y' ) ),
	esc_html( get_bloginfo( 'name' ) )
);
?>
</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%;">
<!-- wp:paragraph {"align":"right","style":{"color":{"text":"rgba(255,255,255,0.75)"}},"fontSize":"small"} -->
<p class="has-text-align-right has-text-color has-small-font-size" style="color:rgba(255,255,255,0.75)"><?php esc_html_e( 'Built with the Unapp theme', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
