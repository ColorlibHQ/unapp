<?php
/**
 * Title: Property: fees
 * Slug: unapp/realestate-fees
 * Categories: unapp, unapp_realestate, unapp_pricing, pricing
 * Keywords: real estate, fees, commission, lettings, charges
 * Viewport Width: 1400
 * Description: Three published agency fees, so a seller knows the cost before the valuation.
 *
 * @package Unapp
 */

$unapp_estate_fees = array(
	array(
		'label' => _x( "Sole agency", 'Agency fee', 'unapp' ),
		'amount' => _x( "1.2%", 'Agency fee', 'unapp' ),
		'text' => _x( "Plus VAT, payable on completion and nothing before it.", 'Agency fee', 'unapp' ),
	),
	array(
		'label' => _x( "Photography and floor plan", 'Agency fee', 'unapp' ),
		'amount' => _x( "Included", 'Agency fee', 'unapp' ),
		'text' => _x( "Taken by a photographer, not by whoever is free that morning.", 'Agency fee', 'unapp' ),
	),
	array(
		'label' => _x( "Lettings management", 'Agency fee', 'unapp' ),
		'amount' => _x( "8%", 'Agency fee', 'unapp' ),
		'text' => _x( "Of the rent, monthly. Tenant find only is one month's rent.", 'Agency fee', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'What we charge', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Our fees, in public', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_estate_fees as $unapp_estate_fee ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"textColor":"muted","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-muted-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_estate_fee['label']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"xxx-large","style":{"typography":{"fontWeight":"700","lineHeight":"1"}}} -->
<p class="has-xxx-large-font-size" style="font-weight:700;line-height:1;"><?php echo $unapp_estate_fee['amount']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_estate_fee['text']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
