<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['weblink_checkalivelink'];

$submit = $nv_Request->get_string( 'submit', 'post' );

if( $submit )
{
	$nv_Request->set_Cookie( 'ok', 1 );
}

$xtpl = new XTemplate( "checklink.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

if( $nv_Request->isset_request( 'ok', 'cookie' ) )
{
	require_once NV_ROOTDIR . '/includes/class/checkurl.class.php';
	$check = new CheckUrl();

	$page_title = $lang_module['weblink_checkalivelink'];

	$numcat = $db->sql_numrows( $db->sql_query( "SELECT id FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` " ) );
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=checklink";
	$all_page = ( $numcat > 1 ) ? $numcat : 1;
	$per_page = 5;
	$page = $nv_Request->get_int( 'page', 'get', 0 );

	$sql = "SELECT `url` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` LIMIT $page,$per_page";
	$result = $db->sql_query( $sql );
	
	$i = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$xtpl->assign( 'CLASS', $i ++ % 2 ? " class=\"second\"" : "" );
		$xtpl->assign( 'URL', $row['url'] );
		
		if( $check->check_curl( $row['url'] ) )
		{
			$xtpl->parse( 'main.check.loop.ok' );
		}
		else
		{
			$xtpl->parse( 'main.check.loop.error' );
		}
		
		$xtpl->parse( 'main.check.loop' );
	}
	
	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.check.generate_page' );
	}
	
	$xtpl->parse( 'main.check' );
}
else
{
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=checklink" );
	$xtpl->parse( 'main.form' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>