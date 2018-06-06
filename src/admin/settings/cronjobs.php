<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:40
 */

if (! defined('NV_IS_FILE_SETTINGS')) {
    die('Stop!!!');
}

$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs_add'] = $nv_Lang->getModule('nv_admin_add');

$result = $db->query('SELECT * FROM ' . NV_CRONJOBS_GLOBALTABLE . ' ORDER BY is_sys DESC');

$contents = array();
while ($row = $result->fetch()) {
    $contents[$row['id']]['caption'] = isset($row[NV_LANG_INTERFACE . '_cron_name']) ? $row[NV_LANG_INTERFACE . '_cron_name'] : (isset($row[NV_LANG_DATA . '_cron_name']) ? $row[NV_LANG_DATA . '_cron_name'] : $row['run_func']);
    $contents[$row['id']]['edit'] = array( (empty($row['is_sys']) ? 1 : 0), $nv_Lang->getGlobal('edit'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs_edit&amp;id=' . $row['id'] );
    $contents[$row['id']]['delete'] = array( (empty($row['is_sys']) ? 1 : 0), $nv_Lang->getGlobal('delete') );
    $contents[$row['id']]['disable'] = array( ((empty($row['is_sys']) or empty($row['act'])) ? 1 : 0), ($row['act'] ? $nv_Lang->getGlobal('disable') : $nv_Lang->getGlobal('activate')), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs_act&amp;id=' . $row['id'] );
    $contents[$row['id']]['detail'][$nv_Lang->getModule('run_file')] = $row['run_file'];
    $contents[$row['id']]['detail'][$nv_Lang->getModule('run_func')] = $row['run_func'];
    $contents[$row['id']]['detail'][$nv_Lang->getModule('params')] = ! empty($row['params']) ? implode(', ', explode(',', $row['params'])) : '';
    $contents[$row['id']]['detail'][$nv_Lang->getModule('start_time')] = nv_date('l, d/m/Y H:i', $row['start_time']);
    $contents[$row['id']]['detail'][$nv_Lang->getModule('interval')] = nv_convertfromSec($row['inter_val'] * 60);
    $contents[$row['id']]['detail'][$nv_Lang->getModule('is_del')] = ! empty($row['del']) ? $nv_Lang->getModule('isdel') : $nv_Lang->getModule('notdel');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('is_sys')] = ! empty($row['is_sys']) ? $nv_Lang->getModule('system') : $nv_Lang->getModule('client');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('act')] = ! empty($row['act']) ? $nv_Lang->getModule('act1') : $nv_Lang->getModule('act0');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('last_time')] = ! empty($row['last_time']) ? nv_date('l, d/m/Y H:i', $row['last_time']) : $nv_Lang->getModule('last_time0');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('last_result')] = empty($row['last_time']) ? $nv_Lang->getModule('last_result_empty') : $nv_Lang->getModule('last_result' . $row['last_result']);

    if (empty($row['act'])) {
        $next_time = 'n/a';
    } else {
        $interval = $row['inter_val'] * 60;
        if (empty($interval) or empty($row['last_time'])) {
            $next_time = nv_date('l, d/m/Y H:i', max($row['start_time'], $global_config['cronjobs_next_time'], NV_CURRENTTIME));
        } else {
            $next_time = nv_date('l, d/m/Y H:i', $row['last_time'] + $interval);
        }
    }

    $contents[$row['id']]['detail'][$nv_Lang->getModule('next_time')] = $next_time;
}
if (empty($contents)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cronjobs_add');
}

$contents = main_theme($contents);
$page_title = $nv_Lang->getGlobal('mod_cronjobs');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';