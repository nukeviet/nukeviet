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

if (!defined('NV_IS_BANNER_CLIENT')) {
    nv_jsonOutput([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
}

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

$types_map = [
    'date' => 'click_time',
    'country' => 'click_country',
    'browser' => 'click_browse_name',
    'os' => 'click_os_name'
];

$response = [
    'status' => 'success',
    'total_clicks' => 0,
    'total_clicks_formatted' => '0',
    'charts' => []
];

foreach ($types_map as $chart_type => $db_field) {
    $data = [];
    $title = '';

    $result = $db->query('SELECT a.' . $db_field . ', b.title FROM ' . NV_BANNERS_GLOBALTABLE . '_click a INNER JOIN ' . NV_BANNERS_GLOBALTABLE . '_rows b ON a.bid=b.id WHERE b.clid= ' . $user_info['userid'] . ' AND a.click_time <= ' . $enddate . ' AND a.click_time >= ' . $firstdate . ' AND a.bid=' . $ads . ' ORDER BY click_time ASC');

    while ($row = $result->fetch()) {
        if ($chart_type == 'date') {
            $data[] = date('d/m', $row[$db_field]);
        } else {
            $data[] = $row[$db_field];
        }
        $title = $row['title'];
    }

    if (count($data) > 0) {
        $statics = array_count_values($data);
        $total = array_sum($statics);

        if ($response['total_clicks'] === 0) {
            $response['total_clicks'] = $total;
            $response['total_clicks_formatted'] = nv_number_format($total);
        }

        $chart_labels = [];
        $chart_series = [];

        foreach ($statics as $label => $quantity) {
            if ($chart_type == 'date') {
                $chart_labels[] = $label;
                $chart_series[] = (int) $quantity;
            } else {
                $chart_labels[] = ucfirst($label);
                $chart_series[] = (int) $quantity;
            }
        }

        $response['charts'][$chart_type] = [
            'labels' => $chart_labels,
            'series' => $chart_series
        ];
    } else {
        $response['charts'][$chart_type] = [
            'labels' => [],
            'series' => []
        ];
    }
}

nv_jsonOutput($response);
