<?php
global $wpdb;$unapp=get_post(169040);$out=array('unapp_parent'=>$unapp->post_parent,'themes'=>array(),'articles'=>array());
$pages=$wpdb->get_results("SELECT ID,post_title,post_name,post_date,post_modified,post_content FROM {$wpdb->posts} WHERE post_status='publish' AND post_type='page'",ARRAY_A);
foreach($pages as$p){$url=get_permalink($p['ID']);if(preg_match('~/wp/themes/[^/]+/$~',$url)){$p['url']=$url;$out['themes'][]=$p;}}
$articles=$wpdb->get_results("SELECT ID,post_title,post_name,post_date,post_modified,post_content FROM {$wpdb->posts} WHERE post_status='publish' AND post_type='post' AND (post_title LIKE '%WordPress%' OR post_title LIKE '%Theme%')",ARRAY_A);
foreach($articles as$p){$p['url']=get_permalink($p['ID']);$out['articles'][]=$p;}
echo wp_json_encode($out,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
