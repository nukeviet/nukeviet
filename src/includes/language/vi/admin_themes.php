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

$lang_module['blocks'] = 'Quản lý block';
$lang_module['change_func_name'] = 'Thay đổi tên gọi của function &ldquo;%1$s&rdquo; thuộc module &ldquo;%2$s&rdquo;';
$lang_module['bl_list_title'] = 'Các block ở &ldquo;%1$s&rdquo; của function &ldquo;%2$s&rdquo;';
$lang_module['add_block_title'] = 'Thêm block vào &ldquo;%1$s&rdquo; của function &ldquo;%2$s&rdquo; thuộc module &ldquo;%3$s&rdquo;';
$lang_module['edit_block_title'] = 'Sửa block &ldquo;%1$s&rdquo; tại &ldquo;%2$s&rdquo; của function &ldquo;%3$s&rdquo; thuộc module &ldquo;%4$s&rdquo;';
$lang_module['block_add'] = 'Thêm block';
$lang_module['block_edit'] = 'Sửa block';
$lang_module['block_title'] = 'Tên block';
$lang_module['block_link'] = 'URL của tên block';
$lang_module['block_file_path'] = 'Lấy nội dung từ file';
$lang_module['block_global_apply'] = 'Áp dụng cho tất cả';
$lang_module['block_type_theme'] = 'Block của giao diện';
$lang_module['block_select_type'] = '-=Hãy chọn dạng=-';
$lang_module['block_tpl'] = 'Template';
$lang_module['block_pos'] = 'Vị trí';
$lang_module['block_groupbl'] = 'Thuộc nhóm';
$lang_module['block_leavegroup'] = 'Tách ra khỏi nhóm và tạo nhóm mới';
$lang_module['block_group_notice'] = 'Lưu ý: <br />Nếu thay đổi một block thuộc 1 nhóm thì sẽ thay đổi toàn bộ các block khác thuộc nhóm đó. <br/>Nếu không muốn thay đổi các block khác cùng nhóm thì hãy tách block ra thành nhóm mới bằng cách đánh dấu vào mục <em>Tách ra khỏi nhóm và tạo nhóm mới</em>.';
$lang_module['block_group_block'] = 'Nhóm';
$lang_module['block_no_more_func'] = 'Nếu check chọn Bỏ ra khỏi nhóm thì chỉ được chọn 1 function';
$lang_module['block_no_func'] = 'Hãy chọn ít nhất là 1 function';
$lang_module['block_limit_func'] = 'Nếu xác nhận bỏ nhóm thì chỉ được chỉ định 1 function cho cho 1 blocks';
$lang_module['block_func'] = 'Khu vực';
$lang_module['block_nums'] = 'Số block thuộc nhóm';
$lang_module['block_count'] = 'block';
$lang_module['block_func_list'] = 'Các function';
$lang_module['blocks_by_funcs'] = 'Quản lý block theo function';
$lang_module['block_yes'] = 'Có';
$lang_module['block_active'] = 'Kích hoạt';
$lang_module['block_group'] = 'Ai có quyền xem';
$lang_module['block_module'] = 'Hiển thị ở module';
$lang_module['block_all'] = 'Tất cả các module';
$lang_module['block_confirm'] = 'Chấp nhận';
$lang_module['block_default'] = 'Mặc định';
$lang_module['block_exp_time'] = 'Ngày hết hạn';
$lang_module['block_sort'] = 'Sắp xếp';
$lang_module['block_change_pos_warning'] = 'Nếu thay đổi vị trí block này sẽ thay đổi toàn bộ vị trí của các block khác thuộc cùng nhóm ';
$lang_module['block_change_pos_warning2'] = 'Bạn có chắc muốn thay đổi vị trí?';
$lang_module['block_error_nogroup'] = 'Hãy chọn ít nhất 1 nhóm';
$lang_module['block_error_noblock'] = 'Hãy chọn ít nhất 1 block';
$lang_module['block_error_nsblock'] = 'Chưa chọn block hoặc tên block không hợp lệ';
$lang_module['block_delete_confirm'] = 'Bạn có chắc muốn xóa tất cả block được chọn. Nếu xóa việc này sẽ không thể phục hồi được?';
$lang_module['block_delete_per_confirm'] = 'Bạn có chắc muốn xóa block này không?';
$lang_module['block_add_success'] = 'Thêm thành công!';
$lang_module['block_update_success'] = 'Cập nhật thành công!';
$lang_module['block_checkall'] = 'Chọn tất cả';
$lang_module['block_uncheckall'] = 'Bỏ chọn tất cả';
$lang_module['block_delete_success'] = 'Xóa thành công';
$lang_module['block_error_nomodule'] = 'Hãy chọn ít nhất 1 module';
$lang_module['error_empty_content'] = 'Block chưa được kết nối với file, khối quảng cáo hoặc chưa có nội dung';
$lang_module['block_type'] = 'Chọn kiểu block';
$lang_module['block_file'] = 'File';
$lang_module['block_html'] = 'HTML';
$lang_module['block_typehtml'] = 'Dạng HTML';
$lang_module['functions'] = 'Chức năng';
$lang_module['edit_block'] = 'Sửa block';
$lang_module['block_function'] = 'Hãy chọn function';
$lang_module['add_block_module'] = 'Áp dụng cho module';
$lang_module['add_block_all_module'] = 'Tất cả các module';
$lang_module['add_block_select_module'] = 'Chọn module';
$lang_module['block_layout'] = 'Chọn layout';
$lang_module['block_select'] = 'Chọn block';
$lang_module['block_check'] = 'Check';
$lang_module['block_select_module'] = 'Chọn module';
$lang_module['block_select_function'] = 'Chọn function';
$lang_module['block_error_fileconfig_title'] = 'Lỗi file cấu hình giao diện';
$lang_module['block_error_fileconfig_content'] = 'File cấu hình của giao diện không đúng hoặc không tồn tại. Hãy kiểm tra lại trong thư mục theme của bạn';
$lang_module['package_theme_module'] = 'Đóng gói theme module';
$lang_module['autoinstall_continue'] = 'Tiếp tục';
$lang_module['back'] = 'Quay lại';
$lang_module['autoinstall_error_nomethod'] = 'Hãy chọn 1 kiểu cài đặt để tiếp tục !';
$lang_module['autoinstall_package_select'] = 'Chọn theme để đóng gói';
$lang_module['autoinstall_package_noselect'] = 'Hãy chọn 1 theme để đóng gói';
$lang_module['autoinstall_package_module_select'] = 'Chọn module để đóng gói';
$lang_module['autoinstall_package_noselect_module'] = 'Hãy chọn 1 module để đóng gói theme';
$lang_module['autoinstall_method_theme_none'] = 'Hãy chọn theme';
$lang_module['autoinstall_method_module_none'] = 'Hãy chọn module';
$lang_module['package_noselect_module_theme'] = 'Bắt buộc phải chọn theme và tên module để đóng gói';
$lang_module['setup_layout'] = 'Thiết lập layout';
$lang_module['setup_module'] = 'Module';
$lang_module['setup_select_layout'] = 'Chọn layout';
$lang_module['setup_updated_layout'] = 'Thiết lập layout thành công!';
$lang_module['setup_error_layout'] = 'Không thể thực hiện lệnh thiết lập layout';
$lang_module['setup_save_layout'] = 'Lưu tất cả thay đổi';
$lang_module['theme_manager'] = 'Quản lý giao diện';
$lang_module['theme_recent'] = 'Danh sách giao diện hiện có';
$lang_module['theme_created_by'] = 'Thiết kế bởi';
$lang_module['theme_created_website'] = 'ghé thăm website của tác giả';
$lang_module['theme_created_folder'] = 'Các file + thư mục nằm trong:';
$lang_module['theme_created_position'] = 'Các vị trí thiết kế trong theme:';
$lang_module['theme_created_activate'] = 'Kích hoạt sử dụng';
$lang_module['theme_created_setting'] = 'Thiết lập theo mặc định';
$lang_module['theme_created_activate_layout'] = 'Lỗi: Bạn cần thiết lập layout cho giao diện này trước khi kích hoạt';
$lang_module['theme_delete'] = 'Xóa các thiết lập';
$lang_module['theme_delete_confirm'] = 'Bạn có chắc chắn Xóa các thiết lập: ';
$lang_module['theme_delete_success'] = 'Xóa các thiết lập giao diện Thành công';
$lang_module['theme_delete_unsuccess'] = 'Có lỗi trong khi Xóa các thiết lập giao diện';
$lang_module['theme_created_current_use'] = 'Giao diện đang sử dụng';
$lang_module['block_front_delete_error'] = 'Lỗi: không thể xóa block, hãy kiểm tra lại quyền của bạn';
$lang_module['block_front_outgroup_success'] = 'Block đã được bỏ ra khỏi nhóm thành công và nằm trong nhóm ';
$lang_module['block_front_outgroup_cancel'] = 'Hiện tại chỉ có duy nhất 1 block nằm trong nhóm này do đó không cần bỏ ra khỏi nhóm';
$lang_module['block_front_outgroup_error_update'] = 'Có lỗi trong quá trình cập nhật dữ liệu';
$lang_module['xcopyblock'] = 'Sao chép block';
$lang_module['xcopyblock_to'] = ' sang theme ';
$lang_module['xcopyblock_from'] = ' từ theme ';
$lang_module['xcopyblock_position'] = 'Chọn vị trí';
$lang_module['xcopyblock_process'] = 'Sao chép';
$lang_module['xcopyblock_no_position'] = 'Hãy chọn ít nhất 1 vị trí để sao chép';
$lang_module['xcopyblock_notice'] = 'Khi thực hiện, hệ thống sẽ xóa các block đã tồn tại ở theme đích, vui lòng chờ cho các tiến trình thực hiện thành công.';
$lang_module['xcopyblock_success'] = 'Quá trình sao chép thành công !';
$lang_module['block_weight'] = 'Cập nhật lại vị trí các block';
$lang_module['block_weight_confirm'] = 'Bạn có chắc chắn Cập nhật lại vị trí các block, khi đó các cấu hình theo các function sẽ bị cập nhật lại';
$lang_module['autoinstall_theme_error_warning_overwrite'] = 'Thông báo: Gói giao diện bạn cài đặt đã tồn tại các file, bạn có chắc chắn thực hiện tiếp quá trình cài đặt để ghi đè các file này';
$lang_module['autoinstall_theme_overwrite'] = 'Thực hiện';

