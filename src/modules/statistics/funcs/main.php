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
!defined('NV_ADMIN') && $canonicalUrl = getCanonicalUrl($page_url);

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
$stmt = $db->prepare('SELECT c_val, c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'year' ORDER BY c_val");
$stmt->execute();

while ($row = $stmt->fetch()) {
    $year_list[$row['c_val']] = $current_year < $row['c_val'] ? null : $row['c_count'];
    $total += $row['c_count'];
}
$stmt->closeCursor();

$ctsy = [];
$ctsy['caption'] = $nv_Lang->getModule('statbyyear');
$ctsy['total'] = nv_number_format($total);
$ctsy['labels'] = array_keys($year_list);
$ctsy['values'] = array_values($year_list);

// Thống kê theo tháng của năm
$month_abbrs = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$month_values = [];
foreach ($month_abbrs as $i => $abbr) {
    $month_values[$abbr] = $i + 1 > $current_month_num ? null : 0;
}

$month_in = "'" . implode("','", array_slice($month_abbrs, 0, $current_month_num)) . "'";
$total = 0;
$stmt = $db->prepare('SELECT c_val, c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'month' AND c_val IN (" . $month_in . ')');
$stmt->execute();

while ($row = $stmt->fetch()) {
    $month_values[$row['c_val']] = $row['c_count'];
    $total += $row['c_count'];
}
$stmt->closeCursor();

$ctsm = [
    'caption' => $nv_Lang->getModule('statbymonth', $current_year),
    'total'   => nv_number_format($total),
    'keys'    => $month_abbrs,
    'values'  => array_values($month_values),
];

// Thống kê theo ngày trong tháng
$total = 0;
$day_list = [];
$stmt = $db->prepare('SELECT c_val, c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'day' AND c_val <= :current_number_of_days ORDER BY c_val");
$stmt->bindValue(':current_number_of_days', $current_number_of_days, PDO::PARAM_INT);
$stmt->execute();

while ($row = $stmt->fetch()) {
    $day_list[$row['c_val']] = $row['c_val'] <= $current_day ? $row['c_count'] : null;
    $total += $row['c_count'];
}
$stmt->closeCursor();

$ctsdm = [];
$ctsdm['caption'] = $nv_Lang->getModule('statbyday', $current_month_str, $current_year);
$ctsdm['total'] = nv_number_format($total);
$ctsdm['labels'] = array_keys($day_list);
$ctsdm['values'] = array_values($day_list);

// Ngày trong tuần
$dow_keys = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$dow_values = array_fill_keys($dow_keys, 0);

$stmt = $db->prepare('SELECT c_val, c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'dayofweek' AND c_val IN ('" . implode("','", $dow_keys) . "')");
$stmt->execute();

$total = 0;
while ($row = $stmt->fetch()) {
    $dow_values[$row['c_val']] = $row['c_count'];
    $total += $row['c_count'];
}
$stmt->closeCursor();

$ctsdw = [
    'caption' => $nv_Lang->getModule('statbydayofweek'),
    'total'   => nv_number_format($total),
    'keys'    => $dow_keys,
    'values'  => array_values($dow_values),
];

// Giờ trong ngày
$total = 0;
$hour_list = [];

$stmt = $db->prepare('SELECT c_val, c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'hour' ORDER BY c_val");
$stmt->execute();

while ($row = $stmt->fetch()) {
    $hour_list[$row['c_val']] = $row['c_val'] > $current_hour ? null : $row['c_count'];
    $total += $row['c_count'];
}
$stmt->closeCursor();

$ctsh = [];
$ctsh['caption'] = $nv_Lang->getModule('statbyhour') . ' (' . date('d/m/Y', NV_CURRENTTIME) . ')';
$ctsh['total'] = nv_number_format($total);
$ctsh['labels'] = array_keys($hour_list);
$ctsh['values'] = array_values($hour_list);

// Theo quốc gia
$stmt = $db->prepare('SELECT c_val, c_count, last_update FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'country' AND c_count != 0 ORDER BY c_count DESC LIMIT 10");
$stmt->execute();

$total = 0;
$countries_list = [];
while ($row = $stmt->fetch()) {
    $countries_list[] = [
        'key' => $row['c_val'],
        'name' => ($row['c_val'] != 'ZZ' and isset($countries[$row['c_val']])) ? ($nv_Lang->existsGlobal('country_' . $row['c_val']) ? $nv_Lang->getGlobal('country_' . $row['c_val']) : $countries[$row['c_val']][1]) : $nv_Lang->getGlobal('unknown'),
        'count' => $row['c_count'],
        'count_format' => !empty($row['c_count']) ? nv_number_format($row['c_count']) : 0,
        'last_visit' => !empty($row['last_update']) ? nv_datetime_format($row['last_update'], 0, 1) : ''
    ];

    $total += $row['c_count'];
}
$stmt->closeCursor();

$stmt = $db->prepare('SELECT SUM(c_count) as total_sum, MAX(c_count) as max_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'country'");
$stmt->execute();
$row_meta = $stmt->fetch();
$all = $row_meta['total_sum'] ?? 0;
$max = $row_meta['max_count'] ?? 0;
$stmt->closeCursor();
$others = $all - $total;

$ctsc = [];
$ctsc['rows'] = $countries_list;
$ctsc['max'] = $max;
$ctsc['others'] = nv_number_format($others);
$ctsc['others_url'] = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allcountries'];

// Theo trình duyệt
$stmt = $db->prepare('SELECT c_val, c_count, last_update FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'browser' AND c_count != 0 ORDER BY c_count DESC");
$stmt->execute();

$total = 0;
$browsers_list = [];
while ($row = $stmt->fetch()) {
    $const = 'BROWSER_' . strtoupper($row['c_val']);
    $browsers_list[] = [
        'name' => $row['c_val'] != 'Unknown' ? (defined($const) ? constant($const) : ucfirst($row['c_val'])) : $nv_Lang->getGlobal('unknown'),
        'count' => $row['c_count'],
        'count_format' => !empty($row['c_count']) ? nv_number_format($row['c_count']) : 0,
        'last_visit' => !empty($row['last_update']) ? nv_datetime_format($row['last_update'], 0, 1) : ''
    ];

    $total += $row['c_count'];
}
$stmt->closeCursor();

$stmt = $db->prepare('SELECT SUM(c_count) as total_sum, MAX(c_count) as max_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'browser'");
$stmt->execute();
$row_meta = $stmt->fetch();
$all = $row_meta['total_sum'] ?? 0;
$max = $row_meta['max_count'] ?? 0;
$stmt->closeCursor();
$others = $all - $total;

$ctsb = [];
$ctsb['rows'] = $browsers_list;
$ctsb['max'] = $max;
$ctsb['others'] = nv_number_format($others);
$ctsb['others_url'] = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allbrowsers'];

// Theo hệ điều hành
$stmt = $db->prepare('SELECT c_val, c_count, last_update FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'os' AND c_count != 0 ORDER BY c_count DESC LIMIT 10");
$stmt->execute();

$total = 0;
$os_list = [];

while ($row = $stmt->fetch()) {
    $const = 'PLATFORM_' . strtoupper($row['c_val']);
    $os_list[] = [
        'name' => $row['c_val'] != 'unknown' ? (defined($const) ? constant($const) : ucfirst($row['c_val'])) : $nv_Lang->getGlobal('unknown'),
        'count' => $row['c_count'],
        'count_format' => !empty($row['c_count']) ? nv_number_format($row['c_count']) : 0,
        'last_visit' => !empty($row['last_update']) ? nv_datetime_format($row['last_update'], 0, 1) : ''
    ];

    $total += $row['c_count'];
}
$stmt->closeCursor();

$stmt = $db->prepare('SELECT SUM(c_count) as total_sum, MAX(c_count) as max_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'os'");
$stmt->execute();
$row_meta = $stmt->fetch();
$all = $row_meta['total_sum'] ?? 0;
$max = $row_meta['max_count'] ?? 0;
$stmt->closeCursor();
$others = $all - $total;

$ctso = [];
$ctso['rows'] = $os_list;
$ctso['max'] = $max;
$ctso['others'] = nv_number_format($others);
$ctso['others_url'] = NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allos'];

$contents = nv_theme_statistics_main($ctsy, $ctsm, $ctsdm, $ctsdw, $ctsc, $ctsb, $ctso, $ctsh);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
