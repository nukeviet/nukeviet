<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if( !defined( 'NV_ADMIN' ) or !defined( 'NV_MAINFILE' ) or !defined( 'NV_IS_MODADMIN' ) )
	die( 'Stop!!!' );

$allow_func = array( 'main', 'content', 'alias', 'change_status', 'change_weight', 'del', 'view', 'config' );

define( 'NV_IS_FILE_ADMIN', true );

$sql = "SELECT config_name,config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
$list = nv_db_cache( $sql );
$page_config = array();
foreach( $list as $values )
{
	$page_config[$values['config_name']] = $values['config_value'];
}

function nv_page_fixweight( $id = 0, $new_weight = 0, $first_news = 0 )
{
	global $db, $db_config, $module_data;

	if( $first_news ) $new_weight = 1;

	if( $id > 0 )
	{
		$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id!=' . $id . ' ORDER BY weight ASC';
	}
	else
	{
		$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY weight ASC';
	}
	$result = $db->query( $sql );

	$weight = 0;
	while( $row = $result->fetch() )
	{
		++$weight;
		if( $new_weight > 0 and $weight == $new_weight ) ++$weight;

		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=' . $weight . ' WHERE id=' . $row['id'];
		$db->query( $sql );
	}

	if( $id > 0 )
	{
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=' . $new_weight . ' WHERE id=' . $id;
		$db->query( $sql );
	}
}
