<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$errormess = '';
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$preg_replace = array( 'pattern' => "/[^a-zA-Z0-9\_]/", 'replacement' => '' );

	$array_config_global = array();
	$array_config_global['cookie_prefix'] = nv_substr( $nv_Request->get_title( 'cookie_prefix', 'post', '', 0, $preg_replace ), 0, 255);
	$array_config_global['session_prefix'] = nv_substr( $nv_Request->get_title( 'session_prefix', 'post', '', 0, $preg_replace ), 0, 255);
	$array_config_global['cookie_secure'] = ( int )$nv_Request->get_bool( 'cookie_secure', 'post', 0 );
	$array_config_global['cookie_httponly'] = ( int )$nv_Request->get_bool( 'cookie_httponly', 'post', 0 );

	foreach( $array_config_global as $config_name => $config_value )
	{
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', '" . mysql_real_escape_string( $config_name ) . "', " . $db->dbescape( $config_value ) . ")" );
	}

	$array_config_define = array();
	$array_config_define['nv_live_cookie_time'] = 86400 * $nv_Request->get_int( 'nv_live_cookie_time', 'post', 1 );
	$array_config_define['nv_live_session_time'] = 60 * $nv_Request->get_int( 'nv_gfx_width', 'post', 0 );
	foreach( $array_config_define as $config_name => $config_value )
	{
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'define', '" . mysql_real_escape_string( $config_name ) . "', " . $db->dbescape( $config_value ) . ")" );
	}
	nv_save_file_config_global();

	if( empty( $errormess ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
		exit();
	}
}

$page_title = $lang_module['variables'];

$xtpl = new XTemplate( "variables.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file . "" );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'DATA', $global_config );
$xtpl->assign( 'NV_LIVE_COOKIE_TIME', round( NV_LIVE_COOKIE_TIME / 86400 ) );
$xtpl->assign( 'NV_LIVE_SESSION_TIME', round( NV_LIVE_SESSION_TIME / 60 ) );
$xtpl->assign( 'CHECKBOX_COOKIE_SECURE', ( $global_config['cookie_secure'] == 1 ) ? ' checked="checked"' : '' );
$xtpl->assign( 'CHECKBOX_COOKIE_HTTPONLY', ( $global_config['cookie_httponly'] == 1 ) ? ' checked="checked"' : '' );

if( $errormess != '' )
{
	$xtpl->assign( 'ERROR', $errormess );
	$xtpl->parse( 'main.error' );
}
$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_admin_theme( $content );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>