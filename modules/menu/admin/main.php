<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$array_menu_type = array();
$arr = array();

$arr['id'] = $arr['type_name'] = 0;
$arr['title'] = $array['description'] = "";
$arr['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );

if( $arr['id'] != 0 )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_menu` WHERE `id`=" . $arr['id'];
	$result = $db->sql_query( $sql );
	$arr = $db->sql_fetchrow( $result );

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

	if( empty( $id ) ) die( 'NO_' . $id );

	$query = "SELECT `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_menu` WHERE `id`=" . $id;
	$result = $db->sql_query( $query );
	$numrows = $db->sql_numrows( $result );

	if( $numrows != 1 ) die( 'NO_' . $id );

	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_about', "aboutid  " . $id, $admin_info['userid'] );

	$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_menu` WHERE `id` = " . $id;
	$db->sql_query( $query );

	if( $db->sql_affectedrows() > 0 )
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
	$arr_menu['title'] = filter_text_input( 'title', 'post', '', 1 );
	$arr_menu['description'] = filter_text_input( 'description', 'post', '', 1, 255 );

	if( empty( $arr_menu['title'] ) )
	{
		$error = $lang_module['error_menu_block'];
	}
	elseif( $arr_menu['id'] == 0 )
	{
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_menu` (`id`, `title`,`menu_item`, `description`) VALUES (
			NULL, 
			" . $db->dbescape( $arr_menu['title'] ) . ",
			'', 
			" . $db->dbescape( $arr_menu['description'] ) . "
		)";

		if( $db->sql_query_insert_id( $sql ) )
		{
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			exit();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	else
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_menu` SET 
			`title`=" . $db->dbescape( $arr_menu['title'] ) . ", 
			`description` =  " . $db->dbescape( $arr_menu['description'] ) . " 
		WHERE `id` =" . $arr_menu['id'];

		if( $db->sql_query( $sql ) )
		{
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			exit();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
}

// List menu
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_menu`";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

$error2 = "";

if( ! $all_page )
{
	$error2 = $lang_module['data_no'];
}
else
{
	$sql .= " ORDER BY `id` DESC";

	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 20;

	$sql2 = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
	$query2 = $db->sql_query( $sql2 );

	$array = array();

	$a = 0;

	while( $row = $db->sql_fetchrow( $query2 ) )
	{
		$arr_items = array();
		$b = 0;
		if( $row['menu_item'] != "" )
		{
			$arr_item = explode( ",", $row['menu_item'] );
			foreach( $arr_item as $key => $val )
			{
				$arr_items[] = $arr_menu_item[$val];
				$b = $b + 1;
			}
			$item = implode( "&nbsp;&nbsp; ", $arr_items );
		}
		else
		{
			$item = "";
		}

		++$a;
		$array[$row['id']] = array(
			'id' => ( int )$row['id'], //
			'nb' => ( int )$a,
			'title' => $row['title'], //
			'menu_item' => $item, //
			'num' => $b, //
			'link_view' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=add_menu&amp;mid=" . $row['id'], //
			'edit_url' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;add=1&amp;id=" . $row['id'], //
			'description' => $row['description'], //
		);
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
}

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;add=1" );

if( $error2 != "" )
{
	$xtpl->assign( 'ERROR', $error2 );
	$xtpl->parse( 'first.error' );
}
else
{
	if( ! empty( $array ) )
	{
		$i = 0;
		foreach( $array as $row )
		{
			$row['class'] = ( ++$i % 2 == 0 ) ? ' class="second"' : '';
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

	$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>