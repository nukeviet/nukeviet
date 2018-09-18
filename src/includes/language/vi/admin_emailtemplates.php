<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Language Tiếng Việt
 * @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
 * @Createdate Mar 04, 2010, 03:22:00 PM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2012 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

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
$lang_module['tpl_send_name'] = 'Tên &amp; Email gửi';
$lang_module['tpl_send_cc'] = 'CC';
$lang_module['tpl_send_bcc'] = 'BCC';
$lang_module['tpl_is_plaintext'] = 'Gửi dạng text thuần';
$lang_module['tpl_is_plaintext_help'] = 'Xóa bỏ định dạng trong nội dung email gửi đi';
$lang_module['tpl_is_disabled'] = 'Hủy gửi mail';
$lang_module['tpl_is_disabled_help'] = 'Chọn tùy chọn này hệ thống sẽ đình chỉ gửi email từ mẫu này';
$lang_module['list_email_help'] = 'Có thể nhập nhiều email, cách nhau bởi dấu phảy';
$lang_module['tpl_send_name_help'] = 'Nếu không nhập ở đây, hệ thống sẽ lấy từ tên website và email liên hệ của site';
$lang_module['tpl_basic_info'] = 'Thông tin cơ bản';
$lang_module['tpl_attachments'] = 'Tập tin đính kèm';
$lang_module['tpl_error_default_subject'] = 'Lỗi: Tiêu đề email còn trống';
$lang_module['tpl_error_default_content'] = 'Lỗi: Nội dung email còn trống';
$lang_module['tpl_error_title'] = 'Lỗi: Tên mẫu email còn trống';
$lang_module['tpl_error_exists'] = 'Lỗi: Tên mẫu email nãy đã được sử dụng, hãy chọn tên khác để tránh nhầm lẫn';
$lang_module['tpl_title'] = 'Tên mẫu email';
$lang_module['tpl_subject'] = 'Tiêu đề email';
$lang_module['tpl_incat'] = 'Danh mục';
$lang_module['default_content'] = 'Nội dung email mặc định';
$lang_module['default_content_info'] = 'Áp dụng cho tất cả các ngôn ngữ nếu ngôn ngữ đó chưa được định nghĩa bên dưới';
$lang_module['lang_content'] = 'Nội dung email theo ngôn ngữ';
$lang_module['lang_content_info'] = 'Áp dụng riêng cho <strong>%s</strong>';
$lang_module['tpl_list'] = 'Danh sách các mẫu email';
$lang_module['tpl_is_active'] = 'Đang nhận email';
$lang_module['tpl_is_disabled'] = 'Ngừng gửi email';
$lang_module['tpl_is_disabled_label'] = 'Dừng';
$lang_module['tpl_custom_label'] = 'Tùy biến';
$lang_module['merge_field'] = 'Các trường hỗ trợ';
$lang_module['merge_field_help'] = 'Các trường này tùy thuộc vào danh mục email, chúng sẽ tự động được thay bằng giá trị tương ứng khi xuất ra nội dung email';
$lang_module['merge_field_guild1'] = 'Cách hiển thị có điều kiện';
$lang_module['merge_field_guild2'] = 'Hiển thị nội dung dựa vào điều kiện của một biến. Ví dụ:';
$lang_module['merge_field_guild3'] = 'Chi tiết hơn, mời xem tại <a href="https://www.smarty.net/docs/en/language.function.if.tpl" target="_blank">đây</a>';
$lang_module['merge_field_guild4'] = 'Xuất ra dạng vòng lặp';
$lang_module['merge_field_guild5'] = 'Duyệt mảng để xuất ra các phần tử trong mảng đó. Ví dụ:';
$lang_module['merge_field_guild6'] = 'Chi tiết hơn, mời xem tại <a href="https://www.smarty.net/docs/en/language.function.foreach.tpl" target="_blank">đây</a>';
