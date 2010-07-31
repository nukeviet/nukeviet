<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_THEMES' ))
	die ( 'Stop!!!' );
$list_bid = $nv_Request->get_string ( 'list', 'post,get' );
$array_bid = explode ( ',', $list_bid );
foreach ( $array_bid as $bid ) {
	$bid = intval ( $bid );
	if ($bid > 0) {
		list ( $position, $func_id ) = $db->sql_fetchrow ( $db->sql_query ( "SELECT position,func_id FROM `" . NV_BLOCKS_TABLE . "` WHERE bid=" . $bid . "" ) );
		$db->sql_query ( "DELETE FROM " . NV_BLOCKS_TABLE . " WHERE bid='" . $bid . "'" );
		#reupdate
		$result = $db->sql_query ( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE position='$position' AND func_id='$func_id' ORDER BY weight ASC" );
		$order = 1;
		while ( $row = $db->sql_fetchrow ( $result ) ) {
			$db->sql_query ( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight=" . $order . " WHERE bid=" . $row ['bid'] . "" );
			$order ++;
		}
		$db->sql_query ( "LOCK TABLE " . NV_BLOCKS_TABLE . " WRITE" );
		$db->sql_query ( "REPAIR TABLE " . NV_BLOCKS_TABLE );
		$db->sql_query ( "OPTIMIZE TABLE " . NV_BLOCKS_TABLE );
		$db->sql_query ( "UNLOCK TABLE " . NV_BLOCKS_TABLE );
	}
}
echo $lang_module ['block_delete_success'];
?>