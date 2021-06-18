<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 14/6/2010, 16:59
 */

if (!defined('NV_IS_MOD_STATISTICS')) {
    die('Stop!!!');
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];

$current_month_num = date('n', NV_CURRENTTIME);
$current_year = date('Y', NV_CURRENTTIME);
$current_day = date('j', NV_CURRENTTIME);
$current_number_of_days = date('t', NV_CURRENTTIME);
$current_dayofweek = date('l', NV_CURRENTTIME);

//Thong ke theo nam
$max = 0;
$total = 0;
$year_list = array();
$result = $db->query("SELECT c_val,c_count FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type='year' ORDER BY c_val");
while (list($year, $count) = $result->fetch(3)) {
    $year_list[$year] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsy = array();
$ctsy['caption'] = $lang_module['statbyyear'];
$ctsy['rows'] = $year_list;
$ctsy['current_year'] = $current_year;
$ctsy['max'] = $max;
$ctsy['total'] = array($lang_global['total'], number_format($total, 0, ',', '.'));

// theo thang
$month_list = array();
$month_list['Jan'] = array('fullname' => $lang_global['january'], 'count' => 0);
$month_list['Feb'] = array('fullname' => $lang_global['february'], 'count' => 0);
$month_list['Mar'] = array('fullname' => $lang_global['march'], 'count' => 0);
$month_list['Apr'] = array('fullname' => $lang_global['april'], 'count' => 0);
$month_list['May'] = array('fullname' => $lang_global['may'], 'count' => 0);
$month_list['Jun'] = array('fullname' => $lang_global['june'], 'count' => 0);
$month_list['Jul'] = array('fullname' => $lang_global['july'], 'count' => 0);
$month_list['Aug'] = array('fullname' => $lang_global['august'], 'count' => 0);
$month_list['Sep'] = array('fullname' => $lang_global['september'], 'count' => 0);
$month_list['Oct'] = array('fullname' => $lang_global['october'], 'count' => 0);
$month_list['Nov'] = array('fullname' => $lang_global['november'], 'count' => 0);
$month_list['Dec'] = array('fullname' => $lang_global['december'], 'count' => 0);

$month_list2 = array_chunk($month_list, $current_month_num, true);
$month_list2 = $month_list2[0];
$month_list2 = "'" . implode("','", array_keys($month_list2)) . "'";


$max = 0;
$total = 0;

$sql = "SELECT c_val,c_count FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type='month' AND c_val IN (" . $month_list2 . ")";
$result = $db->query($sql);
while (list($month, $count) = $result->fetch(3)) {
    $month_list[$month]['count'] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsm = array();
$ctsm['caption'] = sprintf($lang_module['statbymoth'], $current_year);
$ctsm['rows'] = $month_list;
$ctsm['current_month'] = date('M', NV_CURRENTTIME);
$ctsm['max'] = $max;
$ctsm['total'] = array($lang_global['total'], number_format($total, 0, ',', '.'));

// ngay trong thang

$max = 0;
$total = 0;
$day_list = array();

$sql = "SELECT c_val,c_count FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type='day' AND c_val <= " . $current_number_of_days . " ORDER BY c_val";
$result = $db->query($sql);
while (list($day, $count) = $result->fetch(3)) {
    $day_list[$day] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsdm = array();
$ctsdm['caption'] = sprintf($lang_module['statbyday'], $current_month_num);
$ctsdm['rows'] = $day_list;
$ctsdm['current_day'] = $current_day;
$ctsdm['max'] = $max;
$ctsdm['total'] = array($lang_global['total'], number_format($total, 0, ',', '.'));
$ctsdm['numrows'] = $current_number_of_days;

// ngay trong tuan
$dayofweek_list = array();
$dayofweek_list['Sunday'] = array('fullname' => $lang_global['sunday'], 'count' => 0);
$dayofweek_list['Monday'] = array('fullname' => $lang_global['monday'], 'count' => 0);
$dayofweek_list['Tuesday'] = array('fullname' => $lang_global['tuesday'], 'count' => 0);
$dayofweek_list['Wednesday'] = array('fullname' => $lang_global['wednesday'], 'count' => 0);
$dayofweek_list['Thursday'] = array('fullname' => $lang_global['thursday'], 'count' => 0);
$dayofweek_list['Friday'] = array('fullname' => $lang_global['friday'], 'count' => 0);
$dayofweek_list['Saturday'] = array('fullname' => $lang_global['saturday'], 'count' => 0);

$dayofweek_list2 = "'" . implode("','", array_keys($dayofweek_list)) . "'";

$sql = "SELECT c_val,c_count FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type='dayofweek' AND c_val IN (" . $dayofweek_list2 . ")";
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

$ctsdw = array();
$ctsdw['caption'] = $lang_module['statbydayofweek'];
$ctsdw['rows'] = $dayofweek_list;
$ctsdw['current_dayofweek'] = $current_dayofweek;
$ctsdw['max'] = $max;
$ctsdw['total'] = array($lang_global['total'], number_format($total, 0, ',', '.'));

// Giờ trong ngày
$max = 0;
$total = 0;
$hour_list = array();

$sql = "SELECT c_val,c_count FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type='hour' ORDER BY c_val";
$result = $db->query($sql);
while (list($hour, $count) = $result->fetch(3)) {
    $hour_list[$hour] = $count;
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
}

$ctsh = array();
$ctsh['caption'] = $lang_module['statbyhour'];
$ctsh['rows'] = $hour_list;
$ctsh['current_hour'] = date('H', NV_CURRENTTIME);
$ctsh['max'] = $max;
$ctsh['total'] = array($lang_global['total'], number_format($total, 0, ',', '.'));

// quoc gia
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='country' AND c_count!=0")->order('c_count DESC')->limit(10);
$result = $db->query($db->sql());

$total = 0;
$countries_list = array();
while (list($country, $count, $last_visit) = $result->fetch(3)) {
    $fullname = isset($countries[$country]) ? $countries[$country][1] : $lang_global['unknown'];
    $last_visit = !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '';
    $countries_list[$country] = array(
        $fullname,
        $count,
        $last_visit
    );

    $total = $total + $count;
}

$result = $db->query("SELECT SUM(c_count), MAX(c_count) FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type='country'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctsc = array();
$ctsc['caption'] = $lang_module['statbycountry'];
$ctsc['thead'] = array(
    $lang_module['country'],
    $lang_module['hits'],
    $lang_module['last_visit']
);
$ctsc['rows'] = $countries_list;
$ctsc['max'] = $max;
$ctsc['others'] = array(
    $lang_module['others'],
    number_format($others, 0, ',', '.'),
    $lang_module['viewall']
);

// trinh duyet
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='browser' AND c_count!=0")->order('c_count DESC');
$result = $db->query($db->sql());

$total = 0;
$browsers_list = array();

while (list($browser, $count, $last_visit) = $result->fetch(3)) {
    $last_visit = !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '';
    $browsers_list[$browser] = array($count, $last_visit);

    $total = $total + $count;
}

$result = $db->query("SELECT SUM(c_count), MAX(c_count) FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type='browser'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctsb = array();
$ctsb['caption'] = $lang_module['statbybrowser'];
$ctsb['thead'] = array(
    $lang_module['browser'],
    $lang_module['hits'],
    $lang_module['last_visit']
);
$ctsb['rows'] = $browsers_list;
$ctsb['max'] = $max;
$ctsb['others'] = array(
    $lang_module['others'],
    number_format($others, 0, ',', '.'),
    $lang_module['viewall']
);

// he dieu hanh
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='os' AND c_count!=0")->order('c_count DESC')->limit(10);
$result = $db->query($db->sql());

$total = 0;
$os_list = array();

while (list($os, $count, $last_visit) = $result->fetch(3)) {
    $last_visit = !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : '';
    $os_list[$os] = array($count, $last_visit);

    $total = $total + $count;
}

$result = $db->query("SELECT SUM(c_count), MAX(c_count) FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type='os'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctso = array();
$ctso['caption'] = $lang_module['statbyos'];
$ctso['thead'] = array(
    $lang_module['os'],
    $lang_module['hits'],
    $lang_module['last_visit']
);
$ctso['rows'] = $os_list;
$ctso['max'] = $max;
$ctso['others'] = array(
    $lang_module['others'],
    number_format($others, 0, ',', '.'),
    $lang_module['viewall']
);

$contents = nv_theme_statistics_main($ctsy, $ctsm, $ctsdm, $ctsdw, $ctsc, $ctsb, $ctso, $ctsh);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
