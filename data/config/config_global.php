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
    exit('Stop!!!');
}

define('NV_ALLOWED_HTML_TAGS', 'embed, object, param, a, b, blockquote, br, caption, col, colgroup, div, em, h1, h2, h3, h4, h5, h6, hr, i, img, li, p, span, strong, s, sub, sup, table, tbody, td, th, tr, u, ul, ol, iframe, figure, figcaption, video, audio, source, track, code, pre');
define('NV_ANTI_AGENT', 0);
define('NV_ANTI_IFRAME', 1);
define('NV_DEBUG', 0);
define('NV_GFX_HEIGHT', 40);
define('NV_GFX_NUM', 6);
define('NV_GFX_WIDTH', 150);
define('NV_LIVE_COOKIE_TIME', 31104000);
define('NV_LIVE_SESSION_TIME', 0);
define('NV_MAX_HEIGHT', 1500);
define('NV_MAX_WIDTH', 1500);
define('NV_MOBILE_MODE_IMG', 480);
define('NV_EOL', "\r\n");
define('NV_UPLOAD_MAX_FILESIZE', 2097152);
$global_config['admfirewall'] = 0;
$global_config['admin_2step_default'] = 'code';
$global_config['admin_2step_opt'] = 'code';
$global_config['admin_check_pass_time'] = 1800;
$global_config['admin_user_logout'] = 0;
$global_config['admin_XSSsanitize'] = 1;
$global_config['allow_null_origin'] = 0;
$global_config['allow_sitelangs'] = ['vi','en'];
$global_config['assets_cdn'] = 1;
$global_config['authors_detail_main'] = 0;
$global_config['autocheckupdate'] = 1;
$global_config['autoupdatetime'] = 24;
$global_config['block_admin_ip'] = 0;
$global_config['cdn_url'] = 'https://cdn.timurtravel.net';
$global_config['check_zaloip_expired'] = 0;
$global_config['closed_site'] = 0;
$global_config['cookie_httponly'] = 1;
$global_config['cookie_notice_popup'] = 0;
$global_config['cookie_prefix'] = 'nv4';
$global_config['cookie_SameSite'] = 'Lax';
$global_config['cookie_secure'] = 0;
$global_config['crossadmin_restrict'] = 1;
$global_config['crossadmin_valid_domains'] = [];
$global_config['crossadmin_valid_ips'] = [];
$global_config['crosssite_allowed_variables'] = [0 => ['nv' => 'verifyemail', 'api' => '']];
$global_config['crosssite_restrict'] = 1;
$global_config['crosssite_valid_domains'] = [];
$global_config['crosssite_valid_ips'] = [];
$global_config['display_errors_list'] = 32767;
$global_config['domains_restrict'] = 1;
$global_config['domains_whitelist'] = [0 => 'youtube.com', 1 => 'www.youtube.com', 2 => 'google.com', 3 => 'www.google.com', 4 => 'drive.google.com', 5 => 'docs.google.com'];
$global_config['dump_autobackup'] = 1;
$global_config['dump_backup_day'] = 30;
$global_config['dump_backup_ext'] = 'gz';
$global_config['dump_interval'] = 1;
$global_config['end_url_variables'] = [];
$global_config['error_send_email'] = 'anhtunguyen71@gmail.com';
$global_config['error_set_logs'] = 1;
$global_config['file_allowed_ext'] = ['adobe','archives','audio','documents','flash','images','real','video'];
$global_config['forbid_extensions'] = ['htm','html','htmls','js','php','php3','php4','php5','phtml','inc'];
$global_config['forbid_mimes'] = ['application/ecmascript','application/javascript','application/x-javascript','text/ecmascript','text/html','text/javascript','application/x-httpd-php','application/x-httpd-php-source','application/php','application/x-php','text/php','text/x-php'];
$global_config['ftp_check_login'] = 0;
$global_config['ftp_path'] = '/';
$global_config['ftp_port'] = 21;
$global_config['ftp_server'] = 'localhost';
$global_config['ftp_user_name'] = '';
$global_config['ftp_user_pass'] = 'Y9fzD4zLuhPzhNpHx6Q5iA,,';
$global_config['gzip_method'] = 1;
$global_config['ip_allow_null_origin'] = [];
$global_config['is_flood_blocker'] = 1;
$global_config['is_login_blocker'] = 1;
$global_config['lang_geo'] = 0;
$global_config['lang_multi'] = 1;
$global_config['login_number_tracking'] = 5;
$global_config['login_time_ban'] = 30;
$global_config['login_time_tracking'] = 5;
$global_config['max_requests_300'] = 150;
$global_config['max_requests_60'] = 40;
$global_config['my_domains'] = 'nukeviet46.com,nukeviet.tk,www.nukeviet.tk,nukeviet.xyz';
$global_config['notification_active'] = 1;
$global_config['notification_autodel'] = 15;
$global_config['nv_auto_resize'] = 1;
$global_config['nv_display_errors_list'] = 1;
$global_config['nv_max_size'] = 5242880;
$global_config['nv_overflow_size'] = 0;
$global_config['nv_static_url'] = 'https://s1.timurtravel.info';
$global_config['proxy_blocker'] = 0;
$global_config['read_type'] = 0;
$global_config['recaptcha_secretkey'] = 'w2kL-cL5vQKP4afETAIehQet0IakyhOI68AlIT3P6N88zVtKXpi15jTIvDFG_in2';
$global_config['recaptcha_sitekey'] = '6LexZnAUAAAAAD7QnyLgy_B-70sjosv-P9JEnKea';
$global_config['recaptcha_type'] = 'image';
$global_config['recaptcha_ver'] = 2;
$global_config['remote_api_access'] = 0;
$global_config['remote_api_log'] = 1;
$global_config['request_uri_check'] = 'page';
$global_config['resource_preload'] = 2;
$global_config['rewrite_enable'] = 1;
$global_config['rewrite_endurl'] = '/';
$global_config['rewrite_exturl'] = '.html';
$global_config['rewrite_op_mod'] = '';
$global_config['rewrite_optional'] = 0;
$global_config['session_prefix'] = 'nv4s_s84Euy';
$global_config['site_keywords'] = 'NukeViet, portal, mysql, php';
$global_config['site_lang'] = 'vi';
$global_config['site_reopening_time'] = 0;
$global_config['site_timezone'] = 'byCountry';
$global_config['spadmin_add_admin'] = 1;
$global_config['str_referer_blocker'] = 1;
$global_config['timestamp'] = 1651484938;
$global_config['two_step_verification'] = 0;
$global_config['upload_alt_require'] = 1;
$global_config['upload_auto_alt'] = 1;
$global_config['upload_checking_mode'] = 'strong';
$global_config['upload_chunk_size'] = 0;
$global_config['useactivate'] = 2;
$global_config['users_special'] = 0;
$global_config['version'] = '4.6.00';
$global_config['XSSsanitize'] = 1;
$global_config['zaloWebhookIPs'] = [];
$global_config['check_rewrite_file'] = 1;
$global_config['allow_request_mods'] = ['get','post','cookie','session','request','env','server'];
$global_config['request_default_mode'] = 'request';
$global_config['log_errors_list'] = 32767;
$global_config['send_errors_list'] = 256;
$global_config['error_log_path'] = 'data/logs/error_logs';
$global_config['error_log_filename'] = 'error_log';
$global_config['error_log_fileext'] = 'log';
$global_config['setup_langs'] = ['vi','en'];
$global_config['allowed_html_tags'] = ['embed','object','param','a','b','blockquote','br','caption','col','colgroup','div','em','h1','h2','h3','h4','h5','h6','hr','i','img','li','p','span','strong','s','sub','sup','table','tbody','td','th','tr','u','ul','ol','iframe','figure','figcaption','video','audio','source','track','code','pre'];
$global_config['engine_allowed'] = ['Google' => ['host_pattern' => 'google.', 'query_param' => 'q'], 'Yahoo' => ['host_pattern' => 'yahoo.', 'query_param' => 'p'], 'MSN' => ['host_pattern' => 'search.msn', 'query_param' => 'q'], 'Localhost' => ['host_pattern' => 'localhost', 'query_param' => 'q']];

