<?php
/**
 * Title: Features: sticky explanation
 * Slug: unapp/features-sticky
 * Categories: unapp, unapp_features, steps
 * Keywords: features, sticky, scroll, steps, side by side
 * Viewport Width: 1400
 * Description: The explanation sticks while the steps scroll past it. Falls back to a plain two-column layout where sticky is unsupported.
 *
 * @package Unapp
 */

$unapp_sticky_steps = array(
	array(
		'title' => _x( "Capture", 'Sticky step', 'unapp' ),
		'text' => _x( "Anything anyone says in passing goes in the same inbox, so it stops living in someone's head.", 'Sticky step', 'unapp' ),
	),
	array(
		'title' => _x( "Decide", 'Sticky step', 'unapp' ),
		'text' => _x( "Turn the noise into a short list with owners and dates. The rest is archived, not deleted.", 'Sticky step', 'unapp' ),
	),
	array(
		'title' => _x( "Ship", 'Sticky step', 'unapp' ),
		'text' => _x( "The plan updates itself as the work moves, and the people who need telling are told.", 'Sticky step', 'unapp' ),
	),
);
$unapp_sticky_number = 0;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top alignwide">
<!-- wp:column {"verticalAlignment":"top","width":"44%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:44%;">
<!-- wp:group {"className":"unapp-sticky","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group unapp-sticky">
<!-- wp:paragraph {"align":"left","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-left has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'How it works', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Three moves, and the second one is the hard one', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"large"} -->
<p class="has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Scroll — the explanation keeps up on its own.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"top","width":"56%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:56%;">
<?php foreach ( $unapp_sticky_steps as $unapp_sticky_step ) : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo str_pad( (string) ++$unapp_sticky_number, 2, '0', STR_PAD_LEFT ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_sticky_step['title']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo $unapp_sticky_step['text']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
