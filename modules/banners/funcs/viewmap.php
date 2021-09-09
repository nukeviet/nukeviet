<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
    $width = $nv_Request->get_int('width', 'post,get', 700);
    $year = (int) date('Y');
    $month_array = [
        '1' => 31,
        '3' => 31,
        '4' => 30,
        '5' > 31,
        '6' => 30,
        '7' => 31,
        '8' => 31,
        '9' => 30,
        '10' => 31,
        '11' => 30,
        '12' => 31
    ];
    $month_array['2'] = (($year % 100 == 0) and ($year % 400 == 0)) ? 29 : 28;
    $firstdate = mktime(0, 0, 0, $month, 1, $year);
    $enddate = mktime(24, 60, 60, $month, $month_array[$month], $year);
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

    $process = $data = [];

    $geturl = new NukeViet\Client\UrlGetContents($global_config);

    $result = $db->query('SELECT a.' . $onetype . ', b.title FROM ' . NV_BANNERS_GLOBALTABLE . '_click a INNER JOIN ' . NV_BANNERS_GLOBALTABLE . '_rows b ON a.bid=b.id WHERE b.clid= ' . $user_info['userid'] . ' AND a.click_time <= ' . $enddate . ' AND a.click_time >= ' . $firstdate . ' AND a.bid=' . $ads . ' ORDER BY click_time ASC');
    $title = 'Ads statistic';
    while ($row = $result->fetch()) {
        if ($type == 'date') {
            $row[$onetype] = date('d/m', $row[$onetype]);
        }
        $data[] = $row[$onetype];
        $title = $row['title'];
    }
    if (sizeof($data) > 0) {
        $statics = array_count_values($data);
        $total = array_sum($statics);

        foreach ($statics as $country => $quantity) {
            if ($type == 'date') {
                $process[$country . ' (' . $quantity . ' click)'] = $quantity;
            } else {
                $process[ucfirst($country) . ' (' . round((((int) $quantity * 100) / $total), 2) . '%)'] = round((((int) $quantity * 100) / $total), 2);
            }
        }

        $width = $width > 500 ? 700 : 500;
        $height = $width == 700 ? 300 : 200;

        // google chart intergrated :|
        $imagechart = 'http://chart.apis.google.com/chart?chs=' . $width . 'x' . $height . '&cht=p3&chco=7777CC|76A4FB|3399CC|3366CC|000000|7D5F5F|A94A4A|13E9E9|526767|DBD6D6&chd=t:';
        $imagechart .= implode(',', array_values($process));
        $imagechart .= '&chl=';
        $imagechart .= implode('|', array_keys($process));
        $imagechart .= '&chtt=' . urlencode($title);
        $imagechart = str_replace(' ', '%20', $imagechart);
        header('Content-type: image/png');
        echo $geturl->get($imagechart);
        exit();
    }
}

header('Content-Type: image/svg+xml');
echo '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" viewBox="0 0 15748.03 1968.5"><path d="M8106.99 1298.52c0-14.35-11.64-25.99-25.99-25.99-14.35 0-25.99 11.63-25.99 25.99 0 14.35 11.64 25.99 25.99 25.99 14.35 0 25.99-11.64 25.99-25.99zM8081 1064.63c-14.35 0-25.99 11.63-25.99 25.99v103.95c0 14.35 11.64 25.99 25.99 25.99 14.35 0 25.99-11.64 25.99-25.99v-103.95c0-14.35-11.64-25.99-25.99-25.99zm25.99-102.5V673.09c0-85.3-170.83-129.94-339.57-129.94-168.74 0-339.57 44.63-339.57 129.94v625.43c0 28.19 19.06 68.26 109.87 98.98 62.28 21.08 143.85 32.69 229.7 32.69 76.75 0 150.32-8.9 208.67-25.14 32.2 16.44 67.93 25.14 104.92 25.14 129.02 0 233.89-106.42 233.89-235.62 0-120.19-91.12-219.46-207.9-232.44zm-339.57-367c179.92 0 287.59 50.19 287.59 77.96 0 27.23-105.77 77.96-287.59 77.96-179.91 0-287.59-50.19-287.59-77.96 0-27.23 105.77-77.96 287.59-77.96zm-287.59 150.41c63.38 37.94 175.93 57.49 287.59 57.49 111.66 0 224.21-19.55 287.59-57.49v85.21c0 27.23-105.77 77.96-287.59 77.96-179.91 0-287.59-50.19-287.59-77.96v-85.21zm0 157.66c63.38 37.94 175.94 57.49 287.59 57.49 111.66 0 224.21-19.55 287.59-57.49v58.94c-66.35 7.37-124.42 42.6-162.2 93.71-38.64 5.75-81.73 8.79-125.4 8.79-179.91 0-287.59-50.19-287.59-77.96v-83.47zm0 155.92c63.38 37.94 175.94 57.49 287.59 57.49 32.28 0 64.33-1.58 94.77-4.62-9.73 25.69-15.07 53.52-15.07 82.58 0 7.56.36 15.06 1.07 22.49-25.91 2.3-53.36 3.5-80.76 3.5-179.91 0-287.59-50.19-287.59-77.96v-83.48zm287.59 319.09c-180.18 0-287.59-51.93-287.59-79.69v-83.47c63.38 37.94 175.93 57.48 287.59 57.48 31.06 0 62.22-1.48 91.44-4.28 12.39 37.68 33.75 70.34 60.88 96.51-45.72 8.68-98.78 13.46-152.33 13.46zm313.58 0c-100.74 0-181.91-82.83-181.91-183.64 0-100.31 81.6-181.91 181.91-181.91 100.3 0 181.91 81.61 181.91 181.91 0 101.26-81.61 183.64-181.91 183.64z" fill="#c3ced8"/></svg>';
