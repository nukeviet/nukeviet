<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_STATISTICS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('referer');
$key_words = $module_info['keywords'];
$page_url = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allreferers'];
$contents = '';

$sql = 'SELECT COUNT(*), SUM(total), MAX(total) FROM ' . NV_REFSTAT_TABLE;
$result = $db->query($sql);
[$num_items, $total, $max] = $result->fetch(3);

if ($num_items) {
    $base_url = $page_url;
    $page = $nv_Request->get_page('page', 'get', 1);
    $per_page = 50;

    if ($page > 1) {
        $page_url .= '&amp;page=' . $page;
    }

    // Không cho tùy ý đánh số page + xác định trang trước, trang sau
    betweenURLs($page, ceil($num_items / $per_page), $base_url, '&amp;page=', $prevPage, $nextPage);

    $db->sqlreset()
        ->select('host, total, last_update')
        ->from(NV_REFSTAT_TABLE)
        ->where('total!=0')
        ->order('total DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db->query($db->sql());

    $host_list = [];
    while ([$host, $count, $last_visit] = $result->fetch(3)) {
        $host_list[] = [
            'key' => $host,
            'count' => $count,
            'count_format' => nv_number_format($count),
            'last_visit' => !empty($last_visit) ? nv_datetime_format($last_visit, 0, 0) : '',
            'bymonth_link' => NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['referer'] . '&amp;host=' . $host,
            'proc' => ceil(($count / $max) * 100)
        ];
    }

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

    if ($page > 1) {
        $page_title .= NV_TITLEBAR_DEFIS . $nv_Lang->getGlobal('page') . ' ' . $page;
    }

    $contents = nv_theme_statistics_allreferers($host_list, $generate_page);
}

$canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
