<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_SYSTEM' ) and ! defined( 'NV_ADMIN' ) and ! defined( 'NV_WYSIWYG' ) )
{
	Header( "Location: index.php" );
	exit();
}

define( 'NV_MAINFILE', true );

//Thoi gian bat dau phien lam viec
define( 'NV_START_TIME', array_sum( explode( " ", microtime() ) ) );

//Khong cho xac dinh tu do cac variables
$db_config = $global_config = $module_config = $client_info = $user_info = $admin_info = $sys_info = $lang_global = $lang_module = $rss = $nv_vertical_menu = $array_mod_title = $content_type = $blocks = $submenu = $select_options = $error_info = $rewrite = $countries = $newCountry = array();
$page_title = $key_words = $canonicalUrl = $mod_title = $editor_password = $my_head = $my_footer = $description = $contents = "";
$editor = $rewrite = false;

//Xac dinh thu muc goc cua site
define( 'NV_ROOTDIR', pathinfo( str_replace( DIRECTORY_SEPARATOR, '/', __file__ ), PATHINFO_DIRNAME ) );

$sys_info['disable_classes'] = ( ( $disable_classes = ini_get( "disable_classes" ) ) != "" and $disable_classes != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", $disable_classes ) ) : array();
$sys_info['disable_functions'] = ( ( $disable_functions = ini_get( "disable_functions" ) ) != "" and $disable_functions != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", $disable_functions ) ) : array();

if( extension_loaded( 'suhosin' ) )
{
	$sys_info['disable_functions'] = array_merge( $sys_info['disable_functions'], array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "suhosin.executor.func.blacklist" ) ) ) );
}
$sys_info['ini_set_support'] = ( function_exists( 'ini_set' ) and ! in_array( 'ini_set', $sys_info['disable_functions'] ) ) ? true : false;

//Ket noi voi cac file constants, config
require( NV_ROOTDIR . "/includes/constants.php" );
if( file_exists( NV_ROOTDIR . "/" . NV_CONFIG_FILENAME ) )
{
	require( realpath( NV_ROOTDIR . "/" . NV_CONFIG_FILENAME ) );
}
else
{
	if( file_exists( NV_ROOTDIR . '/install/index.php' ) )
	{
		$base_siteurl = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME );
		if( $base_siteurl == DIRECTORY_SEPARATOR ) $base_siteurl = '';
		if( ! empty( $base_siteurl ) ) $base_siteurl = str_replace( DIRECTORY_SEPARATOR, '/', $base_siteurl );
		if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/[\/]+$/", '', $base_siteurl );
		if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/^[\/]*(.*)$/", '/\\1', $base_siteurl );
		if( defined( 'NV_ADMIN' ) )
		{
			$base_siteurl = preg_replace( "#/" . NV_ADMINDIR . "(.*)$#", '', $base_siteurl );
		}
		if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "#/index\.php(.*)$#", '', $base_siteurl );
		Header( "Location: " . $base_siteurl . "/install/index.php" );
	}
	die();
}

require( NV_ROOTDIR . "/" . NV_DATADIR . "/config_global.php" );

// Xac dinh cac tags cho phep
$global_config['allowed_html_tags'] = array_map( "trim", explode( ',', NV_ALLOWED_HTML_TAGS ) );

//Kiem tra trang thai cua may chu, neu > 80 se thong bao "Server too busy. Please try again later"
if( $global_config['getloadavg'] ) require( NV_ROOTDIR . "/includes/getloadavg.php" );

$global_config['file_allowed_ext'] = ! empty( $global_config['file_allowed_ext'] ) ? explode( ",", $global_config['file_allowed_ext'] ) : array();
$global_config['forbid_extensions'] = ! empty( $global_config['forbid_extensions'] ) ? explode( ",", $global_config['forbid_extensions'] ) : array();
$global_config['forbid_mimes'] = ! empty( $global_config['forbid_mimes'] ) ? explode( ",", $global_config['forbid_mimes'] ) : array();

