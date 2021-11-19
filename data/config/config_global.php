<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

define('NV_ALLOWED_HTML_TAGS', 'embed, object, param, a, b, blockquote, br, caption, col, colgroup, div, em, h1, h2, h3, h4, h5, h6, hr, i, img, li, p, span, strong, s, sub, sup, table, tbody, td, th, tr, u, ul, ol, iframe, figure, figcaption, video, audio, source, track, code, pre');
define('NV_ANTI_AGENT', 0);
define('NV_ANTI_IFRAME', 1);
define('NV_DEBUG', 1);
define('NV_GFX_HEIGHT', 40);
define('NV_GFX_NUM', 6);
define('NV_GFX_WIDTH', 150);
define('NV_LIVE_COOKIE_TIME', 31104000);
define('NV_LIVE_SESSION_TIME', 0);
define('NV_MAX_HEIGHT', 1500);
define('NV_MAX_WIDTH', 1500);
define('NV_MOBILE_MODE_IMG', 480);
define('NV_EOL', "\n");
define('NV_UPLOAD_MAX_FILESIZE', 41943040);
$global_config['admfirewall'] = 0;
$global_config['admin_2step_default'] = 'code';
$global_config['admin_2step_opt'] = 'code';
$global_config['admin_check_pass_time'] = 1800;
$global_config['allow_null_origin'] = 0;
$global_config['allow_sitelangs'] = ['vi'];
$global_config['authors_detail_main'] = 0;
$global_config['autocheckupdate'] = 1;
$global_config['autoupdatetime'] = 24;
$global_config['block_admin_ip'] = 0;
$global_config['cdn_url'] = '';
$global_config['closed_site'] = 0;
$global_config['cookie_httponly'] = 1;
$global_config['cookie_notice_popup'] = 0;
$global_config['cookie_prefix'] = 'nv4';
$global_config['cookie_SameSite'] = 'Lax';
$global_config['cookie_secure'] = 0;
$global_config['crossadmin_restrict'] = 1;
$global_config['crossadmin_valid_domains'] = [];
$global_config['crossadmin_valid_ips'] = [];
$global_config['crosssite_restrict'] = 1;
$global_config['crosssite_valid_domains'] = [];
$global_config['crosssite_valid_ips'] = [];
$global_config['display_errors_list'] = 32767;
$global_config['domains_restrict'] = 1;
$global_config['domains_whitelist'] = ['youtube.com','www.youtube.com','google.com','www.google.com','drive.google.com','docs.google.com'];
$global_config['dump_autobackup'] = 0;
$global_config['dump_backup_day'] = 30;
$global_config['dump_backup_ext'] = 'gz';
$global_config['dump_interval'] = 1;
$global_config['error_send_email'] = 'you@example.com';
$global_config['error_set_logs'] = 1;
$global_config['file_allowed_ext'] = ['adobe','archives','audio','documents','flash','images','real','video'];
$global_config['forbid_extensions'] = ['htm','html','htmls','js','php','php3','php4','php5','phtml','inc'];
$global_config['forbid_mimes'] = ['application/ecmascript','application/javascript','application/x-javascript','text/ecmascript','text/html','text/javascript','application/x-httpd-php','application/x-httpd-php-source','application/php','application/x-php','text/php','text/x-php'];
$global_config['ftp_check_login'] = 0;
$global_config['ftp_path'] = '/';
$global_config['ftp_port'] = 21;
$global_config['ftp_server'] = 'localhost';
$global_config['ftp_user_name'] = '';
$global_config['ftp_user_pass'] = 'gt16sCOHXlmRtYqCLqKQ8w,,';
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
$global_config['my_domains'] = 'localhost';
$global_config['notification_active'] = 1;
$global_config['notification_autodel'] = 15;
$global_config['nv_auto_resize'] = 1;
$global_config['nv_display_errors_list'] = 1;
$global_config['nv_max_size'] = 41943040;
$global_config['nv_overflow_size'] = 0;
$global_config['nv_static_url'] = '';
$global_config['proxy_blocker'] = 0;
$global_config['read_type'] = 0;
$global_config['recaptcha_secretkey'] = '';
$global_config['recaptcha_sitekey'] = '';
$global_config['recaptcha_type'] = 'image';
$global_config['recaptcha_ver'] = 2;
$global_config['remote_api_access'] = 0;
$global_config['remote_api_log'] = 1;
$global_config['rewrite_enable'] = 1;
$global_config['rewrite_endurl'] = '/';
$global_config['rewrite_exturl'] = '.html';
$global_config['rewrite_op_mod'] = '';
$global_config['rewrite_optional'] = 0;
$global_config['session_prefix'] = 'nv4s_m1KeCu';
$global_config['site_keywords'] = 'NukeViet, portal, mysql, php';
$global_config['site_lang'] = 'vi';
$global_config['site_reopening_time'] = 0;
$global_config['site_timezone'] = 'byCountry';
$global_config['spadmin_add_admin'] = 1;
$global_config['str_referer_blocker'] = 0;
$global_config['timestamp'] = 1637305487;
$global_config['two_step_verification'] = 0;
$global_config['upload_alt_require'] = 1;
$global_config['upload_auto_alt'] = 1;
$global_config['upload_checking_mode'] = 'strong';
$global_config['upload_chunk_size'] = 0;
$global_config['useactivate'] = 2;
$global_config['users_special'] = 0;
$global_config['version'] = '4.5.01';
$global_config['check_rewrite_file'] = 1;
$global_config['allow_request_mods'] = ['get','post','cookie','session','request','env','server'];
$global_config['request_default_mode'] = 'request';
$global_config['log_errors_list'] = 32767;
$global_config['send_errors_list'] = 256;
$global_config['error_log_path'] = 'data/logs/error_logs';
$global_config['error_log_filename'] = 'error_log';
$global_config['error_log_fileext'] = 'log';
$global_config['setup_langs'] = ['vi'];
$global_config['allowed_html_tags'] = ['embed','object','param','a','b','blockquote','br','caption','col','colgroup','div','em','h1','h2','h3','h4','h5','h6','hr','i','img','li','p','span','strong','s','sub','sup','table','tbody','td','th','tr','u','ul','ol','iframe','figure','figcaption','video','audio','source','track','code','pre'];
$global_config['engine_allowed'] = ['Google' => ['host_pattern' => 'google.', 'query_param' => 'q'], 'Yahoo' => ['host_pattern' => 'yahoo.', 'query_param' => 'p'], 'MSN' => ['host_pattern' => 'search.msn', 'query_param' => 'q'], 'Localhost' => ['host_pattern' => 'localhost', 'query_param' => 'q']];

