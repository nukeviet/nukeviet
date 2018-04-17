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

$lang_module['global_config'] = 'Cấu hình chung';
$lang_module['site_config'] = 'Cấu hình site';
$lang_module['lang_site_config'] = 'Cấu hình site Theo ngôn ngữ: %s';
$lang_module['bots_config'] = 'Máy chủ tìm kiếm';

$lang_module['site_domain'] = 'Tên miền chính của site';
$lang_module['sitename'] = 'Tên gọi của site';
$lang_module['theme'] = 'Giao diện mặc định cho PC';
$lang_module['mobile_theme'] = 'Giao diện mặc định cho Mobile';
$lang_module['themeadmin'] = 'Giao diện người quản trị';
$lang_module['default_module'] = 'Module mặc định trên trang chủ';
$lang_module['description'] = 'Mô tả của site';
$lang_module['rewrite'] = 'Bật chức năng rewrite';
$lang_module['rewrite_optional'] = 'Loại bỏ kí tự ngôn ngữ trên url';
$lang_module['rewrite_op_mod'] = 'Loại bỏ tên module trên url';
$lang_module['disable_content'] = 'Nội dung thông báo site ngưng hoạt động';
$lang_module['submit'] = 'Lưu cấu hình';
$lang_module['err_writable'] = 'Lỗi hệ thống không ghi được file: %s bạn cần cấu hình server cho phép ghi file này.';
$lang_module['err_supports_rewrite'] = 'Lỗi, Máy chủ của bạn không hỗ trợ module rewrite';
$lang_module['err_save_sysconfig'] = 'Các thay đổi đã được lưu lại tuy nhiên hệ thống không ghi được cấu hình ra file. Hãy cấp quyền ghi cho file %s sau đó thực hiện lại';

$lang_module['security'] = 'Thiết lập an ninh';
$lang_module['flood_blocker'] = 'Chống flood';
$lang_module['is_flood_blocker'] = 'Kích hoạt tính năng chống flood';
$lang_module['max_requests_60'] = 'Số requests tối đa trong 1 phút';
$lang_module['max_requests_300'] = 'Số requests tối đa trong 5 phút';
$lang_module['max_requests_error'] = 'Lỗi: Cần nhập số request lớn hơn 0';
$lang_module['nv_anti_iframe'] = 'Chống IFRAME';
$lang_module['nv_anti_agent'] = 'Kiểm tra và chặn các máy tính nếu agent không có';
$lang_module['nv_allowed_html_tags'] = 'Mã HTML được chấp nhận sử dụng trong hệ thống';
$lang_module['nv_debug'] = 'Chế độ nhà phát triển';
$lang_module['nv_debug_help'] = 'Nếu bật tùy chọn này, hệ thống sẽ hiển thị các lỗi để giúp nhà phát triển dễ dàng kiểm tra trong quá trình lập trình. Nếu website đang hoạt động trên môi trường thật, bạn <strong>nên tắt</strong> tùy chọn này';

$lang_module['captcha_type'] = 'Loại captcha';
$lang_module['captcha_type_0'] = 'Captcha mặc định';
$lang_module['captcha_type_1'] = 'Cool php captcha';
$lang_module['captcha_type_2'] = 'reCAPTCHA';
$lang_module['captcha'] = 'Cấu hình hiển thị captcha';
$lang_module['captcha_0'] = 'Không hiển thị';
$lang_module['captcha_1'] = 'Khi admin đăng nhập';
$lang_module['captcha_2'] = 'Khi thành viên đăng nhập';
$lang_module['captcha_3'] = 'Khi khách đăng ký';
$lang_module['captcha_4'] = 'Khi thành viên đăng nhập hoặc khách đăng ký';
$lang_module['captcha_5'] = 'Khi admin hoặc thành viên đăng nhập';
$lang_module['captcha_6'] = 'Khi admin đăng nhập hoặc khách đăng ký';
$lang_module['captcha_7'] = 'Hiển thị trong mọi trường hợp';
$lang_module['captcha_num'] = 'Số ký tự của captcha';
$lang_module['captcha_size'] = 'Kích thước của captcha';
$lang_module['recaptcha_sitekey'] = 'Site key';
$lang_module['recaptcha_secretkey'] = 'Secret key';
$lang_module['recaptcha_type'] = 'Kiểu xác nhận';
$lang_module['recaptcha_type_image'] = 'Hình ảnh (nên chọn)';
$lang_module['recaptcha_type_audio'] = 'Âm thanh';
$lang_module['recaptcha_guide'] = 'Nhấp vào đây để lấy thông số Site key và Secret key.';

