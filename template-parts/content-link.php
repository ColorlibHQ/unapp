<?php
/**
 * Template part for displaying link posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
?>

<?php if( is_single() ) : ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	    <?php
	    if( has_post_thumbnail() ){
		    the_post_thumbnail( 'unapp-post-thumb' );
	    }
	    ?>
        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p class="admin"><span><?php unapp_post_date_format(); ?></span></p>
        <div class="post-entry"><?php the_content(); ?></div>
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'unapp' ),
			'after'  => '</div>',
		) );
		?>
    </article>
<?php else : ?>
    <div class="col-md-4 animate-box">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	        <?php
	        if( has_post_thumbnail() ){
		        the_post_thumbnail( 'unapp-post-thumb' );
	        }
	        ?>
            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p class="admin"><span><?php unapp_post_date_format(); ?></span></p>
            <p><?php echo unapp_excerpt( 20 ); ?></p>
            <p class="author-wrap">
                <a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' )); ?>" class="author-img"><?php echo get_avatar( get_the_author_meta( 'ID' ) , 32 ); ?></a>
                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="author"><?php esc_html_e( 'by', 'unapp' );?> <?php the_author_meta( 'display_name' ); ?></a>
            </p>
        </article>
    </div>
<?php endif; ?>
