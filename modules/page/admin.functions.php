<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'content', 'alias', 'change_status', 'change_weight', 'del', 'view', 'config' );

define( 'NV_IS_FILE_ADMIN', true );

$sql = "SELECT config_name,config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
$list = nv_db_cache( $sql );
$page_config = array();
foreach( $list as $values )
{
	$page_config[$values['config_name']] = $values['config_value'];
}

function nv_page_fix_weight( $news_first )
{
	global $db, $module_data, $page_config;

	$order = $news_first ? 'DESC' : 'ASC';
	$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY weight ' . $order;
	$result = $db->query( $sql );

	$weight = 0;
	while( $row = $result->fetch() )
	{
		++$weight;

		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=' . $weight . ' WHERE id=' . $row['id'];
		$db->query( $sql );
	}
}
