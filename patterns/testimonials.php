<?php
/**
 * Title: Testimonials grid
 * Slug: unapp/testimonials
 * Categories: unapp, unapp_proof, testimonials, featured
 * Keywords: testimonials, quotes, reviews, customers, social proof
 * Viewport Width: 1400
 * Description: Three testimonial cards with a rating, quote, portrait and role.
 *
 * @package Unapp
 */

$unapp_quotes = array(
	array(
		'quote'  => _x( 'We replaced three tools with Unapp in a fortnight. Our release notes now write themselves from the board.', 'Testimonial quote', 'unapp' ),
		'name'   => _x( 'Priya Raman', 'Testimonial author', 'unapp' ),
		'role'   => _x( 'Head of Product, Northwind', 'Testimonial author role', 'unapp' ),
		'avatar' => 'avatar-2',
	),
	array(
		'quote'  => _x( 'Onboarding used to take a week. New engineers now ship something real on day two.', 'Testimonial quote', 'unapp' ),
		'name'   => _x( 'Tom Alvarez', 'Testimonial author', 'unapp' ),
		'role'   => _x( 'Engineering Manager, Vertex', 'Testimonial author role', 'unapp' ),
		'avatar' => 'avatar-5',
	),
	array(
		'quote'  => _x( 'The reporting alone paid for the year. I can finally answer "where is it?" without a meeting.', 'Testimonial quote', 'unapp' ),
		'name'   => _x( 'Béatrice Laurent', 'Testimonial author', 'unapp' ),
		'role'   => _x( 'COO, Lumen Studio', 'Testimonial author role', 'unapp' ),
		'avatar' => 'avatar-7',
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Customers', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Teams don\'t go back', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'A few words from the people who run their week inside Unapp.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns alignwide">
<?php foreach ( $unapp_quotes as $unapp_quote ) : ?>
<!-- wp:column {} -->
<div class="wp-block-column">
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"96px"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ui/stars-5.svg' ) ); ?>" alt="<?php esc_attr_e( 'Rated 5 out of 5', 'unapp' ); ?>" style="width:96px;"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size"><?php echo esc_html( $unapp_quote['quote'] ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"56px","height":"56px","style":{"border":{"radius":"999px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/avatars/' . $unapp_quote['avatar'] . '.svg' ) ); ?>" alt="<?php echo esc_attr( $unapp_quote['name'] ); ?>" style="border-radius:999px;width:56px;height:56px;"/></figure>
<!-- /wp:image -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontFamily":"heading","fontSize":"medium","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-heading-font-family has-medium-font-size" style="font-weight:600;"><?php echo esc_html( $unapp_quote['name'] ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_quote['role'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
