<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/15/2010 15:32
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

if( empty( $id ) ) die( 'Stop!!!' );

$sql = 'SELECT act FROM ' . NV_BANNERS_GLOBALTABLE. '_rows WHERE id=' . $id . ' AND act IN (0,1,3)';
$row = $db->query( $sql )->fetch();
if( empty( $row ) ) die( 'Stop!!!' );

$act = intval( $row['act'] );
if( $act == 0 ) $act = 1;
elseif( $act == 1 ) $act = 3;
elseif( $act == 3 ) $act = 1;

$sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE. '_rows SET act=' . $act . ' WHERE id=' . $id;
$return = ( $db->exec( $sql ) ) ? 'OK' : 'NO';

nv_CreateXML_bannerPlan();

include NV_ROOTDIR . '/includes/header.php';
echo $return . '|act_' . $id;
include NV_ROOTDIR . '/includes/footer.php';