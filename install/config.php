<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_MAINFILE')) {
    die();
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
$db_config['collation'] = '';//utf8_general_ci, utf8mb4_unicode_ci, utf8mb4_vietnamese_ci

$array_data = array();
$array_data['lang_multi'] = 0;
$array_data['site_name'] = '';
$array_data['nv_login'] = '';
$array_data['nv_email'] = '';
$array_data['nv_password'] = '';
$array_data['re_password'] = '';
$array_data['question'] = '';
$array_data['answer_question'] = '';
$array_data['socialbutton'] = 1;

$global_config['unofficial_mode'] = 0; // Cảnh báo bản thử nghiệm
$global_config['version'] = '4.3.03'; // NukeViet 4.3
$global_config['site_email'] = '';
$global_config['site_phone'] = '';
$global_config['error_set_logs'] = 1;
$global_config['error_send_email'] = 'support@nukeviet.vn';
$global_config['my_domains'] = '';
$global_config['cookie_prefix'] = '';
$global_config['session_prefix'] = '';
$global_config['cookie_secure'] = 0;
$global_config['cookie_httponly'] = 1;

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

$global_config['proxy_blocker'] = 0;
$global_config['str_referer_blocker'] = 0;

$global_config['lang_multi'] = 1;
$global_config['lang_geo'] = 0;
$global_config['site_lang'] = 'en';
$global_config['engine_allowed'] = array();
$global_config['site_theme'] = 'default';
$global_config['gfx_chk'] = 3;

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

