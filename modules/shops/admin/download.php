<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 16 Jan 2015 02:23:16 GMT
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$data = array();
$error = array();
$table_name = $db_config['prefix'] . "_" . $module_data . "_files";
$data['id'] = $nv_Request->get_int( 'id', 'get', 0 );
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if( $nv_Request->isset_request( 'del', 'post,get' ) )
{
	$id = $nv_Request->get_int( 'id', 'post,get', 0 );
	if( empty( $id ) ) die( 'NO' );

	$count = $db->query( 'SELECT COUNT(*) FROM ' . $table_name . ' WHERE id=' . $id )->fetchColumn();
	if( $count > 0 )
	{
		$result = $db->query( 'DELETE FROM ' . $table_name . ' WHERE id=' . $id );
		if( $result )
		{
			$result = $db->query( 'DELETE FROM ' . $table_name . '_rows WHERE id_files=' . $id );
			nv_del_moduleCache( $module_name );
			die( 'OK' );
		}
	}
	die( 'NO' );
}

if( $nv_Request->isset_request( 'change_active', 'get,post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$sql = 'SELECT id FROM ' . $table_name . ' WHERE id=' . $id;
	$id = $db->query( $sql )->fetchColumn();
	if( empty( $id ) ) die( 'NO_' . $id );

	$new_status = $nv_Request->get_bool( 'new_status', 'post' );
	$new_status = ( int )$new_status;

	$sql = 'UPDATE ' . $table_name . ' SET status=' . $new_status . ' WHERE id=' . $id;
	$db->query( $sql );
	nv_del_moduleCache( $module_name );
	die( 'OK' );
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$field_lang = nv_file_table( $table_name );
	$data['id'] = $nv_Request->get_int( 'id', 'post', 0 );
	$data['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$data['description'] = $nv_Request->get_textarea( 'description', '', 'br' );
	$data['path'] = $nv_Request->get_title( 'path', 'post', '' );

	if( empty( $data['title'] ) )
	{
		$error[] = $lang_module['download_files_error_title'];
	}

	if( empty( $data['path'] ) )
	{
		$error[] = $lang_module['download_files_error_path'];
	}
	elseif( file_exists( NV_ROOTDIR . $data['path'] ) )
	{
		$data['path'] = str_replace( NV_UPLOADS_DIR . '/' . $module_name . '/', '', $data['path'] );
	}

	if( $data['id'] > 0 )
	{
		$stmt = $db->prepare( "UPDATE " . $table_name . " SET path=:path, " . NV_LANG_DATA . "_title=:title, " . NV_LANG_DATA . "_description=:description WHERE id =" . $data['id'] );
		$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
		$stmt->bindParam( ':path', $data['path'], PDO::PARAM_STR );
		$stmt->bindParam( ':description', $data['description'], PDO::PARAM_STR );
		if( $stmt->execute() )
		{
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			die();
		}
		else
		{
			$error[] = $lang_module['errorsave'];
		}
	}
	else
	{
		$listfield = "";
		$listvalue = "";

		foreach( $field_lang as $field_lang_i )
		{
			list( $flang, $fname ) = $field_lang_i;
			$listfield .= ", " . $flang . "_" . $fname;
			if( $flang == NV_LANG_DATA )
			{
				$listvalue .= ", " . $db->quote( $data[$fname] );
			}
			else
			{
				$listvalue .= ", " . $db->quote( $data[$fname] );
			}
		}

		$stmt = $db->prepare( "INSERT INTO " . $table_name . " (id, path, addtime, status " . $listfield . ") VALUES (NULL, :path, " . NV_CURRENTTIME . ", 1 " . $listvalue . ")" );
		$stmt->bindParam( ':path', $data['path'], PDO::PARAM_STR );
		if( $stmt->execute() )
		{
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			die();
		}
		else
		{
			$error[] = $lang_module['errorsave'];
		}
	}
}
elseif( $data['id'] > 0 )
{
	$lang_module['download_file_add'] = $lang_module['download_file_edit'];
	$data = $db->query( 'SELECT id, ' . NV_LANG_DATA . '_title title, ' . NV_LANG_DATA . '_description description, path FROM ' . $table_name . ' WHERE id=' . $data['id'] )->fetch();
}
else
{
	$data['id'] = 0;
	$data['path'] = '';
	$data['status'] = 1;
	$data['addtime'] = NV_CURRENTTIME;
	$data[NV_LANG_DATA . '_title'] = '';
	$data[NV_LANG_DATA . '_description'] = '';
}

$per_page = 20;
$page = $nv_Request->get_int( 'page', 'post,get', 1 );
$array_search = array();
$array_search['keywords'] = $nv_Request->get_title( 'keywords', 'get', '' );
$array_search['status'] = $nv_Request->get_int( 'status', 'get', -1 );
$where = '';

$db->sqlreset( )
	->select( 'COUNT(*)' )
	->from( $db_config['prefix'] . '_' . $module_data . '_files' );

if( !empty( $array_search['keywords'] ) )
{
	$where .= ' AND ' . NV_LANG_DATA . '_title LIKE :q_title OR ' . NV_LANG_DATA . '_description LIKE :q_description';
}

if( $array_search['status'] >= 0 )
{
	$where .= ' AND status = ' . $array_search['status'];
}

if( ! empty( $where ) )
{
	$db->where( '1=1' . $where );
}

$sth = $db->prepare( $db->sql( ) );

if( !empty( $array_search['keywords'] ) )
{
	$sth->bindValue( ':q_title', '%' . $array_search['keywords'] . '%' );
	$sth->bindValue( ':q_description', '%' . $array_search['keywords'] . '%' );
}

$sth->execute( );
$num_items = $sth->fetchColumn( );

$db->select( 'id, path, addtime, status, ' . NV_LANG_DATA . '_title title, ' . NV_LANG_DATA . '_description description' )->order( 'id DESC' )->limit( $per_page )->offset( ($page - 1) * $per_page );
$sth = $db->prepare( $db->sql( ) );

if( !empty( $array_search['keywords'] ) )
{
	$sth->bindValue( ':q_title', '%' . $array_search['keywords'] . '%' );
	$sth->bindValue( ':q_description', '%' . $array_search['keywords'] . '%' );
}
$sth->execute( );

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_UPLOADS_DIR', NV_UPLOADS_DIR );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'SEARCH', $array_search );
$xtpl->assign( 'UPLOADS_FILES_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/files' );
$xtpl->assign( 'ACTION', $base_url );

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if( !empty( $array_search['keywords'] ) )
{
	$base_url .= '&keywords=' . $array_search['keywords'];
}

if( $array_search['status'] >= 0 )
{
	$base_url .= '&status=' . $array_search['status'];
}

while( $view = $sth->fetch( ) )
{
	$view['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'] . '#edit';
	$view['count_product'] = $db->query( 'SELECT COUNT(*) FROM ' . $table_name . '_rows WHERE id_files=' . $view['id'] )->fetchColumn();
	$view['addtime'] = nv_date( 'H:i d/m/Y', $view['addtime'] );
	$view['active'] = $view['status'] ? 'checked="checked"' : '';
	$xtpl->assign( 'VIEW', $view );
	$xtpl->parse( 'main.loop' );
}

$array_status = array( '1' => $lang_module['review_status_1'], '0' => $lang_module['review_status_0'] );
foreach( $array_status as $key => $value )
{
	$xtpl->assign( 'STATUS', array( 'key' => $key, 'value' => $value, 'selected' => $array_search['status'] == $key ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.status' );
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
if( !empty( $generate_page ) )
{
	$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

if( !empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['download'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';