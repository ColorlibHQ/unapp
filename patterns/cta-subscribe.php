<?php
/**
 * Title: Call to action with background image
 * Slug: unapp/cta-subscribe
 * Categories: unapp, call-to-action, banner
 * Keywords: cta, subscribe, newsletter, cover, banner
 * Viewport Width: 1400
 * Description: Full-width cover with a tinted photo, headline, supporting text and a subscribe button. Replace the button with a form block from your newsletter plugin if you have one.
 *
 * @package Unapp
 */

?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/crowd.avif' ) ); ?>","dimRatio":80,"overlayColor":"primary","isUserOverlayColor":true,"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-80 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/crowd.avif' ) ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
	<!-- wp:heading {"textAlign":"center","textColor":"base"} -->
	<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color"><?php esc_html_e( 'Already trusted by over 10,000 teams', 'unapp' ); ?></h2>
	<!-- /wp:heading -->
	<!-- wp:paragraph {"align":"center","style":{"color":{"text":"rgba(255,255,255,0.85)"}},"fontSize":"large"} -->
	<p class="has-text-align-center has-text-color has-large-font-size" style="color:rgba(255,255,255,0.85)"><?php esc_html_e( 'Subscribe to receive product updates and productivity tips straight to your inbox. No spam, unsubscribe any time.', 'unapp' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:button {"backgroundColor":"base","textColor":"primary"} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-base-background-color has-text-color has-background wp-element-button" href="#"><?php echo esc_html_x( 'Subscribe', 'Button text', 'unapp' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div></div>
<!-- /wp:cover -->