$language_array = ['en' => ['name' => 'English', 'language' => 'English'], 'fr' => ['name' => 'Français', 'language' => 'French'], 'vi' => ['name' => 'Tiếng Việt', 'language' => 'Vietnamese']];

$nv_plugins = ['vi' => ['' => ['change_site_buffer' => [1 => [0 => 'includes/plugin/cdn_js_css_image.php', 1 => '', 2 => 1007]], 'get_qr_code' => [1 => [0 => 'includes/plugin/qrcode.php', 1 => '', 2 => 1]], 'get_rewrite_domain' => [1 => [0 => 'includes/plugin/rewrite_obsolute.php', 1 => '', 2 => 1006]], 'zalo_webhook' => [1 => [0 => 'includes/plugin/zalo_webhook.php', 1 => '', 2 => 1004]]]], 'en' => ['' => ['change_site_buffer' => [1 => [0 => 'includes/plugin/cdn_js_css_image.php', 1 => '', 2 => 1007]], 'get_qr_code' => [1 => [0 => 'includes/plugin/qrcode.php', 1 => '', 2 => 1]], 'get_rewrite_domain' => [1 => [0 => 'includes/plugin/rewrite_obsolute.php', 1 => '', 2 => 1006]], 'zalo_webhook' => [1 => [0 => 'includes/plugin/zalo_webhook.php', 1 => '', 2 => 1004]]]]];
