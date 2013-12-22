<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$bid = $nv_Request->get_int( 'bid', 'post' );
$func_id = $nv_Request->get_int( 'func_id', 'post' );

$row = $db->query( 'SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid )->fetch();

if( $func_id > 0 and isset( $row['bid'] ) )
{
	$sth = $db->prepare( 'SELECT MAX(weight) FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme' );
	$sth->bindParam( ':theme', $row['theme'], PDO::PARAM_STR );
	$sth->execute();
	$maxweight = $sth->fetchColumn();

	$row['weight'] = intval( $maxweight ) + 1;

	try
	{
		$sth = $db->prepare( 'INSERT INTO ' . NV_BLOCKS_TABLE . '_groups
			(theme, module, file_name, title, link, template, position, exp_time, active, groups_view, all_func, weight, config) VALUES
			( :theme, :module, :file_name, :title, :link, :template, :position, :exp_time, ' . $row['active'] . ', :groups_view, 0, ' . $row['weight'] . ', :config )' );
		$sth->bindParam( ':theme', $row['theme'], PDO::PARAM_STR );
		$sth->bindParam( ':module', $row['module'], PDO::PARAM_STR );
		$sth->bindParam( ':file_name', $row['file_name'], PDO::PARAM_STR );
		$sth->bindParam( ':title', $row['title'], PDO::PARAM_STR );
		$sth->bindParam( ':link', $row['link'], PDO::PARAM_STR );
		$sth->bindParam( ':template', $row['template'], PDO::PARAM_STR );
		$sth->bindParam( ':position', $row['position'], PDO::PARAM_STR );
		$sth->bindParam( ':exp_time', $row['exp_time'], PDO::PARAM_INT );
		$sth->bindParam( ':groups_view', $row['groups_view'], PDO::PARAM_STR );
		$sth->bindParam( ':config', $row['config'], PDO::PARAM_STR );
		$sth->execute();
		$new_bid = $db->lastInsertId();

		$db->exec( 'UPDATE ' . NV_BLOCKS_TABLE . '_weight SET bid=' . $new_bid . ' WHERE bid=' . $bid . ' AND func_id=' . $func_id );

		if( ! empty( $row['all_func'] ) )
		{
			$db->exec( 'UPDATE ' . NV_BLOCKS_TABLE . '_groups SET all_func=0 WHERE bid=' . $bid );
		}

		nv_del_moduleCache( 'themes' );

		echo $lang_module['block_front_outgroup_success'] . $new_bid;
	}
	catch (PDOException $e)
	{
		echo $lang_module['block_front_outgroup_error_update'];
	}
}
else
{
	echo $lang_module['block_front_outgroup_cancel'];
}

?>