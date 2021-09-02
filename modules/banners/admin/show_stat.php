<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if ($client_info['is_myreferer'] != 1) {
    exit('Wrong URL');
}

$id = $nv_Request->get_int('id', 'get', 0);

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    exit('Stop!!!');
}

$current_day = date('d');
$current_month = date('n');
$current_year = date('Y');
$publ_day = date('d', $row['publ_time']);
$publ_month = date('n', $row['publ_time']);
$publ_year = date('Y', $row['publ_time']);

$data_month = $nv_Request->get_int('month', 'get');

if ($nv_Request->isset_request('month', 'get') and preg_match('/^[0-9]{1,2}$/', $nv_Request->get_int('month', 'get'))) {
    $get_month = $nv_Request->get_int('month', 'get');

    if ($get_month < $current_month) {
        if ($current_year != $publ_year) {
            $data_month = $get_month;
        } elseif ($get_month > $publ_month) {
            $data_month = $get_month;
        }
    }
}

$time = mktime(0, 0, 0, $data_month, 15, $current_year);
$day_max = ($data_month == $current_month) ? $current_day : date('t', $time);
$day_min = ($current_month == $publ_month and $current_year == $publ_year) ? $publ_day : 1;
$maxday = mktime(24, 60, 60, $data_month, $day_max, $current_year);
$minday = mktime(0, 0, 0, $data_month, $day_min, $current_year);
$sum = $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid=' . $id . ' AND click_time>=' . $minday . ' AND click_time<=' . $maxday)->fetchColumn();

$cts = [];

$ext = in_array($nv_Request->get_string('ext', 'get', 'no'), ['country', 'browse', 'os'], true) ? $nv_Request->get_string('ext', 'get') : 'day';

