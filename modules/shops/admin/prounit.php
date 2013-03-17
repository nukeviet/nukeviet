<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
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
	$data['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$data['note'] = filter_text_input( 'note', 'post', '', 1 );
	
	if( $data['id'] == 0 )
	{
		$listfield = "";
		$listvalue = "";
		
		foreach( $field_lang as $field_lang_i )
		{
			list( $flang, $fname ) = $field_lang_i;
			$listfield .= ", `" . $flang . "_" . $fname . "`";
			if( $flang == NV_LANG_DATA )
			{
				$listvalue .= ", " . $db->dbescape( $data[$fname] );
			}
			else
			{
				$listvalue .= ", " . $db->dbescape( $data[$fname] );
			}
		}
		
		$sql = "INSERT INTO `" . $table_name . "` (`id` " . $listfield . ") VALUES (NULL " . $listvalue . ")";
		
		if( $db->sql_query_insert_id( $sql ) )
		{
			$db->sql_freeresult();
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
		$sql = "UPDATE `" . $table_name . "` SET `" . NV_LANG_DATA . "_title`=" . $db->dbescape( $data['title'] ) . ", `" . NV_LANG_DATA . "_note` =  " . $db->dbescape( $data['note'] ) . " WHERE `id` =" . $data['id'];
		$db->sql_query( $sql );
		
		if( $db->sql_affectedrows() > 0 )
		{
			$error = $lang_module['saveok'];
			$db->sql_freeresult();
			
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
		$db->sql_freeresult();
	}
}
else
{
	if( $data['id'] > 0 )
	{
		$data_old = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . $table_name . "` WHERE id=" . $data['id'] ) );
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
$result = $db->sql_query( "SELECT `id`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_note` FROM `" . $table_name . "` ORDER BY `id` DESC" );
while( list( $id, $title, $note ) = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'title', $title );
	$xtpl->assign( 'note', $note );
	$xtpl->assign( 'id', $id );
	$xtpl->assign( 'link_edit', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $id );
	$xtpl->assign( 'link_del', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delunit&id=" . $id );
	
	$xtpl->parse( 'main.data.row' );
	$count++;
}

$xtpl->assign( 'URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delunit" );
$xtpl->assign( 'URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );

if( $count > 0 ) $xtpl->parse( 'main.data' );


$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>