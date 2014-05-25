<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

/**
 * nv_FixWeightCat()
 *
 * @param integer $parentid
 * @return
 */
function nv_FixWeightCat( $parentid = 0 )
{
	global $db, $module_data;

	$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
	$result = $db->query( $sql );
	$weight = 0;
	while( $row = $result->fetch() )
	{
		++$weight;
		$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_categories SET weight=' . $weight . ' WHERE id=' . $row['id'] );
	}
}

/**
 * nv_del_cat()
 *
 * @param mixed $catid
 * @return
 */
function nv_del_cat( $catid )
{
	global $db, $module_name, $module_data, $admin_info;

	$sql = 'SELECT parentid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id=' . $catid;
	list( $p, $title ) = $db->query( $sql )->fetch( 3 );

	$sql = 'SELECT id, fileupload, fileimage FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE catid=' . $catid;
	$result = $db->query( $sql );

	$ids = array();
	while( list( $id, $fileupload, $fileimage ) = $result->fetch( 3 ) )
	{
		$ids[] = $id;
	}

	if( ! empty( $ids ) )
	{
		$ids = implode( ',', $ids );
		$sql = 'DELETE FROM ' . NV_PREFIXLANG . '_comments WHERE module=' . $db->quote( $module_name ) . ' AND id IN (' . $ids . ')';
		$db->query( $sql );

		$sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE fid IN (' . $ids . ')';
		$db->query( $sql );
	}

	$sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE catid=' . $catid;
	$db->query( $sql );

	$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE parentid=' . $catid;
	$result = $db->query( $sql );
	while( list( $id ) = $result->fetch( 3 ) )
	{
		nv_del_cat( $id );
	}

	$sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id=' . $catid;
	$db->query( $sql );

    nv_del_moduleCache( $module_name );

	nv_insert_logs( NV_LANG_DATA, $module_data, 'Delete Category', $title, $admin_info['userid'] );
}

$array = array();
$error = '';
$groups_list = nv_groups_list();

