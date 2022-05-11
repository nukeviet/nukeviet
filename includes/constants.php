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

//Neu thay doi bat ky gia tri trong file nay ban can vao admin phan cau hinh he thong va luu lai
//Luu y: Tat ca ten thu muc va file can viet thuong, chi bao gom chu cai, so va dau -, ten file co the bao gom dau _

//Ten file config
define('NV_CONFIG_FILENAME', 'config.php');

//Ten thu muc admin
define('NV_ADMINDIR', 'admin');

//Ten thu muc luu data
define('NV_DATADIR', 'data/config');

//Thu muc chua cac file logs
define('NV_LOGS_DIR', 'data/logs');

//Thu muc chua cac file tam thoi
define('NV_TEMP_DIR', 'data/tmp');

//Ten thu muc cache
define('NV_CACHEDIR', 'data/cache');

//Thu muc chua IP
define('NV_IP_DIR', 'data/ip');

//Thu muc chua certificates SMIME
define('NV_CERTS_DIR', 'data/certs');

//Thu muc assets
define('NV_ASSETS_DIR', 'assets');

//Ten thu muc editors
define('NV_EDITORSDIR', NV_ASSETS_DIR . '/editors');

//Thu muc uploads
define('NV_UPLOADS_DIR', 'uploads');

//Thu muc uploads banner
define('NV_BANNER_DIR', 'banners');

//TDT file tam thoi, toi da 3 ky tu
define('NV_TEMPNAM_PREFIX', 'nv_');

//Ten file error_log
define('NV_ERRORLOGS_FILENAME', 'error_log');

//duoi cua file log
define('NV_LOGS_EXT', 'log');

//Ten thay the cho bien $name
define('NV_NAME_VARIABLE', 'nv');

//Ten thay the cho bien $op
define('NV_OP_VARIABLE', 'op');

//Ten thay the cho bien ngon ngu
define('NV_LANG_VARIABLE', 'language');

//Do nen trang khi bat che do nen
define('ZLIB_OUTPUT_COMPRESSION_LEVEL', 6);

//Thoi gian de tinh online, tinh bang giay, 300 = 5 phut
define('NV_ONLINE_UPD_TIME', 300);

//Thoi gian luu tru referer, 2592000 = 30 ngay
define('NV_REF_LIVE_TIME', 2592000);

//So ky tu toi thieu cua input tim kiem
define('NV_MIN_SEARCH_LENGTH', 3);

//So ky tu toi da cua input tim kiem
define('NV_MAX_SEARCH_LENGTH', 60);

//ky tu phan cach trong title
define('NV_TITLEBAR_DEFIS', ' - ');

//Cac thiet lap trong siteword
define('NV_SITEWORDS_MIN_WORD_LENGTH', 4);
define('NV_SITEWORDS_MIN_WORD_OCCUR', 5);
define('NV_SITEWORDS_MIN_2WORDS_LENGTH', 2);
define('NV_SITEWORDS_MIN_2WORDS_PHRASE_OCCUR', 2);
define('NV_SITEWORDS_MIN_3WORDS_LENGTH', 0);
define('NV_SITEWORDS_MIN_3WORDS_PHRASE_OCCUR', 0);
define('NV_SITEWORDS_MAX_STRLEN', 300);

// Thiet lap cho get,post,cookie,session,request,env,server
define('NV_ALLOW_REQUEST_MODS', 'get,post,cookie,session,request,env,server');
define('NV_REQUEST_DEFAULT_MODE', 'request');

//Hien thi, ghi loi
/*
 * E_ALL - All errors and warnings (doesn't include E_STRICT) E_ERROR - fatal run-time errors E_WARNING - run-time warnings (non-fatal errors) E_PARSE - compile-time parse errors E_NOTICE - run-time notices (these are warnings which often result from a bug in your code, but it's possible that it was intentional (e.g., using an uninitialized variable and relying on the fact it's automatically initialized to an empty string) E_STRICT			- run-time notices, enable to have PHP suggest changes to your code which will ensure the best interoperability and forward compatibility of your code E_CORE_ERROR - fatal errors that occur during PHP's initial startup E_CORE_WARNING - warnings (non-fatal errors) that occur during PHP's initial startup E_COMPILE_ERROR - fatal compile-time errors E_COMPILE_WARNING - compile-time warnings (non-fatal errors) E_USER_ERROR - user-generated error message E_USER_WARNING - user-generated warning message E_USER_NOTICE - user-generated notice message Examples: -
 * Show all errors, except for notices and coding standards warnings error_reporting = E_ALL & ~E_NOTICE - Show all errors, except for notices error_reporting = E_ALL & ~E_NOTICE | E_STRICT - Show only errors error_reporting = E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR
 */
