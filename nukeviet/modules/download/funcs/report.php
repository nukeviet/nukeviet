<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

$dlrp = $nv_Request->get_string( 'dlrp', 'session', '' );

$dlrp = ! empty( $dlrp ) ? unserialize( $dlrp ) : array();

if ( $id and ! in_array( $id, $dlrp ) )
{
    $dlrp[] = $id;
    $dlrp = serialize( $dlrp );
    $nv_Request->set_Session( 'dlrp', $dlrp );

    $query = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    list( $num ) = $db->sql_fetchrow( $result );

    if ( $num )
    {
        $query = "REPLACE INTO `" . NV_PREFIXLANG . "_" . $module_data . "_report` VALUES (" . $id . ", " . $db->dbescape( $client_info['ip'] ) . ", " . NV_CURRENTTIME . ")";
        $db->sql_query( $query );
    }
}

die( "OK" );

?>