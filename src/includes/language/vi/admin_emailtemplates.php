<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2012 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['copy'] = 'Sao chép';
$lang_module['errorsave'] = 'Lỗi không xác định dẫn đến không thể lưu dữ liệu';
$lang_module['add_template'] = 'Thêm mẫu email';
$lang_module['edit_template'] = 'Sửa mẫu email';
$lang_module['categories'] = 'Danh mục mẫu email';
$lang_module['categories_other'] = 'Chưa được xếp loại';
$lang_module['categories_list'] = 'Danh sách các danh mục';
$lang_module['categories_add'] = 'Thêm danh mục';
$lang_module['categories_edit'] = 'Sửa danh mục';
$lang_module['categories_title'] = 'Tên danh mục';
$lang_module['categories_error_title'] = 'Chưa nhập tên danh mục';
$lang_module['categories_error_exists'] = 'Tên danh mục này đã được sử dụng, hãy chọn một tên khác';
$lang_module['categories_show'] = 'Hiển thị email trong danh sách';
$lang_module['from'] = 'Từ';
$lang_module['to'] = 'Đến';
$lang_module['keywords'] = 'Từ khóa';
$lang_module['all'] = 'Tất cả';
$lang_module['add_edit'] = 'Thêm/sửa';
$lang_module['default'] = 'Mặc định';
$lang_module['order'] = 'Thứ tự';
$lang_module['adv_info'] = 'Thông tin nâng cao';

$lang_module['tpl_send_name'] = 'Tên &amp; Email gửi';
$lang_module['tpl_send_cc'] = 'CC';
$lang_module['tpl_send_bcc'] = 'BCC';
$lang_module['tpl_is_plaintext'] = 'Gửi dạng text thuần';
$lang_module['tpl_is_plaintext_help'] = 'Xóa bỏ định dạng trong nội dung email gửi đi';
$lang_module['tpl_is_disabled'] = 'Hủy gửi mail';
$lang_module['tpl_is_disabled_help'] = 'Chọn tùy chọn này hệ thống sẽ đình chỉ gửi email từ mẫu này';
$lang_module['tpl_is_selftemplate'] = 'Template riêng';
$lang_module['tpl_is_selftemplate_help'] = 'Chọn tùy chọn này nếu không muốn áp dụng mẫu email chung khi gửi mail đi';
$lang_module['list_email_help'] = 'Có thể nhập nhiều email, cách nhau bởi dấu phảy';
$lang_module['tpl_send_name_help'] = 'Nếu không nhập ở đây, hệ thống sẽ lấy từ tên website và email liên hệ của site';
$lang_module['tpl_basic_info'] = 'Thông tin cơ bản';
$lang_module['tpl_attachments'] = 'Tập tin đính kèm';
$lang_module['tpl_error_default_subject'] = 'Tiêu đề email còn trống';
$lang_module['tpl_error_default_content'] = 'Nội dung email còn trống';
$lang_module['tpl_error_title'] = 'Tên mẫu email %s còn trống';
$lang_module['tpl_error_exists'] = 'Tên mẫu email nãy ở %s đã được sử dụng, hãy chọn tên khác để tránh nhầm lẫn';
$lang_module['tpl_error_smarty_subject'] = 'Vì lý do bảo mật, bạn không được phép sử dụng biến $smarty trong tiêu đề email mặc định';
$lang_module['tpl_error_smarty_subject1'] = 'Vì lý do bảo mật, bạn không được phép sử dụng biến $smarty trong tiêu đề email %s';
$lang_module['tpl_error_smarty_content'] = 'Vì lý do bảo mật, bạn không được phép sử dụng biến $smarty trong nội dung email mặc định';
$lang_module['tpl_error_smarty_content1'] = 'Vì lý do bảo mật, bạn không được phép sử dụng biến $smarty trong nội dung email %s';
$lang_module['tpl_title'] = 'Tên';
$lang_module['tpl_subject'] = 'Tiêu đề';
$lang_module['tpl_content'] = 'Nội dung';
$lang_module['tpl_incat'] = 'Danh mục';
$lang_module['function'] = 'Chức năng';
$lang_module['rollback_message'] = 'Nếu trong quá trình chỉnh sửa, bạn đã thay đổi tiêu đề, nội dung email mà không còn nhớ nguyên mẫu ban đầu. Hiện tại email này không gửi được bình thường. Bạn hãy nhấp nút bên dưới để khôi phục lại email gốc. Tên gọi, tiêu đề, nội dung của email trên toàn bộ các ngôn ngữ hiện có sẽ trở về như ban đầu';
$lang_module['update_for'] = 'Thực hiện hành động này cho';
$lang_module['update_for1'] = 'Mẫu này trên toàn bộ các ngôn ngữ';
$lang_module['update_for2'] = 'Mẫu này trên %s';
$lang_module['update_for3'] = 'Mẫu này trên các module cùng tên %s';
$lang_module['update_for4'] = 'Chỉ mẫu này';
$lang_module['default_content'] = 'Nội dung email mặc định';
$lang_module['default_content_info'] = 'Áp dụng cho tất cả các ngôn ngữ nếu ngôn ngữ đó chưa được định nghĩa bên dưới';
$lang_module['lang_content'] = 'Nội dung email theo ngôn ngữ';
$lang_module['tpl_list'] = 'Danh sách các mẫu email';
$lang_module['tpl_is_active'] = 'Đang nhận email';
$lang_module['tpl_is_disabled'] = 'Ngừng gửi email';
$lang_module['tpl_is_disabled_label'] = 'Dừng';
$lang_module['tpl_custom_label'] = 'Tùy biến';
$lang_module['tpl_plugin'] = 'Trình xử lý dữ liệu';
$lang_module['tpl_plugin_help'] = 'Chọn plugin xử lý các merge fields trong nội dung email';
$lang_module['tpl_pluginsys'] = 'Trình xử lý dữ liệu hệ thống';
$lang_module['tpl_mailtpl'] = 'Mẫu cố định';
$lang_module['tpl_pluginsys_help'] = 'Các trình xử lý này cố định với mẫu email của hệ thống và không thể thay đổi. Nếu bạn muốn bổ sung, hãy chọn thêm bên dưới';
$lang_module['merge_field'] = 'Các trường hỗ trợ';
$lang_module['merge_field_help'] = 'Các trường sẽ tự động được thay bằng giá trị tương ứng khi xuất ra nội dung email. Nhấp vào mô tả của các biến để điền vào trình soạn thảo';
$lang_module['merge_field_guild1'] = 'Cách hiển thị có điều kiện';
$lang_module['merge_field_guild2'] = 'Hiển thị nội dung dựa vào điều kiện của một biến. Ví dụ:';
$lang_module['merge_field_guild3'] = 'Chi tiết hơn, mời xem tại <a href="https://www.smarty.net/docs/en/language.function.if.tpl" target="_blank">đây</a>';
$lang_module['merge_field_guild4'] = 'Xuất ra dạng vòng lặp';
$lang_module['merge_field_guild5'] = 'Duyệt mảng để xuất ra các phần tử trong mảng đó. Ví dụ:';
$lang_module['merge_field_guild6'] = 'Chi tiết hơn, mời xem tại <a href="https://www.smarty.net/docs/en/language.function.foreach.tpl" target="_blank">đây</a>';

