<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @since unapp 1.0
 */

/**
 * Template Name: About
 */
get_header(); ?>

<?php unapp_page_header(); ?>

<?php

$unapp_fp = Epsilon_Page_Generator::get_instance_page( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );

$unapp_fp->generate_output();

?>

<?php get_footer(); ?>
