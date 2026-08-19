<?php
/**
 * Title: Header: centred
 * Slug: unapp/header-centered
 * Categories: unapp, header
 * Keywords: header, centered, logo, navigation, template part
 * Block Types: core/template-part/header
 * Viewport Width: 1400
 * Description: Centred site title above a centred menu — an alternative header template part.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","backgroundColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group">
<!-- wp:site-logo {"width":40} /-->
<!-- wp:site-title {"level":0,"textAlign":"center"} /-->
</div>
<!-- /wp:group -->
<!-- wp:navigation {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} /-->
</div>
<!-- /wp:group -->
