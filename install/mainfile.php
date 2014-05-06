<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_SYSTEM' ) and ! defined( 'NV_ADMIN' ) and ! defined( 'NV_WYSIWYG' ) )
{
	Header( 'Location: index.php' );
	exit();
}

error_reporting( 0 );

define( 'NV_MAINFILE', true );

//Khong cho xac dinh tu do cac variables
$db_config = $global_config = $module_config = $client_info = $user_info = $admin_info = $sys_info = $lang_global = $lang_module = $rss = $nv_vertical_menu = $array_mod_title = $content_type = $select_options = $error_info = $countries = array();
$page_title = $key_words = $canonicalUrl = $mod_title = $editor_password = $my_head = $my_footer = $description = $contents = '';
$editor = false;

//Xac dinh thu muc goc cua site
define( 'NV_ROOTDIR', str_replace( '\\', '/', realpath( pathinfo( __file__, PATHINFO_DIRNAME ) . '/../' ) ) );

$sys_info['disable_classes'] = ( ( $disable_classes = ini_get( "disable_classes" ) ) != '' and $disable_classes != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", $disable_classes ) ) : array();
$sys_info['disable_functions'] = ( ( $disable_functions = ini_get( "disable_functions" ) ) != '' and $disable_functions != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", $disable_functions ) ) : array();

if( extension_loaded( 'suhosin' ) )
{
	$sys_info['disable_functions'] = array_merge( $sys_info['disable_functions'], array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "suhosin.executor.func.blacklist" ) ) ) );
}

$sys_info['ini_set_support'] = ( function_exists( 'ini_set' ) and ! in_array( 'ini_set', $sys_info['disable_functions'] ) ) ? true : false;

//Ket noi voi cac file constants, config
require NV_ROOTDIR . '/includes/constants.php';

require_once realpath( NV_ROOTDIR . '/install/config.php' );

$global_config['my_domains'] = $_SERVER['SERVER_NAME'];

// Xac dinh cac tags cho phep
$global_config['allowed_html_tags'] = array_map( "trim", explode( ',', NV_ALLOWED_HTML_TAGS ) );

//Xac dinh IP cua client
require NV_ROOTDIR . '/includes/class/ips.class.php';
$ips = new ips();
$client_info['ip'] = $ips->remote_ip;
if( $client_info['ip'] == "none" ) die( 'Error: Your IP address is not correct' );

//Neu khong co IP
//define( 'NV_SERVER_IP', $ips->server_ip );
define( 'NV_FORWARD_IP', $ips->forward_ip );
define( 'NV_REMOTE_ADDR', $ips->remote_addr );
define( 'NV_CLIENT_IP', $client_info['ip'] );

//Xac dinh Quoc gia
require NV_ROOTDIR . '/includes/countries.php';
$client_info['country'] = nv_getCountry_from_cookie( $client_info['ip'] );

//Mui gio
require NV_ROOTDIR . '/includes/timezone.php';
define( 'NV_CURRENTTIME', isset( $_SERVER['REQUEST_TIME'] ) ? $_SERVER['REQUEST_TIME'] : time() );

$global_config['log_errors_list'] = NV_LOG_ERRORS_LIST;
$global_config['display_errors_list'] = NV_DISPLAY_ERRORS_LIST;
$global_config['send_errors_list'] = NV_SEND_ERRORS_LIST;
$global_config['error_log_path'] = NV_LOGS_DIR . '/error_logs';
$global_config['error_log_filename'] = NV_ERRORLOGS_FILENAME;
$global_config['error_log_fileext'] = NV_LOGS_EXT;

//Ket noi voi class Error_handler
require NV_ROOTDIR . '/includes/class/error.class.php';
$ErrorHandler = new Error( $global_config );
set_error_handler( array( &$ErrorHandler, 'error_handler' ) );

//Ket noi voi cac file cau hinh, function va template
require NV_ROOTDIR . '/install/ini.php';
require NV_ROOTDIR . '/includes/utf8/' . $sys_info['string_handler'] . '_string_handler.php';
require NV_ROOTDIR . '/includes/utf8/utf8_functions.php';
require NV_ROOTDIR . '/includes/core/filesystem_functions.php';
require NV_ROOTDIR . '/includes/core/cache_functions.php';
require NV_ROOTDIR . '/includes/functions.php';
require NV_ROOTDIR . '/includes/core/theme_functions.php';
require NV_ROOTDIR . '/includes/class/xtemplate.class.php';

