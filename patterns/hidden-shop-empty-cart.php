<?php
/**
 * Title: Shop: empty cart
 * Slug: unapp/hidden-shop-empty-cart
 * Inserter: no
 * Categories: unapp
 * Keywords: cart, empty, shop
 * Viewport Width: 1400
 * Description: The empty-cart message used by the Cart template.
 *
 * @package Unapp
 */

?>
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Your cart is empty', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted"} -->
<p class="has-text-align-center has-muted-color has-text-color"><?php esc_html_e( 'Nothing here yet. Have a look at what is in stock.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/shop/"><?php esc_html_e( 'Browse the shop', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
