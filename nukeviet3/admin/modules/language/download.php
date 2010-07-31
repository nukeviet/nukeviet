<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$dirlang = filter_text_input( 'dirlang', 'get', '' );
$page_title = $language_array[$dirlang]['name'] . " -> " . $lang_module['nv_admin_read'];
if ( $nv_Request->get_string( 'checksess', 'get' ) == md5( "downloadallfile" . session_id() ) )
{
    if ( ! empty( $dirlang ) )
    {
        $allowfolder = array();
        $dirs = nv_scandir( NV_ROOTDIR . "/modules", $global_config['check_module'] );
        $err = 0;
        foreach ( $dirs as $module )
        {
            if ( file_exists( NV_ROOTDIR . "/modules/" . $module . "/language/admin_" . $dirlang . ".php" ) )
            {
                $allowfolder[] = NV_ROOTDIR . "/modules/" . $module . "/language/admin_" . $dirlang . ".php";
            }
            if ( file_exists( NV_ROOTDIR . "/modules/" . $module . "/language/" . $dirlang . ".php" ) )
            {
                $allowfolder[] = NV_ROOTDIR . "/modules/" . $module . "/language/" . $dirlang . ".php";
            }
        }
        if ( is_dir( NV_ROOTDIR . "/language/" . $dirlang ) )
        {
            $allowfolder[] = NV_ROOTDIR . "/language/" . $dirlang;
        }
        
        require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
        if ( file_exists( NV_ROOTDIR . '/'.NV_TEMP_DIR.'/' . $dirlang . '.zip' ) )
        {
            unlink( NV_ROOTDIR . '/'.NV_TEMP_DIR.'/' . $dirlang . '.zip' );
        }
        $zip = new PclZip( NV_ROOTDIR . '/'.NV_TEMP_DIR.'/' . $dirlang . '.zip' );
        $zip->create( $allowfolder, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR );
        $filesize = @filesize( NV_ROOTDIR . '/'.NV_TEMP_DIR.'/' . $dirlang . '.zip' );
        $contents = array();
        $contents['mime'] = " application/zip";
        $contents['fname'] = $dirlang . '.zip';
        header( 'Content-Description: File Transfer' );
        header( "Content-Type: " . $contents['mime'] . "; name=\"" . $contents['fname'] . "\"" );
        header( "Content-Disposition: attachment; filename=\"" . basename( $contents['fname'] ) . "\"" );
        header( "Content-Transfer-Encoding: binary" );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Pragma: public' );
        header( "Content-Length: " . $filesize );
        ob_end_clean();
        flush();
        readfile( NV_ROOTDIR . '/'.NV_TEMP_DIR.'/' . $dirlang . '.zip' );
        exit();
    }
}
else
{
    die( "error checksess" );
}

?>