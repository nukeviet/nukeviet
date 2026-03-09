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
    exit('Stop!!!');
}

/*
 * Nếu thay đổi bất kỳ giá trị nào trong file này bạn cần vào admin phần cấu hình hệ thống và lưu lại.
 * Lưu ý:
 * - Tất cả tên thư mục và file cần viết thường, chỉ bao gồm chữ cái, số và dấu -, tên file có thể bao gồm dấu _.
 * - Tất cả các giá trị không được chứa kí tự '
 * - Không chỉnh sửa hoặc thay đổi bất kỳ dòng nào bắt đầu bằng // trong tệp này
 * Nếu không tuân thủ các quy tắc trên, hệ thống của bạn có thể bị lỗi khi thực hiện cập nhật
 */

// Tên tệp config
// begin: NV_CONFIG_FILENAME
define('NV_CONFIG_FILENAME', 'config.php');
// end

// Thư mục quản trị
// begin: NV_ADMINDIR
define('NV_ADMINDIR', 'admin');
// end

// Tên thư mục lưu data
// begin: NV_DATADIR
define('NV_DATADIR', 'data/config');
// end

// Thư mục chứa các file logs
// begin: NV_LOGS_DIR
define('NV_LOGS_DIR', 'data/logs');
// end

// Thư mục chứa các file tạm thời
// begin: NV_TEMP_DIR
define('NV_TEMP_DIR', 'data/tmp');
// end

// Tên thư mục cache
// begin: NV_CACHEDIR
define('NV_CACHEDIR', 'data/cache');
// end

// Thư mục chứa IP
// begin: NV_IP_DIR
define('NV_IP_DIR', 'data/ip');
// end

// Thư mục chứa certificates SMIME
// begin: NV_CERTS_DIR
define('NV_CERTS_DIR', 'data/certs');
// end

// Thư mục assets
// begin: NV_ASSETS_DIR
define('NV_ASSETS_DIR', 'assets');
// end

// Tên thư mục editors
define('NV_EDITORSDIR', NV_ASSETS_DIR . '/editors');

// Thư mục uploads
// begin: NV_UPLOADS_DIR
define('NV_UPLOADS_DIR', 'uploads');
// end

// Thư mục uploads banner
// begin: NV_BANNER_DIR
define('NV_BANNER_DIR', 'banners');
// end

// TDT file tạm thời, tối đa 3 ký tự
// begin: NV_TEMPNAM_PREFIX
define('NV_TEMPNAM_PREFIX', 'nv_');
// end

// Tên file error_log
// begin: NV_ERRORLOGS_FILENAME
define('NV_ERRORLOGS_FILENAME', 'error_log');
// end

// Tên file notice_log
// begin: NV_NOTICELOGS_FILENAME
define('NV_NOTICELOGS_FILENAME', 'notice_log');
// end

// Đuôi của file log
// begin: NV_LOGS_EXT
define('NV_LOGS_EXT', 'log');
// end

// Tên thay thế cho biến $name
// begin: NV_NAME_VARIABLE
define('NV_NAME_VARIABLE', 'nv');
// end

// Tên thay thế cho biến $op
// begin: NV_OP_VARIABLE
define('NV_OP_VARIABLE', 'op');
// end

// Tên thay thế cho biến ngôn ngữ
// begin: NV_LANG_VARIABLE
define('NV_LANG_VARIABLE', 'language');
// end

// Độ nén trang khi bật chế độ nén
// begin: ZLIB_OUTPUT_COMPRESSION_LEVEL
define('ZLIB_OUTPUT_COMPRESSION_LEVEL', 6);
// end

// Thời gian để tính online, tính bằng giây, 300 = 5 phút
// begin: NV_ONLINE_UPD_TIME
define('NV_ONLINE_UPD_TIME', 300);
// end

// Thời gian lưu trữ referer, 2592000 = 30 ngày
// begin: NV_REF_LIVE_TIME
define('NV_REF_LIVE_TIME', 2592000);
// end

// Số ký tự tối thiểu của input tìm kiếm
// begin: NV_MIN_SEARCH_LENGTH
define('NV_MIN_SEARCH_LENGTH', 3);
// end

// Số ký tự tối đa của input tìm kiếm
// begin: NV_MAX_SEARCH_LENGTH
define('NV_MAX_SEARCH_LENGTH', 60);
// end

// Ký tự phân cách trong title
// begin: NV_TITLEBAR_DEFIS
define('NV_TITLEBAR_DEFIS', ' - ');
// end

// Giao diện mặc định của hệ thống ngoài site
// begin: NV_DEFAULT_SITE_THEME
define('NV_DEFAULT_SITE_THEME', 'default');
// end

// Giao diện mặc định của hệ thống trong quản trị
// begin: NV_DEFAULT_ADMIN_THEME
define('NV_DEFAULT_ADMIN_THEME', 'admin_default');
// end

/*
 * Các thiết lập bên dưới vui lòng không chỉnh sửa
 */

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

// Thời gian tối đa chạy 1 truy vấn từ function post_async
// Tính bằng millisecond: 1 second = 1000 millisecond
define('NV_POST_ASYNC_TIMEOUT_MS', 100);
// Tính bằng second (dành cho php < 7.16.2)
define('NV_POST_ASYNC_TIMEOUT', 1);

