<?php

/**
 * @Project NUKEVIET 3.5
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 19/12/2012, 10:36
 */

$sitekey = "";
// giá trị của $global_config['sitekey'] trong file /config.php
$ip_update = "";
// IP của máy tính thực hiện nâng cấp

define( 'NV_ADMIN', true );
define( 'NV_MAINFILE', true );

//Thoi gian bat dau phien lam viec
define( 'NV_START_TIME', array_sum( explode( " ", microtime( ) ) ) );

//Khong cho xac dinh tu do cac variables
$db_config = $global_config = $module_config = $client_info = $user_info = $admin_info = $sys_info = $lang_global = $lang_module = $rss = $nv_vertical_menu = $array_mod_title = $content_type = $submenu = $select_options = $error_info = $countries = $newCountry = array( );
$page_title = $key_words = $canonicalUrl = $mod_title = $editor_password = $my_head = $my_footer = $description = $contents = "";
$editor = false;

$sys_info['disable_classes'] = (($disable_classes = ini_get( "disable_classes" )) != "" and $disable_classes != false) ? array_map( 'trim', preg_split( "/[\s,]+/", $disable_classes ) ) : array( );
$sys_info['disable_functions'] = (($disable_functions = ini_get( "disable_functions" )) != "" and $disable_functions != false) ? array_map( 'trim', preg_split( "/[\s,]+/", $disable_functions ) ) : array( );

if( extension_loaded( 'suhosin' ) )
{
	$sys_info['disable_functions'] = array_merge( $sys_info['disable_functions'], array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "suhosin.executor.func.blacklist" ) ) ) );
}

$sys_info['ini_set_support'] = (function_exists( 'ini_set' ) and ! in_array( 'ini_set', $sys_info['disable_functions'] )) ? true : false;

//Xac dinh thu muc goc cua site
define( 'NV_ROOTDIR', pathinfo( str_replace( DIRECTORY_SEPARATOR, '/', __file__ ), PATHINFO_DIRNAME ) );

//Ket noi voi cac file constants, config
require_once ( realpath( NV_ROOTDIR . "/install/config.php" ));
require (NV_ROOTDIR . "/includes/constants.php");

if( file_exists( NV_ROOTDIR . "/" . NV_CONFIG_FILENAME ) )
{
	require ( realpath( NV_ROOTDIR . "/" . NV_CONFIG_FILENAME ));
}
else
{
	die( 'sys not install' );
}
if( $global_config['sitekey'] != $sitekey OR empty( $global_config['sitekey'] ) )
{
	die( 'error sitekey config' );

}

require (NV_ROOTDIR . "/" . NV_DATADIR . "/config_global.php");

$search = array(
	'&amp;',
	'&#039;',
	'&quot;',
	'&lt;',
	'&gt;',
	'&#x005C;',
	'&#x002F;',
	'&#40;',
	'&#41;',
	'&#42;',
	'&#91;',
	'&#93;',
	'&#33;',
	'&#x3D;',
	'&#x23;',
	'&#x25;',
	'&#x5E;',
	'&#x3A;',
	'&#x7B;',
	'&#x7D;',
	'&#x60;',
	'&#x7E;'
);
$replace = array(
	'&',
	'\'',
	'"',
	'<',
	'>',
	'\\',
	'/',
	'(',
	')',
	'*',
	'[',
	']',
	'!',
	'=',
	'#',
	'%',
	'^',
	':',
	'{',
	'}',
	'`',
	'~'
);
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

if( ! is_array( $global_config['allow_sitelangs'] ) )
{
	$global_config['allow_sitelangs'] = ! empty( $global_config['allow_sitelangs'] ) ? explode( ",", $global_config['allow_sitelangs'] ) : array( );
	$global_config['allow_adminlangs'] = ! empty( $global_config['allow_adminlangs'] ) ? explode( ",", $global_config['allow_adminlangs'] ) : array( );
	$global_config['openid_servers'] = ! empty( $global_config['openid_servers'] ) ? explode( ",", $global_config['openid_servers'] ) : array( );
}

// Xac dinh cac tags cho phep
$global_config['allowed_html_tags'] = array_map( "trim", explode( ',', NV_ALLOWED_HTML_TAGS ) );

//Xac dinh IP cua client
require (NV_ROOTDIR . '/includes/class/ips.class.php');
$ips = new ips( );
$client_info['ip'] = $ips->remote_ip;
if( $client_info['ip'] == "none" )
	die( 'Error: Your IP address is not correct' );

