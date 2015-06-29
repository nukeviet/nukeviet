<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:17
 */

if( ! defined( 'NV_IS_FILE_AUTHORS' ) ) die( 'Stop!!!' );

$admin_id = $nv_Request->get_int( 'admin_id', 'get', 0 );

$query = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $admin_id;
$row = $db->query( $query )->fetch();

if( empty( $row ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$allowed = false;
if( defined( 'NV_IS_GODADMIN' ) )
{
	$allowed = true;
}
elseif( defined( 'NV_IS_SPADMIN' ) )
{
	if( $row['admin_id'] == $admin_info['admin_id'] )
	{
		$allowed = true;
	}
	elseif( $row['lev'] == 3 and $global_config['spadmin_add_admin'] == 1 )
	{
		$allowed = true;
	}
}
else
{
	if( $row['admin_id'] == $admin_info['admin_id'] )
	{
		$allowed = true;
	}
}

if( empty( $allowed ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$old_modules = array();
if( $row['lev'] == 3 )
{
	$array_keys = array_keys( $site_mods );
	foreach( $array_keys as $mod )
	{
		if( ! empty( $mod ) )
		{
			if( ! empty( $site_mods[$mod]['admins'] ) )
			{
				if( in_array( $admin_id, explode( ',', $site_mods[$mod]['admins'] ) ) )
				{
					$old_modules[] = $mod;
				}
			}
		}
	}
}

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id;
$row_user = $db->query( $sql )->fetch();

if( empty( $row['files_level'] ) )
{
	$old_allow_files_type = array();
	$old_allow_modify_files = $old_allow_create_subdirectories = $old_allow_modify_subdirectories = 0;
}
else
{
	list( $old_allow_files_type, $old_allow_modify_files, $old_allow_create_subdirectories, $old_allow_modify_subdirectories ) = explode( '|', $row['files_level'] );
	$old_allow_files_type = ! empty( $old_allow_files_type ) ? explode( ',', $old_allow_files_type ) : array();
}

$error = '';

if( $nv_Request->get_int( 'save', 'post', 0 ) )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['nv_admin_edit'], 'Username: ' . $row_user['username'], $admin_info['userid'] );

	$editor = $nv_Request->get_title( 'editor', 'post', '' );
	if( defined( 'NV_IS_SPADMIN' ) )
	{
		$allow_files_type = $nv_Request->get_array( 'allow_files_type', 'post', array() );
		$allow_modify_files = $nv_Request->get_int( 'allow_modify_files', 'post', 0 );
		$allow_create_subdirectories = $nv_Request->get_int( 'allow_create_subdirectories', 'post', 0 );
		$allow_modify_subdirectories = $nv_Request->get_int( 'allow_modify_subdirectories', 'post', 0 );
	}
	else
	{
		$allow_files_type = $old_allow_files_type;
		$allow_modify_files = $old_allow_modify_files;
		$allow_create_subdirectories = $old_allow_create_subdirectories;
		$allow_modify_subdirectories = $old_allow_modify_subdirectories;
	}

	$lev = ( defined( 'NV_IS_GODADMIN' ) and $row['admin_id'] != $admin_info['admin_id'] ) ? $nv_Request->get_int( 'lev', 'post', 0 ) : $row['lev'];
	$modules = ( defined( 'NV_IS_SPADMIN' ) and $row['admin_id'] != $admin_info['admin_id'] ) ? $nv_Request->get_array( 'modules', 'post', array() ) : $old_modules;
	$position = ( defined( 'NV_IS_GODADMIN' ) or ( defined( 'NV_IS_SPADMIN' ) and $row['lev'] != 1 and $row['admin_id'] != $admin_info['admin_id'] ) ) ? $nv_Request->get_title( 'position', 'post' ) : $row['position'];

	if( $lev == 2 ) $modules = array();

	if( ! empty( $modules ) ) $modules = array_intersect( array_keys( $site_mods ), $modules );

	if( empty( $position ) )
	{
		$error = $lang_module['position_incorrect'];
	}
	else
	{
		$add_modules = array_diff( $modules, $old_modules );
		$del_modules = array_diff( $old_modules, $modules );

		if( ! empty( $add_modules ) )
		{
			foreach( $add_modules as $mod )
			{
				$admins = $site_mods[$mod]['admins'] . ( ! empty( $site_mods[$mod]['admins'] ) ? ',' : '' ) . $admin_id;
				$sth = $db->prepare( 'UPDATE ' . NV_MODULES_TABLE . ' SET admins= :admins WHERE title= :mod' );
				$sth->bindParam( ':admins', $admins, PDO::PARAM_STR );
				$sth->bindParam( ':mod', $mod, PDO::PARAM_STR );
				$sth->execute();
			}
		}
		if( ! empty( $del_modules ) )
		{
			foreach( $del_modules as $del )
			{
				$admins = ( ! empty( $site_mods[$del]['admins'] ) ) ? explode( ',', $site_mods[$del]['admins'] ) : array();
				$admins = array_diff( $admins, array( $admin_id ) );
				$admins = ( ! empty( $admins ) ) ? implode( ',', $admins ) : '';
				$sth = $db->prepare( 'UPDATE ' . NV_MODULES_TABLE . ' SET admins= :admins WHERE title= :mod' );
				$sth->bindParam( ':admins', $admins, PDO::PARAM_STR );
				$sth->bindParam( ':mod', $del, PDO::PARAM_STR );
				$sth->execute();
			}
		}

		if( ! empty( $add_modules ) or ! empty( $del_modules ) )
		{
			nv_del_moduleCache( 'modules' );
		}

		$allow_files_type = array_values( array_intersect( $global_config['file_allowed_ext'], $allow_files_type ) );
		$files_level = ( ! empty( $allow_files_type ) ? implode( ',', $allow_files_type ) : '' ) . '|' . $allow_modify_files . '|' . $allow_create_subdirectories . '|' . $allow_modify_subdirectories;

		$sth = $db->prepare( 'UPDATE ' . NV_AUTHORS_GLOBALTABLE . ' SET editor = :editor, lev=' . $lev . ', files_level= :files_level, position= :position WHERE admin_id=' . $admin_id );
		$sth->bindParam( ':editor', $editor, PDO::PARAM_STR );
		$sth->bindParam( ':files_level', $files_level, PDO::PARAM_STR );
		$sth->bindParam( ':position', $position, PDO::PARAM_STR );
		$sth->execute();

		if( $lev != $row['lev'] )
		{
			nv_groups_add_user( $lev, $admin_id );
			nv_groups_del_user( $row['lev'], $admin_id );
		}

		$result = array();
		$result['admin_id'] = $admin_id;
		$result['login'] = $row_user['username'];
		$result['change'] = array();
		if( $editor != $row['editor'] )
		{
			$result['change']['editor'] = array( $lang_module['editor'], ( ! empty( $row['editor'] ) ? $row['editor'] : $lang_module['not_use'] ), ( ! empty( $editor ) ? $editor : $lang_module['not_use'] ) );
		}
		if( $allow_files_type != $old_allow_files_type )
		{
			$result['change']['allow_files_type'] = array( $lang_module['allow_files_type'], ( ! empty( $old_allow_files_type ) ? implode( ', ', $old_allow_files_type ) : $lang_global['no'] ), ( ! empty( $allow_files_type ) ? implode( ', ', $allow_files_type ) : $lang_global['no'] ) );
		}
		if( $allow_modify_files != $old_allow_modify_files )
		{
			$result['change']['allow_modify_files'] = array( $lang_module['allow_modify_files'], ( ! empty( $old_allow_modify_files ) ? $lang_global['yes'] : $lang_global['no'] ), ( ! empty( $allow_modify_files ) ? $lang_global['yes'] : $lang_global['no'] ) );
		}
		if( $allow_create_subdirectories != $old_allow_create_subdirectories )
		{
			$result['change']['allow_create_subdirectories'] = array( $lang_module['allow_create_subdirectories'], ( ! empty( $old_allow_create_subdirectories ) ? $lang_global['yes'] : $lang_global['no'] ), ( ! empty( $allow_create_subdirectories ) ? $lang_global['yes'] : $lang_global['no'] ) );
		}
		if( $allow_modify_subdirectories != $old_allow_modify_subdirectories )
		{
			$result['change']['allow_modify_subdirectories'] = array( $lang_module['allow_modify_subdirectories'], ( ! empty( $old_allow_modify_subdirectories ) ? $lang_global['yes'] : $lang_global['no'] ), ( ! empty( $allow_modify_subdirectories ) ? $lang_global['yes'] : $lang_global['no'] ) );
		}
		if( $lev == 2 and $lev != $row['lev'] )
		{
			$result['change']['lev'] = array( $lang_module['lev'], $lang_global['level' . $row['lev']], $lang_global['level' . $lev] );
		}
		elseif( $lev == 3 and $lev != $row['lev'] )
		{
			$result['change']['lev'] = array( $lang_module['lev'], $lang_global['level' . $row['lev']], $lang_global['level' . $lev] );
			$old = array();
			if( ! empty( $old_modules ) )
			{
				foreach( $old_modules as $m )
				{
					$old[] = $site_mods[$m]['custom_title'];
				}
			}
			$old = ( ! empty( $old ) ) ? implode( ', ', $old ) : '';
			$new = array();
			if( ! empty( $modules ) )
			{
				foreach( $modules as $m )
				{
					$new[] = $site_mods[$m]['custom_title'];
				}
			}
			$new = ( ! empty( $new ) ) ? implode( ', ', $new ) : '';

			$result['change']['modules'] = array( $lang_module['nv_admin_modules'], $old, $new );
		}
		elseif( $lev == 3 and $lev == $row['lev'] )
		{
			if( ! empty( $add_modules ) or ! empty( $del_modules ) )
			{
				$old = array();
				if( ! empty( $old_modules ) )
				{
					foreach( $old_modules as $m )
					{
						$old[] = $site_mods[$m]['custom_title'];
					}
				}
				$old = ( ! empty( $old ) ) ? implode( ', ', $old ) : '';
				$new = array();
				if( ! empty( $modules ) )
				{
					foreach( $modules as $m )
					{
						$new[] = $site_mods[$m]['custom_title'];
					}
				}
				$new = ( ! empty( $new ) ) ? implode( ', ', $new ) : '';
				$result['change']['modules'] = array( $lang_module['nv_admin_modules'], $old, $new );
			}
		}
		if( $position != $row['position'] )
		{
			$result['change']['position'] = array( $lang_module['position'], $row['position'], $position );
		}

		if( empty( $result['change'] ) )
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '#aid' . $admin_id );
			exit();
		}
		nv_admin_edit_result( $result );
		exit();
	}
}
else
{
	$lev = intval( $row['lev'] );
	$modules = $old_modules;
	$position = $row['position'];
	$editor = $row['editor'];
	$allow_files_type = $old_allow_files_type;
	$allow_modify_files = $old_allow_modify_files;
	$allow_create_subdirectories = $old_allow_create_subdirectories;
	$allow_modify_subdirectories = $old_allow_modify_subdirectories;
}

