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

$lang_module['global_config'] = "Cấu hình chung";
$lang_module['lang_site_config'] = "Theo ngôn ngữ";
$lang_module['bots_config'] = "Máy chủ tìm kiếm";

$lang_module['checkupdate'] = "Kiểm tra phiên bản";
$lang_module['sitename'] = "Tên gọi của site";
$lang_module['theme'] = "Giao diện mặc định";
$lang_module['themeadmin'] = "Giao diện người quản trị";
$lang_module['default_module'] = "Module mặc định trên trang chủ";
$lang_module['description'] = "Mô tả của site";
$lang_module['rewrite'] = "Bật chức năng rewrite";
$lang_module['rewrite_optional'] = "Nếu bật chức năng rewrite thì loại bỏ kí tự ngôn ngữ trên url";
$lang_module['site_disable'] = "Site ngưng hoạt động";
$lang_module['disable_content'] = "Nội dung thông báo";
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

$lang_module['smtp_server'] = "Server Infomation";
$lang_module['incoming_ssl'] = "This server requires an encrypted connection (SSL)";
$lang_module['outgoing'] = "Outgoing mail server (SMTP)";
$lang_module['outgoing_port'] = "Outgoing server(SMTP)";
$lang_module['smtp_username'] = "Logon infomation";
$lang_module['smtp_login'] = "User Name";
$lang_module['smtp_pass'] = "Password";

//check update


$lang_module['update_error'] = "Lỗi: hệ thống không check được thông tin, Bạn vui lòng kiểm tra lại vào thời gian khác";
$lang_module['version_latest'] = "Phiên bản hiện tại của bạn đang là mới nhất";
$lang_module['version_no_latest'] = "Phiên bản của bạn chưa mới nhất";
$lang_module['version_info'] = "Thông tin phiên bản mới nhất";
$lang_module['version_name'] = "Tên hệ thống";
$lang_module['version_number'] = "Số phiên bản";
$lang_module['version_date'] = "Ngày phát hành";
$lang_module['version_note'] = "Ghi chú về phiên bản mới";

$lang_module['version_download'] = " bạn có thể download phiên bản mới";
$lang_module['version_updatenew'] = "update phiên bản mới";

$lang_module['smtp_pass'] = "Password";

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
$lang_module['proxy_blocker_2'] = "Kểm tra vừa";
$lang_module['proxy_blocker_3'] = "Kiểm tra tuyệt đối";

$lang_module['str_referer_blocker'] = "Kích hoạt tiện ích kiểm tra và chuyển hướng các REFERER bên ngoài đến trang chủ";

$lang_module['my_domains'] = "Các domain chạy site, cách nhau bỏi dấu phảy";
$lang_module['cookie_prefix'] = "Tiến tố cookie";
$lang_module['session_prefix'] = "Tiền tố session";

$lang_module['is_user_forum'] = "Chuyển quản lý thành viên cho diễn đàn";

?>