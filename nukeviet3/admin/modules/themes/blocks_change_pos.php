<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$group = $nv_Request->get_int( 'groupbl', 'post' );
$pos = htmlspecialchars( $nv_Request->get_string( 'pos', 'post' ), ENT_QUOTES );

if ( ! empty( $pos ) and $group > 0 )
{
    $result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE groupbl=" . intval( $group ) . "" );
    while ( list( $bids ) = $db->sql_fetchrow( $result ) )
    {
        $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "` SET position=" . $db->dbescape( $pos ) . " WHERE `bid`='$bids'" );
    }
}
?>