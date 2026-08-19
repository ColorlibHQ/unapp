<?php
/**
 * Title: Author box
 * Slug: unapp/author-box
 * Categories: unapp, unapp_content, posts
 * Keywords: author, bio, byline, post, avatar
 * Viewport Width: 900
 * Description: Author avatar, name, biography and links — drop it under the post content in the Single template.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center">
<!-- wp:column {"width":"20%"} -->
<div class="wp-block-column" style="flex-basis:20%;">
<!-- wp:avatar {"size":96,"style":{"border":{"radius":"999px"}}} /-->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"80%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column" style="flex-basis:80%;">
<!-- wp:post-author-name {"fontSize":"large","style":{"typography":{"fontWeight":"600"}},"fontFamily":"heading"} /-->
<!-- wp:post-author-biography {"textColor":"muted"} /-->
<!-- wp:social-links {"iconColor":"muted","iconColorValue":"#6b7280","className":"is-style-logos-only","size":"has-small-icon-size"} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only">
<!-- wp:social-link {"url":"https://x.com","service":"x"} /-->
<!-- wp:social-link {"url":"https://linkedin.com","service":"linkedin"} /-->
</ul>
<!-- /wp:social-links -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
