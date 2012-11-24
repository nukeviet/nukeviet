<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['weblink_del_link_title'];

$id = ( $nv_Request->get_int( 'id', 'get' ) > 0 ) ? $nv_Request->get_int( 'id', 'post,get' ) : 0;

if( empty( $id ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$xtpl = new XTemplate( "del_link.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$submit = $nv_Request->get_string( 'submit', 'post' );
if( ! empty( $submit ) )
{
	$confirm = $nv_Request->get_int( 'confirm', 'post' );
	if( $confirm == 1 )
	{
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE id=" . $id;
		
		if( $db->sql_query( $sql ) )
		{
			$db->sql_freeresult();
			$msg = $lang_module['weblink_del_success'];
		}
		else
		{
			$msg = $lang_module['weblink_del_error'];
		}
		
		if( $msg != '' )
		{
			$xtpl->assign( 'ERROR', $msg );
			$xtpl->parse( 'main.error' );
		}
		
		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['weblink_del_link_title'], "id " . $id, $admin_info['userid'] );
	}
	else
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "" );
	}
}
else
{	
	$xtpl->assign( 'ID', $id );
	$xtpl->parse( 'main.confirm' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>