<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

$submit = $nv_Request->get_string( 'submit', 'post' );

if( $submit )
{
	$array_config_global = array();
	$array_config_global['autocheckupdate'] = $nv_Request->get_int( 'autocheckupdate', 'post', 0 );
	$array_config_global['autoupdatetime'] = $nv_Request->get_int( 'autoupdatetime', 'post', 24 );

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name" );
	foreach( $array_config_global as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR, 30 );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}

	nv_save_file_config_global();
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	exit();
}

$page_title = $lang_module['config'];
$lang_module['hour'] = $lang_global['hour'];

$xtpl = new XTemplate( 'config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'AUTOCHECKUPDATE', ( $global_config['autocheckupdate'] ) ? ' checked="checked"' : '' );

for( $i = 1; $i <= 100; ++$i )
{
	$xtpl->assign( 'VALUE', $i );
	$xtpl->assign( 'TEXT', $i );
	$xtpl->assign( 'SELECTED', ( $i == $global_config['autoupdatetime'] ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.updatetime' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $content );
include NV_ROOTDIR . '/includes/footer.php';