$page_title = $lang_module['nv_admin_edit'];

$contents = array();
$contents['info'] = ( ! empty( $error ) ) ? $error : sprintf( $lang_module['nv_admin_edit_info'], $row_user['username'] );
$contents['is_error'] = ( ! empty( $error ) ) ? 1 : 0;
$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $admin_id;
if( defined( 'NV_IS_SPADMIN' ) and $row['admin_id'] != $admin_info['admin_id'] )
{
	$mods = array();
	$array_keys = array_keys( $site_mods );
	foreach( $array_keys as $mod )
	{
		$mods[$mod]['checked'] = in_array( $mod, $modules ) ? 1 : 0;
		$mods[$mod]['custom_title'] = $site_mods[$mod]['custom_title'];
	}

	$contents['lev'] = array( $lang_module['lev'], $lang_module['if_level3_selected'], $mods );

	if( defined( 'NV_IS_GODADMIN' ) )
	{
		array_push( $contents['lev'], $lev, $lang_global['level2'], $lang_global['level3'] );
	}
}
if( defined( 'NV_IS_GODADMIN' ) or ( defined( 'NV_IS_SPADMIN' ) and $row['lev'] != 1 and $row['admin_id'] != $admin_info['admin_id'] ) )
{
	$contents['position'] = array( $lang_module['position'], $position, $lang_module['position_info'] );
}

