<?php
/**
 * Title: Hero with app screenshot
 * Slug: unapp/hero
 * Categories: unapp, banner, featured
 * Keywords: hero, banner, app, landing, gradient
 * Viewport Width: 1400
 * Description: Full-width gradient hero with headline, two buttons and a large product screenshot anchored to the bottom edge.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","gradient":"primary-to-accent","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"0"},"blockGap":"var:preset|spacing|40"},"elements":{"heading":{"color":{"text":"var:preset|color|base"}}}},"layout":{"type":"constrained","contentSize":"860px","wideSize":"1100px"}} -->
<div class="wp-block-group alignfull has-base-color has-primary-to-accent-gradient-background has-text-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:0">
	<!-- wp:heading {"textAlign":"center","level":1,"fontSize":"xxx-large"} -->
	<h1 class="wp-block-heading has-text-align-center has-xxx-large-font-size"><?php esc_html_e( 'Take on your biggest projects and goals', 'unapp' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
	<p class="has-text-align-center has-large-font-size"><?php esc_html_e( 'Unapp brings planning, files and conversations into one calm workspace, so your team ships faster with less back-and-forth.', 'unapp' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|30","margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html_x( 'Get Premium', 'Hero primary button', 'unapp' ); ?></a></div>
		<!-- /wp:button -->
		<!-- wp:button {"textColor":"base","className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-base-color has-text-color wp-element-button" href="#"><?php echo esc_html_x( 'See how it works', 'Hero secondary button', 'unapp' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--60)">
		<!-- wp:image {"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":{"topLeft":"16px","topRight":"16px"}},"shadow":"var:preset|shadow|card-strong"}} -->
		<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/dashboard-1.avif' ) ); ?>" alt="<?php esc_attr_e( 'Unapp dashboard showing project analytics and portfolio insights', 'unapp' ); ?>" style="border-top-left-radius:16px;border-top-right-radius:16px;box-shadow:var(--wp--preset--shadow--card-strong)"/></figure>
		<!-- /wp:image -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
