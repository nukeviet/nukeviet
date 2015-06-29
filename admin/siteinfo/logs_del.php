<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11-10-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

// Delete all log
if( $nv_Request->get_title( 'logempty', 'post', '' ) == md5( 'siteinfo_' . session_id() . '_' . $admin_info['userid'] ) )
{
	if( $db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_logs' ) )
	{
		nv_del_moduleCache( $module_name );
		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['log_empty_log'], 'All', $admin_info['userid'] );
		die( 'OK' );
	}
	else
	{
		die( $lang_module['log_del_error'] );
	}
}

$id = $nv_Request->get_int( 'id', 'post,get', 0 );
$contents = 'NO_' . $lang_module['log_del_error'];
$number_del = 0;
if( $id > 0 )
{
	if( $db->exec( 'DELETE FROM ' . $db_config['prefix'] . '_logs WHERE id=' . $id ) )
	{
		$contents = 'OK_' . $lang_module['log_del_ok'];
		++$number_del;
	}
}
else
{
	$listall = $nv_Request->get_string( 'listall', 'post,get' );
	$array_id = explode( ',', $listall );
	$array_id = array_map( 'intval', $array_id );
	foreach( $array_id as $id )
	{
		if( $id > 0 )
		{
			$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_logs WHERE id=' . $id );
			++$number_del;
		}
	}
	$contents = 'OK_' . $lang_module['log_del_ok'];
}

nv_insert_logs( NV_LANG_DATA, $module_name, $lang_global['delete'] . ' ' . $lang_module['logs_title'], $number_del, $admin_info['userid'] );

echo $contents;
exit();