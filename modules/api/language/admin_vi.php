<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '17/11/2022, 11:00';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main_title'] = 'API của tôi';
$lang_module['role_management'] = 'Quản lý API-role';
$lang_module['add_role'] = 'Tạo mới API-role';
$lang_module['edit_role'] = 'Sửa API-role';
$lang_module['api_addtime'] = 'Tạo lúc';
$lang_module['api_edittime'] = 'Cập nhật';
$lang_module['api_roles_list'] = 'Danh sách các API-role';
$lang_module['api_roles_empty'] = 'Không tìm thấy API-role theo yêu cầu';
$lang_module['api_roles_empty2'] = 'Chưa có API-role nào được tạo. Hệ thống sẽ tự động chuyển trến trang tạo API-role trong giây lát';
$lang_module['api_roles_title'] = 'Tên gọi';
$lang_module['api_roles_description'] = 'Mô tả';
$lang_module['api_roles_allowed'] = 'Các API';
$lang_module['api_roles_error_title'] = 'Lỗi: Chưa nhập tên API-role';
$lang_module['api_roles_error_exists'] = 'Lỗi: Tên API-role này đã được sử dụng';
$lang_module['api_roles_error_role'] = 'Lỗi: Chưa có API nào được chọn';
$lang_module['api_roles_api_doesnt_exist'] = 'Không nhận diện được API';
$lang_module['api_roles_checkall'] = 'Chọn tất cả';
$lang_module['api_roles_uncheckall'] = 'Bỏ chọn tất cả';
$lang_module['api_roles_detail'] = 'Danh sách các API của';
$lang_module['api_role_notice'] = 'Lưu ý: Tùy theo cấp độ của tài khoản quản trị được cấp phép mà các API được quyền sử dụng trong mỗi API-role sẽ được xác định lại';
$lang_module['api_role_notice_lang'] = 'Các API của hệ thống sẽ hiệu lực đối với tất cả các ngôn ngữ. Các API của module chỉ có hiệu lực đối với ngôn ngữ hiện tại.';
$lang_module['api_of_system'] = 'Hệ thống';
$lang_module['api_role_credential'] = 'Quản lý người sử dụng API-role';
$lang_module['api_role_credential_empty'] = 'API-role này chưa có người sử dụng';
$lang_module['api_role_select'] = 'Hãy chọn API-role';
$lang_module['api_role'] = 'API-role';
$lang_module['api_role_credential_add'] = 'Thêm người sử dụng';
$lang_module['api_role_credential_edit'] = 'Sửa thông tin người sử dụng';
$lang_module['api_role_credential_search'] = 'Tìm kiếm đối tượng';
$lang_module['api_role_credential_error'] = 'Vui lòng khai báo đối tượng được phép sử dụng API-role này';
$lang_module['api_role_credential_addtime'] = 'Bắt đầu';
$lang_module['api_role_credential_access_count'] = 'Số lần gọi<br/>API-role';
$lang_module['api_role_credential_last_access'] = 'Gọi API-role<br/>gần đây';
$lang_module['api_role_credential_userid'] = 'ID';
$lang_module['api_role_credential_username'] = 'Bí danh';
$lang_module['api_role_credential_fullname'] = 'Họ và tên';
$lang_module['status'] = 'Trạng thái';
$lang_module['active'] = 'Hoạt động';
$lang_module['inactive'] = 'Tạm dừng';
$lang_module['activated'] = 'Đã kích hoạt';
$lang_module['not_activated'] = 'Chưa kích hoạt';
$lang_module['suspended'] = 'Đình chỉ';
$lang_module['activate'] = 'Kích hoạt';
$lang_module['deactivate'] = 'Hủy kích hoạt';
$lang_module['api_role_status'] = 'Trạng thái<br/>API-role';
$lang_module['api_role_credential_status'] = 'Trạng thái<br/>người sử dụng';
$lang_module['api_role_credential_unknown'] = 'Đối tượng chưa được xác định';
$lang_module['api_role_credential_count'] = 'Số người sử dụng';
$lang_module['api_role_type'] = 'Loại';
$lang_module['api_role_type_private'] = 'Riêng tư';
$lang_module['api_role_type_public'] = 'Công cộng';
$lang_module['api_role_type_private2'] = 'Các API-role được chỉ định';
$lang_module['api_role_type_public2'] = 'Các API-role công cộng';
$lang_module['api_role_object'] = 'Đối tượng';
$lang_module['api_role_object_admin'] = 'Quản trị viên';
$lang_module['api_role_object_user'] = 'Người dùng';
$lang_module['api_role_type_private_note'] = 'API-role riêng tư là nhóm các API mà đối tượng không thể tự đăng ký sử dụng. Chỉ điều hành chung mới được phép chỉ định API-role riêng tư cho các đối tượng nhất định';
$lang_module['api_role_type_public_note'] = 'API-role công cộng là nhóm các API mà bất kỳ đối tượng nào cũng có thể tự đăng ký để sử dụng';
$lang_module['api_role_type_private_error'] = 'API-role không cho phép tự ý kích hoạt sử dụng';
$lang_module['all'] = 'Tất cả';
$lang_module['authentication'] = 'Xác thực';
$lang_module['not_access_authentication'] = 'Tài khoản chưa được tạo xác thực truy cập API-role';
$lang_module['recreate_access_authentication_info'] = 'Nếu quên mã bí mật, hãy tạo lại xác thực';
$lang_module['create_access_authentication'] = 'Tạo xác thực';
$lang_module['recreate_access_authentication'] = 'Tạo lại xác thực';
$lang_module['api_credential_ident'] = 'Khóa truy cập';
$lang_module['api_credential_secret'] = 'Mã bí mật';
$lang_module['auth_method'] = 'Chọn phương thức';
$lang_module['auth_method_select'] = 'Vui lòng chọn phương thức xác thực';
$lang_module['auth_method_password_verify'] = 'password_verify (khuyên dùng)';
$lang_module['auth_method_md5_verify'] = 'md5_verify';
$lang_module['auth_method_none'] = 'Không xác thực (dùng trong việc phát triển)';
$lang_module['value_copied'] = 'Giá trị đã được sao chép vào bộ nhớ';
$lang_module['api_ips'] = 'IP Truy cập';
$lang_module['api_ips_help'] = 'Các IP được phân cách bởi dấu phẩy. Việc truy cập API-role chỉ được thực hiện từ các IP này. Để trống được hiểu là không kiểm tra IP';
$lang_module['api_ips_update'] = 'Cập nhật IP Truy cập';
$lang_module['deprivation'] = 'Tước quyền';
$lang_module['deprivation_confirm'] = 'Bạn thực sự muốn tước quyền của người dùng này?';
$lang_module['config'] = 'Cài đặt';
$lang_module['remote_api_access'] = 'Bật Remote API';
$lang_module['remote_api_access_help'] = 'Nếu tắt toàn bộ quyền truy cập API từ bên ngoài sẽ bị chặn. Các API bên trong vẫn sử dụng bình thường';
$lang_module['api_remote_off'] = 'Remote API <strong>đang tắt</strong> nên các cuộc gọi API sẽ không thể thực hiện. Để hỗ trợ cuộc gọi API, <strong><a href="%s">hãy bật Remote API tại đây</a></strong>';
$lang_module['api_remote_off2'] = 'Remote API <strong>đang tắt</strong> nên các cuộc gọi API sẽ không thể thực hiện.';
$lang_module['cat_api_list'] = 'Các API trong danh mục';
$lang_module['flood_blocker'] = 'Hạn chế cuộc gọi';
$lang_module['flood_blocker_note'] = 'Nếu để trống các trường ở dòng này, số lượng cuộc gọi sẽ không bị giới hạn';
$lang_module['flood_limit'] = 'Số cuộc gọi tối đa';
$lang_module['flood_interval'] = 'Trong khoảng';
$lang_module['minutes'] = 'phút';
$lang_module['hours'] = 'giờ';
$lang_module['log_period'] = 'Thời lượng lưu nhật ký cuộc gọi API';
$lang_module['log_period_note'] = 'Để trống được hiểu là không ghi nhật ký';
$lang_module['flood_interval_error'] = 'Thời lượng của quy tắc hạn chế truy vấn không thể lớn hơn thời lượng lưu nhật ký truy vấn';
$lang_module['logs'] = 'Nhật ký cuộc gọi API';
$lang_module['log_time'] = 'Gọi vào';
$lang_module['log_ip'] = 'Từ IP';
$lang_module['log_del_confirm'] = 'Bạn thực sự muốn xóa?';
$lang_module['del_selected'] = 'Xóa những dòng đã chọn';
$lang_module['del_all'] = 'Xóa tất cả';
$lang_module['api_select'] = 'Hãy chọn API';
$lang_module['fromdate'] = 'Thời gian gọi từ';
$lang_module['todate'] = 'Thời gian gọi đến';
$lang_module['filter_logs'] = 'Lọc nhật ký';
$lang_module['endtime'] = 'Kết thúc';
$lang_module['quota'] = 'Hạn ngạch';
$lang_module['indefinitely'] = 'Vô thời hạn';
$lang_module['no_quota'] = 'Không giới hạn';
$lang_module['addtime_note'] = 'Để trống được hiểu là thời gian hiện tại';
$lang_module['endtime_note'] = 'Để trống được hiểu là vô thời hạn';
$lang_module['quota_note'] = 'Để trống được hiểu là không giới hạn';
