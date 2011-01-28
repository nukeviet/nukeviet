<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$xtpl = new XTemplate( "listimg.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );

$pathimg = nv_check_path_upload( $nv_Request->get_string( 'path', 'get', NV_UPLOADS_DIR ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $pathimg );

if ( isset( $check_allow_upload_dir['view_dir'] ) )
{
    $type = $nv_Request->get_string( 'type', 'get', 'file' );
    if ( $type != "image" and $type != "flash" ) $type = "file";

    $selectfile = htmlspecialchars( trim( $nv_Request->get_string( 'imgfile', 'get', '' ) ), ENT_QUOTES );
    $selectfile = basename( $selectfile );
    
    $author = $nv_Request->isset_request( 'author', 'get' ) ? true : false;
    $refresh = $nv_Request->isset_request( 'refresh', 'get' ) ? true : false;

    $md5 = md5( $pathimg );
    $tempFile = NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache/" . $md5;
    $file_exists = file_exists( $tempFile );
    $results = array();

    if ( $file_exists )
    {
        $results = file_get_contents( $tempFile );
        $results = unserialize( $results );
    }

    if ( $refresh or ! $file_exists )
    {
        $files = @scandir( NV_ROOTDIR . "/" . $pathimg );

        if ( ! empty( $files ) )
        {
            foreach ( $files as $file )
            {
                if ( in_array( $file, $array_hidefolders ) ) continue;

                clearstatcache();
                unset( $matches );
                if ( preg_match( "/([a-zA-Z0-9\.\-\_]+)\.([a-zA-Z0-9]+)$/", $file, $matches ) )
                {
                    if ( ! isset( $results[$file] ) ) $results[$file] = array();
                    $results[$file][0] = $file;
                    $max = 16;
                    if ( strlen( $file ) > $max )
                    {
                        $results[$file][0] = substr( $matches[1], 0, ( $max - 3 - strlen( $matches[2] ) ) ) . "..." . $matches[2];
                    }

                    $results[$file][1] = $matches[2];
                    $results[$file][2] = "file";

                    $stat = @stat( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
                    $results[$file][3] = $stat['size'];

                    $results[$file][4] = NV_BASE_SITEURL . 'images/file.gif';
                    $results[$file][5] = 32;
                    $results[$file][6] = 32;
                    $results[$file][7] = "|";

                    if ( in_array( $matches[2], $array_images ) )
                    {
                        $size = @getimagesize( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
                        $results[$file][2] = "image";
                        $results[$file][4] = NV_BASE_SITEURL . $pathimg . '/' . $file;
                        $results[$file][5] = $size[0];
                        $results[$file][6] = $size[1];
                        $results[$file][7] = $size[0] . "|" . $size[1];

                        if ( $size[0] > 80 or $size[1] > 80 )
                        {
                            if ( ( $_src = nv_get_viewImage( $pathimg . '/' . $file, 80, 80 ) ) !== false )
                            {
                                $results[$file][4] = NV_BASE_SITEURL . $_src[0];
                                $results[$file][5] = $_src[1];
                                $results[$file][6] = $_src[2];
                            }
                            else
                            {
                                if ( $results[$file][5] > 80 )
                                {
                                    $results[$file][6] = round( 80 / $results[$file][5] * $results[$file][6] );
                                    $results[$file][5] = 80;
                                }

                                if ( $results[$file][6] > 80 )
                                {
                                    $results[$file][5] = round( 80 / $results[$file][6] * $results[$file][5] );
                                    $results[$file][6] = 80;
                                }
                            }
                        }
                    } elseif ( in_array( $matches[2], $array_flash ) )
                    {
                        $results[$file][2] = "flash";
                        $results[$file][4] = NV_BASE_SITEURL . 'images/flash.gif';

                        if ( $matches[2] == "swf" )
                        {
                            $size = @getimagesize( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
                            if ( isset( $size, $size[0], $size[1] ) )
                            {
                                $results[$file][7] = $size[0] . "|" . $size[1];
                            }
                        }
                    } elseif ( in_array( $matches[2], $array_archives ) )
                    {
                        $results[$file][4] = NV_BASE_SITEURL . 'images/zip.gif';
                    } elseif ( in_array( $matches[2], $array_documents ) )
                    {
                        $results[$file][4] = NV_BASE_SITEURL . 'images/doc.gif';
                    }

                    if ( ! isset( $results[$file][8] ) ) $results[$file][8] = 0;
                    $results[$file][9] = $stat['mtime'];
                }
            }
        }

        $files = array_flip( $files );
        $results = array_intersect_key( $results, $files );
        ksort( $results );
        file_put_contents( $tempFile, serialize( $results ) );
    }

    if ( ! empty( $results ) )
    {
        $author = ( $author === true ) ? $admin_info['userid'] : 0;

        foreach ( $results as $title => $file )
        {
            if ( $type == "file" or ( $type != "file" and $file[2] == $type ) )
            {
                if ( ! $author or $author == $file[8] )
                {
                    $file = array_combine( array( 'name0', 'ext', 'type', 'filesize', 'src', 'srcWidth', 'srcHeight', 'name', 'author', 'mtime' ), $file );
                    $file['title'] = $title;
                    if ( $file['type'] == "image" or $file['ext'] == "swf" )
                    {
                        $file['size'] = str_replace( "|", " x ", $file['name'] ) . " pixels";
                    }
                    else
                    {
                        $file['size'] = $file['filesize'];
                    }

                    $file['name'] .= "|" . $file['ext'] . "|" . $file['type'] . "|" . nv_convertfromBytes( $file['filesize'] ) . "|" . $file['author'] . "|" . nv_date( "l, d F Y, H:i:s P", $file['mtime'] );
                    $file['sel'] = ( $selectfile == $title ) ? " imgsel" : "";
                    $xtpl->assign( "IMG", $file );
                    $xtpl->parse( 'main.loopimg' );
                }
            }
        }
    }
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
echo $contents;

?>