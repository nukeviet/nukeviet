<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs_add'] = $lang_module['nv_admin_add'];

$result = $db->query('SELECT * FROM ' . NV_CRONJOBS_GLOBALTABLE . ' ORDER BY is_sys DESC');

$contents = [];
while ($row = $result->fetch()) {
    $contents[$row['id']]['caption'] = isset($row[NV_LANG_INTERFACE . '_cron_name']) ? $row[NV_LANG_INTERFACE . '_cron_name'] : (isset($row[NV_LANG_DATA . '_cron_name']) ? $row[NV_LANG_DATA . '_cron_name'] : $row['run_func']);
    $contents[$row['id']]['edit'] = [(empty($row['is_sys']) ? 1 : 0), $lang_global['edit'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs_edit&amp;id=' . $row['id']];
    $contents[$row['id']]['delete'] = [(empty($row['is_sys']) ? 1 : 0), $lang_global['delete'], md5(NV_CHECK_SESSION . '_' . $module_name . '_cronjobs_del_' . $row['id'])];
    $contents[$row['id']]['disable'] = [
        ((empty($row['is_sys']) or empty($row['act'])) ? 1 : 0),
        ($row['act'] ? $lang_global['disable'] : $lang_global['activate']),
        NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs_act&amp;id=' . $row['id'] . '&checkss=' . md5(NV_CHECK_SESSION . '_' . $module_name . '_cronjobs_act_' . $row['id'])
    ];
    $contents[$row['id']]['detail'][$lang_module['run_file']] = $row['run_file'];
    $contents[$row['id']]['detail'][$lang_module['run_func']] = $row['run_func'];
    $contents[$row['id']]['detail'][$lang_module['params']] = !empty($row['params']) ? implode(', ', explode(',', $row['params'])) : '';
    $contents[$row['id']]['detail'][$lang_module['start_time']] = nv_date('l, d/m/Y H:i', $row['start_time']);
    $contents[$row['id']]['detail'][$lang_module['interval']] = nv_convertfromSec($row['inter_val'] * 60);
    $contents[$row['id']]['detail'][$lang_module['is_del']] = !empty($row['del']) ? $lang_module['isdel'] : $lang_module['notdel'];
    $contents[$row['id']]['detail'][$lang_module['is_sys']] = !empty($row['is_sys']) ? $lang_module['system'] : $lang_module['client'];
    $contents[$row['id']]['detail'][$lang_module['act']] = !empty($row['act']) ? $lang_module['act1'] : $lang_module['act0'];
    $contents[$row['id']]['detail'][$lang_module['last_time']] = !empty($row['last_time']) ? nv_date('l, d/m/Y H:i:s', $row['last_time']) : $lang_module['last_time0'];
    $contents[$row['id']]['detail'][$lang_module['last_result']] = empty($row['last_time']) ? $lang_module['last_result_empty'] : $lang_module['last_result' . $row['last_result']];

    if (empty($row['act'])) {
        $next_time = 'n/a';
    } else {
        $interval = $row['inter_val'] * 60;
        if (empty($interval) or empty($row['last_time'])) {
            $next_time = nv_date('l, d/m/Y H:i:s', max($row['start_time'], $global_config['cronjobs_next_time'], NV_CURRENTTIME));
        } else {
            $next_time = nv_date('l, d/m/Y H:i:s', $row['last_time'] + $interval);
        }
    }

    $contents[$row['id']]['detail'][$lang_module['next_time']] = $next_time;
}
if (empty($contents)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cronjobs_add');
}

$contents = main_theme($contents);
$page_title = $lang_global['mod_cronjobs'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
