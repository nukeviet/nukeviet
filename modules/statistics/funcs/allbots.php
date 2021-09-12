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

$page_title = $lang_module['bot'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['bot'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$contents = '';

$result = $db->query('SELECT COUNT(*), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='bot' AND c_count!=0");
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
        ->where("c_type='bot' AND c_count!=0")
        ->order('c_count DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);

    $result = $db->query($db->sql());

    $bot_list = [];
    while (list($bot, $count, $last_visit) = $result->fetch(3)) {
        $bot_list[] = [
            'name' => $bot,
            'count' => $count,
            'count_format' => !empty($count) ? number_format($count) : 0,
            'last_visit' => !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '',
            'proc' => ceil(($count / $max) * 100)
        ];
    }

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

    if ($page > 1) {
        $page_title .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
    }

    $contents = nv_theme_statistics_allbots($bot_list, $generate_page);
}

$canonicalUrl = getCanonicalUrl($page_url, true, true);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
