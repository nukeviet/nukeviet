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
    'name'        => 'Tên module',
    // ... Khai báo danh sách func frontend phụ (nếu có) vào đây. Hàm main luôn đi đầu tiên (Mặc định).
    'modfuncs'    => 'main',
    'is_sysmod'   => 0,
    'virtual'     => 1,
    'version'     => '5.0.00',
    'date'        => 'Thu, 1 Jan 2026 00:00:00 GMT',
    'author'      => 'Tác giả',
    'note'        => '',
    'uploads_dir' => [$module_upload],  // dùng $module_upload, không phải $module_name

    // --- Các key tùy chọn (dùng khi cần) ---

    // Func hỗ trợ tái tạo alias — danh sách con của modfuncs
    // 'change_alias' => 'func1,func2',

    // Func hiển thị dưới dạng submenu trong admin (thay vì menu chính)
    // 'submenu'      => 'func1,func2',

    // Thư mục uploads phụ (ngoài thư mục gốc)
    // 'uploads_dir'  => [$module_upload, $module_upload . '/source', $module_upload . '/thumb'],

    // Thư mục files/ (khác với uploads/) — vd: chứa file cấu hình, dữ liệu tĩnh
    // 'files_dir'    => [$module_upload . '/topics'],
];
