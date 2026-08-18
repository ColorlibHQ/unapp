<?php
/**
 * Title: Comments
 * Slug: unapp/hidden-comments
 * Inserter: no
 * Description: Comments list, pagination and comment form.
 *
 * @package Unapp
 */

?>
<!-- wp:comments {"className":"wp-block-comments-query-loop","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}}} -->
<div class="wp-block-comments wp-block-comments-query-loop" style="margin-top:var(--wp--preset--spacing--60)">
	<!-- wp:comments-title {"level":3} /-->
	<!-- wp:comment-template -->
		<!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|border","width":"1px","style":"solid"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
		<div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-style:solid;border-bottom-width:1px;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)">
			<!-- wp:avatar {"size":48,"style":{"border":{"radius":"999px"}}} /-->
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
				<div class="wp-block-group">
					<!-- wp:comment-author-name /-->
					<!-- wp:comment-date /-->
					<!-- wp:comment-edit-link /-->
				</div>
				<!-- /wp:group -->
				<!-- wp:comment-content /-->
				<!-- wp:comment-reply-link /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:comment-template -->
	<!-- wp:comments-pagination {"paginationArrow":"chevron","layout":{"type":"flex","justifyContent":"space-between"}} -->
		<!-- wp:comments-pagination-previous /-->
		<!-- wp:comments-pagination-numbers /-->
		<!-- wp:comments-pagination-next /-->
	<!-- /wp:comments-pagination -->
	<!-- wp:post-comments-form /-->
</div>
<!-- /wp:comments -->
