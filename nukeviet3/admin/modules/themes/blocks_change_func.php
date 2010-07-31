<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_THEMES' ))
	die ( 'Stop!!!' );
$newfunc = $nv_Request->get_int ( 'newfunc', 'post' );
$blockid = $nv_Request->get_int ( 'blockid', 'post' );
$pos = filter_text_input( 'position', 'post', '',1);
list ( $maxweight ) = $db->sql_fetchrow ( $db->sql_query ( "SELECT MAX(weight) FROM `" . NV_BLOCKS_TABLE . "` WHERE position='" . $pos . "' AND func_id=" . $newfunc . "" ) );
$db->sql_query ( "UPDATE `" . NV_BLOCKS_TABLE . "` SET func_id='" . $db->dbescape ( $newfunc ) . "', weight='" . ($maxweight + 1) . "' WHERE `bid`='$blockid'" );
#reupdate 
$result = $db->sql_query ( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE func_id='" . $newfunc . "' AND position='" . $pos . "'" );
$i = 1;
while ( list ( $bid ) = $db->sql_fetchrow ( $result ) ) {
	$db->sql_query ( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight='" . ($i) . "' WHERE `bid`='$bid'" );
	$i ++;
}
?>