<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$order = $nv_Request->get_int( 'order', 'post,get' );
$bid = $nv_Request->get_int( 'bid', 'post,get' );
$func_id = $nv_Request->get_int( 'func_id', 'post,get' );

list( $bid, $theme, $position ) = $db->query( 'SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid )->fetch( 3 );

if( $order > 0 and $bid > 0 )
{
	$weight = 0;
	$sth = $db->prepare( 'SELECT t1.bid FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t1.bid!=' . $bid . ' AND t1.func_id=' . $func_id . ' AND t2.theme = :theme AND t2.position = :position ORDER BY t1.weight ASC' );
	$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
	$sth->bindParam( ':position', $position, PDO::PARAM_STR );
	$sth->execute();
	while( list( $bid_i ) = $sth->fetch( 3 ) )
	{
		++$weight;
		if( $weight == $order ) ++$weight;
		$db->query( 'UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=' . $weight . ' WHERE bid=' . $bid_i . ' AND func_id=' . $func_id );
	}

	$db->query( 'UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=' . $order . ' WHERE bid=' . $bid . ' AND func_id=' . $func_id );

	nv_del_moduleCache( 'themes' );

	$db->query( 'OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_weight' );

	echo 'OK';
}
else
{
	echo 'ERROR';
}