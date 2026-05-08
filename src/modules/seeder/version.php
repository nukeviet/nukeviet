<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$module_version = [
    'name' => 'Seeder',
    'modfuncs' => '',
    'is_sysmod' => 0,
    'virtual' => 0,
    'version' => '1.0.00',
    'date' => 'Saturday, May 02, 2026 12:00:00 PM GMT+07:00',
    'author' => 'VINADES.,JSC <contact@vinades.vn>',
    'note' => 'Tool seeder dữ liệu demo generic cho theme NukeViet 5 (CLI + Admin UI). Manifest JSON đặt trong data/.',
    'uploads_dir' => [
        $module_upload
    ],
    'icon' => 'fa-solid fa-database'
];
