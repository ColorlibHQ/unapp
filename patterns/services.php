<?php
/**
 * Title: Services grid
 * Slug: unapp/services
 * Categories: unapp, services, featured
 * Keywords: services, features, icons, grid, columns
 * Viewport Width: 1400
 * Description: Section intro followed by four icon cards in a responsive grid.
 *
 * @package Unapp
 */

$unapp_services = array(
	array(
		'icon'  => 'layers',
		'title' => _x( 'Real template creation', 'Service title', 'unapp' ),
		'text'  => _x( 'Start every project from proven templates and adapt them to your workflow in minutes.', 'Service description', 'unapp' ),
	),
	array(
		'icon'  => 'users',
		'title' => _x( 'Built for collaboration', 'Service title', 'unapp' ),
		'text'  => _x( 'Comment, assign and resolve work together, in real time, from any device.', 'Service description', 'unapp' ),
	),
	array(
		'icon'  => 'zap',
		'title' => _x( 'Fast by default', 'Service title', 'unapp' ),
		'text'  => _x( 'Instant search, offline mode and background sync keep your team moving.', 'Service description', 'unapp' ),
	),
	array(
		'icon'  => 'shield',
		'title' => _x( 'Secure at every layer', 'Service title', 'unapp' ),
		'text'  => _x( 'Encryption in transit and at rest, single sign-on and granular permissions.', 'Service description', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"heading"} -->
		<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase"><?php echo esc_html_x( 'Why Unapp', 'Section eyebrow label', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"textAlign":"center"} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Everything your team needs to move faster', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'From the first idea to the final release, Unapp keeps every project, person and file in sync.', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns alignwide">
		<?php foreach ( $unapp_services as $unapp_service ) : ?>
		<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"radius":"14px"},"spacing":{"padding":{"top":"14px","bottom":"14px","left":"14px","right":"14px"}}},"backgroundColor":"primary","layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group has-primary-background-color has-background" style="border-radius:14px;padding-top:14px;padding-right:14px;padding-bottom:14px;padding-left:14px">
				<!-- wp:image {"width":"28px","height":"28px","sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icons/' . $unapp_service['icon'] . '.svg' ) ); ?>" alt="" style="width:28px;height:28px"/></figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:group -->
			<!-- wp:heading {"level":3,"fontSize":"large"} -->
			<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_service['title'] ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"textColor":"muted"} -->
			<p class="has-muted-color has-text-color"><?php echo esc_html( $unapp_service['text'] ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
