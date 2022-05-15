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

    $process = $data = [];

    $result = $db->query('SELECT a.' . $onetype . ' FROM ' . NV_BANNERS_GLOBALTABLE . '_click a INNER JOIN ' . NV_BANNERS_GLOBALTABLE . '_rows b ON a.bid=b.id WHERE b.clid= ' . $user_info['userid'] . ' AND a.click_time <= ' . $enddate . ' AND a.click_time >= ' . $firstdate . ' AND a.bid=' . $ads . ' ORDER BY click_time ASC');
    while ($row = $result->fetch()) {
        if ($type == 'date') {
            $row[$onetype] = date('d/m', $row[$onetype]);
        }
        $data[] = $row[$onetype];
    }
    if (sizeof($data) > 0) {
        $statics = array_count_values($data);
        $total = array_sum($statics);

        foreach ($statics as $country => $quantity) {
            if ($type == 'date') {
                $process[$country . '(' . $quantity . ' click)'] = $quantity;
            } else {
                $process[$country . '(' . round((((int) $quantity * 100) / $total), 2) . '%)'] = round((((int) $quantity * 100) / $total), 2);
            }
        }

        // google chart intergrated :|
        $imagechart = 'http://chart.apis.google.com/chart?chs=700x350&cht=p3&chco=7777CC|76A4FB|3399CC|3366CC|000000|7D5F5F|A94A4A|13E9E9|526767|DBD6D6&chd=t:';
        $imagechart .= implode(',', array_values($process));
        $imagechart .= '&chl=';
        $imagechart .= implode('|', array_keys($process));
        $imagechart .= '&chtt=Banner Stats';
        $imagechart = str_replace(' ', '%20', $imagechart);
        header('Content-type: image/png');
        echo file_get_contents($imagechart);
    } else {
        $my_img = imagecreate(700, 80);
        $background = imagecolorallocate($my_img, 255, 255, 255);
        $text_colour = imagecolorallocate($my_img, 0, 0, 0);
        $line_colour = imagecolorallocate($my_img, 128, 255, 0);
        imagestring($my_img, 4, 30, 25, 'no data', $text_colour);
        imagesetthickness($my_img, 5);
        imageline($my_img, 30, 45, 165, 45, $line_colour);

        header('Content-type: image/png');
        imagepng($my_img);
        imagecolordeallocate($my_img, $line_colour);
        imagecolordeallocate($my_img, $text_colour);
        imagecolordeallocate($my_img, $background);
        imagedestroy($my_img);
    }
}
