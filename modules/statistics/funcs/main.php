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
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
$canonicalUrl = getCanonicalUrl($page_url, true, true);

$current_month_num = date('n', NV_CURRENTTIME);
$current_year = date('Y', NV_CURRENTTIME);
$current_day = date('j', NV_CURRENTTIME);
$current_number_of_days = date('t', NV_CURRENTTIME);

$monthlist = array_map('trim', explode(',', $lang_module['statbyday_of_months']));
$current_month_str = $monthlist[((int) $current_month_num - 1)];

//Thong ke theo nam
$total = 0;
$year_list = [];
$result = $db->query('SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='year' ORDER BY c_val");
while (list($year, $count) = $result->fetch(3)) {
    $year_list[$year] = $count;
    $total = $total + $count;
}

$ctsy = [];
$ctsy['caption'] = $lang_module['statbyyear'];
$ctsy['total'] = number_format($total, 0, ',', '.');
$ctsy['dataLabel'] = implode('_', array_keys($year_list));
$ctsy['dataValue'] = implode('_', $year_list);

// theo thang
$month_list = [];
$month_list['Jan'] = ['fullname' => $lang_global['january'], 'count' => 0];
$month_list['Feb'] = ['fullname' => $lang_global['february'], 'count' => 0];
$month_list['Mar'] = ['fullname' => $lang_global['march'], 'count' => 0];
$month_list['Apr'] = ['fullname' => $lang_global['april'], 'count' => 0];
$month_list['May'] = ['fullname' => $lang_global['may'], 'count' => 0];
$month_list['Jun'] = ['fullname' => $lang_global['june'], 'count' => 0];
$month_list['Jul'] = ['fullname' => $lang_global['july'], 'count' => 0];
$month_list['Aug'] = ['fullname' => $lang_global['august'], 'count' => 0];
$month_list['Sep'] = ['fullname' => $lang_global['september'], 'count' => 0];
$month_list['Oct'] = ['fullname' => $lang_global['october'], 'count' => 0];
$month_list['Nov'] = ['fullname' => $lang_global['november'], 'count' => 0];
$month_list['Dec'] = ['fullname' => $lang_global['december'], 'count' => 0];

$month_list2 = array_chunk($month_list, $current_month_num, true);
$month_list2 = $month_list2[0];
$month_list2 = "'" . implode("','", array_keys($month_list2)) . "'";

$total = 0;
$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='month' AND c_val IN (" . $month_list2 . ')';
$result = $db->query($sql);
while (list($month, $count) = $result->fetch(3)) {
    $month_list[$month]['count'] = $count;
    $total = $total + $count;
}

$data_label = [];
$data_value = [];
foreach ($month_list as $m) {
    $data_label[] = $m['fullname'];
    $data_value[] = $m['count'];
}

$ctsm = [];
$ctsm['caption'] = sprintf($lang_module['statbymonth'], $current_year);
$ctsm['total'] = number_format($total, 0, ',', '.');
$ctsm['dataLabel'] = implode('_', $data_label);
$ctsm['dataValue'] = implode('_', $data_value);

// ngay trong thang
$total = 0;
$day_list = [];
$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='day' AND c_val <= " . $current_number_of_days . ' ORDER BY c_val';
$result = $db->query($sql);
while (list($day, $count) = $result->fetch(3)) {
    $day_list[$day] = $count;
    $total = $total + $count;
}

$ctsdm = [];
$ctsdm['caption'] = sprintf($lang_module['statbyday'], $current_month_str, $current_year);
$ctsdm['total'] = number_format($total, 0, ',', '.');
$ctsdm['dataLabel'] = implode('_', array_keys($day_list));
$ctsdm['dataValue'] = implode('_', $day_list);

// ngay trong tuan
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
$total = 0;
while (list($dayofweek, $count) = $result->fetch(3)) {
    $dayofweek_list[$dayofweek]['count'] = $count;
    $total = $total + $count;
}

$data_label = [];
$data_value = [];
foreach ($dayofweek_list as $m) {
    $data_label[] = $m['fullname'];
    $data_value[] = $m['count'];
}

$ctsdw = [];
$ctsdw['caption'] = $lang_module['statbydayofweek'];
$ctsdw['total'] = number_format($total, 0, ',', '.');
$ctsdw['dataLabel'] = implode('_', $data_label);
$ctsdw['dataValue'] = implode('_', $data_value);

// Giờ trong ngày
$total = 0;
$hour_list = [];

$sql = 'SELECT c_val,c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='hour' ORDER BY c_val";
$result = $db->query($sql);
while (list($hour, $count) = $result->fetch(3)) {
    $hour_list[$hour] = $count;
    $total = $total + $count;
}

$ctsh = [];
$ctsh['caption'] = $lang_module['statbyhour'] . ' (' . date('d/m/Y', NV_CURRENTTIME) . ')';
$ctsh['total'] = number_format($total, 0, ',', '.');
$ctsh['dataLabel'] = implode('_', array_keys($hour_list));
$ctsh['dataValue'] = implode('_', $hour_list);

// quoc gia
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='country' AND c_count!=0")->order('c_count DESC')->limit(10);
$result = $db->query($db->sql());

$total = 0;
$countries_list = [];
while (list($country, $count, $last_visit) = $result->fetch(3)) {
    $countries_list[] = [
        'key' => $country,
        'name' => ($country != 'ZZ' and isset($countries[$country])) ? (isset($lang_global['country_' . $country]) ? $lang_global['country_' . $country] : $countries[$country][1]) : $lang_global['unknown'],
        'count' => $count,
        'count_format' => !empty($count) ? number_format($count) : 0,
        'last_visit' => !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : ''
    ];

    $total = $total + $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='country'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctsc = [];
$ctsc['rows'] = $countries_list;
$ctsc['max'] = $max;
$ctsc['others'] = number_format($others, 0, ',', '.');
$ctsc['others_url'] = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allcountries'];

// trinh duyet
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='browser' AND c_count!=0")->order('c_count DESC');
$result = $db->query($db->sql());

$total = 0;
$browsers_list = [];
while (list($browser, $count, $last_visit) = $result->fetch(3)) {
    $const = 'BROWSER_' . strtoupper($browser);
    $name = $browser != 'Unknown' ? (defined($const) ? constant($const) : ucfirst($browser)) : $lang_global['unknown'];
    $browsers_list[] = [
        'name' => $name,
        'count' => $count,
        'count_format' => !empty($count) ? number_format($count) : 0,
        'last_visit' => !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : ''
    ];

    $total = $total + $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='browser'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctsb = [];
$ctsb['rows'] = $browsers_list;
$ctsb['max'] = $max;
$ctsb['others'] = number_format($others, 0, ',', '.');
$ctsb['others_url'] = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allbrowsers'];

// he dieu hanh
$db->sqlreset()->select('c_val,c_count, last_update')->from(NV_COUNTER_GLOBALTABLE)->where("c_type='os' AND c_count!=0")->order('c_count DESC')->limit(10);
$result = $db->query($db->sql());

$total = 0;
$os_list = [];

while (list($os, $count, $last_visit) = $result->fetch(3)) {
    $const = 'PLATFORM_' . strtoupper($os);
    $name = $os != 'unknown' ? (defined($const) ? constant($const) : ucfirst($os)) : $lang_global['unknown'];

    $os_list[] = [
        'name' => $name,
        'count' => $count,
        'count_format' => !empty($count) ? number_format($count) : 0,
        'last_visit' => !empty($last_visit) ? nv_date('l, d F Y H:i', $last_visit) : ''
    ];

    $total = $total + $count;
}

$result = $db->query('SELECT SUM(c_count), MAX(c_count) FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type='os'");
list($all, $max) = $result->fetch(3);
$others = $all - $total;

$ctso = [];
$ctso['rows'] = $os_list;
$ctso['max'] = $max;
$ctso['others'] = number_format($others, 0, ',', '.');
$ctsb['others_url'] = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allos'];

$contents = nv_theme_statistics_main($ctsy, $ctsm, $ctsdm, $ctsdw, $ctsc, $ctsb, $ctso, $ctsh);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
