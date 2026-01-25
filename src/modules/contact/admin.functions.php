<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

if (defined('NV_IS_SPADMIN')) {
    $allow_func = [
        'main',
        'department',
        'send',
        'config',
        'supporter'
    ];
} else {
    $allow_func = [
        'main',
        'department',
        'send'
    ];
}

//Tài liệu hướng dẫn
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:contact';
$array_url_instruction['config'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:contact#cấu_hinh_module';
$array_url_instruction['supporter'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:contact#hiển_thị_danh_sach_cac_nhan_vien_hỗ_trợ';
$array_url_instruction['department'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:contact#quản_ly_cac_bộ_phận';
$array_url_instruction['send'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:contact#gửi_phản_hồi';
$array_url_instruction['supporter-content'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:contact#them_nhan_vien_hỗ_trợ';
$array_url_instruction['row'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:contact#them_bộ_phận';

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

/**
 * nv_getAllowed()
 * Lấy danh sách các bộ phận được phép xem, phản hồi và nhận qua email
 *
 * @return array
 */
function nv_getAllowed()
{
    global $admin_info, $nv_Lang;

    $contact_allowed = [
        'view' => [],
        'exec' => [],
        'reply' => [],
        'obt' => []
    ];

    if (defined('NV_IS_SPADMIN')) {
        $contact_allowed['view'][0] = $nv_Lang->getModule('is_default');
        $contact_allowed['exec'][0] = $nv_Lang->getModule('is_default');
        $contact_allowed['reply'][0] = $nv_Lang->getModule('is_default');
        $contact_allowed['obt'][0] = $nv_Lang->getModule('is_default');
    }

    $departments = get_department_list();
    foreach ($departments as $id => $row) {
        $id = (int) $id;
        if (defined('NV_IS_SPADMIN')) {
            $contact_allowed['view'][$id] = $row['full_name'];
            $contact_allowed['exec'][$id] = $row['full_name'];
            $contact_allowed['reply'][$id] = $row['full_name'];
        }

        $admins = !empty($row['admins']) ? json_decode($row['admins'], true) : [];
        if (!empty($admins)) {
            if (!empty($admins['view_level']) and in_array((int) $admin_info['admin_id'], $admins['view_level'], true)) {
                $contact_allowed['view'][$id] = $row['full_name'];
            }
            if (!empty($admins['exec_level']) and in_array((int) $admin_info['admin_id'], $admins['exec_level'], true)) {
                $contact_allowed['exec'][$id] = $row['full_name'];
            }
            if (!empty($admins['reply_level']) and in_array((int) $admin_info['admin_id'], $admins['reply_level'], true)) {
                $contact_allowed['reply'][$id] = $row['full_name'];
            }
            if (!empty($admins['obt_level']) and in_array((int) $admin_info['admin_id'], $admins['obt_level'], true)) {
                $contact_allowed['obt'][$id] = $row['full_name'];
            }
        }
    }

    return $contact_allowed;
}

/**
 * get_department_list()
 * Danh sách các bộ phận
 *
 * @return array
 */
function get_department_list()
{
    global $nv_Cache, $module_name;

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_department ORDER BY weight';

    return $nv_Cache->db($sql, 'id', $module_name);
}

/**
 * get_supporter_list()
 * Danh sách các nhân viên hỗ trợ
 *
 * @return array
 */
function get_supporter_list()
{
    global $nv_Cache, $module_name;

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_supporter ORDER BY departmentid, weight';

    return $nv_Cache->db($sql, 'id', $module_name);
}

define('NV_IS_FILE_ADMIN', true);
