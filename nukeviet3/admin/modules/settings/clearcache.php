<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 28/11/2010, 16:48
 */

if ( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

function nv_clearCache( $dir, $base )
{
    $dels = array();
    $files = scandir( $dir );
    foreach ( $files as $file )
    {
        if ( $file != "." and $file != ".." and $file != ".htaccess" and $file != "index.html" )
        {
            @unlink( $dir . '/' . $file );
            $dels[] = $base . '/' . $file;
        }
    }
    if ( ! file_exists( $dir . "/index.html" ) )
    {
        file_put_contents( $dir . "/index.html", "" );
    }

    return $dels;
}

$contents = "<br /><strong>" . $lang_module['clearcacheDetail'] . "</strong>:<br /><br />\r\n<ul>\r\n<ol>\r\n";

$cacheDir = NV_ROOTDIR . '/cache';
$files = nv_clearCache( $cacheDir, 'cache' );
foreach($files as $file)
{
    $contents .= "<li>" . $file . "</li>\r\n";
}
$cssDir = NV_ROOTDIR . '/files/css';
$files = nv_clearCache( $cssDir, 'files/css' );
foreach($files as $file)
{
    $contents .= "<li>" . $file . "</li>\r\n";
}
$jsDir = NV_ROOTDIR . '/files/js';
$files = nv_clearCache( $jsDir, 'files/js' );
foreach($files as $file)
{
    $contents .= "<li>" . $file . "</li>\r\n";
}

$contents .= "</ol>\r\n</ul>";

$page_title = $lang_module['clearcache'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>