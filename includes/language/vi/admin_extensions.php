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
$lang_translator['copyright'] = '@Copyright (C) 2012 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['newest'] = 'Ứng dụng mới nhất';
$lang_module['popular'] = 'Ứng dụng phổ biến nhất';
$lang_module['featured'] = 'Ứng dụng khuyên dùng';
$lang_module['downloaded'] = 'Ứng dụng đã tải';
$lang_module['favorites'] = 'Ứng dụng đã đánh dấu';

$lang_module['empty_response'] = 'Không có bất kỳ dữ liệu nào phù hợp với yêu cầu này.';

$lang_module['extname'] = 'Tên ứng dụng';
$lang_module['author'] = 'Tác giả';
$lang_module['detail'] = 'Chi tiết';
$lang_module['install'] = 'Cài đặt';
$lang_module['price'] = 'Giá';
$lang_module['free'] = 'Miễn phí';
$lang_module['newest_version'] = 'Phiên bản mới nhất';
$lang_module['ext_type'] = 'Loại';
$lang_module['compatible'] = 'Tương thích';
$lang_module['incompatible'] = 'Không tương thích';
$lang_module['tab_info'] = 'Thông tin';
$lang_module['tab_guide'] = 'Hướng dẫn';
$lang_module['tab_files'] = 'Tải về khả dụng';
$lang_module['tab_images'] = 'Hình ảnh';
$lang_module['updatetime'] = 'Cập nhật';
$lang_module['view_hits'] = 'Lượt xem';
$lang_module['download_hits'] = 'Tải về';
$lang_module['rating_text'] = 'Đánh giá';
$lang_module['rating_text_detail'] = '%s điểm/%s đánh giá';
$lang_module['license'] = 'Giấy phép';
$lang_module['download'] = 'Tải xuống';
$lang_module['download_note'] = 'Sử dụng đường dẫn này để tải xuống máy tính cá nhân';
$lang_module['install_note'] = 'Sử dụng đường dẫn này để cài đặt trực tiếp';

$lang_module['file_name'] = 'Tên file';
$lang_module['file_version'] = 'Phiên bản';

$lang_module['types_0'] = 'Không rõ';
$lang_module['types_1'] = 'Modules';
$lang_module['types_2'] = 'Themes';
$lang_module['types_3'] = 'Blocks';
$lang_module['types_4'] = 'Cronjobs';
$lang_module['types_5'] = 'Khác';

$lang_module['file_type_0'] = 'Không rõ';
$lang_module['file_type_1'] = 'File cài đặt';
$lang_module['file_type_2'] = 'File hướng dẫn';
$lang_module['file_type_3'] = 'Loại file khác';

$lang_module['search_key'] = 'Nhập từ khóa';
$lang_module['search_go'] = 'Tìm';

$lang_module['detail_title'] = 'Chi tiết ứng dụng &quot;%s&quot;';
$lang_module['detail_empty_documentation'] = 'Ứng dụng này không có hướng dẫn';
$lang_module['detail_empty_images'] = 'Ứng dụng này không có ảnh minh họa';