// Add cat
if( $nv_Request->isset_request( 'add', 'get' ) )
{
	$page_title = $lang_module['addcat_titlebox'];
	$is_error = false;
	if( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$array['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
		$array['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
		$array['description'] = $nv_Request->get_title( 'description', 'post', '', 1 );
		$array['alias'] = $nv_Request->get_title( 'alias', 'post', '' );
		$array['alias'] = ( $array['alias'] == '' ) ? change_alias( $array['title'] ) : change_alias( $array['alias'] );
		if( empty( $array['title'] ) )
		{
			$error = $lang_module['error_cat2'];
			$is_error = true;
		}
		else
		{
			if( ! empty( $array['parentid'] ) )
			{
				$sql = 'SELECT COUNT(*) AS count FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id=' . $array['parentid'];
				$count = $db->query( $sql )->fetchColumn();
				if( ! $count )
				{
					$error = $lang_module['error_cat3'];
					$is_error = true;
				}
			}

			if( ! $is_error )
			{
				$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE alias= :alias' );
				$stmt->bindParam( ':alias', $array['alias'], PDO::PARAM_STR );
				$stmt->execute();
				$count = $stmt->fetchColumn();
				if( $count )
				{
					$error = $lang_module['error_cat1'];
					$is_error = true;
				}
			}
		}

		if( ! $is_error )
		{
			$_groups_post = $nv_Request->get_array( 'groups_view', 'post', array() );
			$array['groups_view'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

			$_groups_post = $nv_Request->get_array( 'groups_download', 'post', array() );
			$array['groups_download'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

			$sql = 'SELECT MAX(weight) AS new_weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE parentid=' . $array['parentid'];
			$new_weight = $db->query( $sql )->fetchColumn();
			$new_weight = ( int )$new_weight + 1;

			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_categories (parentid, title, alias, description, groups_view, groups_download, weight, status) VALUES (
				 ' . $array['parentid'] . ',
				 :title,
				 :alias,
				 :description,
				 :groups_view,
				 :groups_download,
				 ' . $new_weight . ',
				 1)';
			$data_insert = array();
			$data_insert['title'] = $array['title'];
			$data_insert['alias'] = $array['alias'];
			$data_insert['description'] = $array['description'];
			$data_insert['groups_view'] = $array['groups_view'];
			$data_insert['groups_download'] = $array['groups_download'];

			$catid = $db->insert_id( $sql, 'id', $data_insert );

			if( ! $catid )
			{
				$error = $lang_module['error_cat4'];
				$is_error = true;
			}
			else
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['addcat_titlebox'], $array['title'], $admin_info['userid'] );
				nv_del_moduleCache( $module_name );

				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat' );
				exit();
			}
		}
	}
	else
	{
		$array['parentid'] = 0;
		$array['title'] = '';
		$array['alias'] = '';
		$array['description'] = '';
		$array['groups_view'] =	$array['groups_download'] = 6;
	}

	$listcats = array(
		array(
			'id' => 0,
			'name' => $lang_module['category_cat_maincat'],
			'selected' => ''
		)
	);
	$listcats = $listcats + nv_listcats( $array['parentid'] );

	$groups_view = explode( ',', $array['groups_view'] );
	$array['groups_view'] = array();
	foreach( $groups_list as $key => $title )
	{
		$array['groups_view'][] = array(
			'key' => $key,
			'title' => $title,
			'checked' => in_array( $key, $groups_view ) ? ' checked="checked"' : ''
		);
	}

	$groups_download = explode( ',', $array['groups_download'] );
	$array['groups_download'] = array();
	foreach( $groups_list as $key => $title )
	{
		$array['groups_download'][] = array(
			'key' => $key,
			'title' => $title,
			'checked' => in_array( $key, $groups_download ) ? ' checked="checked"' : ''
		);
	}

	$xtpl = new XTemplate( 'cat_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;add=1' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array );

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.error' );
	}

	foreach( $listcats as $cat )
	{
		$xtpl->assign( 'LISTCATS', $cat );
		$xtpl->parse( 'main.parentid' );
	}

	foreach( $array['groups_view'] as $group )
	{
		$xtpl->assign( 'GROUPS_VIEW', $group );
		$xtpl->parse( 'main.groups_view' );
	}

	foreach( $array['groups_download'] as $group )
	{
		$xtpl->assign( 'GROUPS_DOWNLOAD', $group );
		$xtpl->parse( 'main.groups_download' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';

	exit();
}

// Edit cat
if( $nv_Request->isset_request( 'edit', 'get' ) )
{
	$page_title = $lang_module['editcat_cat'];

	$catid = $nv_Request->get_int( 'catid', 'get', 0 );

	if( empty( $catid ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat' );
		exit();
	}

	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id=' . $catid;
	$row = $db->query( $sql )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat' );
		exit();
	}

	$is_error = false;

	if( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$array['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
		$array['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
		$array['description'] = $nv_Request->get_title( 'description', 'post', '' );

		$_groups_post = $nv_Request->get_array( 'groups_view', 'post', array() );
		$array['groups_view'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

		$_groups_post = $nv_Request->get_array( 'groups_download', 'post', array() );
		$array['groups_download'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

		$array['alias'] = $nv_Request->get_title( 'alias', 'post', '' );
		$array['alias'] = ( $array['alias'] == '' ) ? change_alias( $array['title'] ) : change_alias( $array['alias'] );

		if( empty( $array['title'] ) )
		{
			$error = $lang_module['error_cat2'];
			$is_error = true;
		}
		else
		{
			if( ! empty( $array['parentid'] ) )
			{
				$sql = 'SELECT COUNT(*) AS count FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id=' . $array['parentid'];
				$result = $db->query( $sql );
				$count = $result->fetchColumn();

				if( ! $count )
				{
					$error = $lang_module['error_cat3'];
					$is_error = true;
				}
			}

			if( ! $is_error )
			{
				$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id!=' . $catid . ' AND alias= :alias' );
				$stmt->bindParam( ':alias', $array['alias'], PDO::PARAM_STR );
				$stmt->execute();

				$count = $stmt->fetchColumn();
				if( $count )
				{
					$error = $lang_module['error_cat1'];
					$is_error = true;
				}
			}
		}

		if( ! $is_error )
		{
			if( $array['parentid'] != $row['parentid'] )
			{
				$sql = 'SELECT MAX(weight) AS new_weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE parentid=' . $array['parentid'];
				$result = $db->query( $sql );
				$new_weight = $result->fetchColumn();
				$new_weight = ( int )$new_weight;
				++$new_weight;
			}
			else
			{
				$new_weight = $row['weight'];
			}

			$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_categories SET
				 parentid=' . $array['parentid'] . ',
				 title= :title,
				 alias= :alias,
				 description= :description,
				 groups_view= :groups_view,
				 groups_download= :groups_download,
				 weight=' . $new_weight . '
				 WHERE id=' . $catid );

			$stmt->bindParam( ':title', $array['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $array['alias'], PDO::PARAM_STR );
			$stmt->bindParam( ':description', $array['description'], PDO::PARAM_STR, strlen( $array['description'] ) );
			$stmt->bindParam( ':groups_view', $array['groups_view'], PDO::PARAM_STR );
			$stmt->bindParam( ':groups_download', $array['groups_download'], PDO::PARAM_STR );

			if( ! $stmt->execute() )
			{
				$error = $lang_module['error_cat5'];
				$is_error = true;
			}
			else
			{
				if( $array['parentid'] != $row['parentid'] )
				{
					nv_FixWeightCat( $row['parentid'] );
				}

				nv_del_moduleCache( $module_name );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['editcat_cat'], $array['title'], $admin_info['userid'] );

				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat' );
				exit();
			}
		}
	}
	else
	{
		$array['parentid'] = ( int )$row['parentid'];
		$array['title'] = $row['title'];
		$array['alias'] = $row['alias'];
		$array['description'] = $row['description'];

		$array['groups_view'] = explode( ',', $row['groups_view'] );
		$array['groups_download'] = explode( ',', $row['groups_download'] );
	}

	$listcats = array(
		array(
			'id' => 0,
			'name' => $lang_module['category_cat_maincat'],
			'selected' => ''
		)
	);
	$listcats = $listcats + nv_listcats( $array['parentid'], $catid );

	$groups_view = $array['groups_view'];
	$array['groups_view'] = array();
	foreach( $groups_list as $key => $title )
	{
		$array['groups_view'][] = array(
			'key' => $key,
			'title' => $title,
			'checked' => in_array( $key, $groups_view ) ? ' checked="checked"' : ''
		);
	}

	$groups_download = $array['groups_download'];
	$array['groups_download'] = array();
	foreach( $groups_list as $key => $title )
	{
		$array['groups_download'][] = array(
			'key' => $key,
			'title' => $title,
			'checked' => in_array( $key, $groups_download ) ? ' checked="checked"' : ''
		);
	}

	$xtpl = new XTemplate( 'cat_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;edit=1&amp;catid=' . $catid );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array );

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.error' );
	}

	foreach( $listcats as $cat )
	{
		$xtpl->assign( 'LISTCATS', $cat );
		$xtpl->parse( 'main.parentid' );
	}

	foreach( $array['groups_view'] as $group )
	{
		$xtpl->assign( 'GROUPS_VIEW', $group );
		$xtpl->parse( 'main.groups_view' );
	}


	foreach( $array['groups_download'] as $group )
	{
		$xtpl->assign( 'GROUPS_DOWNLOAD', $group );
		$xtpl->parse( 'main.groups_download' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';

	exit();
}

// Delete cat
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$catid = $nv_Request->get_int( 'catid', 'post', 0 );
	$sql = 'SELECT id, parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id=' . $catid;
	$result = $db->query( $sql );
	list( $catid, $parentid ) = $result->fetch( 3 );

	if( empty( $catid ) )
	{
		die( 'NO' );
	}

	nv_del_cat( $catid );
	nv_FixWeightCat( $parentid );
	nv_del_moduleCache( $module_name );

	die( 'OK' );
}

// Change weight cat
if( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$catid = $nv_Request->get_int( 'catid', 'post', 0 );
	$new = $nv_Request->get_int( 'new', 'post', 0 );

	$query = 'SELECT parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id=' . $catid;
	$row = $db->query( $query )->fetch();
	if( empty( $row ) ) die( 'NO' );

	$query = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id!=' . $catid . ' AND parentid=' . $row['parentid'] . ' ORDER BY weight ASC';
	$result = $db->query( $query );
	$weight = 0;
	while( $row = $result->fetch() )
	{
		++$weight;
		if( $weight == $new ) ++$weight;
		$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_categories SET weight=' . $weight . ' WHERE id=' . $row['id'] );
	}
	$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_categories SET weight=' . $new . ' WHERE id=' . $catid );

	nv_del_moduleCache( $module_name );
	die( 'OK' );
}

// Active - Deactive
if( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$catid = $nv_Request->get_int( 'catid', 'post', 0 );

	$query = 'SELECT status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id=' . $catid;
	$row = $db->query( $query )->fetch();
	if( empty( $row) ) die( 'NO' );

	$status = $row['status'] ? 0 : 1;

	$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_categories SET status=' . $status . ' WHERE id=' . $catid;
	$db->query( $sql );

	nv_del_moduleCache( $module_name );

	die( 'OK' );
}

// List cat
$page_title = $lang_module['download_catmanager'];

$pid = $nv_Request->get_int( 'pid', 'get', 0 );

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE parentid=' . $pid . ' ORDER BY weight ASC';
$_array_cat = $db->query( $sql )->fetchAll();
$num = sizeof( $_array_cat );

if( ! $num )
{
	$_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat';
	if( empty( $pid ) )
	{
		$_url .='&add=1';
	}
	Header( 'Location: ' . $_url );
	exit();
}

if( $pid )
{
	$sql2 = 'SELECT title,parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE id=' . $pid;
	$result2 = $db->query( $sql2 );
	list( $parentid, $parentid2 ) = $result2->fetch( 3 );
	$caption = sprintf( $lang_module['table_caption2'], $parentid );
	$parentid = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;pid=' . $parentid2 . '">' . $parentid . '</a>';
}
else
{
	$caption = $lang_module['table_caption1'];
	$parentid = $lang_module['category_cat_maincat'];
}

$list = array();
$a = 0;
foreach ( $_array_cat as $row )
{
	$numsub = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE parentid=' . $row['id'] )->fetchColumn();
	if( $numsub )
	{
		$numsub = ' (<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;pid=' . $row['id'] . '">' . $numsub . ' ' . $lang_module['category_cat_sub'] . '</a>)';
	}
	else
	{
		$numsub = '';
	}

	$weight = array();
	for( $i = 1; $i <= $num; ++$i )
	{
		$weight[$i]['title'] = $i;
		$weight[$i]['pos'] = $i;
		$weight[$i]['selected'] = ( $i == $row['weight'] ) ? ' selected="selected"' : '';
	}

	$list[$row['id']] = array(
		'id' => ( int )$row['id'],
		'title' => $row['title'],
		'titlelink' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;catid=' . $row['id'],
		'numsub' => $numsub,
		'parentid' => $parentid,
		'weight' => $weight,
		'status' => $row['status'] ? ' checked="checked"' : ''
	);

	++$a;
}

$xtpl = new XTemplate( 'cat_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'ADD_NEW_CAT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;add=1' );
$xtpl->assign( 'TABLE_CAPTION', $caption );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'LANG', $lang_module );

foreach( $list as $row )
{
	$xtpl->assign( 'ROW', $row );

	foreach( $row['weight'] as $weight )
	{
		$xtpl->assign( 'WEIGHT', $weight );
		$xtpl->parse( 'main.row.weight' );
	}

	$xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;edit=1&amp;catid=' . $row['id'] );
	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';