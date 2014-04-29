<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:24
 */

if( ! defined( 'NV_IS_FILE_AUTHORS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['main'];

$admins = array();
if( $nv_Request->isset_request( 'id', 'get' ) )
{
	$admin_id = $nv_Request->get_int( 'id', 'get', 0 );
	$sql = 'SELECT t1.admin_id as admin_id, t1.check_num as check_num, t1.last_agent as last_agent, t1.last_ip as last_ip, t1.last_login as last_login, t1.files_level as files_level, t1.lev as lev,t1.position as position, t1.editor as editor, t1.is_suspend as is_suspend, t1.susp_reason as susp_reason,
	t2.username as username, t2.email as email, t2.full_name as full_name, t2.view_mail as view_mail, t2.regdate as regdate
	FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE admin_id=' . $admin_id;
	$adminrows = $db->query( $sql )->fetchAll();
	$numrows = sizeof( $adminrows );

	if( $numrows != 1 )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}
}
else
{
	$sql = 'SELECT t1.admin_id as admin_id, t1.check_num as check_num, t1.last_agent as last_agent, t1.last_ip as last_ip, t1.last_login as last_login, t1.files_level as files_level, t1.lev as lev,t1.position as position, t1.editor as editor, t1.is_suspend as is_suspend, t1.susp_reason as susp_reason,
		t2.username as username, t2.email as email, t2.full_name as full_name, t2.view_mail as view_mail, t2.regdate as regdate
		FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid ORDER BY t1.lev ASC';

	$adminrows = $db->query( $sql )->fetchAll();
	$numrows = sizeof( $adminrows );
}

if( $numrows )
{
	$sql = 'SELECT * FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC';
	$list_modules = nv_db_cache( $sql, '', 'modules' );
	foreach ($adminrows as $row )
	{
		$login = $row['username'];
		$email = ( defined( 'NV_IS_SPADMIN' ) ) ? $row['email'] : ( ( $row['admin_id'] == $admin_info['admin_id'] ) ? $row['email'] : ( intval( $row['view_mail'] ) ? $row['email'] : '' ) );
		$email = ! empty( $email ) ? nv_EncodeEmail( $email ) : '';
		$level = intval( $row['lev'] );
		if( $level == 1 )
		{
			$level_txt = '<strong>' . $lang_global['level1'] . '</strong>';
		}
		elseif( $level == 2 )
		{
			$level_txt = '<strong>' . $lang_global['level2'] . '</strong>';
		}
		else
		{
			$array_mod = array();
			foreach( $list_modules as $row_mod )
			{
				if( ! empty( $row_mod['admins'] ) and in_array( $row['admin_id'], explode( ',', $row_mod['admins'] ) ) )
				{
					$array_mod[] = $row_mod['custom_title'];
				}
			}
			$level_txt = implode( ', ', $array_mod );
		}
		$last_login = intval( $row['last_login'] );
		$last_login = $last_login ? nv_date( 'l, d/m/Y H:i', $last_login ) : $lang_module['last_login0'];
		$last_agent = $row['last_agent'];
		$row['full_name'] = empty( $row['full_name'] ) ? $row['full_name'] : $row['username'];

		$browser = array_combine( array( 'key', 'name' ), explode( '|', nv_getBrowser( $last_agent ) ) );

		$os = array_combine( array( 'key', 'name' ), explode( '|', nv_getOs( $last_agent ) ) );

		$is_suspend = intval( $row['is_suspend'] );
		if( empty( $is_suspend ) )
		{
			$is_suspend = $lang_module['is_suspend0'];
		}
		else
		{
			$last_reason = unserialize( $row['susp_reason'] );
			$last_reason = array_shift( $last_reason );
			list( $susp_admin_id, $susp_admin_name ) = $db->query( 'SELECT userid,full_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . intval( $last_reason['start_admin'] ) )->fetch( 3 );
			$susp_admin_name = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_LANG_VARIABLE . "=" . $module_name . "&amp;id=" . $susp_admin_id . "\">" . $susp_admin_name . "</a>";
			$is_suspend = sprintf( $lang_module['is_suspend1'], nv_date( 'd/m/Y H:i', $last_reason['starttime'] ), $susp_admin_name, $last_reason['info'] );
		}

		$thead = array();
		$thead['level'] = $level;
		if( defined( 'NV_IS_GODADMIN' ) )
		{
			$thead['edit'] = 1;
			$thead['chg_is_suspend'] = ( $row['admin_id'] != $admin_info['admin_id'] ) ? 1 : 0;
			$thead['del'] = ( $row['admin_id'] != $admin_info['admin_id'] ) ? 1 : 0;
		}
		elseif( defined( 'NV_IS_SPADMIN' ) )
		{
			if( $row['lev'] == 1 )
			{
				$thead['edit'] = 0;
				$thead['chg_is_suspend'] = 0;
				$thead['del'] = 0;
			}
			elseif( $row['lev'] == 2 )
			{
				if( $row['admin_id'] == $admin_info['admin_id'] )
				{
					$thead['edit'] = 1;
					$thead['chg_is_suspend'] = 0;
					$thead['del'] = 0;
				}
				else
				{
					$thead['edit'] = 0;
					$thead['chg_is_suspend'] = 0;
					$thead['del'] = 0;
				}
			}
			elseif( $global_config['spadmin_add_admin'] == 1 )
			{
				$thead['edit'] = 1;
				$thead['chg_is_suspend'] = 1;
				$thead['del'] = 1;
			}
			else
			{
				$thead['edit'] = 0;
				$thead['chg_is_suspend'] = 0;
				$thead['del'] = 0;
			}
		}
		else
		{
			$thead['edit'] = ( $row['admin_id'] == $admin_info['admin_id'] ) ? 1 : 0;
			$thead['chg_is_suspend'] = 0;
			$thead['del'] = 0;
		}

		if( ! empty( $thead['edit'] ) )
		{
			$thead['edit'] = array( NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $row['admin_id'], $lang_global['edit'] );
		}

		if( ! empty( $thead['chg_is_suspend'] ) )
		{
			$thead['chg_is_suspend'] = array( NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=suspend&amp;admin_id=' . $row['admin_id'], $lang_module['chg_is_suspend2'] );
		}
		if( ! empty( $thead['del'] ) )
		{
			$thead['del'] = array( NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=del&amp;admin_id=' . $row['admin_id'], $lang_global['delete'] );
		}

		if( empty( $row['files_level'] ) )
		{
			$allow_files_type = array();
			$allow_modify_files = $allow_create_subdirectories = $allow_modify_subdirectories = 0;
		}
		else
		{
			list( $allow_files_type, $allow_modify_files, $allow_create_subdirectories, $allow_modify_subdirectories ) = explode( '|', $row['files_level'] );
			$allow_files_type = ! empty( $allow_files_type ) ? explode( ',', $allow_files_type ) : array();
			$allow_files_type = array_values( array_intersect( $global_config['file_allowed_ext'], $allow_files_type ) );
		}

		$admins[$row['admin_id']] = array();
		$admins[$row['admin_id']]['caption'] = ( $row['admin_id'] == $admin_info['admin_id'] ) ? sprintf( $lang_module['admin_info_title2'], $row['full_name'] ) : sprintf( $lang_module['admin_info_title1'], $row['full_name'] );
		$admins[$row['admin_id']]['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;id=' . $row['admin_id'];
		$admins[$row['admin_id']]['thead'] = $thead;
		$admins[$row['admin_id']]['options'] = array();
		$admins[$row['admin_id']]['options']['login'] = array( $lang_module['login'], $login );
		$admins[$row['admin_id']]['options']['email'] = array( $lang_module['email'], $email );
		$admins[$row['admin_id']]['options']['full_name'] = array( $lang_module['full_name'], $row['full_name'] );
		$admins[$row['admin_id']]['options']['lev'] = array( $lang_module['lev'], $level_txt );
		$admins[$row['admin_id']]['options']['lev'] = array( $lang_module['lev'], $level_txt );
		$admins[$row['admin_id']]['options']['position'] = array( $lang_module['position'], $row['position'] );
		$admins[$row['admin_id']]['options']['is_suspend'] = array( $lang_module['is_suspend'], $is_suspend, $row['is_suspend'] );

		if( defined( 'NV_IS_SPADMIN' ) )
		{
			$admins[$row['admin_id']]['options']['editor'] = array( $lang_module['editor'], ! empty( $row['editor'] ) ? $row['editor'] : $lang_module['not_use'] );
			$admins[$row['admin_id']]['options']['allow_files_type'] = array( $lang_module['allow_files_type'], ! empty( $allow_files_type ) ? implode( ', ', $allow_files_type ) : $lang_global['no'] );
			$admins[$row['admin_id']]['options']['allow_modify_files'] = array( $lang_module['allow_modify_files'], ! empty( $allow_modify_files ) ? $lang_global['yes'] : $lang_global['no'] );
			$admins[$row['admin_id']]['options']['allow_create_subdirectories'] = array( $lang_module['allow_create_subdirectories'], ! empty( $allow_create_subdirectories ) ? $lang_global['yes'] : $lang_global['no'] );
			$admins[$row['admin_id']]['options']['allow_modify_subdirectories'] = array( $lang_module['allow_modify_subdirectories'], ! empty( $allow_modify_subdirectories ) ? $lang_global['yes'] : $lang_global['no'] );

			$admins[$row['admin_id']]['options']['regtime'] = array( $lang_module['regtime'], nv_date( 'l, d/m/Y H:i', $row['regdate'] ) );
			$admins[$row['admin_id']]['options']['last_login'] = array( $lang_module['last_login'], $last_login );
			$admins[$row['admin_id']]['options']['last_ip'] = array( $lang_module['last_ip'], $row['last_ip'] );
			$admins[$row['admin_id']]['options']['browser'] = array( $lang_module['browser'], $browser['name'] );
			$admins[$row['admin_id']]['options']['os'] = array( $lang_module['os'], $os['name'] );
		}
	}
}

if( ! empty( $admins ) )
{
	if( $global_config['authors_detail_main'] or $numrows == 1 )
	{
		$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
		foreach( $admins as $id => $values )
		{
			$xtpl->assign( 'ID', $id );
			$xtpl->assign( 'CAPTION', $values['caption'] );

			if( ! empty( $values['thead']['edit'] ) )
			{
				$xtpl->assign( 'EDIT_HREF', $values['thead']['edit'][0] );
				$xtpl->assign( 'EDIT_NAME', $values['thead']['edit'][1] );
				$xtpl->parse( 'main.loop.edit' );
			}

			if( ! empty( $values['thead']['chg_is_suspend'] ) )
			{
				$xtpl->assign( 'SUSPEND_HREF', $values['thead']['chg_is_suspend'][0] );
				$xtpl->assign( 'SUSPEND_NAME', $values['thead']['chg_is_suspend'][1] );
				$xtpl->parse( 'main.loop.suspend' );
			}

			if( ! empty( $values['thead']['del'] ) )
			{
				$xtpl->assign( 'DEL_HREF', $values['thead']['del'][0] );
				$xtpl->assign( 'DEL_NAME', $values['thead']['del'][1] );
				$xtpl->parse( 'main.loop.del' );
			}

			$xtpl->assign( 'OPTION_LEV', $values['options']['lev'][1] );
			$xtpl->assign( 'THREAD_LEV', $values['thead']['level'] );
			$xtpl->assign( 'NV_ADMIN_THEME', $global_config['admin_theme'] );

			$a = 0;
			foreach( $values['options'] as $key => $value )
			{
				if( ! empty( $value[1] ) )
				{
					$xtpl->assign( 'VALUE0', $value[0] );
					$xtpl->assign( 'VALUE1', $value[1] );
					++$a;
					$xtpl->parse( 'main.loop.option_loop' );
				}
			}
			$xtpl->parse( 'main.loop' );
		}
	}
	else
	{
		$xtpl = new XTemplate( 'list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
		$xtpl->assign( 'LANG', $lang_module );

		$a = 0;
		foreach( $admins as $id => $values )
		{
			if( ! empty( $values['thead']['edit'] ) )
			{
				$xtpl->assign( 'EDIT_HREF', $values['thead']['edit'][0] );
				$xtpl->assign( 'EDIT_NAME', $values['thead']['edit'][1] );
				$xtpl->parse( 'main.loop.edit' );
			}

			if( ! empty( $values['thead']['del'] ) )
			{
				$xtpl->assign( 'DEL_HREF', $values['thead']['del'][0] );
				$xtpl->assign( 'DEL_NAME', $values['thead']['del'][1] );
				$xtpl->parse( 'main.loop.del' );
			}

			$xtpl->assign( 'OPTION_LEV', $values['options']['lev'][1] );
			$xtpl->assign( 'THREAD_LEV', $values['thead']['level'] );
			$xtpl->assign( 'NV_ADMIN_THEME', $global_config['admin_theme'] );

			$data_row = array();
			$data_row['link'] = $values['link'];
			$data_row['login'] = $values['options']['login'][1];
			$data_row['email'] = $values['options']['email'][1];
			$data_row['lev'] = $values['options']['lev'][1];
			$data_row['position'] = $values['options']['position'][1];
			$data_row['is_suspend'] = ( $values['options']['is_suspend'][2] ) ? $lang_module['is_suspend2'] : $lang_module['is_suspend0'];

			$xtpl->assign( 'DATA', $data_row );

			if( ! empty( $values['thead']['chg_is_suspend'] ) )
			{
				$xtpl->assign( 'SUSPEND_HREF', $values['thead']['chg_is_suspend'][0] );
				$xtpl->assign( 'SUSPEND_NAME', ( $values['options']['is_suspend'][2] ) ? $lang_module['suspend0'] : $lang_module['suspend1'] );
				$xtpl->parse( 'main.loop.suspend' );
			}
			$xtpl->parse( 'main.loop' );
			++$a;
		}
	}

	$xtpl->parse( 'main' );

	$contents = $xtpl->text( 'main' );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';