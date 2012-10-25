<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 11/6/2010, 20:9
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_referer_update()
{
    global $nv_Request, $client_info, $global_config, $db, $prefix;

    if ( $client_info['is_myreferer'] == 0 )
    {
        $host = $nv_Request->referer_host;
        $host = str_replace( 'www.', '', $host );
        $host = explode( '/', $host );
        $host = reset( $host );
        $host = strtolower( $host );

        $log_path = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ref_logs';
        if ( ! is_dir( $log_path ) )
        {
            @nv_mkdir( NV_ROOTDIR . '/' . NV_LOGS_DIR, 'ref_logs', true );
        }

        $log_current = mktime( 0, 0, 0, date( "n", NV_CURRENTTIME ), date( "j", NV_CURRENTTIME ), date( "Y", NV_CURRENTTIME ) );

        $content = '[' . date( "r", NV_CURRENTTIME ) . ']';
        $content .= ' [' . $client_info['ip'] . ']';
        $content .= ' [' . $client_info['referer'] . ']';
        $content .= ' [' . $client_info['selfurl'] . ']';
        $content .= "\r\n";
        $md5 = md5( $client_info['referer'] . $client_info['selfurl'] );

        $is_save = true;

        $referer_blocker = array();
        if ( file_exists( NV_ROOTDIR . '/' . NV_DATADIR . '/referer_blocker.php' ) )
        {
            include ( NV_ROOTDIR . '/' . NV_DATADIR . '/referer_blocker.php' );
        }

        if ( ! empty( $referer_blocker ) )
        {
            foreach ( $referer_blocker as $blocker )
            {
                if ( preg_match( "/" . preg_quote( $blocker ) . "/i", $host ) )
                {
                    $is_save = false;
                    break;
                }
            }
        }

        if ( $is_save )
        {
            $tmp = $log_path . '/tmp.' . NV_LOGS_EXT;
            if ( file_exists( $tmp ) )
            {
                $ct = file_get_contents( $tmp );
                if ( ! empty( $ct ) )
                {
                    $ct = trim( $ct );
                    $ct = explode( "|", $ct );
                    $p = NV_CURRENTTIME - 60;
                    if ( $ct[0] > $p and $ct[1] == $md5 )
                    {
                        $is_save = false;
                    }
                }
            }
        }

        if ( $is_save )
        {
            file_put_contents( $log_path . '/' . $log_current . '.' . NV_LOGS_EXT, $content, FILE_APPEND );
            file_put_contents( $tmp, NV_CURRENTTIME . '|' . $md5 );

            $sql = "UPDATE `" . NV_REFSTAT_TABLE . "` SET 
            total=total+1, 
            month" . date( 'm', NV_CURRENTTIME ) . "=month" . date( 'm', NV_CURRENTTIME ) . "+1, 
            last_update=" . NV_CURRENTTIME . " 
            WHERE `host`=" . $db->dbescape( $host );
            $db->sql_query( $sql );
            $mysql_info = @mysql_info();
            unset( $matches );
            preg_match( "/^\D+(\d+)/", $mysql_info, $matches );
            if ( $matches[1] == 0 )
            {
                $sql = "INSERT INTO `" . NV_REFSTAT_TABLE . "` 
                (`host`, `total`, `month" . date( 'm', NV_CURRENTTIME ) . "`, `last_update`) 
                VALUES (" . $db->dbescape( $host ) . ",1, 1," . NV_CURRENTTIME . ")";
                $db->sql_query( $sql );
            }

            if ( ! empty( $nv_Request->search_engine ) )
            {
                if ( isset( $global_config['engine_allowed'][$nv_Request->search_engine]['query_param'] ) and ! empty( $global_config['engine_allowed'][$nv_Request->search_engine]['query_param'] ) )
                {
                    $key = $global_config['engine_allowed'][$nv_Request->search_engine]['query_param'];
                    $key = $nv_Request->referer_queries[$key];
                    $key = str_replace( "+", " ", $key );
                    $key = nv_strtolower( $key );
                    $key = nv_substr( $key, 0, 100 );
                    $key = trim( $key );
                    $id = md5( $key );

                    if ( ! empty( $key ) )
                    {
                        $sql = "UPDATE `" . NV_SEARCHKEYS_TABLE . "` 
                        SET total=total+1 WHERE `id`=" . $db->dbescape( $id ) . " 
                        AND `search_engine`=" . $db->dbescape( $nv_Request->search_engine );
                        $db->sql_query( $sql );
                        $mysql_info = @mysql_info();
                        unset( $matches );
                        preg_match( "/^\D+(\d+)/", $mysql_info, $matches );
                        if ( $matches[1] == 0 )
                        {
                            $sql = "INSERT INTO `" . NV_SEARCHKEYS_TABLE . "` 
                            VALUES (" . $db->dbescape( $id ) . "," . $db->dbescape( $key ) . ",1," . $db->dbescape( $nv_Request->search_engine ) . ")";
                            $db->sql_query( $sql );
                        }
                    }
                }
            }
        }
    }
}

nv_referer_update();

?>