$lang_module['ftp_config'] = 'Cấu hình FTP';
$lang_module['smtp_config'] = 'Cấu hình SMTP';
$lang_module['server'] = 'Server or Url';
$lang_module['port'] = 'Port';
$lang_module['username'] = 'User name';
$lang_module['password'] = 'Password';
$lang_module['ftp_path'] = 'Remote path';
$lang_module['mail_config'] = 'Lựa chọn cấu hình';
$lang_module['type_smtp'] = 'SMTP';
$lang_module['type_linux'] = 'Linux Mail';
$lang_module['type_phpmail'] = 'PHPmail';
$lang_module['smtp_server'] = 'Cấu hình máy chủ gửi mail';
$lang_module['incoming_ssl'] = 'Sử dụng Xác thực';
$lang_module['verify_peer_ssl'] = 'Ssl verify peer';
$lang_module['verify_peer_ssl_yes'] = 'Có';
$lang_module['verify_peer_ssl_no'] = 'Không';
$lang_module['verify_peer_name_ssl'] = 'Ssl verify name peer';
$lang_module['outgoing'] = 'Máy chủ (SMTP) Thư Gửi đi';
$lang_module['outgoing_port'] = 'Cổng gửi mail';
$lang_module['smtp_username'] = 'Tài khoản gửi mail';
$lang_module['smtp_login'] = 'Tên Tài khoản';
$lang_module['smtp_pass'] = 'Mật khẩu';
$lang_module['smtp_error_openssl'] = 'Lỗi: Máy chủ của bạn không hỗ trợ gửi mail qua ssl';
$lang_module['bot_name'] = 'Tên máy chủ ';
$lang_module['bot_agent'] = 'UserAgent của máy chủ';
$lang_module['bot_ips'] = 'IP của máy chủ';
$lang_module['bot_allowed'] = 'Quyền xem';
$lang_module['site_keywords'] = 'Từ khóa cho máy chủ tìm kiếm';
$lang_module['site_logo'] = 'Tên file logo của site';
$lang_module['site_banner'] = 'Tên file banner của site';
$lang_module['site_favicon'] = 'Tên file favicon của site';
$lang_module['site_email'] = 'Email của site';
$lang_module['error_set_logs'] = 'Ghi lại lỗi của hệ thống';
$lang_module['error_send_email'] = 'Email nhận thông báo lỗi';
$lang_module['lang_multi'] = 'Kích hoạt chức năng đa ngôn ngữ';
$lang_module['lang_geo'] = 'Kích hoạt chức năng xác định ngôn ngữ theo quốc gia';
$lang_module['lang_geo_config'] = 'Cấu hình chức năng xác định ngôn ngữ theo quốc gia';
$lang_module['site_lang'] = 'Ngôn ngữ mặc định';
$lang_module['site_timezone'] = 'Múi giờ của site';
$lang_module['current_time'] = 'Giờ hiện tại: %s';
$lang_module['date_pattern'] = 'Kiểu hiển thị ngày tháng năm';
$lang_module['time_pattern'] = 'Kiểu hiển thị giờ phút';
$lang_module['gzip_method'] = 'Bật chế độ gzip';
$lang_module['proxy_blocker'] = 'Kiểm tra và chặn các máy tính dùng proxy';
$lang_module['proxy_blocker_0'] = 'Không kiểm tra';
$lang_module['proxy_blocker_1'] = 'Kiểm tra nhẹ';
$lang_module['proxy_blocker_2'] = 'Kiểm tra vừa';
$lang_module['proxy_blocker_3'] = 'Kiểm tra tuyệt đối';
$lang_module['str_referer_blocker'] = 'Kích hoạt tiện ích kiểm tra và chuyển hướng các REFERER bên ngoài đến trang chủ';
$lang_module['my_domains'] = 'Các domain chạy site, cách nhau bởi dấu phảy';
$lang_module['searchEngineUniqueID'] = 'Google search Engine ID<br />(Có dạng 000329275761967753447:sr7yxqgv294 , <a href="http://nukeviet.vn/vi/faq/Su-dung-Google-Custom-Search-tren-NukeViet/" target="_blank">xem chi tiết</a>)';

