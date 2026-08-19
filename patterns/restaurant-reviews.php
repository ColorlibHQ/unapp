<?php
/**
 * Title: Restaurant: reviews
 * Slug: unapp/restaurant-reviews
 * Categories: unapp, unapp_restaurant, unapp_proof, testimonials
 * Keywords: restaurant, reviews, press, quotes, praise
 * Viewport Width: 1400
 * Description: Three short press quotes with their source.
 *
 * @package Unapp
 */

$unapp_reviews = array(
	array(
		'quote' => _x( "The hake was the best thing I ate all year, and I eat out for a living.", 'Restaurant review', 'unapp' ),
		'source' => _x( "Bristol Food Review", 'Restaurant review', 'unapp' ),
	),
	array(
		'quote' => _x( "Ten tables, no music, no theatre — just extremely good cooking and someone who remembers your name.", 'Restaurant review', 'unapp' ),
		'source' => _x( "The Wharf Guide", 'Restaurant review', 'unapp' ),
	),
	array(
		'quote' => _x( "Book six weeks out. It is worth the diary management.", 'Restaurant review', 'unapp' ),
		'source' => _x( "Somerset Life", 'Restaurant review', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Said about us', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Kind words', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_reviews as $unapp_review ) : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.5"}}} -->
<p class="has-large-font-size" style="line-height:1.5;"><?php echo '&#8220;' . $unapp_review['quote'] . '&#8221;'; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_review['source']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
