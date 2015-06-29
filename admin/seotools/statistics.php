<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_SEOTOOLS' ) ) die( 'Stop!!!' );

$timezone_array = array_keys( $nv_parse_ini_timezone );
$googleAnalyticsMethod = array( 'classic' => 'Classic Analytics', 'universal' => 'Universal Analytics' );

$array_config_global = array();

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_config_global['online_upd'] = $nv_Request->get_int( 'online_upd', 'post' );
	$array_config_global['statistic'] = $nv_Request->get_int( 'statistic', 'post' );

	$statistics_timezone = nv_substr( $nv_Request->get_title( 'statistics_timezone', 'post', '', 0 ), 0, 255 );

	if( ! empty( $statistics_timezone ) and in_array( $statistics_timezone, $timezone_array ) )
	{
		$array_config_global['statistics_timezone'] = $statistics_timezone;
	}
	else
	{
		$array_config_global['statistics_timezone'] = NV_SITE_TIMEZONE_NAME;
	}

	$array_config_global['googleAnalyticsID'] = nv_substr( $nv_Request->get_title( 'googleAnalyticsID', 'post', '', 1 ), 0, 20 );

	if( ! preg_match( '/^UA-\d{4,}-\d+$/', $array_config_global['googleAnalyticsID'] ) ) $array_config_global['googleAnalyticsID'] = '';

	$array_config_global['googleAnalyticsSetDomainName'] = $nv_Request->get_int( 'googleAnalyticsSetDomainName', 'post' );
	$array_config_global['googleAnalyticsMethod'] = $nv_Request->get_title( 'googleAnalyticsMethod', 'post', '', 1 );
	if( ! isset( $googleAnalyticsMethod[$array_config_global['googleAnalyticsMethod']] ) )
	{
		$googleAnalyticsMethod['googleAnalyticsMethod'] = 'classic';
	}

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name" );
	foreach( $array_config_global as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}

	nv_delete_all_cache( false );

	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	exit();
}

$page_title = $lang_module['global_statistics'];

$array_config_global['online_upd'] = ( $global_config['online_upd'] ) ? ' checked="checked"' : '';
$array_config_global['statistic'] = ( $global_config['statistic'] ) ? ' checked="checked"' : '';
$array_config_global['googleAnalyticsID'] = $global_config['googleAnalyticsID'];

$xtpl = new XTemplate( 'statistics.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
sort( $timezone_array );
foreach( $timezone_array as $site_timezone_i )
{
	$xtpl->assign( 'TIMEZONEOP', $site_timezone_i );
	$xtpl->assign( 'TIMEZONESELECTED', ( $site_timezone_i == $global_config['statistics_timezone'] ) ? ' selected="selected"' : '' );
	$xtpl->assign( 'TIMEZONELANGVALUE', $site_timezone_i );
	$xtpl->parse( 'main.timezone' );
}

for( $i = 0; $i < 3; ++$i )
{
	$xtpl->assign( 'GOOGLEANALYTICSSETDOMAINNAME_SELECTED', ( $global_config['googleAnalyticsSetDomainName'] == $i ) ? ' selected="selected"' : '' );
	$xtpl->assign( 'GOOGLEANALYTICSSETDOMAINNAME_VALUE', $i );
	$xtpl->assign( 'GOOGLEANALYTICSSETDOMAINNAME_TITLE', $lang_module['googleAnalyticsSetDomainName_' . $i] );
	$xtpl->parse( 'main.googleAnalyticsSetDomainName' );
}
foreach( $googleAnalyticsMethod as $key => $title )
{
	$xtpl->assign( 'GOOGLEANALYTICSMETHOD_SELECTED', ( $global_config['googleAnalyticsMethod'] == $key ) ? ' selected="selected"' : '' );
	$xtpl->assign( 'GOOGLEANALYTICSMETHOD_VALUE', $key );
	$xtpl->assign( 'GOOGLEANALYTICSMETHOD_TITLE', $title );
	$xtpl->parse( 'main.googleAnalyticsMethod' );
}
$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $content );
include NV_ROOTDIR . '/includes/footer.php';