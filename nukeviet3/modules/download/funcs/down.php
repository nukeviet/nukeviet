<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */
if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );
if ( $_SESSION['down'] != 'ok' ) die( 'Stop!!!' );
$permission = false;
global $user_info;
if ( $configdownload['who_view1'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view2'] ) )
{
    $permission = true;
}
if ( $configdownload['who_view1'] == 0 )
{
    $permission = true;
}
if ( $configdownload['who_view1'] == 1 && defined( 'NV_IS_USER' ) )
{
    $permission = true;
}
if ( $configdownload['who_view1'] == 2 && defined( 'NV_IS_ADMIN' ) )
{
    $permission = true;
}
$contents = "";
$fileid = $nv_Request->get_int( 'id', 'get' );

$url_link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=view&id=' . $fileid;
if ( $permission )
{
    list( $fileupload, $linkdirect ) = $db->sql_fetchrow( $db->sql_query( "SELECT fileupload,linkdirect FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE id=" . $fileid . "" ) );
    $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET download=download+1 WHERE id=" . $fileid . "" );
    $path_filename = NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $configdownload['filedir'] . "/" . $fileupload;
    if ( file_exists( $path_filename ) && is_file( $path_filename ) )
    {
        $file_extension = strtolower( substr( strrchr( $fileupload, "." ), 1 ) );
        $ctype = "";
        switch ( $file_extension )
        {
            case "pdf":
                $ctype = "application/pdf";
                break;
            case "exe":
                $ctype = "application/octet-stream";
                break;
            case "zip":
                $ctype = "application/zip";
                break;
            case "zar":
                $ctype = "application/zar";
                break;
            case "doc":
                $ctype = "application/msword";
                break;
            case "xls":
                $ctype = "application/vnd.ms-excel";
                break;
            case "ppt":
                $ctype = "application/vnd.ms-powerpoint";
                break;
            case "gif":
                $ctype = "image/gif";
                break;
            case "png":
                $ctype = "image/png";
                break;
            case "jpeg":
            case "jpg":
                $ctype = "image/jpg";
                break;
            default:
                $ctype = "application/force-download";
        }
        header( "Pragma: public" );
        header( "Expires: 0" );
        header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
        header( "Cache-Control: private", false );
        header( "Content-Type: $ctype" );
        
        header( "Content-Disposition: attachment; filename=\"" . $fileupload . "\";" );
        header( "Content-Transfer-Encoding: binary" );
        header( "Content-Length: " . filesize( $path_filename ) );
        readfile( $path_filename );
        exit();
    }
    else{
    	die("".$path_filename."");
    }
}
else
{
    $url_link1 = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . base64_encode( $url_link );
    Header( "Location:" . $url_link1 );
    exit();
}
$contents = "Error file!";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>
