<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$mod_name = $nv_Request->get_title( 'mod_name', 'post,get', '' );

if( $nv_Request->isset_request( 'submit', 'post' ) AND isset( $site_mods[$mod_name] ) )
{
	$array_config = array();
	$array_config['emailcomm'] = $nv_Request->get_int( 'emailcomm', 'post', 0 );
	$array_config['auto_postcomm'] = $nv_Request->get_int( 'auto_postcomm', 'post', 0 );
	$array_config['activecomm'] = $nv_Request->get_int( 'activecomm', 'post', 0 );
	$array_config['allowed_comm'] = $nv_Request->get_int( 'allowed_comm', 'post', 0 );
	$array_config['setcomm'] = $nv_Request->get_int( 'setcomm', 'post', 0 );
	$array_config['sortcomm'] = $nv_Request->get_int( 'sortcomm', 'post', 0 );

	$admins_mod_name = explode( ',', $site_mods[$mod_name]['admins'] );
	$admins_module_name = explode( ',', $site_mods[$module_name]['admins'] );
	$admins_module_name = array_unique( array_merge( $admins_mod_name, $admins_module_name ) );

	$adminscomm = $nv_Request->get_typed_array( 'adminscomm', 'post', 'int' );
	$adminscomm = array_intersect( $adminscomm, $admins_module_name );
	$array_config['adminscomm'] = implode( ',', $adminscomm );

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name" );
	$sth->bindParam( ':module_name', $mod_name, PDO::PARAM_STR );
	foreach( $array_config as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}
	nv_del_moduleCache( 'settings' );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	die();
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
if( ! empty( $mod_name ) )
{
	$xtpl->assign( 'MOD_NAME', $mod_name );
	$xtpl->assign( 'DATA', $module_config[$mod_name] );
	$xtpl->assign( 'ACTIVECOMM', $module_config[$mod_name]['activecomm'] ? ' checked="checked"' : '' );
	$xtpl->assign( 'EMAILCOMM', $module_config[$mod_name]['emailcomm'] ? ' checked="checked"' : '' );

	$admins_mod_name = explode( ',', $site_mods[$mod_name]['admins'] );
	$admins_module_name = explode( ',', $site_mods[$module_name]['admins'] );
	$admins_module_name = array_unique( array_merge( $admins_mod_name, $admins_module_name ) );
	if( ! empty( $admins_module_name ) )
	{
		$adminscomm = explode( ',', $module_config[$mod_name]['adminscomm'] );

		$admins_module_name = array_map( 'intval', $admins_module_name );
		$_sql = 'SELECT userid, username, full_name FROM ' . $db_config['dbsystem'] . '.' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . implode( ',', $admins_module_name ) . ')';
		$_query = $db->query( $_sql );

		while( $row = $_query->fetch() )
		{
			if( ! empty( $row['full_name'] ) )
			{
				$row['username'] .= ' (' . $row['full_name'] . ')';
			}
			$xtpl->assign( 'OPTION', array(
				'key' => $row['userid'],
				'title' => $row['username'],
				'checked' => ( in_array( $row['userid'], $adminscomm ) ) ? ' checked="checked"' : ''
			) );
			$xtpl->parse( 'main.config.adminscomm' );
		}
	}

	for( $i = 0; $i <= 2; ++$i )
	{
		$xtpl->assign( 'OPTION', array(
			'key' => $i,
			'title' => $lang_module['auto_postcomm_' . $i],
			'selected' => $i == $module_config[$mod_name]['auto_postcomm'] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.config.auto_postcomm' );
	}

	while( list( $comm_i, $title_i ) = each( $array_allowed_comm ) )
	{
		$xtpl->assign( 'OPTION', array(
			'key' => $comm_i,
			'title' => $title_i,
			'selected' => $comm_i == $module_config[$mod_name]['allowed_comm'] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.config.allowed_comm' );
	}

	// Thao luan mac dinh khi tao bai viet moi
	while( list( $comm_i, $title_i ) = each( $array_setcomm ) )
	{
		$xtpl->assign( 'OPTION', array(
			'key' => $comm_i,
			'title' => $title_i,
			'selected' => $comm_i == $module_config[$mod_name]['setcomm'] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.config.setcomm' );
	}

	// Order by comm
	for( $i = 0; $i <= 2; ++$i )
	{
		$xtpl->assign( 'OPTION', array(
			'key' => $i,
			'title' => $lang_module['sortcomm_' . $i],
			'selected' => $i == $module_config[$mod_name]['sortcomm'] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.config.sortcomm' );
	}

	$xtpl->parse( 'main.config' );

	$page_title = sprintf( $lang_module['config_mod_name'], $site_mods[$mod_name]['custom_title'] );
}
else
{
	$weight = 0;
	foreach( $site_mod_comm as $mod_name => $row_mod )
	{
		$admin_title = ( ! empty( $row_mod['admin_title'] ) ) ? $row_mod['admin_title'] : $row_mod['custom_title'];

		$row = array();
		$row['weight'] = ++$weight;
		$row['mod_name'] = $mod_name;
		$row['admin_title'] = $admin_title;
		$row['allowed_comm'] = $array_allowed_comm[$module_config[$mod_name]['allowed_comm']];
		$row['auto_postcomm'] = $lang_module['auto_postcomm_' . $module_config[$mod_name]['auto_postcomm']];
		$row['activecomm'] = $module_config[$mod_name]['activecomm'] ? 'check' : 'check-empty';
		$row['emailcomm'] = $module_config[$mod_name]['emailcomm'] ? 'check' : 'check-empty';
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.list.loop' );
	}
	$xtpl->parse( 'main.list' );

	$page_title = $lang_module['config'];
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>