$language_array = ['en' => ['name' => 'English', 'language' => 'English'], 'fr' => ['name' => 'Français', 'language' => 'French'], 'vi' => ['name' => 'Tiếng Việt', 'language' => 'Vietnamese']];
$nv_parse_ini_timezone = ['Pacific/Midway' => ['winter_offset' => -39600, 'summer_offset' => -39600], 'Pacific/Pago_Pago' => ['winter_offset' => -39600, 'summer_offset' => -39600], 'Pacific/Niue' => ['winter_offset' => -39600, 'summer_offset' => -39600], 'Pacific/Tahiti' => ['winter_offset' => -36000, 'summer_offset' => -36000], 'Pacific/Rarotonga' => ['winter_offset' => -36000, 'summer_offset' => -36000], 'Pacific/Apia' => ['winter_offset' => -36000, 'summer_offset' => -36000], 'Pacific/Fakaofo' => ['winter_offset' => -36000, 'summer_offset' => -36000], 'Pacific/Marquesas' => ['winter_offset' => -34200, 'summer_offset' => -34200], 'Pacific/Gambier' => ['winter_offset' => -32400, 'summer_offset' => -32400], 'US/Alaska' => ['winter_offset' => -32400, 'summer_offset' => -28800], 'Pacific/Pitcairn' => ['winter_offset' => -28800, 'summer_offset' => -28800], 'US/Pacific' => ['winter_offset' => -28800, 'summer_offset' => -25200], 'US/Arizona' => ['winter_offset' => -25200, 'summer_offset' => -25200], 'US/Mountain' => ['winter_offset' => -25200, 'summer_offset' => -21600], 'America/Belize' => ['winter_offset' => -21600, 'summer_offset' => -21600], 'America/Costa_Rica' => ['winter_offset' => -21600, 'summer_offset' => -21600], 'America/Guatemala' => ['winter_offset' => -21600, 'summer_offset' => -21600], 'America/El_Salvador' => ['winter_offset' => -21600, 'summer_offset' => -21600], 'America/Managua' => ['winter_offset' => -21600, 'summer_offset' => -21600], 'America/Tegucigalpa' => ['winter_offset' => -21600, 'summer_offset' => -21600], 'Pacific/Easter' => ['winter_offset' => -18000, 'summer_offset' => -21600], 'US/Central' => ['winter_offset' => -21600, 'summer_offset' => -18000], 'America/Mexico_City' => ['winter_offset' => -21600, 'summer_offset' => -18000], 'America/Bogota' => ['winter_offset' => -18000, 'summer_offset' => -18000], 'America/Cayman' => ['winter_offset' => -18000, 'summer_offset' => -18000], 'America/Guayaquil' => ['winter_offset' => -18000, 'summer_offset' => -18000], 'America/Jamaica' => ['winter_offset' => -18000, 'summer_offset' => -18000], 'America/Lima' => ['winter_offset' => -18000, 'summer_offset' => -18000], 'America/Nassau' => ['winter_offset' => -18000, 'summer_offset' => -18000], 'America/Port-au-Prince' => ['winter_offset' => -18000, 'summer_offset' => -18000], 'America/Panama' => ['winter_offset' => -18000, 'summer_offset' => -18000], 'America/Havana' => ['winter_offset' => -18000, 'summer_offset' => -14400], 'America/New_York' => ['winter_offset' => -18000, 'summer_offset' => -14400], 'US/Eastern' => ['winter_offset' => -18000, 'summer_offset' => -14400], 'America/Toronto' => ['winter_offset' => -18000, 'summer_offset' => -14400], 'America/Anguilla' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Antigua' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Aruba' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Barbados' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Caracas' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Curacao' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Dominica' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Grenada' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Guadeloupe' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Guyana' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/La_Paz' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Santo_Domingo' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/St_Kitts' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/St_Lucia' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Martinique' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Port_of_Spain' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Puerto_Rico' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/St_Thomas' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/St_Vincent' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Tortola' => ['winter_offset' => -14400, 'summer_offset' => -14400], 'America/Santiago' => ['winter_offset' => -10800, 'summer_offset' => -14400], 'Canada/Atlantic' => ['winter_offset' => -14400, 'summer_offset' => -10800], 'Atlantic/Bermuda' => ['winter_offset' => -14400, 'summer_offset' => -10800], 'America/Montevideo' => ['winter_offset' => -10800, 'summer_offset' => -10800], 'Antarctica/Rothera' => ['winter_offset' => -10800, 'summer_offset' => -10800], 'America/Paramaribo' => ['winter_offset' => -10800, 'summer_offset' => -10800], 'America/Argentina/Buenos_Aires' => ['winter_offset' => -10800, 'summer_offset' => -10800], 'America/Cayenne' => ['winter_offset' => -10800, 'summer_offset' => -10800], 'America/Sao_Paulo' => ['winter_offset' => -7200, 'summer_offset' => -10800], 'America/St_Johns' => ['winter_offset' => -12600, 'summer_offset' => -9000], 'America/Godthab' => ['winter_offset' => -10800, 'summer_offset' => -7200], 'America/Asuncion' => ['winter_offset' => -10800, 'summer_offset' => -7200], 'Atlantic/Stanley' => ['winter_offset' => -10800, 'summer_offset' => -7200], 'America/Noronha' => ['winter_offset' => -7200, 'summer_offset' => -7200], 'Atlantic/South_Georgia' => ['winter_offset' => -7200, 'summer_offset' => -7200], 'Atlantic/Cape_Verde' => ['winter_offset' => -3600, 'summer_offset' => -3600], 'Atlantic/Azores' => ['winter_offset' => -3600, 'summer_offset' => 0], 'Africa/Abidjan' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Accra' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Bamako' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Banjul' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Bissau' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Casablanca' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Conakry' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Dakar' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Freetown' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Lome' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Monrovia' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Nouakchott' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Ouagadougou' => ['winter_offset' => 0, 'summer_offset' => 0], 'Atlantic/Reykjavik' => ['winter_offset' => 0, 'summer_offset' => 0], 'Africa/Sao_Tome' => ['winter_offset' => 0, 'summer_offset' => 0], 'Europe/Lisbon' => ['winter_offset' => 0, 'summer_offset' => 0], 'UTC' => ['winter_offset' => 0, 'summer_offset' => 0], 'Europe/Dublin' => ['winter_offset' => 0, 'summer_offset' => 3600], 'Europe/London' => ['winter_offset' => 0, 'summer_offset' => 3600], 'Africa/Algiers' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Bangui' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Brazzaville' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Douala' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Kinshasa' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Malabo' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Lagos' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Libreville' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Luanda' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Ndjamena' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Niamey' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Porto-Novo' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Tunis' => ['winter_offset' => 3600, 'summer_offset' => 3600], 'Africa/Windhoek' => ['winter_offset' => 7200, 'summer_offset' => 3600], 'Europe/Amsterdam' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Andorra' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Belgrade' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Berlin' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Bratislava' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Brussels' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Budapest' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Bucharest' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Chisinau' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Copenhagen' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Gibraltar' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Istanbul' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Kiev' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Ljubljana' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Luxembourg' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Malta' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Monaco' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Oslo' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Madrid' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Paris' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Prague' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Rome' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/San_Marino' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Sarajevo' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Skopje' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Stockholm' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Vatican' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Tirane' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Vaduz' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Vienna' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Zagreb' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Zurich' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Europe/Warsaw' => ['winter_offset' => 3600, 'summer_offset' => 7200], 'Africa/Blantyre' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Bujumbura' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Cairo' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Gaborone' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Harare' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Johannesburg' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Kigali' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Lusaka' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Maputo' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Maseru' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Mbabane' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Africa/Tripoli' => ['winter_offset' => 7200, 'summer_offset' => 7200], 'Europe/Athens' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Europe/Riga' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Europe/Helsinki' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Europe/Tallinn' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Europe/Sofia' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Asia/Amman' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Asia/Beirut' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Asia/Damascus' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Asia/Gaza' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Asia/Jerusalem' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Asia/Nicosia' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Europe/Vilnius' => ['winter_offset' => 7200, 'summer_offset' => 10800], 'Africa/Addis_Ababa' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Indian/Antananarivo' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Africa/Asmara' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Africa/Dar_es_Salaam' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Africa/Kampala' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Africa/Khartoum' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Africa/Mogadishu' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Africa/Nairobi' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Africa/Djibouti' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Asia/Bahrain' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Asia/Kuwait' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Indian/Comoro' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Asia/Baghdad' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Asia/Aden' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Europe/Moscow' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Asia/Qatar' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Asia/Riyadh' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Indian/Mayotte' => ['winter_offset' => 10800, 'summer_offset' => 10800], 'Europe/Minsk' => ['winter_offset' => 10800, 'summer_offset' => 14400], 'Asia/Dubai' => ['winter_offset' => 14400, 'summer_offset' => 14400], 'Asia/Muscat' => ['winter_offset' => 14400, 'summer_offset' => 14400], 'Asia/Tbilisi' => ['winter_offset' => 14400, 'summer_offset' => 14400], 'Indian/Mahe' => ['winter_offset' => 14400, 'summer_offset' => 14400], 'Indian/Mauritius' => ['winter_offset' => 14400, 'summer_offset' => 14400], 'Indian/Reunion' => ['winter_offset' => 14400, 'summer_offset' => 14400], 'Asia/Yerevan' => ['winter_offset' => 14400, 'summer_offset' => 18000], 'Asia/Tehran' => ['winter_offset' => 12600, 'summer_offset' => 16200], 'Asia/Kabul' => ['winter_offset' => 16200, 'summer_offset' => 16200], 'Asia/Baku' => ['winter_offset' => 16200, 'summer_offset' => 18000], 'Asia/Ashgabat' => ['winter_offset' => 18000, 'summer_offset' => 18000], 'Asia/Dushanbe' => ['winter_offset' => 18000, 'summer_offset' => 18000], 'Asia/Karachi' => ['winter_offset' => 18000, 'summer_offset' => 18000], 'Indian/Kerguelen' => ['winter_offset' => 18000, 'summer_offset' => 18000], 'Indian/Maldives' => ['winter_offset' => 18000, 'summer_offset' => 18000], 'Asia/Samarkand' => ['winter_offset' => 18000, 'summer_offset' => 18000], 'Asia/Calcutta' => ['winter_offset' => 19800, 'summer_offset' => 19800], 'Asia/Katmandu' => ['winter_offset' => 20700, 'summer_offset' => 20700], 'Asia/Yekaterinburg' => ['winter_offset' => 18000, 'summer_offset' => 21600], 'Indian/Chagos' => ['winter_offset' => 21600, 'summer_offset' => 21600], 'Asia/Bishkek' => ['winter_offset' => 21600, 'summer_offset' => 21600], 'Asia/Colombo' => ['winter_offset' => 21600, 'summer_offset' => 21600], 'Asia/Dhaka' => ['winter_offset' => 21600, 'summer_offset' => 21600], 'Asia/Qyzylorda' => ['winter_offset' => 21600, 'summer_offset' => 21600], 'Asia/Thimphu' => ['winter_offset' => 21600, 'summer_offset' => 21600], 'Asia/Rangoon' => ['winter_offset' => 23400, 'summer_offset' => 23400], 'Asia/Almaty' => ['winter_offset' => 21600, 'summer_offset' => 25200], 'Asia/Bangkok' => ['winter_offset' => 25200, 'summer_offset' => 25200], 'Asia/Jakarta' => ['winter_offset' => 25200, 'summer_offset' => 25200], 'Asia/Phnom_Penh' => ['winter_offset' => 25200, 'summer_offset' => 25200], 'Asia/Ho_Chi_Minh' => ['winter_offset' => 25200, 'summer_offset' => 25200], 'Asia/Vientiane' => ['winter_offset' => 25200, 'summer_offset' => 25200], 'Asia/Krasnoyarsk' => ['winter_offset' => 25200, 'summer_offset' => 28800], 'Asia/Brunei' => ['winter_offset' => 28800, 'summer_offset' => 28800], 'Asia/Kuala_Lumpur' => ['winter_offset' => 28800, 'summer_offset' => 28800], 'Asia/Macau' => ['winter_offset' => 28800, 'summer_offset' => 28800], 'Asia/Manila' => ['winter_offset' => 28800, 'summer_offset' => 28800], 'Asia/Hong_Kong' => ['winter_offset' => 28800, 'summer_offset' => 28800], 'Australia/Perth' => ['winter_offset' => 28800, 'summer_offset' => 28800], 'Asia/Shanghai' => ['winter_offset' => 28800, 'summer_offset' => 28800], 'Asia/Singapore' => ['winter_offset' => 28800, 'summer_offset' => 28800], 'Asia/Taipei' => ['winter_offset' => 28800, 'summer_offset' => 28800], 'Asia/Ulaanbaatar' => ['winter_offset' => 28800, 'summer_offset' => 28800], 'Asia/Irkutsk' => ['winter_offset' => 28800, 'summer_offset' => 32400], 'Asia/Seoul' => ['winter_offset' => 32400, 'summer_offset' => 32400], 'Asia/Tokyo' => ['winter_offset' => 32400, 'summer_offset' => 32400], 'Asia/Dili' => ['winter_offset' => 32400, 'summer_offset' => 32400], 'Pacific/Palau' => ['winter_offset' => 32400, 'summer_offset' => 32400], 'Australia/Darwin' => ['winter_offset' => 34200, 'summer_offset' => 34200], 'Australia/Adelaide' => ['winter_offset' => 37800, 'summer_offset' => 34200], 'Asia/Yakutsk' => ['winter_offset' => 32400, 'summer_offset' => 36000], 'Australia/Brisbane' => ['winter_offset' => 36000, 'summer_offset' => 36000], 'Pacific/Guam' => ['winter_offset' => 36000, 'summer_offset' => 36000], 'Pacific/Port_Moresby' => ['winter_offset' => 36000, 'summer_offset' => 36000], 'Pacific/Saipan' => ['winter_offset' => 36000, 'summer_offset' => 36000], 'Australia/Sydney' => ['winter_offset' => 39600, 'summer_offset' => 36000], 'Australia/Lord_Howe' => ['winter_offset' => 39600, 'summer_offset' => 37800], 'Asia/Vladivostok' => ['winter_offset' => 36000, 'summer_offset' => 39600], 'Pacific/Guadalcanal' => ['winter_offset' => 39600, 'summer_offset' => 39600], 'Pacific/Ponape' => ['winter_offset' => 39600, 'summer_offset' => 39600], 'Pacific/Efate' => ['winter_offset' => 39600, 'summer_offset' => 39600], 'Pacific/Noumea' => ['winter_offset' => 39600, 'summer_offset' => 39600], 'Pacific/Norfolk' => ['winter_offset' => 41400, 'summer_offset' => 41400], 'Asia/Magadan' => ['winter_offset' => 39600, 'summer_offset' => 43200], 'Pacific/Fiji' => ['winter_offset' => 43200, 'summer_offset' => 43200], 'Pacific/Tarawa' => ['winter_offset' => 43200, 'summer_offset' => 43200], 'Pacific/Funafuti' => ['winter_offset' => 43200, 'summer_offset' => 43200], 'Pacific/Majuro' => ['winter_offset' => 43200, 'summer_offset' => 43200], 'Pacific/Nauru' => ['winter_offset' => 43200, 'summer_offset' => 43200], 'Pacific/Auckland' => ['winter_offset' => 46800, 'summer_offset' => 43200], 'Pacific/Chatham' => ['winter_offset' => 49500, 'summer_offset' => 45900], 'Pacific/Enderbury' => ['winter_offset' => 46800, 'summer_offset' => 46800], 'Pacific/Tongatapu' => ['winter_offset' => 46800, 'summer_offset' => 46800], 'Pacific/Kiritimati' => ['winter_offset' => 50400, 'summer_offset' => 50400]];

$nv_plugin_area=[1 => [0 => 'qrcode.php'], 3 => [0 => 'cdn_js_css_image.php']];
