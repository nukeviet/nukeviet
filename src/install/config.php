<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit();
}

$db_config['dbhost'] = 'localhost';
$db_config['dbtype'] = 'mysql';
$db_config['dbport'] = '';
$db_config['dbname'] = '';
$db_config['dbuname'] = '';
$db_config['dbpass'] = '';
$db_config['dbdetete'] = 0;
$db_config['prefix'] = 'nv5';
$db_config['persistent'] = false;
$db_config['collation'] = ''; //utf8_general_ci, utf8mb4_unicode_ci, utf8mb4_vietnamese_ci

$array_data = [];
$array_data['lang_multi'] = 0;
$array_data['site_name'] = 'NUKEVIET';
$array_data['nv_login'] = '';
$array_data['nv_email'] = '';
$array_data['nv_password'] = '';
$array_data['re_password'] = '';
$array_data['question'] = '';
$array_data['answer_question'] = '';
$array_data['socialbutton'] = 1;
$array_data['dev_mode'] = 0;

$global_config['unofficial_mode'] = 1; // Cảnh báo bản thử nghiệm
$global_config['version'] = '5.0.00'; // NukeViet 5.0 Develop
$global_config['version_time'] = 1736144674;
$global_config['core_cdn_url'] = 'https://cdn.jsdelivr.net/gh/nukeviet/nukeviet@nukeviet5.0/src/';
$global_config['site_email'] = '';
$global_config['site_phone'] = '';
$global_config['error_set_logs'] = 1;
$global_config['error_send_email'] = 'support@nukeviet.vn';
$global_config['my_domains'] = '';
$global_config['cookie_prefix'] = '';
$global_config['session_prefix'] = '';
$global_config['cookie_secure'] = 0;
$global_config['cookie_httponly'] = 1;
$global_config['cookie_SameSite'] = 'Lax';

$global_config['sitekey'] = '';
$global_config['site_home_module'] = 'news';
$global_config['idsite'] = 0;

$global_config['site_timezone'] = 'byCountry';
$global_config['statistics_timezone'] = '';
$global_config['gzip_method'] = 1;
$global_config['blank_operation'] = 1;
$global_config['rewrite_enable'] = 1;
$global_config['admin_rewrite'] = 1;
$global_config['rewrite_endurl'] = '/';
$global_config['rewrite_exturl'] = '.html';
$global_config['rewrite_optional'] = 0;
$global_config['rewrite_op_mod'] = '';

$global_config['crossadmin_restrict'] = 1;
$global_config['crosssite_restrict'] = 1;
$global_config['domains_restrict'] = 1;

$global_config['str_referer_blocker'] = 0;

/*
 * Proxy tin cậy: chỉ tin các header IP do proxy đặt (X-Forwarded-For, CF-Connecting-IP...)
 * khi IP kết nối trực tiếp (REMOTE_ADDR) thuộc danh sách dưới đây.
 * Danh sách gồm dải mạng nội bộ (reverse proxy chạy cùng máy hoặc cùng LAN) và dải IP của Cloudflare.
 *
 * Lưu ý: dải IP Cloudflare thay đổi theo thời gian, danh sách dưới đây có thể outdate.
 * Nguồn chính thức: https://www.cloudflare.com/ips/
 * Sau khi cài đặt xong, hãy cập nhật lại bằng nút "Lấy dải IP Cloudflare" trong
 * Quản trị > Cấu hình > Quản lý proxy tin cậy.
 */
$global_config['trusted_proxies'] = ['127.0.0.0/8', '::1', '10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16', 'fc00::/7', '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22', '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20', '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13', '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22', '2400:cb00::/32', '2606:4700::/32', '2803:f800::/32', '2405:b500::/32', '2405:8100::/32', '2a06:98c0::/29', '2c0f:f248::/32'];
$global_config['trusted_proxy_enable'] = 1;

$global_config['lang_multi'] = 1;
$global_config['lang_geo'] = 0;
$global_config['site_lang'] = 'en';
$global_config['engine_allowed'] = [];
$global_config['site_theme'] = 'default';
$global_config['notification_active'] = 0;

// Tài khoản chỉ được sử dụng Unicode, không có các ký tự đặc biệt
$global_config['nv_unick_type'] = 4;

// Mật khẩu cần kết hợp số và chữ, yêu cầu có chữ in HOA
$global_config['nv_upass_type'] = 3;

// Thời gian lặp lại việc sao lưu CSDL tính bằng ngày
$global_config['dump_interval'] = 1;

//hashprefix: support LDAP({CRYPT}, {SSHA512}, {SSHA256}, {SSHA}, {SHA}, {MD5}); {NV3}
$global_config['hashprefix'] = '{CRYPT}';

//so ky tu toi da cua password doi voi user
$global_config['nv_upassmax'] = 32;

//so ky tu toi thieu cua password doi voi user
$global_config['nv_upassmin'] = 8;

//so ky tu toi da cua ten tai khoan doi voi user
$global_config['nv_unickmax'] = 20;

//so ky tu toi thieu cua ten tai khoan doi voi user
$global_config['nv_unickmin'] = 4;

define('NV_LIVE_COOKIE_TIME', 31104000);

define('NV_LIVE_SESSION_TIME', 0);

// Ma HTML duoc chap nhan
define('NV_ALLOWED_HTML_TAGS', 'embed, object, param, a, b, blockquote, br, caption, col, colgroup, div, em, h1, h2, h3, h4, h5, h6, hr, i, img, li, p, span, strong, s, sub, sup, table, tbody, td, th, tr, u, ul, ol, iframe, figure, figcaption, video, audio, source, track, code, pre, mark');

//Chống IFRAME
define('NV_ANTI_IFRAME', 1);

//Chặn các bots nếu agent không có
define('NV_ANTI_AGENT', 0);

// Chế độ phát triển
define('NV_DEBUG', 0);

// Kích thước ảnh tối đa mặc định
define('NV_MAX_WIDTH', 1500);
define('NV_MAX_HEIGHT', 1500);
