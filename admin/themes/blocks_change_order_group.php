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

list( $bid, $theme, $position ) = $db->sql_fetchrow( $db->sql_query( "SELECT `bid`, `theme`, `position` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `bid`=" . $bid . "" ) );

if( $order > 0 and $bid > 0 )
{
	$weight = 0;
	$result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE bid!=" . $bid . " AND theme='" . $theme . "' AND position='$position' ORDER BY weight  ASC" );

	while( list( $bid_i ) = $db->sql_fetchrow( $result ) )
	{
		++$weight;
		if( $weight == $order ) ++$weight;
		$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_groups` SET `weight`=" . $weight . " WHERE `bid`=" . $bid_i );
	}

	$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_groups` SET `weight`=" . $order . " WHERE `bid`=" . $bid );
	$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_groups`" );

	nv_del_moduleCache( 'themes' );

	echo "OK";
}
else
{
	echo "ERROR";
}

?>