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

$bid = $nv_Request->get_int('bid', 'get', 0);

if (empty($bid)) {
    nv_htmlOutput('Stop!!!');
}

$stmt = $db->prepare('SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id = :bid');
$stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
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

if (preg_match('/^[0-9]{1,2}$/', $nv_Request->get_int('month', 'get'))) {
    $post_month = $nv_Request->get_int('month', 'get');

    if ($post_month < $current_month) {
        if ($current_year != $publ_year) {
            $data_month = $post_month;
        } elseif ($post_month > $publ_month) {
            $data_month = $post_month;
        }
    }
}

$time = mktime(0, 0, 0, $data_month, 15, $current_year);
$day_max = ($data_month == $current_month) ? $current_day : date('t', $time);
$day_min = ($current_month == $publ_month and $current_year == $publ_year) ? $publ_day : 1;

$where = ['bid = :bid'];
$params = [':bid' => $bid];

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=show-list-stat&amp;bid=' . $bid . '&amp;month=' . $data_month;
$caption = $nv_Lang->getModule('show_list_stat1', nv_monthname($data_month), $current_year);

$data_ext = $data_val = '';

if (in_array($nv_Request->get_string('ext', 'get', 'no'), ['day', 'country', 'browse', 'os'], true)) {
    switch ($nv_Request->get_string('ext', 'get')) {
        case 'day':
            if ($nv_Request->isset_request('val', 'get') and preg_match('/^[0-9]+$/', $nv_Request->get_string('val', 'get')) and $nv_Request->get_int('val', 'get', 0) <= $day_max and $nv_Request->get_int('val', 'get', 0) >= $day_min) {
                $data_ext = 'day';
                $data_val = $nv_Request->get_int('val', 'get');
                $maxday = mktime(24, 60, 60, $data_month, $data_val, $current_year);
                $minday = mktime(0, 0, 0, $data_month, $data_val, $current_year);
                $where[] = 'click_time >= :minday AND click_time <= :maxday';
                $params[':minday'] = $minday;
                $params[':maxday'] = $maxday;
                $base_url .= '&amp;ext=' . $data_ext . '&amp;val=' . $data_val;
                $caption = $nv_Lang->getModule('show_list_stat2', str_pad($data_val, 2, '0', STR_PAD_LEFT), nv_monthname($data_month), $current_year);
            }
            break;
        case 'country':
            if ($nv_Request->isset_request('val', 'get') and ($nv_Request->get_string('val', 'get') == 'Unknown' or preg_match('/^[A-Z]{2}$/', $nv_Request->get_string('val', 'get')))) {
                $data_ext = 'country';
                $data_val = $nv_Request->get_string('val', 'get');
                $where[] = 'click_country = :data_val';
                $params[':data_val'] = $data_val;
                $base_url .= '&amp;ext=' . $data_ext . '&amp;val=' . $data_val;
                $caption = $nv_Lang->getModule('show_list_stat3', (isset($countries[$data_val]) ? $countries[$data_val][1] : $data_val), nv_monthname($data_month), $current_year);
            }
            break;
        case 'browse':
            if ($nv_Request->isset_request('val', 'get') and preg_match('/^[a-zA-Z0-9]+$/', $nv_Request->get_string('val', 'get'))) {
                $data_ext = 'browse';
                $data_val = $nv_Request->get_string('val', 'get');
                $where[] = 'click_browse_name = :data_val';
                $params[':data_val'] = $data_val;
                $base_url .= '&amp;ext=' . $data_ext . '&amp;val=' . $data_val;
                $caption = $nv_Lang->getModule('show_list_stat4', '{pattern}', nv_monthname($data_month), $current_year);
            }
            break;
        case 'os':
            if ($nv_Request->isset_request('val', 'get') and preg_match('/^[a-zA-Z0-9-\\s]+$/', $nv_Request->get_string('val', 'get'))) {
                $data_ext = 'os';
                $data_val = $nv_Request->get_string('val', 'get');
                $where[] = 'click_os_name = :data_val';
                $params[':data_val'] = $data_val;
                $base_url .= '&amp;ext=' . $data_ext . '&amp;val=' . $data_val;
                $caption = $nv_Lang->getModule('show_list_stat5', '{pattern}', nv_monthname($data_month), $current_year);
            }
            break;
    }
}

$where_sql = implode(' AND ', $where);
$stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE ' . $where_sql);
foreach ($params as $p => $v) {
    $stmt->bindValue($p, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$num_items = (int) $stmt->fetchColumn();
$stmt->closeCursor();

if (empty($num_items)) {
    exit('Wrong URL');
}

$page = $nv_Request->get_page('page', 'get', 1);
$per_page = 50;

$stmt = $db->prepare('SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE ' . $where_sql . ' ORDER BY click_time DESC LIMIT ' . $per_page . ' OFFSET ' . (($page - 1) * $per_page));
foreach ($params as $p => $v) {
    $stmt->bindValue($p, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();

$rows = [];
$replacement = '';

while ($row = $stmt->fetch()) {
    $rows[] = [
        'click_time' => nv_datetime_format($row['click_time']),
        'click_ip' => $row['click_ip'],
        'click_country' => isset($countries[$row['click_country']]) ? $countries[$row['click_country']][1] : $row['click_country'],
        'click_browse_name' => $row['click_browse_name'],
        'click_os_name' => $row['click_os_name'],
        'click_ref' => $row['click_ref']
    ];

    if ($data_ext == 'browse' and empty($replacement)) {
        $replacement = $row['click_browse_name'];
    } elseif ($data_ext == 'os' and empty($replacement)) {
        $replacement = $row['click_os_name'];
    }
}
$stmt->closeCursor();

if (!empty($replacement)) {
    $caption = preg_replace('/\{pattern\}/', $replacement, $caption);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('show_list_stat.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CAPTION', $caption);
$tpl->assign('ROWS', $rows);
$tpl->assign('THEAD', [$nv_Lang->getModule('click_date'), $nv_Lang->getModule('click_ip'), $nv_Lang->getModule('click_country'), $nv_Lang->getModule('click_browse'), $nv_Lang->getModule('click_os'), $nv_Lang->getModule('click_ref')]);
$tpl->assign('GENERATE_PAGE', nv_generate_page($base_url, $num_items, $per_page, $page, true, true, 'nv_urldecode_ajax', 'statistic'));

$contents = $tpl->fetch('show_list_stat.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
