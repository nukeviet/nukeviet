<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_BANNERS')) {
    exit('Stop!!!');
}

if (defined('NV_IS_BANNER_CLIENT')) {
    $type = $nv_Request->get_title('type', 'post,get', 'country', 1);
    $month = $nv_Request->get_int('month', 'post,get');
    $ads = $nv_Request->get_int('ads', 'post,get');
    $year = (int) date('Y');
    $month_array = [
        1 => 31,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31
    ];
    $month_array[2] = (($year % 100 == 0) and ($year % 400 == 0)) ? 29 : 28;
    $firstdate = mktime(0, 0, 0, $month, 1, $year);
    $enddate = mktime(23, 59, 59, $month, $month_array[$month], $year);
    $onetype = '';

    switch ($type) {
        case 'country':
            $onetype = 'click_country';
            break;
        case 'browser':
            $onetype = 'click_browse_name';
            break;
        case 'os':
            $onetype = 'click_os_name';
            break;
        case 'date':
            $onetype = 'click_time';
            break;
    }

    $data = [];
    $title = '';

    $result = $db->query('SELECT a.' . $onetype . ', b.title FROM ' . NV_BANNERS_GLOBALTABLE . '_click a INNER JOIN ' . NV_BANNERS_GLOBALTABLE . '_rows b ON a.bid=b.id WHERE b.clid= ' . $user_info['userid'] . ' AND a.click_time <= ' . $enddate . ' AND a.click_time >= ' . $firstdate . ' AND a.bid=' . $ads . ' ORDER BY click_time ASC');

    while ($row = $result->fetch()) {
        if ($type == 'date') {
            $data[] = date('d/m', $row[$onetype]);
        } else {
            $data[] = $row[$onetype];
        }
        $title = $row['title'];
    }

    header('Content-Type: application/json; charset=utf-8');

    if (count($data) > 0) {
        $statics = array_count_values($data);
        $total = array_sum($statics);

        $chart_labels = [];
        $chart_series = [];

        foreach ($statics as $label => $quantity) {
            if ($type == 'date') {
                $chart_labels[] = $label;
                $chart_series[] = (int) $quantity;
            } else {
                $chart_labels[] = ucfirst($label);
                $chart_series[] = (int) $quantity;
            }
        }

        echo json_encode([
            'status' => 'success',
            'type' => $type,
            'title' => $title,
            'chart_labels' => $chart_labels,
            'chart_series' => $chart_series,
            'total_clicks' => $total
        ]);
    } else {
        echo json_encode([
            'status' => 'empty',
            'type' => $type,
            'title' => '',
            'chart_labels' => [],
            'chart_series' => [],
            'total_clicks' => 0
        ]);
    }
    exit();
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'status' => 'error',
    'message' => 'Unauthorized'
]);
