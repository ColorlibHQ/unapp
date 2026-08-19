<?php
/**
 * Title: Portfolio: work grid
 * Slug: unapp/portfolio-work
 * Categories: unapp, unapp_portfolio, portfolio, featured
 * Keywords: portfolio, work, projects, grid, gallery, case studies
 * Viewport Width: 1400
 * Description: Four projects in a two-by-two grid with title and credits under each.
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
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Selected work', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Recent projects', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide">
<?php foreach ( array_slice( $unapp_projects, 0, 2 ) as $unapp_project ) : ?>
<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","aspectRatio":"4/3","scale":"cover","style":{"border":{"radius":"14px"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/abstract/' . $unapp_project['image'] . '.svg' ) ); ?>" alt="<?php echo esc_attr( $unapp_project['title'] ); ?>" style="border-radius:14px;aspect-ratio:4/3;object-fit:cover;"/></figure>
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
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide">
<?php foreach ( array_slice( $unapp_projects, 2, 2 ) as $unapp_project ) : ?>
<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","aspectRatio":"4/3","scale":"cover","style":{"border":{"radius":"14px"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/abstract/' . $unapp_project['image'] . '.svg' ) ); ?>" alt="<?php echo esc_attr( $unapp_project['title'] ); ?>" style="border-radius:14px;aspect-ratio:4/3;object-fit:cover;"/></figure>
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
