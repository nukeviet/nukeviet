<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_RSS')) {
    exit('Stop!!!');
}

$page_title = $module_info['site_title'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$canonicalUrl = getCanonicalUrl($page_url, true, true);

$rsscontents = '';
$feed_configs_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . $module_data . '_' . NV_LANG_DATA . '.json';
if (file_exists($feed_configs_file)) {
    $feed_configs = json_decode(file_get_contents($feed_configs_file), true);
    $rsscontents = !empty($feed_configs['contents']) ? $feed_configs['contents'] : '';
}

$contents = nv_rss_main_theme($rsscontents);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
