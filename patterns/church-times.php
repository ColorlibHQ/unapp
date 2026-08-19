<?php
/**
 * Title: Church: service times
 * Slug: unapp/church-times
 * Categories: unapp, unapp_church, text
 * Keywords: church, service times, schedule, worship, sunday
 * Viewport Width: 1400
 * Description: Service times with a short description of each gathering.
 *
 * @package Unapp
 */

$unapp_services_times = array(
	array( 'when' => _x( 'Sunday 9:30', 'Service time', 'unapp' ), 'what' => _x( 'Traditional service', 'Service name', 'unapp' ), 'note' => _x( 'Hymns, choir and communion in the main hall.', 'Service note', 'unapp' ) ),
	array( 'when' => _x( 'Sunday 11:15', 'Service time', 'unapp' ), 'what' => _x( 'Contemporary service', 'Service name', 'unapp' ), 'note' => _x( 'Band-led worship with children\'s groups running alongside.', 'Service note', 'unapp' ) ),
	array( 'when' => _x( 'Wednesday 19:00', 'Service time', 'unapp' ), 'what' => _x( 'Midweek prayer', 'Service name', 'unapp' ), 'note' => _x( 'A quiet hour in the chapel. Come and go as you need.', 'Service note', 'unapp' ) ),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Service times', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'When we gather', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Come as you are. Every service is signed and the building is step-free.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<?php foreach ( $unapp_services_times as $unapp_time ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center">
<!-- wp:column {"verticalAlignment":"center","width":"28%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:28%;">
<!-- wp:paragraph {"textColor":"primary","fontFamily":"heading","fontSize":"large","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-primary-color has-text-color has-heading-font-family has-large-font-size" style="font-weight:600;"><?php echo esc_html( $unapp_time['when'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"72%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:72%;">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_time['what'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_time['note'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- wp:separator {"className":"is-style-wide","backgroundColor":"border"} -->
<hr class="wp-block-separator has-text-color has-border-color has-border-border-color has-alpha-channel-opacity has-border-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
