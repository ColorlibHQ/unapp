<?php
/**
 * Title: Header: simple
 * Slug: unapp/header-simple
 * Categories: unapp, header
 * Keywords: header, simple, menu, branding
 * Block Types: core/template-part/header
 * Viewport Width: 1400
 * Description: Site branding and the primary menu, without a call-to-action button.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","backgroundColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);">
<!-- wp:group {"align":"wide","className":"unapp-header-row","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide unapp-header-row">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
<!-- wp:site-logo {"width":40} /-->
<!-- wp:site-title {"level":0} /-->
</div>
<!-- /wp:group -->
<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
<!-- wp:navigation {"ariaLabel":"<?php echo esc_attr_x( 'Main menu', 'Navigation landmark label', 'unapp' ); ?>","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
