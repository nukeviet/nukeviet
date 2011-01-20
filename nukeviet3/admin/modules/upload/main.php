<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

//age_title = $lang_module['upload_manager'];
if ( strpos( $client_info['browser']['name'], 'Internet Explorer v6' ) !== false )
{
    nv_info_die( $global_config['site_description'], $lang_global['site_info'], "<br />" . $lang_module['upload_error_browser_ie6'] );
}
/** get config file **/
$path = ( defined( 'NV_IS_SPADMIN' ) ) ? "" : NV_UPLOADS_DIR;
$path = htmlspecialchars( trim( $nv_Request->get_string( 'path', 'get', $path ) ), ENT_QUOTES );
$currentpath = $nv_Request->isset_request( 'path', 'post' ) ? htmlspecialchars( trim( $nv_Request->get_string( 'path', 'post', $path ) ), ENT_QUOTES ) : htmlspecialchars( trim( $nv_Request->get_string( 'currentpath', 'get', $path ) ), ENT_QUOTES );
if ( empty( $currentpath ) )
{
    $currentpath = NV_UPLOADS_DIR;
}

$area = "";
$popup = $nv_Request->get_int( 'popup', 'get', 0 );
$selectedfile = '';
$uploadflag = $nv_Request->isset_request( 'confirm', 'post' );

////////////////////////////////////////////////////////////////////////////////////////
$errors = array();
if ( ! empty( $uploadflag ) )
{
    require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
    $upload = new upload( $admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
    if ( is_uploaded_file( $_FILES['fileupload']['tmp_name'] ) && nv_check_allow_upload_dir( $currentpath ) )
    {
        $upload_info = $upload->save_file( $_FILES['fileupload'], NV_ROOTDIR . '/' . $currentpath, false );
        if ( ! empty( $upload_info['error'] ) )
        {
            $errors[] = $upload_info['error'];
        }
        else
        {
            $selectedfile = $upload_info['basename'];
        }
    }
    elseif ( $nv_Request->isset_request( 'imgurl', 'post' ) and nv_is_url( $nv_Request->get_string( 'imgurl', 'post' ) ) and nv_check_allow_upload_dir( $currentpath ) )
    {
        $urlfile = trim( $nv_Request->get_string( 'imgurl', 'post' ) );
        $upload_info = $upload->save_urlfile( $urlfile, NV_ROOTDIR . '/' . $currentpath, false );
        if ( ! empty( $upload_info['error'] ) )
        {
            $errors[] = $upload_info['error'];
        }
        else
        {
            $selectedfile = $upload_info['basename'];
        }
    }
    else
    {
        $errors[] = $lang_module['upload_file_error_invalidurl'];
    }
}
$type = htmlspecialchars( trim( $nv_Request->get_string( 'type', 'get', 'file' ) ), ENT_QUOTES );
if ( $popup )
{
    $area = htmlspecialchars( trim( $nv_Request->get_string( 'area', 'get' ) ), ENT_QUOTES );
    /////////////////////////////////////////////////////////////////////////////////////////////
    $xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
    $xtpl->assign( "NV_BASE_SITEURL", NV_BASE_SITEURL );
    $xtpl->assign( "ADMIN_THEME", $global_config['module_theme'] );
    $xtpl->assign( "NV_OP_VARIABLE", NV_OP_VARIABLE );
    $xtpl->assign( "NV_NAME_VARIABLE", NV_NAME_VARIABLE );
    $xtpl->assign( "module_name", $module_name );
    $xtpl->assign( "LANG", $lang_module );
    $xtpl->assign( "currentpath", $currentpath );
    $xtpl->assign( "path", $path );
    $xtpl->assign( "area", $area );
    $xtpl->assign( "type", $type );
    $xtpl->assign( "funnum", $nv_Request->get_int( 'CKEditorFuncNum', 'get', 0 ) );
    $xtpl->assign( "selectedfile", $selectedfile );
    //////////////////////////////////////////////////////
    if ( ! empty( $errors ) )
    {
        $xtpl->assign( "error", implode( "<br>", $errors ) );
        $xtpl->parse( 'main.error' );
    }
    
    if ( $admin_info['allow_create_subdirectories'] )
    {
        $xtpl->parse( 'main.allow_create_subdirectories' );
    }
    if ( $admin_info['allow_modify_subdirectories'] )
    {
        $xtpl->parse( 'main.allow_modify_subdirectories' );
    }
    $sfile = ( $type == 'file' ) ? '  selected="selected"' : '';
    $simage = ( $type == 'image' ) ? '  selected="selected"' : '';
    $sflash = ( $type == 'flash' ) ? '  selected="selected"' : '';
    
    $xtpl->assign( "sflash", $sflash );
    $xtpl->assign( "simage", $simage );
    $xtpl->assign( "sfile", $sfile );
}
include ( NV_ROOTDIR . "/includes/header.php" );
if ( $popup )
{
    $xtpl->parse( 'main.header' );
    $xtpl->parse( 'main.footer' );
    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );
    echo $contents;
}
else
{
    $contents = "<iframe src='".NV_BASE_ADMINURL."index.php?".NV_NAME_VARIABLE."=".$module_name."&popup=1' width='100%' height='400px' frameborder='0'></iframe>";
    echo nv_admin_theme( $contents );
}
include ( NV_ROOTDIR . "/includes/footer.php" );

?>