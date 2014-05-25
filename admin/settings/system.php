<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$adminThemes = array( '' );
$adminThemes = array_merge( $adminThemes, nv_scandir( NV_ROOTDIR . '/themes', $global_config['check_theme_admin'] ) );
unset( $adminThemes[0] );

$closed_site_Modes = array();
$closed_site_Modes[0] = $lang_module['closed_site_0'];
if( defined( 'NV_IS_GODADMIN' ) )
{
	$closed_site_Modes[1] = $lang_module['closed_site_1'];
}
$closed_site_Modes[2] = $lang_module['closed_site_2'];
$closed_site_Modes[3] = $lang_module['closed_site_3'];

$allow_sitelangs = array();
foreach( $global_config['allow_sitelangs'] as $lang_i )
{
	if( file_exists( NV_ROOTDIR . '/language/' . $lang_i . '/global.php' ) )
	{
		$allow_sitelangs[] = $lang_i;
	}
}

$timezone_array = array_keys( $nv_parse_ini_timezone );

$errormess = '';
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_config_site = array();

	$admin_theme = $nv_Request->get_string( 'admin_theme', 'post' );
	if( ! empty( $admin_theme ) and in_array( $admin_theme, $adminThemes ) )
	{
		$array_config_site['admin_theme'] = $admin_theme;
	}

	$closed_site = $nv_Request->get_int( 'closed_site', 'post' );
	if( isset( $closed_site_Modes[$closed_site] ) )
	{
		$array_config_site['closed_site'] = $closed_site;
	}

	$site_email = nv_substr( $nv_Request->get_title( 'site_email', 'post', '', 1 ), 0, 255 );
	if( nv_check_valid_email( $site_email ) == '' )
	{
		$array_config_site['site_email'] = $site_email;
	}

	$preg_replace = array( 'pattern' => "/[^a-z\-\_\.\,\;\:\@\/\\s]/i", 'replacement' => '' );
	$array_config_site['date_pattern'] = nv_substr( $nv_Request->get_title( 'date_pattern', 'post', '', 0, $preg_replace ), 0, 255 );
	$array_config_site['time_pattern'] = nv_substr( $nv_Request->get_title( 'time_pattern', 'post', '', 0, $preg_replace ), 0, 255 );

	$array_config_site['searchEngineUniqueID'] = $nv_Request->get_title( 'searchEngineUniqueID', 'post', '' );
	if( preg_match( '/[^a-zA-Z0-9\:\-\_\.]/', $array_config_site['searchEngineUniqueID'] ) ) $array_config_site['searchEngineUniqueID'] = '';

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name" );
	foreach( $array_config_site as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR, 30 );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}

	if( defined( 'NV_IS_GODADMIN' ) )
	{
		$array_config_global = array();
		$site_timezone = $nv_Request->get_title( 'site_timezone', 'post', '', 0 );
		if( empty( $site_timezone ) or ( ! empty( $site_timezone ) and ( in_array( $site_timezone, $timezone_array ) or $site_timezone == 'byCountry' ) ) )
		{
			$array_config_global['site_timezone'] = $site_timezone;
		}
		$my_domains = $nv_Request->get_title( 'my_domains', 'post', '' );
		$array_config_global['my_domains'] = array( NV_SERVER_NAME );

		if( ! empty( $my_domains ) )
		{
			$my_domains = array_map( 'trim', explode( ',', $my_domains ) );
			foreach( $my_domains as $dm )
			{
				$dm = preg_replace( '/^(http|https|ftp|gopher)\:\/\//', '', $dm );
				$dm = preg_replace( '/^([^\/]+)\/*(.*)$/', '\\1', $dm );
				$dm = nv_check_domain( nv_strtolower( $dm ) );
				if( ! empty( $dm ) )
				{
					$array_config_global['my_domains'][] = $dm;
				}
			}
		}
		$array_config_global['my_domains'] = array_unique( $array_config_global['my_domains'] );
		$array_config_global['my_domains'] = implode( ',', $array_config_global['my_domains'] );

		$array_config_global['gzip_method'] = $nv_Request->get_int( 'gzip_method', 'post' );
		$array_config_global['lang_multi'] = $nv_Request->get_int( 'lang_multi', 'post' );
		$array_config_global['optActive'] = $nv_Request->get_int( 'optActive', 'post' );

		$site_lang = $nv_Request->get_title( 'site_lang', 'post', '', 1 );
		if( ! empty( $site_lang ) and in_array( $site_lang, $allow_sitelangs ) )
		{
			$array_config_global['site_lang'] = $site_lang;
		}

		if( $array_config_global['lang_multi'] == 0 )
		{
			$array_config_global['rewrite_optional'] = $nv_Request->get_int( 'rewrite_optional', 'post', 0 );
			$array_config_global['lang_geo'] = 0;
			$array_config_global['rewrite_op_mod'] = $nv_Request->get_title( 'rewrite_op_mod', 'post' );
			if( ! isset( $site_mods[$array_config_global['rewrite_op_mod']] ) OR $array_config_global['rewrite_optional'] ==0 )
			{
				$array_config_global['rewrite_op_mod'] = '';
			}
		}
		else
		{
			$array_config_global['rewrite_optional'] = 0;
			$array_config_global['lang_geo'] = $nv_Request->get_int( 'lang_geo', 'post', 0 );
			$array_config_global['rewrite_op_mod'] = '';
		}

        $error_send_email = nv_substr( $nv_Request->get_title( 'error_send_email', 'post', '', 1 ), 0, 255 );
        if( nv_check_valid_email( $error_send_email ) == '' )
        {
            $array_config_global['error_send_email'] = $error_send_email;
        }

		$cdn_url = rtrim( $nv_Request->get_string( 'cdn_url', 'post' ), '/' );
		$array_config_global['cdn_url'] = ( nv_is_url( $cdn_url ) ) ? $cdn_url : '';

		$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name" );
		foreach( $array_config_global as $config_name => $config_value )
		{
			$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR, 30 );
			$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
			$sth->execute();
		}

		nv_save_file_config_global();

		$array_config_rewrite = array(
			'rewrite_optional' => $array_config_global['rewrite_optional'],
			'rewrite_endurl' => $global_config['rewrite_endurl'],
			'rewrite_exturl' => $global_config['rewrite_exturl'],
			'rewrite_op_mod' => $array_config_global['rewrite_op_mod']
		);
		$rewrite = nv_rewrite_change( $array_config_rewrite );
		if( empty( $rewrite[0] ) )
		{
			$errormess .= sprintf( $lang_module['err_writable'], $rewrite[1] );
		}
	}
	else
	{
		nv_delete_all_cache( false );
	}
	if( empty( $errormess ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
		exit();
	}
}

