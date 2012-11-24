<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$bid = $nv_Request->get_int( 'bid', 'post' );

list( $bid, $theme, $position ) = $db->sql_fetchrow( $db->sql_query( "SELECT `bid`, `theme`, `position` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `bid`=" . $bid . "" ) );

if( intval( $bid ) > 0 )
{
	$db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `bid`=" . $bid );
	$db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid`=" . $bid );

	// reupdate
	$weight = 0;
	$result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE theme='" . $theme . "' AND position='$position' ORDER BY weight ASC" );

	while( list( $bid_i ) = $db->sql_fetchrow( $result ) )
	{
		++$weight;
		$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_groups` SET `weight`=" . $weight . " WHERE `bid`=" . $bid_i );
	}

	$func_id_old = $weight = 0;
	$result = $db->sql_query( "SELECT t1.bid, t1.func_id FROM `" . NV_BLOCKS_TABLE . "_weight` AS t1 INNER JOIN `" . NV_BLOCKS_TABLE . "_groups` AS t2 ON t1.bid = t2.bid WHERE t2.theme='" . $theme . "' AND t2.position='$position' ORDER BY t1.func_id ASC, t1.weight  ASC" );

	while( list( $bid_i, $func_id_i ) = $db->sql_fetchrow( $result ) )
	{
		if( $func_id_i == $func_id_old )
		{
			++$weight;
		}
		else
		{
			$weight = 1;
			$func_id_old = $func_id_i;
		}
	
		$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_weight` SET `weight`=" . $weight . " WHERE `bid`=" . $bid_i . " AND `func_id`=" . $func_id_i );
	}

	$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_groups`" );
	$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_weight`" );

	nv_del_moduleCache( 'themes' );

	echo $lang_module['block_delete_success'];
}
else
{
	echo $lang_module['block_front_delete_error'];
}

?>