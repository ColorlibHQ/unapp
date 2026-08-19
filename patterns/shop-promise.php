<?php
/**
 * Title: Shop: promises
 * Slug: unapp/shop-promise
 * Categories: unapp, unapp_shop, unapp_features
 * Keywords: shop, shipping, returns, guarantee, promise, ecommerce
 * Viewport Width: 1400
 * Description: Four reassurance cards: delivery, returns, guarantee and how the run sizes work.
 *
 * @package Unapp
 */

$unapp_shop_promises = array(
	array(
		'icon' => 'package',
		'title' => _x( "Sent within a day", 'Shop promise', 'unapp' ),
		'text' => _x( "Ordered before 2pm on a weekday, it leaves the same afternoon.", 'Shop promise', 'unapp' ),
	),
	array(
		'icon' => 'refresh',
		'title' => _x( "Sixty days to change your mind", 'Shop promise', 'unapp' ),
		'text' => _x( "Unworn, unwashed, and we pay the return postage.", 'Shop promise', 'unapp' ),
	),
	array(
		'icon' => 'shield',
		'title' => _x( "Made to last", 'Shop promise', 'unapp' ),
		'text' => _x( "Two-year guarantee on everything, repairs at cost after that.", 'Shop promise', 'unapp' ),
	),
	array(
		'icon' => 'heart',
		'title' => _x( "Made in small runs", 'Shop promise', 'unapp' ),
		'text' => _x( "Roughly two hundred of anything. When it is gone we make it again, or we do not.", 'Shop promise', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Why buy here', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'The boring promises that matter', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-4","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->
<div class="wp-block-group alignwide unapp-grid-4">
<?php foreach ( $unapp_shop_promises as $unapp_shop_promise ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:group {"style":{"border":{"radius":"14px"},"spacing":{"padding":{"top":"14px","bottom":"14px","left":"14px","right":"14px"}}},"backgroundColor":"primary","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group has-primary-background-color has-background" style="border-radius:14px;padding-top:14px;padding-right:14px;padding-bottom:14px;padding-left:14px">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"24px","height":"24px"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icons/' . $unapp_shop_promise['icon'] . '.svg' ) ); ?>" alt="" style="width:24px;height:24px;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_shop_promise['title']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo $unapp_shop_promise['text']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
