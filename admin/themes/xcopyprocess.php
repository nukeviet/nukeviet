<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$theme1 = $nv_Request->get_title( 'theme1', 'post' );
$theme2 = $nv_Request->get_title( 'theme2', 'post' );

$position = $nv_Request->get_title( 'position', 'post' );
$position = explode( ',', $position );

if( preg_match( $global_config['check_theme'], $theme1 ) and preg_match( $global_config['check_theme'], $theme2 ) and $theme1 != $theme2 and file_exists( NV_ROOTDIR . '/themes/' . $theme1 . '/config.ini' ) and file_exists( NV_ROOTDIR . '/themes/' . $theme2 . '/config.ini' ) and ! empty( $position ) )
{
	foreach( $position as $pos )
	{
		// Begin drop all exist blocks behavior with theme 2 and position relative
		$sth = $db->prepare( 'DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid IN (SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position)' );
		$sth->bindParam( ':theme', $theme2, PDO::PARAM_STR );
		$sth->bindParam( ':position', $pos, PDO::PARAM_STR );
		$sth->execute();

		$sth = $db->prepare( 'DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position' );
		$sth->bindParam( ':theme', $theme2, PDO::PARAM_STR );
		$sth->bindParam( ':position', $pos, PDO::PARAM_STR );
		$sth->execute();

		// Get and insert block from theme 1
		$sth = $db->prepare( 'SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position' );
		$sth->bindParam( ':theme', $theme1, PDO::PARAM_STR );
		$sth->bindParam( ':position', $pos, PDO::PARAM_STR );
		$sth->execute();
		while( $row = $sth->fetch() )
		{
			$_sql = 'INSERT INTO ' . NV_BLOCKS_TABLE . '_groups
				(theme, module, file_name, title, link, template, position, exp_time, active, groups_view, all_func, weight, config) VALUES
				(:theme, :module, :file_name, :title, :link, :template, :position, ' . $row['exp_time'] . ', ' . $row['active'] . ', :groups_view, :all_func, :weight, :config )';

			$data = array();
			$data['theme'] = $theme2;
			$data['module'] = $row['module'];
			$data['file_name'] = $row['file_name'];
			$data['title'] = $row['title'];
			$data['link'] = $row['link'];
			$data['template'] = $row['template'];
			$data['position'] = $row['position'];
			$data['groups_view'] = $row['groups_view'];
			$data['all_func'] = $row['all_func'];
			$data['weight'] = $row['weight'];
			$data['config'] = $row['config'];
			$bid = $db->insert_id( $_sql, 'bid', $data );

			$result_weight = $db->query( 'SELECT func_id, weight FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid = ' . $row['bid'] );
			while( list( $func_id, $weight ) = $result_weight->fetch( 3 ) )
			{
				$db->query( 'INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (' . $bid . ', ' . $func_id . ', ' . $weight . ')' );
			}
		}
	}

	$db->query( 'OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_groups' );
	$db->query( 'OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_weight' );

	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['xcopyblock'], $lang_module['xcopyblock_from'] . ' ' . $theme1 . ' ' . $lang_module['xcopyblock_to'] . ' ' . $theme2, $admin_info['userid'] );
	nv_del_moduleCache( 'themes' );

	echo $lang_module['xcopyblock_success'];
}
else
{
	die( 'error request !' );
}