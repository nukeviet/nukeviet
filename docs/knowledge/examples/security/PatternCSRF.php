<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// 1. Tạo token chống CSRF (thường gán vào template)
$csrf_create = csrf_create($csrf_key);

// 2. Kiểm tra token khi có request POST
if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        // Không hợp lệ → báo lỗi hoặc redirect (tùy định dạng trả về)
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    // Thực hiện xử lý dữ liệu tiếp theo...
}

// Lưu ý: Đối với template (XTemplate hoặc Smarty)
// $xtpl->assign('CHECKSS', $csrf_create);
// Tpl: <input type="hidden" name="checkss" value="{CHECKSS}" />
