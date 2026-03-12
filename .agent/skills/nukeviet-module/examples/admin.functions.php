<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// PATTERN 1: Module Đơn Giản (như module Page)

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

// Danh sách func admin được phép
$allow_func = ['main', 'content', 'del', 'edit'];
define('NV_IS_FILE_ADMIN', true);

// Func chỉ dành riêng cho super admin
if (defined('NV_IS_SPADMIN')) {
    $allow_func[] = 'config';
}

// Load shared helper (nếu có global.functions.php)
// require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

/* ==========================================================
// PATTERN 2: Module Phức Tạp (như module News)
// $allow_func được khai báo trong admin.menu.php thay vì ở đây

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

define('NV_IS_FILE_ADMIN', true);

// Load shared helper
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

// Hàm hiển thị dùng trong nhiều trang admin (vd: dropdown danh mục)
function nv_tenmodule_show_cat_list($selected = 0)
{
    // ...
}
========================================================== */
