<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 5:50
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

if ($admin_info['level'] == 1) {
    $allow_func[] = 'logs_del';
}

$menu_top = array(
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_siteinfo']
);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo';
$array_url_instruction['system_info'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#cấu_hinh_site';
$array_url_instruction['php_info_configuration'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#cấu_hinh_php';
$array_url_instruction['php_info_modules'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#tiện_ich_mở_rộng';
$array_url_instruction['php_info_environment'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#cac_biến_moi_truờng';
$array_url_instruction['php_info_variables'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#cac_biến_tiền_dịnh';
$array_url_instruction['logs'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#nhật_ky_hệ_thống';

define('NV_IS_FILE_SITEINFO', true);

/**
 * nv_siteinfo_getlang()
 *
 * @return
 */
function nv_siteinfo_getlang()
{
    global $db_config, $nv_Cache;
    $sql = 'SELECT DISTINCT lang FROM ' . $db_config['prefix'] . '_logs';
    $result = $nv_Cache->db($sql, 'lang', 'siteinfo');
    $array_lang = array();

    if (!empty($result)) {
        foreach ($result as $row) {
            $array_lang[] = $row['lang'];
        }
    }

    return $array_lang;
}

/**
 * nv_siteinfo_getuser()
 *
 * @return
 */
function nv_siteinfo_getuser()
{
    global $db_config, $nv_Cache;
    $sql = 'SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN ( SELECT DISTINCT userid FROM ' . $db_config['prefix'] . '_logs WHERE userid!=0 ) ORDER BY username ASC';
    $result = $nv_Cache->db($sql, 'userid', 'siteinfo');
    $array_user = array();

    if (!empty($result)) {
        foreach ($result as $row) {
            $array_user[] = array(
                'userid' => $row['userid'],
                'username' => $row['username']
            );
        }
    }

    return $array_user;
}

/**
 * nv_siteinfo_getmodules()
 *
 * @return
 */
function nv_siteinfo_getmodules()
{
    global $db_config, $nv_Cache;
    $sql = 'SELECT DISTINCT module_name FROM ' . $db_config['prefix'] . '_logs';
    $result = $nv_Cache->db($sql, 'module_name', 'siteinfo');
    $array_modules = array();

    if (!empty($result)) {
        foreach ($result as $row) {
            $array_modules[] = $row['module_name'];
        }
    }

    return $array_modules;
}

/**
 * nv_get_lang_module()
 *
 * @param mixed $mod
 * @return
 */
function nv_get_lang_module($mod)
{
    global $site_mods;

    $lang_module = array();

    if (isset($site_mods[$mod])) {
        if (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_' . NV_LANG_INTERFACE . '.php')) {
            include NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_' . NV_LANG_INTERFACE . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_' . NV_LANG_DATA . '.php')) {
            include NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_' . NV_LANG_DATA . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_en.php')) {
            include NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_en.php';
        }
    }
    return $lang_module;
}