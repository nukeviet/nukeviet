<?php
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
