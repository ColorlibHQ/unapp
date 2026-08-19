<?php
/**
 * Title: Finance: how advice works
 * Slug: unapp/finance-process
 * Categories: unapp, unapp_finance, unapp_features, steps
 * Keywords: finance, adviser, process, steps, how it works, advice
 * Viewport Width: 1400
 * Description: The four stages of an advice relationship, numbered, with the reassurance that you can stop at any of them.
 *
 * @package Unapp
 */

$unapp_finance_steps = array(
	array(
		'title' => _x( "A conversation", 'Advice step', 'unapp' ),
		'text' => _x( "An hour on the phone or in the office, at our cost. You describe what you are worried about; we tell you honestly whether we can help.", 'Advice step', 'unapp' ),
	),
	array(
		'title' => _x( "A written plan", 'Advice step', 'unapp' ),
		'text' => _x( "Where you stand today, what you want, and the gap between the two — in a document your family could read without us in the room.", 'Advice step', 'unapp' ),
	),
	array(
		'title' => _x( "Putting it in place", 'Advice step', 'unapp' ),
		'text' => _x( "We do the paperwork, the transfers and the chasing. You sign things and otherwise get on with your life.", 'Advice step', 'unapp' ),
	),
	array(
		'title' => _x( "A review every year", 'Advice step', 'unapp' ),
		'text' => _x( "Markets move, tax rules change and so do you. We meet each year and adjust, or confirm that nothing needs adjusting.", 'Advice step', 'unapp' ),
	),
);
$unapp_finance_step_number = 0;
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'How it works', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Four steps, and you can stop after any of them', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Nothing is charged until you have seen the plan and agreed to it in writing.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-4","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->
<div class="wp-block-group alignwide unapp-grid-4">
<?php foreach ( $unapp_finance_steps as $unapp_finance_step ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo str_pad( (string) ++$unapp_finance_step_number, 2, '0', STR_PAD_LEFT ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_finance_step['title']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_finance_step['text']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
