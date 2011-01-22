<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Language Vietnamese
 * @Createdate May 31, 2010, 08:01:47 PM
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) )
{
    die( 'Stop!!!' );
}

$lang_translator['author'] = "VINADES.,JSC (contact@vinades.vn)";
$lang_translator['createdate'] = "04/03/2010, 15:22";
$lang_translator['copyright'] = "@Copyright (C) 2010 VINADES.,JSC. All rights reserved";
$lang_translator['info'] = "";
$lang_translator['langtype'] = "lang_module";

$lang_module['metaTagsConfig'] = "Cấu hình Meta-Tags";
$lang_module['metaTagsGroupName'] = "Kiểu Nhóm";
$lang_module['metaTagsGroupValue'] = "Tên Nhóm";
$lang_module['metaTagsNote'] = "Các Meta-Tags: \"%s\" được xác định tự động";
$lang_module['metaTagsVar'] = "Chấp nhận các biến";
$lang_module['metaTagsContent'] = "Nội dung";
$lang_module['googleAnalyticsSetDomainName_title'] = "Đặc tính Domain khi khai báo với Google Analytics";
$lang_module['googleAnalyticsSetDomainName_0'] = "Domain duy nhất";
$lang_module['googleAnalyticsSetDomainName_1'] = "Domain cấp cao + subdomains chạy song song";
$lang_module['googleAnalyticsSetDomainName_2'] = "Nhiều Domain cấp cao chạy song song";
$lang_module['googleAnalyticsID'] = "ID tài khoản Google Analytics<br />(Có dạng UA-XXXXX-X, <a href=\"http://www.google.com/analytics/\" target=\"_blank\">xem chi tiết</a>)";
$lang_module['global_config'] = "Cấu hình chung";
$lang_module['lang_site_config'] = "Theo ngôn ngữ";
$lang_module['bots_config'] = "Máy chủ tìm kiếm";

$lang_module['optActive'] = "Kích hoạt chức năng tối ưu site<br />(Chỉ tắt với admin)";
$lang_module['sitename'] = "Tên gọi của site";
$lang_module['theme'] = "Giao diện mặc định";
$lang_module['themeadmin'] = "Giao diện người quản trị";
$lang_module['default_module'] = "Module mặc định trên trang chủ";
$lang_module['description'] = "Mô tả của site";
$lang_module['rewrite'] = "Bật chức năng rewrite";
$lang_module['rewrite_optional'] = "Loại bỏ kí tự ngôn ngữ trên url";
$lang_module['site_disable'] = "Site ngưng hoạt động";
$lang_module['disable_content'] = "Nội dung thông báo site ngưng hoạt động";
$lang_module['footer_content'] = "Nội dung cuối site";

$lang_module['submit'] = "Lưu";
$lang_module['err_writable'] = "Lỗi hệ thống không ghi được file: %s bạn cần cấu hình server cho phép ghi file này.";
$lang_module['err_supports_rewrite'] = "Lỗi, Máy chủ của bạn không hỗ trợ module rewrite";
$lang_module['captcha'] = "Cấu hình hiển thị captcha";
$lang_module['captcha_0'] = "Không hiển thị";
$lang_module['captcha_1'] = "Khi admin đăng nhập";
$lang_module['captcha_2'] = "Khi thành viên đăng nhập";
$lang_module['captcha_3'] = "Khi khách đăng ký";
$lang_module['captcha_4'] = "Khi thành viên đăng nhập hoặc khách đăng ký";
$lang_module['captcha_5'] = "Khi admin hoặc thành viên đăng nhập";
$lang_module['captcha_6'] = "Khi admin đăng nhập hoặc khách đăng ký";
$lang_module['captcha_7'] = "Hiển thị trong mọi trường hợp";

$lang_module['ftp_config'] = "Cấu hình FTP";
$lang_module['smtp_config'] = "Cấu hình SMTP";
$lang_module['server'] = "Server or Url";
$lang_module['port'] = "Port";
$lang_module['username'] = "User name";
$lang_module['password'] = "Password";
$lang_module['ftp_path'] = "Remote path";
$lang_module['mail_config'] = "Lựa chọn cấu hình";
$lang_module['type_smtp'] = "SMTP";
$lang_module['type_linux'] = "Linux Mail";
$lang_module['type_phpmail'] = "PHPmail";

