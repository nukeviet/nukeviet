<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_zalo']
];

$allow_func = [
    'main'
];
if (defined('NV_IS_GODADMIN')) {
    $allow_func[] = 'settings';
}
unset($page_title, $select_options);

define('NV_IS_FILE_ZALO', true);

function accessTokenUpdate($result)
{
    global $db, $nv_Cache;

    $array_config_site = [];
    $array_config_site['zaloOAAccessToken'] = $result['access_token'];
    $array_config_site['zaloOARefreshToken'] = $result['refresh_token'];
    $array_config_site['zaloOAAccessTokenTime'] = NV_CURRENTTIME;

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll(false);
}
