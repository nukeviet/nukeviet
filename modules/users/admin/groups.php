<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:5
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_global['mod_groups'];
$contents = '';
//Lay danh sach nhom
$sql = "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "` WHERE `idsite` = " . $global_config['idsite'] . " OR (`idsite` =0 AND group_id > 3 AND `siteus` = 1) ORDER BY `idsite`, `weight`";
$result = $db->sql_query( $sql );
$groupsList = array();
$groupcount = 0;
$weight_siteus = 0;
while( $row = $db->sql_fetch_assoc( $result ) )
{
	if( $row['idsite'] == $global_config['idsite'] )
	{
		++$groupcount;
	}
	else
	{
		$row['weight'] = ++$weight_siteus;
		$row['title'] = '<b>' . $row['title'] . '</b>';
	}
	$groupsList[$row['group_id']] = $row;
}

//Neu khong co nhom => chuyen den trang tao nhom
if( ! $groupcount and ! $nv_Request->isset_request( 'add', 'get' ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add" );
	die();
}

//Thay doi thu tu nhom
if( $nv_Request->isset_request( 'cWeight, id', 'post' ) )
{
	$group_id = $nv_Request->get_int( 'id', 'post' );
	$cWeight = $nv_Request->get_int( 'cWeight', 'post' );
	if( ! isset( $groupsList[$group_id] ) OR ! defined( 'NV_IS_SPADMIN' ) OR $group_id <= 3 OR $groupsList[$group_id]['idsite'] != $global_config['idsite'] ) die( "ERROR" );

	$cWeight = min( $cWeight, $groupcount );

	$query = array();
	$query[] = "WHEN `group_id` = " . $group_id . " THEN " . $cWeight;
	unset( $groupsList[$group_id] );
	--$groupcount;
	$idList = array_keys( $groupsList );

	for( $i = 0, $weight = 1; $i < $groupcount; ++$i, ++$weight )
	{
		if( $weight == $cWeight ) ++$weight;
		$query[] = "WHEN `group_id` = " . $idList[$i] . " THEN " . $weight;
	}

	$query = "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "` SET `weight` = CASE " . implode( " ", $query ) . " END";
	$db->sql_query( $query );

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['changeGroupWeight'], "group_id: " . $group_id, $admin_info['userid'] );
	die( "OK" );
}

//Thay doi tinh trang hien thi cua nhom
if( $nv_Request->isset_request( 'act', 'post' ) )
{
	$group_id = $nv_Request->get_int( 'act', 'post' );
	if( ! isset( $groupsList[$group_id] ) OR ! defined( 'NV_IS_SPADMIN' ) OR $group_id <= 3 OR $groupsList[$group_id]['idsite'] != $global_config['idsite'] ) die( "ERROR|" . $groupsList[$group_id]['act'] );

	$act = $groupsList[$group_id]['act'] ? 0 : 1;
	$query = "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "` SET `act`=" . $act . " WHERE `group_id`=" . $group_id . " LIMIT 1";
	$db->sql_query( $query );

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['ChangeGroupAct'], "group_id: " . $group_id, $admin_info['userid'] );
	die( "OK|" . $act );
}

//Xoa nhom
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	$group_id = $nv_Request->get_int( 'del', 'post', 0 );
	if( ! isset( $groupsList[$group_id] ) OR ! defined( 'NV_IS_SPADMIN' ) OR $group_id <= 3 OR $groupsList[$group_id]['idsite'] != $global_config['idsite'] ) die( $lang_module['error_group_not_found'] );

	$array_groups = array();
	$result_gru = $db->sql_query( "SELECT `group_id`, `userid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "_users` WHERE `userid` IN (SELECT `userid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "_users` WHERE `group_id`=" . $group_id . ")" );
	while( $row = $db->sql_fetch_assoc( $result_gru ) )
	{
		$array_groups[$row['userid']][$row['group_id']] = 1;
	}
	foreach( $array_groups as $userid => $gr )
	{
		unset( $gr[$group_id] );
		$in_groups = array_keys( $gr );
		$db->sql_query( "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` SET `in_groups`='" . implode( ',', $in_groups ) . "' WHERE `userid`=" . $userid );
	}

	$db->sql_query( "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id` = " . $group_id . " LIMIT 1" );
	$db->sql_query( "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "_users` WHERE `group_id` = " . $group_id );

	unset( $groupsList[$group_id] );
	--$groupcount;
	$idList = array_keys( $groupsList );

	$query = array();
	for( $i = 0, $weight = 1; $i < $groupcount; ++$i, ++$weight )
	{
		$query[] = "WHEN `group_id` = " . $idList[$i] . " THEN " . $weight;
	}

	if( ! empty( $query ) )
	{
		$query = "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "` SET `weight` = CASE " . implode( " ", $query ) . " END";
		$db->sql_query( $query );
	}

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['delGroup'], "group_id: " . $group_id, $admin_info['userid'] );
	die( "OK" );
}

