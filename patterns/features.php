<?php
/**
 * Title: Features around a phone
 * Slug: unapp/features
 * Categories: unapp, featured, services
 * Keywords: features, mobile, app, phone, icons
 * Viewport Width: 1400
 * Description: Two columns of icon features flanking a mobile app screenshot in a device frame.
 *
 * @package Unapp
 */

$unapp_features_left = array(
	array(
		'icon'  => 'smartphone',
		'title' => _x( 'Native mobile apps', 'Feature title', 'unapp' ),
		'text'  => _x( 'iOS and Android apps that work offline and sync the moment you are back online.', 'Feature description', 'unapp' ),
	),
	array(
		'icon'  => 'cloud',
		'title' => _x( 'Cloud sync', 'Feature title', 'unapp' ),
		'text'  => _x( 'Every change is saved automatically and mirrored across all of your devices in seconds.', 'Feature description', 'unapp' ),
	),
);
$unapp_features_right = array(
	array(
		'icon'  => 'lock',
		'title' => _x( 'Private by design', 'Feature title', 'unapp' ),
		'text'  => _x( 'Granular sharing controls, two-factor authentication and audit logs on every plan.', 'Feature description', 'unapp' ),
	),
	array(
		'icon'  => 'bar-chart',
		'title' => _x( 'Insightful reports', 'Feature title', 'unapp' ),
		'text'  => _x( 'Understand velocity, workload and progress with analytics built right in.', 'Feature description', 'unapp' ),
	),
);

/**
 * Print one icon + text feature row.
 *
 * @param array $feature Feature definition (icon, title, text).
 */
$unapp_feature_row = static function ( $feature ) {
	?>
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
	<div class="wp-block-group">
		<!-- wp:group {"style":{"border":{"radius":"14px"},"spacing":{"padding":{"top":"12px","bottom":"12px","left":"12px","right":"12px"}}},"backgroundColor":"secondary","layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group has-secondary-background-color has-background" style="border-radius:14px;padding-top:12px;padding-right:12px;padding-bottom:12px;padding-left:12px">
			<!-- wp:image {"width":"24px","height":"24px","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icons/' . $feature['icon'] . '.svg' ) ); ?>" alt="" style="width:24px;height:24px"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":3,"fontSize":"large"} -->
			<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $feature['title'] ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"textColor":"muted"} -->
			<p class="has-muted-color has-text-color"><?php echo esc_html( $feature['text'] ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
	<?php
};
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"heading"} -->
		<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase"><?php echo esc_html_x( 'Features', 'Section eyebrow label', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"textAlign":"center"} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Powerful features, thoughtfully designed', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Everything you need to plan, track and deliver — on desktop and in your pocket.', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center","width":"36%","style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:36%">
			<?php
			foreach ( $unapp_features_left as $unapp_feature ) {
				$unapp_feature_row( $unapp_feature );
			}
			?>
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"28%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:28%">
			<!-- wp:image {"align":"center","width":"260px","sizeSlug":"full","linkDestination":"none","className":"is-style-device"} -->
			<figure class="wp-block-image aligncenter size-full is-resized is-style-device"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/app-mobile-1.avif' ) ); ?>" alt="<?php esc_attr_e( 'Unapp mobile app showing a body-weight goal chart', 'unapp' ); ?>" style="width:260px"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"36%","style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:36%">
			<?php
			foreach ( $unapp_features_right as $unapp_feature ) {
				$unapp_feature_row( $unapp_feature );
			}
			?>
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
