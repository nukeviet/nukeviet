<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$checkss = $nv_Request->get_string( 'checkss', 'post' );
$theme = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );

if( ! empty( $theme ) and $checkss == md5( $theme . $global_config['sitekey'] . session_id() ) )
{
	// Cap nhat block hien thi toan site cho cac function moi phat sinh - Danh cho lap trinh vien
	$array_bid = array(); // Danh sac tat ca cac block se kiem tra
	$sql = "SELECT `bid`, `position` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `theme`=" . $db->dbescape( $theme ) . " AND `all_func`=1";
	$result = $db->sql_query( $sql );
	
	while( list( $bid, $position ) = $db->sql_fetchrow( $result ) )
	{
		$array_bid[$bid] = $position;
	}
	
	$array_funcid = array(); // Danh sach ID tat ca cac function co block trong he thong
	$result = $db->sql_query( "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `show_func` = '1' ORDER BY `in_module` ASC, `subweight` ASC" );
	
	while( list( $func_id_i ) = $db->sql_fetchrow( $result ) )
	{
		$array_funcid[] = $func_id_i;
	}
	
	foreach( $array_bid as $bid => $position )
	{
		$func_list = array(); // Cac fuction da them block
		$result = $db->sql_query( "SELECT `func_id` FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid`=" . $bid );
		
		while( list( $func_inlist ) = $db->sql_fetchrow( $result ) )
		{
			$func_list[] = $func_inlist;
		}
		
		foreach( $array_funcid as $func_id )
		{
			if( ! in_array( $func_id, $func_list ) ) // Cac function chua duoc them
			{
				$sql = "SELECT MAX(t1.weight) FROM `" . NV_BLOCKS_TABLE . "_weight` AS t1 INNER JOIN `" . NV_BLOCKS_TABLE . "_groups` AS t2 ON t1.bid = t2.bid WHERE t1.func_id=" . $func_id . " AND t2.theme=" . $db->dbescape( $theme ) . " AND t2.position=" . $db->dbescape( $position );
				list( $weight ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
				$weight = intval( $weight ) + 1;

				$db->sql_query( "INSERT INTO `" . NV_BLOCKS_TABLE . "_weight` (`bid`, `func_id`, `weight`) VALUES ('" . $bid . "', '" . $func_id . "', '" . $weight . "')" );
			}
		}
	}
	
	// Cap nhat lai weight theo danh sach cac block
	$result = $db->sql_query( "SELECT bid, position, weight FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE theme='" . $theme . "' ORDER BY `position` ASC, `weight` ASC" );
	
	$array_position = array();
	
	while( list( $bid_i, $position, $weight ) = $db->sql_fetchrow( $result ) )
	{
		$array_position[] = $position;
		$db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_weight` SET `weight`=" . $weight . " WHERE `bid`=" . $bid_i );
	}

	// Kiem tra va cap nhat lai weight tung function
	$array_position = array_unique( $array_position );

	foreach( $array_position as $position )
	{
		$func_id_old = $weight = 0;
		$result = $db->sql_query( "SELECT t1.bid, t1.func_id FROM `" . NV_BLOCKS_TABLE . "_weight` AS t1 INNER JOIN `" . NV_BLOCKS_TABLE . "_groups` AS t2 ON t1.bid = t2.bid WHERE t2.theme='" . $theme . "' AND t2.position='" . $position . "' ORDER BY t1.func_id ASC, t1.weight  ASC" );
	
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

	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['block_weight'], 'reset position all block', $admin_info['userid'] );
	nv_del_moduleCache( 'themes' );

	echo $lang_module['block_update_success'];
}
else
{
	echo "ERROR";
}

?>