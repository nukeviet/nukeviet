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

$allow_func = [
    'main',
    'content',
    'content-del',
    'content-change-status',
    'content-change-weight',
    'cat',
    'cat-alias',
    'cat-del',
    'cat-change-status',
    'cat-change-weight',
    'content-alias'
];

define('NV_IS_FILE_ADMIN', true);

if (defined('NV_IS_SPADMIN')) {
    $allow_func[] = 'config';
}

// Lấy cấu hình module từ biến hệ thống
$config = $module_config[$module_name];

// Khởi tạo danh sách bảng DB cho module — dùng chung cho mọi Repository
use NukeViet\Module\Content\Shared\Tables;
$tables = new Tables(NV_PREFIXLANG, $module_data);
