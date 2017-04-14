<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/14/2017 09:47
 */

if (!defined('NV_IS_MOD_FAQ')) {
    die('Stop!!!');
}

$url = array();
$cacheFile = NV_LANG_DATA . '_Sitemap.cache';
$cacheTTL = 7200;

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $url = unserialize($cache);
} else {
    $list_cats = nv_list_cats();
    $in = array_keys($list_cats);
    $in = implode(',', $in);
    
    $sql = 'SELECT id, catid, addtime 
        FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE catid IN (' . $in . ') 
        AND status=1 ORDER BY weight ASC LIMIT 1000';
    $result = $db->query($sql);
    
    while (list($id, $cid, $publtime) = $result->fetch(3)) {
        $url[] = array(
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $list_cats[$cid]['alias'] . '#faq' . $id,
            'publtime' => $publtime
        );
    }
    
    $cache = serialize($url);
    $nv_Cache->setItem($module_name, $cacheFile, $cache, $cacheTTL);
}

nv_xmlSitemap_generate($url);
die();
