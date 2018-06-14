<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @version unapp 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$arg = array(
'status'    => 'approve'
);
$comments_query = new WP_Comment_Query( $arg );
?>


    <div class="section-padding-top">
		<?php if(get_comments_number() > 0 ): ?>
            <div class="section-wrapper">

                <div class="comment-area">
                    <h4 class="title"><?php esc_html_e( 'Comments', 'unapp' ); ?> ( <?php print number_format_i18n(get_comments_number() ); ?> )</h4>
                    <ul class="comments">
						<?php
						if( number_format_i18n( get_comments_number() ) > 0 ):
							wp_list_comments( array(
								'style'       => 'ul',
								'callback'    => 'unapp_comment_list',
								'short_ping'  => true
							) );
						endif;
						?>
                    </ul>
                    <div class="nav-links">
						<?php paginate_comments_links( array('type' => 'list' ) ); ?>
                    </div>
                </div>

            </div>
		<?php endif; ?>
        <div class="section-wrapper">
            <div class="write-comment">
				<?php
				$commenter = wp_get_current_commenter();
				$req = get_option( 'require_name_email' );
				$aria_req = ($req ? " aria-required='true' " : '');
				$required_text = ' ';

				$comment_form_arg = array(
					'class_submit'  => 'button',
					'label_submit'  => esc_html__( 'Submit', 'unapp' ),
					'title_reply'   => esc_html__( 'Leave a Comments', 'unapp' ),
					'fields'        => apply_filters( 'comment_form_default_fields', array(
						'author'    => '<div class="row"><div class="col-xs-6"><div class="form-group"><input type="text"' . $aria_req . ' name="author" class="form-control" placeholder="Name*" value="'.esc_attr( $commenter['comment_author'] ).'"></div></div>',
						'email'    => '<div class="col-xs-6"><div class="form-group"><input type="email"' . $aria_req . ' name="email" class="form-control" placeholder="Email*" value="'.esc_attr(  $commenter['comment_author_email'] ).'"></div></div></div>'
					) ),
					'comment_field' => '<div class="form-group"><textarea name="comment" rows="7" class="form-control" placeholder="Comment*"></textarea></div>',
				);
				comment_form( $comment_form_arg );
				?>
            </div>
        </div>
    </div>