define('NV_DISPLAY_ERRORS_LIST', E_ALL);
//Danh sach cac loi se hien thi
//define( 'NV_DISPLAY_ERRORS_LIST', 0); //tat thong bao loi
define('NV_LOG_ERRORS_LIST', E_ALL | E_STRICT);
//Danh sach cac loi se ghi log
define('NV_SEND_ERRORS_LIST', E_USER_ERROR);
//Danh sach cac loi se gui den email

//Phan dau cua trang
define('NV_FILEHEAD', "/**\n * NukeViet Content Management System\n * @version 4.x\n * @author VINADES.,JSC <contact@vinades.vn>\n * @copyright (C) 2009-" . gmdate('Y') . " VINADES.,JSC. All rights reserved\n * @license GNU/GPL version 2 or any later version\n * @see https://github.com/nukeviet The NukeViet CMS GitHub project\n */");

// Vui long khong thay doi gia tri nay
define('NUKEVIET_STORE_APIURL', 'https://api.nukeviet.vn/store/');

//Browser Names
define('BROWSER_OPERA', 'Opera');
define('BROWSER_OPERAMINI', 'Opera Mini');
define('BROWSER_WEBTV', 'WebTV');
define('BROWSER_EXPLORER', 'Internet Explorer');
define('BROWSER_EDGE', 'Microsoft Edge');
define('BROWSER_POCKET', 'Pocket Internet Explorer');
define('BROWSER_KONQUEROR', 'Konqueror');
define('BROWSER_ICAB', 'iCab');
define('BROWSER_OMNIWEB', 'OmniWeb');
define('BROWSER_FIREBIRD', 'Firebird');
define('BROWSER_FIREFOX', 'Firefox');
define('BROWSER_BRAVE', 'Brave');
define('BROWSER_PALEMOON', 'Palemoon');
define('BROWSER_ICEWEASEL', 'Iceweasel');
define('BROWSER_SHIRETOKO', 'Shiretoko');
define('BROWSER_MOZILLA', 'Mozilla');
define('BROWSER_AMAYA', 'Amaya');
define('BROWSER_LYNX', 'Lynx');
define('BROWSER_SAFARI', 'Safari');
define('BROWSER_IPHONE', 'iPhone');
define('BROWSER_IPOD', 'iPod');
define('BROWSER_IPAD', 'iPad');
define('BROWSER_CHROME', 'Chrome');
define('BROWSER_COCCOC', 'Coc Coc');
define('BROWSER_COCCOCBOT', 'CoccocBot');
define('BROWSER_ANDROID', 'Android');
define('BROWSER_GOOGLEBOT', 'GoogleBot');
define('BROWSER_CURL', 'cURL');
define('BROWSER_WGET', 'Wget');
define('BROWSER_UCBROWSER', 'UCBrowser');
define('BROWSER_YANDEXBOT', 'YandexBot');
define('BROWSER_YANDEXIMAGERESIZER_BOT', 'YandexImageResizer');
define('BROWSER_YANDEXIMAGES_BOT', 'YandexImages');
define('BROWSER_YANDEXVIDEO_BOT', 'YandexVideo');
define('BROWSER_YANDEXMEDIA_BOT', 'YandexMedia');
define('BROWSER_YANDEXBLOGS_BOT', 'YandexBlogs');
define('BROWSER_YANDEXFAVICONS_BOT', 'YandexFavicons');
define('BROWSER_YANDEXWEBMASTER_BOT', 'YandexWebmaster');
define('BROWSER_YANDEXDIRECT_BOT', 'YandexDirect');
define('BROWSER_YANDEXMETRIKA_BOT', 'YandexMetrika');
define('BROWSER_YANDEXNEWS_BOT', 'YandexNews');
define('BROWSER_YANDEXCATALOG_BOT', 'YandexCatalog');
define('BROWSER_YAHOOSLURP', 'Yahoo! Slurp');
define('BROWSER_W3CVALIDATOR', 'W3C Validator');
define('BROWSER_BLACKBERRY', 'BlackBerry');
define('BROWSER_ICECAT', 'IceCat');
define('BROWSER_NOKIAS60', 'Nokia S60 OSS Browser');
define('BROWSER_NOKIA', 'Nokia Browser');
define('BROWSER_MSN', 'MSN Browser');
define('BROWSER_MSNBOT', 'MSN Bot');
define('BROWSER_BINGBOT', 'Bing Bot');
define('BROWSER_VIVALDI', 'Vivaldi');
define('BROWSER_YANDEX', 'Yandex');
define('BROWSER_PLAYSTATION', 'PlayStation');
define('BROWSER_SAMSUNG', 'SamsungBrowser');
define('BROWSER_SILK', 'Silk');
define('BROWSER_I_FRAME', 'Iframely');
define('BROWSER_COCOA', 'CocoaRestClient');