$lang_module['config'] = 'Thiết lập giao diện';
$lang_module['config_not_exit'] = 'Giao diện %s không có chức năng cấu hình';

$lang_module['show_device'] = 'Hiển thị trên thiết bị';
$lang_module['show_device_1'] = 'Tất cả';
$lang_module['show_device_2'] = 'Hiển thị di động';
$lang_module['show_device_3'] = 'Hiển thị máy tính bảng';
$lang_module['show_device_4'] = 'Các thiết bị khác';

$lang_module['preview_theme_on'] = 'Cho phép xem trước';
$lang_module['preview_theme_off'] = 'Hủy xem trước';
$lang_module['preview_theme_link'] = 'Liên kết xem trước giao diện';
$lang_module['preview_theme_link_copied'] = 'Liên kết đã được sao chép vào bộ nhớ tạm';

$lang_module['settings'] = 'Cấu hình';
$lang_module['settings_utheme'] = 'Thiết lập giao diện người dùng';
$lang_module['settings_utheme_help'] = 'Người sử dụng có thể chuyển đổi giữa các giao diện bên dưới';
$lang_module['settings_utheme_note'] = 'Bạn cần thiết lập giao diện trước khi giao diện có thể xuất hiện bên dưới';
$lang_module['settings_utheme_lnote'] = 'Cấu hình này áp dụng cho ngôn ngữ <strong>%s</strong>';
$lang_module['settings_utheme_choose'] = 'Chọn giao diện';