$lang_module['install_title'] = 'Cài đặt ứng dụng &quot;%s&quot;';
$lang_module['install_getfile'] = 'Tìm kiếm phiên bản thích hợp';
$lang_module['install_getfile_error'] = 'Không có phiên bản nào thích hợp';
$lang_module['install_check_compatible'] = 'Kiểm tra tính tương thích';
$lang_module['install_check_compatible_error'] = 'Phiên bản không tương thích';
$lang_module['install_check_auto_install'] = 'Kiểm tra khả năng cài đặt tự động';
$lang_module['install_manual_install'] = 'Ứng dụng này không hỗ trợ cài đặt tự động, bạn có thể click vào nút tải về bện dưới và làm theo hướng dẫn để cài đặt ứng dụng thủ công';
$lang_module['install_manual_install_danger'] = 'Ứng dụng này không hỗ trợ cài đặt tự động, và cũng không có hướng dẫn cài đặt, bạn có thể click vào nút bên dưới để tải về cà cài đặt thủ công';
$lang_module['install_documentation'] = 'Hướng dẫn cài đặt';
$lang_module['install_check_installed'] = 'Kiểm tra ứng dụng chưa được cài đặt';
$lang_module['install_check_installed_error'] = 'Ứng dụng này đã tồn tại trên hệ thống. Để cài đặt mới, trước tiên cần xóa ứng dụng này khỏi hệ thống, nhấp <a href="%s">Vào đây</a> để kiểm tra và xóa ứng dụng.';
$lang_module['install_check_installed_unsure'] = 'Hệ thống không thể kiểm tra kiểu ứng dụng này một cách tối ưu, có thể việc cài mới sẽ ghi đẽ nội dung cũ, bạn nên lưu ý trước khi cài đặt';
$lang_module['install_continue'] = 'Tiếp tục cài đặt';
$lang_module['install_cancel'] = 'Hủy cài đặt';
$lang_module['install_file_download'] = 'Tải về file cài đặt';
$lang_module['install_check_require'] = 'Kiểm tra ứng dụng bắt buộc';
$lang_module['install_check_require_fail'] = 'Để ứng dụng này có thể hoạt động, bạn cần phải cài đặt ứng dụng <strong>&quot;%s&quot;</strong> trước. Nhấp vào đây để xem thông tin về ứng dụng bắt buộc này';
$lang_module['install_check_paid'] = 'Kiểm tra thanh toán';
$lang_module['install_check_paid_await'] = 'Ứng dụng này đang trong quá trình thanh toán và đang chờ được xác nhận, việc cài đặt bị chặn cho đến khi hoàn tất thanh toán';
$lang_module['install_check_paid_nologin'] = 'Hệ thống yêu cầu bạn đăng nhập Merchant Site để kiểm tra việc thanh toán ứng dụng có phí. Nhấp vào đây để đăng nhập';
$lang_module['install_check_paid_unpaid'] = 'Ứng dụng này có phí, bạn cần mua ứng dụng trước khi có thể cài đặt. Nhấp vào đây để mua.';

$lang_module['download_error_preparam'] = 'Lỗi: Thiếu thông tin cần thiết';
$lang_module['download_error_save'] = 'Lỗi: Lưu file thất bại';
$lang_module['download_ok'] = 'Tải file thành công, hệ thống sẽ chuyển đến trang cài đặt trong giây lát';

$lang_module['login_pagetitle'] = 'Đăng nhập Merchant Site';
$lang_module['login_require'] = 'Chức năng này yêu cầu bạn phải đăng nhập. Nhấp <a href="%s">Vào đây</a> để đăng nhập';
$lang_module['login_creat_merchant'] = 'Nếu chưa có tài khoản, click <a href="http://nukeviet.vn/store/merchant/manager/">Vào đây</a> để tạo';
$lang_module['login_success'] = 'Đăng nhập thành công, hệ thống sẽ chuyển trang trong giây lát';

$lang_module['extUpd'] = 'Nâng cấp ứng dụng';
$lang_module['extUpdCheck'] = 'Kiểm tra gói nâng cấp';
$lang_module['extUpdCheckSuccess'] = 'Có thể nâng cấp';
$lang_module['extUpdCheckSuccessNote'] = 'Đang tải về gói nâng cấp, vui lòng đợi';
$lang_module['extUpdCheckStatus'] = 'Trạng thái';
$lang_module['extUpdNotLogin'] = 'Chưa đăng nhập';
$lang_module['extUpdLoginRequire'] = 'Để nâng cấp ứng dụng này, trước hết hệ thống yêu cần bạn đăng nhập Merchant Site. Nhấp <strong><a href="%s">Vào đây</a></strong> để đăng nhập';
$lang_module['extUpdPaidRequire'] = 'Gói nâng cấp ứng dụng này có phí, bạn cần mua gói nâng cấp trước khi có thể nâng cấp. Nhấp <strong><a href="%s">Vào đây</a></strong> để mua';
$lang_module['extUpdUnpaid'] = 'Chưa thanh toán';
$lang_module['extUpdInvalid'] = 'Không hợp lệ';
$lang_module['extUpdInvalidNote'] = 'Dữ liệu không hợp lệ, vui lòng kiểm tra lại';
$lang_module['extUpdErrorDownload'] = 'Lỗi tải gói nâng cấp';

