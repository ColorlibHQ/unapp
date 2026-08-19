<?php
/**
 * Title: Fitness: memberships
 * Slug: unapp/fitness-memberships
 * Categories: unapp, unapp_fitness, unapp_pricing, pricing
 * Keywords: fitness, gym, membership, pricing, plans, join
 * Viewport Width: 1400
 * Description: Three membership tiers with real gym pricing, a highlighted middle option and a concessions line.
 *
 * @package Unapp
 */

$unapp_fitness_plans = array(
	array(
		'name'     => _x( 'Off-peak', 'Membership name', 'unapp' ),
		'price'    => _x( '39', 'Membership price', 'unapp' ),
		'per'      => _x( 'a month', 'Membership period', 'unapp' ),
		'note'     => _x( 'Weekdays before 16:00 and all weekend.', 'Membership note', 'unapp' ),
		'cta'      => _x( 'Join off-peak', 'Membership button', 'unapp' ),
		'featured' => false,
		'features' => array( _x( 'Every off-peak class', 'Membership feature', 'unapp' ), _x( 'Open gym whenever we are open', 'Membership feature', 'unapp' ), _x( 'Programme reviewed each month', 'Membership feature', 'unapp' ) ),
	),
	array(
		'name'     => _x( 'Full', 'Membership name', 'unapp' ),
		'price'    => _x( '59', 'Membership price', 'unapp' ),
		'per'      => _x( 'a month', 'Membership period', 'unapp' ),
		'note'     => _x( 'Any class, any hour we are open.', 'Membership note', 'unapp' ),
		'cta'      => _x( 'Join full', 'Membership button', 'unapp' ),
		'featured' => true,
		'features' => array( _x( 'Every class on the timetable', 'Membership feature', 'unapp' ), _x( 'Open gym whenever we are open', 'Membership feature', 'unapp' ), _x( 'Programme reviewed each month', 'Membership feature', 'unapp' ), _x( 'Bring a friend once a month', 'Membership feature', 'unapp' ) ),
	),
	array(
		'name'     => _x( 'Coached', 'Membership name', 'unapp' ),
		'price'    => _x( '120', 'Membership price', 'unapp' ),
		'per'      => _x( 'a month', 'Membership period', 'unapp' ),
		'note'     => _x( 'Small-group coaching, four to a coach.', 'Membership note', 'unapp' ),
		'cta'      => _x( 'Talk to a coach', 'Membership button', 'unapp' ),
		'featured' => false,
		'features' => array( _x( 'Everything in Full', 'Membership feature', 'unapp' ), _x( 'Two coached sessions a week', 'Membership feature', 'unapp' ), _x( 'Written programme, updated fortnightly', 'Membership feature', 'unapp' ), _x( 'Video review of your lifts', 'Membership feature', 'unapp' ) ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Memberships', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Three ways to train here', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Monthly rolling, cancel with a month\'s notice, no joining fee and no twelve-month contract to sign.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_fitness_plans as $unapp_fitness_plan ) : ?>
<?php if ( $unapp_fitness_plan['featured'] ) : ?>
<!-- wp:group {"className":"is-style-elevated","style":{"border":{"radius":"18px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-elevated" style="border-radius:18px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40);">
<?php if ( $unapp_fitness_plan['featured'] ) : ?>
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"700","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:700;letter-spacing:0.06em;text-transform:uppercase;"><?php echo esc_html_x( 'Most members choose this', 'Highlighted membership badge', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<?php endif; ?>
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_fitness_plan['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_fitness_plan['note']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|8"}},"layout":{"type":"flex","orientation":"horizontal","verticalAlignment":"bottom"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"xxx-large","style":{"typography":{"fontWeight":"700","lineHeight":"1"}}} -->
<p class="has-xxx-large-font-size" style="font-weight:700;line-height:1;"><?php echo '£' . $unapp_fitness_plan['price']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_fitness_plan['per']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist">
<?php foreach ( $unapp_fitness_plan['features'] as $unapp_fitness_feature ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $unapp_fitness_feature ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="#join"><?php echo esc_html( $unapp_fitness_plan['cta'] ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<?php else : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"18px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:18px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40);">
<?php if ( $unapp_fitness_plan['featured'] ) : ?>
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"700","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:700;letter-spacing:0.06em;text-transform:uppercase;"><?php echo esc_html_x( 'Most members choose this', 'Highlighted membership badge', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<?php endif; ?>
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_fitness_plan['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_fitness_plan['note']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|8"}},"layout":{"type":"flex","orientation":"horizontal","verticalAlignment":"bottom"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"xxx-large","style":{"typography":{"fontWeight":"700","lineHeight":"1"}}} -->
<p class="has-xxx-large-font-size" style="font-weight:700;line-height:1;"><?php echo '£' . $unapp_fitness_plan['price']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_fitness_plan['per']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist">
<?php foreach ( $unapp_fitness_plan['features'] as $unapp_fitness_feature ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $unapp_fitness_feature ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="#join"><?php echo esc_html( $unapp_fitness_plan['cta'] ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<?php endif; ?>
<?php endforeach; ?>
</div>
<!-- /wp:group -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"small"} -->
<p class="has-text-align-center has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'Students, NHS staff and over-65s take 20% off any membership — just ask at the desk.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
