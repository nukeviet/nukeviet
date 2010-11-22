<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 22/11/2010, 6:25
 */

$maxAge = 3600 * 24 * 1;//Thoi gian luu trong Bo nho dem la 1 ngay

if ( isset( $_REQUEST['file'] ) )
{
    $file = $_REQUEST['file'];

    $ext = end( explode( ".", $file ) );
    switch ( $ext )
    {
        case 'css':
            $contenttype = 'css';
            break;

        case 'js':
            $contenttype = 'javascript';
            break;

        default:
            header( "HTTP/1.0 403 Forbidden" );
            exit;
    }
    
    if ( ! is_file( $file ) )
    {
        header( "HTTP/1.0 404 Not Found" );
        exit;
    }

    $lastmod = filemtime( $file );

    if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
    {
        $modsince = strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] );
        if ( $modsince != -1 && $modsince == $lastmod )
        {
            header( "HTTP/1.0 304 Not Modified" );
            header( 'Content-Length: 0' );
            exit();
        }
    }
    else
    {
        $hash = $lastmod . '-' . md5( $file );

        if ( isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) && stripslashes( $_SERVER['HTTP_IF_NONE_MATCH'] ) == '"' . $hash . '"' )
        {
            header( "HTTP/1.0 304 Not Modified" );
            header( 'Content-Length: 0' );
            exit();
        }
        
        header( "Etag: \"" . $hash . "\"" );
    }

    $data = file_get_contents( $file );
    $data = call_user_func( "compress_" . $contenttype, $data );

    $currenttime = time();

    ob_start( "ob_gzhandler" );

    header( 'Expires: ' . gmdate( "D, d M Y H:i:s", $currenttime + $maxAge ) . " GMT" );
    header( 'Cache-Control: public; max-age=' . $maxAge );
    header( 'Last-Modified: ' . gmdate( "D, d M Y H:i:s", $lastmod ) . " GMT" );
    header( 'Content-type: text/' . $contenttype . '; charset: UTF-8' );
    echo $data;
    exit;
}

header( "HTTP/1.0 404 Not Found" );
exit;

function compress_css( $cssContent )
{
    $cssContent = preg_replace( "/url[\s]*\([\s]*[\'|\"](.*)?[\'|\"][\s]*\)/", "url($1)", $cssContent ); //xoa cac dau ngoac don
    $cssContent = preg_replace( "/\/\*(.*)?\*\//Usi", "", $cssContent ); //xoa chu thich
    $cssContent = str_replace( array( "\n ", "\n", "\t" ), " ", $cssContent ); //xoa xuong dong, dau cach TAB
    $cssContent = preg_replace( "/[\s]+/", " ", $cssContent ); //Xoa khoang trang
    $cssContent = preg_replace( array( "/[\s]*[\;]+[\s]*\}/", "/[\s]*[\;]+[\s]*/", "/[\s]*[\}]+[\s]*/", "/[\s]*[\{]+[\s]*/", "/[\s]*[\:]+[\s]*/", "/[\s]*[\,]+[\s]*/" ), array( "}", ";", "}", "{", ":", "," ), $cssContent );
    $cssContent = preg_replace( "/[^\}]+\{[\s|\;]*\}[\s]*/", "", $cssContent ); //Xoa nhung css khong co noi dung
    return $cssContent;
}

function compress_javascript( $jsContent )
{
    //Phat trien sau
    return $jsContent;
}

?>