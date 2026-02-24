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
$firstdate = mktime(0, 0, 0, $month, 1, $year);
$enddate = mktime(23, 59, 59, $month, cal_days_in_month(CAL_GREGORIAN, $month, $year), $year);

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

    $result = $db->query('SELECT a.' . $db_field . ', b.title FROM ' . NV_BANNERS_GLOBALTABLE . '_click a
    INNER JOIN ' . NV_BANNERS_GLOBALTABLE . '_rows b ON a.bid=b.id
    WHERE b.clid= ' . $user_info['userid'] . ' AND a.click_time <= ' . $enddate . ' AND a.click_time >= ' . $firstdate . '
    AND a.bid=' . $ads . ' ORDER BY click_time ASC');

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

        $chart_labels = $chart_series = $chart_series_formatted = [];

        foreach ($statics as $label => $quantity) {
            if ($chart_type == 'date') {
                $chart_labels[] = $label;
            } else {
                $chart_labels[] = ucfirst($label);
            }
            $chart_series[] = (int) $quantity;
            $chart_series_formatted[] = nv_number_format($quantity);
        }

        // Tính tỷ lệ phần trăm
        $percent_series = [];
        foreach ($chart_series as $quantity) {
            $percent_series[] = float_format(($quantity / $total) * 100);
        }

        $response['charts'][$chart_type] = [
            'labels' => $chart_labels,
            'series' => $chart_series,
            'series_formatted' => $chart_series_formatted,
            'name' => $nv_Lang->getModule('chart_lbl_clicks'),
            'lbl_total' => $nv_Lang->getModule('chart_lbl_total'),
            'total' => $response['total_clicks_formatted'],
            'percent_series' => $percent_series
        ];
    } else {
        $response['charts'][$chart_type] = [
            'labels' => [],
            'series' => [],
            'series_formatted' => [],
            'name' => '',
            'lbl_total' => '',
            'total' => 0
        ];
    }
}

nv_jsonOutput($response);

/**
 * @param float $num
 * @param string $lang
 * @return string
 */
function float_format(float $num, string $lang = '')
{
    global $nv_default_regions, $global_config;

    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }
    $region = $global_config['region'][$lang] ?? $nv_default_regions[$lang] ?? $nv_default_regions['en'];
    $region['decimal_length'] = 1;
    $num = number_format($num, $region['decimal_length'], $region['decimal_symbol'], $region['thousand_symbol']);
    if (strpos($num, $region['decimal_symbol']) !== false) {
        if ($region['trailing_zero']) {
            $num = rtrim($num, '0');
            $num = rtrim($num, $region['decimal_symbol']);
        }
        if ($region['leading_zero']) {
            $num = ltrim($num, '0');
        }
    }

    return $num;
}
