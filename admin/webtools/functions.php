<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$allow_func = array(
    'main',
    'statistics',
    'clearsystem'
);
if (empty($global_config['idsite'])) {
    $allow_func[] = 'checkupdate';
    $allow_func[] = 'config';
}

$menu_top = array(
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_webtools']
);

if (defined('NV_IS_GODADMIN')) {
    $allow_func[] = 'deleteupdate';
    $allow_func[] = 'getupdate';
}

define('NV_IS_FILE_WEBTOOLS', true);

//Document
$array_url_instruction['clearsystem'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:webtools';
$array_url_instruction['statistics'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:webtools#cấu_hinh_thống_ke';
$array_url_instruction['checkupdate'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:webtools#kiểm_tra_phien_bản';
$array_url_instruction['config'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:webtools';