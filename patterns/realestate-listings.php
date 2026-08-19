<?php
/**
 * Title: Property: listings
 * Slug: unapp/realestate-listings
 * Categories: unapp, unapp_realestate, unapp_content
 * Keywords: real estate, property, listings, for sale, homes, grid
 * Viewport Width: 1400
 * Description: Four properties with photograph, price and the room count.
 *
 * @package Unapp
 */

$unapp_listings = array(
	array(
		'image' => 'skyline',
		'name'  => _x( 'Lansdown Crescent', 'Property name', 'unapp' ),
		'price' => _x( '£1,250,000', 'Property price', 'unapp' ),
		'meta'  => _x( '4 bed · 2 bath · Grade I listed', 'Property details', 'unapp' ),
	),
	array(
		'image' => 'desk',
		'name'  => _x( 'Walcot Street', 'Property name', 'unapp' ),
		'price' => _x( '£465,000', 'Property price', 'unapp' ),
		'meta'  => _x( '2 bed · 1 bath · Top-floor flat', 'Property details', 'unapp' ),
	),
	array(
		'image' => 'gathering',
		'name'  => _x( 'Bathford Mill', 'Property name', 'unapp' ),
		'price' => _x( '£720,000', 'Property price', 'unapp' ),
		'meta'  => _x( '3 bed · 2 bath · Converted mill', 'Property details', 'unapp' ),
	),
	array(
		'image' => 'studio-1',
		'name'  => _x( 'Bear Flat', 'Property name', 'unapp' ),
		'price' => _x( '£389,000', 'Property price', 'unapp' ),
		'meta'  => _x( '2 bed · 1 bath · Victorian terrace', 'Property details', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'For sale', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'On the books this week', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Everything we are selling, with the price on it. No \'guide price on application\'.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-4","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->
<div class="wp-block-group alignwide unapp-grid-4">
<?php foreach ( $unapp_listings as $unapp_listing ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","aspectRatio":"4/3","scale":"cover","style":{"border":{"radius":"20px"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo get_theme_file_uri( 'assets/images/abstract/' . $unapp_listing['image'] . '.svg' ); ?>" alt="<?php echo $unapp_listing['name']; ?>" style="border-radius:20px;aspect-ratio:4/3;object-fit:cover;"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_listing['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"primary","style":{"typography":{"fontWeight":"700"}}} -->
<p class="has-primary-color has-text-color" style="font-weight:700;"><?php echo $unapp_listing['price']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_listing['meta']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
