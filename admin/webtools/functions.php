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

$allow_func = [
    'main',
    'statistics',
    'clearsystem'
];
if (empty($global_config['idsite'])) {
    $allow_func[] = 'checkupdate';
    $allow_func[] = 'config';
}

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_webtools']
];

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
