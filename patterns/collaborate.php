<?php
/**
 * Title: Collaborate (image and text)
 * Slug: unapp/collaborate
 * Categories: unapp, about, media
 * Keywords: media text, image, checklist, about, collaborate
 * Viewport Width: 1400
 * Description: Media & Text section with a photo, headline, checklist and call-to-action on a soft background.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:media-text {"align":"wide","mediaType":"image","mediaWidth":52,"imageFill":false,"style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->
	<div class="wp-block-media-text alignwide is-stacked-on-mobile" style="grid-template-columns:52% auto"><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/office.avif' ) ); ?>" alt="<?php esc_attr_e( 'Bright open-plan office with a large shared table', 'unapp' ); ?>"/></figure><div class="wp-block-media-text__content">
		<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"heading"} -->
		<p class="has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase"><?php echo esc_html_x( 'Teamwork', 'Section eyebrow label', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'Collaborate with your design team in a new way', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"textColor":"muted","fontSize":"large"} -->
		<p class="has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Share boards, leave feedback directly on the canvas and turn decisions into tasks without ever leaving Unapp.', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:list {"className":"is-style-checklist"} -->
		<ul class="wp-block-list is-style-checklist">
			<!-- wp:list-item -->
			<li><?php esc_html_e( 'Unlimited boards and projects', 'unapp' ); ?></li>
			<!-- /wp:list-item -->
			<!-- wp:list-item -->
			<li><?php esc_html_e( 'Real-time comments and mentions', 'unapp' ); ?></li>
			<!-- /wp:list-item -->
			<!-- wp:list-item -->
			<li><?php esc_html_e( 'Version history for every file', 'unapp' ); ?></li>
			<!-- /wp:list-item -->
		</ul>
		<!-- /wp:list -->
		<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html_x( 'Start collaborating', 'Button text', 'unapp' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div></div>
	<!-- /wp:media-text -->
</div>
<!-- /wp:group -->