$lang_module['variables'] = 'Cấu hình cookie session';
$lang_module['cookie_prefix'] = 'Tiến tố cookie';
$lang_module['session_prefix'] = 'Tiền tố session';
$lang_module['live_cookie_time'] = 'Thời gian tồn tại của cookie';
$lang_module['live_session_time'] = 'Thời gian tồn tại session';
$lang_module['live_session_time0'] = '=0 Tồn tại đến khi đóng trình duyệt';
$lang_module['cookie_secure'] = 'cookie secure';
$lang_module['cookie_httponly'] = 'cookie httponly';

$lang_module['is_user_forum'] = 'Chuyển quản lý thành viên cho diễn đàn';
$lang_module['banip'] = 'Quản lý IP cấm';
$lang_module['banip_ip'] = 'Ip';
$lang_module['banip_timeban'] = 'Thời gian bắt đầu';
$lang_module['banip_timeendban'] = 'Thời gian kết thúc';
$lang_module['banip_funcs'] = 'Chức năng';
$lang_module['banip_checkall'] = 'Chọn tất cả';
$lang_module['banip_uncheckall'] = 'Bỏ chọn tất cả';
$lang_module['banip_title_add'] = 'Thêm địa chỉ IP cấm';
$lang_module['banip_title_edit'] = 'Sửa địa chỉ IP cấm';
$lang_module['banip_address'] = 'Địa chỉ';
$lang_module['banip_begintime'] = 'Thời gian bắt đầu cấm';
$lang_module['banip_endtime'] = 'Thời gian bắt kết thúc';
$lang_module['banip_notice'] = 'Ghi chú';
$lang_module['banip_confirm'] = 'Chấp nhận';
$lang_module['banip_mask_select'] = 'Hãy chọn';
$lang_module['banip_area'] = 'Khu vực';
$lang_module['banip_nolimit'] = 'Vô thời hạn';
$lang_module['banip_area_select'] = 'Hãy chọn khu vực';
$lang_module['banip_noarea'] = 'Chưa xác định';
$lang_module['banip_del_success'] = 'Đã xóa thành công !';
$lang_module['banip_area_front'] = 'Ngoài site';
$lang_module['banip_area_admin'] = 'Khu vực admin';
$lang_module['banip_area_both'] = 'Cả admin và ngoài site';
$lang_module['banip_delete_confirm'] = 'Bạn có chắc muốn xóa ip này ra khỏi danh sách bị ban?';
$lang_module['banip_mask'] = 'Mask IP';
$lang_module['banip_edit'] = 'Sửa';
$lang_module['banip_delete'] = 'Xóa';
$lang_module['banip_error_ip'] = 'Hãy nhập Ip cần ban';
$lang_module['banip_error_area'] = 'Bạn cần chọn khu vực';
$lang_module['banip_error_validip'] = 'Lỗi: Bạn cần nhập IP đúng chuẩn';
$lang_module['banip_error_write'] = 'Lỗi: Bạn đã không cấp quyền để hệ thống có thể ghi file, hãy CHMOD thư mục <strong>%s</strong> ở chế độ 0777 hoặc "Change permission" để hệ thống có thể ghi file, nếu không hãy tạo file banip.php với nội dung bên dưới và đặt vào thư mục <strong>%s</strong>';