$editors = array();
$dirs = nv_scandir( NV_ROOTDIR . '/' . NV_EDITORSDIR, '/^[a-zA-Z0-9_]+$/' );
if( ! empty( $dirs ) )
{
	foreach( $dirs as $dir )
	{
		if( file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . $dir . '/nv.php' ) ) $editors[] = $dir;
	}
}

if( ! empty( $editors ) )
{
	$contents['editor'] = array( $lang_module['editor'], $editors, $editor, $lang_module['not_use'] );
}

if( defined( 'NV_IS_SPADMIN' ) )
{
	if( ! empty( $global_config['file_allowed_ext'] ) )
	{
		$contents['allow_files_type'] = array( $lang_module['allow_files_type'], $global_config['file_allowed_ext'], $allow_files_type );
	}

	$contents['allow_modify_files'] = array( $lang_module['allow_modify_files'], $allow_modify_files );
	$contents['allow_create_subdirectories'] = array( $lang_module['allow_create_subdirectories'], $allow_create_subdirectories );
	$contents['allow_modify_subdirectories'] = array( $lang_module['allow_modify_subdirectories'], $allow_modify_subdirectories );
}

// Parse content
$xtpl = new XTemplate( 'edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors' );
$xtpl->assign( 'CLASS', $contents['is_error'] ? ' class="error"' : '' );
$xtpl->assign( 'INFO', $contents['info'] );
$xtpl->assign( 'ACTION', $contents['action'] );
$xtpl->assign( 'LANG',$lang_module );

