<?php

/**
* @Project NUKEVIET 3.x
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2012 VINADES.,JSC. All rights reserved
* @Language Tiếng Việt
* @Createdate Mar 04, 2010, 03:22:00 PM
*/

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) )  die( 'Stop!!!' );

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2012 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_global';

$lang_global['mod_authors'] = 'Quản trị';
$lang_global['mod_groups'] = 'Nhóm thành viên';
$lang_global['mod_database'] = 'CSDL';
$lang_global['mod_settings'] = 'Cấu hình';
$lang_global['mod_cronjobs'] = 'Tiến trình tự động';
$lang_global['mod_modules'] = 'Quản lý Modules';
$lang_global['mod_themes'] = 'Quản lý giao diện';
$lang_global['mod_siteinfo'] = 'Thông tin';
$lang_global['mod_language'] = 'Ngôn ngữ';
$lang_global['mod_upload'] = 'Quản lý File';
$lang_global['mod_webtools'] = 'Công cụ web';
$lang_global['go_clientsector'] = 'Trang chủ site';
$lang_global['go_clientmod'] = 'Xem ngoài site';
$lang_global['please_select'] = 'Hãy lựa chọn';
$lang_global['admin_password_empty'] = 'Mật khẩu quản trị của bạn chưa được khai báo';
$lang_global['adminpassincorrect'] = 'Mật khẩu quản trị &ldquo;<strong>%s</strong>&rdquo; không chính xác. Hãy thử lại lần nữa';
$lang_global['admin_password'] = 'Mật khẩu của bạn';
$lang_global['admin_no_allow_func'] = 'Bạn không có quyền truy cập chức năng này';
$lang_global['who_view'] = 'Quyền xem';
$lang_global['who_view0'] = 'Tất cả';
$lang_global['who_view1'] = 'Thành viên';
$lang_global['who_view2'] = 'Quản trị';
$lang_global['who_view3'] = 'Nhóm Thành viên';
$lang_global['groups_view'] = 'Các nhóm được xem';
$lang_global['block_modules'] = 'Block của modules';
$lang_global['hello_admin1'] = 'Xin chào %1$s ! Lần đăng nhập Quản trị trước vào %2$s';
$lang_global['hello_admin2'] = 'Tài khoản Quản trị: %1$s ! Bạn đã đăng nhập Quản trị cách đây %2$s';
$lang_global['hello_admin3'] = 'Xin chào mừng %1$s. Đây là lần đăng nhập Quản trị đầu tiên của bạn';
$lang_global['ftp_error_account'] = 'Lỗi: hệ thống không kết nối được FTP server vui lòng kiểm tra lại các thông số FTP';
$lang_global['ftp_error_path'] = 'Lỗi: thông số Remote path không đúng';
$lang_global['login_error_account'] = 'Lỗi: Tài khoản Admin chưa được khai báo hoặc khai báo không hợp lệ! (Không ít hơn %1$s ký tự, không nhiều hơn %2$s ký tự. Chỉ chứa các ký tự có trong bảng chữ cái latin, số và dấu gạch dưới)';
$lang_global['login_error_password'] = 'Lỗi: Password của Admin chưa được khai báo hoặc khai báo không hợp lệ! (Không ít hơn %1$s ký tự, không nhiều hơn %2$s ký tự. Chỉ chứa các ký tự có trong bảng chữ cái latin, số và dấu gạch dưới)';
$lang_global['login_error_security'] = 'Lỗi: Mã kiểm tra chưa được khai báo hoặc khai báo không hợp lệ! (Phải có %1$s ký tự. Chỉ chứa các ký tự có trong bảng chữ cái latin và số)';
$lang_global['error_zlib_support'] = 'Lỗi: Máy chủ của bạn không hỗ trợ thư viện zlib, bạn cần liên hệ với nhà cung cấp dịch vụ hosting bật thư viện zlib để có thể sử dụng tính năng này.';
$lang_global['error_zip_extension'] = 'Lỗi: Máy chủ của bạn không hỗ trợ extension ZIP, bạn cần liên hệ với nhà cung cấp dịch vụ hosting bật extension ZIP để có thể sử dụng tính năng này.';
$lang_global['error_uploadNameEmpty'] = 'Lỗi: Tên file tải lên không xác định';
$lang_global['error_uploadSizeEmpty'] = 'Lỗi: Dung lượng file tải lên không xác định';
$lang_global['error_upload_ini_size'] = 'Lỗi: Dung lượng file tải lên lớn hơn mức cho phép được xác định trong php.ini';
$lang_global['error_upload_form_size'] = 'Lỗi: Dung lượng file tải lên lớn hơn mức cho phép được xác định qua biến MAX_FILE_SIZE trong mã HTML';
$lang_global['error_upload_partial'] = 'Lỗi: Chỉ một phần của file được tải lên';
$lang_global['error_upload_no_file'] = 'Lỗi: Chưa có file tải lên';
$lang_global['error_upload_no_tmp_dir'] = 'Lỗi: Thư mục tạm thời chứa file tải lên không được xác định';
$lang_global['error_upload_cant_write'] = 'Lỗi: Không thể ghi file tải lên';
$lang_global['error_upload_extension'] = 'Lỗi: File tải lên bị chặn vì thành phần mở rộng không hợp lệ';
$lang_global['error_upload_unknown'] = 'Đã xảy ra lỗi không xác định khi tải lên';
$lang_global['error_upload_type_not_allowed'] = 'Lỗi: loại file không được phép tải lên';
$lang_global['error_upload_mime_not_recognize'] = 'Lỗi: Hệ thống không thể xác định được định dạng của file tải lên';
$lang_global['error_upload_max_user_size'] = 'Lỗi: Dung lượng file tải lên lớn hơn mức cho phép. Dung lượng lớn nhất được tải lên là %d bytes';
$lang_global['error_upload_not_image'] = 'Lỗi: Hệ thống không thể xác định được định dạng hình tải lên';
$lang_global['error_upload_image_failed'] = 'Lỗi: Hình tải lên không hợp lệ';
$lang_global['error_upload_image_width'] = 'Lỗi: Hình tải lên có chiều rộng lớn hơn mức cho phép. Chiều rộng lớn nhất cho phép là %d pixels';
$lang_global['error_upload_image_height'] = 'Lỗi: Hình tải lên có chiều cao lớn hơn mức cho phép. Chiều cao lớn nhất cho phép là %d pixels';
$lang_global['error_upload_forbidden'] = 'Lỗi: Thư mục chứa file tải lên không được xác định';
$lang_global['error_upload_writable'] = 'Lỗi: Thư mục %s không cho phép chứa file tải lên. Có thể bạn cần CHMOD lại thư mục này ở dạng 0777';
$lang_global['error_upload_urlfile'] = 'Lỗi: URL mà bạn đưa ra không đúng';
$lang_global['error_upload_url_notfound'] = 'Lỗi: Không thể tải file từ URL mà bạn đưa ra';

?>