$global_config['allow_request_mods'] = NV_ALLOW_REQUEST_MODS != '' ? array_map( "trim", explode( ',', NV_ALLOW_REQUEST_MODS ) ) : "request";
$global_config['request_default_mode'] = NV_REQUEST_DEFAULT_MODE != '' ? trim( NV_REQUEST_DEFAULT_MODE ) : 'request';
$global_config['session_save_path'] = NV_SESSION_SAVE_PATH;

$language_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/langs.ini', true );

//Ket noi voi class xu ly request
require NV_ROOTDIR . '/includes/class/request.class.php';
$nv_Request = new Request( $global_config, $client_info['ip'] );

define( 'NV_SERVER_NAME', $nv_Request->server_name );
//vd: mydomain1.com
define( 'NV_SERVER_PROTOCOL', $nv_Request->server_protocol );
//vd: http
define( 'NV_SERVER_PORT', $nv_Request->server_port );
//vd: 80
define( 'NV_MY_DOMAIN', $nv_Request->my_current_domain );
//vd: http://mydomain1.com:80
define( 'NV_HEADERSTATUS', $nv_Request->headerstatus );
//vd: HTTP/1.0
define( 'NV_USER_AGENT', $nv_Request->user_agent );
//HTTP_USER_AGENT
define( "NV_BASE_SITEURL", preg_replace( "/\/install$/", "/", $nv_Request->base_siteurl ) );
//vd: /ten_thu_muc_chua_site/
define( "NV_BASE_ADMINURL", $nv_Request->base_adminurl . '/' );
//vd: /ten_thu_muc_chua_site/admin/
define( 'NV_DOCUMENT_ROOT', $nv_Request->doc_root );
// D:/AppServ/www
define( 'NV_EOL', ( strtoupper( substr( PHP_OS, 0, 3 ) == 'WIN' ) ? "\r\n" : ( strtoupper( substr( PHP_OS, 0, 3 ) == 'MAC' ) ? "\r" : "\n" ) ) );
//Ngat dong
define( 'NV_UPLOADS_REAL_DIR', NV_ROOTDIR . '/' . NV_UPLOADS_DIR );
//Xac dinh duong dan thuc den thu muc upload
define( 'NV_CACHE_PREFIX', md5( $global_config['sitekey'] . NV_BASE_SITEURL ) );
//Hau to cua file cache

//Ngon ngu
require NV_ROOTDIR . '/includes/language.php';
require NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/global.php';

$global_config['cookie_path'] = $nv_Request->cookie_path;
//vd: /ten_thu_muc_chua_site/
$global_config['cookie_domain'] = $nv_Request->cookie_domain;
//vd: .mydomain1.com
$global_config['site_url'] = $nv_Request->site_url;
//vd: http://mydomain1.com/ten_thu_muc_chua_site
$global_config['my_domains'] = $nv_Request->my_domains;
//vd: "mydomain1.com,mydomain2.com"

$sys_info['register_globals'] = $nv_Request->is_register_globals;
//0 = khong, 1 = bat
$sys_info['magic_quotes_gpc'] = $nv_Request->is_magic_quotes_gpc;
// 0 = khong, 1 = co
$sys_info['sessionpath'] = $nv_Request->session_save_path;
//vd: D:/AppServ/www/ten_thu_muc_chua_site/sess/

$client_info['session_id'] = $nv_Request->session_id;
//ten cua session
$client_info['referer'] = $nv_Request->referer;
//referer
$client_info['is_myreferer'] = $nv_Request->referer_key;
//0 = referer tu ben ngoai site, 1 = referer noi bo, 2 = khong co referer
$client_info['selfurl'] = $nv_Request->my_current_domain . $nv_Request->request_uri;
//trang dang xem
$client_info['agent'] = $nv_Request->user_agent;
//HTTP_USER_AGENT
$client_info['session_id'] = $nv_Request->session_id;
//ten cua session
$global_config['sitekey'] = md5( $_SERVER['SERVER_NAME'] . NV_ROOTDIR . $client_info['session_id'] );

//Chan truy cap neu HTTP_USER_AGENT == 'none'
if( NV_USER_AGENT == "none" )
{
	trigger_error( 'We\'re sorry. The software you are using to access our website is not allowed. Some examples of this are e-mail harvesting programs and programs that will copy websites to your hard drive. If you feel you have gotten this message in error, please send an e-mail addressed to admin. Your I.P. address has been logged. Thanks.', 256 );
}

//Captcha
if( $nv_Request->isset_request( 'scaptcha', 'get' ) )
{
	include_once NV_ROOTDIR . '/includes/core/captcha.php';
}

//Class ma hoa du lieu
require NV_ROOTDIR . '/includes/class/crypt.class.php';
$crypt = new nv_Crypt( $global_config['sitekey'], NV_CRYPT_SHA1 == 1 ? 'sha1' : 'md5' );

?>