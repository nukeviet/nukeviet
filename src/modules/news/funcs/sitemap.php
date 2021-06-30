<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
}

/*
 * Xem thêm https://www.sitemaps.org/protocol.html
 * always
 * hourly
 * daily
 * weekly
 * monthly
 * yearly
 * never
 *
 * priority from 0.0 to 1.0
 *
 */

$url = [];
$cacheFile = NV_LANG_DATA . '_sitemap_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 7200;

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $url = unserialize($cache);
} else {
    $db_slave->sqlreset()
        ->select('id, catid, publtime, alias')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
        ->where('status=1')
        ->order($order_articles_by . ' DESC')
        ->limit(1000);
    $result = $db_slave->query($db_slave->sql());

    $url = [];

    while (list($id, $catid_i, $publtime, $alias) = $result->fetch(3)) {
        $catalias = $global_array_cat[$catid_i]['alias'];
        $url[] = [
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $catalias . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
            'publtime' => $publtime,
            'changefreq' => 'daily',
            'priority' => '0.8'
        ];
    }

    $cache = serialize($url);
    $nv_Cache->setItem($module_name, $cacheFile, $cache, $cacheTTL);
}

nv_xmlSitemap_generate($url);
exit();
