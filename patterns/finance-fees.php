<?php
/**
 * Title: Finance: fees
 * Slug: unapp/finance-fees
 * Categories: unapp, unapp_finance, unapp_pricing, pricing
 * Keywords: finance, fees, cost, pricing, charges, transparent
 * Viewport Width: 1400
 * Description: Three fee cards stating the initial charge, the ongoing percentage and the fact that nothing else is charged.
 *
 * @package Unapp
 */

$unapp_finance_fees = array(
	array(
		'label' => _x( "Initial advice", 'Fee', 'unapp' ),
		'amount' => _x( "£1,800", 'Fee', 'unapp' ),
		'text' => _x( "Fixed, quoted before we start and payable only once you have the written plan in your hands.", 'Fee', 'unapp' ),
	),
	array(
		'label' => _x( "Ongoing advice", 'Fee', 'unapp' ),
		'amount' => _x( "0.65%", 'Fee', 'unapp' ),
		'text' => _x( "A year on the money we look after, billed monthly. It covers the annual review and everything in between.", 'Fee', 'unapp' ),
	),
	array(
		'label' => _x( "Nothing else", 'Fee', 'unapp' ),
		'amount' => _x( "£0", 'Fee', 'unapp' ),
		'text' => _x( "No commission, no product kickbacks, and no charge for phoning us with a question.", 'Fee', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'What it costs', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Our fees, on the website, like a plumber', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'You should not have to sit through a meeting to find out what advice costs.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_finance_fees as $unapp_finance_fee ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"textColor":"muted","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-muted-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_finance_fee['label']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"xxx-large","style":{"typography":{"fontWeight":"700","lineHeight":"1"}}} -->
<p class="has-xxx-large-font-size" style="font-weight:700;line-height:1;"><?php echo $unapp_finance_fee['amount']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_finance_fee['text']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
