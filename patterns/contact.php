<?php
/**
 * Title: Contact details
 * Slug: unapp/contact
 * Categories: unapp, contact
 * Keywords: contact, address, phone, email, cards
 * Viewport Width: 1400
 * Description: Section intro and three contact cards (address, phone, email) followed by a call-to-action button. Add a form block from your contact-form plugin below it if needed.
 *
 * @package Unapp
 */

$unapp_contacts = array(
	array(
		'icon'  => 'map-pin',
		'title' => _x( 'Visit us', 'Contact card title', 'unapp' ),
		'html'  => esc_html_x( '198 West 21th Street, Suite 721', 'Contact card address line 1', 'unapp' ) . '<br>' . esc_html_x( 'New York, NY 10016', 'Contact card address line 2', 'unapp' ),
	),
	array(
		'icon'  => 'phone',
		'title' => _x( 'Call us', 'Contact card title', 'unapp' ),
		'html'  => '<a href="tel:+1235235598">+1 235 2355 98</a><br>' . esc_html_x( 'Mon–Fri, 9:00–18:00', 'Contact card opening hours', 'unapp' ),
	),
	array(
		'icon'  => 'mail',
		'title' => _x( 'Email us', 'Contact card title', 'unapp' ),
		'html'  => '<a href="mailto:info@example.com">info@example.com</a><br>' . esc_html_x( 'We reply within one business day', 'Contact card email note', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"heading"} -->
		<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase"><?php echo esc_html_x( 'Contact', 'Section eyebrow label', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"textAlign":"center"} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Get in touch', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Questions about plans, security or a custom rollout? Our team is happy to help.', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-columns alignwide">
		<?php foreach ( $unapp_contacts as $unapp_contact ) : ?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
			<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:group {"style":{"border":{"radius":"999px"},"spacing":{"padding":{"top":"14px","bottom":"14px","left":"14px","right":"14px"}}},"backgroundColor":"primary","layout":{"type":"flex","flexWrap":"nowrap"}} -->
				<div class="wp-block-group has-primary-background-color has-background" style="border-radius:999px;padding-top:14px;padding-right:14px;padding-bottom:14px;padding-left:14px">
					<!-- wp:image {"width":"24px","height":"24px","sizeSlug":"full","linkDestination":"none"} -->
					<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icons/' . $unapp_contact['icon'] . '.svg' ) ); ?>" alt="" style="width:24px;height:24px"/></figure>
					<!-- /wp:image -->
				</div>
				<!-- /wp:group -->
				<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
				<h3 class="wp-block-heading has-text-align-center has-large-font-size"><?php echo esc_html( $unapp_contact['title'] ); ?></h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"align":"center","textColor":"muted"} -->
				<p class="has-text-align-center has-muted-color has-text-color"><?php echo wp_kses( $unapp_contact['html'], array( 'a' => array( 'href' => array() ), 'br' => array() ) ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="mailto:info@example.com"><?php echo esc_html_x( 'Send us a message', 'Button text', 'unapp' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
