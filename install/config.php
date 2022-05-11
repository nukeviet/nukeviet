<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
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
$db_config['prefix'] = 'nv4';
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
$global_config['version'] = '4.6.00'; // NukeViet 4.6 Develop
$global_config['core_cdn_url'] = 'https://cdn.jsdelivr.net/gh/nukeviet/nukeviet@nukeviet4.6/';
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
$global_config['rewrite_enable'] = 1;
$global_config['rewrite_endurl'] = '/';
$global_config['rewrite_exturl'] = '.html';
$global_config['rewrite_optional'] = 0;
$global_config['rewrite_op_mod'] = '';

$global_config['crossadmin_restrict'] = 1;
$global_config['crosssite_restrict'] = 1;
$global_config['domains_restrict'] = 1;

$global_config['proxy_blocker'] = 0;
$global_config['str_referer_blocker'] = 0;

$global_config['lang_multi'] = 1;
$global_config['lang_geo'] = 0;
$global_config['site_lang'] = 'en';
$global_config['engine_allowed'] = [];
$global_config['site_theme'] = 'default';

// Tài khoản chỉ được sử dụng Unicode, không có các ký tự đặc biệt
$global_config['nv_unick_type'] = 4;

// Mật khẩu cần kết hợp số và chữ, yêu cầu có chữ in HOA
$global_config['nv_upass_type'] = 3;

// Thời gian lặp lại việc sao lưu CSDL tính bằng ngày
$global_config['dump_interval'] = 1;

//hashprefix: support LDAP({SSHA512}, {SSHA256}, {SSHA}, {SHA}, {MD5}); {NV3}
$global_config['hashprefix'] = '{SSHA512}';

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
define('NV_ALLOWED_HTML_TAGS', 'embed, object, param, a, b, blockquote, br, caption, col, colgroup, div, em, h1, h2, h3, h4, h5, h6, hr, i, img, li, p, span, strong, s, sub, sup, table, tbody, td, th, tr, u, ul, ol, iframe, figure, figcaption, video, audio, source, track, code, pre');

//Chống IFRAME
define('NV_ANTI_IFRAME', 1);

//Chặn các bots nếu agent không có
define('NV_ANTI_AGENT', 0);

// Chế độ phát triển
define('NV_DEBUG', 0);
