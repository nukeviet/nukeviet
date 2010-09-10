<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['checkupdate'];

function get_version ( )
{
    global $global_config;
    
    $my_file = NV_ROOTDIR . '/' . NV_DATADIR . '/nukeviet.version.xml';
    
    $xmlcontent = false;
    
    if ( file_exists( $my_file ) and filemtime( $my_file ) > NV_CURRENTTIME - 3600 )
    {
        $xmlcontent = simplexml_load_file( $my_file );
    }
    else
    {
        include ( NV_ROOTDIR . "/includes/class/geturl.class.php" );
        $getContent = new UrlGetContents( $global_config );
        $content = $getContent->get( 'http://update.nukeviet.vn/nukeviet.version.xml' );
        
        if ( $content )
        {
            $xmlcontent = simplexml_load_string( $content );
            if ( $xmlcontent != false )
            {
                file_put_contents( $my_file, $content );
            }
        }
    }
    
    return $xmlcontent;
}

function nv_version_compare ( $version1, $version2 )
{
    $v1 = explode( '.', $version1 );
    $v2 = explode( '.', $version2 );
    
    if ( $v1[0] > $v2[0] )
    {
        return 1;
    }
    
    if ( $v1[0] < $v2[0] )
    {
        return - 1;
    }
    
    if ( $v1[1] > $v2[1] )
    {
        return 1;
    }
    
    if ( $v1[1] < $v2[1] )
    {
        return - 1;
    }
    
    if ( $v1[2] > $v2[2] )
    {
        return 1;
    }
    
    if ( $v1[2] < $v2[2] )
    {
        return - 1;
    }
    
    return 0;
}

#host


$new_version = get_version();
if ( $new_version == false )
{
    $contents = $lang_module['update_error'];
}
else
{
    $newinfo = "
		<br /><strong>" . $lang_module['version_info'] . ":</strong>
		<br />" . $lang_module['version_name'] . ": " . ( $new_version->name ) . "
		<br />" . $lang_module['version_number'] . ": " . ( $new_version->version ) . "
		<br />" . $lang_module['version_date'] . ": " . ( $new_version->date ) . "
		<br />";
    $newinfolink = "";
    if ( $new_version->link != "" )
    {
        $newinfolink = $lang_module['version_download'] . " <a title\"" . $lang_module['version_updatenew'] . "\" href=\"" . $new_version->link . "\">" . $new_version->name . "</a><br />";
    }
    
    if ( nv_version_compare( $global_config['version'], $new_version->version ) < 0 )
    {
        $contents = "<p style=\"font-weight:bold;color:red;margin-top:20px\">" . $lang_module['version_no_latest'] . " </p>";
        $contents .= $newinfolink . "<br />" . $newinfo;
        $contents .= $lang_module['version_note'] . ": <br />";
        $contents .= "<p style=\"font-style:italic\">" . $new_version->message . "</p>";
    }
    else
    {
        $contents = "<p style=\"margin:50px;\">" . $lang_module['version_latest'] . " <br>" . $newinfo . "</p>";
    }
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>