//Neu khong co IP
//define( 'NV_SERVER_IP', $ips->server_ip );
define( 'NV_FORWARD_IP', $ips->forward_ip );
define( 'NV_REMOTE_ADDR', $ips->remote_addr );
define( 'NV_CLIENT_IP', $client_info['ip'] );
if( NV_CLIENT_IP != $ip_update )
{
	die( 'Error: ip update is not correct: ' . NV_CLIENT_IP );
}

//Xac dinh Quoc gia
require (NV_ROOTDIR . '/includes/countries.php');
$client_info['country'] = nv_getCountry_from_cookie( $client_info['ip'] );

//Mui gio
require (NV_ROOTDIR . '/includes/timezone.php');
define( 'NV_CURRENTTIME', isset( $_SERVER['REQUEST_TIME'] ) ? $_SERVER['REQUEST_TIME'] : time( ) );

$global_config['log_errors_list'] = NV_LOG_ERRORS_LIST;
$global_config['display_errors_list'] = NV_DISPLAY_ERRORS_LIST;
$global_config['send_errors_list'] = NV_SEND_ERRORS_LIST;
$global_config['error_log_path'] = NV_LOGS_DIR . '/error_logs';
$global_config['error_log_filename'] = NV_ERRORLOGS_FILENAME;
$global_config['error_log_fileext'] = NV_LOGS_EXT;

//Ket noi voi class Error_handler
require (NV_ROOTDIR . '/includes/class/error.class.php');
$ErrorHandler = new Error( $global_config );
set_error_handler( array(
	&$ErrorHandler,
	'error_handler'
) );

//Ket noi voi cac file cau hinh, function va template
require (NV_ROOTDIR . '/includes/ini.php');
require (NV_ROOTDIR . '/includes/utf8/' . $sys_info['string_handler'] . '_string_handler.php');
require (NV_ROOTDIR . '/includes/utf8/utf8_functions.php');
require (NV_ROOTDIR . '/includes/core/filesystem_functions.php');
require (NV_ROOTDIR . '/includes/core/cache_functions.php');
require (NV_ROOTDIR . '/includes/functions.php');
require (NV_ROOTDIR . '/includes/core/theme_functions.php');
require (NV_ROOTDIR . "/includes/class/xtemplate.class.php");

$global_config['allow_request_mods'] = NV_ALLOW_REQUEST_MODS != '' ? array_map( "trim", explode( ",", NV_ALLOW_REQUEST_MODS ) ) : "request";
$global_config['request_default_mode'] = NV_REQUEST_DEFAULT_MODE != '' ? trim( NV_REQUEST_DEFAULT_MODE ) : 'request';
$global_config['session_save_path'] = NV_SESSION_SAVE_PATH;

$language_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/langs.ini', true );

//Ket noi voi class xu ly request
require (NV_ROOTDIR . '/includes/class/request.class.php');
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
define( 'NV_EOL', (strtoupper( substr( PHP_OS, 0, 3 ) == 'WIN' ) ? "\r\n" : (strtoupper( substr( PHP_OS, 0, 3 ) == 'MAC' ) ? "\r" : "\n")) );
//Ngat dong
define( 'NV_UPLOADS_REAL_DIR', NV_ROOTDIR . '/' . NV_UPLOADS_DIR );
//Xac dinh duong dan thuc den thu muc upload
define( 'NV_CACHE_PREFIX', md5( $global_config['sitekey'] . NV_BASE_SITEURL ) );
//Hau to cua file cache

//Ngon ngu
require (NV_ROOTDIR . '/includes/language.php');
require (NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/global.php");

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
	trigger_error( 'We\'re sorry. The software you are using to access our website is not allowed. Some examples of this are e-mail harvesting programs and programs that will  copy websites to your hard drive. If you feel you have gotten this message  in error, please send an e-mail addressed to admin. Your I.P. address has been logged. Thanks.', 256 );
}

//Captcha
if( $nv_Request->isset_request( 'scaptcha', 'get' ) )
{
	include_once (NV_ROOTDIR . "/includes/core/captcha.php");
}

//Class ma hoa du lieu
require (NV_ROOTDIR . '/includes/class/crypt.class.php');
$crypt = new nv_Crypt( $global_config['sitekey'], NV_CRYPT_SHA1 == 1 ? 'sha1' : 'md5' );

//Bat dau phien lam viec cua MySQL
require (NV_ROOTDIR . '/includes/class/mysql.class.php');
$db_config['new_link'] = NV_MYSQL_NEW_LINK;
$db_config['persistency'] = NV_MYSQL_PERSISTENCY;
$db_config['collation'] = NV_MYSQL_COLLATION;
if( $db_config['dbhost'] == "localhost" )
{
	$db_config['dbhost'] = "127.0.0.1";
}
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