$lang_module['test'] = 'Gửi thử nghiệm email';
$lang_module['test_tomail'] = 'Email nhận';
$lang_module['test_error_tomail'] = 'Bạn chưa nhập email nhận';
$lang_module['test_error_template'] = 'Mẫu email này không tồn tại';
$lang_module['test_tomail_note'] = 'Nhập một email mỗi dòng, thường tối đa 50 email';
$lang_module['test_value_fields'] = 'Dữ liệu mẫu tùy biến';
$lang_module['test_success'] = 'Đã gửi thử nghiệm email thành công, vui lòng kiểm tra hộp thư đến (cả hộp thư rác nếu không có ở hộp thư đến) để xem email nhận được';
$lang_module['test_note1'] = 'Tính năng gửi thử nghiệm email hỗ trợ các biến đơn có dữ liệu dạng chuỗi hoặc số ví dụ như <code>$site_name</code>, <code>$username</code> và dữ liệu dạng mảng một chiều của chuỗi hoặc số ví dụ như <code>$user.full_name</code>';
$lang_module['test_note2'] = 'Nếu biến của bạn có dạng mảng hãy viết dạng <code>$user.full_name</code> thay vì sử dụng dạng <code>$user[\'full_name\']</code>';
$lang_module['test_note3'] = 'Khi bạn soạn thảo một mẫu email có các điều kiện, các biến phức tạp nằm ngoài phạm vi hỗ trợ bên trên, hãy thử nghiệm gửi email bằng hàm <code>nv_sendmail_from_template</code> hoặc hàm <code>nv_sendmail_template_async</code> qua hình thức lập trình';
