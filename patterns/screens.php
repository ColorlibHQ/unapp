<?php
/**
 * Title: App screens gallery
 * Slug: unapp/screens
 * Categories: unapp, gallery, portfolio
 * Keywords: gallery, screenshots, portfolio, lightbox, work
 * Viewport Width: 1400
 * Description: Section intro and a three-column gallery of product screenshots that open in the built-in lightbox.
 *
 * @package Unapp
 */

$unapp_screens = array(
	array(
		'file'    => 'dashboard-1.avif',
		'caption' => _x( 'Portfolio overview', 'Gallery caption', 'unapp' ),
		'alt'     => _x( 'Dashboard with balance history chart and project risk gauge', 'Image alt text', 'unapp' ),
	),
	array(
		'file'    => 'dashboard-2.avif',
		'caption' => _x( 'Analytics and reports', 'Gallery caption', 'unapp' ),
		'alt'     => _x( 'Dashboard with analytics widgets and charts', 'Image alt text', 'unapp' ),
	),
	array(
		'file'    => 'dashboard-3.avif',
		'caption' => _x( 'Project timeline', 'Gallery caption', 'unapp' ),
		'alt'     => _x( 'Dashboard with project timeline and task list', 'Image alt text', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"heading"} -->
		<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase"><?php echo esc_html_x( 'Screens', 'Section eyebrow label', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"textAlign":"center"} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'A closer look at Unapp', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Clean dashboards that surface what matters. Click any screen to enlarge it.', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:gallery {"columns":3,"linkTo":"none","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
	<figure class="wp-block-gallery alignwide has-nested-images columns-3 is-cropped">
		<?php foreach ( $unapp_screens as $unapp_screen ) : ?>
		<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"12px"},"shadow":"var:preset|shadow|card"}} -->
		<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/' . $unapp_screen['file'] ) ); ?>" alt="<?php echo esc_attr( $unapp_screen['alt'] ); ?>" style="border-radius:12px;box-shadow:var(--wp--preset--shadow--card)"/><figcaption class="wp-element-caption"><?php echo esc_html( $unapp_screen['caption'] ); ?></figcaption></figure>
		<!-- /wp:image -->
		<?php endforeach; ?>
	</figure>
	<!-- /wp:gallery -->
</div>
<!-- /wp:group -->
