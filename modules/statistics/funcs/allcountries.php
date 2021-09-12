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

$page_title = $lang_module['country'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['country'];
$page_url = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allcountries'];
$contents = '';

$sql = 'SELECT COUNT(*), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='country' AND c_count!=0";
$result = $db->query($sql);
list($num_items, $max) = $result->fetch(3);

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
        ->select('c_val,c_count, last_update')
        ->from(NV_COUNTER_GLOBALTABLE)
        ->where("c_type='country' AND c_count!=0")
        ->order('c_count DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db->query($db->sql());

    $countries_list = [];
    while (list($country, $count, $last_visit) = $result->fetch(3)) {
        $fullname = isset($countries[$country]) ? $countries[$country][1] : $lang_module['unknown'];
        $last_visit = !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '';
        $countries_list[$country] = [$fullname, $count, $last_visit];
    }

    if (!empty($countries_list)) {
        $cts = [];
        $cts['thead'] = [$lang_module['country'], $lang_module['hits'], $lang_module['last_visit']];
        $cts['rows'] = $countries_list;
        $cts['max'] = $max;
        $cts['generate_page'] = nv_generate_page($base_url, $num_items, $per_page, $page);
    }
    if ($page > 1) {
        $page_title .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
    }

    $contents = nv_theme_statistics_allcountries($num_items, $countries_list, $cts);
}

$canonicalUrl = getCanonicalUrl($page_url, true, true);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
