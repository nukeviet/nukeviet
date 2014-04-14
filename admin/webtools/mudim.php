<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 28/10/2012, 14:51
 */

if( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['mudim'];

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_config_global = array();

	$array_config_global['mudim_active'] = $nv_Request->get_int( 'mudim_active', 'post' );
	$array_config_global['mudim_showpanel'] = $nv_Request->get_int( 'mudim_showpanel', 'post' );
	$array_config_global['mudim_method'] = $nv_Request->get_int( 'mudim_method', 'post' );
	$array_config_global['mudim_displaymode'] = $nv_Request->get_int( 'mudim_displaymode', 'post' );

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

$mudim_active_modes = array(
	'0' => $lang_module['mudim_active_no'],
	'1' => $lang_module['mudim_active_all'],
	'2' => $lang_module['mudim_active_site'],
	'3' => $lang_module['mudim_active_admin']
);
$mudim_method = array(
	'0' => 'OFF',
	'1' => 'VNI',
	'2' => 'TELEX',
	'3' => 'VIQR',
	'4' => 'COMBINED',
	'5' => 'AUTO'
);
$mudim_displaymode = array( '0' => 'Hiển thị đầy đủ', '1' => 'Hiển thị tối giản' );

$xtpl = new XTemplate( 'mudim.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'MUDIM_SHOWPANEL', ( $global_config['mudim_showpanel'] ) ? ' checked="checked"' : '' );

foreach( $mudim_active_modes as $key => $value )
{
	$xtpl->assign( 'MUDIM_ACTIVE_OP', $key );
	$xtpl->assign( 'MUDIM_ACTIVE_SELECTED', ( $key == $global_config['mudim_active'] ) ? "selected='selected'" : "" );
	$xtpl->assign( 'MUDIM_ACTIVE_TEXT', $value );
	$xtpl->parse( 'main.mudim_active' );
}

foreach( $mudim_method as $key => $value )
{
	$xtpl->assign( 'MUDIM_METHOD_OP', $key );
	$xtpl->assign( 'MUDIM_METHOD_SELECTED', ( $key == $global_config['mudim_method'] ) ? "selected='selected'" : "" );
	$xtpl->assign( 'MUDIM_METHOD_TEXT', $value );
	$xtpl->parse( 'main.mudim_method' );
}

foreach( $mudim_displaymode as $key => $value )
{
	$xtpl->assign( 'MUDIM_DISPLAYMODE_OP', $key );
	$xtpl->assign( 'MUDIM_DISPLAYMODE_SELECTED', ( $key == $global_config['mudim_displaymode'] ) ? "selected='selected'" : "" );
	$xtpl->assign( 'MUDIM_DISPLAYMODE_TEXT', $value );
	$xtpl->parse( 'main.mudim_displaymode' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';