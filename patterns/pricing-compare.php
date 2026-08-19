<?php
/**
 * Title: Pricing: comparison table
 * Slug: unapp/pricing-compare
 * Categories: unapp, unapp_pricing, featured
 * Keywords: pricing, compare, table, plans, features
 * Viewport Width: 1400
 * Description: A feature comparison table across three plans, with calls to action beneath.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Compare', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Everything, side by side', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'The full breakdown of what each plan includes.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide">
<!-- wp:table {"className":"is-style-compare"} -->
<figure class="wp-block-table is-style-compare"><table><thead><tr><th><?php esc_html_e( 'Feature', 'unapp' ); ?></th><th><?php esc_html_e( 'Free', 'unapp' ); ?></th><th><?php esc_html_e( 'Team', 'unapp' ); ?></th><th><?php esc_html_e( 'Business', 'unapp' ); ?></th></tr></thead><tbody><tr><td><?php esc_html_e( 'Projects', 'unapp' ); ?></td><td><?php esc_html_e( '3', 'unapp' ); ?></td><td><?php esc_html_e( 'Unlimited', 'unapp' ); ?></td><td><?php esc_html_e( 'Unlimited', 'unapp' ); ?></td></tr><tr><td><?php esc_html_e( 'Storage', 'unapp' ); ?></td><td><?php esc_html_e( '1 GB', 'unapp' ); ?></td><td><?php esc_html_e( '100 GB', 'unapp' ); ?></td><td><?php esc_html_e( '1 TB', 'unapp' ); ?></td></tr><tr><td><?php esc_html_e( 'Team members', 'unapp' ); ?></td><td><?php esc_html_e( '2', 'unapp' ); ?></td><td><?php esc_html_e( 'Up to 50', 'unapp' ); ?></td><td><?php esc_html_e( 'Unlimited', 'unapp' ); ?></td></tr><tr><td><?php esc_html_e( 'Reporting', 'unapp' ); ?></td><td><?php esc_html_e( 'Basic', 'unapp' ); ?></td><td><?php esc_html_e( 'Advanced', 'unapp' ); ?></td><td><?php esc_html_e( 'Advanced + API', 'unapp' ); ?></td></tr><tr><td><?php esc_html_e( 'Single sign-on', 'unapp' ); ?></td><td><?php esc_html_e( '—', 'unapp' ); ?></td><td><?php esc_html_e( 'Yes', 'unapp' ); ?></td><td><?php esc_html_e( 'Yes', 'unapp' ); ?></td></tr><tr><td><?php esc_html_e( 'Support', 'unapp' ); ?></td><td><?php esc_html_e( 'Community', 'unapp' ); ?></td><td><?php esc_html_e( 'Priority', 'unapp' ); ?></td><td><?php esc_html_e( 'Dedicated manager', 'unapp' ); ?></td></tr></tbody></table></figure>
<!-- /wp:table -->
</div>
<!-- /wp:group -->
<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Start free trial', 'unapp' ); ?></a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Talk to sales', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
