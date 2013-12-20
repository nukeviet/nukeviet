<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$theme1 = $nv_Request->get_title( 'theme1', 'post' );
$theme2 = $nv_Request->get_title( 'theme2', 'post' );

$position = $nv_Request->get_title( 'position', 'post' );
$position = explode( ',', $position );

if( ! empty( $theme1 ) and ! empty( $theme2 ) and $theme1 != $theme2 and file_exists( NV_ROOTDIR . '/themes/' . $theme1 . '/config.ini' ) and file_exists( NV_ROOTDIR . '/themes/' . $theme2 . '/config.ini' ) and ! empty( $position ) )
{
	foreach( $position as $pos )
	{
		// Begin drop all exist blocks behavior with theme 2 and position relative
		$sth = $db->prepare( 'DELETE FROM `' . NV_BLOCKS_TABLE . '_weight` WHERE `bid` IN (SELECT `bid` FROM `' . NV_BLOCKS_TABLE . '_groups` WHERE `theme` = :theme AND `position`= :position)' );
		$sth->bindParam( ':theme', $theme2, PDO::PARAM_STR );
		$sth->bindParam( ':position', $pos, PDO::PARAM_STR );
		$sth->execute();

		$sth = $db->prepare( 'DELETE FROM `' . NV_BLOCKS_TABLE . '_groups` WHERE `theme` = :theme AND `position`= :position' );
		$sth->bindParam( ':theme', $theme2, PDO::PARAM_STR );
		$sth->bindParam( ':position', $pos, PDO::PARAM_STR );
		$sth->execute();

		// Get and insert block from theme 1
		$sth = $db->prepare( 'SELECT * FROM `' . NV_BLOCKS_TABLE . '_groups` WHERE `theme` = :theme AND `position`= :position' );
		$sth->bindParam( ':theme', $theme2, PDO::PARAM_STR );
		$sth->bindParam( ':position', $pos, PDO::PARAM_STR );
		$sth->execute();
		while( $row = $sth->fetch() )
		{
			$sth2 = $db->prepare( 'INSERT INTO `' . NV_BLOCKS_TABLE . '_groups`
				(`theme`, `module`, `file_name`, `title`, `link`, `template`, `position`, `exp_time`, `active`, `groups_view`, `all_func`, `weight`, `config`) VALUES
				(:theme, :module, :file_name, :title, :link, :template, :position, :exp_time, :active, :groups_view, :all_func, :weight, :config )' );
			$sth2->bindParam( ':theme', $theme2, PDO::PARAM_STR );
			$sth2->bindParam( ':module', $row['module'], PDO::PARAM_STR );
			$sth2->bindParam( ':file_name', $row['file_name'], PDO::PARAM_STR );
			$sth2->bindParam( ':title', $row['title'], PDO::PARAM_STR );
			$sth2->bindParam( ':link', $row['link'], PDO::PARAM_STR );
			$sth2->bindParam( ':template', $row['template'], PDO::PARAM_STR );
			$sth2->bindParam( ':position', $row['position'], PDO::PARAM_STR );
			$sth2->bindParam( ':exp_time', $row['exp_time'], PDO::PARAM_INT );
			$sth2->bindParam( ':active', $row['active'], PDO::PARAM_INT );
			$sth2->bindParam( ':groups_view', $row['groups_view'], PDO::PARAM_STR );
			$sth2->bindParam( ':all_func', $row['all_func'], PDO::PARAM_STR );
			$sth2->bindParam( ':weight', $row['weight'], PDO::PARAM_STR );
			$sth2->bindParam( ':config', $row['config'], PDO::PARAM_STR );
			$sth2->execute();
			$bid = $db->lastInsertId();

			$result_weight = $db->query( 'SELECT func_id, weight FROM `' . NV_BLOCKS_TABLE . '_weight` WHERE `bid` = ' . $row['bid'] );
			while( list( $func_id, $weight ) = $result_weight->fetch( 3 ) )
			{
				$db->exec( 'INSERT INTO `' . NV_BLOCKS_TABLE . '_weight` (`bid`, `func_id`, `weight`) VALUES (' . $bid . ', ' . $func_id . ', ' . $weight . ')' );
			}
		}
	}

	$db->exec( 'OPTIMIZE TABLE `' . NV_BLOCKS_TABLE . '_groups`' );
	$db->exec( 'OPTIMIZE TABLE `' . NV_BLOCKS_TABLE . '_weight`' );

	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['xcopyblock'], $lang_module['xcopyblock_from'] . ' ' . $theme1 . ' ' . $lang_module['xcopyblock_to'] . ' ' . $theme2, $admin_info['userid'] );
	nv_del_moduleCache( 'themes' );

	echo $lang_module['xcopyblock_success'];
}
else
{
	die( 'error request !' );
}

?>