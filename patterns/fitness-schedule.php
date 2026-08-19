<?php
/**
 * Title: Fitness: class timetable
 * Slug: unapp/fitness-schedule
 * Categories: unapp, unapp_fitness, text
 * Keywords: fitness, timetable, schedule, classes, gym
 * Viewport Width: 1400
 * Description: A weekly class timetable using the Comparison table style.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Timetable', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'This week at the studio', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Twelve people per class. Book through the app up to seven days ahead.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide">
<!-- wp:table {"className":"is-style-compare"} -->
<figure class="wp-block-table is-style-compare"><table><thead><tr><th><?php esc_html_e( 'Day', 'unapp' ); ?></th><th><?php esc_html_e( 'Morning', 'unapp' ); ?></th><th><?php esc_html_e( 'Evening', 'unapp' ); ?></th></tr></thead><tbody><tr><td><?php esc_html_e( 'Monday', 'unapp' ); ?></td><td><?php esc_html_e( '06:30 · Strength', 'unapp' ); ?></td><td><?php esc_html_e( '18:30 · Conditioning', 'unapp' ); ?></td></tr><tr><td><?php esc_html_e( 'Tuesday', 'unapp' ); ?></td><td><?php esc_html_e( '07:00 · Mobility', 'unapp' ); ?></td><td><?php esc_html_e( '19:00 · Strength', 'unapp' ); ?></td></tr><tr><td><?php esc_html_e( 'Wednesday', 'unapp' ); ?></td><td><?php esc_html_e( '06:30 · Conditioning', 'unapp' ); ?></td><td><?php esc_html_e( '18:30 · Open gym', 'unapp' ); ?></td></tr><tr><td><?php esc_html_e( 'Thursday', 'unapp' ); ?></td><td><?php esc_html_e( '07:00 · Strength', 'unapp' ); ?></td><td><?php esc_html_e( '19:00 · Intervals', 'unapp' ); ?></td></tr><tr><td><?php esc_html_e( 'Friday', 'unapp' ); ?></td><td><?php esc_html_e( '06:30 · Strength', 'unapp' ); ?></td><td><?php esc_html_e( '17:30 · Team workout', 'unapp' ); ?></td></tr><tr><td><?php esc_html_e( 'Saturday', 'unapp' ); ?></td><td><?php esc_html_e( '08:00 · Long session', 'unapp' ); ?></td><td><?php esc_html_e( '10:00 · Beginners', 'unapp' ); ?></td></tr></tbody></table></figure>
<!-- /wp:table -->
</div>
<!-- /wp:group -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Book a class', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
