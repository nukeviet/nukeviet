<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$order = $nv_Request->get_int( 'order', 'post,get' );
$bid = $nv_Request->get_int( 'bid', 'post,get' );
if ( $order > 0 and $bid > 0 )
{
    list( $position, $oldorder, $func_id, $theme ) = $db->sql_fetchrow( $db->sql_query( "SELECT position, weight, func_id, theme FROM `" . NV_BLOCKS_TABLE . "` WHERE bid=" . $bid . "" ) );
    if ( $oldorder < $order )
    {
        $result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE weight<=" . $order . " AND bid!=" . $bid . " AND position='$position' AND func_id='$func_id' AND theme='$theme' ORDER BY weight DESC" );
        $weight = $order - 1;
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight=" . $weight . " WHERE bid=" . $row['bid'] . "" );
            $weight --;
        }
        $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight=" . $order . " WHERE bid=" . $bid . "" );
    }
    elseif ( $oldorder > $order )
    {
        $result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE weight>=" . $order . " AND bid!=" . $bid . " AND position='$position' AND func_id='$func_id'  AND theme='$theme' ORDER BY weight ASC" );
        $weight = $order + 1;
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight=" . $weight . " WHERE bid=" . $row['bid'] . "" );
            $weight ++;
        }
        $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight=" . $order . " WHERE bid=" . $bid . "" );
    }
    #reupdate
    $result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE position='$position' AND func_id='$func_id' AND theme='$theme' ORDER BY weight ASC" );
    $order = 1;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight=" . $order . " WHERE bid=" . $row['bid'] . "" );
        $order ++;
    }
    nv_del_moduleCache( 'themes' );
}
?>