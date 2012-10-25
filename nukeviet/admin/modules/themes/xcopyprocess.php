<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$theme1 = filter_text_input( 'theme1', 'post' );
$theme2 = filter_text_input( 'theme2', 'post' );

$position = filter_text_input( 'position', 'post' );
$position = explode( ',', $position );

if( ! empty( $theme1 ) and ! empty( $theme2 ) and $theme1 != $theme2 and file_exists( NV_ROOTDIR . '/themes/' . $theme1 . '/config.ini' ) and file_exists( NV_ROOTDIR . '/themes/' . $theme2 . '/config.ini' ) and ! empty( $position ) )
{
	foreach( $position as $pos )
	{
		// Begin drop all exist blocks behavior with theme 2 and position relative
		$db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid` IN (SELECT `bid` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `theme` = " . $db->dbescape( $theme2 ) . " AND `position`=" . $db->dbescape( $pos ) . ")" );
		$db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `theme` = " . $db->dbescape( $theme2 ) . " AND `position`=" . $db->dbescape( $pos ) . "" );

		// Get and insert block from theme 1
		$result = $db->sql_query( "SELECT * FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `theme` = " . $db->dbescape( $theme1 ) . " AND `position`=" . $db->dbescape( $pos ) );
	
		while( $row = $db->sql_fetchrow( $result ) )
		{
			$bid = ( int )$db->sql_query_insert_id( "INSERT INTO `" . NV_BLOCKS_TABLE . "_groups` (`bid`, `theme`, `module`, `file_name`, `title`, `link`, `template`, `position`, `exp_time`, `active`, `groups_view`, `all_func`, `weight`, `config`) VALUES ( NULL, " . $db->dbescape( $theme2 ) . ", " . $db->dbescape( $row['module'] ) . ", " . $db->dbescape( $row['file_name'] ) . ", " . $db->dbescape( $row['title'] ) . ", " . $db->dbescape( $row['link'] ) . ", " . $db->dbescape( $row['template'] ) . ", " . $db->dbescape( $row['position'] ) . ", '" . $row['exp_time'] . "', '" . $row['active'] . "', " . $db->dbescape( $row['groups_view'] ) . ", '" . $row['all_func'] . "', '" . $row['weight'] . "', " . $db->dbescape( $row['config'] ) . " )" );
			$result_weight = $db->sql_query( "SELECT func_id, weight FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid` = " . $row['bid'] );
		
			while( list( $func_id, $weight ) = $db->sql_fetchrow( $result_weight ) )
			{
				$db->sql_query( "INSERT INTO `" . NV_BLOCKS_TABLE . "_weight` (`bid`, `func_id`, `weight`) VALUES ('" . $bid . "', '" . $func_id . "', '" . $weight . "')" );
			}
		}
	}

	$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_groups`" );
	$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_weight`" );
	
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['xcopyblock'], $lang_module['xcopyblock_from'] . ' ' . $theme1 . ' ' . $lang_module['xcopyblock_to'] . ' ' . $theme2, $admin_info['userid'] );
	nv_del_moduleCache( 'themes' );

	echo $lang_module['xcopyblock_success'];
}
else
{
	die( 'error request !' );
}

?>