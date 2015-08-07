<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/11/2010 22:27
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

if( empty( $id ) ) die( 'Stop!!!' );

$query = 'SELECT act FROM ' . NV_BANNERS_GLOBALTABLE. '_clients WHERE id=' . $id;
$row = $db->query( $query )->fetch();
if( empty( $row ) ) die( 'Stop!!!' );

$act = $row['act'] ? 0 : 1;

$sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE. '_clients SET act=' . $act . ' WHERE id=' . $id;
$return = $db->exec( $sql ) ? 'OK' : 'NO';

include NV_ROOTDIR . '/includes/header.php';
echo $return . '|act_' . $id . '|' . $id . '|client_info';
include NV_ROOTDIR . '/includes/footer.php';