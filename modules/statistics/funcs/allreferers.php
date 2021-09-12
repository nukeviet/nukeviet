<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_STATISTICS')) {
    exit('Stop!!!');
}

$page_title = $lang_module['referer'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['referer'];
$page_url = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allreferers'];
$contents = '';

$sql = 'SELECT COUNT(*), SUM(total), MAX(total) FROM ' . NV_REFSTAT_TABLE;
$result = $db->query($sql);
list($num_items, $total, $max) = $result->fetch(3);

if ($num_items) {
    $base_url = $page_url;
    $page = $nv_Request->get_int('page', 'get', 1);
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
    while (list($host, $count, $last_visit) = $result->fetch(3)) {
        $last_visit = !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '';
        $bymonth = '<a href="' . NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['referer'] . '&amp;host=' . $host . '">' . $lang_module['statbymoth2'] . '</a>';
        $host_list[$host] = [$count, $last_visit, $bymonth];
    }

    if (!empty($host_list)) {
        $cts = [];
        $cts['thead'] = [$lang_module['referer'], $lang_module['hits'], $lang_module['last_visit']];
        $cts['rows'] = $host_list;
        $cts['max'] = $max;
        $cts['generate_page'] = nv_generate_page($base_url, $num_items, $per_page, $page);
    }
    if ($page > 1) {
        $page_title .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
    }
    $contents = nv_theme_statistics_allreferers($num_items, $cts, $host_list);
}

$canonicalUrl = getCanonicalUrl($page_url, true, true);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
