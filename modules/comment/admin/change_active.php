<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$cid = $nv_Request->get_int( 'cid', 'post', 0 );

$sql = 'SELECT cid FROM ' . NV_PREFIXLANG . '_comments WHERE cid=' . $cid;

$cid = $db->query( $sql )->fetchColumn();
if( empty( $cid ) ) die( 'NO_' . $cid );

$new_status = $nv_Request->get_bool( 'new_status', 'post' );
$new_status = ( int )$new_status;

$sql = 'UPDATE ' . NV_PREFIXLANG . '_comments SET status=' . $new_status . ' WHERE cid=' . $cid;
$db->query( $sql );
nv_del_moduleCache( $module_name );

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id;
include NV_ROOTDIR . '/includes/footer.php';