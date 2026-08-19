<?php
/**
 * Title: Footer
 * Slug: unapp/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: Dark four-column footer with brand, links, latest posts, contact details and copyright bar.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|60"},"elements":{"link":{"color":{"text":"var:preset|color|base"},":hover":{"color":{"text":"var:preset|color|secondary"}}},"heading":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"dark","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-color has-dark-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--40)">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column {"width":"34%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
		<div class="wp-block-column" style="flex-basis:34%">
			<!-- wp:site-title {"level":0,"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}}} /-->
			<!-- wp:paragraph {"style":{"color":{"text":"rgba(255,255,255,0.75)"}}} -->
			<p class="has-text-color" style="color:rgba(255,255,255,0.75)"><?php esc_html_e( 'One calm workspace for planning, files and conversations. Take on your biggest projects and goals with Unapp.', 'unapp' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:social-links {"iconColor":"base","iconColorValue":"#ffffff","className":"is-style-logos-only","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
			<ul class="wp-block-social-links has-icon-color is-style-logos-only">
				<!-- wp:social-link {"url":"https://facebook.com","service":"facebook"} /-->
				<!-- wp:social-link {"url":"https://x.com","service":"x"} /-->
				<!-- wp:social-link {"url":"https://linkedin.com","service":"linkedin"} /-->
				<!-- wp:social-link {"url":"https://youtube.com","service":"youtube"} /-->
			</ul>
			<!-- /wp:social-links -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"18%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
		<div class="wp-block-column" style="flex-basis:18%">
			<!-- wp:heading {"fontSize":"medium"} -->
			<h2 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Company', 'unapp' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"},"typography":{"textTransform":"none","letterSpacing":"0","fontWeight":"400"}},"fontFamily":"body","fontSize":"medium"} -->
				<!-- wp:navigation-link {"label":"<?php echo esc_html_x( 'About', 'Footer menu link', 'unapp' ); ?>","url":"#"} /-->
				<!-- wp:navigation-link {"label":"<?php echo esc_html_x( 'Pricing', 'Footer menu link', 'unapp' ); ?>","url":"#"} /-->
				<!-- wp:navigation-link {"label":"<?php echo esc_html_x( 'Careers', 'Footer menu link', 'unapp' ); ?>","url":"#"} /-->
				<!-- wp:navigation-link {"label":"<?php echo esc_html_x( 'Contact', 'Footer menu link', 'unapp' ); ?>","url":"#"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"24%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
		<div class="wp-block-column" style="flex-basis:24%">
			<!-- wp:heading {"fontSize":"medium"} -->
			<h2 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Latest posts', 'unapp' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:latest-posts {"postsToShow":3,"displayPostDate":true} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"24%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
		<div class="wp-block-column" style="flex-basis:24%">
			<!-- wp:heading {"fontSize":"medium"} -->
			<h2 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Contact', 'unapp' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"style":{"color":{"text":"rgba(255,255,255,0.75)"}}} -->
			<p class="has-text-color" style="color:rgba(255,255,255,0.75)"><?php esc_html_e( '198 West 21th Street, Suite 721', 'unapp' ); ?><br><?php esc_html_e( 'New York, NY 10016', 'unapp' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"style":{"color":{"text":"rgba(255,255,255,0.75)"}}} -->
			<p class="has-text-color" style="color:rgba(255,255,255,0.75)"><a href="tel:+1235235598">+1 235 2355 98</a><br><a href="mailto:info@example.com">info@example.com</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:group {"align":"wide","style":{"border":{"top":{"color":"rgba(255,255,255,0.15)","width":"1px","style":"solid"}},"spacing":{"padding":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide" style="border-top-color:rgba(255,255,255,0.15);border-top-style:solid;border-top-width:1px;padding-top:var(--wp--preset--spacing--40)">
		<!-- wp:paragraph {"style":{"color":{"text":"rgba(255,255,255,0.75)"}},"fontSize":"small"} -->
		<p class="has-text-color has-small-font-size" style="color:rgba(255,255,255,0.75)">
		<?php
		printf(
			/* translators: 1: current year, 2: site name. */
			esc_html__( '© %1$s %2$s. All rights reserved.', 'unapp' ),
			esc_html( date_i18n( 'Y' ) ),
			esc_html( get_bloginfo( 'name' ) )
		);
		?>
		</p>
		<!-- /wp:paragraph -->
		<!-- wp:paragraph {"style":{"color":{"text":"rgba(255,255,255,0.75)"}},"fontSize":"small"} -->
		<p class="has-text-color has-small-font-size" style="color:rgba(255,255,255,0.75)">
		<?php
		printf(
			/* translators: %s: link to Colorlib. */
			esc_html__( 'Unapp theme by %s', 'unapp' ),
			'<a href="' . esc_url( 'https://colorlib.com/' ) . '" rel="nofollow noopener">Colorlib</a>'
		);
		?>
		</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
