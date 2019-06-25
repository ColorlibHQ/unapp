<?php
defined( 'ABSPATH' ) || exit;

/**
 * Function for unapp page header
 */
if( ! function_exists( 'unapp_page_header' ) ) :
	function unapp_page_header() {
		$img = get_custom_header();
		$img = $img->url;
		?>
		<section id="home" class="video-hero" style="background-image: url('<?php echo esc_url( $img ); ?>');  background-size:cover; background-position: center center;background-attachment:fixed;" data-section="home">
			<div class="ewf-section__overlay-color"></div>
			<div class="display-t display-t2 text-center">
				<div class="display-tc display-tc2">
					<div class="container">
						<div class="col-md-12 col-md-offset-0">
							<div class="animate-box">
								<h2><?php ( is_home() || is_front_page() ) ? esc_html_e( 'Latest Blog Posts', 'unapp' ) : wp_title( $sep = '' ) ?></h2>
								<?php echo get_unapp_breadcrumbs(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
endif; // unapp_page_header

/**
 * Blog post date format that control from 'general setting'
 */
function unapp_post_date_format(){
	$date_format = get_option( 'date_format' );
	the_time($date_format);
}

/**
 * Custom list of comment for the theme
 *
 * @since unapp 1.0
 */
if( !function_exists( 'unapp_comment_list' ) ):
	function unapp_comment_list( $comment, $args, $depth ){
		$GLOBALS['comment'] = $comment;
		?>
        <li id="comment-<?php comment_ID(); ?>" class="comment">
            <div class="comment-wrap">
				<?php
				if(get_avatar($comment) != false) :
					?>
                    <div class="comment-thumb">
						<?php
						echo get_avatar( $comment, 80 );
						?>
                    </div>
				<?php
				endif;
				?>
                <div class="comment-body">
                    <div class="comment-meta">
                        <p><span class="author"><a href="<?php comment_author_link(); ?>"><?php print $comment->comment_author; ?></a></span> / <span class="date"><?php comment_date(); ?></span></p>
                    </div>
                    <div class="comment-content">
						<?php
						if( $comment->comment_approved == 0 ):
							esc_html__( 'Your comment is awaitting moderation', 'unapp' );
						endif;
						comment_text();
						?>
                    </div>
                    <div class="comment-reply">
						<?php
						comment_reply_link( array_merge(
							$args, array(
								'depth' => $depth,
								'max_depth' => $args['max_depth'],
								'reply_text' => esc_html__( 'Reply', 'unapp' ),
							)
						) );
						?>
                    </div>
                </div>
                <div class="fix"></div>
            </div>
        </li>
		<?php
	}
endif;
add_filter('comment_reply_link', 'replace_reply_link_class');
function replace_reply_link_class($class){
	$class = str_replace("class='comment-reply-link", "class='button primary-bg", $class);
	return $class;
}

// /*-------------------------------------------*
//  *				Excerpt Length
//  *------------------------------------------*/

if(!function_exists('unapp_excerpt')):
	function unapp_excerpt($limit) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt);
		} else {
			$excerpt = implode(" ",$excerpt);
		}
		$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
		return $excerpt;
	}
endif;

/**
 * Map marker
 */

if( !function_exists( 'map_marker_enqueue_script' ) ){
	function map_marker_enqueue_script() {
		$theme_name = "var theme_js_directory = '". get_template_directory_uri() . "/assets/images/loc.png'";
		wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/assets/js/custom_js.js', array(), '1.0' );
		wp_add_inline_script( 'custom-js', $theme_name );
	}
	add_action( 'wp_enqueue_scripts', 'map_marker_enqueue_script' );
}

/**
 * Custom function for breadcrumb
 */