$nv_parse_ini_timezone = array(
    'Pacific/Midway' => array( 'winter_offset' => -39600, 'summer_offset' => -39600 ),
    'Pacific/Pago_Pago' => array( 'winter_offset' => -39600, 'summer_offset' => -39600 ),
    'Pacific/Niue' => array( 'winter_offset' => -39600, 'summer_offset' => -39600 ),
    'Pacific/Tahiti' => array( 'winter_offset' => -36000, 'summer_offset' => -36000 ),
    'Pacific/Rarotonga' => array( 'winter_offset' => -36000, 'summer_offset' => -36000 ),
    'Pacific/Apia' => array( 'winter_offset' => -36000, 'summer_offset' => -36000 ),
    'Pacific/Fakaofo' => array( 'winter_offset' => -36000, 'summer_offset' => -36000 ),
    'Pacific/Marquesas' => array( 'winter_offset' => -34200, 'summer_offset' => -34200 ),
    'Pacific/Gambier' => array( 'winter_offset' => -32400, 'summer_offset' => -32400 ),
    'US/Alaska' => array( 'winter_offset' => -32400, 'summer_offset' => -28800 ),
    'Pacific/Pitcairn' => array( 'winter_offset' => -28800, 'summer_offset' => -28800 ),
    'US/Pacific' => array( 'winter_offset' => -28800, 'summer_offset' => -25200 ),
    'US/Arizona' => array( 'winter_offset' => -25200, 'summer_offset' => -25200 ),
    'US/Mountain' => array( 'winter_offset' => -25200, 'summer_offset' => -21600 ),
    'America/Belize' => array( 'winter_offset' => -21600, 'summer_offset' => -21600 ),
    'America/Costa_Rica' => array( 'winter_offset' => -21600, 'summer_offset' => -21600 ),
    'America/Guatemala' => array( 'winter_offset' => -21600, 'summer_offset' => -21600 ),
    'America/El_Salvador' => array( 'winter_offset' => -21600, 'summer_offset' => -21600 ),
    'America/Managua' => array( 'winter_offset' => -21600, 'summer_offset' => -21600 ),
    'America/Tegucigalpa' => array( 'winter_offset' => -21600, 'summer_offset' => -21600 ),
    'Pacific/Easter' => array( 'winter_offset' => -18000, 'summer_offset' => -21600 ),
    'US/Central' => array( 'winter_offset' => -21600, 'summer_offset' => -18000 ),
    'America/Mexico_City' => array( 'winter_offset' => -21600, 'summer_offset' => -18000 ),
    'America/Bogota' => array( 'winter_offset' => -18000, 'summer_offset' => -18000 ),
    'America/Cayman' => array( 'winter_offset' => -18000, 'summer_offset' => -18000 ),
    'America/Guayaquil' => array( 'winter_offset' => -18000, 'summer_offset' => -18000 ),
    'America/Jamaica' => array( 'winter_offset' => -18000, 'summer_offset' => -18000 ),
    'America/Lima' => array( 'winter_offset' => -18000, 'summer_offset' => -18000 ),
    'America/Nassau' => array( 'winter_offset' => -18000, 'summer_offset' => -18000 ),
    'America/Port-au-Prince' => array( 'winter_offset' => -18000, 'summer_offset' => -18000 ),
    'America/Panama' => array( 'winter_offset' => -18000, 'summer_offset' => -18000 ),
    'America/Havana' => array( 'winter_offset' => -18000, 'summer_offset' => -14400 ),
    'America/New_York' => array( 'winter_offset' => -18000, 'summer_offset' => -14400 ),
    'US/Eastern' => array( 'winter_offset' => -18000, 'summer_offset' => -14400 ),
    'America/Toronto' => array( 'winter_offset' => -18000, 'summer_offset' => -14400 ),
    'America/Anguilla' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Antigua' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Aruba' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Barbados' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Caracas' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Curacao' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Dominica' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Grenada' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Guadeloupe' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Guyana' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/La_Paz' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Santo_Domingo' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/St_Kitts' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/St_Lucia' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Martinique' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Port_of_Spain' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Puerto_Rico' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/St_Thomas' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/St_Vincent' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Tortola' => array( 'winter_offset' => -14400, 'summer_offset' => -14400 ),
    'America/Santiago' => array( 'winter_offset' => -10800, 'summer_offset' => -14400 ),
    'Canada/Atlantic' => array( 'winter_offset' => -14400, 'summer_offset' => -10800 ),
    'Atlantic/Bermuda' => array( 'winter_offset' => -14400, 'summer_offset' => -10800 ),
    'America/Montevideo' => array( 'winter_offset' => -10800, 'summer_offset' => -10800 ),
    'Antarctica/Rothera' => array( 'winter_offset' => -10800, 'summer_offset' => -10800 ),
    'America/Paramaribo' => array( 'winter_offset' => -10800, 'summer_offset' => -10800 ),
    'America/Argentina/Buenos_Aires' => array( 'winter_offset' => -10800, 'summer_offset' => -10800 ),
    'America/Cayenne' => array( 'winter_offset' => -10800, 'summer_offset' => -10800 ),
    'America/Sao_Paulo' => array( 'winter_offset' => -7200, 'summer_offset' => -10800 ),
    'America/St_Johns' => array( 'winter_offset' => -12600, 'summer_offset' => -9000 ),
    'America/Godthab' => array( 'winter_offset' => -10800, 'summer_offset' => -7200 ),
    'America/Asuncion' => array( 'winter_offset' => -10800, 'summer_offset' => -7200 ),
    'Atlantic/Stanley' => array( 'winter_offset' => -10800, 'summer_offset' => -7200 ),
    'America/Noronha' => array( 'winter_offset' => -7200, 'summer_offset' => -7200 ),
    'Atlantic/South_Georgia' => array( 'winter_offset' => -7200, 'summer_offset' => -7200 ),
    'Atlantic/Cape_Verde' => array( 'winter_offset' => -3600, 'summer_offset' => -3600 ),
    'Atlantic/Azores' => array( 'winter_offset' => -3600, 'summer_offset' => 0 ),
    'Africa/Abidjan' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Accra' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Bamako' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Banjul' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Bissau' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Casablanca' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Conakry' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Dakar' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Freetown' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Lome' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Monrovia' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Nouakchott' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Ouagadougou' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Atlantic/Reykjavik' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Africa/Sao_Tome' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Europe/Lisbon' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'UTC' => array( 'winter_offset' => 0, 'summer_offset' => 0 ),
    'Europe/Dublin' => array( 'winter_offset' => 0, 'summer_offset' => 3600 ),
    'Europe/London' => array( 'winter_offset' => 0, 'summer_offset' => 3600 ),
    'Africa/Algiers' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Bangui' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Brazzaville' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Douala' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Kinshasa' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Malabo' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Lagos' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Libreville' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Luanda' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Ndjamena' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Niamey' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Porto-Novo' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Tunis' => array( 'winter_offset' => 3600, 'summer_offset' => 3600 ),
    'Africa/Windhoek' => array( 'winter_offset' => 7200, 'summer_offset' => 3600 ),
    'Europe/Amsterdam' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Andorra' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Belgrade' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Berlin' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Bratislava' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Brussels' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Budapest' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Bucharest' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Chisinau' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Copenhagen' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Gibraltar' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Istanbul' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Kiev' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Ljubljana' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Luxembourg' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Malta' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Monaco' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Oslo' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Madrid' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Paris' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Prague' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Rome' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/San_Marino' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Sarajevo' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Skopje' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Stockholm' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Vatican' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Tirane' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Vaduz' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Vienna' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Zagreb' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Zurich' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Europe/Warsaw' => array( 'winter_offset' => 3600, 'summer_offset' => 7200 ),
    'Africa/Blantyre' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Bujumbura' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Cairo' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Gaborone' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Harare' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Johannesburg' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Kigali' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Lusaka' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Maputo' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Maseru' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Mbabane' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Africa/Tripoli' => array( 'winter_offset' => 7200, 'summer_offset' => 7200 ),
    'Europe/Athens' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Europe/Riga' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Europe/Helsinki' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Europe/Tallinn' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Europe/Sofia' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Asia/Amman' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Asia/Beirut' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Asia/Damascus' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Asia/Gaza' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Asia/Jerusalem' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Asia/Nicosia' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Europe/Vilnius' => array( 'winter_offset' => 7200, 'summer_offset' => 10800 ),
    'Africa/Addis_Ababa' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Indian/Antananarivo' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Africa/Asmara' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Africa/Dar_es_Salaam' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Africa/Kampala' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Africa/Khartoum' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Africa/Mogadishu' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Africa/Nairobi' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Africa/Djibouti' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Asia/Bahrain' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Asia/Kuwait' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Indian/Comoro' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Asia/Baghdad' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Asia/Aden' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Europe/Moscow' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Asia/Qatar' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Asia/Riyadh' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Indian/Mayotte' => array( 'winter_offset' => 10800, 'summer_offset' => 10800 ),
    'Europe/Minsk' => array( 'winter_offset' => 10800, 'summer_offset' => 14400 ),
    'Asia/Dubai' => array( 'winter_offset' => 14400, 'summer_offset' => 14400 ),
    'Asia/Muscat' => array( 'winter_offset' => 14400, 'summer_offset' => 14400 ),
    'Asia/Tbilisi' => array( 'winter_offset' => 14400, 'summer_offset' => 14400 ),
    'Indian/Mahe' => array( 'winter_offset' => 14400, 'summer_offset' => 14400 ),
    'Indian/Mauritius' => array( 'winter_offset' => 14400, 'summer_offset' => 14400 ),
    'Indian/Reunion' => array( 'winter_offset' => 14400, 'summer_offset' => 14400 ),
    'Asia/Yerevan' => array( 'winter_offset' => 14400, 'summer_offset' => 18000 ),
    'Asia/Tehran' => array( 'winter_offset' => 12600, 'summer_offset' => 16200 ),
    'Asia/Kabul' => array( 'winter_offset' => 16200, 'summer_offset' => 16200 ),
    'Asia/Baku' => array( 'winter_offset' => 16200, 'summer_offset' => 18000 ),
    'Asia/Ashgabat' => array( 'winter_offset' => 18000, 'summer_offset' => 18000 ),
    'Asia/Dushanbe' => array( 'winter_offset' => 18000, 'summer_offset' => 18000 ),
    'Asia/Karachi' => array( 'winter_offset' => 18000, 'summer_offset' => 18000 ),
    'Indian/Kerguelen' => array( 'winter_offset' => 18000, 'summer_offset' => 18000 ),
    'Indian/Maldives' => array( 'winter_offset' => 18000, 'summer_offset' => 18000 ),
    'Asia/Samarkand' => array( 'winter_offset' => 18000, 'summer_offset' => 18000 ),
    'Asia/Calcutta' => array( 'winter_offset' => 19800, 'summer_offset' => 19800 ),
    'Asia/Katmandu' => array( 'winter_offset' => 20700, 'summer_offset' => 20700 ),
    'Asia/Yekaterinburg' => array( 'winter_offset' => 18000, 'summer_offset' => 21600 ),
    'Indian/Chagos' => array( 'winter_offset' => 21600, 'summer_offset' => 21600 ),
    'Asia/Bishkek' => array( 'winter_offset' => 21600, 'summer_offset' => 21600 ),
    'Asia/Colombo' => array( 'winter_offset' => 21600, 'summer_offset' => 21600 ),
    'Asia/Dhaka' => array( 'winter_offset' => 21600, 'summer_offset' => 21600 ),
    'Asia/Qyzylorda' => array( 'winter_offset' => 21600, 'summer_offset' => 21600 ),
    'Asia/Thimphu' => array( 'winter_offset' => 21600, 'summer_offset' => 21600 ),
    'Asia/Rangoon' => array( 'winter_offset' => 23400, 'summer_offset' => 23400 ),
    'Asia/Almaty' => array( 'winter_offset' => 21600, 'summer_offset' => 25200 ),
    'Asia/Bangkok' => array( 'winter_offset' => 25200, 'summer_offset' => 25200 ),
    'Asia/Jakarta' => array( 'winter_offset' => 25200, 'summer_offset' => 25200 ),
    'Asia/Phnom_Penh' => array( 'winter_offset' => 25200, 'summer_offset' => 25200 ),
    'Asia/Saigon' => array( 'winter_offset' => 25200, 'summer_offset' => 25200 ),
    'Asia/Vientiane' => array( 'winter_offset' => 25200, 'summer_offset' => 25200 ),
    'Asia/Krasnoyarsk' => array( 'winter_offset' => 25200, 'summer_offset' => 28800 ),
    'Asia/Brunei' => array( 'winter_offset' => 28800, 'summer_offset' => 28800 ),
    'Asia/Kuala_Lumpur' => array( 'winter_offset' => 28800, 'summer_offset' => 28800 ),
    'Asia/Macau' => array( 'winter_offset' => 28800, 'summer_offset' => 28800 ),
    'Asia/Manila' => array( 'winter_offset' => 28800, 'summer_offset' => 28800 ),
    'Asia/Hong_Kong' => array( 'winter_offset' => 28800, 'summer_offset' => 28800 ),
    'Australia/Perth' => array( 'winter_offset' => 28800, 'summer_offset' => 28800 ),
    'Asia/Shanghai' => array( 'winter_offset' => 28800, 'summer_offset' => 28800 ),
    'Asia/Singapore' => array( 'winter_offset' => 28800, 'summer_offset' => 28800 ),
    'Asia/Taipei' => array( 'winter_offset' => 28800, 'summer_offset' => 28800 ),
    'Asia/Ulaanbaatar' => array( 'winter_offset' => 28800, 'summer_offset' => 28800 ),
    'Asia/Irkutsk' => array( 'winter_offset' => 28800, 'summer_offset' => 32400 ),
    'Asia/Seoul' => array( 'winter_offset' => 32400, 'summer_offset' => 32400 ),
    'Asia/Tokyo' => array( 'winter_offset' => 32400, 'summer_offset' => 32400 ),
    'Asia/Dili' => array( 'winter_offset' => 32400, 'summer_offset' => 32400 ),
    'Pacific/Palau' => array( 'winter_offset' => 32400, 'summer_offset' => 32400 ),
    'Australia/Darwin' => array( 'winter_offset' => 34200, 'summer_offset' => 34200 ),
    'Australia/Adelaide' => array( 'winter_offset' => 37800, 'summer_offset' => 34200 ),
    'Asia/Yakutsk' => array( 'winter_offset' => 32400, 'summer_offset' => 36000 ),
    'Australia/Brisbane' => array( 'winter_offset' => 36000, 'summer_offset' => 36000 ),
    'Pacific/Guam' => array( 'winter_offset' => 36000, 'summer_offset' => 36000 ),
    'Pacific/Port_Moresby' => array( 'winter_offset' => 36000, 'summer_offset' => 36000 ),
    'Pacific/Saipan' => array( 'winter_offset' => 36000, 'summer_offset' => 36000 ),
    'Australia/Sydney' => array( 'winter_offset' => 39600, 'summer_offset' => 36000 ),
    'Australia/Lord_Howe' => array( 'winter_offset' => 39600, 'summer_offset' => 37800 ),
    'Asia/Vladivostok' => array( 'winter_offset' => 36000, 'summer_offset' => 39600 ),
    'Pacific/Guadalcanal' => array( 'winter_offset' => 39600, 'summer_offset' => 39600 ),
    'Pacific/Ponape' => array( 'winter_offset' => 39600, 'summer_offset' => 39600 ),
    'Pacific/Efate' => array( 'winter_offset' => 39600, 'summer_offset' => 39600 ),
    'Pacific/Noumea' => array( 'winter_offset' => 39600, 'summer_offset' => 39600 ),
    'Pacific/Norfolk' => array( 'winter_offset' => 41400, 'summer_offset' => 41400 ),
    'Asia/Magadan' => array( 'winter_offset' => 39600, 'summer_offset' => 43200 ),
    'Pacific/Fiji' => array( 'winter_offset' => 43200, 'summer_offset' => 43200 ),
    'Pacific/Tarawa' => array( 'winter_offset' => 43200, 'summer_offset' => 43200 ),
    'Pacific/Funafuti' => array( 'winter_offset' => 43200, 'summer_offset' => 43200 ),
    'Pacific/Majuro' => array( 'winter_offset' => 43200, 'summer_offset' => 43200 ),
    'Pacific/Nauru' => array( 'winter_offset' => 43200, 'summer_offset' => 43200 ),
    'Pacific/Auckland' => array( 'winter_offset' => 46800, 'summer_offset' => 43200 ),
    'Pacific/Chatham' => array( 'winter_offset' => 49500, 'summer_offset' => 45900 ),
    'Pacific/Enderbury' => array( 'winter_offset' => 46800, 'summer_offset' => 46800 ),
    'Pacific/Tongatapu' => array( 'winter_offset' => 46800, 'summer_offset' => 46800 ),
    'Pacific/Kiritimati' => array( 'winter_offset' => 50400, 'summer_offset' => 50400 )
);
