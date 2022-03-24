<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'Zalo';
$lang_module['settings'] = 'Cấu hình';
$lang_module['zalo_official_account_id'] = 'Official Account ID (OAID)';
$lang_module['oa_create_note'] = '<ul><li>Nếu chưa có Official Account, bạn có thể tạo nó <a href="%s" target="_blank">tại đây</a></li><li>Để lấy OAID, hãy truy cập <a href="%s" target="_blank">tại đây</a></li></ul>';
$lang_module['app_id'] = 'ID ứng dụng';
$lang_module['app_secret_key'] = 'Khóa bí mật của ứng dụng';
$lang_module['app_note'] = '<ul><li>Nếu chưa có ứng dụng, bạn có thể tạo nó <a href="%s" target="_blank">tại đây</a>.</li><li>Truy cập trang <a href="%s" target="_blank">Quản lý ứng dụng</a>, click vào ứng dụng mong muốn để đến trang cài đặt, copy ID ứng dụng và Khóa bí mật của ứng dụng vào 2 ô tương ứng bên (Chú ý: Nếu ứng dụng đang ở trạng thái Chưa kích hoạt, bạn cần click vào nút chuyển ở góc phải phía trên để chuyển ứng dụng sang trạng thái Đang hoạt động).</li><li>Click vào nút «Official Account» => «Quản lý Official Account» ở thanh menu bên trái để thiết lập liên kết với Official Account mà bạn khai báo trên.</li><li>Click vào nút «Official Account» => «Thiết lập chung» ở thanh menu bên trái, tìm đến ô «Official Account Callback Url» và click vào nút «Cập nhật» để nhập vào giá trị sau:<code>%s</code>, sau đó click nút «Lưu» bên cạnh. Tiếp tục đến khu vực «Chọn quyền cần yêu cầu được cấp từ OA», chọn tất cả các quyền sử dụng API và click vào nút «Lưu»</li><li>Click vào nút «Đăng nhập», ở trang hiện ra click vào nút «Web», khai báo ô «Home URL»  giá trị: <code>%s</code>, sau đó lần lượt thêm 2 giá trị sau vào ô «Callback URL»: <code>%s</code> và <code>%s</code>, click vào «Lưu thay đổi».</li></ul>';
$lang_module['access_token'] = 'Mã thực thi (access token)';
$lang_module['refresh_token'] = 'Mã làm mới (refresh token)';
$lang_module['submit'] = 'Thực hiện';
$lang_module['access_token_create'] = 'Tạo Mã thực thi';
$lang_module['oa_id_empty'] = 'Lỗi: Official Account ID chưa được khai báo';
$lang_module['redirect_uri_empty'] = 'Lỗi: Callback Url chưa được khai báo';
$lang_module['app_id_empty'] = 'Lỗi: ID ứng dụng chưa được khai báo';
$lang_module['app_seckey_empty'] = 'Lỗi: Khóa bí mật của ứng dụng chưa được khai báo';
$lang_module['refresh_token_empty'] = 'Lỗi: Mã làm mới chưa được xác định';
$lang_module['not_response'] = 'Lỗi: Không có dữ liệu trả về';
$lang_module['oa_id_incorrect'] = 'Lỗi: OAID trả về không khớp với OAID mà bạn khai báo';
$lang_module['refresh_token_expired'] = 'Lỗi: Mã làm mới đã hết hạn';
