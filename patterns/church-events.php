<?php
/**
 * Title: Church: upcoming events
 * Slug: unapp/church-events
 * Categories: unapp, unapp_church, unapp_content
 * Keywords: church, events, diary, calendar, upcoming
 * Viewport Width: 1400
 * Description: A dated list of what is on: community lunch, job club, harvest and carols.
 *
 * @package Unapp
 */

$unapp_church_events = array(
	array(
		'when' => _x( "Sat 14 Sep", 'Church event', 'unapp' ),
		'title' => _x( "Community lunch", 'Church event', 'unapp' ),
		'text' => _x( "Everyone eats, nobody pays. Twelve o'clock in the hall.", 'Church event', 'unapp' ),
	),
	array(
		'when' => _x( "Thu 26 Sep", 'Church event', 'unapp' ),
		'title' => _x( "Job club", 'Church event', 'unapp' ),
		'text' => _x( "CVs, applications and interview practice with people who hire for a living.", 'Church event', 'unapp' ),
	),
	array(
		'when' => _x( "Sun 6 Oct", 'Church event', 'unapp' ),
		'title' => _x( "Harvest service", 'Church event', 'unapp' ),
		'text' => _x( "Bring tinned food if you can; the whole lot goes to the foodbank on Mill Lane.", 'Church event', 'unapp' ),
	),
	array(
		'when' => _x( "Sun 22 Dec", 'Church event', 'unapp' ),
		'title' => _x( "Carols by candlelight", 'Church event', 'unapp' ),
		'text' => _x( "The one service a year that fills the balcony. Come early.", 'Church event', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Diary', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Coming up', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<?php foreach ( $unapp_church_events as $unapp_church_event ) : ?>
<!-- wp:columns {"verticalAlignment":"top","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top is-not-stacked-on-mobile">
<!-- wp:column {"verticalAlignment":"top","width":"26%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:26%;">
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_church_event['when']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"top","width":"74%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:74%;">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_church_event['title']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_church_event['text']; ?></p>
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
