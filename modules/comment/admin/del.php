<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$listcid = $nv_Request->get_string( 'list', 'post,get' );

if( ! empty( $listcid ) )
{
	$cid_array = explode( ',', $listcid );
	$cid_array = array_map( 'intval', $cid_array );
	$listcid = implode( ', ', $cid_array );

	// Xac dinh ID cac bai viet
	$sql = 'SELECT DISTINCT id, module FROM ' . NV_PREFIXLANG . '_comments WHERE cid IN (' . $listcid . ')';
	$array_row_id = $db->query( $sql )->fetchAll();
	// Het Xac dinh ID cac bai viet

	if( defined( 'NV_IS_SPADMIN' ) )
	{
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_comments WHERE cid IN (' . $listcid . ')' );
	}
	elseif( ! empty( $site_mod_comm ) )
	{
		$array_mod_name = array();
		foreach( $site_mod_comm as $module_i => $row )
		{
			$array_mod_name[] = "'" . $module_i . "'";
		}
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_comments WHERE cid IN (' . $listcid . ') AND module IN (' . implode( ', ', $array_mod_name ) . ')' );
	}
	else
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}

	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edit_delete'], 'listcid ' . $listcid, $admin_info['userid'] );

	foreach( $array_row_id as $row )
	{
		if( isset( $site_mod_comm[$row['module']] ) )
		{
			$mod_info = $site_mod_comm[$row['module']];
			if( file_exists( NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php' ) )
			{
				include NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php';
			}
		}
	}

	echo $lang_module['delete_success'];
}
else
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}