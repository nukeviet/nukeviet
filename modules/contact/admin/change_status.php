<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/7/2010, 3:26
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id=' . $id;
$id = $db->query( $sql )->fetchColumn();

if( empty( $id ) ) die( 'NO' );

$new_status = $nv_Request->get_bool( 'new_status', 'post' );
$new_status = ( int )$new_status;

$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_department SET act=' . $new_status . ' WHERE id=' . $id );

nv_del_moduleCache( $module_name );

include NV_ROOTDIR . '/includes/header.php';
echo 'OK';
include NV_ROOTDIR . '/includes/footer.php';