//2) Thay đổi bộ gõ mudim
$db->sql_query( "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` 
(`lang`, `module`, `config_name`, `config_value`) VALUES
('sys', 'global', 'mudim_displaymode', '1'),
('sys', 'global', 'mudim_method', '4'),
('sys', 'global', 'mudim_showpanel', '1'),
('sys', 'global', 'mudim_active', '1')" );

//3) Xóa getloadavg.php, bởi nó ít tác dụng mà khi bật lên làm chậm site
$db->sql_query( "DELETE FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang` = 'sys' AND `module` = 'global' AND `config_name` = 'getloadavg'" );

//4) Thay đổi giao diện admin
$db->sql_query( "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_config` (`config`, `content`, `edit_time`) VALUES('access_admin', 'a:6:{s:12:\"access_addus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:14:\"access_waiting\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_editus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:12:\"access_delus\";a:2:{i:1;b:1;i:2;b:1;}s:13:\"access_passus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_groups\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}}', 1355894514)" );

//8) Tùy biến trường dữ liệu thành viên
$db->sql_query( "CREATE TABLE IF NOT EXISTS `" . NV_USERS_GLOBALTABLE . "_field` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(25) NOT NULL,
  `weight` int(10) unsigned NOT NULL DEFAULT '1',
  `field_type` enum('textbox','textarea','editor','select','radio','checkbox','multiselect') NOT NULL DEFAULT 'textbox',
  `field_choices` mediumtext NOT NULL,
  `match_type` enum('none','alphanumeric','email','url','regex','callback') NOT NULL DEFAULT 'none',
  `match_regex` varchar(250) NOT NULL DEFAULT '',
  `func_callback` varchar(75) NOT NULL DEFAULT '',
  `min_length` bigint(20) unsigned NOT NULL DEFAULT '0',
  `max_length` bigint(20) unsigned NOT NULL DEFAULT '0',
  `required` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `show_register` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_editable` enum('yes','once','never') NOT NULL DEFAULT 'yes',
  `show_profile` tinyint(4) NOT NULL DEFAULT '1',
  `class` varchar(50) NOT NULL,
  `language` text NOT NULL,
  `default_value` varchar(255) NOT NULL DEFAULT '',  
  PRIMARY KEY (`fid`),
  UNIQUE KEY `field` (`field`)
) ENGINE=MyISAM" );

$db->sql_query( "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_field` (`fid`, `field`, `weight`, `field_type`, `field_choices`, `match_type`, `match_regex`, `func_callback`, `max_length`, `required`, `show_register`, `user_editable`, `show_profile`, `class`, `language`) VALUES
(1, 'website', 1, 'textbox', '', 'callback', '', 'nv_is_url', 255, 0, 0, 'yes', 1, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:7:\"Website\";i:1;s:0:\"\";}}'),
(2, 'location', 2, 'textbox', '', 'none', '', '', 255, 0, 0, 'yes', 1, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:12:\"Địa chỉ\";i:1;s:0:\"\";}}'),
(3, 'yim', 3, 'textbox', '', 'none', '', '', 40, 0, 0, 'yes', 1, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:18:\"Tài khoản Yahoo\";i:1;s:0:\"\";}}'),
(4, 'telephone', 4, 'textbox', '', 'regex', '^[a-zA-Z0-9-_.,]{3,20}$', '', 100, 0, 0, 'yes', 1, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:15:\"Điện thoại\";i:1;s:0:\"\";}}'),
(5, 'fax', 5, 'textbox', '', 'regex', '^[a-zA-Z0-9-_.,]{3,20}$', '', 100, 0, 0, 'yes', 1, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:3:\"Fax\";i:1;s:0:\"\";}}'),
(6, 'mobile', 6, 'textbox', '', 'regex', '^[a-zA-Z0-9-_.,]{3,20}$', '', 100, 0, 0, 'yes', 1, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:10:\"Di động\";i:1;s:0:\"\";}}')" );

$db->sql_query( "CREATE TABLE IF NOT EXISTS `" . NV_USERS_GLOBALTABLE . "_info` (
  `userid` mediumint(8) unsigned NOT NULL,
  `website` varchar(100) NOT NULL DEFAULT '',
  `location` varchar(100) NOT NULL DEFAULT '',
  `yim` varchar(100) NOT NULL DEFAULT '',
  `telephone` varchar(100) NOT NULL DEFAULT '',
  `fax` varchar(100) NOT NULL DEFAULT '',
  `mobile` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8" );

$is = $db->sql_query( "INSERT INTO  `" . NV_USERS_GLOBALTABLE . "_info` SELECT `userid`, `website`, `location`, `yim`, `telephone`, `fax`, `mobile` FROM `" . NV_USERS_GLOBALTABLE . "`" );
if( $is )
{
	$db->sql_query( "ALTER TABLE `" . NV_USERS_GLOBALTABLE . "`
	  DROP `website`,
	  DROP `location`,
	  DROP `yim`,
	  DROP `telephone`,
	  DROP `fax`,
	  DROP `mobile`" );
}
$db->sql_query( "ALTER TABLE `" . NV_USERS_GLOBALTABLE . "_reg` ADD `users_info` MEDIUMTEXT NOT NULL" );

//9) Cấu hình đăng ký thành viên
$db->sql_query( "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES
('sys', 'global', 'cookie_httponly', '" . $global_config['cookie_httponly'] . "'),
('sys', 'global', 'admin_check_pass_time', '1800'),
('sys', 'global', 'adminrelogin_max', '3'),
('sys', 'global', 'cookie_secure', '" . $global_config['cookie_secure'] . "'),
('sys', 'global', 'nv_unick_type', '" . $global_config['nv_unick_type'] . "'),
('sys', 'global', 'nv_upass_type', '" . $global_config['nv_upass_type'] . "'),
('sys', 'global', 'is_flood_blocker', '1'),
('sys', 'global', 'max_requests_60', '40'),
('sys', 'global', 'max_requests_300', '150'),
('sys', 'global', 'nv_display_errors_list', '1'),
('sys', 'global', 'display_errors_list', '1'),
('sys', 'define', 'nv_unickmin', '" . NV_UNICKMIN . "'),
('sys', 'define', 'nv_unickmax', '" . NV_UNICKMAX . "'),
('sys', 'define', 'nv_upassmin', '" . NV_UPASSMIN . "'),
('sys', 'define', 'nv_upassmax', '" . NV_UPASSMAX . "'),
('sys', 'define', 'nv_gfx_num', '6'),
('sys', 'define', 'nv_gfx_width', '120'),
('sys', 'define', 'nv_gfx_height', '25'),
('sys', 'define', 'nv_max_width', '1500'),
('sys', 'define', 'nv_max_height', '1500'),
('sys', 'define', 'nv_live_cookie_time', '" . NV_LIVE_COOKIE_TIME . "'),
('sys', 'define', 'nv_anti_iframe', '".NV_ANTI_IFRAME."'),
('sys', 'define', 'nv_allowed_html_tags', '".NV_ALLOWED_HTML_TAGS."'),
('sys', 'define', 'nv_live_session_time', '0'),
('sys', 'define', 'nv_auto_resize', '1'),
('sys', 'define', 'cdn_url', ''),
('sys', 'define', 'dir_forum', '')" );

//11) Thay đổi CSDL module users để phù hợp với chức năng tìm lại mật khẩu
$db->sql_query( "ALTER TABLE `" . NV_CONFIG_GLOBALTABLE . "` CHANGE `passlostkey` `passlostkey` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''" );
$db->sql_query( "DELETE FROM `" . NV_CONFIG_GLOBALTABLE . "_config` WHERE `config` = 'registertype'" );

//12) Cập nhật chức năng không phân viết tài khoản chữ hoa và chữ thường.
$db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `md5username` = MD5(LOWER(`username`))" );

//13) Quản lý menu ngang trong admin:
$db->sql_query( "CREATE TABLE IF NOT EXISTS `" . NV_AUTHORS_GLOBALTABLE . "_module` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(55) NOT NULL,
  `lang_key` varchar(50) NOT NULL DEFAULT '',
  `weight` int(11) NOT NULL DEFAULT '0',
  `act_1` tinyint(4) NOT NULL DEFAULT '0',
  `act_2` tinyint(4) NOT NULL DEFAULT '1',
  `act_3` tinyint(4) NOT NULL DEFAULT '1',
  `checksum` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`mid`),
  UNIQUE KEY `module` (`module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8" );

$db->sql_query( "INSERT INTO `" . NV_AUTHORS_GLOBALTABLE . "_module` (`mid`, `module`, `lang_key`, `weight`, `act_1`, `act_2`, `act_3`, `checksum`) VALUES
(1, 'siteinfo', 'mod_siteinfo', 1, 1, 1, 1, ''),
(2, 'authors', 'mod_authors', 2, 1, 1, 1, ''),
(3, 'settings', 'mod_settings', 3, 1, 1, 0, ''),
(4, 'database', 'mod_database', 4, 1, 0, 0, ''),
(5, 'webtools', 'mod_webtools', 5, 1, 0, 0, ''),
(6, 'language', 'mod_language', 6, 1, 1, 0, ''),
(7, 'modules', 'mod_modules', 7, 1, 1, 0, ''),
(8, 'themes', 'mod_themes', 8, 1, 1, 0, ''),
(9, 'upload', 'mod_upload', 9, 1, 1, 1, '')" );

$result = $db->sql_query( "SELECT * FROM `" . NV_AUTHORS_GLOBALTABLE . "_module` ORDER BY `weight` ASC" );
while( $row = $db->sql_fetch_assoc( $result ) )
{
	$checksum = md5( $row['module'] . "#" . $row['act_1'] . "#" . $row['act_2'] . "#" . $row['act_3'] . "#" . $global_config['sitekey'] );
	$db->sql_query( "UPDATE `" . NV_AUTHORS_GLOBALTABLE . "_module` SET `checksum` = '" . $checksum . "' WHERE `mid` = " . $row['mid'] );
}
//14) Mã hóa mật khẩu smtp, ftp
$array_config = array( );
$array_config['ftp_user_pass'] = nv_base64_encode( $crypt->aes_encrypt( $global_config['ftp_user_pass'] ) );
$array_config['smtp_password'] = nv_base64_encode( $crypt->aes_encrypt( $global_config['smtp_password'] ) );
foreach( $array_config as $config_name => $config_value )
{
	$db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` 
				SET `config_value`=" . $db->dbescape_string( $config_value ) . " 
				WHERE `config_name` = " . $db->dbescape_string( $config_name ) . " 
				AND `lang` = 'sys' AND `module`='global' 
				LIMIT 1" );
}
//17) Thêm cấu hình thời gian lặp lại quá trình backup CSDL
$db->sql_query( "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'dump_interval', '1')" );

//20) Xóa các trường không sử dụng trong CSDL module bannner
$db->sql_query( "ALTER TABLE `".NV_BANNERS_ROWS_GLOBALTABLE."`
  DROP `file_name_tmp`,
  DROP `file_alt_tmp`,
  DROP `click_url_tmp`");
$db->sql_query( "ALTER TABLE `".NV_BANNERS_ROWS_GLOBALTABLE."` ADD `imageforswf` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `file_alt`");
$db->sql_query( "ALTER TABLE `".NV_BANNERS_ROWS_GLOBALTABLE."` ADD `target` VARCHAR( 10 ) NOT NULL DEFAULT '_blank' AFTER `click_url`");  

require_once (NV_ROOTDIR . "/modules/banners/admin.functions.php");
nv_CreateXML_bannerPlan();

require_once (NV_ROOTDIR . "/includes/core/admin_functions.php");
if( ! nv_save_file_config_global( ) )
{
	nv_deletefile( NV_ROOTDIR . "/" . NV_ADMINDIR . "/modules/settings/banip.php" );
	nv_deletefile( NV_ROOTDIR . "/" . NV_ADMINDIR . "/modules/settings/uploadconfig.php" );
	nv_deletefile( NV_ROOTDIR . "/includes/getloadavg.php" );
	nv_deletefile( NV_ROOTDIR . "/includes/core/wysyiwyg_functions.php" );
	nv_deletefile( NV_ROOTDIR . "/includes/ini/langs_multi.ini" );
	nv_deletefile( NV_ROOTDIR . "/includes/phpmailer/language", true );
	nv_deletefile( NV_ROOTDIR . "/js/jquery/jquery.validate.js" );
	nv_deletefile( NV_ROOTDIR . "/js/popcalendar", true );
	nv_deletefile( NV_ROOTDIR . "/themes/admin_default/modules/settings/banip.tpl" );
	nv_deletefile( NV_ROOTDIR . "/themes/admin_default/modules/settings/uploadconfig.tpl" );
	nv_deletefile( NV_ROOTDIR . "/themes/admin_default/modules/webtools/googlecode.tpl" );
	nv_deletefile( NV_ROOTDIR . "/themes/admin_default/modules/webtools/main.tpl" );

	require_once (NV_ROOTDIR . "/language/" . NV_LANG_DATA . "/install.php");
	die( sprintf( $lang_module['file_not_writable'], NV_DATADIR . "/config_global.php" ) );
}

$contents = "<meta http-equiv=\"refresh\" content=\"1;URL=" . NV_BASE_SITEURL . "update2.php?step=1\" />";

die( 'Thực hiện nâng cấp CSDL thành công, Chương trình sẽ chuyển sang bước nâng cấp CSDL module Upload' . $contents );
?>