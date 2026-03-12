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
 * Pattern bảo mật CSRF chuẩn NukeViet 5
 *
 * Tiêu chuẩn khuyến nghị: Sử dụng hash_hmac kết hợp hash_equals.
 * Tạo chuỗi expected chung cho module admin tại đầu file.
 */

// Tạo token chống CSRF
$checkss_expected = hash_hmac('sha256', NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['admin_id'], NV_CACHE_PREFIX);

// Cách 1: Kiểm tra checkss param tại các action ajax / submit form POST
if (!hash_equals($checkss_expected, $nv_Request->get_title('checkss', 'post', ''))) {
    // Không hợp lệ → báo lỗi hoặc redirect (tùy định dạng trả về)
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Error session!!!'
    ]);
}

// Cách 2: Truyền checkss vào template (XTemplate hoặc Smarty)
// $xtpl->assign('CHECKSS_EXPECTED', $checkss_expected);
// Tpl: <script>var module_checkss = '{CHECKSS_EXPECTED}';</script> hoặc <input type="hidden" name="checkss" value="{CHECKSS_EXPECTED}" />
