<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_PAGE')) {
    exit('Stop!!!');
}

$url = [];
$cacheFile = 'sitemap_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 7200;

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, ttl: $cacheTTL)) != false) {
    $url = unserialize($cache);
} else {
    $sql = 'SELECT alias,add_time FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status=1';
    $result = $db_slave->query($sql);

    while ([$alias, $publtime] = $result->fetch(3)) {
        $url[] = [
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $alias . $global_config['rewrite_exturl'],
            'publtime' => $publtime
        ];
    }

    $cache = serialize($url);
    $nv_Cache->setItem($module_name, $cacheFile, $cache, ttl: $cacheTTL);
}

nv_xmlSitemap_generate($url);
exit();
