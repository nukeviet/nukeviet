<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$allow_func = ['main', 'language', 'smtp'];
if ($site_fulladmin) {
    $allow_func[] = 'system';
    $allow_func[] = 'security';
}
if (defined('NV_IS_GODADMIN')) {
    $allow_func[] = 'ftp';
    $allow_func[] = 'security';
    $allow_func[] = 'cronjobs';
    $allow_func[] = 'cronjobs_add';
    $allow_func[] = 'cronjobs_edit';
    $allow_func[] = 'cronjobs_del';
    $allow_func[] = 'cronjobs_act';
    $allow_func[] = 'plugin';
    $allow_func[] = 'variables';
    $allow_func[] = 'ssettings';
    $allow_func[] = 'cdn_backendhost';
    $allow_func[] = 'custom';
}

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_settings')
];

unset($page_title, $select_options);

define('NV_IS_FILE_SETTINGS', true);

// Documents
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings';
$array_url_instruction['system'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:system';
$array_url_instruction['smtp'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:smtp';
$array_url_instruction['security'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:security';
$array_url_instruction['plugin'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:plugin';
$array_url_instruction['cronjobs'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:cronjobs';
$array_url_instruction['ftp'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:ftp';
$array_url_instruction['variables'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:setting:variables';

/**
 * nv_admin_add_theme()
 *
 * @param mixed $contents
 */
function nv_admin_add_theme($contents)
{
    global $global_config, $module_file, $my_head, $my_footer, $nv_Lang;

    $xtpl = new XTemplate('cronjobs_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);

    $my_head .= '<link type="text/css" href="' . ASSETS_STATIC_URL . "/js/jquery-ui/jquery-ui.min.css\" rel=\"stylesheet\" />\n";

    $my_footer .= '<script type="text/javascript" src="' . ASSETS_STATIC_URL . "/js/jquery-ui/jquery-ui.min.js\"></script>\n";
    $my_footer .= '<script type="text/javascript" src="' . ASSETS_LANG_STATIC_URL . '/js/language/jquery.ui.datepicker-' . NV_LANG_INTERFACE . ".js\"></script>\n";

    if ($contents['is_error']) {
        $xtpl->parse('main.error');
    }

    $xtpl->assign('DATA', $contents);

    foreach ($contents['run_file'][2] as $run) {
        $xtpl->assign('RUN_FILE', ['key' => $run, 'selected' => $contents['run_file'][3] == $run ? ' selected="selected"' : '']);
        $xtpl->parse('main.run_file');
    }

    for ($i = 0; $i < 24; ++$i) {
        $xtpl->assign('HOUR', ['key' => $i, 'selected' => $i == $contents['hour'][1] ? ' selected="selected"' : '']);
        $xtpl->parse('main.hour');
    }

    for ($i = 0; $i < 60; ++$i) {
        $xtpl->assign('MIN', ['key' => $i, 'selected' => $i == $contents['min'][1] ? ' selected="selected"' : '']);
        $xtpl->parse('main.min');
    }

    for ($i = 0; $i < 2; ++$i) {
        $xtpl->assign('INTER_VAL_TYPE', [
            'key' => $i,
            'title' => $nv_Lang->getModule('cron_interval_type' . $i),
            'selected' => $i == $contents['inter_val_type'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.inter_val_type');
    }

    $xtpl->assign('DELETE', !empty($contents['del'][1]) ? ' checked="checked"' : '');

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * Cập nhật lại thời điểm thực hiện tiếp theo của Cronjob
 * @return bool
 */
function update_cronjob_next_time()
{
    global $nv_Cache, $db;

    // Kiểm tra xem cron đang chạy không, nếu đang chạy thì không cập nhật
    $files = nv_scandir(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/', '/^cronjobs\_(.*)\.txt/i');
    $timeout = NV_CURRENTTIME - 300;
    foreach ($files as $file) {
        if (@filemtime(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $file) > $timeout) {
            return true;
        }
    }

    // Xác định thời điểm chạy tiếp theo
    $cronjobs_next_time = 0;
    $sql = 'SELECT start_time, inter_val, inter_val_type, last_time FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE act=1';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        if (empty($row['last_time'])) {
            $next_time = $row['start_time'];
        } else {
            $next_time = $row['last_time'] + ($row['inter_val'] * 60);
        }
        if (empty($cronjobs_next_time) or $cronjobs_next_time > $next_time) {
            $cronjobs_next_time = $next_time;
        }
    }

    if ($cronjobs_next_time > 0 and $db->exec('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $cronjobs_next_time . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'cronjobs_next_time' AND (CAST(config_value AS UNSIGNED) <= " . NV_CURRENTTIME . ' OR CAST(config_value AS UNSIGNED) >= ' . $cronjobs_next_time . ')')) {
        $nv_Cache->delMod('settings');
    }

    $nv_Cache->delMod('settings');
}

/**
 * Lấy số module ảo+chính của 1 module
 *
 * @param string $module_file
 * @return number
 */
function get_num_mod($module_file)
{
    global $site_mods;

    $num = 0;
    foreach ($site_mods as $mod) {
        if ($mod['module_file'] == $module_file) {
            ++$num;
        }
    }

    return $num;
}

/**
 * get_max_pulgin()
 * Hàm tính số plugin tối đa của 1 file
 *
 * @param string $hook
 * @param string $receive
 * @return number
 */
function get_max_pulgin($hook, $receive)
{
    return (empty($hook) ? 1 : get_num_mod($hook)) * (empty($receive) ? 1 : get_num_mod($receive));
}

/**
 * get_list_ips()
 * Lấy danh sách IPs bị cấm hoặc bỏ flood
 *
 * @param int $type
 * @return array|void
 */
function get_list_ips($type)
{
    global $db, $db_config, $nv_Lang, $ips;

    $masklist = [
        0 => '255.255.255.255',
        3 => '255.255.255.xxx',
        2 => '255.255.xxx.xxx',
        1 => '255.xxx.xxx.xxx'
    ];
    $arealist = [$nv_Lang->getModule('area_select'), $nv_Lang->getModule('area_front'), $nv_Lang->getModule('area_admin'), $nv_Lang->getModule('area_both')];

    $sql = 'SELECT id, ip, mask, area, begintime, endtime FROM ' . $db_config['prefix'] . '_ips WHERE type = ' . $type . ' ORDER BY id DESC';
    $result = $db->query($sql);

    $list = [];
    while ($row = $result->fetch()) {
        $status = $row['begintime'] > NV_CURRENTTIME ? 2 : ((!empty($row['endtime']) and $row['endtime'] < NV_CURRENTTIME) ? 0 : 1);
        $note = [];
        $note[] = $nv_Lang->getModule('ip_mask') . ': ' . ($ips->isIp4($row['ip']) ? $masklist[$row['mask']] : '/' . $row['mask']);
        !$type && $note[] = $nv_Lang->getModule('banip_area') . ': ' . $arealist[$row['area']];
        !empty($row['begintime']) && $note[] = $nv_Lang->getModule('start_time') . ': ' . date('d/m/Y H:i', $row['begintime']);
        $note[] = $nv_Lang->getModule('end_time') . ': ' . (!empty($row['endtime']) ? date('d/m/Y H:i', $row['endtime']) : $nv_Lang->getModule('unlimited'));

        $row['note'] = implode(', ', $note);
        $row['status'] = $status === 2 ? $nv_Lang->getModule('waiting') : ($status === 0 ? $nv_Lang->getModule('ended') : $nv_Lang->getModule('running'));
        $row['status_icon'] = $status === 2 ? 'fa-pause' : ($status === 0 ? 'fa-stop' : 'fa-play');
        $row['class'] = $status === 0 ? ' text-muted' : '';
        $list[$row['id']] = $row;
    }

    return $list;
}
