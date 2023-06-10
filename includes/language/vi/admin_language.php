<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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

$lang_module['order'] = 'Thứ tự';
$lang_module['nv_lang_data'] = 'Ngôn ngữ data';
$lang_module['site_lang'] = 'Ngôn ngữ mặc định';
$lang_module['nv_lang_interface'] = 'Ngôn ngữ giao diện';
$lang_module['nv_lang_setting'] = 'Cấu hình ngôn ngữ giao diện';
$lang_module['nv_admin_read'] = 'Nhập từ file vào CSDL';
$lang_module['nv_admin_read_all'] = 'Nhập từ files vào CSDL';
$lang_module['nv_admin_submit'] = 'Thực hiện';
$lang_module['nv_lang_module'] = 'Module';
$lang_module['nv_admin_write'] = 'Xuất ra file';
$lang_module['nv_admin_download'] = 'Download file';
$lang_module['nv_admin_delete'] = 'Xóa khỏi CSDL';
$lang_module['nv_admin_delete_files'] = 'Xóa files';
$lang_module['nv_admin_write_all'] = 'Xuất tất cả các dữ liệu ra file';
$lang_module['nv_admin_edit'] = 'Sửa ngôn ngữ';
$lang_module['nv_admin_edit_save'] = 'Lưu thay đổi';
$lang_module['nv_lang_nb'] = 'STT';
$lang_module['nv_lang_area'] = 'Khu vực';
$lang_module['nv_lang_site'] = 'Ngoài site';
$lang_module['nv_lang_admin'] = 'Quản lý site';
$lang_module['nv_lang_whole_site'] = 'Toàn site';
$lang_module['nv_lang_func'] = 'Chức năng';
$lang_module['nv_lang_key'] = 'Ký hiệu';
$lang_module['nv_lang_value'] = 'Giá trị';
$lang_module['nv_lang_note_edit'] = 'Lưu ý: chỉ sử dụng các thẻ html sau cho các trường giá trị';
$lang_module['nv_lang_author'] = 'Tác giả';
$lang_module['nv_lang_createdate'] = 'Ngày tạo';
$lang_module['nv_setting_read'] = 'Cấu hình đọc ngôn ngữ vào CSDL';
$lang_module['nv_setting_type_0'] = 'Lưu tất cả các giá trị';
$lang_module['nv_setting_type_1'] = 'Chỉ lưu các giá trị chưa có lang_key';
$lang_module['nv_setting_type_2'] = 'Chỉ cập nhật các giá trị đã có biến lang_key';
$lang_module['nv_setting_save'] = 'Cập nhật cấu hình thành công';
$lang_module['nv_lang_show'] = 'Quản lý hiển thị ngôn ngữ';
$lang_module['nv_lang_name'] = 'Tên ngôn ngữ';
$lang_module['nv_lang_slsite'] = 'Hiển thị ngoài site';
$lang_module['nv_lang_native_name'] = 'Ngôn ngữ bản địa';
$lang_module['nv_lang_sl'] = ' Có thể chọn';
$lang_module['nv_lang_error_exit'] = 'Để kiểm tra ngôn ngữ, cần có ít nhất 2 ngôn ngữ được lưu vào CSDL, trong đó phải có ít nhất một ngôn ngữ cơ sở là tiếng Việt hoặc tiếng Anh. <a href="%s">Nhấp vào đây</a> để thực hiện đọc ngôn ngữ vào CSDL.';
$lang_module['nv_lang_empty'] = 'Chưa có ngôn ngữ giao diện nào được đọc vào CSDL. <a href="%s">Nhấp vào đây</a> để thực hiện đọc ngôn ngữ vào CSDL.';
$lang_module['nv_data_note'] = 'Để download ngôn ngữ mới truy cập website <a title="Site dịch Ngôn ngữ cho NukeViet 4" href="http://translate.nukeviet.vn" target="_blank">Ngôn ngữ cho NukeViet 4</a>';
$lang_module['nv_data_note2'] = 'Để thêm mới ngôn ngữ dữ liệu, bạn cần <a title="Kích hoạt chức năng đa ngôn ngữ: Cấu hình -> Cấu hình chung " href="%s">kích hoạt chức năng đa ngôn ngữ</a>.';
$lang_module['nv_setup'] = 'Đã được cài đặt';
$lang_module['nv_setup_new'] = 'Cài đặt mới';
$lang_module['nv_setup_delete'] = 'Xóa ngôn ngữ data';
$lang_module['nv_data_setup'] = 'Ngôn ngữ data này đã được cài đặt';
$lang_module['nv_data_setup_ok'] = 'Cài đặt thành công! Bạn sẽ được chuyển đến trang thiết lập site bằng ngôn ngữ mới.';
$lang_module['nv_lang_readok'] = 'Đã thực hiện xong việc đọc ngôn ngữ giao diện. Bạn đang được chuyển đến trang danh sách các file. Nhấp vào đây nếu cảm thấy đợi lâu.';
$lang_module['nv_lang_deleteok'] = 'Thực hiện xong việc xóa ngôn ngữ khỏi CSDL.';
$lang_module['nv_lang_delete_files_ok'] = 'Các file sau đây đã được xóa';
$lang_module['nv_lang_wite_ok'] = 'Thực hiện xong việc tạo file ngôn ngữ giao diện';
$lang_module['nv_lang_delete'] = 'Xóa khỏi CSDL';
$lang_module['nv_lang_delete_error'] = 'Có lỗi trong quá trình xóa file ngôn ngữ giao diện. Bạn cần kiểm tra lại các file không được xóa.';
$lang_module['nv_error_write_file'] = 'Lỗi: không ghi được file';
$lang_module['nv_error_write_module'] = 'Lỗi: không xác định được file của module';
$lang_module['nv_error_exit_module'] = 'Lỗi: không tồn tại ngôn ngữ của module';
$lang_module['nv_lang_check'] = 'Kiểm tra ngôn ngữ';
$lang_module['nv_lang_data_source'] = 'Đối chiếu với ngôn ngữ';
$lang_module['nv_lang_checkallarea'] = 'Tất cả các khu vực';
$lang_module['nv_lang_check_no_data'] = 'Không có kết quả nào được tìm thấy theo yêu cầu của bạn';
$lang_module['nv_check_type'] = 'Điều kiện kiểm tra ';
$lang_module['nv_check_type_0'] = 'Kiểm tra ngôn ngữ chưa được dịch';
$lang_module['nv_check_type_1'] = 'Kiểm tra ngôn ngữ giống nhau';
$lang_module['nv_check_type_2'] = 'Kiểm tra tất cả';
$lang_module['nv_lang_check_title'] = 'Kiểm tra các ngôn ngữ chưa được dịch';
$lang_module['countries'] = 'Ngôn ngữ theo quốc gia';
$lang_module['countries_name'] = 'Tên quốc gia';
$lang_module['lang_installed'] = 'Ngôn ngữ đã cài đặt';
$lang_module['lang_can_install'] = 'Ngôn ngữ chưa cài đặt';
$lang_module['key_is_duplicate'] = 'Khóa này trùng lặp';
$lang_module['field_is_required'] = 'Trường này bắt buộc phải được khai báo';
$lang_module['language_to_check'] = 'Ngôn ngữ cần kiểm tra';
$lang_module['read_files'] = 'Các files đã được đọc vào CSDL';
