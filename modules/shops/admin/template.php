<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['template'];
$error = "";
$savecat = 0;

$data = array( "title" => "", 'alias' => "" );
$table_name = $db_config['prefix'] . '_' . $module_data . '_template';
$data['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
$act= $nv_Request->get_int( 'act', 'get', 0 );
if( ! empty( $act ) )
{	
	if ($act ==1)
	{
		
		$status= $nv_Request->get_int( 'status', 'get', 0 );
		$id= $nv_Request->get_int( 'id', 'get', 0 );
		$new_status = ($status ==1) ? 0:1;
		
		$sql = 'UPDATE ' .$table_name . ' SET status=' . $new_status . ' WHERE id=' . $id;
		
		$db->query( $sql );
		nv_del_moduleCache( $module_name );
		
		
	}
	
}

if( ! empty( $savecat ) )
{
	//$field_lang = nv_file_table( $table_name );
	$data['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	$data['alias'] = change_alias($data['title']);

	if( $data['id'] == 0 )
	{
		$listfield = "";
		$listvalue = "";

		

		$sql = "INSERT INTO " . $table_name . " VALUES (NULL ,1, '" . $data['title'] . "','".$data['alias'] ."')";
		$templaid = $db->insert_id( $sql );
		if ($templaid !=0)
		{			
			$sql = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_info_".$templaid."(
			  shopid mediumint(8) unsigned NOT NULL DEFAULT '0',
			  status tinyint(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (shopid) 
			) ENGINE=MyISAM ";	
								
			$db->query( $sql);
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
		$stmt = $db->prepare( "UPDATE " . $table_name . " SET " . "title= :title, alias = :alias WHERE id =" . $data['id'] );
		$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
		$stmt->bindParam( ':alias', $data['alias'], PDO::PARAM_STR );
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
			"title" => $data_old['title'],
			"alias" => $data_old['alias']
		);
	}
}

$xtpl = new XTemplate( "template.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'caption', $lang_module['template_info'] );

$count = 0;
$result = $db->query( "SELECT id, title,alias, status FROM " . $table_name . " ORDER BY id DESC" );

while( list( $id, $title, $alias,$status) = $result->fetch( 3 ) )
{
	$xtpl->assign( 'title', $title );
	$xtpl->assign( 'alias', $alias );
	$xtpl->assign( 'link_edit', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $id );
	$xtpl->assign( 'link_status', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&act=1&status=".$status."&id=" . $id );
	$xtpl->assign( 'link_del', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=detemplate&id=" . $id );
	
	$status = ($status==1)? $lang_module['act']:$lang_module['inact'];
	$xtpl->assign( 'status', $status );	
	$xtpl->assign( 'id', $id );
	$xtpl->parse( 'main.data.row' );
	++$count;
}

$xtpl->assign( 'URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=detemplate" );
$xtpl->assign( 'URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );

if( $count > 0 ) $xtpl->parse( 'main.data' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';