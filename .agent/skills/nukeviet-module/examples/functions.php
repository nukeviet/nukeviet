<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

// Module đơn giản — chỉ define hằng
// define('NV_IS_MOD_TENMODULE', true);

// Module phức tạp — có thể thêm logic khởi tạo:
if (!in_array($op, ['viewcat', 'detail'], true)) {
    define('NV_IS_MOD_TENMODULE', true);
}

// Load shared helper dùng chung cả frontend lẫn admin
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

// Có thể khởi tạo dữ liệu dùng chung ($global_array_cat, menu dọc, v.v.)
