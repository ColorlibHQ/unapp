<?php
/**
 * Title: Sidebar
 * Slug: unapp/sidebar
 * Categories: unapp_utility
 * Block Types: core/template-part/sidebar
 * Keywords: sidebar, aside, widgets, search, categories
 * Viewport Width: 400
 * Description: Search, recent posts and categories — the sidebar used by the two sidebar templates.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"fontSize":"large"} -->
		<h2 class="wp-block-heading has-large-font-size"><?php esc_html_e( 'Search', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:pattern {"slug":"unapp/hidden-search"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"fontSize":"large"} -->
		<h2 class="wp-block-heading has-large-font-size"><?php esc_html_e( 'Recent posts', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:latest-posts {"postsToShow":4,"displayPostDate":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"fontSize":"large"} -->
		<h2 class="wp-block-heading has-large-font-size"><?php esc_html_e( 'Topics', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:categories {"showPostCounts":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group is-style-card" style="border-radius:16px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
		<!-- wp:heading {"level":2,"fontSize":"large"} -->
		<h2 class="wp-block-heading has-large-font-size"><?php esc_html_e( 'Try Unapp free', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
		<p class="has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'Fourteen days of the Team plan. No card, no sales call.', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button {"width":100} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html_x( 'Start free trial', 'Button text', 'unapp' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
