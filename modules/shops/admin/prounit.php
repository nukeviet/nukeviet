<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['prounit'];

$error = "";
$savecat = 0;

$data = array( "title" => "", 'note' => "" );
$table_name = $db_config['prefix'] . "_" . $module_data . "_units";
$data['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );

if( ! empty( $savecat ) )
{
	$field_lang = nv_file_table( $table_name );
	$data['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	$data['note'] = $nv_Request->get_title( 'note', 'post', '', 1 );

	if( $data['id'] == 0 )
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

		$sql = "INSERT INTO " . $table_name . " (id " . $listfield . ") VALUES (NULL " . $listvalue . ")";

		if( $db->insert_id( $sql ) )
		{
			nv_del_moduleCache( $module_name );

			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	else
	{
		$stmt = $db->prepare( "UPDATE " . $table_name . " SET " . NV_LANG_DATA . "_title= :title, " . NV_LANG_DATA . "_note = :note WHERE id =" . $data['id'] );
		$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
		$stmt->bindParam( ':note', $data['note'], PDO::PARAM_STR );
		if( $stmt->execute() )
		{
			$error = $lang_module['saveok'];

			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
}
else
{
	if( $data['id'] > 0 )
	{
		$data_old = $db->query( "SELECT * FROM " . $table_name . " WHERE id=" . $data['id'] )->fetch();
		$data = array(
			"id" => $data_old['id'],
			"title" => $data_old[NV_LANG_DATA . '_title'],
			"note" => $data_old[NV_LANG_DATA . '_note']
		);
	}
}

$xtpl = new XTemplate( "prounit.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'caption', $lang_module['prounit_info'] );

$count = 0;
$result = $db->query( "SELECT id, " . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_note FROM " . $table_name . " ORDER BY id DESC" );
while( list( $id, $title, $note ) = $result->fetch( 3 ) )
{
	$xtpl->assign( 'title', $title );
	$xtpl->assign( 'note', $note );
	$xtpl->assign( 'id', $id );
	$xtpl->assign( 'link_edit', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $id );
	$xtpl->assign( 'link_del', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delunit&id=" . $id );

	$xtpl->parse( 'main.data.row' );
	++$count;
}

$xtpl->assign( 'URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delunit" );
$xtpl->assign( 'URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );

if( $count > 0 ) $xtpl->parse( 'main.data' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';