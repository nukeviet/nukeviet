<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 22/11/2010, 6:25
 */

$maxAge = 3600 * 24;
$cacheDir = str_replace( '\\', '/', realpath( dirname( __file__ ) . '/cache' ) );

if ( ! isset( $_GET['file'] ) )
{
    header( "HTTP/1.1 404 Not Found" );
    exit;
}

$file = $_GET['file'];

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
        header( "HTTP/1.1 403 Forbidden" );
        exit;
}

if ( ! is_file( $file ) )
{
    header( "HTTP/1.1 404 Not Found" );
    exit;
}

$lastmod = filemtime( $file );
$md5file = md5( $file );

if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
{
    $modsince = strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] );
    if ( $modsince != -1 && $modsince == $lastmod )
    {
        header( "HTTP/1.1 304 Not Modified" );
        header( 'Content-Length: 0' );
        exit();
    }
}
else
{
    $hash = $lastmod . '-' . $md5file;

    if ( isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) && stripslashes( $_SERVER['HTTP_IF_NONE_MATCH'] ) == '"' . $hash . '"' )
    {
        header( "HTTP/1.1 304 Not Modified" );
        header( 'Content-Length: 0' );
        exit();
    }

    header( "Etag: \"" . $hash . "\"" );
}

if ( file_exists( $cacheDir . '/' . $contenttype . '_' . $md5file . '_' . $lastmod . '.cache' ) )
{
    $data = file_get_contents( $cacheDir . '/' . $contenttype . '_' . $md5file . '_' . $lastmod . '.cache' );
    outputContent( $data, $lastmod );
    exit();
}
else
{
    $fs = glob( $cacheDir . '/' . $contenttype . '_' . $md5file . '_*.cache' );
    if ( ! empty( $fs ) )
    {
        foreach ( $fs as $f )
        {
            if ( preg_match( "/" . $contenttype . "\_" . $md5file . "\_([\d]+)\.cache$/", $f ) )
            {
                @unlink( $f );
            }
        }
    }
}

$data = file_get_contents( $file );
$data = call_user_func( "compress_" . $contenttype, $data );
file_put_contents( $cacheDir . '/' . $contenttype . '_' . $md5file . '_' . $lastmod . '.cache', $data );
outputContent( $data, $lastmod );
exit();

/**
 * outputContent()
 * 
 * @param mixed $cssContent
 * @param mixed $lastmod
 * @return void
 */
function outputContent( $content, $lastmod )
{
    global $maxAge;

    $currenttime = time();

    ob_start( "ob_gzhandler" );
    @header( "Content-Type: text/css; charset=utf-8" );
    @header( 'Cache-Control: public; max-age=' . $maxAge );
    @header( 'Last-Modified: ' . gmdate( "D, d M Y H:i:s", $lastmod ) . " GMT" );
    @header( "expires: " . gmdate( "D, d M Y H:i:s", $currenttime + $maxAge ) . " GMT" );

    echo $content;
    exit();
}

/**
 * compress_css()
 * 
 * @param mixed $cssContent
 * @return
 */
function compress_css( $cssContent )
{
    /* Xoa cac dau ' & " trong url() */
    $cssContent = preg_replace( "/url[\s]*\([\s]*[\'|\"](.*)?[\'|\"][\s]*\)/", "url($1)", $cssContent );
    /* Xoa chu thich */
    $cssContent = preg_replace( '/(\/\*.*?\*\/|^ | $)/is', '', $cssContent );
    /* Xoa xuong dong, dau cach TAB */
    $cssContent = preg_replace( '/[\s\t\r\n]+/', ' ', $cssContent );
    /* Xoa cac dau cach truoc va sau }, {, ;, ,: */
    $cssContent = preg_replace( '/[\s]*(\:|\,|\;|\{|\})[\s]*/', "$1", $cssContent );
    /* Xoa loi 2 dau # */
    $cssContent = preg_replace( "/[\#]+/", "#", $cssContent ); //Neu co ten 1 dau #
    /* 0px -> 0 */
    $cssContent = str_replace( array( ' 0px', ':0px', ';}', ':0 0 0 0', ':0.', ' 0.' ), array( ' 0', ':0', '}', ':0', ':.', ' .' ), $cssContent );
    /* Xoa CSS khong co noi dung */
    $cssContent = preg_replace( "/[^\}]+\{[\s|\;]*\}[\s]*/", "", $cssContent );
    /* Xoa khoang trang */
    $cssContent = preg_replace( "/[\s]+/", " ", $cssContent );
    return $cssContent;
}

/**
 * compress_javascript()
 * 
 * @param mixed $jsContent
 * @return
 */
function compress_javascript( $jsContent )
{
    //Phat trien sau
    return $jsContent;
}

?>