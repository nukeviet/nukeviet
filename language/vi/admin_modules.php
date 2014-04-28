<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Language Tiếng Việt
 * @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
 * @Createdate Mar 04, 2010, 03:22:00 PM
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2012 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['modules'] = 'Thiết lập module mới';
$lang_module['blocks'] = 'Cấu hình các block';
$lang_module['language'] = 'Cài đặt ngôn ngữ';
$lang_module['setup'] = 'Thiết lập';
$lang_module['main'] = 'Danh sách modules';
$lang_module['edit'] = 'Sửa module &ldquo;%s&rdquo;';
$lang_module['edit_error_update_theme'] = 'Quá trình cập nhật module phát hiện thấy có giao diện %s không đúng chuẩn hoặc bị lỗi, hãy kiểm tra lại.';
$lang_module['caption_actmod'] = 'Danh sách các module đang hoạt động';
$lang_module['caption_deactmod'] = 'Danh sách các module đang ngưng hoạt động';
$lang_module['caption_badmod'] = 'Danh sách các module ngưng hoạt động vì lỗi';
$lang_module['caption_newmod'] = 'Danh sách các module chưa cài đặt';
$lang_module['module_name'] = 'Module';
$lang_module['custom_title'] = 'Tên gọi ngoài site';
$lang_module['admin_title'] = 'Tên gọi khu vực quản trị';
$lang_module['weight'] = 'Thứ tự';
$lang_module['in_menu'] = 'Top Menu';
$lang_module['submenu'] = 'Sub Menu';
$lang_module['version'] = 'Phiên bản';
$lang_module['settime'] = 'Thời gian cài đặt ';
$lang_module['author'] = 'Tác giả';
$lang_module['theme'] = 'Giao diện';
$lang_module['mobile'] = 'Giao diện cho Mobile';
$lang_module['theme_default'] = 'Mặc định';
$lang_module['keywords'] = 'Từ khóa tìm kiếm';
$lang_module['keywords_info'] = 'Phân cách bởi dấu phẩy';
$lang_module['funcs_list'] = 'Danh sách các function thuộc module &ldquo;%s&rdquo;';
$lang_module['funcs_title'] = 'Function';
$lang_module['funcs_alias'] = 'Alias';
$lang_module['funcs_custom_title'] = 'Tên gọi';
$lang_module['funcs_layout'] = 'Sử dụng layout';
$lang_module['funcs_in_submenu'] = 'Menu';
$lang_module['funcs_subweight'] = 'Thứ tự';
$lang_module['activate_rss'] = 'Kích hoạt chức năng rss';
$lang_module['module_sys'] = 'Các module hệ thống';
$lang_module['vmodule'] = 'Các module ảo';
$lang_module['vmodule_add'] = 'Thêm module ảo';
$lang_module['vmodule_name'] = 'Tên module mới';
$lang_module['vmodule_file'] = 'Module gốc';
$lang_module['vmodule_note'] = 'Ghi chú';
$lang_module['vmodule_select'] = 'Chọn module';
$lang_module['vmodule_blockquote'] = 'Ghi chú: Tên module mới chỉ gồm các chữ cái, số và dấu gạch ngang.';
$lang_module['vmodule_exit'] = 'Lỗi: Module bạn đặt đã có trong hệ thống.';
$lang_module['autoinstall'] = 'Cài đặt và đóng gói tự động';
$lang_module['autoinstall_method'] = 'Lựa chọn tiến trình';
$lang_module['autoinstall_method_none'] = 'Hãy lựa chọn: ';
$lang_module['autoinstall_method_module'] = 'Cài đặt gói Module + Block';
$lang_module['autoinstall_method_block'] = 'Cài đặt Block';
$lang_module['autoinstall_method_packet'] = 'Đóng gói Module';
$lang_module['autoinstall_continue'] = 'Tiếp tục';
$lang_module['back'] = 'Quay lại';
$lang_module['autoinstall_error_nomethod'] = 'Hãy chọn 1 kiểu cài đặt để tiếp tục !';
$lang_module['autoinstall_module_install'] = 'Cài đặt module';
$lang_module['autoinstall_module_select_file'] = 'Hãy chọn gói để cài đặt: ';
$lang_module['autoinstall_module_error_filetype'] = 'Lỗi: File cài đặt phải là định dạng file zip hoặc gz';
$lang_module['autoinstall_module_error_nofile'] = 'Lỗi: Hãy chọn file để tiến hành cài đặt';
$lang_module['autoinstall_module_nomethod'] = 'Chưa xác định phương thức thực hiện';
$lang_module['autoinstall_module_uploadedfile'] = 'Hệ thống đã tải lên file: ';
$lang_module['autoinstall_module_uploadedfilesize'] = 'Dung lượng: ';
$lang_module['autoinstall_module_uploaded_filenum'] = 'Tổng số file + folder: ';
$lang_module['autoinstall_module_error_uploadfile'] = 'Lỗi: không thể upload file lên hệ thống. Hãy kiểm tra lại hoặc chmod thư mục tmp';
$lang_module['autoinstall_module_error_createfile'] = 'Lỗi: không thể lưu đệm danh sách file. Hãy kiểm tra lại hoặc chmod thư mục tmp';
$lang_module['autoinstall_module_error_invalidfile'] = 'Lỗi: File zip không hợp lệ';
$lang_module['autoinstall_module_error_warning_overwrite'] = 'Thông báo: Cấu trúc của module bạn cài đặt có các file và thư mục không đúng chuẩn, bạn có chắc chắn thực hiện tiếp quá trình cài đặt';
$lang_module['autoinstall_module_overwrite'] = 'Thực hiện';
$lang_module['autoinstall_module_error_warning_fileexist'] = 'Danh sách hiện có trên hệ thống:';
$lang_module['autoinstall_module_error_warning_invalidfolder'] = 'Cấu trúc thư mục không hợp lệ:';
$lang_module['autoinstall_module_error_warning_permission_folder'] = 'Host không thể tạo thư mục do safe mod on';
$lang_module['autoinstall_module_checkfile_notice'] = 'Để tiếp tục quá trình cài đặt, click vào KIỂM TRA hệ thống sẽ tự động kiểm tra tính tương thích';
$lang_module['autoinstall_module_checkfile'] = 'KIỂM TRA !';
$lang_module['autoinstall_module_installdone'] = 'TIẾN HÀNH CÀI ĐẶT...';
$lang_module['autoinstall_module_cantunzip'] = 'Lỗi không thể giải nén. Hãy kiểm tra lại chmod các thư mục.';
$lang_module['autoinstall_module_unzip_success'] = 'Quá trình cài đặt thành công. Hệ thống sẽ tự động chuyển bạn sang trang kích hoạt cài đặt ngay bây giờ.';
$lang_module['autoinstall_module_unzip_setuppage'] = 'Đến trang quản lý module.';
$lang_module['autoinstall_module_unzip_filelist'] = 'Danh sách file đã giải nén';
$lang_module['autoinstall_module_error_movefile'] = 'Việc cài đặt tự động không thể tiếp tục do host không hỗ trợ di chuyển các file sau khi giải nén.';
$lang_module['autoinstall_package_select'] = 'Chọn module để đóng gói';
$lang_module['autoinstall_package_noselect'] = 'Hãy chọn 1 module để đóng gói';
$lang_module['autoinstall_package_processing'] = ' xin chờ quá trình thực hiện hoàn thành...';
$lang_module['delete_module_info1'] = 'Module này hiện đang tồn tại trên ngôn ngữ <strong>%s</strong>, hãy xóa module trên các ngôn ngữ đó để có thể xóa module gốc';
$lang_module['delete_module_info2'] = 'Có %d module ảo được tạo ra từ module này, hãy xóa chúng trước khi xóa module gốc';
$lang_module['change_func_name'] = 'Đổi tên function "%s" của module "%s"';
$lang_module['change_fun_alias'] = 'Đổi tên alias "%s" của module "%s"';
$lang_module['description'] = 'Mô tả';