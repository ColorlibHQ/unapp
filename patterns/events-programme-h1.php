<?php
/**
 * Title: Event: programme (opening a page)
 * Slug: unapp/events-programme-h1
 * Inserter: no
 * Categories: unapp, unapp_events, unapp_content
 * Viewport Width: 1400
 * Description: The same section with its heading as the page's h1, for the first section of a page.
 *
 * @package Unapp
 */

$unapp_programme = array(
	array(
		'when' => _x( "09:30", 'Programme item', 'unapp' ),
		'title' => _x( "Doors and coffee", 'Programme item', 'unapp' ),
		'text' => _x( "The good coffee, from the cart outside, until it runs out at eleven.", 'Programme item', 'unapp' ),
	),
	array(
		'when' => _x( "10:00", 'Programme item', 'unapp' ),
		'title' => _x( "Opening: the ten-year codebase", 'Programme item', 'unapp' ),
		'text' => _x( "What survives, what gets rewritten, and how to tell the difference early.", 'Programme item', 'unapp' ),
	),
	array(
		'when' => _x( "11:30", 'Programme item', 'unapp' ),
		'title' => _x( "Three short talks", 'Programme item', 'unapp' ),
		'text' => _x( "Twenty minutes each, on testing, on hiring, and on saying no to a roadmap.", 'Programme item', 'unapp' ),
	),
	array(
		'when' => _x( "14:00", 'Programme item', 'unapp' ),
		'title' => _x( "Workshops", 'Programme item', 'unapp' ),
		'text' => _x( "Four rooms, sign up on the day, capped at thirty each.", 'Programme item', 'unapp' ),
	),
	array(
		'when' => _x( "17:00", 'Programme item', 'unapp' ),
		'title' => _x( "The bar", 'Programme item', 'unapp' ),
		'text' => _x( "Which is, historically, where the useful conversations happen.", 'Programme item', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"},"anchor":"programme"} -->
<div class="wp-block-group alignfull is-style-section-soft" id="programme" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Day one', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"textAlign":"center","fontSize":"xx-large","style":{"typography":{"lineHeight":"1.2"}}} -->
<h1 class="wp-block-heading has-text-align-center has-xx-large-font-size" style="line-height:1.2;"><?php esc_html_e( 'The programme', 'unapp' ); ?></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Day two is the same shape with different people. The full grid goes out a month before.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<?php foreach ( $unapp_programme as $unapp_slot ) : ?>
<!-- wp:columns {"verticalAlignment":"top","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top is-not-stacked-on-mobile">
<!-- wp:column {"verticalAlignment":"top","width":"18%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:18%;">
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo esc_html( $unapp_slot['when'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"top","width":"82%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:82%;">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_slot['title'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_slot['text'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- wp:separator {"className":"is-style-wide","backgroundColor":"border"} -->
<hr class="wp-block-separator has-text-color has-border-color has-border-border-color has-alpha-channel-opacity has-border-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
