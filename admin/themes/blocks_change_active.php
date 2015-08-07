<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$bid = $nv_Request->get_int( 'bid', 'post,get' );

$sql = 'SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid;
$bid = $db->query( $sql )->fetchColumn();
if( empty( $bid ) ) die( 'NO_' . $bid );

$new_status = $nv_Request->get_bool( 'new_status', 'post' );
$new_status = ( int )$new_status;

$sql = 'UPDATE ' . NV_BLOCKS_TABLE . '_groups SET active=' . $new_status . ' WHERE bid=' . $bid;
$db->query( $sql );
nv_del_moduleCache( 'themes' );

echo 'OK_' . $id;