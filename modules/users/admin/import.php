<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

function nv_read_data_from_excel( $file_name )
{
	global $global_config, $db, $client_info, $module_file, $module_data, $module_name, $lang_module, $modConfigs;

	require_once NV_ROOTDIR . '/includes/class/PHPExcel.php' ;

	$objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . "/" . $module_name . "/" . $file_name );
	$objWorksheet = $objPHPExcel->getActiveSheet();

	$highestRow = $objWorksheet->getHighestRow();
	$highestColumn = $objWorksheet->getHighestColumn();
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString( $highestColumn );

	$user_field = array();
	$user_field['userid'] = array( 'col' => 0, 'title' => ( isset( $lang_module['userid'] ) ) ? $lang_module['userid'] : 'userid' );
	$user_field['username'] = array( 'col' => 1, 'title' => ( isset( $lang_module['account'] ) ) ? $lang_module['account'] : 'username' );
	$user_field['password'] = array( 'col' => 2, 'title' => ( isset( $lang_module['password'] ) ) ? $lang_module['password'] : 'password' );
	$user_field['email'] = array( 'col' => 3, 'title' => ( isset( $lang_module['email'] ) ) ? $lang_module['email'] : 'email' );
	$user_field['full_name'] = array( 'col' => 4, 'title' => ( isset( $lang_module['name'] ) ) ? $lang_module['name'] : 'full_name' );
	$user_field['gender'] = array( 'col' => 5, 'title' => ( isset( $lang_module['gender'] ) ) ? $lang_module['gender'] : 'gender' );
	$user_field['birthday'] = array( 'col' => 6, 'title' => ( isset( $lang_module['birthday'] ) ) ? $lang_module['birthday'] : 'birthday' );
	$user_field['sig'] = array( 'col' => 7, 'title' => ( isset( $lang_module['sig'] ) ) ? $lang_module['sig'] : 'sig' );
	$user_field['regdate'] = array( 'col' => 8, 'title' => ( isset( $lang_module['regdate'] ) ) ? $lang_module['regdate'] : 'regdate' );
	$user_field['question'] = array( 'col' => 9, 'title' => ( isset( $lang_module['question'] ) ) ? $lang_module['question'] : 'question' );
	$user_field['answer'] = array( 'col' => 10, 'title' => ( isset( $lang_module['answer'] ) ) ? $lang_module['answer'] : 'answer' );
	$user_field['view_mail'] = array( 'col' => 11, 'title' => isset( $lang_module['show_email'] ) ? $lang_module['show_email'] : 'view_mail' );
	$user_field['active'] = array( 'col' => 12, 'title' => ( isset( $lang_module['active_users'] ) ) ? $lang_module['active_users'] : 'active' );

	$col = 13;
	$result_field = $db->query( "SELECT * FROM " . NV_USERS_GLOBALTABLE . "_field ORDER BY weight ASC" );
	while( $row_field = $result_field->fetch() )
	{
		$language = unserialize( $row_field['language'] );
		$row_field['title'] = ( isset( $language[NV_LANG_DATA] ) ) ? $language[NV_LANG_DATA][0] : $row_field['field'];
		$row_field['col'] = $col++;
		$user_field[$row_field['field']] = $row_field;
	}

	//check field
	foreach( $user_field as $field => $column )
	{
		$col = $column['col'];
		$_field = $objWorksheet->getCellByColumnAndRow( $col, 4 )->getCalculatedValue();
		if( $field != $_field )
		{
			$_title = $objWorksheet->getCellByColumnAndRow( $col, 3 )->getCalculatedValue();
			$mess = sprintf( $lang_module['read_error_field'], $file_name, $_title, $column['title'] );
			die( $mess );
		}
	}

	// read data
	for( $row = 5; $row <= $highestRow; ++$row )
	{
		// Xac dinh cac gi tri cua cac cot du lieu chinh
		$username = $objWorksheet->getCellByColumnAndRow( 1, $row )->getCalculatedValue();
		if( ! empty( $username ) )
		{
			$array_data_read = array();
			foreach( $user_field as $field => $column )
			{
				$col = $column['col'];
				$array_data_read[$field] = $objWorksheet->getCellByColumnAndRow( $col, $row )->getCalculatedValue();
			}
			$mess = 'under construction, username=' . $array_data_read['username'];
			//$mess = sprintf( $lang_module['read_error'], $file_name, $array_data_read['username'], $array_data_read['full_name'] );
			die( $mess );
		}
	}
}

$step = $nv_Request->get_int( 'step', 'get,post', 1 );
if( $step == 1 )
{
	if( file_exists( NV_ROOTDIR . '/includes/class/PHPExcel.php' ) )
	{
		$lang_module['import_note'] = sprintf( $lang_module['import_note'], NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=export&amp;example=1", SYSTEM_UPLOADS_DIR . '/' . $module_name );

		$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
		$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

		$array_file = nv_scandir( NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . "/" . $module_name, "/^([0-9A-Za-z\/\_\.\@\(\)\~\-\%\\s]+)\.(xls|xlsx)$/" );
		if( sizeof( $array_file ) )
		{
			foreach( $array_file as $file_name )
			{
				$file_size = filesize( NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . "/" . $module_name . "/" . $file_name );
				$array_data = array(
					'file_name' => $file_name,
					'file_size' => nv_convertfromBytes( $file_size ),
					'file_name_base64' => nv_base64_encode( $file_name )
				);
				$xtpl->assign( 'DATA', $array_data );
				$xtpl->parse( 'main.read.loop' );
			}
			$xtpl->parse( 'main.read' );
		}
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}
	else
	{
		$contents = $lang_module['required_phpexcel'];
	}

	$page_title = $lang_module['import'];

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}
elseif( $step == 2 )
{
	$listfile = $nv_Request->get_string( 'listfile', 'post', '', 0 );
	if( ! empty( $listfile ) )
	{
		$temp = explode( "@", $listfile );
		$arr_file = array();
		foreach( $temp as $fb )
		{
			if( ! empty( $fb ) )
			{
				$arr_file[] = nv_base64_decode( $fb );
			}
		}
		$nv_Request->set_Session( $module_data . '_listfile', implode( "@", $arr_file ) );
		$nv_Request->set_Session( $module_data . '_getfile', 0 );
		$getfile = 0;
	}
	else
	{
		$listfile = $nv_Request->get_string( $module_data . '_listfile', 'session' );
		$getfile = $nv_Request->get_int( $module_data . '_getfile', 'session', 0 );
		$arr_file = explode( "@", $listfile );
	}
	if( $getfile < count( $arr_file ) )
	{
		if( $sys_info['allowed_set_time_limit'] )
		{
			set_time_limit( 0 );
		}
		if( $sys_info['ini_set_support'] )
		{
			$memoryLimitMB = ( integer )ini_get( 'memory_limit' );
			if( $memoryLimitMB < 1024 )
			{
				ini_set( "memory_limit", "1024M" );
			}
		}
		asort( $arr_file );

		nv_read_data_from_excel( $arr_file[$getfile] );
		$nv_Request->set_Session( $module_data . '_getfile', $getfile + 1 );
		die( "OK_GETFILE" );
	}
	else
	{
		$nv_Request->unset_request( $module_data . '_listfile', 'session' );
		$nv_Request->unset_request( $module_data . '_getfile', 'session' );
		die( "OK_COMPLETE" );
	}
}