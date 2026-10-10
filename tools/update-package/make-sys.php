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
 * Tạo gói nâng cấp hệ thống giả lập để thử luồng install/update.php
 *
 * Cách dùng:
 *   php tools/update-package/make-sys.php [--from=5.0.00] [--files=a,b] [--fail=warn|error] [--auto=0|1|2] [--docs]
 *
 * --from   Phiên bản hiện tại của site, mặc định đọc từ src/data/config/config_global.php
 * --files  Danh sách file, thư mục (so với src) chép vào gói, cách nhau dấu phẩy
 * --fail   Thêm tác vụ luôn thất bại: warn là cảnh báo rồi chạy tiếp, error là dừng tiến trình
 * --auto   Kiểu nâng cấp: 0 bằng tay, 1 tự động (mặc định), 2 nửa tự động
 * --docs   Tạo thêm src/install/update_docs_*.html để thử bước hướng dẫn nâng cấp
 */

require __DIR__ . '/common.php';

[$args, $options] = upk_parse_args($argv);

$from = $options['from'] ?? '';
if ($from === '' or $from === true) {
    $config_file = UPK_SRCDIR . '/data/config/config_global.php';
    if (!is_file($config_file) or !preg_match("/\\\$global_config\['version'\][ ]*=[ ]*'([^']+)'/", file_get_contents($config_file), $m)) {
        upk_die('Không đọc được phiên bản từ src/data/config/config_global.php, hãy truyền --from');
    }
    $from = $m[1];
}

upk_make([
    'formodule' => '',
    'from' => $from,
    'paths' => [
        'admin/siteinfo',
        'includes/core/amlich.php',
        'themes/admin_default/system'
    ],
    'options' => $options
]);
