<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 * @Development version theme control
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$array = $nv_Request->get_array( 'bl', 'post' );
$func_id = $nv_Request->get_int( 'func', 'post' );
$position = $nv_Request->get_string( 'position', 'post' );
$position = explode( ',', $position );
$position = array_unique( array_filter( $position ) );
$count = 1;
if ( ! empty( $array ) && empty( $position ) )
{
    foreach ( $array as $bid )
    {
        $query = "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight = " . $count . " WHERE bid = " . $bid;
        $db->sql_query( $query );
        $count ++;
    }
}
elseif ( ! empty( $array ) && ! empty( $position ) )
{
    #list bid in different in list array
    $sql = "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE position != '[" . $position[0] . "]' AND bid IN (" . implode( ',', $array ) . ")";
    $result = $db->sql_query( $sql );
    list( $bid ) = $db->sql_fetchrow( $result );
    
    # fetch groupbl info from one bid
    $sql = "SELECT groupbl FROM `" . NV_BLOCKS_TABLE . "` WHERE bid =" . $bid . "";
    $result = $db->sql_query( $sql );
    list( $groupbl ) = $db->sql_fetchrow( $result );
    
    #list all bid from groupbl
    $array_bid = array();
    $sql = "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE groupbl = '" . $groupbl . "'";
    $result = $db->sql_query( $sql );
    while ( list( $bid ) = $db->sql_fetchrow( $result ) )
    {
        $array_bid[] = $bid;
    }
    
    #update list in array first have a bid in $array_bid
    foreach ( $array as $bid )
    {
        $order_in_array = array_search( $bid, $array );
        $query = "UPDATE `" . NV_BLOCKS_TABLE . "` SET position = '[" . trim( $position[0] ) . "]', weight='" . ( $order_in_array + 1 ) . "' WHERE bid = " . $bid;
        $db->sql_query( $query );
    }
    foreach ( $array_bid as $bid )
    {
        if ( ! in_array( $bid, $array ) )
        {
            list( $function ) = $db->sql_fetchrow( $db->sql_query( "SELECT func_id FROM `" . NV_BLOCKS_TABLE . "` WHERE bid='" . $bid . "'" ) );
            list( $maxweight2 ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(weight) FROM `" . NV_BLOCKS_TABLE . "` WHERE func_id ='" . $function . "' AND position='[" . $position[0] . "]'" ) );
            $query = "UPDATE `" . NV_BLOCKS_TABLE . "` SET position = '[" . trim( $position[0] ) . "]', weight='" . ( $maxweight2 + 1 ) . "' WHERE bid = " . $bid;
            $db->sql_query( $query );
        }
    }
}
nv_del_moduleCache( 'themes' );
die( 'OK_' . $func_id );
?>