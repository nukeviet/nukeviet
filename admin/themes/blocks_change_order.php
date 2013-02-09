<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$order = $nv_Request->get_int( 'order', 'post,get' );
$bid = $nv_Request->get_int( 'bid', 'post,get' );
$func_id = $nv_Request->get_int( 'func_id', 'post,get' );

list( $bid, $theme, $position ) = $db->sql_fetchrow( $db->sql_query( "SELECT `bid`, `theme`, `position` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `bid`=" . $bid . "" ) );

if( $order > 0 and $bid > 0 )
{
	$weight = 0;
	$result = $db->sql_query( "SELECT t1.bid FROM `" . NV_BLOCKS_TABLE . "_weight` AS t1 INNER JOIN `" . NV_BLOCKS_TABLE . "_groups` AS t2 ON t1.bid = t2.bid WHERE  t1.bid!=" . $bid . " AND t1.func_id='" . $func_id . "' AND t2.theme='" . $theme . "' AND t2.position='$position' ORDER BY t1.weight  ASC" );

	while( list( $bid_i ) = $db->sql_fetchrow( $result ) )
	{
		++$weight;
		if( $weight == $order ) ++$weight;
		$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_weight` SET `weight`=" . $weight . " WHERE `bid`=" . $bid_i . " AND `func_id`=" . $func_id );
	}

	$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_weight` SET `weight`=" . $order . " WHERE `bid`=" . $bid . " AND `func_id`=" . $func_id );
	$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_weight`" );

	nv_del_moduleCache( 'themes' );

	echo "OK";
}
else
{
	echo "ERROR";
}

?>