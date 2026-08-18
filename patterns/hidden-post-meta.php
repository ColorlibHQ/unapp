<?php
/**
 * Title: Post meta
 * Slug: unapp/hidden-post-meta
 * Inserter: no
 * Description: Author avatar, name and publish date shown under the post title.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
<div class="wp-block-group">
	<!-- wp:avatar {"size":32,"style":{"border":{"radius":"999px"}}} /-->
	<!-- wp:post-author-name {"isLink":true,"fontSize":"small"} /-->
	<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
	<p class="has-muted-color has-text-color has-small-font-size">·</p>
	<!-- /wp:paragraph -->
	<!-- wp:post-date {"fontSize":"small"} /-->
</div>
<!-- /wp:group -->
