<?php
/**
 * Title: Portfolio: work grid (opening a page)
 * Slug: unapp/portfolio-work-h1
 * Inserter: no
 * Categories: unapp, unapp_portfolio, portfolio, featured
 * Viewport Width: 1400
 * Description: The same section with its heading as the page's h1, for the first section of a page.
 *
 * @package Unapp
 */

$unapp_projects = array(
	array( 'image' => 'studio-1', 'title' => _x( 'Nordwell Coffee', 'Project title', 'unapp' ), 'meta' => _x( 'Identity · Packaging · 2026', 'Project meta', 'unapp' ) ),
	array( 'image' => 'desk', 'title' => _x( 'Fold Studio', 'Project title', 'unapp' ), 'meta' => _x( 'Website · Art direction · 2025', 'Project meta', 'unapp' ) ),
	array( 'image' => 'skyline', 'title' => _x( 'Meridian Housing', 'Project title', 'unapp' ), 'meta' => _x( 'Brand system · 2025', 'Project meta', 'unapp' ) ),
	array( 'image' => 'gathering', 'title' => _x( 'Common Ground', 'Project title', 'unapp' ), 'meta' => _x( 'Campaign · 2024', 'Project meta', 'unapp' ) ),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Selected work', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"textAlign":"center","fontSize":"xx-large","style":{"typography":{"lineHeight":"1.2"}}} -->
<h1 class="wp-block-heading has-text-align-center has-xx-large-font-size" style="line-height:1.2;"><?php esc_html_e( 'Recent projects', 'unapp' ); ?></h1>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns alignwide">
<?php foreach ( array_slice( $unapp_projects, 0, 2 ) as $unapp_project ) : ?>
<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column">
<!-- wp:image {"width":"100%","aspectRatio":"4/3","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"20px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/abstract/' . $unapp_project['image'] . '.svg' ) ); ?>" alt="<?php echo esc_attr( $unapp_project['title'] ); ?>" style="border-radius:20px;aspect-ratio:4/3;object-fit:cover;width:100%;height:auto"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_project['title'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_project['meta'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns alignwide">
<?php foreach ( array_slice( $unapp_projects, 2, 2 ) as $unapp_project ) : ?>
<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column">
<!-- wp:image {"width":"100%","aspectRatio":"4/3","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"20px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/abstract/' . $unapp_project['image'] . '.svg' ) ); ?>" alt="<?php echo esc_attr( $unapp_project['title'] ); ?>" style="border-radius:20px;aspect-ratio:4/3;object-fit:cover;width:100%;height:auto"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_project['title'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_project['meta'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
