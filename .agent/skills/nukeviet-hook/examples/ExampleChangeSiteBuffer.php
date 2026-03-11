<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/**
 * Hook change_site_buffer: Can thiệp toàn bộ HTML trước khi xuất ra browser
 * Tham chiếu thực tế: src/includes/footer.php dòng 33
 * [$contents, $headers] = nv_apply_hook('', 'change_site_buffer', [$global_config, [$contents, $headers]], [$contents, $headers]);
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$callback = function ($vars, $from_data, $receive_data) {
    // $vars = [$global_config, [$contents, $headers]]
    // $receive_data = [$contents, $headers] (giá trị default)
    $contents = $vars[1][0] ?? $receive_data[0];
    $headers = $vars[1][1] ?? $receive_data[1];

    // Ví dụ: Thêm comment vào cuối trang
    $contents .= "\n<!-- Hook by tenmodule -->";

    return [$contents, $headers];
};

// Module hệ thống: $module_name = '' (luôn là chuỗi rỗng khi load từ system)
nv_add_hook($module_name, 'change_site_buffer', $priority, $callback, $hook_module, $pid);
