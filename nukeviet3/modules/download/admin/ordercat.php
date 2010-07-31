<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$order = $nv_Request->get_int ( 'order', 'post,get' );
$cid = $nv_Request->get_int ( 'cid', 'post,get' );
if ($order)
{
	list ( $parentid, $oldorder ) = $db->sql_fetchrow ( $db->sql_query ( "SELECT parentid,weight FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid=" . $cid . "" ) );
	if ($oldorder < $order)
	{
		$result = $db->sql_query ( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE weight<=" . $order . " AND parentid=" . $parentid . " AND cid!=" . $cid . " ORDER BY weight DESC" );
		$weight = $order - 1;
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$db->sql_query ( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET weight=" . $weight . " WHERE cid=" . $row ['cid'] . "" );
			$weight --;
		}
		$db->sql_query ( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET weight=" . $order . " WHERE cid=" . $cid . "" );
	} elseif ($oldorder > $order)
	{
		$result = $db->sql_query ( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE weight>=" . $order . " AND parentid=" . $parentid . " AND cid!=" . $cid . " ORDER BY weight ASC" );
		$weight = $order + 1;
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$db->sql_query ( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET weight=" . $weight . " WHERE cid=" . $row ['cid'] . "" );
			$weight ++;
		}
		$db->sql_query ( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET weight=" . $order . " WHERE cid=" . $cid . "" );
	}
	#reupdate
	$result = $db->sql_query ( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=" . $parentid . " ORDER BY weight ASC" );
	$weight = 1;
	while ( $row = $db->sql_fetchrow ( $result ) )
	{
		$db->sql_query ( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET weight=" . $weight . " WHERE cid=" . $row ['cid'] . "" );
		$weight ++;
	}
}
?>