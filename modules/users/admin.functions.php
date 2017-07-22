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
