<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @version 1.0
 */
/**
* Template Name: Home
*/
get_header(); ?>
<?php

	$unapp_fp = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
	$unapp_fp->generate_output();

?>

<?php get_footer(); ?>
