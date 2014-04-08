<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['sources'];
$table_name = $db_config['prefix'] . "_" . $module_data . "_sources";
list( $rowcontent['sourceid'], $title, $link, $logo, $error ) = array( 0, "", "http://", "", "" );
$rowcontent = array(
	'sourceid' => 0,
	'link' => '',
	'logo' => '',
	'weight' => 0,
	'add_time' => 0,
	'edit_time' => 0,
	'title' => ''
);

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
if( ! empty( $savecat ) )
{
	$field_lang = nv_file_table( $table_name );

	$rowcontent['sourceid'] = $nv_Request->get_int( 'sourceid', 'post', 0 );
	$rowcontent['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	$rowcontent['link'] = strtolower( nv_substr( $nv_Request->get_title( 'link', 'post', '', 1 ), 0, 255 ) );

	$logo_old = $db->query( "SELECT logo FROM " . $table_name . " WHERE sourceid =" . $rowcontent['sourceid'] )->fetchColumn();

	$rowcontent['logo'] = $nv_Request->get_string( 'logo', 'post', '' );

	if( ! nv_is_url( $rowcontent['logo'] ) and file_exists( NV_DOCUMENT_ROOT . $rowcontent['logo'] ) )
	{
		$rowcontent['logo'] = substr( $rowcontent['logo'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/source/" ) );
	}
	elseif( ! nv_is_url( $rowcontent['logo'] ) )
	{
		$rowcontent['logo'] = $logo_old;
	}

	if( $rowcontent['logo'] != $logo_old and is_file( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/source/" . $logo_old ) )
	{
		@unlink( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/source/" . $logo_old );
	}

	if( empty( $rowcontent['title'] ) )
	{
		$error = $lang_module['source_error_title'];
	}
	else
	{
		if( $rowcontent['sourceid'] == 0 )
		{
			$weight = $db->query( "SELECT max(weight) FROM " . $table_name . "" )->fetchColumn();
			$weight = intval( $weight ) + 1;

			$listfield = "";
			$listvalue = "";
			foreach( $field_lang as $field_lang_i )
			{
				list( $flang, $fname ) = $field_lang_i;
				$listfield .= ", " . $flang . "_" . $fname;
				$listvalue .= ", :" . $flang . "_" . $fname;
			}
			$sql = "INSERT INTO " . $table_name . " ( link, logo, weight, add_time, edit_time " . $listfield . ") VALUES ( :link, :logo, " . $weight . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . " " . $listvalue . ")";
			$data_insert = array();
			$data_insert['link'] = $rowcontent['link'];
			$data_insert['logo'] = $rowcontent['logo'];
			foreach( $field_lang as $field_lang_i )
			{
				list( $flang, $fname ) = $field_lang_i;
				$data_insert[$flang . "_" . $fname] = $rowcontent[$fname];
			}
			if( $db->insert_id( $sql, 'sourceid', $data_insert ) )
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
			$stmt = $db->prepare( "UPDATE " . $table_name . " SET " . NV_LANG_DATA . "_title= :title, link = :link, logo= :logo, edit_time=" . NV_CURRENTTIME . " WHERE sourceid =" . $rowcontent['sourceid'] );
			$stmt->bindParam( ':title', $rowcontent['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':link', $rowcontent['link'], PDO::PARAM_STR );
			$stmt->bindParam( ':logo', $rowcontent['logo'], PDO::PARAM_STR );
			if( $stmt->execute() )
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
	}
}

$rowcontent['sourceid'] = $nv_Request->get_int( 'sourceid', 'get', 0 );
if( $rowcontent['sourceid'] > 0 )
{
	list( $rowcontent['sourceid'], $rowcontent['title'], $rowcontent['link'], $rowcontent['logo'] ) = $db->query( "SELECT sourceid, " . NV_LANG_DATA . "_title, link, logo FROM " . $db_config['prefix'] . "_" . $module_data . "_sources where sourceid=" . $rowcontent['sourceid'] . "" )->fetch( 3 );
	$lang_module['add_sources'] = $lang_module['edit_sources'];
}

$xtpl = new XTemplate( "sources.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'NV_UPLOADS_DIR', NV_UPLOADS_DIR );

$xtpl->assign( 'SOURCES_LIST', nv_show_sources_list() );

if( $error != "" )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( ! empty( $rowcontent['logo'] ) )
{
	$rowcontent['logo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/source/" . $rowcontent['logo'];
}

$xtpl->assign( 'DATA', $rowcontent );

if( ! empty( $rowcontent['logo'] ) )
{
	$xtpl->parse( 'main.logo' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';