<?php
/**
 * Title: Dark mode toggle
 * Slug: unapp/theme-toggle
 * Categories: unapp, unapp_utility, header
 * Keywords: dark mode, light, scheme, toggle, accessibility
 * Viewport Width: 600
 * Description: A button that lets a visitor read the site light or dark and remembers the choice. Add it to the header; the dark tokens and script load only where it appears.
 *
 * @package Unapp
 */

?>
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-outline unapp-scheme-toggle","fontSize":"small"} -->
<div class="wp-block-button has-custom-font-size is-style-outline unapp-scheme-toggle has-small-font-size"><a class="wp-block-button__link has-small-font-size has-custom-font-size wp-element-button" href="#"><span class="unapp-scheme-toggle__light"><?php esc_html_e( 'Dark', 'unapp' ); ?></span><span class="unapp-scheme-toggle__dark"><?php esc_html_e( 'Light', 'unapp' ); ?></span></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
