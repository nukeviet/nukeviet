<?php
/**
 * Pattern bảo mật CSRF chuẩn NukeViet 5
 *
 * KHÔNG có hàm nv_check_formtoken() trong NukeViet 5.
 * Cơ chế chống CSRF dùng hằng NV_CHECK_SESSION:
 */

// Cách 1: Kiểm tra checkss param trùng NV_CHECK_SESSION (phổ biến nhất)
if ($nv_Request->get_title('checkss', 'post') != NV_CHECK_SESSION) {
    // Không hợp lệ → báo lỗi hoặc redirect
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Cách 2: Dùng md5 kết hợp để tạo checkss cho các hành động đặc biệt
$checkss = md5(NV_CHECK_SESSION . '_' . $id);
if ($nv_Request->get_title('checkss', 'post') != $checkss) {
    exit('Stop!!!');
}

// Cách 3: Truyền checkss vào form ẩn (trong template XTemplate hoặc Smarty)
// <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
