<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 22:42
 */

if ( ! defined( 'NV_SYSTEM' ) and ! defined( 'NV_ADMIN' ) and ! defined( 'NV_WYSIWYG' ) )
{
    Header( "Location: index.php" );
    exit();
}

define( 'NV_MAINFILE', true );

//Thoi gian bat dau phien lam viec
define( 'NV_START_TIME', array_sum( explode( " ", microtime() ) ) );

//Khong cho xac dinh tu do cac variables
$db_config = array();
$global_config = array();
$module_config = array();
$client_info = array();
$user_info = array();
$admin_info = array();
$sys_info = array();
$lang_global = array();
$lang_ = array();
$rss = array();
$nv_vertical_menu = array();
$content_type = array();
$blocks = array();
$contents = "";
$submenu = array();
$select_options = array();
$error_info = array();
unset( $key_words, $page_title, $mod_title, $editor, $editor_password, $my_head );

//Xac dinh thu muc goc cua site
define( 'NV_ROOTDIR', realpath( pathinfo( str_replace( '\\', '/', __file__ ), PATHINFO_DIRNAME ) . '/../' ) );

$sys_info['disable_functions'] = ( ini_get( "disable_functions" ) != "" and ini_get( "disable_functions" ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_functions" ) ) ) : array();
$sys_info['ini_set_support'] = ( function_exists( 'ini_set' ) and ! in_array( 'ini_set', $sys_info['disable_functions'] ) ) ? true : false;

require_once ( realpath( NV_ROOTDIR . "/install/config.php" ) );

$global_config['my_domains'] = $_SERVER['SERVER_NAME'];

//Ket noi voi cac file constants, config, timezone
require_once ( NV_ROOTDIR . "/includes/constants.php" );
require_once ( NV_ROOTDIR . '/includes/timezone.php' );

define( 'NV_CURRENTTIME', time() );
define( 'NV_CURRENTYEAR_FNUM', date( 'Y', NV_CURRENTTIME ) ); //2009
define( 'NV_CURRENTYEAR_2NUM', date( 'y', NV_CURRENTTIME ) ); //09
define( 'NV_CURRENTMONTH_NUM', date( 'm', NV_CURRENTTIME ) ); //01-12
define( 'NV_CURRENTMONTH_FTXT', date( 'F', NV_CURRENTTIME ) ); //January - December
define( 'NV_CURRENTMONTH_STXT', date( 'M', NV_CURRENTTIME ) ); //Jan - Dec
define( 'NV_CURRENTDAY_2NUM', date( 'd', NV_CURRENTTIME ) ); //01 - 31
define( 'NV_CURRENTDAY_1NUM', date( 'j', NV_CURRENTTIME ) ); //1 - 31
define( 'NV_CURRENT12HOUR_2NUM', date( 'h', NV_CURRENTTIME ) ); //00-12
define( 'NV_CURRENT12HOUR_1NUM', date( 'g', NV_CURRENTTIME ) ); //0-12
define( 'NV_CURRENT24HOUR_2NUM', date( 'H', NV_CURRENTTIME ) ); //00-23
define( 'NV_CURRENT24HOUR_1NUM', date( 'G', NV_CURRENTTIME ) ); //0-23
define( 'NV_CURRENTMIN_2NUM', date( 'i', NV_CURRENTTIME ) ); //00-59
define( 'NV_DEL_ONLINE_TIME', ( NV_CURRENTTIME - NV_ONLINE_UPD_TIME ) ); //Thoi gian xoa tinh trang online


$global_config['log_errors_list'] = NV_LOG_ERRORS_LIST;
$global_config['display_errors_list'] = NV_DISPLAY_ERRORS_LIST;
$global_config['send_errors_list'] = NV_SEND_ERRORS_LIST;
$global_config['error_log_path'] = NV_LOGS_DIR . '/error_logs';
$global_config['error_log_filename'] = NV_ERRORLOGS_FILENAME;
$global_config['error_log_fileext'] = NV_LOGS_EXT;

//Ket noi voi class Error_handler
require_once ( NV_ROOTDIR . '/includes/class/error.class.php' );
$ErrorHandler = new Error( $global_config );
set_error_handler( array( 
    &$ErrorHandler, 'error_handler' 
) );

//Ket noi voi cac file cau hinh, function va template
require_once ( NV_ROOTDIR . "/install/ini.php" );
require_once ( NV_ROOTDIR . '/includes/functions.php' );
require_once ( NV_ROOTDIR . '/includes/core/theme_functions.php' );
require_once ( NV_ROOTDIR . "/includes/class/xtemplate.class.php" );

$global_config['allow_request_mods'] = NV_ALLOW_REQUEST_MODS != '' ? array_map( "trim", explode( ",", NV_ALLOW_REQUEST_MODS ) ) : "request";
$global_config['request_default_mode'] = NV_REQUEST_DEFAULT_MODE != '' ? trim( NV_REQUEST_DEFAULT_MODE ) : 'request';
$global_config['XSS_replaceString'] = NV_XSS_REPLACESTRING != '' ? NV_XSS_REPLACESTRING : '';
$global_config['cookie_key'] = $global_config['sitekey'];
$global_config['cookie_secure'] = NV_COOKIE_SECURE;
$global_config['cookie_httponly'] = NV_COOKIE_HTTPONLY;
$global_config['session_save_path'] = "";

//Ket noi voi file xac dinh IP
require_once ( NV_ROOTDIR . '/includes/class/ips.class.php' );

$ips = new ips();
define( 'NV_CLIENT_IP', $ips->client_ip );
define( 'NV_FORWARD_IP', $ips->forward_ip );
define( 'NV_REMOTE_ADDR', $ips->remote_addr );

//Xac dinh IP cua client
$client_info['ip'] = $ips->remote_ip;
$client_info['is_proxy'] = $ips->is_proxy;
if ( $client_info['ip'] == "none" ) trigger_error( 'Error: Your IP address is not correct', 256 ); //Neu khong co IP
//Ket noi voi class xu ly request


require_once ( NV_ROOTDIR . '/includes/class/request.class.php' );
$nv_Request = new Request( $global_config, $client_info['ip'] );
//Ngon ngu
$language_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/langs.ini', true );

require_once ( NV_ROOTDIR . '/includes/language.php' );
require_once ( NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/global.php" );

$global_config['cookie_path'] = $nv_Request->cookie_path; //vd: /ten_thu_muc_chua_site/
$global_config['cookie_domain'] = $nv_Request->cookie_domain; //vd: .mydomain1.com
$global_config['site_url'] = $nv_Request->site_url; //vd: http://mydomain1.com/ten_thu_muc_chua_site
$global_config['my_domains'] = $nv_Request->my_domains; //vd: "mydomain1.com,mydomain2.com"


$sys_info['register_globals'] = $nv_Request->is_register_globals; //0 = khong, 1 = bat
$sys_info['magic_quotes_gpc'] = $nv_Request->is_magic_quotes_gpc; // 0 = khong, 1 = co
$sys_info['sessionpath'] = $nv_Request->session_save_path; //vd: D:/AppServ/www/ten_thu_muc_chua_site/sess/


$client_info['session_id'] = $nv_Request->session_id; //ten cua session
$client_info['referer'] = $nv_Request->referer; //referer
$client_info['is_myreferer'] = $nv_Request->referer_key; //0 = referer tu ben ngoai site, 1 = referer noi bo, 2 = khong co referer
$client_info['selfurl'] = $nv_Request->my_current_domain . $nv_Request->request_uri; //trang dang xem
$client_info['agent'] = $nv_Request->user_agent; //HTTP_USER_AGENT

$global_config['sitekey'] = md5( $global_config['my_domains'] . NV_ROOTDIR . $client_info['session_id'] );


define( 'NV_SERVER_NAME', $nv_Request->server_name ); //vd: mydomain1.com
define( 'NV_SERVER_PROTOCOL', $nv_Request->server_protocol ); //vd: http
define( 'NV_SERVER_PORT', $nv_Request->server_port ); //vd: 80
define( 'NV_MY_DOMAIN', $nv_Request->my_current_domain ); //vd: http://mydomain1.com:80
define( 'NV_HEADERSTATUS', $nv_Request->headerstatus ); //vd: HTTP/1.0
define( 'NV_USER_AGENT', $nv_Request->user_agent ); //HTTP_USER_AGENT
define( "NV_BASE_SITEURL", $nv_Request->base_siteurl . '/' ); //vd: /ten_thu_muc_chua_site/
define( "NV_BASE_ADMINURL", $nv_Request->base_adminurl . '/' ); //vd: /ten_thu_muc_chua_site/admin/
define( 'NV_DOCUMENT_ROOT', $nv_Request->doc_root ); // D:/AppServ/www
define( 'NV_EOL', ( strtoupper( substr( PHP_OS, 0, 3 ) == 'WIN' ) ? "\r\n" : ( strtoupper( substr( PHP_OS, 0, 3 ) == 'MAC' ) ? "\r" : "\n" ) ) ); //Ngat dong
define( 'NV_UPLOADS_REAL_DIR', NV_ROOTDIR . '/' . NV_UPLOADS_DIR ); //Xac dinh duong dan thuc den thu muc upload

//Chan truy cap neu HTTP_USER_AGENT == 'none'
if ( NV_USER_AGENT == "none" )
{
    trigger_error( 'We\'re sorry. The software you are using to access our website is not allowed. Some examples of this are e-mail harvesting programs and programs that will  copy websites to your hard drive. If you feel you have gotten this message  in error, please send an e-mail addressed to admin. Your I.P. address has been logged. Thanks.', 256 );
}

//Xac dinh borwser cua client
$client_info['browser'] = array_combine( array( 
    'key', 'name' 
), explode( "|", nv_getBrowser( NV_USER_AGENT, NV_ROOTDIR . '/includes/ini/br.ini' ) ) );

//Class ma hoa du lieu $crypt->hash($data)
require_once ( NV_ROOTDIR . '/includes/class/crypt.class.php' );
$crypt = new nv_Crypt( $global_config['sitekey'], NV_CRYPT_SHA1 == 1 ? 'sha1' : 'md5' );
if ( ! $crypt->_otk ) trigger_error( "sitekey not declared", 256 );

?>