<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 15:5
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_global['mod_groups'];
$contents = '';
//Lay danh sach nhom
$sql = 'SELECT * FROM ' . NV_GROUPS_GLOBALTABLE . ' WHERE idsite = ' . $global_config['idsite'] . ' or (idsite =0 AND group_id > 3 AND siteus = 1) ORDER BY idsite, weight';
$result = $db->query( $sql );
$groupsList = array();
$groupcount = 0;
$weight_siteus = 0;
while( $row = $result->fetch() )
{
	if( $row['idsite'] == $global_config['idsite'] )
	{
		++$groupcount;
	}
	else
	{
		$row['weight'] = ++$weight_siteus;
		$row['title'] = '<strong>' . $row['title'] . '</strong>';
	}
	$groupsList[$row['group_id']] = $row;
}

//Neu khong co nhom => chuyen den trang tao nhom
if( ! $groupcount and ! $nv_Request->isset_request( 'add', 'get' ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add' );
	die();
}

//Thay doi thu tu nhom
if( $nv_Request->isset_request( 'cWeight, id', 'post' ) )
{
	$group_id = $nv_Request->get_int( 'id', 'post' );
	$cWeight = $nv_Request->get_int( 'cWeight', 'post' );
	if( ! isset( $groupsList[$group_id] ) or ! defined( 'NV_IS_SPADMIN' ) or $group_id < 10 or $groupsList[$group_id]['idsite'] != $global_config['idsite'] ) die( 'ERROR' );

	$cWeight = min( $cWeight, $groupcount );

	$query = array();
	$query[] = 'WHEN group_id = ' . $group_id . ' THEN ' . $cWeight;
	unset( $groupsList[$group_id] );
	--$groupcount;
	$idList = array_keys( $groupsList );

	for( $i = 0, $weight = 1; $i < $groupcount; ++$i, ++$weight )
	{
		if( $weight == $cWeight ) ++$weight;
		$query[] = 'WHEN group_id = ' . $idList[$i] . ' THEN ' . $weight;
	}

	$query = 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET weight = CASE ' . implode( ' ', $query ) . ' END';
	$db->query( $query );

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['changeGroupWeight'], 'group_id: ' . $group_id, $admin_info['userid'] );
	die( 'OK' );
}

//Thay doi tinh trang hien thi cua nhom
if( $nv_Request->isset_request( 'act', 'post' ) )
{
	$group_id = $nv_Request->get_int( 'act', 'post' );
	if( ! isset( $groupsList[$group_id] ) or ! defined( 'NV_IS_SPADMIN' ) or $group_id < 10 or $groupsList[$group_id]['idsite'] != $global_config['idsite'] ) die( 'ERROR|' . $groupsList[$group_id]['act'] );

	$act = $groupsList[$group_id]['act'] ? 0 : 1;
	$query = 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET act=' . $act . ' WHERE group_id=' . $group_id;
	$db->query( $query );

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['ChangeGroupAct'], 'group_id: ' . $group_id, $admin_info['userid'] );
	die( 'OK|' . $act );
}

//Xoa nhom
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	$group_id = $nv_Request->get_int( 'del', 'post', 0 );
	if( ! isset( $groupsList[$group_id] ) or ! defined( 'NV_IS_SPADMIN' ) or $group_id < 10 or $groupsList[$group_id]['idsite'] != $global_config['idsite'] ) die( $lang_module['error_group_not_found'] );

	$array_groups = array();
	$result_gru = $db->query( 'SELECT group_id, userid FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid IN (SELECT userid FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE group_id=' . $group_id . ')' );
	while( $row = $result_gru->fetch() )
	{
		$array_groups[$row['userid']][$row['group_id']] = 1;
	}
	foreach( $array_groups as $userid => $gr )
	{
		unset( $gr[$group_id] );
		$in_groups = array_keys( $gr );
		$db->exec( "UPDATE " . NV_USERS_GLOBALTABLE . " SET in_groups='" . implode( ',', $in_groups ) . "' WHERE userid=" . $userid );
	}

	$db->query( 'DELETE FROM ' . NV_GROUPS_GLOBALTABLE . ' WHERE group_id = ' . $group_id );
	$db->query( 'DELETE FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE group_id = ' . $group_id );

	unset( $groupsList[$group_id] );
	--$groupcount;
	$idList = array_keys( $groupsList );

	$query = array();
	for( $i = 0, $weight = 1; $i < $groupcount; ++$i, ++$weight )
	{
		$query[] = 'WHEN group_id = ' . $idList[$i] . ' THEN ' . $weight;
	}

	if( ! empty( $query ) )
	{
		$query = 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET weight = CASE ' . implode( ' ', $query ) . ' END';
		$db->query( $query );
	}

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['delGroup'], 'group_id: ' . $group_id, $admin_info['userid'] );
	die( 'OK' );
}

