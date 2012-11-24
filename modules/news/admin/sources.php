<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['sources'];

list( $sourceid, $title, $link, $logo, $error ) = array( 0, "", "http://", "", "" );

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );

if( ! empty( $savecat ) )
{
	$sourceid = $nv_Request->get_int( 'sourceid', 'post', 0 );
	$title = filter_text_input( 'title', 'post', '', 1 );
	$link = strtolower( filter_text_input( 'link', 'post', '' ) );

	$url_info = @parse_url( $link );
	if( isset( $url_info['scheme'] ) and isset( $url_info['host'] ) )
	{
		$link = $url_info['scheme'] . "://" . $url_info['host'];
	}
	else
	{
		$link = "";
	}

	list( $logo_old ) = $db->sql_fetchrow( $db->sql_query( "SELECT logo FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE `sourceid` =" . $sourceid . "" ) );

	$logo = filter_text_input( 'logo', 'post', '' );
	if( ! nv_is_url( $logo ) and file_exists( NV_DOCUMENT_ROOT . $logo ) )
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/source/" );
		$logo = substr( $logo, $lu );
	}
	elseif( ! nv_is_url( $logo ) )
	{
		$logo = $logo_old;
	}
	if( ( $logo != $logo_old ) and ! empty( $logo_old ) )
	{
		@unlink( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/source/" . $logo_old );
	}
	if( empty( $title ) )
	{
		$error = $lang_module['error_name'];
	}
	elseif( $sourceid == 0 )
	{
		list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources`" ) );
		$weight = intval( $weight ) + 1;
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_sources` (`sourceid`, `title`, `link`, `logo`, `weight`, `add_time`, `edit_time`) VALUES (NULL, " . $db->dbescape( $title ) . ", " . $db->dbescape( $link ) . ", " . $db->dbescape( $logo ) . ", " . $db->dbescape( $weight ) . ", UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ))";
		if( $db->sql_query_insert_id( $sql ) )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_source', " ", $admin_info['userid'] );
			$db->sql_freeresult();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "" );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	else
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_sources` SET `title`=" . $db->dbescape( $title ) . ", `link` =  " . $db->dbescape( $link ) . ", `logo`=" . $db->dbescape( $logo ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `sourceid` =" . $sourceid;
		$db->sql_query( $sql );
		if( $db->sql_affectedrows() > 0 )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_source', "sourceid " . $sourceid, $admin_info['userid'] );
			$db->sql_freeresult();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "" );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
		$db->sql_freeresult();
	}
}

$sourceid = $nv_Request->get_int( 'sourceid', 'get', 0 );
if( $sourceid > 0 )
{
	list( $sourceid, $title, $link, $logo ) = $db->sql_fetchrow( $db->sql_query( "SELECT `sourceid`, `title`, `link`, `logo`  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` where `sourceid`=" . $sourceid ) );
	$lang_module['add_topic'] = $lang_module['edit_topic'];
}

if( ! empty( $logo ) )
{
	$logo = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/source/" . $logo;
}

$xtpl = new XTemplate( "sources.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_UPLOADS_DIR', NV_UPLOADS_DIR );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'SOURCES_LIST', nv_show_sources_list() );

$xtpl->assign( 'sourceid', $sourceid );
$xtpl->assign( 'title', $title );
$xtpl->assign( 'link', $link );
$xtpl->assign( 'logo', $logo );

if( ! empty( $logo ) )
{
	$xtpl->parse( 'main.logo' );
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>