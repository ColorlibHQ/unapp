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
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
<div class="wp-block-group">
	<!-- wp:avatar {"size":32,"style":{"border":{"radius":"999px"}}} /-->
	<!-- wp:post-author-name {"isLink":true,"fontSize":"small"} /-->
	<!-- wp:post-date {"fontSize":"small"} /-->
	<!-- wp:post-time-to-read {"fontSize":"small","textColor":"muted"} /-->
</div>
<!-- /wp:group -->
