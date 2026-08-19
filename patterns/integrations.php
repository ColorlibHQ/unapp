<?php
/**
 * Title: Integrations grid
 * Slug: unapp/integrations
 * Categories: unapp, unapp_features, features, columns
 * Keywords: integrations, apps, connect, stack, grid, icons
 * Viewport Width: 1400
 * Description: A grid of integration tiles with icons, plus a link to a full directory.
 *
 * @package Unapp
 */

$unapp_integrations = array(
	array( 'icon' => 'code', 'label' => _x( 'Code hosting', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'figma', 'label' => _x( 'Design files', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'message-circle', 'label' => _x( 'Team chat', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'calendar', 'label' => _x( 'Calendars', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'inbox', 'label' => _x( 'Email', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'database', 'label' => _x( 'Data warehouse', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'credit-card', 'label' => _x( 'Billing', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'terminal', 'label' => _x( 'CLI and API', 'Integration label', 'unapp' ) ),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Integrations', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Plays nicely with your stack', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Two-way sync with the tools your team already opens every morning.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-4","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->
<div class="wp-block-group alignwide unapp-grid-4">
<?php foreach ( $unapp_integrations as $unapp_integration ) : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group is-style-card" style="border-radius:16px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40);">
<!-- wp:group {"style":{"border":{"radius":"14px"},"spacing":{"padding":{"top":"14px","bottom":"14px","left":"14px","right":"14px"}}},"backgroundColor":"primary","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group has-primary-background-color has-background" style="border-radius:14px;padding-top:14px;padding-right:14px;padding-bottom:14px;padding-left:14px">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"24px","height":"24px"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icons/' . $unapp_integration['icon'] . '.svg' ) ); ?>" alt="" style="width:24px;height:24px;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:paragraph {"fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-heading-font-family has-small-font-size" style="font-weight:600;"><?php echo esc_html( $unapp_integration['label'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Browse all integrations', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
