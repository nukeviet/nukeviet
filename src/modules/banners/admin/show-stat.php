<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if ($client_info['is_myreferer'] != 1) {
    nv_htmlOutput('Wrong URL');
}

$id = $nv_Request->get_int('id', 'get', 0);

$stmt = $db->prepare('SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if (empty($row)) {
    nv_htmlOutput('Stop!!!');
}

$current_day = date('d');
$current_month = date('n');
$current_year = date('Y');
$publ_day = date('d', $row['publ_time']);
$publ_month = date('n', $row['publ_time']);
$publ_year = date('Y', $row['publ_time']);

$data_month = $current_month;

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
$stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid = :id AND click_time >= :minday AND click_time <= :maxday');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':minday', $minday, PDO::PARAM_INT);
$stmt->bindValue(':maxday', $maxday, PDO::PARAM_INT);
$stmt->execute();
$sum = $stmt->fetchColumn();

$cts = [];
$month_label = nv_monthname($data_month) . ' ' . $current_year;

$ext = in_array($nv_Request->get_string('ext', 'get', 'no'), ['country', 'browse', 'os'], true) ? $nv_Request->get_string('ext', 'get') : 'day';

if ($ext == 'country') {
    $stmt = $db->prepare('SELECT click_country FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid = :id AND click_time >= :minday AND click_time <= :maxday ORDER BY click_country DESC');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':minday', $minday, PDO::PARAM_INT);
    $stmt->bindValue(':maxday', $maxday, PDO::PARAM_INT);
    $stmt->execute();

    $bd = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            if (!isset($bd[$row['click_country']])) {
                $bd[$row['click_country']] = 0;
            }
            $bd[$row['click_country']] += 1;
        }
        $stmt->closeCursor();

        $unknown = 0;

        foreach ($bd as $shortname => $click_count) {
            $country = $shortname;
            if (preg_match('/^[A-Z]{2}$/', $country)) {
                $cts[] = [
                    'label' => isset($countries[$country]) ? $countries[$country][1] : $country,
                    'percent' => ($sum > 0) ? round($click_count * 100 / $sum, 1) : 0,
                    'count' => $click_count,
                    'drill_down' => [
                        'bid' => $id,
                        'month' => $data_month,
                        'ext' => $ext,
                        'val' => $country
                    ]
                ];
            } else {
                $unknown += $click_count;
            }
        }

        if (!empty($unknown)) {
            $cts[] = [
                'label' => $nv_Lang->getModule('unknown'),
                'percent' => ($sum > 0) ? round($unknown * 100 / $sum) : 0,
                'count' => $unknown,
                'drill_down' => [
                    'bid' => $id,
                    'month' => $data_month,
                    'ext' => $ext,
                    'val' => 'Unknown'
                ]
            ];
        }
    }
    $caption = $nv_Lang->getModule('info_stat_bycountry_caption', nv_monthname($data_month), $current_year);
} elseif ($ext == 'browse') {
    $stmt = $db->prepare('SELECT click_browse_name FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid = :id AND click_time >= :minday AND click_time <= :maxday ORDER BY click_browse_name DESC');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':minday', $minday, PDO::PARAM_INT);
    $stmt->bindValue(':maxday', $maxday, PDO::PARAM_INT);
    $stmt->execute();
    $bd = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            if (!isset($bd[$row['click_browse_name']])) {
                $bd[$row['click_browse_name']] = 0;
            }
            $bd[$row['click_browse_name']] += 1;
        }
        $stmt->closeCursor();
    }

    $unknown = 0;
    foreach ($bd as $shortname => $click_count) {
        if (trim($shortname) != 'Unknown') {
            $cts[] = [
                'label' => $shortname,
                'percent' => ($sum > 0) ? round($click_count * 100 / $sum, 1) : 0,
                'count' => $click_count,
                'drill_down' => [
                    'bid' => $id,
                    'month' => $data_month,
                    'ext' => $ext,
                    'val' => $shortname
                ]
            ];
        } else {
            $unknown += $click_count;
        }
    }
    if (!empty($unknown)) {
        $cts[] = [
            'label' => $nv_Lang->getModule('unknown'),
            'percent' => ($sum > 0) ? round($unknown * 100 / $sum) : 0,
            'count' => $unknown,
            'drill_down' => [
                'bid' => $id,
                'month' => $data_month,
                'ext' => $ext,
                'val' => 'Unknown'
            ]
        ];
    }

    $caption = $nv_Lang->getModule('info_stat_bybrowse_caption', nv_monthname($data_month), $current_year);
} elseif ($ext == 'os') {
    $stmt = $db->prepare('SELECT click_os_name FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid = :id AND click_time >= :minday AND click_time <= :maxday ORDER BY click_os_name DESC');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':minday', $minday, PDO::PARAM_INT);
    $stmt->bindValue(':maxday', $maxday, PDO::PARAM_INT);
    $stmt->execute();
    $bd = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            if (!isset($bd[$row['click_os_name']])) {
                $bd[$row['click_os_name']] = 0;
            }
            $bd[$row['click_os_name']] += 1;
        }
        $stmt->closeCursor();
    }

    $unknown = 0;
    $robots = [];
    foreach ($bd as $shortname => $click_count) {
        $os_key = $os_name = $shortname;

        if (preg_match('/^Robot\:/', $os_name)) {
            $robots[] = [
                'label' => $os_name,
                'percent' => ($sum > 0) ? round($click_count * 100 / $sum, 1) : 0,
                'count' => $click_count,
                'drill_down' => [
                    'bid' => $id,
                    'month' => $data_month,
                    'ext' => $ext,
                    'val' => $os_key
                ]
            ];
        } elseif ($os_key != 'Unknown') {
            $cts[] = [
                'label' => $os_name,
                'percent' => ($sum > 0) ? round($click_count * 100 / $sum, 1) : 0,
                'count' => $click_count,
                'drill_down' => [
                    'bid' => $id,
                    'month' => $data_month,
                    'ext' => $ext,
                    'val' => $os_key
                ]
            ];
        } else {
            $unknown += $click_count;
        }
    }

    if (!empty($robots)) {
        $cts = array_merge($cts, $robots);
    }

    if (!empty($unknown)) {
        $cts[] = [
            'label' => $nv_Lang->getModule('unknown'),
            'percent' => ($sum > 0) ? round($unknown * 100 / $sum) : 0,
            'count' => $unknown,
            'drill_down' => [
                'bid' => $id,
                'month' => $data_month,
                'ext' => $ext,
                'val' => 'Unknown'
            ]
        ];
    }

    $caption = $nv_Lang->getModule('info_stat_byos_caption', nv_monthname($data_month), $current_year);
} else {
    $stmt = $db->prepare('SELECT click_time FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid = :id AND click_time >= :minday AND click_time <= :maxday ORDER BY click_time DESC');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':minday', $minday, PDO::PARAM_INT);
    $stmt->bindValue(':maxday', $maxday, PDO::PARAM_INT);
    $stmt->execute();
    $bd = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            if (!isset($bd[date('d', $row['click_time'])])) {
                $bd[date('d', $row['click_time'])] = 0;
            }
            $bd[date('d', $row['click_time'])] += 1;
        }
        $stmt->closeCursor();
    }

    for ($i = $day_max; $i >= $day_min; --$i) {
        $c = $bd[$i] ?? 0;
        $item = [
            'label' => str_pad($i, 2, '0', STR_PAD_LEFT) . ' ' . $month_label,
            'percent' => ($sum > 0) ? round(($c * 100) / $sum, 1) : 0,
            'count' => $c,
            'drill_down' => null
        ];
        if (isset($bd[$i])) {
            $item['drill_down'] = [
                'bid' => $id,
                'month' => $data_month,
                'ext' => 'day',
                'val' => $i
            ];
        }
        $cts[] = $item;
    }

    $caption = $nv_Lang->getModule('info_stat_byday_caption', nv_monthname($data_month), $current_year);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('show_stat.tpl'));

foreach ($cts as $index => $ct) {
    $cts[$index]['count_format'] = nv_number_format((float) ($ct['count'] ?? 0));
}

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CAPTION', $caption);
$tpl->assign('SUM', $sum);
$tpl->assign('SUM_FORMAT', nv_number_format((float) $sum));
$tpl->assign('CTS', $cts);

$contents = $tpl->fetch('show_stat.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
