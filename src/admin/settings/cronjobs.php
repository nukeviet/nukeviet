<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getGlobal('mod_cronjobs');

// Lưu thiết lập chung
if ($nv_Request->isset_request('cfg, cronjobs_launcher', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error session!!!'
        ]);
    }

    $array_config_site = [
        'cronjobs_launcher' => $nv_Request->get_title('cronjobs_launcher', 'post', 'system'),
        'cronjobs_interval' => $nv_Request->get_int('cronjobs_interval', 'post', 1)
    ];
    if ($array_config_site['cronjobs_launcher'] != 'server') {
        $array_config_site['cronjobs_launcher'] = 'system';
    }
    if ($array_config_site['cronjobs_interval'] < 1) {
        $array_config_site['cronjobs_interval'] = 1;
    }
    if ($array_config_site['cronjobs_interval'] > 59) {
        $array_config_site['cronjobs_interval'] = 59;
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll();
    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

// Lấy thông tin crontab để sửa
if ($nv_Request->isset_request('crontabinfo', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput([
            'success' => 0,
            'text' => 'Error session!'
        ]);
    }

    $id = $nv_Request->get_int('id', 'post', 0);

    $sql = 'SELECT * FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $id . ' AND is_sys=0';
    $row = $db->query($sql)->fetch();
    if (empty($row)) {
        nv_jsonOutput([
            'success' => 0,
            'text' => 'Not exists!'
        ]);
    }

    nv_jsonOutput([
        'success' => 1,
        'text' => 'Success!',
        'data' => [
            'form_title' => $nv_Lang->getModule('nv_admin_edit') . ': ' . $row[NV_LANG_DATA . '_cron_name'],
            'cron_name' => nv_unhtmlspecialchars($row[NV_LANG_DATA . '_cron_name']),
            'run_file' => $row['run_file'],
            'run_func' => $row['run_func'],
            'params' => nv_unhtmlspecialchars($row['params']),
            'hour' => (int) date('G', $row['start_time'] ?: NV_CURRENTTIME),
            'min' => (int) date('i', $row['start_time'] ?: NV_CURRENTTIME),
            'start_date' => nv_u2d_post($row['start_time'] ?: NV_CURRENTTIME),
            'inter_val' => $row['inter_val'],
            'inter_val_type' => $row['inter_val_type'],
            'del' => $row['del']
        ]
    ]);
}

// Xóa crontab
if ($nv_Request->isset_request('cron_del', 'post')) {
    $id = $nv_Request->get_int('cron_del', 'post', 0);

    if ($nv_Request->get_title('checkss', 'post', '') !== md5(NV_CHECK_SESSION . '_' . $module_name . '_cronjobs_del_' . $id)) {
        nv_jsonOutput([
            'success' => 0,
            'text' => 'Error session!'
        ]);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete crontab', $id, $admin_info['userid']);

    $sql = 'SELECT COUNT(*) FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $id . ' AND is_sys=0';
    if ($db->query($sql)->fetchColumn()) {
        $db->exec('DELETE FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id = ' . $id);
        $db->query('OPTIMIZE TABLE ' . NV_CRONJOBS_GLOBALTABLE);
        update_cronjob_next_time();
    }

    nv_jsonOutput([
        'success' => 1,
        'text' => ''
    ]);
}

// Kích hoạt đình chỉ crontab
if ($nv_Request->isset_request('cron_changeact', 'post')) {
    $id = $nv_Request->get_int('cron_changeact', 'post', 0);

    if ($nv_Request->get_title('checkss', 'post', '') !== md5(NV_CHECK_SESSION . '_' . $module_name . '_cronjobs_act_' . $id)) {
        nv_jsonOutput([
            'success' => 0,
            'text' => 'Error session!'
        ]);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_cronjob_atc', 'id ' . $id, $admin_info['userid']);

    $sql = 'SELECT act FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $id . ' AND (is_sys=0 OR act=0)';
    $row = $db->query($sql)->fetch();

    if (!empty($row)) {
        $act = (int) ($row['act']);
        $new_act = (!empty($act)) ? 0 : 1;
        $db->query('UPDATE ' . NV_CRONJOBS_GLOBALTABLE . ' SET act=' . $new_act . ' WHERE id=' . $id);
    }

    nv_jsonOutput([
        'success' => 1,
        'text' => ''
    ]);
}

// Thêm sửa crontab
if ($nv_Request->isset_request('crontabcontent', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error session!'
        ]);
    }

    $array = [];
    $array['id'] = $nv_Request->get_absint('id', 'post', 0);
    $array['cron_name'] = $nv_Request->get_title('cron_name', 'post', '', 1);
    $array['run_file'] = $nv_Request->get_title('run_file', 'post', '');
    $array['run_func'] = $nv_Request->get_title('run_func_iavim', 'post', '');
    $array['params'] = $nv_Request->get_title('params_iavim', 'post', '');
    $array['interval'] = $nv_Request->get_int('interval_iavim', 'post', 0);
    $array['inter_val_type'] = $nv_Request->get_int('inter_val_type', 'post', 0);
    $array['del'] = (int) $nv_Request->get_bool('del', 'post', false);

    // Kiểm tra chỉnh sửa
    if (!empty($array['id'])) {
        $sql = 'SELECT * FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $array['id'] . ' AND is_sys=0';
        $row = $db->query($sql)->fetch();
        if (empty($row)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Crontab not exists!'
            ]);
        }
    }

    $min = $nv_Request->get_int('min', 'post', 0);
    $hour = $nv_Request->get_int('hour', 'post', 0);

    $array['start_time'] = nv_d2u_post($nv_Request->get_string('start_date', 'post', ''), $hour, $min, 0);
    if (empty($array['start_time'])) {
        $array['start_time'] = NV_CURRENTTIME;
    }
    if ($array['inter_val_type'] < 0 or $array['inter_val_type'] > 1) {
        $array['inter_val_type'] = 1;
    }

    if (empty($array['cron_name'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'cron_name',
            'mess' => $nv_Lang->getModule('cron_name_empty')
        ]);
    }
    if (!empty($array['run_file']) and !nv_is_file(NV_BASE_SITEURL . 'includes/cronjobs/' . $array['run_file'], 'includes/cronjobs')) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'run_file',
            'mess' => $nv_Lang->getModule('file_not_exist')
        ]);
    }
    if (empty($array['run_func']) or !preg_match($global_config['check_cron'], $array['run_func'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'run_func',
            'mess' => $nv_Lang->getModule('func_name_invalid')
        ]);
    }

    if (!empty($array['run_file']) and preg_match('/^([a-zA-Z0-9\-\_\.]+)\.php$/', $array['run_file']) and file_exists(NV_ROOTDIR . '/includes/cronjobs/' . $array['run_file'])) {
        if (!defined('NV_IS_CRON')) {
            define('NV_IS_CRON', true);
        }
        require_once NV_ROOTDIR . '/includes/cronjobs/' . $array['run_file'];
    }
    if (!nv_function_exists($array['run_func'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'run_func',
            'mess' => $nv_Lang->getModule('func_name_not_exist')
        ]);
    }

    if (!empty($array['params'])) {
        $array['params'] = explode(',', $array['params']);
        $array['params'] = array_map('trim', $array['params']);
        $array['params'] = implode(',', $array['params']);
    }

    if (!empty($array['id'])) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_cronjob_edit', json_encode($array, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $admin_info['userid']);
        $sth = $db->prepare('UPDATE ' . NV_CRONJOBS_GLOBALTABLE . ' SET
            start_time=' . $array['start_time'] . ', inter_val=' . $array['interval'] . ',
            inter_val_type=' . $array['inter_val_type'] . ', run_file= :run_file,
            run_func= :run_func, params= :params, del=' . $array['del'] . ',
            ' . NV_LANG_INTERFACE . '_cron_name= :cron_name
        WHERE id=' . $array['id']);

        $sth->bindParam(':run_file', $array['run_file'], PDO::PARAM_STR);
        $sth->bindParam(':run_func', $array['run_func'], PDO::PARAM_STR);
        $sth->bindParam(':params', $array['params'], PDO::PARAM_STR);
        $sth->bindParam(':cron_name', $array['cron_name'], PDO::PARAM_STR);
        $sth->execute();
    } else {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_cronjob_add', json_encode($array, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $admin_info['userid']);
        $sql = 'INSERT INTO ' . NV_CRONJOBS_GLOBALTABLE . ' (
            start_time, inter_val, inter_val_type, run_file, run_func, params, del, is_sys, act,
            last_time, last_result, ' . NV_LANG_INTERFACE . '_cron_name
        ) VALUES (
            ' . $array['start_time'] . ', ' . $array['interval'] . ', ' . $array['inter_val_type'] . ',
            :run_file, :run_func, :params, ' . $array['del'] . ', 0, 1, 0, 0, :cron_name
        )';
        $data = [];
        $data['run_file'] = $array['run_file'];
        $data['run_func'] = $array['run_func'];
        $data['params'] = $array['params'];
        $data['cron_name'] = $array['cron_name'];
        $id = $db->insert_id($sql, 'id', $data);
        if (empty($id)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Error insert DB!'
            ]);
        }

        $sql = 'SELECT lang FROM ' . $db_config['prefix'] . "_setup_language where lang!='" . NV_LANG_INTERFACE . "'";
        $result = $db->query($sql);
        while ([$lang_i] = $result->fetch(3)) {
            $sth = $db->prepare('UPDATE ' . NV_CRONJOBS_GLOBALTABLE . ' SET ' . $lang_i . '_cron_name= :run_func WHERE id=' . $id);
            $sth->bindParam(':run_func', $array['run_func'], PDO::PARAM_STR);
            $sth->execute();
        }
    }

    update_cronjob_next_time();
    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

