<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 03 Jul 2014 04:35:32 GMT
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

global $module_config, $array_subject_admin, $admin_id;

if (!function_exists('nv_laws_array_subject_admin')) {

    /**
     * nv_laws_array_subject_admin()
     *
     * @return
     */
    function nv_laws_array_subject_admin($module_data)
    {
        global $db_slave;

        $array_subject_admin = [];
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins ORDER BY userid ASC';
        $result = $db_slave->query($sql);

        while ($row = $result->fetch()) {
            $array_subject_admin[$row['userid']][$row['subjectid']] = $row;
        }

        return $array_subject_admin;
    }
}

$is_refresh = false;
$array_subject_admin = nv_laws_array_subject_admin($module_data);

if (!empty($module_info['admins'])) {
    $module_admin = explode(',', $module_info['admins']);
    foreach ($module_admin as $userid_i) {
        if (!isset($array_subject_admin[$userid_i])) {
            $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_admins (userid, subjectid, admin, add_content, edit_content, del_content) VALUES (' . $userid_i . ', 0, 1, 1, 1, 1)');
            $is_refresh = true;
        }
    }
}
if ($is_refresh) {
    $array_subject_admin = nv_laws_array_subject_admin($module_data);
}

$admin_id = $admin_info['admin_id'];
$NV_IS_ADMIN_MODULE = false;
$NV_IS_ADMIN_FULL_MODULE = false;
if (defined('NV_IS_SPADMIN')) {
    $NV_IS_ADMIN_MODULE = true;
    $NV_IS_ADMIN_FULL_MODULE = true;
} else {
    if (isset($array_subject_admin[$admin_id][0])) {
        $NV_IS_ADMIN_MODULE = true;
        if (intval($array_subject_admin[$admin_id][0]['admin']) == 2) {
            $NV_IS_ADMIN_FULL_MODULE = true;
        }
    }
}

if ($NV_IS_ADMIN_MODULE) {
    $submenu['signer'] = $lang_module['signer'];
    $submenu['area'] = $lang_module['area'];
    $submenu['cat'] = $lang_module['cat'];
    $submenu['subject'] = $lang_module['subject'];
}

if ($NV_IS_ADMIN_FULL_MODULE) {
    $submenu['admins'] = $lang_module['admins'];
    $submenu['config'] = $lang_module['config'];
}
if ($module_config[$module_name]['activecomm']) {
    $submenu['examine'] = $lang_module['examine'];
}
