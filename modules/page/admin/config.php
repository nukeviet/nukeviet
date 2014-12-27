<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['config'];

$array_config = array();

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_config['viewtype'] = $nv_Request->get_int( 'viewtype', 'post', 0 );
	$array_config['facebookapi'] = $nv_Request->get_string( 'facebookapi', 'post', '' );

	$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_config SET config_value = :config_value WHERE config_name = :config_name');
	foreach( $array_config as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}

	nv_del_moduleCache( $module_name );

	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	die();
}

$array_config['viewtype'] = 0;
$array_config['facebookapi'] = '';

$sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$result = $db->query( $sql );
while( list( $c_config_name, $c_config_value ) = $result->fetch( 3 ) )
{
	$array_config[$c_config_name] = $c_config_value;
}

$xtpl = new XTemplate( 'config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config );

$view_array = array( $lang_module['config_view_type_0'], $lang_module['config_view_type_1'] );
foreach( $view_array as $key => $title )
{
	$xtpl->assign( 'VIEWTYPE', array( 'id' => $key, 'title' => $title, 'selected' => $array_config['viewtype'] == $key ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';