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
$popup = $nv_Request->get_bool( 'popup', 'get', 0 );
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$groups_list = nv_groups_list();

if( $nv_Request->isset_request( 'get_files', 'post,get' ) )
{
	$option = '';
	$sql = 'SELECT id, ' . NV_LANG_DATA . '_title title FROM ' . $db_config['prefix'] . '_' . $module_data . '_files WHERE status=1';
	$array_files = nv_db_cache( $sql, 'id', $module_name );

	if( !empty( $array_files ) )
	{
		foreach( $array_files as $files )
		{
			$option .= '<option value="' . $files['id'] . '">' . $files['title'] . '</option>';
		}
	}
	die( $option );
}

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

	$_dowload_groups = $nv_Request->get_array( 'download_groups', 'post', array() );
	if( in_array( -1, $_dowload_groups ) )
	{
		$data['download_groups'] = '-1';
	}
	else
	{
		$data['download_groups'] = ! empty( $_dowload_groups ) ? implode( ',', nv_groups_post( array_intersect( $_dowload_groups, array_keys( $groups_list ) ) ) ) : '';
	}

	$data['filesize'] = 0;
	$data['extension'] = '';

	$data['path'] = str_replace( NV_UPLOADS_DIR . '/' . $module_upload . '/', '', $data['path'] );
	$real_file = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . $data['path'];

	if( empty( $data['title'] ) )
	{
		die( 'NO_' . $lang_module['download_files_error_title'] );
	}

	if( empty( $data['path'] ) )
	{
		die( 'NO_' . $lang_module['download_files_error_path'] );
	}
	elseif( file_exists( $real_file ) and ( $filesize = filesize( $real_file ) ) != 0 )
	{
		$data['filesize'] = $filesize;
		$data['extension'] = nv_getextension( $real_file );
	}
	else
	{
		die( 'NO_' . $lang_module['download_files_error_path_valid'] );
	}

	if( $data['id'] > 0 )
	{
		$stmt = $db->prepare( "UPDATE " . $table_name . " SET path=:path, filesize=:filesize, extension=:extension, download_groups=:download_groups, " . NV_LANG_DATA . "_title=:title, " . NV_LANG_DATA . "_description=:description WHERE id =" . $data['id'] );
		$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
		$stmt->bindParam( ':path', $data['path'], PDO::PARAM_STR );
		$stmt->bindParam( ':filesize', $data['filesize'], PDO::PARAM_STR );
		$stmt->bindParam( ':extension', $data['extension'], PDO::PARAM_STR );
		$stmt->bindParam( ':download_groups', $data['download_groups'], PDO::PARAM_STR );
		$stmt->bindParam( ':description', $data['description'], PDO::PARAM_STR );
		if( $stmt->execute() )
		{
			nv_del_moduleCache( $module_name );
			die( 'OK' );
		}
		else
		{
			die( 'NO_' . $lang_module['errorsave'] );
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

		$stmt = $db->prepare( "INSERT INTO " . $table_name . " (id, path, filesize, extension, addtime, download_groups, status " . $listfield . ") VALUES (NULL, :path, :filesize, :extension, " . NV_CURRENTTIME . ", :download_groups, 1 " . $listvalue . ")" );
		$stmt->bindParam( ':path', $data['path'], PDO::PARAM_STR );
		$stmt->bindParam( ':filesize', $data['filesize'], PDO::PARAM_STR );
		$stmt->bindParam( ':extension', $data['extension'], PDO::PARAM_STR );
		$stmt->bindParam( ':download_groups', $data['download_groups'], PDO::PARAM_STR );
		if( $stmt->execute() )
		{
			nv_del_moduleCache( $module_name );
			die( 'OK' );
		}
		else
		{
			die( 'NO_' . $lang_module['errorsave'] );
		}
	}
}

if( $data['id'] > 0 )
{
	$lang_module['download_file_add'] = $lang_module['download_file_edit'];
	$data = $db->query( 'SELECT id, ' . NV_LANG_DATA . '_title title, ' . NV_LANG_DATA . '_description description, path, download_groups FROM ' . $table_name . ' WHERE id=' . $data['id'] )->fetch();
}
else
{
	$data['id'] = 0;
	$data['path'] = '';
	$data['status'] = 1;
	$data['download_groups'] = -1;
	$data['addtime'] = NV_CURRENTTIME;
	$data[NV_LANG_DATA . '_title'] = '';
	$data[NV_LANG_DATA . '_description'] = '';
}

$array_search = array();
$array_search['keywords'] = $nv_Request->get_title( 'keywords', 'get', '' );
$array_search['status'] = $nv_Request->get_int( 'status', 'get', -1 );
if( !$popup )
{
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
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
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_UPLOADS_DIR', NV_UPLOADS_DIR );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'SEARCH', $array_search );
$xtpl->assign( 'UPLOADS_FILES_DIR', NV_UPLOADS_DIR . '/' . $module_upload . '/files' );
$xtpl->assign( 'ACTION', $base_url );
$xtpl->assign( 'POPUP', $popup );

if( !$popup )
{
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
		$view['download_hits'] = 0;
		$result = $db->query( 'SELECT download_hits FROM ' . $table_name . '_rows WHERE id_files=' . $view['id'] );
		if( $result->rowCount() > 0 )
		{
			while( list( $download_hits ) = $result->fetch( 3 ) )
			{
				$view['download_hits'] += $download_hits;
			}
		}

		$view['addtime'] = nv_date( 'H:i d/m/Y', $view['addtime'] );
		$view['active'] = $view['status'] ? 'checked="checked"' : '';
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.non_popup.loop' );
	}

	$array_status = array( '1' => $lang_module['review_status_1'], '0' => $lang_module['review_status_0'] );
	foreach( $array_status as $key => $value )
	{
		$xtpl->assign( 'STATUS', array( 'key' => $key, 'value' => $value, 'selected' => $array_search['status'] == $key ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.non_popup.status' );
	}

	$download_groups = explode( ',', $data['download_groups'] );
	$xtpl->assign( 'DOWNLOAD_GROUPS', array(
		'value' => -1,
		'checked' => in_array( -1, $download_groups ) ? ' checked="checked"' : '',
		'title' => $lang_module['download_setting_groups_module']
	) );
	$xtpl->parse( 'main.non_popup.download_groups' );

	foreach( $groups_list as $_group_id => $_title )
	{
		$xtpl->assign( 'DOWNLOAD_GROUPS', array(
			'value' => $_group_id,
			'checked' => in_array( $_group_id, $download_groups ) ? ' checked="checked"' : '',
			'title' => $_title
		) );
		$xtpl->parse( 'main.non_popup.download_groups' );
	}

	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.non_popup.generate_page' );
	}

	$xtpl->parse( 'main.non_popup' );
}
else
{
	$download_groups = explode( ',', $data['download_groups'] );
	$xtpl->assign( 'DOWNLOAD_GROUPS', array(
		'value' => -1,
		'checked' => in_array( -1, $download_groups ) ? ' checked="checked"' : '',
		'title' => $lang_module['download_setting_groups_module']
	) );
	$xtpl->parse( 'main.popup.download_groups' );

	foreach( $groups_list as $_group_id => $_title )
	{
		$xtpl->assign( 'DOWNLOAD_GROUPS', array(
			'value' => $_group_id,
			'checked' => in_array( $_group_id, $download_groups ) ? ' checked="checked"' : '',
			'title' => $_title
		) );
		$xtpl->parse( 'main.popup.download_groups' );
	}

	$xtpl->parse( 'main.popup' );
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
echo nv_admin_theme( $contents, !$popup );
include NV_ROOTDIR . '/includes/footer.php';