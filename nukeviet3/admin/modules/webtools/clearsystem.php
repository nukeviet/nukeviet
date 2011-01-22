<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:51
 */

if ( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['clearsystem'];

function nv_clear_files ( $dir, $base )
{
    global $client_info;
    
    $dels = array();
    $files = scandir( $dir );
    foreach ( $files as $file )
    {
        if ( ! preg_match( "/^[\.]{1,2}([a-zA-Z0-9]*)$/", $file ) and //
$file != "index.html" and is_file( $dir . '/' . $file ) and //
$file != "sess_" . $client_info['session_id'] ) //
        

        {
            $d = nv_deletefile( $dir . '/' . $file, false );
            if ( $d[0] )
            {
                $dels[] = $base . '/' . $file;
            }
        }
    }
    if ( ! file_exists( $dir . "/index.html" ) )
    {
        file_put_contents( $dir . "/index.html", "" );
    }
    return $dels;
}

$xtpl = new XTemplate( "clearsystem.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file . "" );
$xtpl->assign( 'LANG', $lang_module );

if ( $nv_Request->isset_request( 'submit', 'post' ) and $nv_Request->isset_request( 'deltype', 'post' ) )
{
    $deltype = $nv_Request->get_typed_array( 'deltype', 'post', 'string', array() );
    if ( in_array( 'clearcache', $deltype ) )
    {
        $cacheDir = NV_ROOTDIR . '/cache';
        $files = nv_clear_files( $cacheDir, 'cache' );
        foreach ( $files as $file )
        {
            $xtpl->assign( 'DELFILE', $file );
            $xtpl->parse( 'main.delfile.loop' );
        }
        $cssDir = NV_ROOTDIR . '/' . NV_FILES_DIR . '/css';
        $files = nv_clear_files( $cssDir, NV_FILES_DIR . '/css' );
        foreach ( $files as $file )
        {
            $xtpl->assign( 'DELFILE', $file );
            $xtpl->parse( 'main.delfile.loop' );
        }
        $jsDir = NV_ROOTDIR . '/' . NV_FILES_DIR . '/js';
        $files = nv_clear_files( $jsDir, NV_FILES_DIR . '/js' );
        foreach ( $files as $file )
        {
            $xtpl->assign( 'DELFILE', $file );
            $xtpl->parse( 'main.delfile.loop' );
        }
        nv_delete_all_cache();
    }
    
    if ( in_array( 'clearsession', $deltype ) )
    {
        $ssDir = NV_ROOTDIR . "/" . NV_SESSION_SAVE_PATH;
        $files = nv_clear_files( $ssDir, NV_SESSION_SAVE_PATH );
        foreach ( $files as $file )
        {
            $xtpl->assign( 'DELFILE', $file );
            $xtpl->parse( 'main.delfile.loop' );
        }
    }
    
    if ( in_array( 'cleardumpbackup', $deltype ) )
    {
        $log_dir = NV_ROOTDIR . "/" . NV_LOGS_DIR . "/dump_backup";
        $files = nv_clear_files( $log_dir, NV_LOGS_DIR . "/dump_backup" );
        foreach ( $files as $file )
        {
            $xtpl->assign( 'DELFILE', $file );
            $xtpl->parse( 'main.delfile.loop' );
        }
    }
    
    if ( in_array( 'clearfiletemp', $deltype ) )
    {
        $dir = NV_ROOTDIR . "/" . NV_TEMP_DIR;
        $files = nv_clear_files( $dir, NV_TEMP_DIR );
        foreach ( $files as $file )
        {
            $xtpl->assign( 'DELFILE', $file );
            $xtpl->parse( 'main.delfile.loop' );
        }
    }
    
    if ( in_array( 'clearerrorlogs', $deltype ) )
    {
        $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs';
        $files = nv_clear_files( $dir, NV_LOGS_DIR . '/error_logs' );
        foreach ( $files as $file )
        {
            $xtpl->assign( 'DELFILE', $file );
            $xtpl->parse( 'main.delfile.loop' );
        }
        
        $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256';
        $files = nv_clear_files( $dir, NV_LOGS_DIR . '/error_logs/errors256' );
        foreach ( $files as $file )
        {
            $xtpl->assign( 'DELFILE', $file );
            $xtpl->parse( 'main.delfile.loop' );
        }
        
        $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/old';
        $files = nv_clear_files( $dir, NV_LOGS_DIR . '/error_logs/old' );
        foreach ( $files as $file )
        {
            $xtpl->assign( 'DELFILE', $file );
            $xtpl->parse( 'main.delfile.loop' );
        }
        
        $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/tmp';
        $files = nv_clear_files( $dir, NV_LOGS_DIR . '/error_logs/tmp' );
        foreach ( $files as $file )
        {
            $xtpl->assign( 'DELFILE', $file );
            $xtpl->parse( 'main.delfile.loop' );
        }
    }
    $xtpl->parse( 'main.delfile' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>