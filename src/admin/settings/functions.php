<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
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
    $allow_func[] = 'plugin';
    $allow_func[] = 'variables';
    $allow_func[] = 'ssettings';
    $allow_func[] = 'cdn_backendhost';
    $allow_func[] = 'custom';
    $allow_func[] = 'trusted-proxies';
}

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_settings')
];

unset($page_title, $select_options);

define('NV_IS_FILE_SETTINGS', true);

// Documents
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet5:admin:settings';
$array_url_instruction['system'] = 'https://wiki.nukeviet.vn/nukeviet5:admin:settings:system';
$array_url_instruction['smtp'] = 'https://wiki.nukeviet.vn/nukeviet5:admin:settings:smtp';
$array_url_instruction['security'] = 'https://wiki.nukeviet.vn/nukeviet5:admin:settings:security';
$array_url_instruction['plugin'] = 'https://wiki.nukeviet.vn/nukeviet5:admin:settings:plugin';
$array_url_instruction['cronjobs'] = 'https://wiki.nukeviet.vn/nukeviet5:admin:settings:cronjobs';
$array_url_instruction['ftp'] = 'https://wiki.nukeviet.vn/nukeviet5:admin:settings:ftp';
$array_url_instruction['variables'] = 'https://wiki.nukeviet.vn/nukeviet5:admin:settings:variables';
$array_url_instruction['trusted-proxies'] = 'https://wiki.nukeviet.vn/nukeviet5:admin:settings:trusted-proxies';

/**
 * Cập nhật lại thời điểm thực hiện tiếp theo của Cronjob
 * @return bool|void
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

    if ($cronjobs_next_time > 0) {
        $stmt = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :next_time WHERE lang = :lang AND module = 'global' AND config_name = 'cronjobs_next_time' AND (CAST(config_value AS UNSIGNED) <= :current_time OR CAST(config_value AS UNSIGNED) >= :next_time2)");
        $stmt->bindValue(':next_time', $cronjobs_next_time, PDO::PARAM_STR);
        $stmt->bindValue(':next_time2', $cronjobs_next_time, PDO::PARAM_STR);
        $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
        $stmt->bindValue(':current_time', NV_CURRENTTIME, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $nv_Cache->delMod('settings');
        }
    }

    $nv_Cache->delMod('settings');
}

/**
 * Lấy số module ảo+chính của 1 module
 *
 * @param string $module_file
 * @return int
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
 * @return int
 */
function get_max_pulgin($hook, $receive)
{
    return (empty($hook) ? 1 : get_num_mod($hook)) * (empty($receive) ? 1 : get_num_mod($receive));
}

/**
 * Lấy danh sách IPs bị cấm hoặc bỏ flood
 *
 * @param int $type
 * @param array $search Điều kiện lọc: q (từ khóa), area, from (unixtime bắt đầu), to (unixtime kết thúc)
 * @param int $page Trang hiện tại, 0 là lấy hết
 * @param int $perPage Số dòng mỗi trang
 * @return array [danh sách, tổng số dòng]
 */
function get_list_ips($type, $search = [], $page = 0, $perPage = 0)
{
    global $db, $db_config, $nv_Lang, $ips;

    $masklist = [
        0 => '255.255.255.255',
        3 => '255.255.255.xxx',
        2 => '255.255.xxx.xxx',
        1 => '255.xxx.xxx.xxx'
    ];
    $arealist = [$nv_Lang->getModule('area_select'), $nv_Lang->getModule('area_front'), $nv_Lang->getModule('area_admin'), $nv_Lang->getModule('area_both')];

    $where = ['type = ' . (int) $type];
    if (!empty($search['q'])) {
        $where[] = '(ip LIKE :q_ip OR notice LIKE :q_notice)';
    }
    if (!empty($search['area'])) {
        $where[] = 'area = ' . (int) $search['area'];
    }
    if (!empty($search['from'])) {
        $where[] = 'begintime >= ' . (int) $search['from'];
    }
    if (!empty($search['to'])) {
        $where[] = 'endtime > 0 AND endtime <= ' . (int) $search['to'];
    }
    $where = ' WHERE ' . implode(' AND ', $where);

    $bindKeyword = function ($stmt) use ($search, $db) {
        if (!empty($search['q'])) {
            $keyword = '%' . $db->dblikeescape($search['q'], true) . '%';
            $stmt->bindValue(':q_ip', $keyword, PDO::PARAM_STR);
            $stmt->bindValue(':q_notice', $keyword, PDO::PARAM_STR);
        }
    };

    $sql = 'SELECT id, ip, mask, area, begintime, endtime, notice FROM ' . $db_config['prefix'] . '_ips' . $where . ' ORDER BY id DESC';
    if ($page > 0 and $perPage > 0) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_ips' . $where);
        $bindKeyword($stmt);
        $stmt->execute();
        $total = (int) $stmt->fetchColumn();

        $sql .= ' LIMIT ' . $perPage . ' OFFSET ' . (($page - 1) * $perPage);
    }

    $stmt = $db->prepare($sql);
    $bindKeyword($stmt);
    $stmt->execute();

    $list = [];
    while ($row = $stmt->fetch()) {
        $status = $row['begintime'] > NV_CURRENTTIME ? 2 : ((!empty($row['endtime']) and $row['endtime'] < NV_CURRENTTIME) ? 0 : 1);
        $row['status'] = $status;
        $row['status_text'] = $status === 2 ? $nv_Lang->getModule('waiting') : ($status === 0 ? $nv_Lang->getModule('ended') : $nv_Lang->getModule('running'));
        $row['mask_text'] = $ips->isIp4($row['ip']) ? $masklist[$row['mask']] : '/' . $row['mask'];
        $row['area_text'] = $arealist[$row['area']] ?? '';
        $row['begintime_text'] = !empty($row['begintime']) ? nv_datetime_format($row['begintime'], 1) : '';
        $row['endtime_text'] = !empty($row['endtime']) ? nv_datetime_format($row['endtime'], 1) : $nv_Lang->getModule('unlimited');

        $note = [];
        $note[] = $nv_Lang->getModule('ip_mask') . ': ' . $row['mask_text'];
        !$type && $note[] = $nv_Lang->getModule('banip_area') . ': ' . $row['area_text'];
        !empty($row['begintime']) && $note[] = $nv_Lang->getModule('start_time') . ': ' . $row['begintime_text'];
        $note[] = $nv_Lang->getModule('end_time') . ': ' . $row['endtime_text'];
        $row['note'] = implode(', ', $note);

        $list[$row['id']] = $row;
    }
    $stmt->closeCursor();

    return [$list, $total ?? count($list)];
}
