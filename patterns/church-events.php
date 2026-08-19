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
		'when'  => _x( 'Sat 14 Sep', 'Church event date', 'unapp' ),
		'title' => _x( 'Community lunch', 'Church event title', 'unapp' ),
		'text'  => _x( "Everyone eats, nobody pays. Twelve o'clock in the hall.", 'Church event description', 'unapp' ),
	),
	array(
		'when'  => _x( 'Thu 26 Sep', 'Church event date', 'unapp' ),
		'title' => _x( 'Job club', 'Church event title', 'unapp' ),
		'text'  => _x( "CVs, applications and interview practice with people who hire for a living.", 'Church event description', 'unapp' ),
	),
	array(
		'when'  => _x( 'Sun 6 Oct', 'Church event date', 'unapp' ),
		'title' => _x( 'Harvest service', 'Church event title', 'unapp' ),
		'text'  => _x( "Bring tinned food if you can; the whole lot goes to the foodbank on Mill Lane.", 'Church event description', 'unapp' ),
	),
	array(
		'when'  => _x( 'Sun 22 Dec', 'Church event date', 'unapp' ),
		'title' => _x( 'Carols by candlelight', 'Church event title', 'unapp' ),
		'text'  => _x( "The one service a year that fills the balcony. Come early.", 'Church event description', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);">
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
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"800px"}} -->
<div class="wp-block-group">
<?php foreach ( $unapp_church_events as $unapp_church_event ) : ?>
<!-- wp:columns {"verticalAlignment":"top","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top is-not-stacked-on-mobile">
<!-- wp:column {"verticalAlignment":"top","width":"24%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:24%;">
<!-- wp:paragraph {"textColor":"primary","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-primary-color has-text-color" style="font-weight:600;"><?php echo $unapp_church_event['when']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"top","width":"76%","style":{"spacing":{"blockGap":"var:preset|spacing|6"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:76%;">
<!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php echo $unapp_church_event['title']; ?></h3>
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