if( !function_exists( 'get_unapp_breadcrumbs' ) ){
	function get_unapp_breadcrumbs() {
		// Set variables for later use
		$home_link        = esc_url(home_url('/'));
		$home_text        = esc_html__( 'Home', 'unapp' );
		$link_before      = '<span typeof="v:Breadcrumb">';
		$link_after       = '</span>';
		$link_attr        = ' rel="v:url" property="v:title"';
		$link             = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
		$delimiter        = ' &raquo; ';              // Delimiter between crumbs
		$before           = '<span class="current">'; // Tag before the current crumb
		$after            = '</span>';                // Tag after the current crumb
		$page_addon       = '';                       // Adds the page number if the query is paged
		$breadcrumb_trail = '';
		$category_links   = '';

		/**
		 * Set our own $wp_the_query variable. Do not use the global variable version due to
		 * reliability
		 */
		$wp_the_query   = $GLOBALS['wp_the_query'];
		$queried_object = $wp_the_query->get_queried_object();

		// Handle single post requests which includes single pages, posts and attatchments
		if ( is_singular() )
		{
			/**
			 * Set our own $post variable. Do not use the global variable version due to
			 * reliability. We will set $post_object variable to $GLOBALS['wp_the_query']
			 */
			$post_object = sanitize_post( $queried_object );

			// Set variables
			$title          = apply_filters( 'the_title', $post_object->post_title );
			$parent         = $post_object->post_parent;
			$post_type      = $post_object->post_type;
			$post_id        = $post_object->ID;
			$post_link      = $before . $title . $after;
			$parent_string  = '';
			$post_type_link = '';

			if ( 'post' === $post_type )
			{
				// Get the post categories
				$categories = get_the_category( $post_id );
				if ( $categories ) {
					// Lets grab the first category
					$category  = $categories[0];

					$category_links = get_category_parents( $category, true, $delimiter );
					$category_links = str_replace( '<a',   $link_before . '<a' . $link_attr, $category_links );
					$category_links = str_replace( '</a>', '</a>' . $link_after,             $category_links );
				}
			}

			if ( !in_array( $post_type, ['post', 'page', 'attachment'] ) )
			{
				$post_type_object = get_post_type_object( $post_type );
				$archive_link     = esc_url( get_post_type_archive_link( $post_type ) );

				$post_type_link   = sprintf( $link, $archive_link, $post_type_object->labels->singular_name );
			}

			// Get post parents if $parent !== 0
			if ( 0 !== $parent )
			{
				$parent_links = [];
				while ( $parent ) {
					$post_parent = get_post( $parent );

					$parent_links[] = sprintf( $link, esc_url( get_permalink( $post_parent->ID ) ), get_the_title( $post_parent->ID ) );

					$parent = $post_parent->post_parent;
				}

				$parent_links = array_reverse( $parent_links );

				$parent_string = implode( $delimiter, $parent_links );
			}

			// Lets build the breadcrumb trail
			if ( $parent_string ) {
				$breadcrumb_trail = $parent_string . $delimiter . $post_link;
			} else {
				$breadcrumb_trail = $post_link;
			}

			if ( $post_type_link )
				$breadcrumb_trail = $post_type_link . $delimiter . $breadcrumb_trail;

			if ( $category_links )
				$breadcrumb_trail = $category_links . $breadcrumb_trail;
		}

		// Handle archives which includes category-, tag-, taxonomy-, date-, custom post type archives and author archives
		if( is_archive() )
		{
			if (    is_category()
			        || is_tag()
			        || is_tax()
			) {
				// Set the variables for this section
				$term_object        = get_term( $queried_object );
				$taxonomy           = $term_object->taxonomy;
				$term_id            = $term_object->term_id;
				$term_name          = $term_object->name;
				$term_parent        = $term_object->parent;
				$taxonomy_object    = get_taxonomy( $taxonomy );
				$current_term_link  = $before . $taxonomy_object->labels->singular_name . ': ' . $term_name . $after;
				$parent_term_string = '';

				if ( 0 !== $term_parent )
				{
					// Get all the current term ancestors
					$parent_term_links = [];
					while ( $term_parent ) {
						$term = get_term( $term_parent, $taxonomy );

						$parent_term_links[] = sprintf( $link, esc_url( get_term_link( $term ) ), $term->name );

						$term_parent = $term->parent;
					}

					$parent_term_links  = array_reverse( $parent_term_links );
					$parent_term_string = implode( $delimiter, $parent_term_links );
				}

				if ( $parent_term_string ) {
					$breadcrumb_trail = $parent_term_string . $delimiter . $current_term_link;
				} else {
					$breadcrumb_trail = $current_term_link;
				}

			} elseif ( is_author() ) {

				$breadcrumb_trail = esc_html__( 'Author archive for ', 'unapp') .  $before . $queried_object->data->display_name . $after;

			} elseif ( is_date() ) {
				// Set default variables
				$year     = $wp_the_query->query_vars['year'];
				$monthnum = $wp_the_query->query_vars['monthnum'];
				$day      = $wp_the_query->query_vars['day'];

				// Get the month name if $monthnum has a value
				if ( $monthnum ) {
					$date_time  = DateTime::createFromFormat( '!m', $monthnum );
					$month_name = $date_time->format( 'F' );
				}

				if ( is_year() ) {

					$breadcrumb_trail = $before . $year . $after;

				} elseif( is_month() ) {

					$year_link        = sprintf( $link, esc_url( get_year_link( $year ) ), $year );

					$breadcrumb_trail = $year_link . $delimiter . $before . $month_name . $after;

				} elseif( is_day() ) {

					$year_link        = sprintf( $link, esc_url( get_year_link( $year ) ),             $year       );
					$month_link       = sprintf( $link, esc_url( get_month_link( $year, $monthnum ) ), $month_name );

					$breadcrumb_trail = $year_link . $delimiter . $month_link . $delimiter . $before . $day . $after;
				}

			} elseif ( is_post_type_archive() ) {

				$post_type        = $wp_the_query->query_vars['post_type'];
				$post_type_object = get_post_type_object( $post_type );

				$breadcrumb_trail = $before . $post_type_object->labels->singular_name . $after;

			}
		}

		// Handle the search page
		if ( is_search() ) {
			$breadcrumb_trail = esc_html__( 'Search query for: ', 'unapp' ) . $before . get_search_query() . $after;
		}

		// Handle 404's
		if ( is_404() ) {
			$breadcrumb_trail = $before . esc_html__( 'Error 404', 'unapp' ) . $after;
		}

		// Handle paged pages
		if ( is_paged() ) {
			$current_page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
			$page_addon   = $before . sprintf( esc_html__( ' ( Page %s )', 'unapp' ), number_format_i18n( $current_page ) ) . $after;
		}

		$breadcrumb_output_link  = '';
		$breadcrumb_output_link .= '<div class="breadcrumb">';
		if (    is_home()
		        || is_front_page()
		) {
			// Do not show breadcrumbs on page one of home and frontpage
			if ( is_paged() ) {

				$breadcrumb_output_link .= '<a href="' . $home_link . '">' . $home_text . '</a>';
				$breadcrumb_output_link .= $page_addon;
			}
		} else {
			$breadcrumb_output_link .= '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $home_text . '</a>';
			$breadcrumb_output_link .= $delimiter;
			$breadcrumb_output_link .= $breadcrumb_trail;
			$breadcrumb_output_link .= $page_addon;
		}
		$breadcrumb_output_link .= '</div><!-- .breadcrumbs -->';

		return $breadcrumb_output_link;
	}
}

/**
 * Hide epsilon framework quick bar
 */
add_filter( 'show_epsilon_quickie_bar', function (){
    return false;
} );

// Set contact form 7 default form template
function unapp_contact7_form_content( $template, $prop ) {

    if ( 'form' == $prop ) {

        $template =
            '<div class="row form-group"><div class="col-md-6">[text* your-name id:fname class:form-control placeholder "First Name"]</div><div class="col-md-6">[text last-name id:lname class:form-control placeholder "Last Name"]</div></div><div class="row form-group"><div class="col-md-12">[email* your-email id:email class:form-control placeholder "Your email"]</div></div><div class="row form-group"><div class="col-md-12">[text subject id:subject class:form-control placeholder "Your subject of this message"]</div></div><div class="row form-group"><div class="col-md-12">[textarea* message id:message class:form-control placeholder rows:10 cols:30 "Say some thing about us"]</div></div><div class="form-group">[submit class:btn class:btn-primary "Send Message"]</div>';

        return $template;

    } else {
        return $template;
    }
}
add_filter( 'wpcf7_default_template', 'unapp_contact7_form_content', 10, 2 );

