<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 * @Development version theme control
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$array_bid = $nv_Request->get_array( 'bl', 'post' );
$func_id = $nv_Request->get_int( 'func_id', 'post' );

$position = $nv_Request->get_string( 'position', 'post' );

if( ! empty( $array_bid ) && ! empty( $position ) )
{
	$pos_new = '[' . $position . ']';

	$sql = "SELECT `bid`, `theme`, `position` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE position !=" . $db->dbescape_string( $pos_new ) . " AND bid IN (" . implode( ',', $array_bid ) . ")";
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) == 1 )
	{
		list( $bid, $theme, $pos_old ) = $db->sql_fetchrow( $result );

		$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_groups` SET `position`=" . $db->dbescape_string( $pos_new ) . ", `weight`='2147483647' WHERE `bid`=" . $bid );
		$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_weight` SET `weight`='2147483647' WHERE `bid`=" . $bid );

		//Update weight for old position
		$result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE theme='" . $theme . "' AND position='" . $pos_old . "' ORDER BY weight ASC" );
		
		if( $db->sql_numrows( $result ) )
		{
			$weight = 0;
			while( list( $bid_i ) = $db->sql_fetchrow( $result ) )
			{
				++$weight;
				$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_groups` SET `weight`=" . $weight . " WHERE `bid`=" . $bid_i );
			}

			$func_id_old = $weight = 0;
			$result = $db->sql_query( "SELECT t1.bid, t1.func_id FROM `" . NV_BLOCKS_TABLE . "_weight` AS t1 INNER JOIN `" . NV_BLOCKS_TABLE . "_groups` AS t2 ON t1.bid = t2.bid WHERE t2.theme='" . $theme . "' AND t2.position='" . $pos_old . "' ORDER BY t1.func_id ASC, t1.weight  ASC" );
			
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
		}

		//Update weight for news position
		$result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE theme='" . $theme . "' AND position=" . $db->dbescape_string( $pos_new ) . " ORDER BY weight ASC" );
		
		if( $db->sql_numrows( $result ) )
		{
			$weight = 0;
			while( list( $bid_i ) = $db->sql_fetchrow( $result ) )
			{
				++$weight;
				$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_groups` SET `weight`=" . $weight . " WHERE `bid`=" . $bid_i );
			}

			$func_id_old = $weight = 0;
			$result = $db->sql_query( "SELECT t1.bid, t1.func_id FROM `" . NV_BLOCKS_TABLE . "_weight` AS t1 INNER JOIN `" . NV_BLOCKS_TABLE . "_groups` AS t2 ON t1.bid = t2.bid WHERE t2.theme='" . $theme . "' AND t2.position=" . $db->dbescape_string( $pos_new ) . " ORDER BY t1.func_id ASC, t1.weight  ASC" );
			
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
		}
		
		$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_groups`" );
		$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_weight`" );
	}
}

$weight = 1;

if( ! empty( $array_bid ) and $func_id > 0 )
{
	foreach( $array_bid as $bid )
	{
		$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_weight` SET weight = " . $weight . " WHERE bid = " . $bid . " AND func_id=" . $func_id );
		++$weight;
	}
}

nv_del_moduleCache( 'themes' );
die( 'OK_' . $func_id );

?>