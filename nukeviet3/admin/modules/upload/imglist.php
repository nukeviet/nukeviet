<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$xtpl = new XTemplate( "listimg.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( "NV_BASE_SITEURL", NV_BASE_SITEURL );
$xtpl->assign( "ADMIN_THEME", $global_config['module_theme'] );
$xtpl->assign( "NV_OP_VARIABLE", NV_OP_VARIABLE );
$xtpl->assign( "NV_NAME_VARIABLE", NV_NAME_VARIABLE );
$xtpl->assign( "module_name", $module_name );
$xtpl->assign( "LANG", $lang_module );
$xtpl->assign( "NV_MAX_WIDTH", NV_MAX_WIDTH );
$xtpl->assign( "NV_MAX_HEIGHT", NV_MAX_HEIGHT );
$xtpl->assign( "ERRORNEWSIZE", sprintf( $lang_module['errorNewSize'], NV_MAX_WIDTH, NV_MAX_HEIGHT ) );
$xtpl->assign( "MAXSIZESIZE", sprintf( $lang_module['maxSizeSize'], NV_MAX_WIDTH, NV_MAX_HEIGHT ) );

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

    $foldchangefile = md5( NV_ROOTDIR . "/" . $pathimg );
    $filemtime = filemtime( NV_ROOTDIR . "/" . $pathimg );
    $listfile = glob( NV_ROOTDIR . "/" . NV_CACHEDIR . "/all-upload_" . $foldchangefile . "-*.cache" );
    $cachefile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/all-upload_" . $foldchangefile . "-" . $filemtime . ".cache";

    $results = array();
    if ( ! in_array( $cachefile, $listfile ) )
    {
        if ( ! empty( $listfile ) )
        {
            foreach ( $listfile as $file )
            {
                nv_deletefile( $file );
            }
        }

        $files = @scandir( NV_ROOTDIR . "/" . $pathimg );

        if ( ! empty( $files ) )
        {
            foreach ( $files as $file )
            {
                clearstatcache();
                unset( $matches );
                if ( $file != "index.html" and preg_match( "/([a-zA-Z0-9\.\-\_]+)\.([a-zA-Z0-9]+)$/", $file, $matches ) )
                {
                    $name0 = $file;
                    $max = 16;
                    if ( strlen( $name0 ) > $max )
                    {
                        $name0 = substr( $matches[1], 0, ( $max - 2 - strlen( $matches[2] ) ) ) . ".." . $matches[2];
                    }

                    $filesize = @filesize( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
                    $type2 = "file";
                    $src = NV_BASE_SITEURL . 'images/file.gif';
                    $name = "";

                    if ( in_array( $matches[2], array( "gif", "jpg", "jpeg", "pjpeg", "png" ) ) )
                    {
                        $type2 = "image";
                    } elseif ( in_array( $matches[2], array( "flv", "swf", "swc" ) ) )
                    {
                        $type2 = "flash";
                    }

                    if ( in_array( $matches[2], $array_images ) )
                    {
                        $src = NV_BASE_SITEURL . $pathimg . '/' . $file;

                        if ( $filesize > 15000 )
                        {
                            $md5_view_image = md5( $pathimg . '/' . $file );
                            if ( file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/images/' . $md5_view_image . '.' . $matches[2] ) )
                            {
                                $src = NV_BASE_SITEURL . NV_FILES_DIR . '/images/' . $md5_view_image . '.' . $matches[2];
                            }
                            else
                            {
                                $image = new image( NV_ROOTDIR . '/' . $pathimg . '/' . $file, NV_MAX_WIDTH, NV_MAX_HEIGHT );
                                $image->resizeXY( 80, 80 );
                                $image->save( NV_ROOTDIR . '/' . NV_FILES_DIR . '/images', $md5_view_image, 75 );
                                if ( empty( $image->error ) )
                                {
                                    $src = NV_BASE_SITEURL . NV_FILES_DIR . '/images/' . $md5_view_image . '.' . $matches[2];
                                }
                                $image->close();
                            }
                        }

                        $name = @getimagesize( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
                        $name = $name[0] . "|" . $name[1];
                    } elseif ( in_array( $matches[2], $array_archives ) )
                    {
                        $src = NV_BASE_SITEURL . 'images/zip.gif';
                    } elseif ( in_array( $matches[2], $array_documents ) )
                    {
                        $src = NV_BASE_SITEURL . 'images/doc.gif';
                    }

                    $results[] = array( $file, $name0, $matches[2], $type2, nv_convertfromBytes( $filesize ), $src, $name );
                }
            }
        }
        file_put_contents( $cachefile, serialize( $results ) );
    }
    else
    {
        $results = file_get_contents( $cachefile );
        $results = unserialize( $results );
    }

    if ( ! empty( $results ) )
    {
        foreach ( $results as $file )
        {
            if ( $type == "file" or ( $type != "file" and $file[3] == $type ) )
            {
                $file = array_combine( array( 'title', 'name0', 'ext', 'type', 'filesize', 'src', 'name' ), $file );
                $file['name'] .= "|" . $file['filesize'];
                $file['sel'] = ( $selectfile == $file['title'] ) ? ";border:2px solid red" : "";
                $file['selid'] = ( $selectfile == $file['title'] ) ? "id=\"imgselected\"" : "";
                $xtpl->assign( "imglist", $file );
                $xtpl->parse( 'main.loopimg' );
            }
        }
    }

    $row = array( "name" => NV_UPLOADS_DIR, "select" => NV_UPLOADS_DIR );
    $xtpl->assign( "fol", $row );
    $xtpl->parse( 'main.floop' );

    $listdir = viewdir( NV_UPLOADS_DIR );
    foreach ( $listdir as $folder )
    {
        $row = array( "name" => $folder, "select" => ( $folder == $pathimg ) ? " selected=\"selected\"" : "" );
        $xtpl->assign( "fol", $row );
        $xtpl->parse( 'main.floop' );
    }

    if ( ( defined( 'NV_IS_SPADMIN' ) or ( ! defined( 'NV_IS_SPADMIN' ) and ! in_array( $pathimg, $allow_upload_dir ) ) ) and $admin_info['allow_modify_files'] )
    {
        $xtpl->parse( 'main.allow_modify_files' );
        $xtpl->parse( 'main.allow_modify_files1' );
    }
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
echo $contents;

?>