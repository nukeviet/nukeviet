<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['upload_manager'];
$contents = "";

$path = ( defined( 'NV_IS_SPADMIN' ) ) ? "" : NV_UPLOADS_DIR;
$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'get', $path ) );
$currentpath = nv_check_path_upload( $nv_Request->get_string( 'currentpath', 'get', $path ) );
$type = $nv_Request->get_string( 'type', 'get' );
$popup = $nv_Request->get_int( 'popup', 'get', 0 );
$area = htmlspecialchars( trim( $nv_Request->get_string( 'area', 'get' ) ), ENT_QUOTES );

if( empty( $currentpath ) ) $currentpath = NV_UPLOADS_DIR;
if( $type != "image" and $type != "flash" ) $type = "file";

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
if( $popup )
{
	$xtpl->assign( "NV_BASE_SITEURL", NV_BASE_SITEURL );
	$xtpl->assign( "ADMIN_THEME", $global_config['module_theme'] );
	$xtpl->assign( "NV_OP_VARIABLE", NV_OP_VARIABLE );
	$xtpl->assign( "NV_NAME_VARIABLE", NV_NAME_VARIABLE );
	$xtpl->assign( "MODULE_NAME", $module_name );
	$xtpl->assign( "LANG", $lang_module );
	$xtpl->assign( "NV_MAX_WIDTH", NV_MAX_WIDTH );
	$xtpl->assign( "NV_MAX_HEIGHT", NV_MAX_HEIGHT );
	$xtpl->assign( "NV_MIN_WIDTH", 10 );
	$xtpl->assign( "NV_MIN_HEIGHT", 10 );
	$xtpl->assign( "CURRENTPATH", $currentpath );
	$xtpl->assign( "PATH", $path );
	$xtpl->assign( "TYPE", $type );
	$xtpl->assign( "AREA", $area );
	$xtpl->assign( "FUNNUM", $nv_Request->get_int( 'CKEditorFuncNum', 'get', 0 ) );

	$sfile = ( $type == 'file' ) ? '  selected="selected"' : '';
	$simage = ( $type == 'image' ) ? '  selected="selected"' : '';
	$sflash = ( $type == 'flash' ) ? '  selected="selected"' : '';

	$xtpl->assign( "SFLASH", $sflash );
	$xtpl->assign( "SIMAGE", $simage );
	$xtpl->assign( "SFILE", $sfile );

	$xtpl->parse( 'main.header' );
	$xtpl->parse( 'main.footer' );
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}
else
{
	$xtpl->assign( "IFRAME_SRC", NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&popup=1" );
	$xtpl->parse( 'uploadPage' );
	$contents = $xtpl->text( 'uploadPage' );
	$contents = nv_admin_theme( $contents );
	$my_meta = "\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=8\" />";
	$contents = preg_replace( '/(<head>)/i', "\\1" . $my_meta, $contents, 1 );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>