//Platform Names
define('PLATFORM_WIN', 'Windows');
define('PLATFORM_WIN10', 'Windows 10');
define('PLATFORM_WIN8', 'Windows 8');
define('PLATFORM_WIN7', 'Windows 7');
define('PLATFORM_WIN2003', 'Windows 2003');
define('PLATFORM_WINVISTA', 'Windows Vista');
define('PLATFORM_WINCE', 'Windows CE');
define('PLATFORM_WINXP', 'Windows XP');
define('PLATFORM_WIN2000', 'Windows 2000');
define('PLATFORM_APPLE', 'Apple');
define('PLATFORM_LINUX', 'Linux');
define('PLATFORM_OS2', 'OS/2');
define('PLATFORM_BEOS', 'BeOS');
define('PLATFORM_IPHONE', 'iPhone');
define('PLATFORM_IPOD', 'iPod');
define('PLATFORM_IPAD', 'iPad');
define('PLATFORM_BLACKBERRY', 'BlackBerry');
define('PLATFORM_NOKIA', 'Nokia');
define('PLATFORM_FREEBSD', 'FreeBSD');
define('PLATFORM_OPENBSD', 'OpenBSD');
define('PLATFORM_NETBSD', 'NetBSD');
define('PLATFORM_SUNOS', 'SunOS');
define('PLATFORM_OPENSOLARIS', 'OpenSolaris');
define('PLATFORM_ANDROID', 'Android');
define('PLATFORM_IRIX', 'Irix');
define('PLATFORM_PALM', 'Palm');
define('PLATFORM_PLAYSTATION', 'Sony PlayStation');
define('PLATFORM_ROKU', 'Roku');
define('PLATFORM_APPLE_TV', 'Apple TV');
define('PLATFORM_TERMINAL', 'Terminal');
define('PLATFORM_FIRE_OS', 'Fire OS');
define('PLATFORM_SMART_TV', 'SMART-TV');
define('PLATFORM_CHROME_OS', 'Chrome OS');
define('PLATFORM_JAVA_ANDROID', 'Java/Android');
define('PLATFORM_POSTMAN', 'Postman');
define('PLATFORM_I_FRAME', 'Iframely');

// Memcached
define('NV_MEMCACHED_HOST', '127.0.0.1');
define('NV_MEMCACHED_PORT', '11211');

// Redis
define('NV_REDIS_HOST', '127.0.0.1');
define('NV_REDIS_PORT', 6379);
define('NV_REDIS_PASSWORD', ''); // Warning: password default is empty, but if using the password is sent in plain-text over the network
define('NV_REDIS_DBINDEX', 0);
define('NV_REDIS_TIMEOUT', 2.5);

$global_config['site_charset'] = 'utf-8';
$global_config['check_module'] = '/^[a-zA-Z0-9\-]+$/';
$global_config['check_op'] = '/^[a-zA-Z0-9\-]+$/';
$global_config['check_op_file'] = '/^([a-zA-Z0-9\-\_]+)\.php$/';
$global_config['check_block_module'] = '/^(global|module)\.([a-zA-Z0-9\-\_]+)\.php$/';
$global_config['check_block_theme'] = '/^([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\_]+)\.php$/';
$global_config['check_theme'] = '/^(?!admin\_|mobile\_)([a-zA-Z0-9\-\_]+)$/';
$global_config['check_theme_mobile'] = '/^(mobile)\_[a-zA-Z0-9\-\_]+$/';
$global_config['check_theme_admin'] = '/^(admin)\_[a-zA-Z0-9\-\_]+$/';