if( isset( $contents['editor'] ) )
{
	$xtpl->assign( 'EDITOR0', $contents['editor'][0] );
	$xtpl->assign( 'EDITOR3', $contents['editor'][3] );
	foreach( $contents['editor'][1] as $edt )
	{
		$xtpl->assign( 'VALUE', $edt );
		$xtpl->assign( 'SELECTED', $edt == $contents['editor'][2] ? ' selected="selected"' : '' );
		$xtpl->parse( 'edit.editor.loop' );
	}
	$xtpl->parse( 'edit.editor' );
}

if( isset( $contents['allow_files_type'] ) )
{
	$xtpl->assign( 'ALLOW_FILES_TYPE', $contents['allow_files_type'][0] );

	foreach( $contents['allow_files_type'][1] as $tp )
	{
		$xtpl->assign( 'VALUE', $tp );
		$xtpl->assign( 'CHECKED', in_array( $tp, $contents['allow_files_type'][2] ) ? ' checked="checked"' : '' );
		$xtpl->parse( 'edit.allow_files_type.loop' );
	}
	$xtpl->parse( 'edit.allow_files_type' );
}

if( isset( $contents['allow_modify_files'] ) )
{
	$xtpl->assign( 'ALLOW_MODIFY_FILES', $contents['allow_modify_files'][0] );
	$xtpl->assign( 'CHECKED', $contents['allow_modify_files'][1] ? ' checked="checked"' : '' );
	$xtpl->parse( 'edit.allow_modify_files' );
}

if( isset( $contents['allow_create_subdirectories'] ) )
{
	$xtpl->assign( 'ALLOW_CREATE_SUBDIRECTORIES', $contents['allow_create_subdirectories'][0] );
	$xtpl->assign( 'CHECKED', $contents['allow_create_subdirectories'][1] ? ' checked="checked"' : '' );
	$xtpl->parse( 'edit.allow_create_subdirectories' );
}

if( isset( $contents['allow_modify_subdirectories'] ) )
{
	$xtpl->assign( 'ALLOW_MODIFY_SUBDIRECTORIES', $contents['allow_modify_subdirectories'][0] );
	$xtpl->assign( 'CHECKED', $contents['allow_modify_subdirectories'][1] ? ' checked="checked"' : '' );
	$xtpl->parse( 'edit.allow_modify_subdirectories' );
}

if( isset( $contents['lev'] ) )
{
	$xtpl->assign( 'LEV0', $contents['lev'][0] );
	if( isset( $contents['lev'][3] ) )
	{
		$xtpl->assign( 'LEV1', $contents['lev'][1] );
		$xtpl->assign( 'LEV4', $contents['lev'][4] );
		$xtpl->assign( 'LEV5', $contents['lev'][5] );
		$xtpl->assign( 'CHECKED2', $contents['lev'][3] == 2 ? ' checked="checked"' : '' );
		$xtpl->assign( 'CHECKED3', $contents['lev'][3] == 3 ? ' checked="checked"' : '' );
		$xtpl->assign( 'STYLE', $contents['lev'][3] == 3 ? 'visibility:visible;display:block;' : 'visibility:hidden;display:none;' );
		$xtpl->parse( 'edit.lev.if' );
	}
	foreach( $contents['lev'][2] as $mod => $value )
	{
		$xtpl->assign( 'VALUE', $mod );
		$xtpl->assign( 'CHECKED', ! empty( $value['checked'] ) ? 'checked="checked"' : '' );
		$xtpl->assign( 'CUSTOM_TITLE', $value['custom_title'] );
		$xtpl->parse( 'edit.lev.loop' );
	}
	$xtpl->parse( 'edit.lev' );
}

if( isset( $contents['position'] ) )
{
	$xtpl->assign( 'POSITION0', $contents['position'][0] );
	$xtpl->assign( 'POSITION1', $contents['position'][1] );
	$xtpl->assign( 'POSITION2', $contents['position'][2] );
	$xtpl->parse( 'edit.position' );
}

$xtpl->parse( 'edit' );
$contents = $xtpl->text( 'edit' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';