$lang_module['get_update_error_file_download'] = 'Tải gói cập nhật thất bại';
$lang_module['get_update_ok'] = 'Gói nâng cấp an toàn, nhấp <a href="%s">Vào đây</a> để tiến hành giải nén gói nâng cấp';
$lang_module['get_update_warning'] = 'Gói nâng cấp này có thể gây nguy hại đến website của bạn nếu có bất kỳ lỗi nào xảy ra, do đó bạn nên sao lưu toàn bộ website trước khi thực hiện, nhấp <a href="%s">Vào đây</a> để tiến hành giải nén gói nâng cấp';
$lang_module['get_update_warning_permission_folder'] = 'Lỗi không thể tạo thư mục.';
$lang_module['get_update_error_movefile'] = 'Lỗi không thể di chuyển file';
$lang_module['get_update_cantunzip'] = 'Lỗi không thể giải nén. Hãy kiểm tra lại chmod các thư mục.';
$lang_module['get_update_okunzip'] = 'Giải nén thành công, hệ thống sẽ chuyển trang trong giây lát';
$lang_module['get_update_okunzip_link'] = 'Đến trang nâng cấp';

$lang_module['manage'] = 'Quản lý ứng dụng';
$lang_module['extType_module'] = 'Module';
$lang_module['extType_block'] = 'Block';
$lang_module['extType_theme'] = 'Giao diện';
$lang_module['extType_cronjob'] = 'Tiến trình tự động';
$lang_module['extType_other'] = 'Khác';
$lang_module['extType_sys'] = 'Hệ thống';
$lang_module['extType_admin'] = 'Phần quản trị';

$lang_module['install_package'] = 'Cài đặt gói ứng dụng';
$lang_module['install_submit'] = 'Tải lên';
$lang_module['install_error_filetype'] = 'Lỗi: File cài đặt phải là định dạng file zip hoặc gz';
$lang_module['install_error_nofile'] = 'Lỗi: Chưa chọn file tải lên';

