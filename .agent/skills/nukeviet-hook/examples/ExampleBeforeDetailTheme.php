<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Trước khi module 'page' render trang chi tiết → thêm watermark vào nội dung
$callback = function ($args, $from_data, $receive_data) {
    [$rowdetail, $other_links, $content_comment] = $args;

    // Thêm watermark vào body
    $rowdetail['body'] .= '<p class="watermark">© ' . date('Y') . '</p>';

    return [$rowdetail, $other_links, $content_comment];
};

nv_add_hook($module_name, 'before_detail_theme', $priority, $callback, $hook_module, $pid);
