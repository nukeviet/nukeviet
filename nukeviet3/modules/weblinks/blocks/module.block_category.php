<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
 if(!defined('NV_IS_MOD_WEBLINKS'))die('Stop!!!');
 global $global_array_cat, $catid;

 $array_subcat = array();
$array_cat = array();
foreach($global_array_cat as $array_cat_i)
{
    if($array_cat_i['parentid'] == $catid)
    {
        $array_subcat[] = array("title" => $array_cat_i['title'], "link" => $array_cat_i['link'], "count_link" => $array_cat_i['count_link']);
    }
}

$array_cat[] = array("title" => $global_array_cat[$catid]['title'], "link" => $global_array_cat[$catid]['link'], "description" => $global_array_cat[$catid]['description']);

$content = "adadadd";
?>