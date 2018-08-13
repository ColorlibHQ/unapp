<?php
/**
 * Template part for displaying single pages.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MedZone_Lite
 */

?>
<article id="page-<?php the_ID(); ?>">
	<?php
	if( has_post_thumbnail() ){
		the_post_thumbnail( 'unapp-post-thumb' );
	}
	?>
    <div class="post-entry"><?php the_content(); ?></div>
	<?php
	wp_link_pages( array(
		'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'unapp' ),
		'after'  => '</div>',
	) );
	?>
</article>

