<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 1:58
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$allow_func = array( 'main', 'language', 'smtp' );
if (defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['idsite'] > 0)) {
    $allow_func[] = 'system';
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
    $allow_func[] = 'cdn';
}

$menu_top = array(
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_settings')
);

unset($page_title, $select_options);

define('NV_IS_FILE_SETTINGS', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings';
$array_url_instruction['system'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:system';
$array_url_instruction['smtp'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:smtp';
$array_url_instruction['security'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:security';
$array_url_instruction['plugin'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:plugin';
$array_url_instruction['cronjobs'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:cronjobs';
$array_url_instruction['ftp'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:ftp';
$array_url_instruction['variables'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:setting:variables';
