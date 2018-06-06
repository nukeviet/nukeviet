<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

define('NV_IS_FILE_ADMIN', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users';
$array_url_instruction['user_add'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#them_tai_khoản_mới';
$array_url_instruction['user_waiting'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#thanh_vien_dợi_kich_hoạt';
$array_url_instruction['groups'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#nhom_thanh_vien';
$array_url_instruction['question'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#cau_hỏi_bảo_mật';
$array_url_instruction['siteterms'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#nội_quy_site';
$array_url_instruction['fields'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#tuy_biến_dữ_liệu';
$array_url_instruction['config'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#cấu_hinh_module_thanh_vien';

define('NV_MOD_TABLE', ($module_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $module_data);

// Xác định cấu hình module
$global_users_config = array();
$cacheFile = NV_LANG_DATA . '_' . $module_data . '_config_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 3600;
if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_users_config = unserialize($cache);
} else {
    $sql = "SELECT config, content FROM " . NV_MOD_TABLE . "_config";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $global_users_config[$row['config']] = $row['content'];
    }
    $cache = serialize($global_users_config);
    $nv_Cache->setItem($module_name, $cacheFile, $cache, $cacheTTL);
}

require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$array_systemfield_cfg = array(
    'first_name' => array(0, 100),
    'last_name' => array(0, 100),
    'question' => array(3, 255),
    'answer' => array(3, 255),
    'sig' => array(0, 1000)
);
