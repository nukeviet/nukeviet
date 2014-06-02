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

// Thoi gian bat dau phien lam viec
define( 'NV_START_TIME', microtime( true ) );

// Khong cho xac dinh tu do cac variables
$db_config = $global_config = $module_config = $client_info = $user_info = $admin_info = $sys_info = $lang_global = $lang_module = $rss = $nv_vertical_menu = $array_mod_title = $content_type = $submenu = $select_options = $error_info = $countries = array();
$page_title = $key_words = $canonicalUrl = $mod_title = $editor_password = $my_head = $my_footer = $description = $contents = '';
$editor = false;

// Xac dinh thu muc goc cua site
define( 'NV_ROOTDIR', pathinfo( str_replace( DIRECTORY_SEPARATOR, '/', __file__ ), PATHINFO_DIRNAME ) );

// Ket noi voi cac file constants, config
require NV_ROOTDIR . '/includes/constants.php';
if( file_exists( NV_ROOTDIR . '/' . NV_CONFIG_FILENAME ) )
{
	require realpath( NV_ROOTDIR . '/' . NV_CONFIG_FILENAME );
}
else
{
	if( file_exists( NV_ROOTDIR . '/install/index.php' ) )
	{
		$base_siteurl = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME );
		if( $base_siteurl == DIRECTORY_SEPARATOR ) $base_siteurl = '';
		if( ! empty( $base_siteurl ) ) $base_siteurl = str_replace( DIRECTORY_SEPARATOR, '/', $base_siteurl );
		if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( '/[\/]+$/', '', $base_siteurl );
		if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( '/^[\/]*(.*)$/', '/\\1', $base_siteurl );
		if( defined( 'NV_ADMIN' ) )
		{
			$base_siteurl = preg_replace( '#/' . NV_ADMINDIR . '(.*)$#', '', $base_siteurl );
		}
		if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( '#/index\.php(.*)$#', '', $base_siteurl );
		Header( 'Location: ' . $base_siteurl . '/install/index.php' );
	}
	die();
}
require NV_ROOTDIR . '/' . NV_DATADIR . '/config_global.php';

if( defined( 'NV_CONFIG_DIR' ) )
{
    $server_name = preg_replace( '/^[a-z]+\:\/\//i', '',  isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : ( isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '' ) );
    if( file_exists( NV_ROOTDIR . '/' . NV_CONFIG_DIR . '/' . $server_name . '.php' ) )
    {
        require NV_ROOTDIR . '/' . NV_CONFIG_DIR . '/' . $server_name . '.php';
        $db_config['dbname'] = $db_config['dbsite'];
        $global_config['my_domains'] = $server_name;
    }
    define( 'NV_UPLOADS_DIR', SYSTEM_UPLOADS_DIR . '/' . $global_config['site_dir'] );
    define( 'NV_FILES_DIR', SYSTEM_FILES_DIR . '/' . $global_config['site_dir'] );
    define( 'NV_CACHEDIR', SYSTEM_CACHEDIR . '/' . $global_config['site_dir'] );
}
else
{
    define( 'SYSTEM_UPLOADS_DIR', NV_UPLOADS_DIR );
    define( 'SYSTEM_FILES_DIR', NV_FILES_DIR );
    define( 'SYSTEM_CACHEDIR', NV_CACHEDIR );
}

// Xac dinh IP cua client
require NV_ROOTDIR . '/includes/class/ips.class.php';
$ips = new ips();
// define( 'NV_SERVER_IP', $ips->server_ip );
define( 'NV_FORWARD_IP', $ips->forward_ip );
define( 'NV_REMOTE_ADDR', $ips->remote_addr );
define( 'NV_CLIENT_IP', $ips->remote_ip );

// Neu khong co IP
if( NV_CLIENT_IP == 'none' ) die( 'Error: Your IP address is not correct' );

// Xac dinh Quoc gia
require NV_ROOTDIR . '/includes/countries.php';
$client_info['country'] = nv_getCountry_from_cookie( NV_CLIENT_IP );
$client_info['ip'] = NV_CLIENT_IP;

