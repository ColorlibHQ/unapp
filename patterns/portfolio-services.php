<?php
/**
 * Title: Portfolio: services and rates
 * Slug: unapp/portfolio-services
 * Categories: unapp, unapp_portfolio, portfolio, featured
 * Keywords: portfolio, services, rates, pricing, freelance
 * Viewport Width: 1400
 * Description: Three services with an indicative price, separated by hairline rules.
 *
 * @package Unapp
 */

$unapp_offers = array(
	array( 'title' => _x( 'Identity', 'Service title', 'unapp' ), 'price' => _x( 'from £6,000', 'Service price', 'unapp' ), 'text' => _x( 'Naming, marks, type and a system your team can actually use.', 'Service description', 'unapp' ) ),
	array( 'title' => _x( 'Website', 'Service title', 'unapp' ), 'price' => _x( 'from £9,000', 'Service price', 'unapp' ), 'text' => _x( 'Design and build, from the first sketch to the day it ships.', 'Service description', 'unapp' ) ),
	array( 'title' => _x( 'Art direction', 'Service title', 'unapp' ), 'price' => _x( 'day rate', 'Service price', 'unapp' ), 'text' => _x( 'Photography, campaigns and the taste to say no to the wrong idea.', 'Service description', 'unapp' ) ),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Services', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'How we can work together', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:columns {"align":"wide","className":"is-style-divided","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns alignwide is-style-divided">
<?php foreach ( $unapp_offers as $unapp_offer ) : ?>
<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_offer['title'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;"><?php echo esc_html( $unapp_offer['price'] ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html( $unapp_offer['text'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
