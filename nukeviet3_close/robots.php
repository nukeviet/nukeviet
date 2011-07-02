<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 4/12/2010, 17:25
 */

$host = ( isset( $_GET['action'] ) and ! empty( $_GET['action'] ) ) ? $_GET['action'] : $_SERVER['HTTP_HOST'];

$createTime = gmmktime( 0, 0, 0, date( 'm' ), 1, date( 'Y' ) );
$maxAge = 2592000;
$expTme = $createTime + $maxAge;
$hash = $createTime . '-' . md5( $host );

header( "Etag: \"" . $hash . "\"" );

if ( isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) && stripslashes( $_SERVER['HTTP_IF_NONE_MATCH'] ) == '"' . $hash . '"' )
{
    header( "HTTP/1.1 304 Not Modified" );
    header( 'Content-Length: 0' );
    exit();
}

$allowedDirs = array( 'files', 'images', 'js', 'themes', 'uploads', 'forum' );
$allowedFiles = array( 'favicon.ico', 'index.php', 'robots.txt', 'CJzip.php', 'config.php', 'Sitemap.xml' );

$base_siteurl = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME );
if ( $base_siteurl == '\\' or $base_siteurl == '/' ) $base_siteurl = '';
if ( ! empty( $base_siteurl ) ) $base_siteurl = str_replace( '\\', '/', $base_siteurl );
if ( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/[\/]+$/", '', $base_siteurl );
if ( ! empty( $base_siteurl ) ) {
	$base_siteurl = preg_replace( "/^[\/]*(.*)$/", '/\\1', $base_siteurl );
    $base_siteurl = preg_replace( "#/index\.php(.*)$#", '', $base_siteurl );
}
$base_siteurl .= '/';
$rootDir = str_replace( '\\', '/', realpath( dirname( __file__ ) ) );
$dirs = scandir( $rootDir );

$contents = array();
$contents[] = "User-agent: *";

foreach ( $dirs as $dir )
{
    if ( ! preg_match( "/^\.(.*)$/", $dir ) and ! in_array( $dir, $allowedDirs ) and ! in_array( $dir, $allowedFiles ) )
    {
        if ( is_dir( $rootDir . '/' . $dir ) ) $contents[] = "Disallow: /" . $dir . "/";
        else $contents[] = "Disallow: /" . $dir;
    }
}

$contents[] = "Sitemap: http://" . $host . $base_siteurl . "Sitemap.xml";
$contents = implode( "\n", $contents );

header( "Content-Type: text/plain; charset=utf-8" );
header( 'Cache-Control: public; max-age=' . $maxAge );
header( 'Last-Modified: ' . gmdate( "D, d M Y H:i:s", $createTime ) . " GMT" );
header( "expires: " . gmdate( "D, d M Y H:i:s", $expTme ) . " GMT" );

print_r( $contents );

?>