//Hien thi, ghi loi
/*
 * E_ALL - All errors and warnings (doesn't include E_STRICT) E_ERROR - fatal run-time errors E_WARNING - run-time warnings (non-fatal errors) E_PARSE - compile-time parse errors E_NOTICE - run-time notices (these are warnings which often result from a bug in your code, but it's possible that it was intentional (e.g., using an uninitialized variable and relying on the fact it's automatically initialized to an empty string) E_STRICT			- run-time notices, enable to have PHP suggest changes to your code which will ensure the best interoperability and forward compatibility of your code E_CORE_ERROR - fatal errors that occur during PHP's initial startup E_CORE_WARNING - warnings (non-fatal errors) that occur during PHP's initial startup E_COMPILE_ERROR - fatal compile-time errors E_COMPILE_WARNING - compile-time warnings (non-fatal errors) E_USER_ERROR - user-generated error message E_USER_WARNING - user-generated warning message E_USER_NOTICE - user-generated notice message Examples: -
 * Show all errors, except for notices and coding standards warnings error_reporting = E_ALL & ~E_NOTICE - Show all errors, except for notices error_reporting = E_ALL & ~E_NOTICE | E_STRICT - Show only errors error_reporting = E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR
 */
define('NV_DISPLAY_ERRORS_LIST', E_ALL);
//Danh sach cac loi se hien thi
//define( 'NV_DISPLAY_ERRORS_LIST', 0); //tat thong bao loi
define('NV_LOG_ERRORS_LIST', version_compare(PHP_VERSION, '8.4.0', '<') ? (E_ALL | E_STRICT) : E_ALL);
//Danh sach cac loi se ghi log
define('NV_SEND_ERRORS_LIST', E_USER_ERROR);
//Danh sach cac loi se gui den email

//Phan dau cua trang
define('NV_FILEHEAD', "/**\n * NukeViet Content Management System\n * @version 5.x\n * @author VINADES.,JSC <contact@vinades.vn>\n * @copyright (C) 2009-" . gmdate('Y') . " VINADES.,JSC. All rights reserved\n * @license GNU/GPL version 2 or any later version\n * @see https://github.com/nukeviet The NukeViet CMS GitHub project\n */");

// Vui long khong thay doi gia tri nay
define('NUKEVIET_STORE_APIURL', 'https://api.nukeviet.vn/store/');

// Phương pháp hỗ trợ rewrite do người dùng khai báo,
// Nó sẽ được gán cho biến $sys_info['supports_rewrite'] nếu hệ thông không thể tự xác định
define('NV_MY_REWRITE_SUPPORTER', '');

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

// API
define('MANUALL_DEL_API_LOG', false); // Allow the super-admins to delete logs

// Cookies names
define('CURRENT_THEMETYPE_COOKIE_NAME', 'tm'); // Tên cookie chứa thông tin kiểu theme hiện tại
define('DATA_LANG_COOKIE_NAME', 'dlng'); // Tên cookie chứa thông tin ngôn ngữ data
define('INT_LANG_COOKIE_NAME', 'ilng'); // Tên cookie chứa thông tin ngôn ngữ hiển thị
define('U_LANG_COOKIE_NAME', 'ulng'); // Tên cookie chứa thông tin ngôn ngữ của người dùng
define('STATISTIC_COOKIE_NAME', 'st'); // Tên cookie chứa thông tin thời gian thống kê truy cập

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
    'og:image:alt' => '',
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

$nv_default_regions = [
    'vi' => [
        'decimal_symbol' => ',',
        'decimal_length' => 2,
        'thousand_symbol' => '.',
        'leading_zero' => 0,
        'trailing_zero' => 1,
        'currency_symbol' => 'đ',
        'currency_display' => 0,
        'currency_decimal_symbol' => ',',
        'currency_thousand_symbol' => '.',
        'currency_decimal_length' => 2,
        'currency_trailing_zero' => 1,
        'date_short' => 'd/m/Y',
        'date_long' => 'l, d F Y',
        'first_day_of_week' => 0,
        'time_short' => 'g:i A',
        'time_long' => 'g:i:s A',
        'am_char' => 'SA',
        'pm_char' => 'CH',
        'date_get' => 'd-m-Y',
        'date_post' => 'd/m/Y',
        'jsdate_get' => 'dd-mm-yyyy',
        'jsdate_post' => 'dd/mm/yyyy'
    ],
    'en' => [
        'decimal_symbol' => '.',
        'decimal_length' => 2,
        'thousand_symbol' => ',',
        'leading_zero' => 0,
        'trailing_zero' => 1,
        'currency_symbol' => '$',
        'currency_display' => 2,
        'currency_decimal_symbol' => '.',
        'currency_thousand_symbol' => ',',
        'currency_decimal_length' => 2,
        'currency_trailing_zero' => 1,
        'date_short' => 'm/d/Y',
        'date_long' => 'l, d F Y',
        'first_day_of_week' => 0,
        'time_short' => 'g:i A',
        'time_long' => 'g:i:s A',
        'am_char' => 'AM',
        'pm_char' => 'PM',
        'date_get' => 'm-d-Y',
        'date_post' => 'm/d/Y',
        'jsdate_get' => 'mm-dd-yyyy',
        'jsdate_post' => 'mm/dd/yyyy'
    ],
];
