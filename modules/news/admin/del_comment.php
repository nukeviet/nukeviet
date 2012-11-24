<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$listcid = $nv_Request->get_string( 'list', 'post,get' );

if( ! empty( $listcid ) )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_comment', "listcid " . $listcid, $admin_info['userid'] );
	$cid_array = explode( ',', $listcid );
	$cid_array = array_map( "intval", $cid_array );
	foreach( $cid_array as $cid )
	{
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE cid='" . $cid . "'";
		$result = $db->sql_query( $sql );
	}

	// Xac dinh ID cac bai viet
	$sql = "SELECT DISTINCT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE cid in (" . implode( ",", $cid_array ) . ")";
	$query = $db->sql_query( $sql );
	$array_id = array();
	while( list( $id ) = $db->sql_fetchrow( $query ) )
	{
		$array_id[] = $id;
	}
	// Het Xac dinh ID cac bai viet

	// Xac dinh cac chu de bai viet
	$array_listcatid = array();
	$query = $db->sql_query( "SELECT id, listcatid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` in (" . implode( ",", $array_id ) . ")" );
	while( list( $id, $listcatid ) = $db->sql_fetchrow( $query ) )
	{
		$array_listcatid[$id] = explode( ",", $listcatid );
	}

	foreach( $array_id as $id )
	{
		list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` where `id`= '" . $id . "' AND `status`=1" ) );
		$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `hitscm`=" . $numf . " WHERE `id`=" . $id;
		$db->sql_query( $query );
		$array_catid = $array_listcatid[$id];
		foreach( $array_catid as $catid_i )
		{
			$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `hitscm`=" . $numf . " WHERE `id`=" . $id;
			$db->sql_query( $query );
		}
	}

	echo $lang_module['comment_delete_success'];
}

?>