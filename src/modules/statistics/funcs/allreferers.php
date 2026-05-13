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

$sql = 'SELECT COUNT(*) as num_items, SUM(total) as total_sum, MAX(total) as max FROM ' . NV_REFSTAT_TABLE;
$result = $db->query($sql);
$row_meta = $result->fetch();
$num_items = $row_meta['num_items'] ?? 0;
$total = $row_meta['total_sum'] ?? 0;
$max = $row_meta['max'] ?? 0;

if ($num_items) {
    $base_url = $page_url;
    $page = $nv_Request->get_page('page', 'get', 1);
    $per_page = 50;

    if ($page > 1) {
        $page_url .= '&amp;page=' . $page;
    }

    // Không cho tùy ý đánh số page + xác định trang trước, trang sau
    betweenURLs($page, ceil($num_items / $per_page), $base_url, '&amp;page=', $prevPage, $nextPage);

    $stmt = $db->prepare('SELECT host, total, last_update FROM ' . NV_REFSTAT_TABLE . ' WHERE total!=0 ORDER BY total DESC LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
    $stmt->execute();

    $host_list = [];
    while ($row = $stmt->fetch()) {
        $host_list[] = [
            'key' => $row['host'],
            'count' => $row['total'],
            'count_format' => nv_number_format($row['total']),
            'last_visit' => !empty($row['last_update']) ? nv_datetime_format($row['last_update'], 0, 0) : '',
            'bymonth_link' => NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . ($module_info['alias']['referer'] ?? 'referer') . '&amp;host=' . $row['host'],
            'proc' => ceil(($row['total'] / $max) * 100)
        ];
    }
    $stmt->closeCursor();

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

    if ($page > 1) {
        $page_title .= NV_TITLEBAR_DEFIS . $nv_Lang->getGlobal('page') . ' ' . $page;
    }

    $contents = nv_theme_statistics_allreferers($host_list, $generate_page);
}

!defined('NV_ADMIN') && $canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
