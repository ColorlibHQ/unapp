<?php
/**
 * Title: Agency: engagements
 * Slug: unapp/agency-engagements
 * Categories: unapp, unapp_agency, unapp_pricing, pricing
 * Keywords: agency, pricing, retainer, project, sprint, rates
 * Viewport Width: 1400
 * Description: Three engagement shapes with real prices, from a two-week sprint to a monthly retainer.
 *
 * @package Unapp
 */

$unapp_agency_bands = array(
	array(
		'name' => _x( "Sprint", 'Agency engagement', 'unapp' ),
		'price' => _x( "£8,000", 'Agency engagement', 'unapp' ),
		'text' => _x( "Two weeks, one question answered — a positioning test, a prototype, a pitch that has to land.", 'Agency engagement', 'unapp' ),
	),
	array(
		'name' => _x( "Project", 'Agency engagement', 'unapp' ),
		'price' => _x( "from £30,000", 'Agency engagement', 'unapp' ),
		'text' => _x( "Six to twelve weeks with a named team. Most brand and product work sits here.", 'Agency engagement', 'unapp' ),
	),
	array(
		'name' => _x( "Retained", 'Agency engagement', 'unapp' ),
		'price' => _x( "£9,000 a month", 'Agency engagement', 'unapp' ),
		'text' => _x( "Two days a week of the studio, for companies shipping continuously.", 'Agency engagement', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'How we work together', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Three ways to buy an agency', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Published, because you should not have to sit through a call to find out whether we are affordable.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_agency_bands as $unapp_agency_band ) : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_agency_band['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"xx-large","style":{"typography":{"fontWeight":"700","lineHeight":"1"}}} -->
<p class="has-xx-large-font-size" style="font-weight:700;line-height:1;"><?php echo $unapp_agency_band['price']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_agency_band['text']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
