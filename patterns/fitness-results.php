<?php
/**
 * Title: Fitness: the studio in numbers
 * Slug: unapp/fitness-results
 * Categories: unapp, unapp_fitness, unapp_company, stats
 * Keywords: fitness, gym, stats, numbers, members, counter
 * Viewport Width: 1400
 * Description: Four counting numbers about the studio, each with a line explaining why it matters.
 *
 * @package Unapp
 */

$unapp_fitness_stats = array(
	array(
		'number' => '340',
		'label'  => _x( 'members', 'Studio statistic label', 'unapp' ),
		'note'   => _x( 'Most of whom had never touched a barbell before they walked in.', 'Studio statistic note', 'unapp' ),
	),
	array(
		'number' => '42',
		'label'  => _x( 'classes a week', 'Studio statistic label', 'unapp' ),
		'note'   => _x( 'Across two rooms, from 06:00 to 20:30.', 'Studio statistic note', 'unapp' ),
	),
	array(
		'number' => '12',
		'label'  => _x( 'people per class', 'Studio statistic label', 'unapp' ),
		'note'   => _x( 'The number we cap it at, permanently. It is the whole point.', 'Studio statistic note', 'unapp' ),
	),
	array(
		'number' => '7',
		'label'  => _x( 'coaches', 'Studio statistic label', 'unapp' ),
		'note'   => _x( 'All of them qualified, all of them still competing or still learning.', 'Studio statistic note', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'The studio in numbers', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Small on purpose', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-4","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->
<div class="wp-block-group alignwide unapp-grid-4">
<?php foreach ( $unapp_fitness_stats as $unapp_fitness_stat ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|8"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"unapp-count","fontSize":"xxx-large","style":{"typography":{"fontWeight":"700","lineHeight":"1"}}} -->
<p class="unapp-count has-xxx-large-font-size" style="font-weight:700;line-height:1;"><?php echo $unapp_fitness_stat['number']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
<p style="font-weight:600;"><?php echo $unapp_fitness_stat['label']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_fitness_stat['note']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
