<?php
$ids=array(9446,10966,116484,229751,12945,109209,116669,3158,9352,116741,13365,3639,116713,230474,8891,30174,15476,90803,328812,15402,4198,176118,2420,95536,163549,106592,3321,12439,15626);
$out=array();foreach($ids as$id){$out[$id]=array();foreach(array('_yoast_wpseo_title','_yoast_wpseo_metadesc','_yoast_wpseo_opengraph-title','_yoast_wpseo_opengraph-description','_yoast_wpseo_twitter-title','_yoast_wpseo_twitter-description')as$key){$out[$id][$key]=get_post_meta($id,$key,true);}}echo wp_json_encode($out,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
