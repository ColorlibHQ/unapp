<?php
/**
 * Title: Portfolio: how a project runs
 * Slug: unapp/portfolio-process
 * Categories: unapp, unapp_portfolio, unapp_features, steps
 * Keywords: portfolio, process, project, stages, timeline, freelance
 * Viewport Width: 1400
 * Description: Three stages of a design engagement with the week numbers attached, so a client knows what they are buying.
 *
 * @package Unapp
 */

$unapp_portfolio_steps = array(
	array(
		'when' => _x( "Week one", 'Project stage', 'unapp' ),
		'title' => _x( "Reading", 'Project stage', 'unapp' ),
		'text' => _x( "Everything you have already written down, plus conversations with the people who will have to live with the result.", 'Project stage', 'unapp' ),
	),
	array(
		'when' => _x( "Weeks two to four", 'Project stage', 'unapp' ),
		'title' => _x( "Drawing", 'Project stage', 'unapp' ),
		'text' => _x( "Two directions, shown early and shown rough. We kill one together before either gets expensive.", 'Project stage', 'unapp' ),
	),
	array(
		'when' => _x( "Weeks five to eight", 'Project stage', 'unapp' ),
		'title' => _x( "Building", 'Project stage', 'unapp' ),
		'text' => _x( "Type, colour, layout and every state of every component, in a file your developers can actually open.", 'Project stage', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'How a project runs', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Eight weeks, three stages, no surprises', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Every project I take on runs the same way, and you see work in the first fortnight.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_portfolio_steps as $unapp_portfolio_step ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_portfolio_step['when']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_portfolio_step['title']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo $unapp_portfolio_step['text']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
