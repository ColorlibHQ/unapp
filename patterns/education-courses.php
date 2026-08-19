<?php
/**
 * Title: Courses: the timetable
 * Slug: unapp/education-courses
 * Categories: unapp, unapp_education, unapp_content
 * Keywords: education, courses, classes, timetable, prices, list
 * Viewport Width: 1400
 * Description: Four courses with length, price and what you leave with.
 *
 * @package Unapp
 */

$unapp_courses = array(
	array(
		'name'   => _x( "Woodwork: the first joint", 'Course name', 'unapp' ),
		'length' => _x( '6 evenings', 'Course length', 'unapp' ),
		'price'  => _x( '£320', 'Course price', 'unapp' ),
		'text'   => _x( "Hand tools only. You leave with a stool you made and did not glue in a panic.", 'Course description', 'unapp' ),
	),
	array(
		'name'   => _x( "Letterpress", 'Course name', 'unapp' ),
		'length' => _x( '2 days', 'Course length', 'unapp' ),
		'price'  => _x( '£210', 'Course price', 'unapp' ),
		'text'   => _x( "Setting metal type, mixing ink and printing a run of fifty cards to take home.", 'Course description', 'unapp' ),
	),
	array(
		'name'   => _x( "Wheel-thrown ceramics", 'Course name', 'unapp' ),
		'length' => _x( '8 evenings', 'Course length', 'unapp' ),
		'price'  => _x( '£420', 'Course price', 'unapp' ),
		'text'   => _x( "Throwing, trimming and glazing. Everything you make gets fired.", 'Course description', 'unapp' ),
	),
	array(
		'name'   => _x( "Bookbinding", 'Course name', 'unapp' ),
		'length' => _x( '1 day', 'Course length', 'unapp' ),
		'price'  => _x( '£140', 'Course price', 'unapp' ),
		'text'   => _x( "Two sewn bindings in a day, in the good cloth rather than the practice stuff.", 'Course description', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'This term', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Four courses, and a waiting list for two of them', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Everything is capped at eight people, because that is how many benches there are.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<?php foreach ( $unapp_courses as $unapp_course ) : ?>
<!-- wp:columns {"verticalAlignment":"top","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top is-not-stacked-on-mobile">
<!-- wp:column {"verticalAlignment":"top","width":"62%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:62%;">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_course['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_course['text']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"top","width":"38%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:38%;">
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_course['length']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"large","style":{"typography":{"fontWeight":"700"}}} -->
<p class="has-large-font-size" style="font-weight:700;"><?php echo $unapp_course['price']; ?></p>
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
