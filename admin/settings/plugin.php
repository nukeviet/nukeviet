<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 28/10/2012, 14:51
 */

if( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$errormess = $lang_module['plugin_info'];
$pattern_plugin = '/^([a-zA-Z0-9\_]+)\.php$/';

if( $nv_Request->isset_request( 'plugin_file', 'post' ) )
{
	$config_plugin = array();
	$plugin_file = $nv_Request->get_title( 'plugin_file', 'post' );
	if( preg_match( $pattern_plugin, $plugin_file ) and is_file( NV_ROOTDIR . '/includes/plugin/' . $plugin_file ) )
	{
		$plugin_area = $nv_Request->get_int( 'plugin_area', 'post' );
		if( $nv_Request->isset_request( 'delete', 'post' ) )
		{
			$sth = $db->prepare( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_file=:plugin_file' );
			$sth->bindParam( ':plugin_file', $plugin_file, PDO::PARAM_STR, strlen( $title ) );
			$sth->execute();
			$count = $sth->fetchColumn();
			if( empty( $count ) )
			{
				nv_deletefile( NV_ROOTDIR . '/includes/plugin/' . $plugin_file );
			}
		}
		elseif( ! empty( $plugin_area ) )
		{
			$_sql = 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_area=' . $plugin_area;
			$weight = $db->query( $_sql )->fetchColumn();
			$weight = intval( $weight ) + 1;

			try
			{
				$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_plugin (plugin_file, plugin_area, weight) VALUES (:plugin_file, :plugin_area, :weight)' );
				$sth->bindParam( ':plugin_file', $plugin_file, PDO::PARAM_STR );
				$sth->bindParam( ':plugin_area', $plugin_area, PDO::PARAM_INT );
				$sth->bindParam( ':weight', $weight, PDO::PARAM_INT );
				$sth->execute();

				nv_save_file_config_global();
			}
			catch( PDOException $e )
			{
				trigger_error( $e->getMessage() );
			}
		}
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
		die();
	}
}
if( $nv_Request->isset_request( 'dpid', 'get' ) )
{
	$dpid = $nv_Request->get_int( 'dpid', 'get' );
	$checkss = $nv_Request->get_title( 'checkss', 'get' );
	if( $dpid > 0 and $checkss == md5( $dpid . '-' . session_id() . '-' . $global_config['sitekey'] ) )
	{
		$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_plugin WHERE pid=' . $dpid )->fetch();
		if( ! empty( $row ) and $db->exec( 'DELETE FROM ' . $db_config['prefix'] . '_plugin WHERE pid = ' . $dpid ) )
		{
			$weight = intval( $row['weight'] );
			$_query = $db->query( 'SELECT pid FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_area=' . $row['plugin_area'] . ' AND weight > ' . $weight . ' ORDER BY weight ASC' );
			while( list( $pid ) = $_query->fetch( 3 ) )
			{
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_plugin SET weight = ' . $weight++ . ' WHERE pid=' . $pid );
			}

			nv_save_file_config_global();
		}
	}
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
}
elseif( $nv_Request->isset_request( 'pid', 'get' ) and $nv_Request->isset_request( 'weight', 'get' ) )
{
	$pid = $nv_Request->get_int( 'pid', 'get' );
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_plugin WHERE pid=' . $pid )->fetch();
	if( ! empty( $row ) )
	{
		$new = $nv_Request->get_int( 'weight', 'get' );

		$weight = 0;
		$_query = $db->query( 'SELECT pid FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_area=' . $row['plugin_area'] . ' AND pid != ' . $pid . ' ORDER BY weight ASC' );
		while( list( $pid_i ) = $_query->fetch( 3 ) )
		{
			++$weight;
			if( $weight == $new )
			{
				++$weight;
			}
			$db->query( 'UPDATE ' . $db_config['prefix'] . '_plugin SET weight = ' . $weight . ' WHERE pid=' . $pid_i );
		}
		$db->query( 'UPDATE ' . $db_config['prefix'] . '_plugin SET weight = ' . $new . ' WHERE pid=' . $pid );

		nv_save_file_config_global();
	}
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );

$plugin_new = array();
$plugin_all = nv_scandir( NV_ROOTDIR . '/includes/plugin', $pattern_plugin );

$nv_plugin_array = array();
$nv_plugin_area = array();
$_sql = 'SELECT * FROM ' . $db_config['prefix'] . '_plugin ORDER BY plugin_area ASC, weight ASC';
$_query = $db->query( $_sql );
while( $row = $_query->fetch() )
{
	$nv_plugin_area[$row['plugin_area']][] = $row;
	$nv_plugin_array[] = $row['plugin_file'];
}

foreach( $nv_plugin_area as $area => $nv_plugin_area_i )
{
	$_sizeof = sizeof( $nv_plugin_area_i );
	foreach( $nv_plugin_area_i as $row )
	{
		$row['plugin_area'] = ($row['weight'] == 1) ? $lang_module['plugin_area_' . $row['plugin_area']] : '';
		$row['plugin_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;dpid=' . $row['pid'] . '&amp;checkss=' . md5( $row['pid'] . '-' . session_id() . '-' . $global_config['sitekey'] );
		$xtpl->assign( 'DATA', $row );
		for( $i = 1; $i <= $_sizeof; $i++ )
		{
			$xtpl->assign( 'WEIGHT_SELECTED', ($i == $row['weight']) ? ' selected="selected"' : '' );
			$xtpl->assign( 'WEIGHT', $i );
			$xtpl->parse( 'main.loop.weight' );
		}
		$xtpl->parse( 'main.loop' );
	}
}

foreach( $plugin_all as $_file )
{
	if( ! in_array( $_file, $nv_plugin_array ) )
	{
		$plugin_new[] = $_file;
	}
}

if( $errormess != '' )
{
	$xtpl->assign( 'ERROR', $errormess );
	$xtpl->parse( 'main.error' );
}

if( ! empty( $plugin_new ) )
{
	foreach( $plugin_new as $_file )
	{
		$xtpl->assign( 'PLUGIN_FILE', $_file );
		$xtpl->parse( 'main.add.file' );
	}
	for( $i = 1; $i < 4; $i++ )
	{
		$xtpl->assign( 'AREA_VALUE', $i );
		$xtpl->assign( 'AREA_TEXT', $lang_module['plugin_area_' . $i] );
		$xtpl->parse( 'main.add.area' );
	}
	$xtpl->parse( 'main.add' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['plugin'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';