$page_title = $lang_module['global_config'];

$optActive_Modes = array(
	'0' => $lang_module['optActive_no'],
	'1' => $lang_module['optActive_all'],
	'2' => $lang_module['optActive_site'],
	'3' => $lang_module['optActive_admin']
);

$xtpl = new XTemplate( 'system.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $global_config );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'CDNDL', md5( $global_config['sitekey'] . $admin_info['admin_id'] . session_id() ) );

if( defined( 'NV_IS_GODADMIN' ) )
{
    $xtpl->parse( 'main.error_send_email' );

	$result = $db->query( "SELECT config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='sys' AND module='global'" );
	while( list( $c_config_name, $c_config_value ) = $result->fetch( 3 ) )
	{
		$array_config_global[$c_config_name] = $c_config_value;
	}

	$lang_multi = $array_config_global['lang_multi'];
	$xtpl->assign( 'CHECKED_GZIP_METHOD', ( $array_config_global['gzip_method'] ) ? ' checked="checked"' : '' );
	$xtpl->assign( 'CHECKED_LANG_MULTI', ( $array_config_global['lang_multi'] ) ? ' checked="checked"' : '' );

	$xtpl->assign( 'MY_DOMAINS', $array_config_global['my_domains'] );

	if( $lang_multi == 0 )
	{
		$xtpl->assign( 'CHECKED2', ( $array_config_global['rewrite_optional'] == 1 ) ? ' checked ' : '' );

		foreach( $site_mods as $mod => $row )
		{
			$xtpl->assign( 'MODE_VALUE', $mod );
			$xtpl->assign( 'MODE_SELECTED', ( $mod == $array_config_global['rewrite_op_mod'] ) ? "selected='selected'" : "" );
			$xtpl->assign( 'MODE_NAME', $row['custom_title'] );
			$xtpl->parse( 'main.system.rewrite_optional.rewrite_op_mod' );
		}

		$xtpl->parse( 'main.system.rewrite_optional' );
	}
	if( sizeof( $global_config['allow_sitelangs'] ) > 1 )
	{
		foreach( $allow_sitelangs as $lang_i )
		{
			$xtpl->assign( 'LANGOP', $lang_i );
			$xtpl->assign( 'SELECTED', ( $lang_i == $array_config_global['site_lang'] ) ? "selected='selected'" : "" );
			$xtpl->assign( 'LANGVALUE', $language_array[$lang_i]['name'] );
			$xtpl->parse( 'main.system.lang_multi.site_lang_option' );
		}
		if( $lang_multi )
		{
			$xtpl->assign( 'CONFIG_LANG_GEO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=language&' . NV_OP_VARIABLE . '=countries' );
			$xtpl->assign( 'CHECKED_LANG_GEO', ( $array_config_global['lang_geo'] == 1 ) ? ' checked ' : '' );
			$xtpl->parse( 'main.system.lang_multi.lang_geo' );
		}
		$xtpl->parse( 'main.system.lang_multi' );
	}

	foreach( $optActive_Modes as $key => $value )
	{
		$xtpl->assign( 'OPTACTIVE_OP', $key );
		$xtpl->assign( 'OPTACTIVE_SELECTED', ( $key == $array_config_global['optActive'] ) ? "selected='selected'" : "" );
		$xtpl->assign( 'OPTACTIVE_TEXT', $value );
		$xtpl->parse( 'main.system.optActive' );
	}

	$xtpl->assign( 'TIMEZONEOP', 'byCountry' );
	$xtpl->assign( 'TIMEZONESELECTED', ( $array_config_global['site_timezone'] == 'byCountry' ) ? "selected='selected'" : "" );
	$xtpl->assign( 'TIMEZONELANGVALUE', $lang_module['timezoneByCountry'] );
	$xtpl->parse( 'main.system.opsite_timezone' );

	sort( $timezone_array );
	foreach( $timezone_array as $site_timezone_i )
	{
		$xtpl->assign( 'TIMEZONEOP', $site_timezone_i );
		$xtpl->assign( 'TIMEZONESELECTED', ( $site_timezone_i == $array_config_global['site_timezone'] ) ? "selected='selected'" : "" );
		$xtpl->assign( 'TIMEZONELANGVALUE', $site_timezone_i );
		$xtpl->parse( 'main.system.opsite_timezone' );
	}
	$xtpl->parse( 'main.system' );
}

if( $errormess != '' )
{
	$xtpl->assign( 'ERROR', $errormess );
	$xtpl->parse( 'main.error' );
}

foreach( $adminThemes as $name )
{
	$xtpl->assign( 'THEME_NAME', $name );
	$xtpl->assign( 'THEME_SELECTED', ( $name == $global_config['admin_theme'] ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.admin_theme' );
}

foreach( $closed_site_Modes as $value => $name )
{
	$xtpl->assign( 'MODE_VALUE', $value );
	$xtpl->assign( 'MODE_NAME', $name );
	$xtpl->assign( 'MODE_SELECTED', ( $value == $global_config['closed_site'] ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.closed_site_mode' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $content );
include NV_ROOTDIR . '/includes/footer.php';