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

unset($page_title, $select_options);

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_authors')
];
define('NV_IS_FILE_AUTHORS', true);

// Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#quản_trị';
$array_url_instruction['add'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#them_quản_trị';
$array_url_instruction['module'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#quyền_hạn_quản_ly_module';
$array_url_instruction['config'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#cấu_hinh';
