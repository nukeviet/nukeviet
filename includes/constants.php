<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

//Neu thay doi bat ky gia tri trong file nay ban can vao admin phan cau hinh he thong va luu lai

//Ten file config
define( 'NV_CONFIG_FILENAME', 'config.php' );

//Ten thu muc admin
define( 'NV_ADMINDIR', 'admin' );

//Ten thu muc editors
define( 'NV_EDITORSDIR', 'editors' );

//Thu muc uploads
define( 'NV_UPLOADS_DIR', 'uploads' );

//Thu muc files
define( 'NV_FILES_DIR', 'files' );

//Thu muc uploads banner
define( 'NV_BANNER_DIR', 'banners' );

//Thu muc chua cac file logs
define( 'NV_LOGS_DIR', 'logs' );

//Thu muc chua sessions
define( 'NV_SESSION_SAVE_PATH', 'sess' );

//Thu muc chua cac file tam thoi
define( 'NV_TEMP_DIR', 'tmp' );

//Ten thu muc cache
define( 'NV_CACHEDIR', 'cache' );

//Ten thu muc luu data
define( 'NV_DATADIR', 'data' );

//TDT file tam thoi, toi da 3 ky tu
define( 'NV_TEMPNAM_PREFIX', 'nv_' );

//Ten file error_log
define( 'NV_ERRORLOGS_FILENAME', 'error_log' );

//duoi cua file log
define( 'NV_LOGS_EXT', 'log' );

//Ten thay the cho bien $name
define( 'NV_NAME_VARIABLE', 'nv' );

//Ten thay the cho bien $op
define( 'NV_OP_VARIABLE', 'op' );

//Ten thay the cho bien ngon ngu
define( 'NV_LANG_VARIABLE', 'language' );

//Ten bien kiem tra lai mat khau admin
define( 'NV_ADMINRELOGIN_VARIABLE', 'adminrelogin' );

//Do nen trang khi bat che do nen
define( 'ZLIB_OUTPUT_COMPRESSION_LEVEL', 6 );

//Phuong phap ma hoa mat khau: 0 = md5, 1 = sha1
define( 'NV_CRYPT_SHA1', 1 );

//Thoi gian de tinh online, tinh bang giay, 300 = 5 phut
define( 'NV_ONLINE_UPD_TIME', 300 );

//Thoi gian luu tru referer, 2592000 = 30 ngay
define( 'NV_REF_LIVE_TIME', 2592000 );

//So ky tu toi thieu cua input tim kiem
define( 'NV_MIN_SEARCH_LENGTH', 3 );

//So ky tu toi da cua input tim kiem
define( 'NV_MAX_SEARCH_LENGTH', 60 );

//ky tu phan cach trong title
define( 'NV_TITLEBAR_DEFIS', ' - ' );

//Cac thiet lap trong siteword
define( 'NV_SITEWORDS_MIN_WORD_LENGTH', 4 );
define( 'NV_SITEWORDS_MIN_WORD_OCCUR', 5 );
define( 'NV_SITEWORDS_MIN_2WORDS_LENGTH', 2 );
define( 'NV_SITEWORDS_MIN_2WORDS_PHRASE_OCCUR', 2 );
define( 'NV_SITEWORDS_MIN_3WORDS_LENGTH', 0 );
define( 'NV_SITEWORDS_MIN_3WORDS_PHRASE_OCCUR', 0 );
define( 'NV_SITEWORDS_MAX_STRLEN', 300 );

// Thiet lap cho get,post,cookie,session,request,env,server
define( 'NV_ALLOW_REQUEST_MODS', 'get,post,cookie,session,request,env,server' );
define( 'NV_REQUEST_DEFAULT_MODE', 'request' );

//Hien thi, ghi loi
/*
 * E_ALL - All errors and warnings (doesn't include E_STRICT) E_ERROR - fatal run-time errors E_WARNING - run-time warnings (non-fatal errors) E_PARSE - compile-time parse errors E_NOTICE - run-time notices (these are warnings which often result from a bug in your code, but it's possible that it was intentional (e.g., using an uninitialized variable and relying on the fact it's automatically initialized to an empty string) E_STRICT			- run-time notices, enable to have PHP suggest changes to your code which will ensure the best interoperability and forward compatibility of your code E_CORE_ERROR - fatal errors that occur during PHP's initial startup E_CORE_WARNING - warnings (non-fatal errors) that occur during PHP's initial startup E_COMPILE_ERROR - fatal compile-time errors E_COMPILE_WARNING - compile-time warnings (non-fatal errors) E_USER_ERROR - user-generated error message E_USER_WARNING - user-generated warning message E_USER_NOTICE - user-generated notice message Examples: -
 * Show all errors, except for notices and coding standards warnings error_reporting = E_ALL & ~E_NOTICE - Show all errors, except for notices error_reporting = E_ALL & ~E_NOTICE | E_STRICT - Show only errors error_reporting = E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR
 */
define( 'NV_DISPLAY_ERRORS_LIST', E_ALL );
//Danh sach cac loi se hien thi
//define( 'NV_DISPLAY_ERRORS_LIST', 0); //tat thong bao loi
define( 'NV_LOG_ERRORS_LIST', E_ALL | E_STRICT );
//Danh sach cac loi se ghi log
define( 'NV_SEND_ERRORS_LIST', E_USER_ERROR );
//Danh sach cac loi se gui den email

//Phan dau cua trang
define( 'NV_FILEHEAD', "/**\n * @Project NUKEVIET 4.x\n * @Author VINADES.,JSC (contact@vinades.vn)\n * @Copyright (C) " . gmdate( "Y" ) . " VINADES.,JSC. All rights reserved\n * @License GNU/GPL version 2 or any later version\n * @Createdate " . gmdate( "D, d M Y H:i:s" ) . " GMT\n */" );

// Phien ban giao dien tu cao den thap - it nhat phai co hai kieu mac dinh khong duoc it hon
define( 'NV_THEME_TYPE', 'd,t' );

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