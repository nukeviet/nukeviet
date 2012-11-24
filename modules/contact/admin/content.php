<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['content'];

if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

$content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . 'Content.txt';

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$bodytext = nv_editor_filter_textarea( 'bodytext', '', NV_ALLOWED_HTML_TAGS, true );
	file_put_contents( $content_file, $bodytext );

	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	die();
}

$bodytext = "";
if( file_exists( $content_file ) )
{
	$bodytext = file_get_contents( $content_file );
	$bodytext = nv_editor_br2nl( $bodytext );
}

$is_edit = $nv_Request->get_int( 'is_edit', 'get', 0 );
if( empty( $bodytext ) ) $is_edit = 1;

$xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

if( $is_edit )
{
	if( ! empty( $bodytext ) ) $bodytext = nv_htmlspecialchars( $bodytext );
	
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
	
	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$data = nv_aleditor( "bodytext", '99%', '300px', $bodytext );
	}
	else
	{
		$data = "<textarea style=\"width: 99%\" name=\"bodytext\" id=\"bodytext\" cols=\"20\" rows=\"8\">" . $bodytext . "</textarea>";
	}
	
	$xtpl->assign( 'DATA', $data );
	
	$xtpl->parse( 'main.edit' );
}
else
{	
	$xtpl->assign( 'DATA', $bodytext );
	$xtpl->assign( 'URL_EDIT', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;is_edit=1" );
	
	$xtpl->parse( 'main.data' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>