$lang_module['nv_admin_add'] = 'Thêm công việc';
$lang_module['nv_admin_edit'] = 'Sửa công việc';
$lang_module['nv_admin_del'] = 'Xóa công việc';
$lang_module['cron_name_empty'] = 'Bạn chưa khai báo tên của công việc';
$lang_module['file_not_exist'] = 'File mà bạn khai báo không tồn tại';
$lang_module['func_name_invalid'] = 'Bạn chưa khai báo tên hàm hoặc tên hàm không đúng quy định';
$lang_module['func_name_not_exist'] = 'Tên hàm bạn khai báo không tồn tại';
$lang_module['nv_admin_add_title'] = 'Để thêm công việc, bạn cần khai báo đầy đủ vào các ô trống dưới đây';
$lang_module['nv_admin_edit_title'] = 'Để sửa công việc, bạn cần khai báo đầy đủ vào các ô trống dưới đây';
$lang_module['cron_name'] = 'Tên công việc';
$lang_module['file_none'] = 'Không kết nối';
$lang_module['run_file'] = 'Kết nối với file thực thi';
$lang_module['run_file_info'] = 'File thực thi phải là một trong những file được chứa trong thư mục &ldquo;<strong>includes/cronjobs/</strong>&rdquo;';
$lang_module['run_func'] = 'Kết nối với hàm thực thi';
$lang_module['run_func_info'] = 'Hàm thực thi phải được bắt đầu bằng &ldquo;<strong>cron_</strong>&rdquo;';
$lang_module['params'] = 'Thông số';
$lang_module['params_info'] = 'Phân cách bởi dấu phẩy';
$lang_module['interval'] = 'Lặp lại công việc sau';
$lang_module['interval_info'] = 'Nếu chọn &ldquo;<strong>0</strong>&rdquo;, công việc sẽ được thực hiện 1 lần duy nhất';
$lang_module['start_time'] = 'Thời gian bắt đầu';
$lang_module['min'] = 'phút';
$lang_module['hour'] = 'giờ';
$lang_module['day'] = 'ngày';
$lang_module['month'] = 'tháng';
$lang_module['year'] = 'năm';
$lang_module['is_del'] = 'Xóa sau khi thực hiện xong';
$lang_module['isdel'] = 'Xóa';
$lang_module['notdel'] = 'Không';
$lang_module['is_sys'] = 'Công việc được tạo bởi';
$lang_module['system'] = 'Hệ thống';
$lang_module['client'] = 'Admin';
$lang_module['act'] = 'Tình trạng';
$lang_module['act0'] = 'Vô hiệu lực';
$lang_module['act1'] = 'Hiệu lực';
$lang_module['last_time'] = 'Lần thực hiện gần đây';
$lang_module['next_time'] = 'Lần thực hiện sắp tới';
$lang_module['last_time0'] = 'Chưa thực hiện lần nào';
$lang_module['last_result'] = 'Kết quả của lần thực hiện gần đây';
$lang_module['last_result_empty'] = 'n/a';
$lang_module['last_result0'] = 'Tồi';
$lang_module['last_result1'] = 'Đã hoàn thành';
$lang_module['closed_site'] = 'Chế độ đóng cửa site';
$lang_module['closed_site_0'] = 'Site hoạt động bình thường';
$lang_module['closed_site_1'] = 'Đóng cửa site chỉ có Quản trị tối cao truy cập';
$lang_module['closed_site_2'] = 'Đóng cửa site Người điều hành chung truy cập';
$lang_module['closed_site_3'] = 'Đóng cửa site Tất cả admin truy cập';
$lang_module['ssl_https'] = 'Chế độ sử dụng SSL';
$lang_module['ssl_https_module'] = 'Các module kích hoạt SSL';
$lang_module['ssl_https_0'] = 'Tắt SSL';
$lang_module['ssl_https_1'] = 'Kích hoạt toàn site';
$lang_module['ssl_https_2'] = 'Kích hoạt khu vực admin';
$lang_module['note_ssl'] = 'Bạn có chắc chắn site bạn hỗ trợ https không? Nếu không hỗ trợ sẽ không truy cập được các khu vực tương ứng sau khi lưu?';
$lang_module['timezoneAuto'] = 'Theo máy tính của khách truy cập';
$lang_module['timezoneByCountry'] = 'Theo quốc gia của khách truy cập';
$lang_module['allow_switch_mobi_des'] = 'Cho phép chuyển đổi giao diện mobile, desktop';
$lang_module['allow_theme_type'] = 'Các loại giao diện được sử dụng';
$lang_module['ftp_auto_detect_root'] = 'Xác định tự động';
$lang_module['ftp_error_full'] = 'Hãy nhập đủ các thông số để tự động nhận diện Remote path';
$lang_module['ftp_error_detect_root'] = 'Không thể tìm thấy thông số nào phù hợp, hãy kiểm tra lại tên đăng nhập và mật khẩu';
$lang_module['ftp_error_support'] = 'Máy chủ của bạn hiện đang chặn hoặc không hỗ trợ thư viện FTP, hãy liên hệ với nhà cung cấp để được kích hoạt.';
$lang_module['cdn_url'] = 'Hosting CDN cho javascript, css';
$lang_module['cdn_download'] = 'Download các file javascript, css';

