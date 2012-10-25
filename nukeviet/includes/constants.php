<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 5:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

//Neu thay doi bat ky gia tri trong file nay ban can vao admin phan cau hinh he thong va luu lai

//Ten file config
define( "NV_CONFIG_FILENAME", "config.php" );

//Ten thu muc admin
define( "NV_ADMINDIR", "admin" );

//Ten thu muc editors
define( "NV_EDITORSDIR", "editors" );

//Thu muc chua dien dan
define( "DIR_FORUM", "forum" );

//Thu muc uploads
define( "NV_UPLOADS_DIR", "uploads" );

//Thu muc files
define( "NV_FILES_DIR", "files" );

//Thu muc uploads banner
define( "NV_BANNER_DIR", "banners" );

//Thu muc chua cac file logs
define( "NV_LOGS_DIR", "logs" );

//Thu muc chua sessions
define( "NV_SESSION_SAVE_PATH", "sess" );

//Thu muc chua cac file tam thoi
define( "NV_TEMP_DIR", "tmp" );

//Ten thu muc cache
define( "NV_CACHEDIR", "cache" );

//Ten thu muc luu data
define( "NV_DATADIR", "data" );

//TDT file tam thoi, toi da 3 ky tu
define( "NV_TEMPNAM_PREFIX", "nv_" );

//Ten file error_log
define( 'NV_ERRORLOGS_FILENAME', 'error_log' );

//duoi cua file log
define( "NV_LOGS_EXT", "log" );

//Ten thay the cho bien $name
define( "NV_NAME_VARIABLE", "nv" );

//Ten thay the cho bien $op
define( "NV_OP_VARIABLE", "op" );

//Ten thay the cho bien ngon ngu
define( "NV_LANG_VARIABLE", "language" );

//Ten bien kiem tra lai mat khau admin
define( "NV_ADMINRELOGIN_VARIABLE", "adminrelogin" );

//So lan admin duoc phep kiem tra lai password.
//Neu sau so lan do ma van khai bao sai, he thong se tuoc quyen admin va day ra trang chu
define( "NV_ADMINRELOGIN_MAX", 3 );

//Quang thoi gian de kiem tra lai pass cua admin (Neu admin khong hoat dong)
define( "NV_ADMIN_CHECK_PASS_TIME", 3600 );

//Thoi gian ton tai cua cookie, 31536000 giay = 1 nam
define( 'NV_LIVE_COOKIE_TIME', 31536000 );

//Thoi gian ton tai cua session, 0  = ton tai cho den khi dong trinh duyet
define( 'NV_LIVE_SESSION_TIME', 0 );

//Do nen trang khi bat che do nen
define( 'ZLIB_OUTPUT_COMPRESSION_LEVEL', 6 );

//Phuong phap ma hoa mat khau: 0 = md5, 1 = sha1
define( 'NV_CRYPT_SHA1', 1 );

//so ky tu toi da cua password doi voi user
define( 'NV_UPASSMAX', 20 );

//so ky tu toi thieu cua password doi voi user
define( 'NV_UPASSMIN', 5 );

//so ky tu toi da cua ten tai khoan doi voi user
define( 'NV_UNICKMAX', 20 );

//so ky tu toi thieu cua ten tai khoan doi voi user
define( 'NV_UNICKMIN', 4 );

//so ky tu cua Ma kiem tra
define( 'NV_GFX_NUM', 6 );

//Kich thuoc cua hinh hien thi Ma kiem tra
define( 'NV_GFX_WIDTH', 120 );
define( 'NV_GFX_HEIGHT', 25 );

//Thoi gian de tinh online, tinh bang giay, 300 = 5 phut
define( 'NV_ONLINE_UPD_TIME', 300 );

//Thoi gian luu tru referer, 2592000 = 30 ngay
define( 'NV_REF_LIVE_TIME', 2592000 );

//Chieu rong toi da cua hinh tai len
define( 'NV_MAX_WIDTH', 1500 );

//Chieu dai toi da cua hinh tai len
define( 'NV_MAX_HEIGHT', 1500 );

//So ky tu toi thieu cua input tim kiem
define( 'NV_MIN_SEARCH_LENGTH', 3 );

//So ky tu toi da cua input tim kiem
define( 'NV_MAX_SEARCH_LENGTH', 60 );

//Co bat tinh nang chong flood hay khong
define( 'NV_IS_FLOOD_BLOCKER', 1 );

//So requests toi da trong 1 phut
define( 'NV_MAX_REQUESTS_60', 40 );

//So requests toi da trong 5 phut
define( 'NV_MAX_REQUESTS_300', 150 );

//ky tu phan cach trong title
define( "NV_TITLEBAR_DEFIS", "-" );

//Cac thiet lap trong siteword
define( 'NV_SITEWORDS_MIN_WORD_LENGTH', 4 );
define( 'NV_SITEWORDS_MIN_WORD_OCCUR', 5 );
define( 'NV_SITEWORDS_MIN_2WORDS_LENGTH', 2 );
define( 'NV_SITEWORDS_MIN_2WORDS_PHRASE_OCCUR', 2 );
define( 'NV_SITEWORDS_MIN_3WORDS_LENGTH', 0 );
define( 'NV_SITEWORDS_MIN_3WORDS_PHRASE_OCCUR', 0 );
define( 'NV_SITEWORDS_MAX_STRLEN', 300 );

