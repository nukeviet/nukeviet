<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

/////////////////////////////////////////////////////////////////////////////////////////////
$xtpl = new XTemplate( "listimg.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( "NV_BASE_SITEURL", NV_BASE_SITEURL );
$xtpl->assign( "ADMIN_THEME", $global_config['module_theme'] );
$xtpl->assign( "NV_OP_VARIABLE", NV_OP_VARIABLE );
$xtpl->assign( "NV_NAME_VARIABLE", NV_NAME_VARIABLE );
$xtpl->assign( "module_name", $module_name );
$xtpl->assign( "LANG", $lang_module );

$pathimg = nv_check_path_upload( $nv_Request->get_string( 'path', 'get', NV_UPLOADS_DIR ) );
if ( ! empty( $pathimg ) )
{
    $type = htmlspecialchars( trim( $nv_Request->get_string( 'type', 'get', 'file' ) ), ENT_QUOTES );
    $selectfile = htmlspecialchars( trim( $nv_Request->get_string( 'imgfile', 'get', '' ) ), ENT_QUOTES );
    if ( $selectfile != '' )
    {
        $xtpl->parse( 'main.slectfile' );
    }
    require_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
    $imglist = array();
    if ( ! nv_check_allow_upload_dir( $pathimg ) )
    {
        $pathimg = NV_UPLOADS_DIR;
    }
    $xtpl->assign( "folder", $pathimg );
    $files = @scandir( NV_ROOTDIR . "/" . $pathimg );
    if ( ! empty( $files ) )
    {
        if ( $type == 'image' )
        {
            $filter = "\.(gif|jpg|jpeg|pjpeg|png)";
        }
        if ( $type == 'flash' )
        {
            $filter = "\.(flv|swf|swc)";
        }
        foreach ( $files as $file )
        {
            $full_d = NV_ROOTDIR . '/' . $pathimg . '/' . $file;
            if ( ! in_array( $file, $array_hidefolders ) and ! is_dir( $full_d ) )
            {
                if ( $type != 'file' )
                {
                    if ( preg_match( '/^[a-zA-Z0-9\-\_](.*)' . $filter . '$/', strtolower( $file ) ) )
                    {
                        $imglist[] = $file;
                    }
                }
                else
                {
                    $imglist[] = $file;
                }
            }
        }
    }
    for ( $i = 0; $i < count( $imglist ); $i ++ )
    {
        if ( $selectfile == $imglist[$i] )
        {
            $sel = ';border:2px solid red';
            $selid = 'id="imgselected"';
        }
        else
        {
            $sel = '';
            $selid = '';
        }
        
        $ext = nv_getextension( $imglist[$i] );
        clearstatcache();
        $fsize = @filesize( NV_ROOTDIR . '/' . $pathimg . '/' . $imglist[$i] );
        $src = "";
        
        if ( in_array( $ext, $array_images ) )
        {
            if ( $fsize > 15000 )
            {
                //tao thumb cho anh qua 10kb
                $path_view_image = $pathimg . '/' . $imglist[$i];
                $md5_view_image = md5( $path_view_image );
                if ( file_exists( NV_ROOTDIR . '/files/images/' . $md5_view_image . '.' . $ext ) )
                {
                    $path_view_image = 'files/images/' . $md5_view_image . '.' . $ext;
                }
                else
                {
                    $image = new image( NV_ROOTDIR . '/' . $path_view_image, NV_MAX_WIDTH, NV_MAX_HEIGHT );
                    $image->resizeXY( 80, 80 );
                    $image->save( NV_ROOTDIR . '/files/images', $md5_view_image, 75 );
                    if ( empty( $image->error ) )
                    {
                        $path_view_image = 'files/images/' . $md5_view_image . '.' . $ext;
                    }
                    $image->close();
                }
                $src = NV_BASE_SITEURL . $path_view_image;
            }
            else
            {
                $src = NV_BASE_SITEURL . $pathimg . '/' . $imglist[$i];
            }
        }
        elseif ( in_array( $ext, $array_archives ) )
        {
            $src = NV_BASE_SITEURL . 'images/zip.gif';
        }
        elseif ( in_array( $ext, $array_documents ) )
        {
            $src = NV_BASE_SITEURL . 'images/doc.gif';
        }
        else
        {
            $src = NV_BASE_SITEURL . 'images/file.gif';
        }
        
        $filesize = nv_convertfromBytes( $fsize );
        $row = array( 
            "name" => $imglist[$i], "name0" => nv_clean60( $imglist[$i], 10 ), "src" => $src, "filesize" => $filesize, "sel" => $sel, "selid" => $selid 
        );
        $xtpl->assign( "imglist", $row );
        $xtpl->parse( 'main.loopimg' );
    }
    
    $listdir = viewdir( NV_UPLOADS_DIR );
    $row = array( 
        "name" => NV_UPLOADS_DIR, "select" => NV_UPLOADS_DIR 
    );
    $xtpl->assign( "fol", $row );
    $xtpl->parse( 'main.floop' );
    foreach ( $listdir as $folder )
    {
        $sel = ( $folder == $pathimg ) ? " selected=\"selected\"" : "";
        $row = array( 
            "name" => $folder, "select" => $sel 
        );
        $xtpl->assign( "fol", $row );
        $xtpl->parse( 'main.floop' );
    }
}

if ( $admin_info['allow_modify_files'] )
{
    $xtpl->parse( 'main.allow_modify_files' );
    $xtpl->parse( 'main.allow_modify_files1' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
//////////////////////////////////////////
echo $contents;
////////////////////////////////////////


?>