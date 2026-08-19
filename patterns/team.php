<?php
/**
 * Title: Team members
 * Slug: unapp/team
 * Categories: unapp, team
 * Keywords: team, people, members, staff, avatars
 * Viewport Width: 1400
 * Description: Four team members with round portraits, roles and social links.
 *
 * @package Unapp
 */

$unapp_members = array(
	array(
		'name' => _x( 'Dorothy Murphy', 'Team member name', 'unapp' ),
		'role' => _x( 'Product Designer', 'Team member role', 'unapp' ),
	),
	array(
		'name' => _x( 'James Carter', 'Team member name', 'unapp' ),
		'role' => _x( 'Lead Engineer', 'Team member role', 'unapp' ),
	),
	array(
		'name' => _x( 'Aisha Rahman', 'Team member name', 'unapp' ),
		'role' => _x( 'Head of Growth', 'Team member role', 'unapp' ),
	),
	array(
		'name' => _x( 'Leo Novak', 'Team member name', 'unapp' ),
		'role' => _x( 'Customer Success', 'Team member role', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"heading"} -->
		<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase"><?php echo esc_html_x( 'Our team', 'Section eyebrow label', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"textAlign":"center"} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Meet the people behind Unapp', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'A small, senior team that has shipped products used by millions — and answers support tickets personally.', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns alignwide">
		<?php foreach ( $unapp_members as $unapp_index => $unapp_member ) : ?>
		<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
		<div class="wp-block-column">
			<!-- wp:image {"width":"96px","height":"96px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"999px"}}} -->
			<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/avatars/avatar-' . ( $unapp_index + 1 ) . '.svg' ) ); ?>" alt="<?php echo esc_attr( $unapp_member['name'] ); ?>" style="border-radius:999px;width:96px;height:96px"/></figure>
			<!-- /wp:image -->
			<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
			<h3 class="wp-block-heading has-text-align-center has-large-font-size"><?php echo esc_html( $unapp_member['name'] ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"small"} -->
			<p class="has-text-align-center has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_member['role'] ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:social-links {"iconColor":"muted","iconColorValue":"#6b7280","size":"has-small-icon-size","className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
			<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only">
				<!-- wp:social-link {"url":"https://x.com","service":"x"} /-->
				<!-- wp:social-link {"url":"https://linkedin.com","service":"linkedin"} /-->
				<!-- wp:social-link {"url":"https://dribbble.com","service":"dribbble"} /-->
			</ul>
			<!-- /wp:social-links -->
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