//Mac dinh, neu cac thong so khong thay doi, MySQL khong ket noi moi
//ma quay lai voi ket noi cu. Neu NEW_LINK = true se khien MySql luon ket noi moi
define( 'NV_MYSQL_NEW_LINK', false );

//Neu true: Ket noi thuong xuyen
//false: Ket noi khi can
define( 'NV_MYSQL_PERSISTENCY', false );

// mysql 5.6 support utf8_general_ci, utf8_vietnamese_ci
define( 'NV_MYSQL_COLLATION', 'utf8_general_ci' );

// Thiet lap cho get,post,cookie,session,request,env,server
define( "NV_COOKIE_SECURE", 0 );

define( "NV_COOKIE_HTTPONLY", 1 );

define( "NV_ALLOW_REQUEST_MODS", "get,post,cookie,session,request,env,server" );

define( "NV_REQUEST_DEFAULT_MODE", "request" );

define( "NV_XSS_REPLACESTRING", "<x>" );

//HIen thi, ghi loi
//Khong chinh sua gi o 4 dong duoi nay
if ( ! defined( 'E_STRICT' ) ) define( 'E_STRICT', 2048 ); //khong sua
if ( ! defined( 'E_RECOVERABLE_ERROR' ) ) define( 'E_RECOVERABLE_ERROR', 4096 ); //khong sua
if ( ! defined( 'E_DEPRECATED' ) ) define( 'E_DEPRECATED', 8192 ); //khong sua
if ( ! defined( 'E_USER_DEPRECATED' ) ) define( 'E_USER_DEPRECATED', 16384 ); //khong sua
/*
E_ALL             - All errors and warnings (doesn't include E_STRICT)
E_ERROR           - fatal run-time errors
E_WARNING         - run-time warnings (non-fatal errors)
E_PARSE           - compile-time parse errors
E_NOTICE          - run-time notices (these are warnings which often result
                    from a bug in your code, but it's possible that it was
                    intentional (e.g., using an uninitialized variable and
                    relying on the fact it's automatically initialized to an
                    empty string)
E_STRICT			- run-time notices, enable to have PHP suggest changes
                    to your code which will ensure the best interoperability
                    and forward compatibility of your code
E_CORE_ERROR      - fatal errors that occur during PHP's initial startup
E_CORE_WARNING    - warnings (non-fatal errors) that occur during PHP's
                    initial startup
E_COMPILE_ERROR   - fatal compile-time errors
E_COMPILE_WARNING - compile-time warnings (non-fatal errors)
E_USER_ERROR      - user-generated error message
E_USER_WARNING    - user-generated warning message
E_USER_NOTICE     - user-generated notice message

Examples:
- Show all errors, except for notices and coding standards warnings
error_reporting = E_ALL & ~E_NOTICE

- Show all errors, except for notices
error_reporting = E_ALL & ~E_NOTICE | E_STRICT

- Show only errors
error_reporting = E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR
*/
define( "NV_DISPLAY_ERRORS_LIST", E_ALL ); //Danh sach cac loi se hien thi
//define( "NV_DISPLAY_ERRORS_LIST", 0); //tat thong bao loi
define( "NV_LOG_ERRORS_LIST", E_ALL | E_STRICT ); //Danh sach cac loi se ghi log
define( "NV_SEND_ERRORS_LIST", E_USER_ERROR ); //Danh sach cac loi se gui den email

// Ma HTML duoc chap nhan
define( 'NV_ALLOWED_HTML_TAGS', 'embed, object, param, a, b, blockquote, br, caption, col, colgroup, div, em, h1, h2, h3, h4, h5, h6, hr, i, img, li, p, span, strong, sub, sup, table, tbody, td, th, tr, u, ul' );

//Phan dau cua trang
define( 'NV_FILEHEAD', "/**\n * @Project NUKEVIET 3.x\n * @Author VINADES.,JSC (contact@vinades.vn)\n * @Copyright (C) " . gmdate( "Y" ) . " VINADES.,JSC. All rights reserved\n * @Createdate " . gmdate( "D, d M Y H:i:s" ) . " GMT\n */" );

// Phien ban giao dien tu cao den thap - it nhat phai co hai kieu mac dinh khong duoc it hon
define( "NV_THEME_TYPE", 'd,t' );

//Chong IFRAME
define( "NV_ANTI_IFRAME", 0 );

$global_config['site_charset'] = "utf-8";
$global_config['check_module'] = "/^[a-z0-9\-]+$/";
$global_config['check_op'] = "/^[a-zA-Z0-9\-]+$/";
$global_config['check_op_file'] = "/^([a-zA-Z0-9\-\_]+)\.php$/";
$global_config['check_block_global'] = "/^global\.([a-zA-Z0-9\-\_]+)\.php$/";
$global_config['check_block_module'] = "/^(global|module)\.([a-zA-Z0-9\-\_]+)\.php$/";
$global_config['check_theme'] = "/^(?!admin\_|mobile\_)([a-zA-Z0-9\-\_]+)$/";
$global_config['check_theme_mobile'] = "/^(mobile)\_[a-zA-Z0-9\-\_]+$/";
$global_config['check_theme_admin'] = "/^(admin)\_[a-zA-Z0-9\-\_]+$/";

$global_config['check_email'] = '/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/';
$global_config['check_cron'] = "/^(cron)\_[a-zA-Z0-9\_]+$/";
$global_config['check_op_layout'] = "/^layout\.([a-zA-Z0-9\-\_]+)\.tpl$/";

?>