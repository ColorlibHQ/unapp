<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @since unapp 1.0
 */

if ( is_active_sidebar( 'sidebar-1' ) ) {
	?>
        <div class="col-md-4 animate-box">
            <?php dynamic_sidebar( 'sidebar-1' ); ?>
        </div>
    <?php
}
?>