$global_config['check_email'] = '/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/';
$global_config['check_cron'] = '/^(cron)\_[a-zA-Z0-9\_]+$/';
$global_config['check_op_layout'] = '/^layout\.([a-zA-Z0-9\-\_]+)\.tpl$/';
$global_config['check_version'] = '/^([0-9]{1})\.([0-9]{1})\.([0-9]{2})$/';

$global_config['others_headers'] = ['X-Content-Type-Options' => 'nosniff', 'X-XSS-Protection' => '1; mode=block', 'Strict-Transport-Security' => 'max-age=31536000; preload'];

// Meta Property
$meta_property = [
    'og:title' => '',
    'og:type' => '',
    'og:description' => '',
    'og:site_name' => '',
    'og:image' => '',
    'og:image:url' => '',
    'og:image:type' => '',
    'og:image:width' => '',
    'og:image:height' => '',
    'og:url' => ''
];

$nv_parse_ini_timezone = [
    'Pacific/Midway' => ['winter_offset' => -39600, 'summer_offset' => -39600],
    'Pacific/Pago_Pago' => ['winter_offset' => -39600, 'summer_offset' => -39600],
    'Pacific/Niue' => ['winter_offset' => -39600, 'summer_offset' => -39600],
    'Pacific/Tahiti' => ['winter_offset' => -36000, 'summer_offset' => -36000],
    'Pacific/Rarotonga' => ['winter_offset' => -36000, 'summer_offset' => -36000],
    'Pacific/Apia' => ['winter_offset' => -36000, 'summer_offset' => -36000],
    'Pacific/Fakaofo' => ['winter_offset' => -36000, 'summer_offset' => -36000],
    'Pacific/Marquesas' => ['winter_offset' => -34200, 'summer_offset' => -34200],
    'Pacific/Gambier' => ['winter_offset' => -32400, 'summer_offset' => -32400],
    'US/Alaska' => ['winter_offset' => -32400, 'summer_offset' => -28800],
    'Pacific/Pitcairn' => ['winter_offset' => -28800, 'summer_offset' => -28800],
    'US/Pacific' => ['winter_offset' => -28800, 'summer_offset' => -25200],
    'US/Arizona' => ['winter_offset' => -25200, 'summer_offset' => -25200],
    'US/Mountain' => ['winter_offset' => -25200, 'summer_offset' => -21600],
    'America/Belize' => ['winter_offset' => -21600, 'summer_offset' => -21600],
    'America/Costa_Rica' => ['winter_offset' => -21600, 'summer_offset' => -21600],
    'America/Guatemala' => ['winter_offset' => -21600, 'summer_offset' => -21600],
    'America/El_Salvador' => ['winter_offset' => -21600, 'summer_offset' => -21600],
    'America/Managua' => ['winter_offset' => -21600, 'summer_offset' => -21600],
    'America/Tegucigalpa' => ['winter_offset' => -21600, 'summer_offset' => -21600],
    'Pacific/Easter' => ['winter_offset' => -18000, 'summer_offset' => -21600],
    'US/Central' => ['winter_offset' => -21600, 'summer_offset' => -18000],
    'America/Mexico_City' => ['winter_offset' => -21600, 'summer_offset' => -18000],
    'America/Bogota' => ['winter_offset' => -18000, 'summer_offset' => -18000],
    'America/Cayman' => ['winter_offset' => -18000, 'summer_offset' => -18000],
    'America/Guayaquil' => ['winter_offset' => -18000, 'summer_offset' => -18000],
    'America/Jamaica' => ['winter_offset' => -18000, 'summer_offset' => -18000],
    'America/Lima' => ['winter_offset' => -18000, 'summer_offset' => -18000],
    'America/Nassau' => ['winter_offset' => -18000, 'summer_offset' => -18000],
    'America/Port-au-Prince' => ['winter_offset' => -18000, 'summer_offset' => -18000],
    'America/Panama' => ['winter_offset' => -18000, 'summer_offset' => -18000],
    'America/Havana' => ['winter_offset' => -18000, 'summer_offset' => -14400],
    'America/New_York' => ['winter_offset' => -18000, 'summer_offset' => -14400],
    'US/Eastern' => ['winter_offset' => -18000, 'summer_offset' => -14400],
    'America/Toronto' => ['winter_offset' => -18000, 'summer_offset' => -14400],
    'America/Anguilla' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Antigua' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Aruba' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Barbados' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Caracas' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Curacao' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Dominica' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Grenada' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Guadeloupe' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Guyana' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/La_Paz' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Santo_Domingo' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/St_Kitts' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/St_Lucia' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Martinique' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Port_of_Spain' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Puerto_Rico' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/St_Thomas' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/St_Vincent' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Tortola' => ['winter_offset' => -14400, 'summer_offset' => -14400],
    'America/Santiago' => ['winter_offset' => -10800, 'summer_offset' => -14400],
    'Canada/Atlantic' => ['winter_offset' => -14400, 'summer_offset' => -10800],
    'Atlantic/Bermuda' => ['winter_offset' => -14400, 'summer_offset' => -10800],
    'America/Montevideo' => ['winter_offset' => -10800, 'summer_offset' => -10800],
    'Antarctica/Rothera' => ['winter_offset' => -10800, 'summer_offset' => -10800],
    'America/Paramaribo' => ['winter_offset' => -10800, 'summer_offset' => -10800],
    'America/Argentina/Buenos_Aires' => ['winter_offset' => -10800, 'summer_offset' => -10800],
    'America/Cayenne' => ['winter_offset' => -10800, 'summer_offset' => -10800],
    'America/Sao_Paulo' => ['winter_offset' => -7200, 'summer_offset' => -10800],
    'America/St_Johns' => ['winter_offset' => -12600, 'summer_offset' => -9000],
    'America/Godthab' => ['winter_offset' => -10800, 'summer_offset' => -7200],
    'America/Asuncion' => ['winter_offset' => -10800, 'summer_offset' => -7200],
    'Atlantic/Stanley' => ['winter_offset' => -10800, 'summer_offset' => -7200],
    'America/Noronha' => ['winter_offset' => -7200, 'summer_offset' => -7200],
    'Atlantic/South_Georgia' => ['winter_offset' => -7200, 'summer_offset' => -7200],
    'Atlantic/Cape_Verde' => ['winter_offset' => -3600, 'summer_offset' => -3600],
    'Atlantic/Azores' => ['winter_offset' => -3600, 'summer_offset' => 0],
    'Africa/Abidjan' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Accra' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Bamako' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Banjul' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Bissau' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Casablanca' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Conakry' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Dakar' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Freetown' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Lome' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Monrovia' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Nouakchott' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Ouagadougou' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Atlantic/Reykjavik' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Africa/Sao_Tome' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Europe/Lisbon' => ['winter_offset' => 0, 'summer_offset' => 0],
    'UTC' => ['winter_offset' => 0, 'summer_offset' => 0],
    'Europe/Dublin' => ['winter_offset' => 0, 'summer_offset' => 3600],
    'Europe/London' => ['winter_offset' => 0, 'summer_offset' => 3600],
    'Africa/Algiers' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Bangui' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Brazzaville' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Douala' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Kinshasa' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Malabo' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Lagos' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Libreville' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Luanda' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Ndjamena' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Niamey' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Porto-Novo' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Tunis' => ['winter_offset' => 3600, 'summer_offset' => 3600],
    'Africa/Windhoek' => ['winter_offset' => 7200, 'summer_offset' => 3600],
    'Europe/Amsterdam' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Andorra' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Belgrade' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Berlin' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Bratislava' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Brussels' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Budapest' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Bucharest' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Chisinau' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Copenhagen' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Gibraltar' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Istanbul' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Kiev' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Ljubljana' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Luxembourg' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Malta' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Monaco' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Oslo' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Madrid' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Paris' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Prague' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Rome' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/San_Marino' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Sarajevo' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Skopje' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Stockholm' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Vatican' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Tirane' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Vaduz' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Vienna' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Zagreb' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Zurich' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Europe/Warsaw' => ['winter_offset' => 3600, 'summer_offset' => 7200],
    'Africa/Blantyre' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Bujumbura' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Cairo' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Gaborone' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Harare' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Johannesburg' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Kigali' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Lusaka' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Maputo' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Maseru' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Mbabane' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Africa/Tripoli' => ['winter_offset' => 7200, 'summer_offset' => 7200],
    'Europe/Athens' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Europe/Riga' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Europe/Helsinki' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Europe/Tallinn' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Europe/Sofia' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Asia/Amman' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Asia/Beirut' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Asia/Damascus' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Asia/Gaza' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Asia/Jerusalem' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Asia/Nicosia' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Europe/Vilnius' => ['winter_offset' => 7200, 'summer_offset' => 10800],
    'Africa/Addis_Ababa' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Indian/Antananarivo' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Africa/Asmara' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Africa/Dar_es_Salaam' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Africa/Kampala' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Africa/Khartoum' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Africa/Mogadishu' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Africa/Nairobi' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Africa/Djibouti' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Asia/Bahrain' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Asia/Kuwait' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Indian/Comoro' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Asia/Baghdad' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Asia/Aden' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Europe/Moscow' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Asia/Qatar' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Asia/Riyadh' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Indian/Mayotte' => ['winter_offset' => 10800, 'summer_offset' => 10800],
    'Europe/Minsk' => ['winter_offset' => 10800, 'summer_offset' => 14400],
    'Asia/Dubai' => ['winter_offset' => 14400, 'summer_offset' => 14400],
    'Asia/Muscat' => ['winter_offset' => 14400, 'summer_offset' => 14400],
    'Asia/Tbilisi' => ['winter_offset' => 14400, 'summer_offset' => 14400],
    'Indian/Mahe' => ['winter_offset' => 14400, 'summer_offset' => 14400],
    'Indian/Mauritius' => ['winter_offset' => 14400, 'summer_offset' => 14400],
    'Indian/Reunion' => ['winter_offset' => 14400, 'summer_offset' => 14400],
    'Asia/Yerevan' => ['winter_offset' => 14400, 'summer_offset' => 18000],
    'Asia/Tehran' => ['winter_offset' => 12600, 'summer_offset' => 16200],
    'Asia/Kabul' => ['winter_offset' => 16200, 'summer_offset' => 16200],
    'Asia/Baku' => ['winter_offset' => 16200, 'summer_offset' => 18000],
    'Asia/Ashgabat' => ['winter_offset' => 18000, 'summer_offset' => 18000],
    'Asia/Dushanbe' => ['winter_offset' => 18000, 'summer_offset' => 18000],
    'Asia/Karachi' => ['winter_offset' => 18000, 'summer_offset' => 18000],
    'Indian/Kerguelen' => ['winter_offset' => 18000, 'summer_offset' => 18000],
    'Indian/Maldives' => ['winter_offset' => 18000, 'summer_offset' => 18000],
    'Asia/Samarkand' => ['winter_offset' => 18000, 'summer_offset' => 18000],
    'Asia/Calcutta' => ['winter_offset' => 19800, 'summer_offset' => 19800],
    'Asia/Katmandu' => ['winter_offset' => 20700, 'summer_offset' => 20700],
    'Asia/Yekaterinburg' => ['winter_offset' => 18000, 'summer_offset' => 21600],
    'Indian/Chagos' => ['winter_offset' => 21600, 'summer_offset' => 21600],
    'Asia/Bishkek' => ['winter_offset' => 21600, 'summer_offset' => 21600],
    'Asia/Colombo' => ['winter_offset' => 21600, 'summer_offset' => 21600],
    'Asia/Dhaka' => ['winter_offset' => 21600, 'summer_offset' => 21600],
    'Asia/Qyzylorda' => ['winter_offset' => 21600, 'summer_offset' => 21600],
    'Asia/Thimphu' => ['winter_offset' => 21600, 'summer_offset' => 21600],
    'Asia/Rangoon' => ['winter_offset' => 23400, 'summer_offset' => 23400],
    'Asia/Almaty' => ['winter_offset' => 21600, 'summer_offset' => 25200],
    'Asia/Bangkok' => ['winter_offset' => 25200, 'summer_offset' => 25200],
    'Asia/Jakarta' => ['winter_offset' => 25200, 'summer_offset' => 25200],
    'Asia/Phnom_Penh' => ['winter_offset' => 25200, 'summer_offset' => 25200],
    'Asia/Ho_Chi_Minh' => ['winter_offset' => 25200, 'summer_offset' => 25200],
    'Asia/Vientiane' => ['winter_offset' => 25200, 'summer_offset' => 25200],
    'Asia/Krasnoyarsk' => ['winter_offset' => 25200, 'summer_offset' => 28800],
    'Asia/Brunei' => ['winter_offset' => 28800, 'summer_offset' => 28800],
    'Asia/Kuala_Lumpur' => ['winter_offset' => 28800, 'summer_offset' => 28800],
    'Asia/Macau' => ['winter_offset' => 28800, 'summer_offset' => 28800],
    'Asia/Manila' => ['winter_offset' => 28800, 'summer_offset' => 28800],
    'Asia/Hong_Kong' => ['winter_offset' => 28800, 'summer_offset' => 28800],
    'Australia/Perth' => ['winter_offset' => 28800, 'summer_offset' => 28800],
    'Asia/Shanghai' => ['winter_offset' => 28800, 'summer_offset' => 28800],
    'Asia/Singapore' => ['winter_offset' => 28800, 'summer_offset' => 28800],
    'Asia/Taipei' => ['winter_offset' => 28800, 'summer_offset' => 28800],
    'Asia/Ulaanbaatar' => ['winter_offset' => 28800, 'summer_offset' => 28800],
    'Asia/Irkutsk' => ['winter_offset' => 28800, 'summer_offset' => 32400],
    'Asia/Seoul' => ['winter_offset' => 32400, 'summer_offset' => 32400],
    'Asia/Tokyo' => ['winter_offset' => 32400, 'summer_offset' => 32400],
    'Asia/Dili' => ['winter_offset' => 32400, 'summer_offset' => 32400],
    'Pacific/Palau' => ['winter_offset' => 32400, 'summer_offset' => 32400],
    'Australia/Darwin' => ['winter_offset' => 34200, 'summer_offset' => 34200],
    'Australia/Adelaide' => ['winter_offset' => 37800, 'summer_offset' => 34200],
    'Asia/Yakutsk' => ['winter_offset' => 32400, 'summer_offset' => 36000],
    'Australia/Brisbane' => ['winter_offset' => 36000, 'summer_offset' => 36000],
    'Pacific/Guam' => ['winter_offset' => 36000, 'summer_offset' => 36000],
    'Pacific/Port_Moresby' => ['winter_offset' => 36000, 'summer_offset' => 36000],
    'Pacific/Saipan' => ['winter_offset' => 36000, 'summer_offset' => 36000],
    'Australia/Sydney' => ['winter_offset' => 39600, 'summer_offset' => 36000],
    'Australia/Lord_Howe' => ['winter_offset' => 39600, 'summer_offset' => 37800],
    'Asia/Vladivostok' => ['winter_offset' => 36000, 'summer_offset' => 39600],
    'Pacific/Guadalcanal' => ['winter_offset' => 39600, 'summer_offset' => 39600],
    'Pacific/Ponape' => ['winter_offset' => 39600, 'summer_offset' => 39600],
    'Pacific/Efate' => ['winter_offset' => 39600, 'summer_offset' => 39600],
    'Pacific/Noumea' => ['winter_offset' => 39600, 'summer_offset' => 39600],
    'Pacific/Norfolk' => ['winter_offset' => 41400, 'summer_offset' => 41400],
    'Asia/Magadan' => ['winter_offset' => 39600, 'summer_offset' => 43200],
    'Pacific/Fiji' => ['winter_offset' => 43200, 'summer_offset' => 43200],
    'Pacific/Tarawa' => ['winter_offset' => 43200, 'summer_offset' => 43200],
    'Pacific/Funafuti' => ['winter_offset' => 43200, 'summer_offset' => 43200],
    'Pacific/Majuro' => ['winter_offset' => 43200, 'summer_offset' => 43200],
    'Pacific/Nauru' => ['winter_offset' => 43200, 'summer_offset' => 43200],
    'Pacific/Auckland' => ['winter_offset' => 46800, 'summer_offset' => 43200],
    'Pacific/Chatham' => ['winter_offset' => 49500, 'summer_offset' => 45900],
    'Pacific/Enderbury' => ['winter_offset' => 46800, 'summer_offset' => 46800],
    'Pacific/Tongatapu' => ['winter_offset' => 46800, 'summer_offset' => 46800],
    'Pacific/Kiritimati' => ['winter_offset' => 50400, 'summer_offset' => 50400]
];
