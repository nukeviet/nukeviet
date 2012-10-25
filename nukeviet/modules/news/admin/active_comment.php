<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' ); 

$status = $nv_Request->get_int( 'active', 'post' );
$listcid = $nv_Request->get_string( 'list', 'post' );

if( ! empty( $listcid ) )
{
	$status = ( $status == 1 ) ? 1 : 0;
	$cid_array = explode( ',', $listcid );
	$cid_array = array_map( "intval", $cid_array );

	foreach( $cid_array as $cid )
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comments` SET status='" . $status . "' WHERE cid=" . $cid;
		$db->sql_query( $sql );
	}

	// Xac dinh ID cac bai viet
	$sql = "SELECT DISTINCT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE cid in (" . implode( ",", $cid_array ) . ")";
	$sql = $db->sql_query( $sql );
	$array_id = array();
	while( list( $id ) = $db->sql_fetchrow( $sql ) )
	{
		$array_id[] = $id;
	}
	// Het Xac dinh ID cac bai viet

	// Xac dinh cac chu de bai viet
	$array_listcatid = array();
	$sql = $db->sql_query( "SELECT id, listcatid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` in (" . implode( ",", $array_id ) . ")" );
	while( list( $id, $listcatid ) = $db->sql_fetchrow( $sql ) )
	{
		$array_listcatid[$id] = explode( ",", $listcatid );
	}

	foreach( $array_id as $id )
	{
		list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` where `id`= '" . $id . "' AND `status`=1" ) );
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `hitscm`=" . $numf . " WHERE `id`=" . $id;
		$db->sql_query( $sql );
		$array_catid = $array_listcatid[$id];
		foreach( $array_catid as $catid_i )
		{
			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `hitscm`=" . $numf . " WHERE `id`=" . $id;
			$db->sql_query( $sql );
		}
	}
	
	echo $lang_module['comment_update_success'];
}

?>