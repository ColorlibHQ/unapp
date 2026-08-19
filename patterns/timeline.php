<?php
/**
 * Title: Timeline / milestones
 * Slug: unapp/timeline
 * Categories: unapp, unapp_company, about, text
 * Keywords: timeline, history, milestones, about, story, years
 * Viewport Width: 1400
 * Description: A dated list of milestones with a rule between each entry.
 *
 * @package Unapp
 */

$unapp_milestones = array(
	array( 'year' => '2021', 'title' => _x( 'Two people and a whiteboard', 'Milestone title', 'unapp' ), 'text' => _x( 'Unapp started as an internal tool for our own studio. It fitted on one screen and did three things well.', 'Milestone description', 'unapp' ) ),
	array( 'year' => '2023', 'title' => _x( 'Opened to everyone', 'Milestone title', 'unapp' ), 'text' => _x( 'The public beta filled in a week. We spent the year saying no to features and yes to speed.', 'Milestone description', 'unapp' ) ),
	array( 'year' => '2025', 'title' => _x( '10,000 teams', 'Milestone title', 'unapp' ), 'text' => _x( 'Teams in 40 countries now plan their week in Unapp, from two-person studios to listed companies.', 'Milestone description', 'unapp' ) ),
	array( 'year' => '2026', 'title' => _x( 'Built for the long run', 'Milestone title', 'unapp' ), 'text' => _x( 'Profitable, independent and still shipping every Friday.', 'Milestone description', 'unapp' ) ),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Our story', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Five years of shipping on Fridays', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'A short history of how Unapp got here.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group">
<?php foreach ( $unapp_milestones as $unapp_milestone ) : ?>
<!-- wp:group {"className":"unapp-timeline-row","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"default"}} -->
<div class="wp-block-group unapp-timeline-row">
<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns">
<!-- wp:column {"width":"18%"} -->
<div class="wp-block-column" style="flex-basis:18%;">
<!-- wp:paragraph {"textColor":"primary","fontFamily":"heading","fontSize":"x-large","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-primary-color has-text-color has-heading-font-family has-x-large-font-size" style="font-weight:600;"><?php echo esc_html( $unapp_milestone['year'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"82%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column" style="flex-basis:82%;">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_milestone['title'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html( $unapp_milestone['text'] ); ?></p>
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
