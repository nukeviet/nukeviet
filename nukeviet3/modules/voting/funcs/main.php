<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:33
 */

if ( ! defined( 'NV_IS_MOD_VOTING' ) ) die( 'Stop!!!' );

$vid = $nv_Request->get_int( 'vid', 'get', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
$lid = $nv_Request->get_string( 'lid', 'get', '' );

if ( $checkss != md5( $vid . $client_info['session_id'] . $global_config['sitekey'] ) or $vid <= 0 or $lid == '' )
{
    header( "location:" . $global_config['site_url'] );
    exit();
}

$sql = "SELECT `vid`, `question`,`acceptcm`, `who_view`, `groups_view`, `publ_time`, `exp_time` 
        FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `act`=1";

$list = nv_db_cache( $sql, 'vid', 'voting' );

if ( empty( $list ) or ! isset( $list[$vid] ) )
{
    header( "location:" . $global_config['site_url'] );
    exit();
}

$row = $list[$vid];
if ( ( int )$row['exp_time'] < 0 or ( ( int )$row['exp_time'] > 0 and $row['exp_time'] < NV_CURRENTTIME ) )
{
    header( "location:" . $global_config['site_url'] );
    exit();
}

if ( ! nv_set_allow( $row['who_view'], $row['groups_view'] ) )
{
    header( "location:" . $global_config['site_url'] );
    exit();
}

$difftimeout = 3600;
$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/voting_logs';
$log_fileext = preg_match( "/[a-z]+/i", NV_LOGS_EXT ) ? NV_LOGS_EXT : 'log';
$pattern = "/^(.*)\." . $log_fileext . "$/i";
$logs = nv_scandir( $dir, $pattern );

if ( ! empty( $logs ) )
{
    foreach ( $logs as $file )
    {
        $vtime = filemtime( $dir . '/' . $file );

        if ( ! $vtime or $vtime <= NV_CURRENTTIME - $difftimeout )
        {
            @unlink( $dir . '/' . $file );
        }
    }
}

$array_id = explode( ",", $lid );
$array_id = array_map( "intval", $array_id );
$array_id = array_diff( $array_id, array( 0 ) );
$count = count( $array_id );

$note = "";

if ( $count )
{
    $acceptcm = ( int )$row['acceptcm'];
    $logfile = md5( NV_LANG_DATA . $global_config['sitekey'] . $client_info['ip'] . $vid ) . '.' . $log_fileext;

    if ( file_exists( $dir . '/' . $logfile ) )
    {
        $timeout = filemtime( $dir . '/' . $logfile );
        $timeout = ceil( ( $difftimeout - NV_CURRENTTIME + $vtime ) / 60 );
        $note = sprintf( $lang_module['timeoutmsg'], $timeout );
    } elseif ( $count <= $acceptcm )
    {
        $in = implode( ",", $array_id );
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `hitstotal` = hitstotal+1 WHERE `vid` ='" . $vid . "' AND `id` IN (" . $in . ")";
        $db->sql_query( $sql );
        file_put_contents( $dir . "/" . $logfile, '', LOCK_EX );
        $note = $lang_module['okmsg'];
    }
    else
    {
        $note = ( $acceptcm > 1 ) ? sprintf( $lang_module['voting_warning_all'], $acceptcm ) : $lang_module['voting_warning_accept1'];
    }
}

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` 
WHERE `vid` = " . $vid . "  ORDER BY `id` ASC";
$result = $db->sql_query( $sql );

$totalvote = 0;
$vrow = array();

while ( $row2 = $db->sql_fetchrow( $result ) )
{
    $totalvote += ( int )$row2['hitstotal'];
    $vrow[] = $row2;
}

$pubtime = nv_date( "l - d/m/Y  H:i", $row['publ_time'] );
$lang = array( "total" => $lang_module['voting_total'], "counter" => $lang_module['voting_counter'], "publtime" => $lang_module['voting_pubtime'] );
$voting = array( 'question' => $row['question'], "total" => $totalvote, "pubtime" => $pubtime, "row" => $vrow, "lang" => $lang, "note" => $note );

$contents = voting_result( $voting );
include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>