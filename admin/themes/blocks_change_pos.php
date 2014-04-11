<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$bid = $nv_Request->get_int( 'bid', 'post' );
$pos_new = htmlspecialchars( $nv_Request->get_string( 'pos', 'post' ), ENT_QUOTES );

list( $bid, $theme, $pos_old ) = $db->query( 'SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid )->fetch( 3 );

if( $bid > 0 )
{
	$sth = $db->prepare( 'UPDATE ' . NV_BLOCKS_TABLE . '_groups SET position= :position, weight=2147483647 WHERE bid=' . $bid );
	$sth->bindParam( ':position', $pos_new, PDO::PARAM_STR );
	$sth->execute();

	$db->query( 'UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=2147483647 WHERE bid=' . $bid );

	//Update weight for old position
	$sth = $db->prepare( 'SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme=:theme AND position=:position ORDER BY weight ASC' );
	$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
	$sth->bindParam( ':position', $pos_old, PDO::PARAM_STR );
	$sth->execute();
	$weight = 0;
	while( list( $bid_i ) = $sth->fetch( 3 ) )
	{
		++$weight;
		$db->query( 'UPDATE ' . NV_BLOCKS_TABLE . '_groups SET weight=' . $weight . ' WHERE bid=' . $bid_i );
	}

	if( $weight )
	{
		$func_id_old = $weight = 0;
		$sth = $db->prepare( 'SELECT t1.bid, t1.func_id FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t2.theme=:theme AND t2.position=:position ORDER BY t1.func_id ASC, t1.weight ASC' );
		$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
		$sth->bindParam( ':position', $pos_old, PDO::PARAM_STR );
		$sth->execute();
		while( list( $bid_i, $func_id_i ) = $sth->fetch( 3 ) )
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

			$db->query( 'UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=' . $weight . ' WHERE bid=' . $bid_i . ' AND func_id=' . $func_id_i );
		}
	}

	//Update weight for news position
	$sth = $db->prepare( 'SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme=:theme AND position=:position ORDER BY weight ASC' );
	$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
	$sth->bindParam( ':position', $pos_new, PDO::PARAM_STR );
	$sth->execute();

	$weight = 0;
	while( list( $bid_i ) = $sth->fetch( 3 ) )
	{
		++$weight;
		$db->query( 'UPDATE ' . NV_BLOCKS_TABLE . '_groups SET weight=' . $weight . ' WHERE bid=' . $bid_i );
	}

	if( $weight )
	{
		$func_id_old = $weight = 0;
		$sth = $db->prepare( 'SELECT t1.bid, t1.func_id FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t2.theme=:theme AND t2.position=:position ORDER BY t1.func_id ASC, t1.weight ASC' );
		$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
		$sth->bindParam( ':position', $pos_new, PDO::PARAM_STR );
		$sth->execute();
		while( list( $bid_i, $func_id_i ) = $sth->fetch( 3 ) )
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
			$db->query( 'UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=' . $weight . ' WHERE bid=' . $bid_i . ' AND func_id=' . $func_id_i );
		}
	}
	nv_del_moduleCache( 'themes' );

	$db->query( 'OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_groups' );
	$db->query( 'OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_weight' );

	echo $lang_module['block_update_success'];
}
else
{
	echo 'ERROR';
}