<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
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
$mod_title = $module_info['custom_title'];

$current_month_num = date('n', NV_CURRENTTIME);
$current_year = date('Y', NV_CURRENTTIME);
$current_day = date('j', NV_CURRENTTIME);
$current_number_of_days = date('t', NV_CURRENTTIME);
$current_dayofweek = date('l', NV_CURRENTTIME);

//Thong ke theo nam
$max = 0;
$total = 0;
$year_list = [];
$result = $db->query('SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='year' ORDER BY c_val");
while (list($year, $count) = $result->fetch(3)) {
    $year_list[$year] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsy = [];
$ctsy['caption'] = $nv_Lang->getModule('statbyyear');
$ctsy['rows'] = $year_list;
$ctsy['current_year'] = $current_year;
$ctsy['max'] = $max;
$ctsy['total'] = [$nv_Lang->getGlobal('total'), number_format($total, 0, ',', '.')];

// theo thang
$month_list = [];
$month_list['Jan'] = ['fullname' => $nv_Lang->getGlobal('january'), 'count' => 0];
$month_list['Feb'] = ['fullname' => $nv_Lang->getGlobal('february'), 'count' => 0];
$month_list['Mar'] = ['fullname' => $nv_Lang->getGlobal('march'), 'count' => 0];
$month_list['Apr'] = ['fullname' => $nv_Lang->getGlobal('april'), 'count' => 0];
$month_list['May'] = ['fullname' => $nv_Lang->getGlobal('may'), 'count' => 0];
$month_list['Jun'] = ['fullname' => $nv_Lang->getGlobal('june'), 'count' => 0];
$month_list['Jul'] = ['fullname' => $nv_Lang->getGlobal('july'), 'count' => 0];
$month_list['Aug'] = ['fullname' => $nv_Lang->getGlobal('august'), 'count' => 0];
$month_list['Sep'] = ['fullname' => $nv_Lang->getGlobal('september'), 'count' => 0];
$month_list['Oct'] = ['fullname' => $nv_Lang->getGlobal('october'), 'count' => 0];
$month_list['Nov'] = ['fullname' => $nv_Lang->getGlobal('november'), 'count' => 0];
$month_list['Dec'] = ['fullname' => $nv_Lang->getGlobal('december'), 'count' => 0];

$month_list2 = array_chunk($month_list, $current_month_num, true);
$month_list2 = $month_list2[0];
$month_list2 = "'" . implode("','", array_keys($month_list2)) . "'";

$max = 0;
$total = 0;

$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='month' AND c_val IN (" . $month_list2 . ')';
$result = $db->query($sql);
while (list($month, $count) = $result->fetch(3)) {
    $month_list[$month]['count'] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsm = [];
$ctsm['caption'] = sprintf($nv_Lang->getModule('statbymoth'), $current_year);
$ctsm['rows'] = $month_list;
$ctsm['current_month'] = date('M', NV_CURRENTTIME);
$ctsm['max'] = $max;
$ctsm['total'] = [$nv_Lang->getGlobal('total'), number_format($total, 0, ',', '.')];

// ngay trong thang

$max = 0;
$total = 0;
$day_list = [];

$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='day' AND c_val <= " . $current_number_of_days . ' ORDER BY c_val';
$result = $db->query($sql);
while (list($day, $count) = $result->fetch(3)) {
    $day_list[$day] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsdm = [];
$ctsdm['caption'] = sprintf($nv_Lang->getModule('statbyday'), $current_month_num);
$ctsdm['rows'] = $day_list;
$ctsdm['current_day'] = $current_day;
$ctsdm['max'] = $max;
$ctsdm['total'] = [$nv_Lang->getGlobal('total'), number_format($total, 0, ',', '.')];
$ctsdm['numrows'] = $current_number_of_days;

// ngay trong tuan
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

$max = 0;
$total = 0;

while (list($dayofweek, $count) = $result->fetch(3)) {
    $dayofweek_list[$dayofweek]['count'] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsdw = [];
$ctsdw['caption'] = $nv_Lang->getModule('statbydayofweek');
$ctsdw['rows'] = $dayofweek_list;
$ctsdw['current_dayofweek'] = $current_dayofweek;
$ctsdw['max'] = $max;
$ctsdw['total'] = [$nv_Lang->getGlobal('total'), number_format($total, 0, ',', '.')];

// Giờ trong ngày
$max = 0;
$total = 0;
$hour_list = [];

$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='hour' ORDER BY c_val";
$result = $db->query($sql);
while (list($hour, $count) = $result->fetch(3)) {
    $hour_list[$hour] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsh = [];
$ctsh['caption'] = $nv_Lang->getModule('statbyhour');
$ctsh['rows'] = $hour_list;
$ctsh['current_hour'] = date('H', NV_CURRENTTIME);
$ctsh['max'] = $max;
$ctsh['total'] = [$nv_Lang->getGlobal('total'), number_format($total, 0, ',', '.')];

// quoc gia
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='country' AND c_count!=0")->order('c_count DESC')->limit(10);
$result = $db->query($db->sql());

$total = 0;
$countries_list = [];
while (list($country, $count, $last_visit) = $result->fetch(3)) {
    $fullname = isset($countries[$country]) ? $countries[$country][1] : $nv_Lang->getGlobal('unknown');
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
$ctsc['caption'] = $nv_Lang->getModule('statbycountry');
$ctsc['thead'] = [
    $nv_Lang->getModule('country'),
    $nv_Lang->getModule('hits'),
    $nv_Lang->getModule('last_visit')
];
$ctsc['rows'] = $countries_list;
$ctsc['max'] = $max;
$ctsc['others'] = [
    $nv_Lang->getModule('others'),
    number_format($others, 0, ',', '.'),
    $nv_Lang->getModule('viewall')
];

// trinh duyet
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='browser' AND c_count!=0")->order('c_count DESC');
$result = $db->query($db->sql());

$total = 0;
$browsers_list = [];

while (list($browser, $count, $last_visit) = $result->fetch(3)) {
    $last_visit = !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '';
    $browsers_list[$browser] = [$count, $last_visit];

    $total = $total + $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='browser'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctsb = [];
$ctsb['caption'] = $nv_Lang->getModule('statbybrowser');
$ctsb['thead'] = [
    $nv_Lang->getModule('browser'),
    $nv_Lang->getModule('hits'),
    $nv_Lang->getModule('last_visit')
];
$ctsb['rows'] = $browsers_list;
$ctsb['max'] = $max;
$ctsb['others'] = [
    $nv_Lang->getModule('others'),
    number_format($others, 0, ',', '.'),
    $nv_Lang->getModule('viewall')
];

// he dieu hanh
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='os' AND c_count!=0")->order('c_count DESC')->limit(10);
$result = $db->query($db->sql());

$total = 0;
$os_list = [];

while (list($os, $count, $last_visit) = $result->fetch(3)) {
    $last_visit = !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '';
    $os_list[$os] = [$count, $last_visit];

    $total = $total + $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='os'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctso = [];
$ctso['caption'] = $nv_Lang->getModule('statbyos');
$ctso['thead'] = [
    $nv_Lang->getModule('os'),
    $nv_Lang->getModule('hits'),
    $nv_Lang->getModule('last_visit')
];
$ctso['rows'] = $os_list;
$ctso['max'] = $max;
$ctso['others'] = [
    $nv_Lang->getModule('others'),
    number_format($others, 0, ',', '.'),
    $nv_Lang->getModule('viewall')
];

$contents = nv_theme_statistics_main($ctsy, $ctsm, $ctsdm, $ctsdw, $ctsc, $ctsb, $ctso, $ctsh);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
