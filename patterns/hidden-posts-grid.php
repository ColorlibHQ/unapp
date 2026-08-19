<?php
/**
 * Title: Posts grid (inherits query)
 * Slug: unapp/hidden-posts-grid
 * Inserter: no
 * Description: Three-column post grid with pagination used by the blog, archive and search templates.
 *
 * @package Unapp
 */

?>
<!-- wp:query {"queryId":1,"query":{"perPage":9,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide">
	<!-- wp:query-total {"displayType":"range-display","textAlign":"center","fontSize":"small","textColor":"muted","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} /-->
	<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group">
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/10","style":{"border":{"radius":"12px"}}} /-->
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group">
				<!-- wp:post-date {"fontSize":"small"} /-->
				<!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:post-title {"isLink":true,"level":2,"fontSize":"large"} /-->
			<!-- wp:post-excerpt {"moreText":"<?php echo esc_html_x( 'Read more', 'Post excerpt read more link', 'unapp' ); ?>","excerptLength":20} /-->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->

	<!-- wp:query-pagination {"paginationArrow":"chevron","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
		<!-- wp:query-pagination-previous /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next /-->
	<!-- /wp:query-pagination -->

	<!-- wp:query-no-results -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"600px"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"align":"center","textColor":"muted"} -->
			<p class="has-text-align-center has-muted-color has-text-color"><?php esc_html_e( 'Nothing found. Try a different search term or browse the latest posts from the menu.', 'unapp' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:pattern {"slug":"unapp/hidden-search"} /-->
		</div>
		<!-- /wp:group -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