$lang_module['plugin'] = 'Thiết lập Plugin';
$lang_module['plugin_info'] = 'Plugin thi phải là file php được chứa trong thư mục &ldquo;<strong>includes/plugin/</strong>&rdquo;. Các Plugin này sẽ luôn luôn chạy cùng hệ thống khi được kích hoạt';
$lang_module['plugin_file'] = 'File thực thi';
$lang_module['plugin_area'] = 'Khu vực';
$lang_module['plugin_area_1'] = 'Trước khi kết nối CSDL';
$lang_module['plugin_area_2'] = 'Trước khi gọi các module';
$lang_module['plugin_area_3'] = 'Trước khi website gửi nội dung tới trình duyệt';
$lang_module['plugin_area_4'] = 'Sau khi gọi các module';
$lang_module['plugin_number'] = 'Số thứ tự';
$lang_module['plugin_func'] = 'Chức năng';
$lang_module['plugin_add'] = 'Thêm plugin';
$lang_module['plugin_file_delete'] = 'Xóa khỏi hệ thống';

$lang_module['notification_config'] = 'Cấu hình chức năng thông báo';
$lang_module['notification_active'] = 'Hiển thị thông báo khi có hoạt động mới';
$lang_module['notification_autodel'] = 'Tự động xóa thông báo sau thời gian';
$lang_module['notification_autodel_note'] = 'Điền <strong>0</strong> nếu không muốn tự động xóa';
$lang_module['notification_day'] = 'ngày';

$lang_module['is_login_blocker'] = 'Kích hoạt chức năng chặn đăng nhập sai nhiều lần';
$lang_module['login_number_tracking'] = 'Số lần đăng nhập sai tối đa trong khoảng thời gian theo dõi';
$lang_module['login_time_tracking'] = 'Thời gian theo dõi';
$lang_module['login_time_ban'] = 'Thời gian bị cấm đăng nhập';

$lang_module['two_step_verification'] = 'Yêu cầu xác thực đăng nhập hai bước tại';
$lang_module['two_step_verification0'] = 'Không yêu cầu';
$lang_module['two_step_verification1'] = 'Khu vực quản trị';
$lang_module['two_step_verification2'] = 'Khu vực ngoài site';
$lang_module['two_step_verification3'] = 'Tất cả các khu vực';
$lang_module['two_step_verification_note'] = 'Chú ý: Cấu hình này áp dụng cho toàn bộ tài khoản của các nhóm, nếu cần cấu hình riêng cho từng nhóm hãy chọn giá trị này là <strong>%s</strong> sau đó sửa <a href="%s">nhóm</a> rồi chọn khu vực bắt buộc kích hoạt xác thực hai bước theo ý muốn';

$lang_module['site_phone'] = 'Số điện thoại của site';
$lang_module['googleMapsAPI'] = 'Google Maps API key';
$lang_module['googleMapsAPI_guide'] = '<a href="https://wiki.nukeviet.vn/google:api:creat-google-map-apikey" target="_blank">Hướng dẫn lấy Google Maps API key</a>';

$lang_module['noflood_ip_add'] = 'Thêm IP bỏ qua kiểm tra flood';
$lang_module['noflood_ip_edit'] = 'Sửa IP bỏ qua kiểm tra flood';
$lang_module['noflood_ip_list'] = 'Các IP bỏ qua kiểm tra flood';