$search = array( '&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;' );
$replace = array( '&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '#', '%', '^', ':', '{', '}', '`', '~' );
$global_config['my_domains'] = str_replace( $search, $replace, $global_config['my_domains'] );
$global_config['cookie_prefix'] = str_replace( $search, $replace, $global_config['cookie_prefix'] );
$global_config['date_pattern'] = str_replace( $search, $replace, $global_config['date_pattern'] );
$global_config['ftp_path'] = str_replace( $search, $replace, $global_config['ftp_path'] );
$global_config['session_prefix'] = str_replace( $search, $replace, $global_config['session_prefix'] );
$global_config['statistics_timezone'] = str_replace( $search, $replace, $global_config['statistics_timezone'] );
$global_config['time_pattern'] = str_replace( $search, $replace, $global_config['time_pattern'] );
$global_config['upload_logo'] = str_replace( $search, $replace, $global_config['upload_logo'] );
$global_config['rewrite_endurl'] = str_replace( $search, $replace, $global_config['rewrite_endurl'] );
$global_config['rewrite_exturl'] = str_replace( $search, $replace, $global_config['rewrite_exturl'] );
$global_config['searchEngineUniqueID'] = str_replace( $search, $replace, $global_config['searchEngineUniqueID'] );
$global_config['site_timezone'] = str_replace( $search, $replace, $global_config['site_timezone'] );
unset( $search, $replace );

$global_config['allow_sitelangs'] = ! empty( $global_config['allow_sitelangs'] ) ? explode( ",", $global_config['allow_sitelangs'] ) : array();
$global_config['allow_adminlangs'] = ! empty( $global_config['allow_adminlangs'] ) ? explode( ",", $global_config['allow_adminlangs'] ) : array();
$global_config['openid_servers'] = ! empty( $global_config['openid_servers'] ) ? explode( ",", $global_config['openid_servers'] ) : array();
if( empty( $global_config['openid_servers'] ) ) $global_config['openid_mode'] = 0;
if( $global_config['is_user_forum'] )
{
	$forum_files = @scandir( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet' );
	if( ! empty( $forum_files ) and in_array( 'is_user.php', $forum_files ) and in_array( 'changepass.php', $forum_files ) and in_array( 'editinfo.php', $forum_files ) and in_array( 'login.php', $forum_files ) and in_array( 'logout.php', $forum_files ) and in_array( 'lostpass.php', $forum_files ) and in_array( 'register.php', $forum_files ) )
	{
		define( 'NV_IS_USER_FORUM', true );
	}
	else
	{
		$global_config['is_user_forum'] = 0;
	}
}

if( $global_config['openid_mode'] )
{
	define( 'NV_OPENID_ALLOWED', true );
	$openid_servers = array();
	require( NV_ROOTDIR . '/includes/openid.php' );
	$openid_servers = array_intersect_key( $openid_servers, array_flip( $global_config['openid_servers'] ) );
}

//Xac dinh IP cua client
require( NV_ROOTDIR . '/includes/class/ips.class.php' );
$ips = new ips();
$client_info['ip'] = $ips->remote_ip;
if( $client_info['ip'] == "none" ) die( 'Error: Your IP address is not correct' ); //Neu khong co IP
//define( 'NV_SERVER_IP', $ips->server_ip );
define( 'NV_FORWARD_IP', $ips->forward_ip );
define( 'NV_REMOTE_ADDR', $ips->remote_addr );
define( 'NV_CLIENT_IP', $client_info['ip'] );

//Xac dinh Quoc gia
require( NV_ROOTDIR . '/includes/countries.php' );
$client_info['country'] = nv_getCountry_from_cookie( $client_info['ip'] );

//Mui gio
require( NV_ROOTDIR . '/includes/timezone.php' );
define( 'NV_CURRENTTIME', isset( $_SERVER['REQUEST_TIME'] ) ? $_SERVER['REQUEST_TIME'] : time() );

$global_config['log_errors_list'] = NV_LOG_ERRORS_LIST;
$global_config['display_errors_list'] = NV_DISPLAY_ERRORS_LIST;
$global_config['send_errors_list'] = NV_SEND_ERRORS_LIST;
$global_config['error_log_path'] = NV_LOGS_DIR . '/error_logs';
$global_config['error_log_filename'] = NV_ERRORLOGS_FILENAME;
$global_config['error_log_fileext'] = NV_LOGS_EXT;

//Ket noi voi class Error_handler
require( NV_ROOTDIR . '/includes/class/error.class.php' );
$ErrorHandler = new Error( $global_config );
set_error_handler( array( &$ErrorHandler, 'error_handler' ) );

if( empty( $global_config['allow_sitelangs'] ) or empty( $global_config['allow_adminlangs'] ) )
{
	trigger_error( "Error! Language variables is empty!", 256 );
}

//Ket noi voi cac file cau hinh, function va template
require( NV_ROOTDIR . "/includes/ini.php" );
require( NV_ROOTDIR . '/includes/functions.php' );
require( NV_ROOTDIR . '/includes/core/theme_functions.php' );
require( NV_ROOTDIR . "/includes/class/xtemplate.class.php" );

$global_config['allow_request_mods'] = NV_ALLOW_REQUEST_MODS != '' ? array_map( "trim", explode( ",", NV_ALLOW_REQUEST_MODS ) ) : "request";
$global_config['request_default_mode'] = NV_REQUEST_DEFAULT_MODE != '' ? trim( NV_REQUEST_DEFAULT_MODE ) : 'request';
$global_config['XSS_replaceString'] = NV_XSS_REPLACESTRING != '' ? NV_XSS_REPLACESTRING : '';
$global_config['cookie_key'] = $global_config['sitekey'];
$global_config['cookie_secure'] = NV_COOKIE_SECURE;
$global_config['cookie_httponly'] = NV_COOKIE_HTTPONLY;
$global_config['session_save_path'] = NV_SESSION_SAVE_PATH;

//IP Ban
if( nv_is_banIp( $client_info['ip'] ) ) trigger_error( "Hi and Good-bye!!!", 256 );

//Chan proxy
if( $global_config['proxy_blocker'] != 0 )
{
	$client_info['is_proxy'] = $ips->nv_check_proxy();
	if( nv_is_blocker_proxy( $client_info['is_proxy'], $global_config['proxy_blocker'] ) )
	{
		trigger_error( 'ERROR: You are behind a proxy server. Please disconnect and come again!', 256 );
	}
}

//Xac dinh cac search_engine
$global_config['engine_allowed'] = array();
if( file_exists( NV_ROOTDIR . '/' . NV_DATADIR . '/search_engine.xml' ) )
{
	$global_config['engine_allowed'] = nv_object2array( simplexml_load_file( NV_ROOTDIR . '/' . NV_DATADIR . '/search_engine.xml' ) );
}

$language_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/langs.ini', true );
if( defined( 'NV_SYSTEM' ) )
{
	require( NV_ROOTDIR . '/includes/request_uri.php' );
}

//Ket noi voi class xu ly request
require( NV_ROOTDIR . '/includes/class/request.class.php' );
$nv_Request = new Request( $global_config, $client_info['ip'] );

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
define( 'NV_UPLOAD_MAX_FILESIZE', min( nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ), $global_config['nv_max_size'] ) );
define( 'NV_UPLOADS_REAL_DIR', NV_ROOTDIR . '/' . NV_UPLOADS_DIR ); //Xac dinh duong dan thuc den thu muc upload
define( 'NV_CACHE_PREFIX', md5( $global_config['sitekey'] . NV_BASE_SITEURL ) ); //Hau to cua file cache

//Ngon ngu
require( NV_ROOTDIR . '/includes/language.php' );
require( NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/global.php" );

if( NV_LANG_DATA == "vi" )
{
	require( NV_ROOTDIR . '/includes/core/amlich.php' );
}

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

if( preg_match( "/^[0-9]{10,}$/", $nv_Request->get_string( 'nocache', 'get', '' ) ) and //Xac dinh co phai AJAX hay khong
	$client_info['is_myreferer'] === 1 ) define( 'NV_IS_AJAX', true );

//Chan truy cap neu HTTP_USER_AGENT == 'none'
if( NV_USER_AGENT == "none" )
{
	trigger_error( 'We\'re sorry. The software you are using to access our website is not allowed. Some examples of this are e-mail harvesting programs and programs that will  copy websites to your hard drive. If you feel you have gotten this message  in error, please send an e-mail addressed to admin. Your I.P. address has been logged. Thanks.', 256 );
}

//xac dinh co phai User_Agent cua NukeViet hay khong
if( NV_USER_AGENT == 'NUKEVIET CMS ' . $global_config['version'] . '. Developed by VINADES. Url: http://nukeviet.vn. Code: ' . md5( $global_config['sitekey'] ) )
{
	define( 'NV_IS_MY_USER_AGENT', true );
}

//Xac dinh co phai la bot hay khong
$client_info['bot_info'] = nv_check_bot();
$client_info['is_bot'] = ( ! empty( $client_info['bot_info'] ) ) ? 1 : 0;

//Neu la bot va bot bi cam truy cap
if( $client_info['is_bot'] and empty( $client_info['bot_info']['allowed'] ) ) trigger_error( 'Sorry! Website does not support the bot', 256 );

//Xac dinh borwser cua client
if ( $client_info['is_bot'] )
{
	$client_info['browser'] = array(
		'key' => "Unknown",
		'name' => 'Unknown',
		'version' => 0 );
}
else
{
	$client_info['browser'] = array_combine( array( 'key', 'name' ), explode( "|", nv_getBrowser( NV_USER_AGENT, NV_ROOTDIR . '/includes/ini/br.ini' ) ) );
	preg_match( "/^([^0-9]+)([0-9]+)\.(.*)$/", $client_info['browser']['name'], $matches );
	$client_info['browser']['version'] = ( int )$matches[2];
	unset( $matches );
}

//Xac dinh co phai truy cap bang mobile hay khong
$client_info['is_mobile'] = nv_checkmobile( NV_ROOTDIR . '/includes/ini/mobile.ini' );
//Chan hoac chuyen huong neu truy cap tu mobile
//if (!empty($client_info['is_mobile']))
//	trigger_error('Sorry! Website does not support the browser your mobile', 256);

//Ket noi voi class chong flood
if( defined( 'NV_IS_FLOOD_BLOCKER' ) and NV_IS_FLOOD_BLOCKER == 1 and ! $nv_Request->isset_request( 'admin', 'session' ) and //
	( ! $nv_Request->isset_request( 'second', 'get' ) or ( $nv_Request->isset_request( 'second', 'get' ) and $client_info['is_myreferer'] != 1 ) ) )
{
	require( NV_ROOTDIR . '/includes/core/flood_blocker.php' );
}

//Xac dinh OS cua client
$client_info['client_os'] = $client_info['is_bot'] ? array( 'key' => "Robot", 'name' => $client_info['bot_info']['name'] ) : array_combine( array( 'key', 'name' ), explode( "|", nv_getOs( NV_USER_AGENT, NV_ROOTDIR . '/includes/ini/os.ini' ) ) );

//Captcha
if( $nv_Request->isset_request( 'scaptcha', 'get' ) )
{
	include_once( NV_ROOTDIR . "/includes/core/captcha.php" );
}

//Bat dau phien lam viec cua MySQL
require( NV_ROOTDIR . '/includes/class/mysql.class.php' );
$db_config['new_link'] = NV_MYSQL_NEW_LINK;
$db_config['persistency'] = NV_MYSQL_PERSISTENCY;
$db_config['collation'] = NV_MYSQL_COLLATION;
$db = new sql_db( $db_config );
if( ! empty( $db->error ) )
{
	$die = ! empty( $db->error['user_message'] ) ? $db->error['user_message'] : $db->error['message'];
	$die .= ! empty( $db->error['code'] ) ? ' (Code: ' . $db->error['code'] . ')' : '';
	trigger_error( $die, 256 );
}
unset( $db_config['dbpass'] );

//Ten cac table cua CSDL dung chung cho he thong
define( 'NV_AUTHORS_GLOBALTABLE', $db_config['prefix'] . '_authors' );
define( 'NV_GROUPS_GLOBALTABLE', $db_config['prefix'] . '_groups' );
define( 'NV_USERS_GLOBALTABLE', $db_config['prefix'] . '_users' );
define( 'NV_SESSIONS_GLOBALTABLE', $db_config['prefix'] . '_sessions' );
define( 'NV_LANGUAGE_GLOBALTABLE', $db_config['prefix'] . '_language' );

define( 'NV_BANNERS_CLIENTS_GLOBALTABLE', $db_config['prefix'] . '_banners_clients' );
define( 'NV_BANNERS_PLANS_GLOBALTABLE', $db_config['prefix'] . '_banners_plans' );
define( 'NV_BANNERS_ROWS_GLOBALTABLE', $db_config['prefix'] . '_banners_rows' );
define( 'NV_BANNERS_CLICK_GLOBALTABLE', $db_config['prefix'] . '_banners_click' );

define( 'NV_CONFIG_GLOBALTABLE', $db_config['prefix'] . '_config' );
define( 'NV_CRONJOBS_GLOBALTABLE', $db_config['prefix'] . '_cronjobs' );

define( 'NV_PREFIXLANG', $db_config['prefix'] . '_' . NV_LANG_DATA );
define( 'NV_MODULES_TABLE', NV_PREFIXLANG . '_modules' );
define( 'NV_BLOCKS_TABLE', NV_PREFIXLANG . '_blocks' );
define( 'NV_MODFUNCS_TABLE', NV_PREFIXLANG . '_modfuncs' );

define( 'NV_COUNTER_TABLE', NV_PREFIXLANG . '_counter' );
define( 'NV_SEARCHKEYS_TABLE', NV_PREFIXLANG . '_searchkeys' );
define( 'NV_REFSTAT_TABLE', NV_PREFIXLANG . '_referer_stats' );

$sql = "SELECT `module`, `config_name`, `config_value` FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang`='" . NV_LANG_DATA . "' ORDER BY `module` ASC";
$list = nv_db_cache( $sql, '', 'settings' );
foreach( $list as $row )
{
	if( $row['module'] == "global" )
	{
		$global_config[$row['config_name']] = $row['config_value'];
	}
	else
	{
		$module_config[$row['module']][$row['config_name']] = $row['config_value'];
	}
}

if( ! isset( $global_config['upload_checking_mode'] ) or ! in_array( $global_config['upload_checking_mode'], array(
	"mild",
	"lite",
	"none" ) ) )
{
	$global_config['upload_checking_mode'] = "strong";
}
define( 'UPLOAD_CHECKING_MODE', $global_config['upload_checking_mode'] );

//Cap nhat Country moi
if( ! empty( $newCountry ) )
{
	if( $db->sql_query( "INSERT INTO `" . $db_config['prefix'] . "_ipcountry` VALUES (" . $newCountry['ip_from'] . ", " . $newCountry['ip_to'] . ", '" . $newCountry['code'] . "', '" . $newCountry['ip_file'] . "', " . NV_CURRENTTIME . ")" ) )
	{
		$time_del = NV_CURRENTTIME - 604800;
		$db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_ipcountry` WHERE `ip_file`='" . $newCountry['ip_file'] . "' AND `country`='ZZ' AND `time` < " . $time_del );
		$result = $db->sql_query( "SELECT `ip_from`, `ip_to`, `country` FROM `" . $db_config['prefix'] . "_ipcountry` WHERE `ip_file`='" . $newCountry['ip_file'] . "'" );
		$array_ip_file = array();
		while( $row = $db->sql_fetch_assoc( $result ) )
		{
			$array_ip_file[] = $row['ip_from'] . " => array(" . $row['ip_to'] . ", '" . $row['country'] . "')";
		}
		file_put_contents( NV_ROOTDIR . "/" . NV_DATADIR . "/ip_files/" . $newCountry['ip_file'] . ".php", "<?php\n\n\$ranges = array(" . implode( ', ', $array_ip_file ) . ");\n\n?>", LOCK_EX );
	}
	unset( $newCountry, $time_del, $array_ip_file, $result, $row );
}

if( $global_config['is_url_rewrite'] )
{
	$check_rewrite_file = nv_check_rewrite_file();
	if( $check_rewrite_file )
	{
		require( NV_ROOTDIR . "/includes/rewrite.php" );
	}
	else
	{
		require( NV_ROOTDIR . "/includes/rewrite_index.php" );
	}
	if( preg_match( "/^" . nv_preg_quote( NV_BASE_SITEURL . "index.php?" ) . "/i", $_SERVER['REQUEST_URI'] ) )
	{
		$url_rewrite = nv_url_rewrite( $_SERVER['REQUEST_URI'], true );
		if( $url_rewrite != $_SERVER['REQUEST_URI'] )
		{
			Header( "Location: " . $url_rewrite );
			die();
		}
	}
	elseif( $global_config['rewrite_optional'] && preg_match( "/^" . nv_preg_quote( NV_BASE_SITEURL . NV_LANG_DATA . "/" ) . "/i", $_SERVER['REQUEST_URI'] ) )
	{
		$url_rewrite = preg_replace( "/^" . nv_preg_quote( NV_BASE_SITEURL . NV_LANG_DATA . "/" ) . "(.*)$/", NV_BASE_SITEURL . "\\1", $_SERVER['REQUEST_URI'] );
		Header( "Location: " . $url_rewrite );
		die();
	}
	elseif( $global_config['rewrite_optional'] && preg_match( "/^" . nv_preg_quote( NV_BASE_SITEURL . "index.php/" . NV_LANG_DATA . "/" ) . "/i", $_SERVER['REQUEST_URI'] ) )
	{
		$url_rewrite = preg_replace( "/^" . nv_preg_quote( NV_BASE_SITEURL . "index.php/" . NV_LANG_DATA . "/" ) . "(.*)$/", NV_BASE_SITEURL . "\\1", $_SERVER['REQUEST_URI'] );
		Header( "Location: " . $url_rewrite );
		die();
	}
}
elseif( empty( $global_config['lang_multi'] ) and $global_config['rewrite_optional'] )
{
	require( NV_ROOTDIR . "/includes/rewrite_language.php" );

	if( preg_match( "/^" . nv_preg_quote( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA ) . "/i", $_SERVER['REQUEST_URI'] ) )
	{
		$url_rewrite = nv_url_rewrite( $_SERVER['REQUEST_URI'], true );
		if( $url_rewrite != $_SERVER['REQUEST_URI'] )
		{
			Header( "Location: " . $url_rewrite );
			die();
		}
	}
}

if( defined( 'NV_ADMIN' ) )
{
	if( ! in_array( NV_LANG_DATA, $global_config['allow_adminlangs'] ) )
	{
		if( $global_config['lang_multi'] )
		{
			$nv_Request->set_Cookie( 'data_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME );
		}
		Header( "Location: " . NV_BASE_ADMINURL );
		exit();
	}
	if( ! in_array( NV_LANG_INTERFACE, $global_config['allow_adminlangs'] ) )
	{
		if( $global_config['lang_multi'] )
		{
			$nv_Request->set_Cookie( 'int_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME );
		}
		Header( "Location: " . NV_BASE_ADMINURL );
		exit();
	}
}

//Class ma hoa du lieu $crypt->hash($data)
require( NV_ROOTDIR . '/includes/class/crypt.class.php' );
$crypt = new nv_Crypt( $global_config['sitekey'], NV_CRYPT_SHA1 == 1 ? 'sha1' : 'md5' );
if( ! $crypt->_otk ) trigger_error( "sitekey not declared", 256 );

//cronjobs
if( $nv_Request->isset_request( 'second', 'get' ) and $nv_Request->get_string( 'second', 'get' ) == "cronjobs" )
{
	include_once( NV_ROOTDIR . "/includes/core/cronjobs.php" );
}

// Xac dinh kieu giao dien mac dinh
$global_config['array_theme_type'] = array_filter( array_map( 'trim', explode( ',', NV_THEME_TYPE ) ) );
$global_config['current_theme_type'] = $nv_Request->get_string( 'nv' . NV_LANG_DATA . 'themever', 'cookie', '' );

//Kiem tra tu cach admin
if( defined( 'NV_IS_ADMIN' ) || defined( 'NV_IS_SPADMIN' ) )
{
	trigger_error( "Hacking attempt", 256 );
}

// Kiem tra ton tai goi cap nhat va tu cach admin
$nv_check_update = file_exists( NV_ROOTDIR . '/install/update_data.php' );
define( 'ADMIN_LOGIN_MODE', $nv_check_update ? 1 : ( empty( $global_config['closed_site'] ) ? 3 : $global_config['closed_site'] ) );

$admin_cookie = $nv_Request->get_bool( 'admin', 'session', false );
if( ! empty( $admin_cookie ) )
{
	require( NV_ROOTDIR . "/includes/core/admin_access.php" );
	require( NV_ROOTDIR . "/includes/core/is_admin.php" );
}

if( defined( "NV_IS_ADMIN" ) )
{
	//Buoc admin khai bao lai pass neu khong online trong khoang thoi gian nhat dinh
	if( empty( $admin_info['checkpass'] ) )
	{
		if( $nv_Request->isset_request( NV_ADMINRELOGIN_VARIABLE, 'get' ) and $nv_Request->get_int( NV_ADMINRELOGIN_VARIABLE, 'get' ) == 1 )
		{
			require( NV_ROOTDIR . "/includes/core/admin_relogin.php" );
			exit();
		}
	}
}
elseif( ! in_array( NV_LANG_DATA, $global_config['allow_sitelangs'] ) )
{
	$global_config['closed_site'] = 1;
}

//Dinh chi hoat dong cua site
if( $nv_check_update and ! defined( 'NV_IS_UPDATE' ) )
{
	// Dinh chi neu khong la admin toi cao
	if( ! defined( 'NV_ADMIN' ) and ! defined( 'NV_IS_GODADMIN' ) )
	{
		$disable_site_content = ( isset( $global_config['disable_site_content'] ) and ! empty( $global_config['disable_site_content'] ) ) ? $global_config['disable_site_content'] : $lang_global['disable_site_content'];
		nv_info_die( $global_config['site_description'], $lang_global['disable_site_title'], $disable_site_content );
	}
}
elseif( ! defined( 'NV_ADMIN' ) and ! defined( "NV_IS_ADMIN" ) )
{
	if( ! empty( $global_config['closed_site'] ) )
	{
		$disable_site_content = ( isset( $global_config['disable_site_content'] ) and ! empty( $global_config['disable_site_content'] ) ) ? $global_config['disable_site_content'] : $lang_global['disable_site_content'];
		nv_info_die( $global_config['site_description'], $lang_global['disable_site_title'], $disable_site_content );
	}
	elseif( ! in_array( NV_LANG_DATA, $global_config['allow_sitelangs'] ) )
	{
		Header( "Location: " . NV_BASE_SITEURL );
		exit();
	}
	elseif( empty( $global_config['lang_multi'] ) and NV_LANG_DATA != $global_config['site_lang'] )
	{
		Header( "Location: " . NV_BASE_SITEURL );
		exit();
	}
}

unset( $nv_check_update );

define( 'PCLZIP_TEMPORARY_DIR', NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' );

?>