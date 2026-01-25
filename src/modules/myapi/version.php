<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$module_version = [
    'name' => 'API',
    'modfuncs' => 'main',
    'is_sysmod' => 1,
    'virtual' => 0,
    'version' => '5.0.00',
    'date' => 'Thursday, November 17, 2022 11:00:00 AM GMT+07:00',
    'author' => 'VINADES.,JSC <contact@vinades.vn>',
    'note' => '',
    'uploads_dir' => [
        $module_upload
    ],
    'icon' => 'fa-brands fa-nfc-symbol'
];