$lang_module['smtp_server'] = "Cấu hình máy chủ gửi mail";
$lang_module['incoming_ssl'] = "Sử dụng Xác thực";
$lang_module['outgoing'] = "Máy chủ (SMTP) Thư Gửi đi";
$lang_module['outgoing_port'] = "Cổng gửi mail";
$lang_module['smtp_username'] = "Tài khoản gửi mail";
$lang_module['smtp_login'] = "Tên Tài khoản";
$lang_module['smtp_pass'] = "Mật khẩu";

//bots
$lang_module['bot_name'] = "Tên máy chủ ";
$lang_module['bot_agent'] = "UserAgent của máy chủ";
$lang_module['bot_ips'] = "IP của máy chủ";
$lang_module['bot_allowed'] = "Quyền xem";

$lang_module['site_keywords'] = "Từ khóa cho máy chủ tìm kiếm";
$lang_module['site_logo'] = "Tên file logo của site";
$lang_module['site_email'] = "Email của site";
$lang_module['error_send_email'] = "Email nhận thông báo lỗi";
$lang_module['site_phone'] = "Điện thoại liên hệ site";

$lang_module['lang_multi'] = "Kích hoạt đa ngôn ngữ";
$lang_module['site_lang'] = "Ngôn ngữ mặc định";
$lang_module['site_timezone'] = "Múi giờ của site";
$lang_module['date_pattern'] = "Kiểu hiển thị ngày tháng năm";
$lang_module['time_pattern'] = "Kiểu hiển thị giờ phút";
$lang_module['online_upd'] = "Kích hoạt tiện ích đếm số người online";
$lang_module['gzip_method'] = "Bật chế độ gzip";

$lang_module['statistic'] = "Kích hoạt tiện ích thống kê";
$lang_module['proxy_blocker'] = "Kiểm tra và chặn các máy tình dùng proxy";
$lang_module['proxy_blocker_0'] = "Không kiểm tra";
$lang_module['proxy_blocker_1'] = "Kiểm tra nhẹ";
$lang_module['proxy_blocker_2'] = "Kiểm tra vừa";
$lang_module['proxy_blocker_3'] = "Kiểm tra tuyệt đối";

$lang_module['str_referer_blocker'] = "Kích hoạt tiện ích kiểm tra và chuyển hướng các REFERER bên ngoài đến trang chủ";

$lang_module['my_domains'] = "Các domain chạy site, cách nhau bỏi dấu phảy";
$lang_module['cookie_prefix'] = "Tiến tố cookie";
$lang_module['session_prefix'] = "Tiền tố session";

$lang_module['is_user_forum'] = "Chuyển quản lý thành viên cho diễn đàn";

#banip
$lang_module['banip'] = "Quản lý IP cấm";
$lang_module['banip_ip'] = "Ip";
$lang_module['banip_timeban'] = "Thời gian bắt đầu";
$lang_module['banip_timeendban'] = "Thời gian kết thúc";
$lang_module['banip_funcs'] = "Chức năng";
$lang_module['banip_checkall'] = "Chọn tất cả";
$lang_module['banip_uncheckall'] = "Bỏ chọn tất cả";
$lang_module['banip_add'] = "Thêm";
$lang_module['banip_address'] = "Địa chỉ";
$lang_module['banip_begintime'] = "Thời gian bắt đầu cấm";
$lang_module['banip_endtime'] = "Thời gian bắt kết thúc";
$lang_module['banip_notice'] = "Ghi chú";
$lang_module['banip_confirm'] = "Chấp nhận";
$lang_module['banip_mask_select'] = "Hãy chọn";
$lang_module['banip_area'] = "Khu vực";
$lang_module['banip_nolimit'] = "Vô thời hạn";
$lang_module['banip_area_select'] = "Hãy chọn khu vực";
$lang_module['banip_noarea'] = "Chưa xác định";
$lang_module['banip_del_success'] = "Đã xóa thành công !";
$lang_module['banip_area_front'] = "Ngoài site";
$lang_module['banip_area_admin'] = "Khu vực admin";
$lang_module['banip_area_both'] = "Cả admin và ngoài site";
$lang_module['banip_delete_confirm'] = "Bạn có chắc muốn xóa ip này ra khỏi danh sách bị ban?";
$lang_module['banip_mask'] = "Mask IP";
$lang_module['banip_edit'] = "Sửa";
$lang_module['banip_delete'] = "Xóa";
$lang_module['banip_error_ip'] = "Hãy nhập Ip cần ban";
$lang_module['banip_error_area'] = "Bạn cần chọn khu vực";
$lang_module['banip_error_validip'] = "Lỗi: Bạn cần nhập IP đúng chuẩn";

