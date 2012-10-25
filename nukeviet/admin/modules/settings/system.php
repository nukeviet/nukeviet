<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$adminThemes = array( '' );
$adminThemes = array_merge( $adminThemes, nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme_admin'] ) );
unset( $adminThemes[0] );

$closed_site_Modes = array( //
	'0' => $lang_module['closed_site_0'], //
	'1' => $lang_module['closed_site_1'], //
	'2' => $lang_module['closed_site_2'], //
	'3' => $lang_module['closed_site_3'] //
);

$captcha_array = array( //
	0 => $lang_module['captcha_0'], //
	1 => $lang_module['captcha_1'], //
	2 => $lang_module['captcha_2'], //
	3 => $lang_module['captcha_3'], //
	4 => $lang_module['captcha_4'], //
	5 => $lang_module['captcha_5'], //
	6 => $lang_module['captcha_6'], //
	7 => $lang_module['captcha_7'] //
);

$captcha_type_array = array( //
	0 => $lang_module['captcha_type_0'], //
	1 => $lang_module['captcha_type_1'] //
);

$allow_sitelangs = array();
foreach( $global_config['allow_sitelangs'] as $lang_i )
{
	if( file_exists( NV_ROOTDIR . "/language/" . $lang_i . "/global.php" ) )
	{
		$allow_sitelangs[] = $lang_i;
	}
}

$timezone_array = array_keys( nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/timezone.ini', true ) );

$proxy_blocker_array = array( //
	0 => $lang_module['proxy_blocker_0'], //
	1 => $lang_module['proxy_blocker_1'], //
	2 => $lang_module['proxy_blocker_2'], //
	3 => $lang_module['proxy_blocker_3']
);

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_config_global = array();

	$admin_theme = $nv_Request->get_string( 'admin_theme', 'post' );
	if( ! empty( $admin_theme ) and in_array( $admin_theme, $adminThemes ) )
	{
		$array_config_global['admin_theme'] = $admin_theme;
	}

	$closed_site = $nv_Request->get_int( 'closed_site', 'post' );
	if( isset( $closed_site_Modes[$closed_site] ) )
	{
		$array_config_global['closed_site'] = $closed_site;
	}

	$gfx_chk = $nv_Request->get_int( 'gfx_chk', 'post' );
	if( isset( $captcha_array[$gfx_chk] ) )
	{
		$array_config_global['gfx_chk'] = $gfx_chk;
	}
	$captcha_type = $nv_Request->get_int( 'captcha_type', 'post' );
	if( isset( $captcha_type_array[$captcha_type] ) )
	{
		$array_config_global['captcha_type'] = $captcha_type;
	}

	$site_email = filter_text_input( 'site_email', 'post', '', 1, 255 );
	if( nv_check_valid_email( $site_email ) == '' )
	{
		$array_config_global['site_email'] = $site_email;
	}

	$error_send_email = filter_text_input( 'error_send_email', 'post', '', 1, 255 );
	if( nv_check_valid_email( $error_send_email ) == '' )
	{
		$array_config_global['error_send_email'] = $error_send_email;
	}

	$array_config_global['site_phone'] = filter_text_input( 'site_phone', 'post', '', 1, 255 );

	$site_lang = filter_text_input( 'site_lang', 'post', '', 1, 255 );
	if( ! empty( $site_lang ) and in_array( $site_lang, $allow_sitelangs ) )
	{
		$array_config_global['site_lang'] = $site_lang;
	}

	$site_timezone = filter_text_input( 'site_timezone', 'post', '', 0, 255 );
	if( empty( $site_timezone ) or ( ! empty( $site_timezone ) and ( in_array( $site_timezone, $timezone_array ) or $site_timezone == "byCountry" ) ) )
	{
		$array_config_global['site_timezone'] = $site_timezone;
	}

	$array_config_global['date_pattern'] = filter_text_input( 'date_pattern', 'post', '', 1, 255 );
	$array_config_global['time_pattern'] = filter_text_input( 'time_pattern', 'post', '', 1, 255 );

	$my_domains = filter_text_input( 'my_domains', 'post', '' );
	$array_config_global['my_domains'] = array( NV_SERVER_NAME );
	
	if( ! empty( $my_domains ) )
	{
		$my_domains = array_map( "trim", explode( ",", $my_domains ) );
		foreach( $my_domains as $dm )
		{
			if( ! empty( $dm ) )
			{
				$dm2 = ( ! preg_match( "/^(http|https|ftp|gopher)\:\/\//", $dm ) ) ? "http://" . $dm : $dm;
				
				if( nv_is_url( $dm2 ) or $dm == "localhost" or filter_var($dm, FILTER_VALIDATE_IP))
				{
					$array_config_global['my_domains'][] = $dm;
				}
			}
		}
	}
	$array_config_global['my_domains'] = array_unique( $array_config_global['my_domains'] );
	$array_config_global['my_domains'] = implode( ",", $array_config_global['my_domains'] );

	$array_config_global['cookie_prefix'] = filter_text_input( 'cookie_prefix', 'post', '', 1, 255 );
	$array_config_global['session_prefix'] = filter_text_input( 'session_prefix', 'post', '', 1, 255 );
	$array_config_global['searchEngineUniqueID'] = filter_text_input( 'searchEngineUniqueID', 'post', '' );
	
	if( preg_match( "/[^a-zA-Z0-9\:\-\_\.]/", $array_config_global['searchEngineUniqueID'] ) ) $array_config_global['searchEngineUniqueID'] = "";

	$array_config_global['gzip_method'] = $nv_Request->get_int( 'gzip_method', 'post' );
	$array_config_global['lang_multi'] = $nv_Request->get_int( 'lang_multi', 'post' );
	$array_config_global['optActive'] = $nv_Request->get_int( 'optActive', 'post' );
	$array_config_global['getloadavg'] = $nv_Request->get_int( 'getloadavg', 'post' );
	$array_config_global['str_referer_blocker'] = $nv_Request->get_int( 'str_referer_blocker', 'post' );
	$array_config_global['is_url_rewrite'] = $nv_Request->get_int( 'is_url_rewrite', 'post', 0 );

	$proxy_blocker = $nv_Request->get_int( 'proxy_blocker', 'post' );
	
	if( isset( $proxy_blocker_array[$proxy_blocker] ) )
	{
		$array_config_global['proxy_blocker'] = $proxy_blocker;
	}

	if( $array_config_global['lang_multi'] == 0 )
	{
		$array_config_global['rewrite_optional'] = $nv_Request->get_int( 'rewrite_optional', 'post', 0 );
		$array_config_global['lang_geo'] = 0;
	}
	else
	{
		$array_config_global['rewrite_optional'] = 0;
		$array_config_global['lang_geo'] = $nv_Request->get_int( 'lang_geo', 'post', 0 );
	}

	foreach( $array_config_global as $config_name => $config_value )
	{
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', '" . mysql_real_escape_string( $config_name ) . "', " . $db->dbescape( $config_value ) . ")" );
	}

	nv_save_file_config_global();

	if( $global_config['is_url_rewrite'] != $array_config_global['is_url_rewrite'] or $global_config['rewrite_optional'] != $array_config_global['rewrite_optional'] )
	{
		$array_config_global['rewrite_endurl'] = $global_config['rewrite_endurl'];
		$array_config_global['rewrite_exturl'] = $global_config['rewrite_exturl'];
		nv_rewrite_change( $array_config_global );
	}

	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	exit();
}

$page_title = $lang_module['global_config'];

$optActive_Modes = array(
	'0' => $lang_module['optActive_no'],
	'1' => $lang_module['optActive_all'],
	'2' => $lang_module['optActive_site'],
	'3' => $lang_module['optActive_admin']
);

$sql = "SELECT `config_name`, `config_value` FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang`='sys' AND `module`='global'";
$result = $db->sql_query( $sql );

while( list( $c_config_name, $c_config_value ) = $db->sql_fetchrow( $result ) )
{
	$array_config_global[$c_config_name] = $c_config_value;
}

$lang_multi = $array_config_global['lang_multi'];
$array_config_global['gzip_method'] = ( $array_config_global['gzip_method'] ) ? ' checked="checked"' : '';
$array_config_global['lang_multi'] = ( $array_config_global['lang_multi'] ) ? ' checked="checked"' : '';
$array_config_global['str_referer_blocker'] = ( $array_config_global['str_referer_blocker'] ) ? ' checked="checked"' : '';
$array_config_global['getloadavg'] = ( $array_config_global['getloadavg'] ) ? ' checked="checked"' : '';
$array_config_global['searchEngineUniqueID'] = isset( $array_config_global['searchEngineUniqueID'] ) ? $array_config_global['searchEngineUniqueID'] : "";

$xtpl = new XTemplate( "system.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file . "" );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config_global );

foreach( $adminThemes as $name )
{
	$xtpl->assign( 'THEME_NAME', $name );
	$xtpl->assign( 'THEME_SELECTED', ( $name == $array_config_global['admin_theme'] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.admin_theme' );
}

foreach( $closed_site_Modes as $value => $name )
{
	$xtpl->assign( 'MODE_VALUE', $value );
	$xtpl->assign( 'MODE_NAME', $name );
	$xtpl->assign( 'MODE_SELECTED', ( $value == $array_config_global['closed_site'] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.closed_site_mode' );
}

foreach( $captcha_array as $gfx_chk_i => $gfx_chk_lang )
{
	$xtpl->assign( 'GFX_CHK_SELECTED', ( $array_config_global['gfx_chk'] == $gfx_chk_i ) ? ' selected="selected"' : '' );
	$xtpl->assign( 'GFX_CHK_VALUE', $gfx_chk_i );
	$xtpl->assign( 'GFX_CHK_TITLE', $gfx_chk_lang );
	$xtpl->parse( 'main.opcaptcha' );
}

foreach( $captcha_type_array as $captcha_type_i => $captcha_type_lang )
{
	$xtpl->assign( 'CAPTCHA_TYPE_SELECTED', ( $array_config_global['captcha_type'] == $captcha_type_i ) ? ' selected="selected"' : '' );
	$xtpl->assign( 'CAPTCHA_TYPE_VALUE', $captcha_type_i );
	$xtpl->assign( 'CAPTCHA_TYPE_TITLE', $captcha_type_lang );
	$xtpl->parse( 'main.captcha_type' );
}

foreach( $proxy_blocker_array as $proxy_blocker_i => $proxy_blocker_v )
{
	$xtpl->assign( 'PROXYSELECTED', ( $array_config_global['proxy_blocker'] == $proxy_blocker_i ) ? ' selected="selected"' : '' );
	$xtpl->assign( 'PROXYOP', $proxy_blocker_i );
	$xtpl->assign( 'PROXYVALUE', $proxy_blocker_v );
	$xtpl->parse( 'main.proxy_blocker' );
}

$xtpl->assign( 'CHECKED1', ( $array_config_global['is_url_rewrite'] == 1 ) ? ' checked ' : '' );

if( $lang_multi == 0 )
{
	$xtpl->assign( 'CHECKED2', ( $array_config_global['rewrite_optional'] == 1 ) ? ' checked ' : '' );
	$xtpl->parse( 'main.rewrite_optional' );
}
if( $lang_multi and sizeof( $global_config['allow_sitelangs'] ) > 1 )
{
	$xtpl->assign( 'CONFIG_LANG_GEO', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=language&op&' . NV_OP_VARIABLE . '=countries' );
	$xtpl->assign( 'CHECKED_LANG_GEO', ( $array_config_global['lang_geo'] == 1 ) ? ' checked ' : '' );

	foreach( $allow_sitelangs as $lang_i )
	{
		$xtpl->assign( 'LANGOP', $lang_i );
		$xtpl->assign( 'SELECTED', ( $lang_i == $array_config_global['site_lang'] ) ? "selected='selected'" : "" );
		$xtpl->assign( 'LANGVALUE', $language_array[$lang_i]['name'] );
		$xtpl->parse( 'main.lang_multi.site_lang_option' );
	}
	$xtpl->parse( 'main.lang_multi' );
}

foreach( $optActive_Modes as $key => $value )
{
	$xtpl->assign( 'OPTACTIVE_OP', $key );
	$xtpl->assign( 'OPTACTIVE_SELECTED', ( $key == $array_config_global['optActive'] ) ? "selected='selected'" : "" );
	$xtpl->assign( 'OPTACTIVE_TEXT', $value );
	$xtpl->parse( 'main.optActive' );
}

$xtpl->assign( 'TIMEZONEOP', "byCountry" );
$xtpl->assign( 'TIMEZONESELECTED', ( $array_config_global['site_timezone'] == "byCountry" ) ? "selected='selected'" : "" );
$xtpl->assign( 'TIMEZONELANGVALUE', $lang_module['timezoneByCountry'] );
$xtpl->parse( 'main.opsite_timezone' );

sort( $timezone_array );
foreach( $timezone_array as $site_timezone_i )
{
	$xtpl->assign( 'TIMEZONEOP', $site_timezone_i );
	$xtpl->assign( 'TIMEZONESELECTED', ( $site_timezone_i == $array_config_global['site_timezone'] ) ? "selected='selected'" : "" );
	$xtpl->assign( 'TIMEZONELANGVALUE', $site_timezone_i );
	$xtpl->parse( 'main.opsite_timezone' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $content );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>