//Them thanh vien vao nhom
if( $nv_Request->isset_request( 'gid,uid', 'post' ) )
{
	$gid = $nv_Request->get_int( 'gid', 'post', 0 );
	$uid = $nv_Request->get_int( 'uid', 'post', 0 );
	if( ! isset( $groupsList[$gid] ) OR $gid <= 3 ) die( $lang_module['error_group_not_found'] );

	if( $groupsList[$gid]['idsite'] != $global_config['idsite'] AND $groupsList[$gid]['idsite'] == 0 )
	{
		$query = $db->sql_query( "SELECT `idsite` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $uid );
		if( $db->sql_numrows( $query ) )
		{
			list( $idsite_us ) = $db->sql_fetchrow( $query );
			if( $idsite_us != $global_config['idsite'] )
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
	$result_gru = $db->sql_query( "SELECT `group_id` FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "_users` WHERE `userid`=" . $uid );
	while( $row_gru = $db->sql_fetch_assoc( $result_gru ) )
	{
		$in_groups[] = $row_gru['group_id'];
	}
	$db->sql_query( "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` SET `in_groups`='" . implode( ',', $in_groups ) . "' WHERE `userid`=" . $uid );

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['addMemberToGroup'], "Member Id: " . $uid . " group ID: " . $gid, $admin_info['userid'] );

	die( "OK" );
}

//Loai thanh vien khoi nhom
if( $nv_Request->isset_request( 'gid,exclude', 'post' ) )
{
	$gid = $nv_Request->get_int( 'gid', 'post', 0 );
	$uid = $nv_Request->get_int( 'exclude', 'post', 0 );
	if( ! isset( $groupsList[$gid] ) OR $gid <= 3 ) die( $lang_module['error_group_not_found'] );

	if( $groupsList[$gid]['idsite'] != $global_config['idsite'] AND $groupsList[$gid]['idsite'] == 0 )
	{
		$query = $db->sql_query( "SELECT `idsite` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $uid );
		if( $db->sql_numrows( $query ) )
		{
			list( $idsite_us ) = $db->sql_fetchrow( $query );
			if( $idsite_us != $global_config['idsite'] )
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
	$result_gru = $db->sql_query( "SELECT `group_id` FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "_users` WHERE `userid`=" . $uid );
	while( $row_gru = $db->sql_fetch_assoc( $result_gru ) )
	{
		$in_groups[] = $row_gru['group_id'];
	}
	$db->sql_query( "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` SET `in_groups`='" . implode( ',', $in_groups ) . "' WHERE `userid`=" . $uid );

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['exclude_user2'], "Member Id: " . $uid . " group ID: " . $gid, $admin_info['userid'] );
	die( "OK" );
}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );

//Danh sach thanh vien (AJAX)
if( $nv_Request->isset_request( 'listUsers', 'get' ) )
{
	$group_id = $nv_Request->get_int( 'listUsers', 'get', 0 );
	if( ! isset( $groupsList[$group_id] ) ) die( $lang_module['error_group_not_found'] );

	$sql = "SELECT `userid`, `username`, `full_name`, `email`, `idsite` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `userid` IN (SELECT `userid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "_users` WHERE `group_id`=" . $group_id . ")";
	$query = $db->sql_query( $sql );
	$numberusers = $db->sql_numrows( $query );
	if( $numberusers != $groupsList[$group_id]['number'] )
	{
		$db->sql_query( "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "` SET `number` = " . $numberusers . " WHERE `group_id`=" . $group_id );
	}

	$title = ( $group_id <= 3 ) ? $lang_global['level' . $group_id] : $groupsList[$group_id]['title'];
	$xtpl->assign( 'PTITLE', sprintf( $lang_module['users_in_group_caption'], $title, $numberusers ) );
	$xtpl->assign( 'GID', $group_id );

	if( $numberusers )
	{
		$idsite = ( $global_config['idsite'] == $groupsList[$group_id]['idsite'] ) ? 0 : $global_config['idsite'];
		while( $row = $db->sql_fetchrow( $query, 2 ) )
		{
			$xtpl->assign( 'LOOP', $row );
			if( $group_id > 3 AND ( $idsite == 0 OR $idsite == $row['idsite'] ) )
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
	if( ! isset( $groupsList[$group_id] ) )
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
		die();
	}

	$filtersql = " `userid` NOT IN (SELECT `userid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "_users` WHERE `group_id`=" . $group_id . ")";
	if( $groupsList[$group_id]['idsite'] != $global_config['idsite'] AND $groupsList[$group_id]['idsite'] == 0 )
	{
		$filtersql .= " AND `idsite`=" . $global_config['idsite'];
	}
	$xtpl->assign( 'FILTERSQL', nv_base64_encode( $crypt->aes_encrypt( $filtersql, md5( $global_config['sitekey'] . $client_info['session_id'] ) ) ) );
	$xtpl->assign( 'GID', $group_id );

	if( $group_id > 3 )
	{
		$xtpl->parse( 'userlist.adduser' );
	}
	$xtpl->parse( 'userlist' );
	$contents = $xtpl->text( 'userlist' );

	include ( NV_ROOTDIR . '/includes/header.php' );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . '/includes/footer.php' );
	exit();
}

//Them + sua nhom
if( $nv_Request->isset_request( 'add', 'get' ) or $nv_Request->isset_request( 'edit, id', 'get' ) )
{
	if( defined( 'NV_IS_SPADMIN' ) )
	{
		$post = array();
		if( $nv_Request->isset_request( 'edit', 'get' ) )
		{
			$post['id'] = $nv_Request->get_int( 'id', 'get' );
			if( empty( $post['id'] ) or ! isset( $groupsList[$post['id']] ) OR $post['id'] <= 3 OR $groupsList[$post['id']]['idsite'] != $global_config['idsite'] )
			{

				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
				die();
			}

			$xtpl->assign( 'PTITLE', $lang_module['nv_admin_edit'] );
			$xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&edit&id=" . $post['id'] );
			$log_title = $lang_module['nv_admin_edit'];
		}
		else
		{
			$xtpl->assign( 'PTITLE', $lang_module['nv_admin_add'] );
			$xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add" );
			$log_title = $lang_module['nv_admin_add'];
		}

		if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

		if( $nv_Request->isset_request( 'save', 'post' ) )
		{
			$post['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
			if( empty( $post['title'] ) )
			{
				die( $lang_module['title_empty'] );
			}

			// Kiểm tra trùng tên nhóm
			$_sql = "SELECT `group_id` FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "` WHERE `title` LIKE '" . $db->dblikeescape( $post['title'] ) . "' AND `group_id`!= " . intval( $post['id'] ) . " AND (`idsite`=" . $global_config['idsite'] . " OR (`idsite`=0 AND `siteus`=1))";
			if( $db->sql_numrows( $db->sql_query( $_sql ) ) )
			{
				die( sprintf( $lang_module['error_title_exists'], $post['title'] ) );
			}

			$post['content'] = $nv_Request->get_editor( 'content', '', NV_ALLOWED_HTML_TAGS );
			$test_content = trim( strip_tags( $post['content'] ) );
			$post['content'] = ! empty( $test_content ) ? nv_editor_nl2br( $post['content'] ) : "";

			$post['exp_time'] = $nv_Request->get_title( 'exp_time', 'post', '' );

			if( preg_match( "/^([\d]{1,2})\/([\d]{1,2})\/([\d]{4})$/", $post['exp_time'], $matches ) )
			{
				$post['exp_time'] = mktime( 23, 59, 59, $matches[2], $matches[1], $matches[3] );
			}
			else
			{
				$post['exp_time'] = 0;
			}

			$post['public'] = $nv_Request->get_int( 'public', 'post', 0 );
			if( $post['public'] != 1 ) $post['public'] = 0;

			$post['siteus'] = $nv_Request->get_int( 'siteus', 'post', 0 );
			if( $post['siteus'] != 1 ) $post['siteus'] = 0;

			if( isset( $post['id'] ) AND $post['id'] > 3 )
			{
				$query = "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "` SET
					`title`=" . $db->dbescape( $post['title'] ) . ",
					`content`=" . $db->dbescape( $post['content'] ) . ",
					`exp_time`='" . $post['exp_time'] . "',
					`public`='" . $post['public'] . "',
					`siteus`='" . $post['siteus'] . "'
					WHERE `group_id`=" . $post['id'] . " LIMIT 1";
				$ok = $db->sql_query( $query );
			}
			elseif( $nv_Request->isset_request( 'add', 'get' ) )
			{
				list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "` WHERE `idsite`=" . $global_config['idsite'] ) );
				$weight = intval( $weight ) + 1;
				$query = "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "`
					(`group_id`, `title`, `content`, `add_time`, `exp_time`, `public`, `weight`, `act`, `idsite`, `number`, `siteus`)
					VALUES (NULL, " . $db->dbescape( $post['title'] ) . ", " . $db->dbescape( $post['content'] ) . ", " . NV_CURRENTTIME . ", " . $post['exp_time'] . ",
					" . $post['public'] . ", " . $weight . ", 1, " . $global_config['idsite'] . ", 0, " . $post['siteus'] . ");";
				$ok = $post['id'] = $db->sql_query_insert_id( $query );
			}
			if( $ok )
			{
				nv_del_moduleCache( $module_name );
				nv_insert_logs( NV_LANG_DATA, $module_name, $log_title, "Id: " . $post['id'], $admin_info['userid'] );
				die( "OK" );
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
			$post['exp_time'] = ! empty( $post['exp_time'] ) ? date( "d/m/Y", $post['exp_time'] ) : "";
			$post['public'] = $post['public'] ? " checked=\"checked\"" : "";
			$post['siteus'] = $post['siteus'] ? " checked=\"checked\"" : "";
		}
		else
		{
			$post['title'] = $post['content'] = $post['exp_time'] = '';
			$post['public'] = '';
		}

		if( ! empty( $post['content'] ) ) $post['content'] = nv_htmlspecialchars( $post['content'] );

		$xtpl->assign( 'DATA', $post );

		if( defined( 'NV_CONFIG_DIR' ) AND empty( $global_config['idsite'] ) )
		{
			$xtpl->parse( 'add.siteus' );
		}

		if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
		{
			$_cont = nv_aleditor( 'content', '100%', '300px', $post['content'] );
		}
		else
		{
			$_cont = "<textarea style=\"width:100%;height:300px\" name=\"content\" id=\"content\">" . $post['content'] . "</textarea>";
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

	include ( NV_ROOTDIR . '/includes/header.php' );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . '/includes/footer.php' );
	die();
}

//Danh sach nhom
if( $nv_Request->isset_request( 'list', 'get' ) )
{
	foreach( $groupsList as $group_id => $values )
	{
		$xtpl->assign( 'GROUP_ID', $group_id );
		$loop = array(
			'title' => $values['title'],
			'add_time' => nv_date( "d/m/Y H:i", $values['add_time'] ),
			'exp_time' => ! empty( $values['exp_time'] ) ? nv_date( "d/m/Y H:i", $values['exp_time'] ) : $lang_global['unlimited'],
			'public' => $values['public'] ? " checked=\"checked\"" : "",
			'number' => number_format( $values['number'] ),
			'act' => $values['act'] ? " checked=\"checked\"" : ""
		);

		if( defined( 'NV_IS_SPADMIN' ) AND $group_id > 3 AND $values['idsite'] == $global_config['idsite'] )
		{
			$_bg = ( empty( $global_config['idsite'] ) ) ? 4 : 1;
			for( $i = $_bg; $i <= $groupcount; $i++ )
			{
				$opt = array( 'value' => $i, 'selected' => $i == $values['weight'] ? " selected=\"selected\"" : "" );
				$xtpl->assign( 'NEWWEIGHT', $opt );
				$xtpl->parse( 'list.loop.option' );
			}
			$xtpl->parse( 'list.loop.action' );
		}
		else
		{
			$opt = array( 'value' => $values['weight'], 'selected' => " selected=\"selected\"" );
			$xtpl->assign( 'NEWWEIGHT', $opt );
			$xtpl->parse( 'list.loop.option' );

			$loop['act'] .= ' disabled="disabled"';
			if( $group_id <= 3 )
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

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>