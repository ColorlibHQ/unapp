<?php
/**
 * Title: Pricing: monthly and yearly
 * Slug: unapp/pricing-toggle
 * Categories: unapp, unapp_pricing, pricing
 * Keywords: pricing, toggle, monthly, yearly, plans, switch
 * Viewport Width: 1400
 * Description: Three plans with a switch between monthly and yearly prices. Falls back to monthly with no JavaScript.
 *
 * @package Unapp
 */

$unapp_toggle_plans = array(
	array(
		'name'     => _x( 'Free', 'Plan name', 'unapp' ),
		'monthly'  => '0',
		'yearly'   => '0',
		'note'     => _x( 'For one person keeping track of one thing.', 'Plan note', 'unapp' ),
		'featured' => false,
		'features' => array( _x( 'Three projects', 'Plan feature', 'unapp' ), _x( 'Two weeks of history', 'Plan feature', 'unapp' ), _x( 'Community support', 'Plan feature', 'unapp' ) ),
	),
	array(
		'name'     => _x( 'Team', 'Plan name', 'unapp' ),
		'monthly'  => '12',
		'yearly'   => '10',
		'note'     => _x( 'For a team that has outgrown a group chat.', 'Plan note', 'unapp' ),
		'featured' => true,
		'features' => array( _x( 'Unlimited projects', 'Plan feature', 'unapp' ), _x( 'Full history', 'Plan feature', 'unapp' ), _x( 'Roles and permissions', 'Plan feature', 'unapp' ), _x( 'Priority support', 'Plan feature', 'unapp' ) ),
	),
	array(
		'name'     => _x( 'Company', 'Plan name', 'unapp' ),
		'monthly'  => '24',
		'yearly'   => '20',
		'note'     => _x( 'For several teams that have to agree with each other.', 'Plan note', 'unapp' ),
		'featured' => false,
		'features' => array( _x( 'Everything in Team', 'Plan feature', 'unapp' ), _x( 'Single sign-on', 'Plan feature', 'unapp' ), _x( 'Audit log', 'Plan feature', 'unapp' ), _x( 'A named contact', 'Plan feature', 'unapp' ) ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Pricing', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Two sizes of team, and one of you', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Yearly is two months cheaper. No card to look around.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"unapp-period is-style-outline","fontSize":"small"} -->
<div class="wp-block-button has-custom-font-size unapp-period is-style-outline has-small-font-size"><a class="wp-block-button__link has-small-font-size has-custom-font-size wp-element-button" href="#"><span class="unapp-period__to-yearly"><?php esc_html_e( 'Show yearly prices', 'unapp' ); ?></span><span class="unapp-period__to-monthly"><?php esc_html_e( 'Show monthly prices', 'unapp' ); ?></span></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_toggle_plans as $unapp_toggle_plan ) : ?>
<?php if ( $unapp_toggle_plan['featured'] ) : ?>
<!-- wp:group {"className":"is-style-elevated","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-elevated" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_toggle_plan['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_toggle_plan['note']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"horizontal","verticalAlignment":"bottom"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"unapp-price","fontSize":"xxx-large","style":{"typography":{"fontWeight":"700","lineHeight":"1"}}} -->
<p class="unapp-price has-xxx-large-font-size" style="font-weight:700;line-height:1;"><span class="unapp-price__monthly"><?php echo '$' . $unapp_toggle_plan['monthly']; ?></span><span class="unapp-price__yearly"><?php echo '$' . $unapp_toggle_plan['yearly']; ?></span></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html_x( 'per person, per month', 'Plan period', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist">
<?php foreach ( $unapp_toggle_plan['features'] as $unapp_toggle_feature ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $unapp_toggle_feature ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="#start"><?php esc_html_e( 'Start free', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<?php else : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_toggle_plan['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_toggle_plan['note']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"horizontal","verticalAlignment":"bottom"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"unapp-price","fontSize":"xxx-large","style":{"typography":{"fontWeight":"700","lineHeight":"1"}}} -->
<p class="unapp-price has-xxx-large-font-size" style="font-weight:700;line-height:1;"><span class="unapp-price__monthly"><?php echo '$' . $unapp_toggle_plan['monthly']; ?></span><span class="unapp-price__yearly"><?php echo '$' . $unapp_toggle_plan['yearly']; ?></span></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html_x( 'per person, per month', 'Plan period', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist">
<?php foreach ( $unapp_toggle_plan['features'] as $unapp_toggle_feature ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $unapp_toggle_feature ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="#start"><?php esc_html_e( 'Start free', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<?php endif; ?>
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
