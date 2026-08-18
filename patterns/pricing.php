<?php
/**
 * Title: Pricing table
 * Slug: unapp/pricing
 * Categories: unapp, featured, columns
 * Keywords: pricing, plans, table, price, subscription
 * Viewport Width: 1400
 * Description: Three pricing cards with a highlighted middle plan, feature checklists and call-to-action buttons.
 *
 * @package Unapp
 */

$unapp_plans = array(
	array(
		'name'     => _x( 'Starter', 'Pricing plan name', 'unapp' ),
		'tagline'  => _x( 'For individuals getting started', 'Pricing plan tagline', 'unapp' ),
		'price'    => _x( '$0', 'Pricing plan price', 'unapp' ),
		'period'   => _x( '/ month', 'Pricing plan billing period', 'unapp' ),
		'features' => array(
			_x( '3 projects', 'Pricing plan feature', 'unapp' ),
			_x( '1 GB storage', 'Pricing plan feature', 'unapp' ),
			_x( 'Community support', 'Pricing plan feature', 'unapp' ),
		),
		'button'   => _x( 'Get started', 'Pricing plan button', 'unapp' ),
		'featured' => false,
	),
	array(
		'name'     => _x( 'Pro', 'Pricing plan name', 'unapp' ),
		'tagline'  => _x( 'For growing teams', 'Pricing plan tagline', 'unapp' ),
		'price'    => _x( '$12', 'Pricing plan price', 'unapp' ),
		'period'   => _x( '/ user / month', 'Pricing plan billing period', 'unapp' ),
		'features' => array(
			_x( 'Unlimited projects', 'Pricing plan feature', 'unapp' ),
			_x( '100 GB storage', 'Pricing plan feature', 'unapp' ),
			_x( 'Priority support', 'Pricing plan feature', 'unapp' ),
			_x( 'Advanced reports', 'Pricing plan feature', 'unapp' ),
		),
		'button'   => _x( 'Start free trial', 'Pricing plan button', 'unapp' ),
		'featured' => true,
	),
	array(
		'name'     => _x( 'Business', 'Pricing plan name', 'unapp' ),
		'tagline'  => _x( 'For organisations at scale', 'Pricing plan tagline', 'unapp' ),
		'price'    => _x( '$29', 'Pricing plan price', 'unapp' ),
		'period'   => _x( '/ user / month', 'Pricing plan billing period', 'unapp' ),
		'features' => array(
			_x( 'Everything in Pro', 'Pricing plan feature', 'unapp' ),
			_x( 'SSO and audit logs', 'Pricing plan feature', 'unapp' ),
			_x( 'Dedicated success manager', 'Pricing plan feature', 'unapp' ),
		),
		'button'   => _x( 'Contact sales', 'Pricing plan button', 'unapp' ),
		'featured' => false,
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"heading"} -->
		<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase"><?php echo esc_html_x( 'Pricing', 'Section eyebrow label', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"textAlign":"center"} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Simple, transparent pricing', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Start free and upgrade when your team grows. No hidden fees, cancel any time.', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-columns alignwide">
		<?php foreach ( $unapp_plans as $unapp_plan ) : ?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<?php if ( $unapp_plan['featured'] ) : ?>
			<!-- wp:group {"className":"is-style-section-gradient","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"},"shadow":"var:preset|shadow|glow"},"layout":{"type":"default"}} -->
			<div class="wp-block-group is-style-section-gradient" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);box-shadow:var(--wp--preset--shadow--glow)">
				<!-- wp:heading {"level":3,"fontSize":"large"} -->
				<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_plan['name'] ); ?></h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-small-font-size"><?php echo esc_html( $unapp_plan['tagline'] ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","lineHeight":"1"}},"fontSize":"xx-large","fontFamily":"heading"} -->
					<p class="has-heading-font-family has-xx-large-font-size" style="font-weight:600;line-height:1"><?php echo esc_html( $unapp_plan['price'] ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"small"} -->
					<p class="has-small-font-size"><?php echo esc_html( $unapp_plan['period'] ); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
				<!-- wp:list {"className":"is-style-checklist"} -->
				<ul class="wp-block-list is-style-checklist">
					<?php foreach ( $unapp_plan['features'] as $unapp_feature ) : ?>
					<!-- wp:list-item -->
					<li><?php echo esc_html( $unapp_feature ); ?></li>
					<!-- /wp:list-item -->
					<?php endforeach; ?>
				</ul>
				<!-- /wp:list -->
				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
				<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
					<!-- wp:button {"backgroundColor":"base","textColor":"primary","width":100} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-primary-color has-base-background-color has-text-color has-background wp-element-button" href="#"><?php echo esc_html( $unapp_plan['button'] ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
			<?php else : ?>
			<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:heading {"level":3,"fontSize":"large"} -->
				<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_plan['name'] ); ?></h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
				<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_plan['tagline'] ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","lineHeight":"1"}},"fontSize":"xx-large","fontFamily":"heading"} -->
					<p class="has-heading-font-family has-xx-large-font-size" style="font-weight:600;line-height:1"><?php echo esc_html( $unapp_plan['price'] ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
					<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_plan['period'] ); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
				<!-- wp:list {"className":"is-style-checklist"} -->
				<ul class="wp-block-list is-style-checklist">
					<?php foreach ( $unapp_plan['features'] as $unapp_feature ) : ?>
					<!-- wp:list-item -->
					<li><?php echo esc_html( $unapp_feature ); ?></li>
					<!-- /wp:list-item -->
					<?php endforeach; ?>
				</ul>
				<!-- /wp:list -->
				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
				<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
					<!-- wp:button {"width":100,"className":"is-style-outline"} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html( $unapp_plan['button'] ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
			<?php endif; ?>
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