if ($ext == 'country') {
    $sql = 'SELECT click_country FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid=' . $id . ' AND click_time>=' . $minday . ' AND click_time<=' . $maxday . ' ORDER BY click_country DESC';
    $result = $db->query($sql);
    $unknown = 0;

    if (!empty($result)) {
        $result = $db->query($sql);
        $bd = [];
        if (!empty($result)) {
            while ($row = $result->fetch()) {
                if (!isset($bd[$row['click_country']])) {
                    $bd[$row['click_country']] = 0;
                }
                $bd[$row['click_country']] = $bd[$row['click_country']] + 1;
            }
        }
        foreach ($bd as $shortname => $click_count) {
            $country = $shortname;
            if (preg_match('/^[A-Z]{2}$/', $country)) {
                $key = 'nv_show_list_stat(' . $id . ',' . $data_month . ",'" . $ext . "','" . $country . "','statistic',0);";
                $cts[$key][0] = isset($countries[$country]) ? $countries[$country][1] : $country;
                $cts[$key][1] = ($sum > 0) ? round($click_count * 100 / $sum, 1) : 0;
                $cts[$key][2] = $click_count;
            } else {
                $unknown += $click_count;
            }
        }

        if (!empty($unknown)) {
            $key = 'nv_show_list_stat(' . $id . ',' . $data_month . ",'" . $ext . "','Unknown','statistic',0);";
            $cts[$key][0] = $lang_module['unknown'];
            $cts[$key][1] = ($sum > 0) ? round($unknown * 100 / $sum) : 0;
            $cts[$key][2] = $unknown;
        }
    }
    $caption = sprintf($lang_module['info_stat_bycountry_caption'], nv_monthname($data_month), $current_year);
} elseif ($ext == 'browse') {
    $sql = 'SELECT click_browse_name FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid=' . $id . ' AND click_time>=' . $minday . ' AND click_time<=' . $maxday . ' ORDER BY click_country DESC';

    $result = $db->query($sql);
    $bd = [];
    if (!empty($result)) {
        while ($row = $result->fetch()) {
            if (!isset($bd[$row['click_browse_name']])) {
                $bd[$row['click_browse_name']] = 0;
            }
            $bd[$row['click_browse_name']] = $bd[$row['click_browse_name']] + 1;
        }
    }
    $unknown = 0;
    foreach ($bd as $shortname => $click_count) {
        if (trim($shortname) != 'Unknown') {
            $key = 'nv_show_list_stat(' . $id . ',' . $data_month . ",'" . $ext . "','" . $shortname . "','statistic',0);";
            $cts[$key][0] = $shortname;
            $cts[$key][1] = ($sum > 0) ? round($click_count * 100 / $sum, 1) : 0;
            $cts[$key][2] = $click_count;
        } else {
            $unknown += $click_count;
        }
    }
    if (!empty($unknown)) {
        $key = 'nv_show_list_stat(' . $id . ',' . $data_month . ",'" . $ext . "','Unknown','statistic',0);";
        $cts[$key][0] = $lang_module['unknown'];
        $cts[$key][1] = ($sum > 0) ? round($unknown * 100 / $sum) : 0;
        $cts[$key][2] = $unknown;
    }

    $caption = sprintf($lang_module['info_stat_bybrowse_caption'], nv_monthname($data_month), $current_year);
} elseif ($ext == 'os') {
    $sql = 'SELECT click_os_name FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid=' . $id . ' AND click_time>=' . $minday . ' AND click_time<=' . $maxday . ' ORDER BY click_os_name DESC';
    $result = $db->query($sql);
    $bd = [];

    if (!empty($result)) {
        while ($row = $result->fetch()) {
            if (!isset($bd[$row['click_os_name']])) {
                $bd[$row['click_os_name']] = 0;
            }
            $bd[$row['click_os_name']] = $bd[$row['click_os_name']] + 1;
        }
    }

    $unknown = 0;
    foreach ($bd as $shortname => $click_count) {
        $os_key = $os_name = $shortname;

        if (preg_match('/^Robot\:/', $os_name)) {
            $key = 'nv_show_list_stat(' . $id . ',' . $data_month . ",'" . $ext . "','" . $os_key . "','statistic',0);";
            $robots[$key][0] = $os_name;
            $robots[$key][1] = ($sum > 0) ? round($click_count * 100 / $sum, 1) : 0;
            $robots[$key][2] = $click_count;
        } elseif ($os_key != 'Unknown') {
            $key = 'nv_show_list_stat(' . $id . ',' . $data_month . ",'" . $ext . "','" . $os_key . "','statistic',0);";
            $cts[$key][0] = $os_name;
            $cts[$key][1] = ($sum > 0) ? round($click_count * 100 / $sum, 1) : 0;
            $cts[$key][2] = $click_count;
        } else {
            $unknown += $click_count;
        }
    }

    if (!empty($robots)) {
        $cts = array_merge($cts, $robots);
    }

    if (!empty($unknown)) {
        $key = 'nv_show_list_stat(' . $id . ',' . $data_month . ",'" . $ext . "','Unknown','statistic',0);";
        $cts[$key][0] = $lang_module['unknown'];
        $cts[$key][1] = ($sum > 0) ? round($unknown * 100 / $sum) : 0;
        $cts[$key][2] = $unknown;
    }

    $caption = sprintf($lang_module['info_stat_byos_caption'], nv_monthname($data_month), $current_year);
} else {
    $sql = 'SELECT click_time FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid=' . $id . ' AND click_time>=' . $minday . ' AND click_time<=' . $maxday . ' ORDER BY click_time DESC';
    $result = $db->query($sql);
    $bd = [];

    if (!empty($result)) {
        while ($row = $result->fetch()) {
            if (!isset($bd[date('d', $row['click_time'])])) {
                $bd[date('d', $row['click_time'])] = 0;
            }
            $bd[date('d', $row['click_time'])] = $bd[date('d', $row['click_time'])] + 1;
        }
    }

    for ($i = $day_max; $i >= $day_min; --$i) {
        $c = isset($bd[$i]) ? $bd[$i] : 0;
        $key = isset($bd[$i]) ? 'nv_show_list_stat(' . $id . ',' . $data_month . ",'day','" . $i . "','statistic',0);" : $i;
        $cts[$key][0] = str_pad($i, 2, '0', STR_PAD_LEFT) . ' ' . nv_date('F Y', $time);
        $cts[$key][1] = ($sum > 0) ? round(($c * 100) / $sum, 1) : 0;
        $cts[$key][2] = $c;
    }

    $caption = sprintf($lang_module['info_stat_byday_caption'], nv_monthname($data_month), $current_year);
}

$contents = nv_show_stat_theme([$caption, $sum, $cts]);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