// Mui gio
require NV_ROOTDIR . '/includes/timezone.php';
define( 'NV_CURRENTTIME', isset( $_SERVER['REQUEST_TIME'] ) ? $_SERVER['REQUEST_TIME'] : time() );

// Ket noi voi class Error_handler
require NV_ROOTDIR . '/includes/class/error.class.php';
$ErrorHandler = new Error( $global_config );
set_error_handler( array( &$ErrorHandler, 'error_handler' ) );

if( empty( $global_config['allow_sitelangs'] ) or empty( $global_config['allow_adminlangs'] ) )
{
	trigger_error( 'Error! Language variables is empty!', 256 );
}

// Ket noi voi cac file cau hinh, function va template
require NV_ROOTDIR . '/includes/ini.php';
require NV_ROOTDIR . '/includes/utf8/' . $sys_info['string_handler'] . '_string_handler.php';
require NV_ROOTDIR . '/includes/utf8/utf8_functions.php';
require NV_ROOTDIR . '/includes/core/filesystem_functions.php';
require NV_ROOTDIR . '/includes/core/cache_functions.php';
require NV_ROOTDIR . '/includes/functions.php';
require NV_ROOTDIR . '/includes/core/theme_functions.php';
require NV_ROOTDIR . '/includes/class/xtemplate.class.php';

// IP Ban
if( nv_is_banIp( NV_CLIENT_IP ) ) trigger_error( 'Hi and Good-bye!!!', 256 );

