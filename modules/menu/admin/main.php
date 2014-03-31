<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$array_menu_type = array();
$arr = array();

$arr['id'] = $arr['type_name'] = 0;
$arr['title'] = $array['description'] = '';
$arr['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );

if( $arr['id'] != 0 )
{
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $arr['id'];
	$result = $db->query( $sql );
	$arr = $result->fetch();

	if( empty( $arr ) )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
}

$page_title = $lang_module['m_list'];

// Delete menu
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$query = 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	$result = $db->query( $query );

	if( ! $result->fetchColumn() ) die( 'NO_' . $id );

	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_about', 'aboutid ' . $id, $admin_info['userid'] );

	$sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id;
	if( $db->exec( $sql ) )
	{
		nv_del_moduleCache( $module_name );
	}
	else
	{
		die( 'NO_' . $typeid );
	}

	die( 'OK_' . $typeid );
}

// Add/Edit menu
if( $nv_Request->get_int( 'save', 'post' ) )
{
	$arr_menu['id'] = $nv_Request->get_int( 'id', 'post', 0 );
	$arr_menu['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
	$arr_menu['description'] = nv_substr( $nv_Request->get_title( 'description', 'post', '', 1 ), 0, 255 );

	if( empty( $arr_menu['title'] ) )
	{
		$error = $lang_module['error_menu_block'];
	}
	elseif( $arr_menu['id'] == 0 )
	{
		$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . " (title, description) VALUES ( :title, :description )";
		$data_insert = array();
		$data_insert['title'] = $arr_menu['title'];
		$data_insert['description'] = $arr_menu['description'];
		if( $db->insert_id( $sql, 'id', $data_insert ) )
		{
			nv_del_moduleCache( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			exit();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	else
	{
		$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title= :title, description = :description WHERE id =' . $arr_menu['id'] );
		$stmt->bindParam( ':title', $arr_menu['title'], PDO::PARAM_STR );
		$stmt->bindParam( ':description', $arr_menu['description'], PDO::PARAM_STR );
		if( $stmt->execute() )
		{
			nv_del_moduleCache( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			exit();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
}

// List menu
$db->sqlreset()
	->select( 'COUNT(*)' )
	->from( NV_PREFIXLANG . '_' . $module_data );

$all_page = $db->query( $db->sql() )->fetchColumn();

$error2 = '';

if( ! $all_page )
{
	$error2 = $lang_module['data_no'];
}
else
{
	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 20;

	$db->select( '*' )
		->order( 'id DESC' )
		->limit( $per_page )
		->offset( $page );
	$query2 = $db->query( $db->sql() );

	$array = array();
	$a = 0;
	while( $row = $query2->fetch() )
	{
        $arr_items = array();
        $sql = "SELECT title FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE mid = " . $row['id'] . " ORDER BY sort ASC";
        $result = $db->query( $sql );
        while( list( $title_i ) = $result->fetch( 3 ) )
        {
            $arr_items[] = $title_i;
        }

		++$a;
		$array[$row['id']] = array(
			'id' => $row['id'],
			'nb' => $a,
			'title' => $row['title'],
			'menu_item' => implode( '&nbsp;&nbsp; ', $arr_items ),
			'num' => sizeof( $arr_items ),
			'link_view' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add_menu&amp;mid=' . $row['id'],
			'edit_url' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;add=1&amp;id=' . $row['id'],
			'description' => $row['description']
		);
	}

	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
}

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;add=1' );

if( $error2 != '' )
{
	$xtpl->assign( 'ERROR', $error2 );
	$xtpl->parse( 'first.error' );
}
else
{
	if( ! empty( $array ) )
	{
		foreach( $array as $row )
		{
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'first.table.loop1' );
		}
	}

	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'first.table.generate_page' );
	}

	$xtpl->parse( 'first.table' );
}

$xtpl->parse( 'first' );
$contents = $xtpl->text( 'first' );

// Prase Add/Edit template
if( $nv_Request->isset_request( 'add', 'get' ) )
{
	$page_title = ( ! $arr['id'] ) ? $lang_module['add_menu'] : $lang_module['edit_menu'];

	$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.error' );
	}

	foreach( $array_menu_type as $mtype )
	{
		$xtpl->assign( 'MTYPE', $mtype );
		$xtpl->parse( 'main.type_menu' );
	}

	$xtpl->assign( 'DATAFORM', $arr );

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>