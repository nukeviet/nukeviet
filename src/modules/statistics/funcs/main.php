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

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
$canonicalUrl = getCanonicalUrl($page_url);

$current_month_num = date('n', NV_CURRENTTIME);
$current_year = date('Y', NV_CURRENTTIME);
$current_day = date('j', NV_CURRENTTIME);
$current_number_of_days = date('t', NV_CURRENTTIME);
$current_hour = (int) date('G', NV_CURRENTTIME);

$monthlist = array_map('trim', explode(',', $nv_Lang->getModule('statbyday_of_months')));
$current_month_str = $monthlist[((int) $current_month_num - 1)];

// Thống kê theo năm
$total = 0;
$year_list = [];
$result = $db->query('SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='year' ORDER BY c_val");
while ([$year, $count] = $result->fetch(3)) {
    $year_list[$year] = $current_year < $year ? null : $count;
    $total += $count;
}

$ctsy = [];
$ctsy['caption'] = $nv_Lang->getModule('statbyyear');
$ctsy['total'] = nv_number_format($total);
$ctsy['dataLabel'] = implode('_', array_keys($year_list));
$ctsy['dataValue'] = implode('_', $year_list);

// Thống kê theo tháng của năm
$month_list = [];
$month_list['Jan'] = ['fullname' => $nv_Lang->getGlobal('january'), 'count' => 0];
$month_list['Feb'] = ['fullname' => $nv_Lang->getGlobal('february'), 'count' => $current_month_num < 2 ? null : 0];
$month_list['Mar'] = ['fullname' => $nv_Lang->getGlobal('march'), 'count' => $current_month_num < 3 ? null : 0];
$month_list['Apr'] = ['fullname' => $nv_Lang->getGlobal('april'), 'count' => $current_month_num < 4 ? null : 0];
$month_list['May'] = ['fullname' => $nv_Lang->getGlobal('may'), 'count' => $current_month_num < 5 ? null : 0];
$month_list['Jun'] = ['fullname' => $nv_Lang->getGlobal('june'), 'count' => $current_month_num < 6 ? null : 0];
$month_list['Jul'] = ['fullname' => $nv_Lang->getGlobal('july'), 'count' => $current_month_num < 7 ? null : 0];
$month_list['Aug'] = ['fullname' => $nv_Lang->getGlobal('august'), 'count' => $current_month_num < 8 ? null : 0];
$month_list['Sep'] = ['fullname' => $nv_Lang->getGlobal('september'), 'count' => $current_month_num < 9 ? null : 0];
$month_list['Oct'] = ['fullname' => $nv_Lang->getGlobal('october'), 'count' => $current_month_num < 10 ? null : 0];
$month_list['Nov'] = ['fullname' => $nv_Lang->getGlobal('november'), 'count' => $current_month_num < 11 ? null : 0];
$month_list['Dec'] = ['fullname' => $nv_Lang->getGlobal('december'), 'count' => $current_month_num < 12 ? null : 0];

$month_list2 = array_chunk($month_list, $current_month_num, true);
$month_list2 = $month_list2[0];
$month_list2 = "'" . implode("','", array_keys($month_list2)) . "'";

$total = 0;
$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='month' AND c_val IN (" . $month_list2 . ')';
$result = $db->query($sql);
while ([$month, $count] = $result->fetch(3)) {
    $month_list[$month]['count'] = $count;
    $total += $count;
}

$data_label = [];
$data_value = [];
foreach ($month_list as $m) {
    $data_label[] = $m['fullname'];
    $data_value[] = $m['count'];
}

$ctsm = [];
$ctsm['caption'] = $nv_Lang->getModule('statbymonth', $current_year);
$ctsm['total'] = nv_number_format($total);
$ctsm['dataLabel'] = implode('_', $data_label);
$ctsm['dataValue'] = implode('_', $data_value);

// Thống kê theo ngày trong tháng
$total = 0;
$day_list = [];
$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='day' AND c_val <= " . $current_number_of_days . ' ORDER BY c_val';
$result = $db->query($sql);
while ([$day, $count] = $result->fetch(3)) {
    $day_list[$day] = $day <= $current_day ? $count : null;
    $total += $count;
}

$ctsdm = [];
$ctsdm['caption'] = $nv_Lang->getModule('statbyday', $current_month_str, $current_year);
$ctsdm['total'] = nv_number_format($total);
$ctsdm['dataLabel'] = implode('_', array_keys($day_list));
$ctsdm['dataValue'] = implode('_', $day_list);

// Ngày trong tuần
$dayofweek_list = [];
$dayofweek_list['Sunday'] = ['fullname' => $nv_Lang->getGlobal('sunday'), 'count' => 0];
$dayofweek_list['Monday'] = ['fullname' => $nv_Lang->getGlobal('monday'), 'count' => 0];
$dayofweek_list['Tuesday'] = ['fullname' => $nv_Lang->getGlobal('tuesday'), 'count' => 0];
$dayofweek_list['Wednesday'] = ['fullname' => $nv_Lang->getGlobal('wednesday'), 'count' => 0];
$dayofweek_list['Thursday'] = ['fullname' => $nv_Lang->getGlobal('thursday'), 'count' => 0];
$dayofweek_list['Friday'] = ['fullname' => $nv_Lang->getGlobal('friday'), 'count' => 0];
$dayofweek_list['Saturday'] = ['fullname' => $nv_Lang->getGlobal('saturday'), 'count' => 0];

$dayofweek_list2 = "'" . implode("','", array_keys($dayofweek_list)) . "'";
$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='dayofweek' AND c_val IN (" . $dayofweek_list2 . ')';
$result = $db->query($sql);
$total = 0;
while ([$dayofweek, $count] = $result->fetch(3)) {
    $dayofweek_list[$dayofweek]['count'] = $count;
    $total += $count;
}

$data_label = [];
$data_value = [];
foreach ($dayofweek_list as $m) {
    $data_label[] = $m['fullname'];
    $data_value[] = $m['count'];
}

$ctsdw = [];
$ctsdw['caption'] = $nv_Lang->getModule('statbydayofweek');
$ctsdw['total'] = nv_number_format($total);
$ctsdw['dataLabel'] = implode('_', $data_label);
$ctsdw['dataValue'] = implode('_', $data_value);

// Giờ trong ngày
$total = 0;
$hour_list = [];

$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='hour' ORDER BY c_val";
$result = $db->query($sql);
while ([$hour, $count] = $result->fetch(3)) {
    $hour_list[$hour] = $hour > $current_hour ? null : $count;
    $total += $count;
}

$ctsh = [];
$ctsh['caption'] = $nv_Lang->getModule('statbyhour') . ' (' . date('d/m/Y', NV_CURRENTTIME) . ')';
$ctsh['total'] = nv_number_format($total);
$ctsh['dataLabel'] = implode('_', array_keys($hour_list));
$ctsh['dataValue'] = implode('_', $hour_list);

// Theo quốc gia
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='country' AND c_count!=0")->order('c_count DESC')->limit(10);
$result = $db->query($db->sql());

$total = 0;
$countries_list = [];
while ([$country, $count, $last_visit] = $result->fetch(3)) {
    $countries_list[] = [
        'key' => $country,
        'name' => ($country != 'ZZ' and isset($countries[$country])) ? ($nv_Lang->existsGlobal('country_' . $country) ? $nv_Lang->getGlobal('country_' . $country) : $countries[$country][1]) : $nv_Lang->getGlobal('unknown'),
        'count' => $count,
        'count_format' => !empty($count) ? nv_number_format($count) : 0,
        'last_visit' => !empty($last_visit) ? nv_datetime_format($last_visit, 0, 0) : ''
    ];

    $total += $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='country'");
[$all, $max] = $result->fetch(3);
$others = $all - $total;

$ctsc = [];
$ctsc['rows'] = $countries_list;
$ctsc['max'] = $max;
$ctsc['others'] = nv_number_format($others);
$ctsc['others_url'] = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allcountries'];

// Theo trình duyệt
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='browser' AND c_count!=0")->order('c_count DESC');
$result = $db->query($db->sql());

$total = 0;
$browsers_list = [];
while ([$br, $count, $last_visit] = $result->fetch(3)) {
    $const = 'BROWSER_' . strtoupper($br);
    $name = $br != 'Unknown' ? (defined($const) ? constant($const) : ucfirst($br)) : $nv_Lang->getGlobal('unknown');
    $browsers_list[] = [
        'name' => $name,
        'count' => $count,
        'count_format' => !empty($count) ? nv_number_format($count) : 0,
        'last_visit' => !empty($last_visit) ? nv_datetime_format($last_visit, 0, 0) : ''
    ];

    $total += $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='browser'");
[$all, $max] = $result->fetch(3);
$others = $all - $total;

$ctsb = [];
$ctsb['rows'] = $browsers_list;
$ctsb['max'] = $max;
$ctsb['others'] = nv_number_format($others);
$ctsb['others_url'] = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allbrowsers'];

// Theo hệ điều hành
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='os' AND c_count!=0")->order('c_count DESC')->limit(10);
$result = $db->query($db->sql());

$total = 0;
$os_list = [];

while ([$os, $count, $last_visit] = $result->fetch(3)) {
    $const = 'PLATFORM_' . strtoupper($os);
    $name = $os != 'unknown' ? (defined($const) ? constant($const) : ucfirst($os)) : $nv_Lang->getGlobal('unknown');

    $os_list[] = [
        'name' => $name,
        'count' => $count,
        'count_format' => !empty($count) ? nv_number_format($count) : 0,
        'last_visit' => !empty($last_visit) ? nv_datetime_format($last_visit, 0, 0) : ''
    ];

    $total += $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='os'");
[$all, $max] = $result->fetch(3);
$others = $all - $total;

$ctso = [];
$ctso['rows'] = $os_list;
$ctso['max'] = $max;
$ctso['others'] = nv_number_format($others);
$ctsb['others_url'] = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allos'];

$contents = nv_theme_statistics_main($ctsy, $ctsm, $ctsdm, $ctsdw, $ctsc, $ctsb, $ctso, $ctsh);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
