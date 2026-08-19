<?php
/**
 * Title: Finance: services
 * Slug: unapp/finance-services
 * Categories: unapp, unapp_finance, featured, services
 * Keywords: finance, services, advice, planning, grid
 * Viewport Width: 1400
 * Description: Six advisory services as icon cards in a three-column grid.
 *
 * @package Unapp
 */

$unapp_offer_list = array(
	array( 'icon' => 'target', 'title' => _x( 'Retirement planning', 'Finance service', 'unapp' ), 'text' => _x( 'What you can spend, when you can stop, and what happens if markets misbehave.', 'Finance service description', 'unapp' ) ),
	array( 'icon' => 'trending-up', 'title' => _x( 'Investment management', 'Finance service', 'unapp' ), 'text' => _x( 'Low-cost, globally diversified portfolios rebalanced on a schedule, not a hunch.', 'Finance service description', 'unapp' ) ),
	array( 'icon' => 'shield', 'title' => _x( 'Protection', 'Finance service', 'unapp' ), 'text' => _x( 'The cover you actually need, and an honest word about the cover you do not.', 'Finance service description', 'unapp' ) ),
	array( 'icon' => 'briefcase', 'title' => _x( 'Business owners', 'Finance service', 'unapp' ), 'text' => _x( 'Extracting profit, funding an exit and keeping the tax bill defensible.', 'Finance service description', 'unapp' ) ),
	array( 'icon' => 'key', 'title' => _x( 'Estate planning', 'Finance service', 'unapp' ), 'text' => _x( 'Wills, trusts and the conversations families keep postponing.', 'Finance service description', 'unapp' ) ),
	array( 'icon' => 'pie-chart', 'title' => _x( 'Cashflow modelling', 'Finance service', 'unapp' ), 'text' => _x( 'One picture of your money for the next thirty years, updated every year.', 'Finance service description', 'unapp' ) ),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'What we do', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Six ways we help', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Most clients start with one and end up with a plan that covers all six.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_offer_list as $unapp_service_item ) : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:group {"style":{"border":{"radius":"14px"},"spacing":{"padding":{"top":"12px","bottom":"12px","left":"12px","right":"12px"}}},"backgroundColor":"primary","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group has-primary-background-color has-background" style="border-radius:14px;padding-top:12px;padding-right:12px;padding-bottom:12px;padding-left:12px">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"24px","height":"24px"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icons/' . $unapp_service_item['icon'] . '.svg' ) ); ?>" alt="" style="width:24px;height:24px;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_service_item['title'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_service_item['text'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
