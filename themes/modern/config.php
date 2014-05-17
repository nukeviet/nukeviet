<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 17 Apr 2014 04:03:46 GMT
 */

if ( !defined( 'NV_IS_FILE_THEMES' ) )	die( 'Stop!!!' );

$config_theme = array();
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$config_theme['show_logo'] = $nv_Request->get_int( 'show_logo', 'post', 0 );
	$config_theme['show_site_name'] = $nv_Request->get_int( 'show_site_name', 'post', 0 );
	$config_theme['module_in_menu'] = $nv_Request->get_typed_array( 'module_in_menu', 'post', 'string' );
	$config_value = serialize( $config_theme );

	if ( isset( $module_config['themes'][$selectthemes] ) )
	{
		$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = :config_name AND lang = '" . NV_LANG_DATA . "' AND module='themes'" );
	}
	else
	{
		$sth = $db->prepare( "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . NV_LANG_DATA . "', 'themes', :config_name, :config_value)" );
	}

	$sth->bindParam( ':config_name', $selectthemes, PDO::PARAM_STR );
	$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR, strlen( $config_value ) );
	$sth->execute();

	nv_del_moduleCache( 'settings' );

	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&selectthemes=' . $selectthemes . '&rand=' . nv_genpass() );
	die();
}
elseif ( isset( $module_config['themes'][$selectthemes] ) )
{
	$config_theme = unserialize( $module_config['themes'][$selectthemes] );
}
else
{
	require NV_ROOTDIR . '/themes/' . $selectthemes . '/config_default.php';
}

$config_theme['show_logo'] = ($config_theme['show_logo']) ? ' checked="checked"' : '';
$config_theme['show_site_name'] = ($config_theme['show_site_name']) ? ' checked="checked"' : '';

$xtpl = new XTemplate( 'config.tpl', NV_ROOTDIR . '/themes/' . $selectthemes . '/system/' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'SELECTTHEMES', $selectthemes );
$xtpl->assign( 'CONFIG_THEME', $config_theme );

foreach( $site_mods as $modname => $modvalues )
{
	$modvalues['modname'] = $modname;
	$modvalues['checked'] = in_array($modname, $config_theme['module_in_menu']) ? ' checked="checked"' : '';
	$xtpl->assign( 'MODULE', $modvalues );
	$xtpl->parse( 'main.module' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );