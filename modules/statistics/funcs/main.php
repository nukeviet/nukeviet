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

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
$canonicalUrl = getCanonicalUrl($page_url);

$current_month_num = date('n', NV_CURRENTTIME);
$current_year = date('Y', NV_CURRENTTIME);
$current_day = date('j', NV_CURRENTTIME);
$current_number_of_days = date('t', NV_CURRENTTIME);
$current_dayofweek = date('l', NV_CURRENTTIME);
$current_hour = (int) date('G', NV_CURRENTTIME);

// Thống kê theo năm
$max = 0;
$total = 0;
$year_list = [];
$result = $db->query('SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='year' ORDER BY c_val");
while ($_scratch = $result->fetch(3)) {
    list($year, $count) = $_scratch;
    unset($_scratch);
    $year_list[$year] = $current_year < $year ? null : $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsy = [];
$ctsy['caption'] = $lang_module['statbyyear'];
$ctsy['rows'] = $year_list;
$ctsy['current_year'] = $current_year;
$ctsy['max'] = $max;
$ctsy['total'] = [$lang_global['total'], number_format($total, 0, ',', '.')];

// Thống kê theo tháng
$month_list = [];
$month_list['Jan'] = ['fullname' => $lang_global['january'], 'count' => 0];
$month_list['Feb'] = ['fullname' => $lang_global['february'], 'count' => $current_month_num < 2 ? null : 0];
$month_list['Mar'] = ['fullname' => $lang_global['march'], 'count' => $current_month_num < 3 ? null : 0];
$month_list['Apr'] = ['fullname' => $lang_global['april'], 'count' => $current_month_num < 4 ? null : 0];
$month_list['May'] = ['fullname' => $lang_global['may'], 'count' => $current_month_num < 5 ? null : 0];
$month_list['Jun'] = ['fullname' => $lang_global['june'], 'count' => $current_month_num < 6 ? null : 0];
$month_list['Jul'] = ['fullname' => $lang_global['july'], 'count' => $current_month_num < 7 ? null : 0];
$month_list['Aug'] = ['fullname' => $lang_global['august'], 'count' => $current_month_num < 8 ? null : 0];
$month_list['Sep'] = ['fullname' => $lang_global['september'], 'count' => $current_month_num < 9 ? null : 0];
$month_list['Oct'] = ['fullname' => $lang_global['october'], 'count' => $current_month_num < 10 ? null : 0];
$month_list['Nov'] = ['fullname' => $lang_global['november'], 'count' => $current_month_num < 11 ? null : 0];
$month_list['Dec'] = ['fullname' => $lang_global['december'], 'count' => $current_month_num < 12 ? null : 0];

$month_list2 = array_chunk($month_list, $current_month_num, true);
$month_list2 = $month_list2[0];
$month_list2 = "'" . implode("','", array_keys($month_list2)) . "'";

$max = 0;
$total = 0;

$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='month' AND c_val IN (" . $month_list2 . ')';
$result = $db->query($sql);
while ($_scratch = $result->fetch(3)) {
    list($month, $count) = $_scratch;
    unset($_scratch);
    $month_list[$month]['count'] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsm = [];
$ctsm['caption'] = sprintf($lang_module['statbymoth'], $current_year);
$ctsm['rows'] = $month_list;
$ctsm['current_month'] = date('M', NV_CURRENTTIME);
$ctsm['max'] = $max;
$ctsm['total'] = [$lang_global['total'], number_format($total, 0, ',', '.')];

// Thống kê theo ngày trong tháng
$max = 0;
$total = 0;
$day_list = [];

$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='day' AND c_val <= " . $current_number_of_days . ' ORDER BY c_val';
$result = $db->query($sql);
while ($_scratch = $result->fetch(3)) {
    list($day, $count) = $_scratch;
    unset($_scratch);
    $day_list[$day] = $day <= $current_day ? $count : null;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsdm = [];
$ctsdm['caption'] = sprintf($lang_module['statbyday'], $current_month_num);
$ctsdm['rows'] = $day_list;
$ctsdm['current_day'] = $current_day;
$ctsdm['max'] = $max;
$ctsdm['total'] = [$lang_global['total'], number_format($total, 0, ',', '.')];
$ctsdm['numrows'] = $current_number_of_days;

// Ngày trong tuần
$dayofweek_list = [];
$dayofweek_list['Sunday'] = ['fullname' => $lang_global['sunday'], 'count' => 0];
$dayofweek_list['Monday'] = ['fullname' => $lang_global['monday'], 'count' => 0];
$dayofweek_list['Tuesday'] = ['fullname' => $lang_global['tuesday'], 'count' => 0];
$dayofweek_list['Wednesday'] = ['fullname' => $lang_global['wednesday'], 'count' => 0];
$dayofweek_list['Thursday'] = ['fullname' => $lang_global['thursday'], 'count' => 0];
$dayofweek_list['Friday'] = ['fullname' => $lang_global['friday'], 'count' => 0];
$dayofweek_list['Saturday'] = ['fullname' => $lang_global['saturday'], 'count' => 0];

$dayofweek_list2 = "'" . implode("','", array_keys($dayofweek_list)) . "'";

$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='dayofweek' AND c_val IN (" . $dayofweek_list2 . ')';
$result = $db->query($sql);

$max = 0;
$total = 0;

while ($_scratch = $result->fetch(3)) {
    list($dayofweek, $count) = $_scratch;
    unset($_scratch);
    $dayofweek_list[$dayofweek]['count'] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsdw = [];
$ctsdw['caption'] = $lang_module['statbydayofweek'];
$ctsdw['rows'] = $dayofweek_list;
$ctsdw['current_dayofweek'] = $current_dayofweek;
$ctsdw['max'] = $max;
$ctsdw['total'] = [$lang_global['total'], number_format($total, 0, ',', '.')];

// Giờ trong ngày
$max = 0;
$total = 0;
$hour_list = [];

$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='hour' ORDER BY c_val";
$result = $db->query($sql);
while ($_scratch = $result->fetch(3)) {
    list($hour, $count) = $_scratch;
    unset($_scratch);
    $hour_list[$hour] = $hour > $current_hour ? null : $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsh = [];
$ctsh['caption'] = $lang_module['statbyhour'];
$ctsh['rows'] = $hour_list;
$ctsh['current_hour'] = date('H', NV_CURRENTTIME);
$ctsh['max'] = $max;
$ctsh['total'] = [$lang_global['total'], number_format($total, 0, ',', '.')];

// Theo quốc gia
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='country' AND c_count!=0")->order('c_count DESC')->limit(10);
$result = $db->query($db->sql());

$total = 0;
$countries_list = [];
while ($_scratch = $result->fetch(3)) {
    list($country, $count, $last_visit) = $_scratch;
    unset($_scratch);
    $fullname = isset($countries[$country]) ? $countries[$country][1] : $lang_global['unknown'];
    $last_visit = !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '';
    $countries_list[$country] = [
        $fullname,
        $count,
        $last_visit
    ];

    $total = $total + $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='country'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctsc = [];
$ctsc['caption'] = $lang_module['statbycountry'];
$ctsc['thead'] = [
    $lang_module['country'],
    $lang_module['hits'],
    $lang_module['last_visit']
];
$ctsc['rows'] = $countries_list;
$ctsc['max'] = $max;
$ctsc['others'] = [
    $lang_module['others'],
    number_format($others, 0, ',', '.'),
    $lang_module['viewall']
];

// Theo trình duyệt
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='browser' AND c_count!=0")->order('c_count DESC');
$result = $db->query($db->sql());

$total = 0;
$browsers_list = [];

while ($_scratch = $result->fetch(3)) {
    list($br, $count, $last_visit) = $_scratch;
    unset($_scratch);
    $last_visit = !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '';
    $browsers_list[$br] = [$count, $last_visit];

    $total = $total + $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='browser'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctsb = [];
$ctsb['caption'] = $lang_module['statbybrowser'];
$ctsb['thead'] = [
    $lang_module['browser'],
    $lang_module['hits'],
    $lang_module['last_visit']
];
$ctsb['rows'] = $browsers_list;
$ctsb['max'] = $max;
$ctsb['others'] = [
    $lang_module['others'],
    number_format($others, 0, ',', '.'),
    $lang_module['viewall']
];

// Theo hệ điều hành
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='os' AND c_count!=0")->order('c_count DESC')->limit(10);
$result = $db->query($db->sql());

$total = 0;
$os_list = [];

while ($_scratch = $result->fetch(3)) {
    list($os, $count, $last_visit) = $_scratch;
    unset($_scratch);
    $last_visit = !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '';
    $os_list[$os] = [$count, $last_visit];

    $total = $total + $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='os'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctso = [];
$ctso['caption'] = $lang_module['statbyos'];
$ctso['thead'] = [
    $lang_module['os'],
    $lang_module['hits'],
    $lang_module['last_visit']
];
$ctso['rows'] = $os_list;
$ctso['max'] = $max;
$ctso['others'] = [
    $lang_module['others'],
    number_format($others, 0, ',', '.'),
    $lang_module['viewall']
];

$contents = nv_theme_statistics_main($ctsy, $ctsm, $ctsdm, $ctsdw, $ctsc, $ctsb, $ctso, $ctsh);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
