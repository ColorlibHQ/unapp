<?php
/**
 * Title: Agency: selected clients
 * Slug: unapp/agency-clients
 * Categories: unapp, unapp_agency, unapp_proof
 * Keywords: agency, clients, work, portfolio, list
 * Viewport Width: 1400
 * Description: A dated client list with the discipline beside each name.
 *
 * @package Unapp
 */

$unapp_agency_clients = array(
	array(
		'name' => _x( "Northbank", 'Agency client', 'unapp' ),
		'meta' => _x( "Brand and product · 2026", 'Agency client', 'unapp' ),
	),
	array(
		'name' => _x( "Halden Rail", 'Agency client', 'unapp' ),
		'meta' => _x( "Identity · 2025", 'Agency client', 'unapp' ),
	),
	array(
		'name' => _x( "Pier & Post", 'Agency client', 'unapp' ),
		'meta' => _x( "Website and CMS · 2025", 'Agency client', 'unapp' ),
	),
	array(
		'name' => _x( "Verity Health", 'Agency client', 'unapp' ),
		'meta' => _x( "Design system · 2024", 'Agency client', 'unapp' ),
	),
	array(
		'name' => _x( "Brightwater", 'Agency client', 'unapp' ),
		'meta' => _x( "Campaign · 2024", 'Agency client', 'unapp' ),
	),
	array(
		'name' => _x( "Foldline", 'Agency client', 'unapp' ),
		'meta' => _x( "Naming and brand · 2023", 'Agency client', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Selected clients', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Who we have done it for', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<?php foreach ( $unapp_agency_clients as $unapp_agency_client ) : ?>
<!-- wp:columns {"verticalAlignment":"center","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center is-not-stacked-on-mobile">
<!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%;">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_agency_client['name']; ?></h3>
<!-- /wp:heading -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%;">
<!-- wp:paragraph {"align":"right","textColor":"muted","fontSize":"small"} -->
<p class="has-text-align-right has-muted-color has-text-color has-small-font-size"><?php echo $unapp_agency_client['meta']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- wp:separator {"className":"is-style-wide","backgroundColor":"border"} -->
<hr class="wp-block-separator has-text-color has-border-color has-border-border-color has-alpha-channel-opacity has-border-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
