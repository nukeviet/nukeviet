<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/*
 * Tạo gói nâng cấp module giả lập để thử luồng install/update.php
 *
 * Cách dùng:
 *   php tools/update-package/make-module.php <module_file> [--from=5.0.00] [--files=a,b] [--fail=warn|error] [--auto=0|1|2] [--docs]
 *
 * <module_file> Thư mục module trong src/modules, module phải đang được cài trên site
 * --from   Phiên bản module đang cài (cột version của bảng setup_extensions), mặc định đọc từ modules/<module_file>/version.php
 * --files  Danh sách file, thư mục (so với src) chép vào gói, cách nhau dấu phẩy
 * --fail   Thêm tác vụ luôn thất bại: warn là cảnh báo rồi chạy tiếp, error là dừng tiến trình
 * --auto   Kiểu nâng cấp: 0 bằng tay, 1 tự động (mặc định), 2 nửa tự động
 * --docs   Tạo thêm src/install/update_docs_*.html để thử bước hướng dẫn nâng cấp
 */

require __DIR__ . '/common.php';

[$args, $options] = upk_parse_args($argv);

$module = $args[0] ?? '';
if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $module) or !is_dir(UPK_SRCDIR . '/modules/' . $module)) {
    upk_die('Cần truyền tên thư mục module có trong src/modules, VD: php tools/update-package/make-module.php news');
}

$from = $options['from'] ?? '';
if ($from === '' or $from === true) {
    $version_file = UPK_SRCDIR . '/modules/' . $module . '/version.php';
    if (!is_file($version_file) or !preg_match("/'version'[ ]*=>[ ]*'([^']+)'/", file_get_contents($version_file), $m)) {
        upk_die('Không đọc được phiên bản từ src/modules/' . $module . '/version.php, hãy truyền --from');
    }
    $from = $m[1];
}

// Mặc định chép funcs, language của module và giao diện module trong theme future, admin_future
$paths = [];
foreach (['modules/' . $module . '/funcs', 'modules/' . $module . '/language', 'themes/future/modules/' . $module, 'themes/admin_future/modules/' . $module] as $path) {
    if (is_dir(UPK_SRCDIR . '/' . $path)) {
        $paths[] = $path;
    }
}
if (empty($paths)) {
    $paths[] = 'modules/' . $module . '/version.php';
}

upk_make([
    'formodule' => $module,
    'from' => $from,
    'paths' => $paths,
    'options' => $options
]);
