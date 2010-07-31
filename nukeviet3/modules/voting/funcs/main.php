<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:33
 */

if ( ! defined( 'NV_IS_MOD_VOTING' ) ) die( 'Stop!!!' );

$difftimeout = 3600;
$vid = $nv_Request->get_int( 'vid', 'get', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
$lid = $nv_Request->get_string( 'lid', 'get', '' );
$note = "";

if ( $checkss == md5( $vid . $client_info['session_id'] . $global_config['sitekey'] ) and $vid > 0 and $lid != "" )
{
    $timeout = $nv_Request->get_int( 'voting_timeout_' . $vid, 'cookie', 0 );
    $array_id = array_map( "intval", explode( ",", $lid ) );
    $array_id = array_diff( $array_id, array( 0 ) );
    if ( count( $array_id ) > 0 and ( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout ) )
    {
        $sql = "SELECT `vid`, `question`,`acceptcm` FROM `" . NV_PREFIXLANG . "_" . $module_data . "`   WHERE `act`=1 AND vid=" . $vid;
        $result = $db->sql_query( $sql );
        list( $vid, $question, $accept ) = $db->sql_fetchrow( $result );
        if ( count( $array_id ) <= $accept )
        {
            foreach ( $array_id as $id )
            {
                if ( $id > 0 )
                {
                    $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `hitstotal` = hitstotal+1 WHERE `vid` ='" . $vid . "' AND `id` =" . $id );
                }
            }
            $nv_Request->set_Cookie( 'voting_timeout_' . $vid, NV_CURRENTTIME );
            $note = $lang_module['okmsg'];
        }
        else
        {
            $note = ( $accept > 1 ) ? sprintf( $lang_module['voting_warning_all'], $accept ) : $lang_module['voting_warning_accept1'];
        }
    } elseif ( count( $array_id ) > 0 )
    {
        $timeout = ceil( ( $difftimeout - NV_CURRENTTIME + $timeout ) / 60 );
        $note = sprintf( $lang_module['timeoutmsg'], $timeout );
    }

    $sql = "SELECT a.vid as vid, a.question as question, a.publ_time as publtime, b.title as title, b.hitstotal FROM " . NV_PREFIXLANG . "_" . $module_data . " a INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_rows b ON a.vid=b.vid WHERE a.act='1' and a.vid=" . $vid . " ORDER BY b.id ASC";
    $result = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $totalvote[] = $row['hitstotal'];
        $pubtime = nv_date( "l - d/m/Y  H:i", $row['publtime'] );
        $voting['row'][] = $row;
    }
    $lang = array( "total" => $lang_module['voting_total'], "counter" => $lang_module['voting_counter'], "publtime" => $lang_module['voting_pubtime'] );
    $voting = array( "total" => array_sum( $totalvote ), "pubtime" => $pubtime, "row" => $voting['row'], "lang" => $lang, "note" => $note );

    $contents = voting_result( $voting );
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo $contents;
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
    header( "location:" . $global_config['site_url'] );
}

?>