//Them thanh vien vao nhom
if( $nv_Request->isset_request( 'gid,uid', 'post' ) )
{
	$gid = $nv_Request->get_int( 'gid', 'post', 0 );
	$uid = $nv_Request->get_int( 'uid', 'post', 0 );
	if( ! isset( $groupsList[$gid] ) or $gid < 10 ) die( $lang_module['error_group_not_found'] );

	if( $groupsList[$gid]['idsite'] != $global_config['idsite'] AND $groupsList[$gid]['idsite'] == 0 )
	{
		$row = $db->query( 'SELECT idsite FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $uid )->fetch();
		if( ! empty( $row ) )
		{
			if( $row['idsite'] != $global_config['idsite'] )
			{
				die( $lang_module['error_group_in_site'] );
			}
		}
		else
		{
			die( $lang_module['search_not_result'] );
		}
	}

	if( ! nv_groups_add_user( $gid, $uid ) )
	{
		die( $lang_module['search_not_result'] );
	}

	// Update for table users
	$in_groups = array();
	$result_gru = $db->query( 'SELECT group_id FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $uid );
	while( $row_gru = $result_gru->fetch() )
	{
		$in_groups[] = $row_gru['group_id'];
	}
	$db->exec( "UPDATE " . NV_USERS_GLOBALTABLE . " SET in_groups='" . implode( ',', $in_groups ) . "' WHERE userid=" . $uid );

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['addMemberToGroup'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid'] );

	die( 'OK' );
}

//Loai thanh vien khoi nhom
if( $nv_Request->isset_request( 'gid,exclude', 'post' ) )
{
	$gid = $nv_Request->get_int( 'gid', 'post', 0 );
	$uid = $nv_Request->get_int( 'exclude', 'post', 0 );
	if( ! isset( $groupsList[$gid] ) or $gid < 10 ) die( $lang_module['error_group_not_found'] );

	if( $groupsList[$gid]['idsite'] != $global_config['idsite'] AND $groupsList[$gid]['idsite'] == 0 )
	{
		$row = $db->query( 'SELECT idsite FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $uid )->fetch();
		if( ! empty( $row ) )
		{
			if( $row['idsite'] != $global_config['idsite'] )
			{
				die( $lang_module['error_group_in_site'] );
			}
		}
		else
		{
			die( $lang_module['search_not_result'] );
		}
	}

	if( ! nv_groups_del_user( $gid, $uid ) )
	{
		die( $lang_module['UserNotInGroup'] );
	}

	// Update for table users
	$in_groups = array();
	$result_gru = $db->query( 'SELECT group_id FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $uid );
	while( $row_gru = $result_gru->fetch() )
	{
		$in_groups[] = $row_gru['group_id'];
	}
	$db->query( "UPDATE " . NV_USERS_GLOBALTABLE . " SET in_groups='" . implode( ',', $in_groups ) . "' WHERE userid=" . $uid );

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['exclude_user2'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid'] );
	die( 'OK' );
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );

//Danh sach thanh vien (AJAX)
if( $nv_Request->isset_request( 'listUsers', 'get' ) )
{
	$group_id = $nv_Request->get_int( 'listUsers', 'get', 0 );
	if( ! isset( $groupsList[$group_id] ) ) die( $lang_module['error_group_not_found'] );

	$sql = 'SELECT userid, username, full_name, email, idsite FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (SELECT userid FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE group_id=' . $group_id . ')';
	$_rows = $db->query( $sql )->fetchAll();
	$numberusers = sizeof( $_rows );
	if( $numberusers != $groupsList[$group_id]['numbers'] )
	{
		$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = ' . $numberusers . ' WHERE group_id=' . $group_id );
	}

	$title = ( $group_id < 10 ) ? $lang_global['level' . $group_id] : $groupsList[$group_id]['title'];
	$xtpl->assign( 'PTITLE', sprintf( $lang_module['users_in_group_caption'], $title, $numberusers ) );
	$xtpl->assign( 'GID', $group_id );

	if( $numberusers )
	{
		$idsite = ( $global_config['idsite'] == $groupsList[$group_id]['idsite'] ) ? 0 : $global_config['idsite'];
		foreach ( $_rows as $row )
		{
			$xtpl->assign( 'LOOP', $row );
			if( $group_id > 3 and ( $idsite == 0 or $idsite == $row['idsite'] ) )
			{
				$xtpl->parse( 'listUsers.ifExists.loop.delete' );
			}
			$xtpl->parse( 'listUsers.ifExists.loop' );
		}
		$xtpl->parse( 'listUsers.ifExists' );
	}

	$xtpl->parse( 'listUsers' );
	$xtpl->out( 'listUsers' );
	exit();
}

//Danh sach thanh vien
if( $nv_Request->isset_request( 'userlist', 'get' ) )
{
	$group_id = $nv_Request->get_int( 'userlist', 'get', 0 );
	if( ! isset( $groupsList[$group_id] ) or ! ( $group_id < 4 or $group_id > 9 ))
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}

	$filtersql = ' userid NOT IN (SELECT userid FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE group_id=' . $group_id . ')';
	if( $groupsList[$group_id]['idsite'] != $global_config['idsite'] AND $groupsList[$group_id]['idsite'] == 0 )
	{
		$filtersql .= ' AND idsite=' . $global_config['idsite'];
	}
	$xtpl->assign( 'FILTERSQL', nv_base64_encode( $crypt->aes_encrypt( $filtersql, md5( $global_config['sitekey'] . $client_info['session_id'] ) ) ) );
	$xtpl->assign( 'GID', $group_id );

	if( $group_id > 9 )
	{
		$xtpl->parse( 'userlist.adduser' );
	}
	$xtpl->parse( 'userlist' );
	$contents = $xtpl->text( 'userlist' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

//Them + sua nhom
if( $nv_Request->isset_request( 'add', 'get' ) or $nv_Request->isset_request( 'edit, id', 'get' ) )
{
	if( defined( 'NV_IS_SPADMIN' ) )
	{
		$post = array();
		$post['id'] = $nv_Request->get_int( 'id', 'get' );
		if( $nv_Request->isset_request( 'edit', 'get' ) )
		{
			if( empty( $post['id'] ) or ! isset( $groupsList[$post['id']] ) or $post['id'] < 10 or $groupsList[$post['id']]['idsite'] != $global_config['idsite'] )
			{
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
				die();
			}

			$xtpl->assign( 'PTITLE', $lang_module['nv_admin_edit'] );
			$xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&edit&id=' . $post['id'] );
			$log_title = $lang_module['nv_admin_edit'];
		}
		else
		{
			$xtpl->assign( 'PTITLE', $lang_module['nv_admin_add'] );
			$xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add' );
			$log_title = $lang_module['nv_admin_add'];
		}

		if( defined( 'NV_EDITOR' ) ) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' ;

		if( $nv_Request->isset_request( 'save', 'post' ) )
		{
			$post['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
			if( empty( $post['title'] ) )
			{
				die( $lang_module['title_empty'] );
			}

			// Kiểm tra trùng tên nhóm
			$stmt = $db->prepare( 'SELECT group_id FROM ' . NV_GROUPS_GLOBALTABLE . ' WHERE title LIKE :title AND group_id!= ' . intval( $post['id'] ) . ' AND (idsite=' . $global_config['idsite'] . ' or (idsite=0 AND siteus=1))' );
			$stmt->bindParam( ':title', $post['title'], PDO::PARAM_STR );
			$stmt->execute();
			if( $stmt->fetchColumn() )
			{
				die( sprintf( $lang_module['error_title_exists'], $post['title'] ) );
			}

			$post['content'] = $nv_Request->get_editor( 'content', '', NV_ALLOWED_HTML_TAGS );

			$post['exp_time'] = $nv_Request->get_title( 'exp_time', 'post', '' );

			if( preg_match( '/^([\d]{1,2})\/([\d]{1,2})\/([\d]{4})$/', $post['exp_time'], $matches ) )
			{
				$post['exp_time'] = mktime( 23, 59, 59, $matches[2], $matches[1], $matches[3] );
			}
			else
			{
				$post['exp_time'] = 0;
			}

			$post['publics'] = $nv_Request->get_int( 'publics', 'post', 0 );
			if( $post['publics'] != 1 ) $post['publics'] = 0;

			$post['siteus'] = $nv_Request->get_int( 'siteus', 'post', 0 );
			if( $post['siteus'] != 1 ) $post['siteus'] = 0;

			if( isset( $post['id'] ) and $post['id'] > 3 )
			{
				$stmt = $db->prepare( "UPDATE " . NV_GROUPS_GLOBALTABLE . " SET
					title= :title,
					content= :content,
					exp_time='" . $post['exp_time'] . "',
					publics='" . $post['publics'] . "',
					siteus='" . $post['siteus'] . "'
					WHERE group_id=" . $post['id'] );

				$stmt->bindParam( ':title', $post['title'], PDO::PARAM_STR );
				$stmt->bindParam( ':content', $post['content'], PDO::PARAM_STR, strlen( $post['content'] ) );
				$ok = $stmt->execute();
			}
			elseif( $nv_Request->isset_request( 'add', 'get' ) )
			{
				$weight = $db->query( "SELECT max(weight) FROM " . NV_GROUPS_GLOBALTABLE . " WHERE idsite=" . $global_config['idsite'] )->fetchColumn();
				$weight = intval( $weight ) + 1;

				$_sql = "INSERT INTO " . NV_GROUPS_GLOBALTABLE . "
					(title, content, add_time, exp_time, publics, weight, act, idsite, numbers, siteus)
					VALUES ( :title, :content, " . NV_CURRENTTIME . ", " . $post['exp_time'] . ", " . $post['publics'] . ", " . $weight . ", 1, " . $global_config['idsite'] . ", 0, " . $post['siteus'] . ")";

				$data_insert = array();
				$data_insert['title'] = $post['title'];
				$data_insert['content'] = $post['content'];

				$ok = $post['id'] = $db->insert_id( $_sql, 'group_id', $data_insert );
			}
			if( $ok )
			{
				nv_del_moduleCache( $module_name );
				nv_insert_logs( NV_LANG_DATA, $module_name, $log_title, 'Id: ' . $post['id'], $admin_info['userid'] );
				die( 'OK' );
			}
			else
			{
				die( $lang_module['errorsave'] );
			}
		}

		if( $nv_Request->isset_request( 'edit', 'get' ) )
		{
			$post = $groupsList[$post['id']];
			$post['content'] = nv_editor_br2nl( $post['content'] );
			$post['exp_time'] = ! empty( $post['exp_time'] ) ? date( 'd/m/Y', $post['exp_time'] ) : '';
			$post['publics'] = $post['publics'] ? ' checked="checked"' : '';
			$post['siteus'] = $post['siteus'] ? ' checked="checked"' : '';
		}
		else
		{
			$post['title'] = $post['content'] = $post['exp_time'] = '';
			$post['publics'] = '';
		}

		$post['content'] = htmlspecialchars( nv_editor_br2nl( $post['content'] ) );

		$xtpl->assign( 'DATA', $post );

		if( defined( 'NV_CONFIG_DIR' ) and empty( $global_config['idsite'] ) )
		{
			$xtpl->parse( 'add.siteus' );
		}

		if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
		{
			$_cont = nv_aleditor( 'content', '100%', '300px', $post['content'] );
		}
		else
		{
			$_cont = '<textarea style="width:100%;height:300px" name="content" id="content">' . $post['content'] . '</textarea>';
		}
		$xtpl->assign( 'CONTENT', $_cont );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
		$xtpl->parse( 'add' );
		$contents = $xtpl->text( 'add' );
	}
	else
	{
		$contents = $lang_global['admin_no_allow_func'];
	}

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}

//Danh sach nhom
if( $nv_Request->isset_request( 'list', 'get' ) )
{
	$weight_op = 1;
	foreach( $groupsList as $group_id => $values )
	{
		$xtpl->assign( 'GROUP_ID', $group_id );
		if( $group_id < 4 or $group_id > 9  )
		{
			$link_userlist = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op .'&amp;userlist=' . $group_id;
		}
		elseif( $group_id == 4 )
		{
			$link_userlist = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
		}
		else
		{
			$link_userlist = '#';
		}

		$loop = array(
			'title' => $values['title'],
			'add_time' => nv_date( 'd/m/Y H:i', $values['add_time'] ),
			'exp_time' => ! empty( $values['exp_time'] ) ? nv_date( 'd/m/Y H:i', $values['exp_time'] ) : $lang_global['unlimited'],
			'publics' => $values['publics'] ? ' checked="checked"' : '',
			'number' => number_format( $values['numbers'] ),
			'act' => $values['act'] ? ' checked="checked"' : '',
			'link_userlist' => $link_userlist
		);

		if( defined( 'NV_IS_SPADMIN' ) and $group_id > 9 and $values['idsite'] == $global_config['idsite'] )
		{
			$_bg = ( empty( $global_config['idsite'] ) ) ? $weight_op : 1;
			for( $i = $_bg; $i <= $groupcount; $i++ )
			{
				$opt = array( 'value' => $i, 'selected' => $i == $values['weight'] ? ' selected="selected"' : '' );
				$xtpl->assign( 'NEWWEIGHT', $opt );
				$xtpl->parse( 'list.loop.option' );
			}
			$xtpl->parse( 'list.loop.action' );
		}
		else
		{
			++$weight_op;
			$opt = array( 'value' => $values['weight'], 'selected' => ' selected="selected"' );
			$xtpl->assign( 'NEWWEIGHT', $opt );
			$xtpl->parse( 'list.loop.option' );

			$loop['act'] .= ' disabled="disabled"';
			if( $group_id < 9 )
			{
				$loop['title'] = $lang_global['level' . $group_id];
			}
		}
		$xtpl->assign( 'LOOP', $loop );
		$xtpl->parse( 'list.loop' );
	}
	if( defined( 'NV_IS_SPADMIN' ) )
	{
		$xtpl->parse( 'list.action_js' );
	}
	$xtpl->parse( 'list' );
	$xtpl->out( 'list' );
	exit();
}
if( defined( 'NV_IS_SPADMIN' ) )
{
	$xtpl->parse( 'main.addnew' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';