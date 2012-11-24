<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$list = $nv_Request->get_string( 'list', 'post,get' );

$array_bid = explode( ',', $list );
$array_bid = array_map( "intval", $array_bid );

$array_expression = array();
$result = $db->sql_query( "SELECT `bid`, `theme`, `position` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `bid` in (" . implode( ",", $array_bid ) . ")" );

while( list( $bid_i, $theme_i, $position_i ) = $db->sql_fetchrow( $result ) )
{
	$array_expression[$theme_i][$position_i][] = $bid_i;
}

if( ! empty( $array_expression ) )
{
	foreach( $array_expression as $theme_i => $array_data_i )
	{
		foreach( $array_data_i as $position => $array_position )
		{
			$db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `bid` in (" . implode( ",", $array_position ) . ")" );
			$db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid` in (" . implode( ",", $array_position ) . ")" );

			$weight = 0;
			$result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE theme='" . $theme_i . "' AND position='$position' ORDER BY weight ASC" );
		
			while( list( $bid_i ) = $db->sql_fetchrow( $result ) )
			{
				++$weight;
				$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_groups` SET `weight`=" . $weight . " WHERE `bid`=" . $bid_i );
			}

			$func_id_old = $weight = 0;
			$result = $db->sql_query( "SELECT t1.bid, t1.func_id FROM `" . NV_BLOCKS_TABLE . "_weight` AS t1 INNER JOIN `" . NV_BLOCKS_TABLE . "_groups` AS t2 ON t1.bid = t2.bid WHERE t2.theme='" . $theme_i . "' AND t2.position='$position' ORDER BY t1.func_id ASC, t1.weight  ASC" );
		
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
	}
	
	$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_weight`" );
	$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_groups`" );

	nv_del_moduleCache( 'themes' );
}

echo $lang_module['block_delete_success'];

?>