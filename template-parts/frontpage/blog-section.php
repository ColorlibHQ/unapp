<?php
$frontpage = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'blog', Unapp_Repeatable_Sections::get_instance() );
$parent_attr = array(
	'class' => array( 'colorlib-blog', 'ewf-section' ),
	'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat', 'background-parallax' ),
);

$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'blog' );

?>
<section class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
    <div id="<?php echo $id ?>" <?php $attr_helper->generate_attributes( $parent_attr ); ?>>

    	<?php if ( '' != $fields['blog_background_video'] ): ?>
			<a class="player" data-property="{videoURL:'<?php echo esc_url( $fields[ 'blog_background_video' ] ); ?>',containment:'#<?php echo $id ?>', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, quality:'default'}"></a>
		<?php endif ?>

	    <?php $attr_helper->generate_color_overlay(); ?>

        <div class="container">
	        <?php echo wp_kses( unapp_Helper::generate_pencil( 'unapp_Repeatable_Sections', 'blog' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
	                <?php
	                echo wp_kses_post(
		                Unapp_Helper::generate_section_title(
			                $fields['blog_title'],
			                $fields['blog_subtitle'],
			                array(
				                'doubled' => false,
				                'center'  => false,
			                )
		                )
	                );
	                ?>

                </div>
            </div>
            <div class="row">
				<?php
				$args = array(
					'posts_per_page' => $fields['blog_post_count'],
					'ignore_sticky_posts' => 1
				);
				$query = new WP_Query( $args );
				while ( $query->have_posts() ) :
					$query->the_post();
					?>
                    <div class="col-md-4 animate-box" data-animate-effect="<?php echo esc_attr( $fields[ 'blog_animate' ] ); ?>">
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <h2><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a></h2>
                            <p class="admin"><span><?php unapp_post_date_format(); ?></span></p>
                            <p><?php echo unapp_excerpt( 20 ); ?></p>
                            <p class="author-wrap">
                                <a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' )); ?>" class="author-img">
									<?php echo get_avatar( get_the_author_meta( 'ID' ) , 32 ); ?>
                                </a>
                                <a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' )); ?>" class="author"><?php esc_html_e( 'by', 'unapp' );?> <?php the_author_meta( 'display_name' ); ?></a>
                            </p>
                        </article>
                    </div>
				<?php endwhile; ?>
            </div>
        </div>
    </div>
</section>