#uploadconfig
$lang_module['uploadconfig'] = "Cấu hình upload";
$lang_module['uploadconfig_ban_ext'] = "Phần mở rộng bị cấm";
$lang_module['uploadconfig_ban_mime'] = "Loại mime bị cấm";
$lang_module['uploadconfig_types'] = "Loại files cho phép";
$lang_module['sys_max_size'] = "Server của bạn chỉ cho phép tải file có dung lượng tối đa";
$lang_module['nv_max_size'] = "Dung lượng tối đa của file tải lên";
$lang_module['upload_checking_mode'] = "Kiểu kiểm tra file tải lên";
$lang_module['strong_mode'] = "Mạnh";
$lang_module['mild_mode'] = "Vừa phải";
$lang_module['lite_mode'] = "Yếu";
$lang_module['upload_checking_note'] = "Máy chủ của bạn không hỗ trợ một số hàm xác định loại file. Nếu chọn \"Mạnh\", bạn sẽ không thể upload file lên host";

#cronjobs
$lang_module['nv_admin_add'] = "Thêm công việc";
$lang_module['nv_admin_edit'] = "Sửa công việc";
$lang_module['nv_admin_del'] = "Xóa công việc";
$lang_module['cron_name_empty'] = "Bạn chưa khai báo tên của công việc";
$lang_module['file_not_exist'] = "File mà bạn khai báo không tồn tại";
$lang_module['func_name_invalid'] = "Bạn chưa khai báo tên hàm hoặc tên hàm không đúng quy định";
$lang_module['nv_admin_add_title'] = "Để thêm công việc, bạn cần khai báo đầy đủ vào các ô trống dưới đây";
$lang_module['nv_admin_edit_title'] = "Để sửa công việc, bạn cần khai báo đầy đủ vào các ô trống dưới đây";
$lang_module['cron_name'] = "Tên công việc";
$lang_module['file_none'] = "Không kết nối";
$lang_module['run_file'] = "Kết nối với file thực thi";
$lang_module['run_file_info'] = "File thực thi phải là một trong những file được chứa trong thư mục &ldquo;<strong>includes/cronjobs/</strong>&rdquo;";
$lang_module['run_func'] = "Kết nối với hàm thực thi";
$lang_module['run_func_info'] = "Hàm thực thi phải được bắt đầu bằng &ldquo;<strong>cron_</strong>&rdquo;";
$lang_module['params'] = "Thông số";
$lang_module['params_info'] = "Phân cách bởi dấu phẩy";
$lang_module['interval'] = "Lặp lại công việc sau";
$lang_module['interval_info'] = "Nếu chọn &ldquo;<strong>0</strong>&rdquo;, công việc sẽ được thực hiện 1 lần duy nhất";
$lang_module['start_time'] = "Thời gian bắt đầu";
$lang_module['min'] = "phút";
$lang_module['hour'] = "giờ";
$lang_module['day'] = "ngày";
$lang_module['month'] = "tháng";
$lang_module['year'] = "năm";
$lang_module['is_del'] = "Xóa sau khi thực hiện xong";
$lang_module['isdel'] = "Xóa";
$lang_module['notdel'] = "Không";
$lang_module['is_sys'] = "Công việc được tạo bởi";
$lang_module['system'] = "Hệ thống";
$lang_module['client'] = "Admin";
$lang_module['act'] = "Tình trạng";
$lang_module['act0'] = "Vô hiệu lực";
$lang_module['act1'] = "Hiệu lực";
$lang_module['last_time'] = "Lần thực hiện gần đây";
$lang_module['next_time'] = "Lần thực hiện sắp tới";
$lang_module['last_time0'] = "Chưa thực hiện lần nào";
$lang_module['last_result'] = "Kết quả của lần thực hiện gần đây";
$lang_module['last_result_empty'] = "n/a";
$lang_module['last_result0'] = "Tồi";
$lang_module['last_result1'] = "Đã hoàn thành";

//adminPage Settings
$lang_module['adminpage_settings'] = "Cấu hình trang Quản trị";
$lang_module['loginMode'] = "Đối tượng được đăng nhập trang quản trị";
$lang_module['loginMode1'] = "Quản trị tối cao";
$lang_module['loginMode2'] = "Người điều hành chung";
$lang_module['loginMode3'] = "Tất cả admin";

?>