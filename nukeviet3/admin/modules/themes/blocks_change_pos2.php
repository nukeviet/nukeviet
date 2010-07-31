<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_THEMES' ))
	die ( 'Stop!!!' );
$bid = $nv_Request->get_int ( 'bid', 'post' );
$func = $nv_Request->get_int ( 'func_id', 'post' );
$pos = filter_text_input( 'pos', 'post', '',1);
$group = $nv_Request->get_int ( 'group', 'post' );
if (! empty ( $pos ) and $bid > 0) {
	$result = $db->sql_query ( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE groupbl=" . intval ( $group ) . "" );
	while ( list ( $bids ) = $db->sql_fetchrow ( $result ) ) {
		list ( $maxweight ) = $db->sql_fetchrow ( $db->sql_query ( "SELECT MAX(weight) FROM `" . NV_BLOCKS_TABLE . "` WHERE func_id=" . intval ( $func ) . " AND position=" . $db->dbescape ( $pos ) . "" ) );
		$db->sql_query ( "UPDATE `" . NV_BLOCKS_TABLE . "` SET position=" . $db->dbescape ( $pos ) . ", weight='" . ($maxweight + 1) . "' WHERE `bid`='$bids'" );
	}
	#reupdate 
	$result = $db->sql_query ( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE func_id='" . $func . "' AND position='" . $pos . "'" );
	$i = 1;
	while ( list ( $bid ) = $db->sql_fetchrow ( $result ) ) {
		$db->sql_query ( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight='" . ($i) . "' WHERE `bid`='$bid'" );
		$i ++;
	}
}
echo $lang_module ['block_update_success'];
?>