$result = $db->query('SELECT * FROM ' . NV_CRONJOBS_GLOBALTABLE . ' ORDER BY is_sys DESC');

$contents = [];
while ($row = $result->fetch()) {
    $contents[$row['id']]['caption'] = $row[NV_LANG_INTERFACE . '_cron_name'] ?? ($row[NV_LANG_DATA . '_cron_name'] ?? $row['run_func']);
    $contents[$row['id']]['del_checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_cronjobs_del_' . $row['id']);
    $contents[$row['id']]['act_checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_cronjobs_act_' . $row['id']);
    $contents[$row['id']]['is_sys'] = $row['is_sys'];
    $contents[$row['id']]['act'] = $row['act'];
    $contents[$row['id']]['last_time'] = $row['last_time'];
    $contents[$row['id']]['last_time_title'] = !empty($row['last_time']) ? nv_datetime_format($row['last_time']) : $nv_Lang->getModule('last_time0');
    $contents[$row['id']]['last_result'] = $row['last_result'];
    $contents[$row['id']]['last_result_title'] = empty($row['last_time']) ? $nv_Lang->getModule('last_result_empty') : $nv_Lang->getModule('last_result' . $row['last_result']);
    $contents[$row['id']]['detail'][$nv_Lang->getModule('run_file')] = $row['run_file'];
    $contents[$row['id']]['detail'][$nv_Lang->getModule('run_func')] = $row['run_func'];
    if (!empty($row['params'])) {
        $contents[$row['id']]['detail'][$nv_Lang->getModule('params')] = preg_replace('/\,\s*/', ', ', $row['params']);
    }
    $contents[$row['id']]['detail'][$nv_Lang->getModule('start_time')] = nv_datetime_format($row['start_time'], 0, 0);
    $contents[$row['id']]['detail'][$nv_Lang->getModule('interval')] = nv_convertfromSec($row['inter_val'] * 60);
    $contents[$row['id']]['detail'][$nv_Lang->getModule('is_del')] = !empty($row['del']) ? $nv_Lang->getModule('isdel') : $nv_Lang->getModule('notdel');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('is_sys')] = !empty($row['is_sys']) ? $nv_Lang->getModule('system') : $nv_Lang->getModule('client');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('act')] = !empty($row['act']) ? $nv_Lang->getModule('act1') : $nv_Lang->getModule('act0');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('last_time')] = !empty($row['last_time']) ? nv_datetime_format($row['last_time'], 0, 0) : $nv_Lang->getModule('last_time0');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('last_result')] = empty($row['last_time']) ? $nv_Lang->getModule('last_result_empty') : $nv_Lang->getModule('last_result' . $row['last_result']);

    if (empty($row['act'])) {
        $next_time = 'n/a';
    } else {
        $interval = $row['inter_val'] * 60;
        if (empty($interval) or empty($row['last_time'])) {
            $next_time = nv_datetime_format(max($row['start_time'], $global_config['cronjobs_next_time'], NV_CURRENTTIME), 0, 0);
        } else {
            $next_time = nv_datetime_format($row['last_time'] + $interval, 0, 0);
        }
    }

    $contents[$row['id']]['detail'][$nv_Lang->getModule('next_time')] = $next_time;
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'plural', 'plural');
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->setTemplateDir(get_module_tpl_dir('cronjobs.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('GCONFIG', $global_config);
$tpl->assign('CRONLISTS', $contents);

$url = urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&loadcron=' . md5('cronjobs' . $global_config['sitekey']), NV_MY_DOMAIN);
if ($global_config['cronjobs_interval'] <= 1 or $global_config['cronjobs_interval'] > 59) {
    $interval = '*';
} else {
    $interval = '*/' . $global_config['cronjobs_interval'];
}
$code = $interval . ' * * * *  /usr/bin/wget --spider &quot;' . $url . '&quot;  &gt;/dev/null 2&gt;&amp;1';
$tpl->assign('LAUCHER_SERVER_URL', $url);
$tpl->assign('CRON_CODE', $code);

$filelist = nv_scandir(NV_ROOTDIR . '/includes/cronjobs', '/^([a-zA-Z0-9\_\.]+)\.php$/');
$tpl->assign('FILELIST', $filelist);
$tpl->assign('START_TIME', nv_u2d_post(NV_CURRENTTIME));

// Tự mở form thêm crontab và chọn tệp này
$auto_add_file = $nv_Request->get_title('auto_add_file', 'get', '');
if (!empty($auto_add_file) and !in_array($auto_add_file, $filelist)) {
    $auto_add_file = '';
}
$tpl->assign('AUTO_ADD_FILE', $auto_add_file);

$contents = $tpl->fetch('cronjobs.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
