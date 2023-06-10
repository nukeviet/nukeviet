<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

if ($nv_Request->isset_request('cfg, cronjobs_launcher', 'post')) {
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
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs&amp;rand=' . nv_genpass());
}

$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs_add'] = $nv_Lang->getModule('nv_admin_add');

$result = $db->query('SELECT * FROM ' . NV_CRONJOBS_GLOBALTABLE . ' ORDER BY is_sys DESC');

$contents = [];
while ($row = $result->fetch()) {
    $contents[$row['id']]['caption'] = isset($row[NV_LANG_INTERFACE . '_cron_name']) ? $row[NV_LANG_INTERFACE . '_cron_name'] : (isset($row[NV_LANG_DATA . '_cron_name']) ? $row[NV_LANG_DATA . '_cron_name'] : $row['run_func']);
    $contents[$row['id']]['edit'] = [(empty($row['is_sys']) ? 1 : 0), $nv_Lang->getGlobal('edit'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs_edit&amp;id=' . $row['id']];
    $contents[$row['id']]['delete'] = [(empty($row['is_sys']) ? 1 : 0), $nv_Lang->getGlobal('delete'), md5(NV_CHECK_SESSION . '_' . $module_name . '_cronjobs_del_' . $row['id'])];
    $contents[$row['id']]['disable'] = [
        ((empty($row['is_sys']) or empty($row['act'])) ? 1 : 0),
        ($row['act'] ? $nv_Lang->getGlobal('disable') : $nv_Lang->getGlobal('activate')),
        NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs_act&amp;id=' . $row['id'] . '&checkss=' . md5(NV_CHECK_SESSION . '_' . $module_name . '_cronjobs_act_' . $row['id'])
    ];
    $contents[$row['id']]['act'] = $row['act'];
    $contents[$row['id']]['last_time'] = $row['last_time'];
    $contents[$row['id']]['last_time_title'] = !empty($row['last_time']) ? nv_date('d/m/Y H:i', $row['last_time']) : $nv_Lang->getModule('last_time0');
    $contents[$row['id']]['last_result'] = $row['last_result'];
    $contents[$row['id']]['last_result_title'] = empty($row['last_time']) ? $nv_Lang->getModule('last_result_empty') : $nv_Lang->getModule('last_result' . $row['last_result']);
    $contents[$row['id']]['detail'][$nv_Lang->getModule('run_file')] = $row['run_file'];
    $contents[$row['id']]['detail'][$nv_Lang->getModule('run_func')] = $row['run_func'];
    if (!empty($row['params'])) {
        $contents[$row['id']]['detail'][$nv_Lang->getModule('params')] = preg_replace('/\,\s*/', ', ', $row['params']);
    }
    $contents[$row['id']]['detail'][$nv_Lang->getModule('start_time')] = nv_date('l, d/m/Y H:i', $row['start_time']);
    $contents[$row['id']]['detail'][$nv_Lang->getModule('interval')] = nv_convertfromSec($row['inter_val'] * 60);
    $contents[$row['id']]['detail'][$nv_Lang->getModule('is_del')] = !empty($row['del']) ? $nv_Lang->getModule('isdel') : $nv_Lang->getModule('notdel');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('is_sys')] = !empty($row['is_sys']) ? $nv_Lang->getModule('system') : $nv_Lang->getModule('client');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('act')] = !empty($row['act']) ? $nv_Lang->getModule('act1') : $nv_Lang->getModule('act0');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('last_time')] = !empty($row['last_time']) ? nv_date('l, d/m/Y H:i:s', $row['last_time']) : $nv_Lang->getModule('last_time0');
    $contents[$row['id']]['detail'][$nv_Lang->getModule('last_result')] = empty($row['last_time']) ? $nv_Lang->getModule('last_result_empty') : $nv_Lang->getModule('last_result' . $row['last_result']);

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

    $contents[$row['id']]['detail'][$nv_Lang->getModule('next_time')] = $next_time;
}
if (empty($contents)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cronjobs_add');
}

$xtpl = new XTemplate('cronjobs_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cronjobs');

$url = urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&loadcron=' . md5('cronjobs' . $global_config['sitekey']), NV_MY_DOMAIN);
if ($global_config['cronjobs_interval'] <= 1 or $global_config['cronjobs_interval'] > 59) {
    $interval = '*';
} else {
    $interval = '*/' . $global_config['cronjobs_interval'];
}
$code = $interval . ' * * * *  /usr/bin/wget --spider &quot;' . $url . '&quot;  &gt;/dev/null 2&gt;&amp;1';
$xtpl->assign('LAUCHER_SERVER_URL', $url);
$xtpl->assign('CRON_CODE', $code);

if ($global_config['cronjobs_last_time'] > 0) {
    $xtpl->assign('LAST_CRON', $nv_Lang->getModule('cron_last_time', nv_date('d/m/Y H:i:s', $global_config['cronjobs_last_time'])));
    $xtpl->assign('NEXT_CRON', $nv_Lang->getModule('cron_next_time', nv_date('d/m/Y H:i:s', ($global_config['cronjobs_last_time'] + $global_config['cronjobs_interval'] * 60))));
    $xtpl->parse('main.next_cron');
}

if (isset($global_config['cronjobs_launcher']) and $global_config['cronjobs_launcher'] == 'server') {
    $xtpl->parse('main.launcher_server');
    $xtpl->parse('main.cron_code');
} else {
    $xtpl->parse('main.launcher_system');
}

for ($i = 1; $i < 60; ++$i) {
    $xtpl->assign('CRON_INTERVAL', [
        'val' => $i,
        'sel' => $i == $global_config['cronjobs_interval'] ? ' selected="selected"' : '',
        'name' => plural($i, $nv_Lang->getGlobal('plural_min'))
    ]);
    $xtpl->parse('main.cronjobs_interval');
}

foreach ($contents as $id => $values) {
    $xtpl->assign('DATA', [
        'caption' => $values['caption'],
        'edit' => empty($values['edit']) ? [] : $values['edit'],
        'disable' => empty($values['disable']) ? [] : $values['disable'],
        'delete' => empty($values['delete']) ? [] : $values['delete'],
        'id' => $id,
        'last_time_title' => $values['last_time_title'],
        'last_result_title' => $values['last_result_title']
    ]);

    if (empty($values['act'])) {
        $xtpl->parse('main.crj.inactivate');
    }

    if (!empty($values['last_time'])) {
        $xtpl->parse('main.crj.last_time.result' . $values['last_result']);
        $xtpl->parse('main.crj.last_time');
    } else {
        $xtpl->parse('main.crj.never');
    }

    if (!empty($values['edit'][0]) or !empty($values['disable'][0]) or !empty($values['delete'][0])) {
        if (!empty($values['edit'][0])) {
            $xtpl->parse('main.crj.action.edit');
        }
        if (!empty($values['disable'][0])) {
            $xtpl->parse('main.crj.action.disable');
        }
        if (!empty($values['delete'][0])) {
            $xtpl->parse('main.crj.action.delete');
        }
        $xtpl->parse('main.crj.action');
    }

    foreach ($values['detail'] as $key => $value) {
        $xtpl->assign('ROW', [
            'key' => $key,
            'value' => $value
        ]);

        $xtpl->parse('main.crj.loop');
    }

    $xtpl->parse('main.crj');
}

$xtpl->parse('main');

$contents = $xtpl->text('main');
$page_title = $nv_Lang->getGlobal('mod_cronjobs');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