// Chan proxy
if( $global_config['proxy_blocker'] != 0 )
{
	$client_info['is_proxy'] = $ips->nv_check_proxy();
	if( nv_is_blocker_proxy( $client_info['is_proxy'], $global_config['proxy_blocker'] ) )
	{
		trigger_error( 'ERROR: You are behind a proxy server. Please disconnect and come again!', 256 );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	require NV_ROOTDIR . '/includes/request_uri.php';
}

// Ket noi voi class xu ly request
require NV_ROOTDIR . '/includes/class/request.class.php';
$nv_Request = new Request( $global_config, NV_CLIENT_IP );

define( 'NV_SERVER_NAME', $nv_Request->server_name );
// vd: mydomain1.com

define( 'NV_SERVER_PROTOCOL', $nv_Request->server_protocol );
// vd: http

define( 'NV_SERVER_PORT', $nv_Request->server_port );
// vd: 80

define( 'NV_MY_DOMAIN', $nv_Request->my_current_domain );
// vd: http://mydomain1.com:80

define( 'NV_HEADERSTATUS', $nv_Request->headerstatus );
// vd: HTTP/1.0

define( 'NV_BASE_SITEURL', $nv_Request->base_siteurl . '/' );
// vd: /ten_thu_muc_chua_site/

define( 'NV_BASE_ADMINURL', $nv_Request->base_adminurl . '/' );
// vd: /ten_thu_muc_chua_site/admin/

define( 'NV_DOCUMENT_ROOT', $nv_Request->doc_root );
// D:/AppServ/www

define( 'NV_CACHE_PREFIX', md5( $global_config['sitekey'] . NV_SERVER_NAME ) );
// Hau to cua file cache

define( 'NV_USER_AGENT', $nv_Request->user_agent );

// Ngon ngu
require NV_ROOTDIR . '/includes/language.php';
require NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/global.php';

$domains = explode( ',', $global_config['my_domains'] );
if( ! in_array( NV_SERVER_NAME, $domains ) )
{
    $global_config['site_logo'] = 'images/logo.png';
    $global_config['site_url'] = NV_SERVER_PROTOCOL . '://' . $domains[0] . NV_SERVER_PORT;
    nv_info_die( $global_config['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], '', '', '', '' );
}

// Xac dinh duong dan thuc den thu muc upload
define( 'NV_UPLOADS_REAL_DIR', NV_ROOTDIR . '/' . NV_UPLOADS_DIR );

// vd: /ten_thu_muc_chua_site/
$global_config['cookie_path'] = $nv_Request->cookie_path;

// vd: .mydomain1.com
$global_config['cookie_domain'] = $nv_Request->cookie_domain;

// vd: http://mydomain1.com/ten_thu_muc_chua_site
$global_config['site_url'] = $nv_Request->site_url;

// vd: 'mydomain1.com,mydomain2.com'
$global_config['my_domains'] = $nv_Request->my_domains;

$sys_info['register_globals'] = $nv_Request->is_register_globals;
// 0 = khong, 1 = bat

$sys_info['magic_quotes_gpc'] = $nv_Request->is_magic_quotes_gpc;
// 0 = khong, 1 = co

// vd: D:/AppServ/www/ten_thu_muc_chua_site/sess/
$sys_info['sessionpath'] = $nv_Request->session_save_path;

// ten cua session
$client_info['session_id'] = $nv_Request->session_id;

// referer
$client_info['referer'] = $nv_Request->referer;

// 0 = referer tu ben ngoai site, 1 = referer noi bo, 2 = khong co referer
$client_info['is_myreferer'] = $nv_Request->referer_key;
// trang dang xem
$client_info['selfurl'] = $nv_Request->my_current_domain . $nv_Request->request_uri;

// Xac dinh co phai AJAX hay khong
if( preg_match( '/^[0-9]{10,}$/', $nv_Request->get_string( 'nocache', 'get', '' ) ) and $client_info['is_myreferer'] === 1 ) define( 'NV_IS_AJAX', true );

// Chan truy cap neu HTTP_USER_AGENT == 'none'
if( NV_USER_AGENT == 'none' and NV_ANTI_AGENT )
{
	trigger_error( 'We\'re sorry. The software you are using to access our website is not allowed. Some examples of this are e-mail harvesting programs and programs that will copy websites to your hard drive. If you feel you have gotten this message in error, please send an e-mail addressed to admin. Your I.P. address has been logged. Thanks.', 256 );
}

// xac dinh co phai User_Agent cua NukeViet hay khong
if( NV_USER_AGENT == 'NUKEVIET CMS ' . $global_config['version'] . '. Developed by VINADES. Url: http://nukeviet.vn. Code: ' . md5( $global_config['sitekey'] ) )
{
	define( 'NV_IS_MY_USER_AGENT', true );
}

// Xac dinh co phai la bot hay khong
$client_info['bot_info'] = nv_check_bot();
$client_info['is_bot'] = ( ! empty( $client_info['bot_info'] ) ) ? 1 : 0;

// Neu la bot va bot bi cam truy cap
if( $client_info['is_bot'] and empty( $client_info['bot_info']['allowed'] ) ) trigger_error( 'Sorry! Website does not support the bot', 256 );

// Xac dinh borwser cua client
if( $client_info['is_bot'] )
{
	$client_info['browser'] = array(
		'key' => 'Unknown',
		'name' => 'Unknown',
		'version' => 0
	);
}
else
{
	$client_info['browser'] = array_combine( array( 'key', 'name' ), explode( '|', nv_getBrowser( NV_USER_AGENT ) ) );
	if( preg_match( '/^([^0-9]+)([0-9]+)\.(.*)$/', $client_info['browser']['name'], $matches ) )
	{
		$client_info['browser']['version'] = ( int )$matches[2];
		unset( $matches );
	}
	else
	{
		$client_info['browser']['version'] = 0;
	}
}

// Xac dinh co phai truy cap bang mobile hay khong
$client_info['is_mobile'] = nv_checkmobile( NV_USER_AGENT );

// Ket noi voi class chong flood
if( $global_config['is_flood_blocker'] and ! $nv_Request->isset_request( 'admin', 'session' ) and //
( ! $nv_Request->isset_request( 'second', 'get' ) or ( $nv_Request->isset_request( 'second', 'get' ) and $client_info['is_myreferer'] != 1 ) ) )
{
	require NV_ROOTDIR . '/includes/core/flood_blocker.php';
}

// Xac dinh OS cua client
$client_info['client_os'] = $client_info['is_bot'] ? array( 'key' => 'Robot', 'name' => $client_info['bot_info']['name'] ) : array_combine( array( 'key', 'name' ), explode( '|', nv_getOs( NV_USER_AGENT ) ) );

// Captcha
if( $nv_Request->isset_request( 'scaptcha', 'get' ) )
{
	require NV_ROOTDIR . '/includes/core/captcha.php';
}
// Class ma hoa du lieu
require NV_ROOTDIR . '/includes/class/crypt.class.php';
$crypt = new nv_Crypt( $global_config['sitekey'], NV_CRYPT_SHA1 == 1 ? 'sha1' : 'md5' );
$global_config['ftp_user_pass'] = $crypt->aes_decrypt( nv_base64_decode( $global_config['ftp_user_pass'] ) );

if( isset( $nv_plugin_area[1] ) )
{
    // Kết nối với các plugin Trước khi kết nối CSDL
    foreach ( $nv_plugin_area[1] as $_fplugin )
    {
        include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
    }
}

// Bat dau phien lam viec cua MySQL
require NV_ROOTDIR . '/includes/class/db.class.php';
$db = new sql_db( $db_config );
if( empty( $db->connect ) )
{
	trigger_error( 'Sorry! Could not connect to data server', 256 );
}
unset( $db_config['dbpass'] );

// Ten cac table cua CSDL dung chung cho he thong
define( 'NV_AUTHORS_GLOBALTABLE', $db_config['prefix'] . '_authors' );
define( 'NV_GROUPS_GLOBALTABLE', $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_groups' );
define( 'NV_USERS_GLOBALTABLE', $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_users' );
define( 'NV_SESSIONS_GLOBALTABLE', $db_config['prefix'] . '_sessions' );
define( 'NV_LANGUAGE_GLOBALTABLE', $db_config['prefix'] . '_language' );

define( 'NV_CONFIG_GLOBALTABLE', $db_config['prefix'] . '_config' );
define( 'NV_CRONJOBS_GLOBALTABLE', $db_config['prefix'] . '_cronjobs' );

define( 'NV_UPLOAD_GLOBALTABLE', $db_config['prefix'] . '_upload' );
define( 'NV_BANNERS_GLOBALTABLE', $db_config['prefix'] . '_banners' );
define( 'NV_COUNTER_GLOBALTABLE', $db_config['prefix'] . '_counter' );

define( 'NV_PREFIXLANG', $db_config['prefix'] . '_' . NV_LANG_DATA );
define( 'NV_MODULES_TABLE', NV_PREFIXLANG . '_modules' );
define( 'NV_BLOCKS_TABLE', NV_PREFIXLANG . '_blocks' );
define( 'NV_MODFUNCS_TABLE', NV_PREFIXLANG . '_modfuncs' );

define( 'NV_SEARCHKEYS_TABLE', NV_PREFIXLANG . '_searchkeys' );
define( 'NV_REFSTAT_TABLE', NV_PREFIXLANG . '_referer_stats' );

$sql = "SELECT lang, module, config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='" . NV_LANG_DATA . "' or (lang='sys' AND module='site') ORDER BY module ASC";
$list = nv_db_cache( $sql, '', 'settings' );
foreach( $list as $row )
{
	if( ( $row['lang'] == NV_LANG_DATA and $row['module'] == 'global' ) or ( $row['lang'] == 'sys' AND $row['module'] == 'site' ) )
	{
		$global_config[$row['config_name']] = $row['config_value'];
	}
	else
	{
		$module_config[$row['module']][$row['config_name']] = $row['config_value'];
	}
}

$global_config['smtp_password'] = $crypt->aes_decrypt( nv_base64_decode( $global_config['smtp_password'] ) );
if( $sys_info['ini_set_support'] )
{
	ini_set( 'sendmail_from', $global_config['site_email'] );
}
if( ! isset( $global_config['upload_checking_mode'] ) or ! in_array( $global_config['upload_checking_mode'], array( 'mild', 'lite', 'none' ) ) )
{
	$global_config['upload_checking_mode'] = 'strong';
}
define( 'UPLOAD_CHECKING_MODE', $global_config['upload_checking_mode'] );

if( defined( 'NV_ADMIN' ) )
{
	if( ! in_array( NV_LANG_DATA, $global_config['allow_adminlangs'] ) )
	{
		if( $global_config['lang_multi'] )
		{
			$nv_Request->set_Cookie( 'data_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME );
		}
		Header( 'Location: ' . NV_BASE_ADMINURL );
		exit();
	}
	if( ! in_array( NV_LANG_INTERFACE, $global_config['allow_adminlangs'] ) )
	{
		if( $global_config['lang_multi'] )
		{
			$nv_Request->set_Cookie( 'int_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME );
		}
		Header( 'Location: ' . NV_BASE_ADMINURL );
		exit();
	}
}

// cronjobs
if( $nv_Request->isset_request( 'second', 'get' ) and $nv_Request->get_string( 'second', 'get' ) == 'cronjobs' )
{
	require NV_ROOTDIR . '/includes/core/cronjobs.php';
}

// Xac dinh kieu giao dien mac dinh
$global_config['current_theme_type'] = $nv_Request->get_string( 'nv' . NV_LANG_DATA . 'themever', 'cookie', '' );

// Kiem tra tu cach admin
if( defined( 'NV_IS_ADMIN' ) || defined( 'NV_IS_SPADMIN' ) )
{
	trigger_error( 'Hacking attempt', 256 );
}

// Kiem tra ton tai goi cap nhat va tu cach admin
$nv_check_update = file_exists( NV_ROOTDIR . '/install/update_data.php' );
define( 'ADMIN_LOGIN_MODE', $nv_check_update ? 1 : ( empty( $global_config['closed_site'] ) ? 3 : $global_config['closed_site'] ) );

$admin_cookie = $nv_Request->get_bool( 'admin', 'session', false );
if( ! empty( $admin_cookie ) )
{
	require NV_ROOTDIR . '/includes/core/admin_access.php';
	require NV_ROOTDIR . '/includes/core/is_admin.php';
}

if( defined( 'NV_IS_ADMIN' ) )
{
	// Buoc admin khai bao lai pass neu khong online trong khoang thoi gian nhat dinh
	if( empty( $admin_info['checkpass'] ) )
	{
		if( $nv_Request->isset_request( NV_ADMINRELOGIN_VARIABLE, 'get' ) and $nv_Request->get_int( NV_ADMINRELOGIN_VARIABLE, 'get' ) == 1 )
		{
			require NV_ROOTDIR . '/includes/core/admin_relogin.php';
			exit();
		}
	}
}
elseif( ! in_array( NV_LANG_DATA, $global_config['allow_sitelangs'] ) )
{
	$global_config['closed_site'] = 1;
}

// Dinh chi hoat dong cua site
if( $nv_check_update and ! defined( 'NV_IS_UPDATE' ) )
{
	// Dinh chi neu khong la admin toi cao
	if( ! defined( 'NV_ADMIN' ) and ! defined( 'NV_IS_GODADMIN' ) )
	{
		$disable_site_content = ( isset( $global_config['disable_site_content'] ) and ! empty( $global_config['disable_site_content'] ) ) ? $global_config['disable_site_content'] : $lang_global['disable_site_content'];
		nv_info_die( $global_config['site_description'], $lang_global['disable_site_title'], $disable_site_content, '', '', '', '' );
	}
}
elseif( ! defined( 'NV_ADMIN' ) and ! defined( 'NV_IS_ADMIN' ) )
{
	if( ! empty( $global_config['closed_site'] ) )
	{
		$disable_site_content = ( isset( $global_config['disable_site_content'] ) and ! empty( $global_config['disable_site_content'] ) ) ? $global_config['disable_site_content'] : $lang_global['disable_site_content'];
		nv_info_die( $global_config['site_description'], $lang_global['disable_site_title'], $disable_site_content, '', '', '', '' );
	}
	elseif( ! in_array( NV_LANG_DATA, $global_config['allow_sitelangs'] ) )
	{
		Header( 'Location: ' . NV_BASE_SITEURL );
		exit();
	}
	elseif( empty( $global_config['lang_multi'] ) and NV_LANG_DATA != $global_config['site_lang'] )
	{
		Header( 'Location: ' . NV_BASE_SITEURL );
		exit();
	}
}

unset( $nv_check_update );

define( 'PCLZIP_TEMPORARY_DIR', NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' );

if( isset( $nv_plugin_area[2] ) )
{
    // Kết nối với các plugin Trước khi gọi các module
    foreach ( $nv_plugin_area[2] as $_fplugin )
    {
        include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
    }
}