$lang_module['autoinstall_install'] = 'Cài đặt ứng dụng';
$lang_module['autoinstall_nomethod'] = 'Chưa xác định phương thức thực hiện';
$lang_module['autoinstall_uploadedfile'] = 'Thông tin ứng dụng được tải lên';
$lang_module['autoinstall_uploadedfilename'] = 'Tên file';
$lang_module['autoinstall_uploadedfilesize'] = 'Dung lượng';
$lang_module['autoinstall_uploaded_filenum'] = 'Tổng số file + folder';
$lang_module['autoinstall_uploaded_filelist'] = 'Danh sách các file';
$lang_module['autoinstall_uploaded_num_exists'] = 'Số file trùng';
$lang_module['autoinstall_uploaded_num_invalid'] = 'Số file cấu trúc không phù hợp';
$lang_module['autoinstall_error_uploadfile'] = 'Lỗi: không thể upload file lên hệ thống. Hãy kiểm tra lại hoặc chmod thư mục tmp';
$lang_module['autoinstall_error_uploadfile1'] = 'Lỗi: không thể upload file lên hệ thống. Hãy kiểm tra lại có thể dung lượng file vượt quá %s';
$lang_module['autoinstall_error_downloaded'] = 'Lỗi: Không tìm thấy file đã upload';
$lang_module['autoinstall_error_createfile'] = 'Lỗi: không thể lưu đệm danh sách file. Hãy kiểm tra lại hoặc chmod thư mục tmp';
$lang_module['autoinstall_error_invalidfile'] = 'Lỗi: File zip không hợp lệ';
$lang_module['autoinstall_error_check_fail'] = 'Lỗi: Ứng dụng này có cấu trúc không phù hợp và có thể gây nguy hại đến website. Để đảm bảo an toàn, hệ thống từ chối việc tiếp tục cài đặt ứng dụng này';
$lang_module['autoinstall_error_check_warning'] = 'Ứng dụng này có một số tệp tin hiện đã có trên hệ thống. Tiếp tục cài đặt, những tệp tin đó sẽ bị thay thế. Nếu bạn chắc chắn điều này an toàn, nhấp <strong><a href="#">Vào đây</a></strong> để tiến hành giải nén và cài đặt.';
$lang_module['autoinstall_error_check_success'] = 'Ứng dụng này an toàn với hệ thống của bạn. Nhấp <strong><a href="#">Vào đây</a></strong> để tiến hành giải nén và cài đặt.';
$lang_module['autoinstall_error_warning_permission_folder'] = 'Host không thể tạo thư mục do safe mod on';
$lang_module['autoinstall_cantunzip'] = 'Lỗi không thể giải nén. Hãy kiểm tra lại chmod các thư mục.';
$lang_module['autoinstall_unzip_success'] = 'Quá trình cài đặt thành công. Hệ thống sẽ tự động chuyển bạn sang khu vực thích hợp ngay bây giờ.';
$lang_module['autoinstall_unzip_setuppage'] = 'Đến trang quản lý ứng dụng.';
$lang_module['autoinstall_unzip_filelist'] = 'Danh sách file đã giải nén';
$lang_module['autoinstall_error_movefile'] = 'Việc cài đặt tự động không thể tiếp tục do host không hỗ trợ di chuyển các file sau khi giải nén.';
$lang_module['autoinstall_error_missing_cfg'] = 'File tải lên không hợp lệ vui lòng kiểm tra lại cách thức đóng gói ứng dụng';
$lang_module['autoinstall_error_cfg_content'] = 'Gói ứng dụng không đầy đủ thông tin, vui lòng kiểmt tra lại';
$lang_module['autoinstall_error_cfg_type'] = 'Loại ứng dụng không xác định';
$lang_module['autoinstall_error_cfg_version'] = 'Phiên bản ứng dụng không hợp lệ';
$lang_module['autoinstall_error_cfg_name'] = 'Tên ứng dụng theo loại ứng dụng không đúng chuẩn quy định';
$lang_module['autoinstall_error_mimetype'] = 'Cảnh báo: Hệ thống không kiểm tra được loại file của các tệp tin sau. Nếu chắc chắn các tệp tin đó không chứa mã độc, hãy nhấp nút &quot;Bỏ qua cảnh báo&quot;';
$lang_module['autoinstall_error_mimetype_pass'] = 'Bỏ qua cảnh báo';
$lang_module['autoinstall_note_invaild'] = 'Vị trí không được phép';
$lang_module['autoinstall_note_exists'] = 'Tệp tin đã tồn tại';

$lang_module['package'] = 'Đóng gói ứng dụng';

$lang_module['delele_ext_confirm'] = 'Bạn có chắc chắn xóa ứng dụng này, việc này sẽ không thể hoàn tác?';
$lang_module['delele_ext_theme_note_module'] = 'Bạn không thể xóa theme vì đang sử dụng cho module: %s, bạn cần cấu hình lại các module đó.';
$lang_module['delele_ext_success'] = 'Đã xóa thành công ứng dụng ra khỏi hệ thống!';
$lang_module['delele_ext_unsuccess'] = 'Có lỗi trong quá trình xóa!';
$lang_module['delele_ext_theme_delete_current_theme'] = 'Bạn không thể xóa theme hiện tại hệ thống đang sử dụng!';
