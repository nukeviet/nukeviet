<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/12/2010 21:52
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

if( empty( $id ) ) die( 'Stop!!!' );

$sql = "SELECT `act` FROM `" . NV_BANNERS_PLANS_GLOBALTABLE . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if( $numrows != 1 ) die( 'Stop!!!' );

$row = $db->sql_fetchrow( $result );
$act = $row['act'] ? 0 : 1;

$sql = "UPDATE `" . NV_BANNERS_PLANS_GLOBALTABLE . "` SET `act`=" . $act . " WHERE `id`=" . $id;
$return = $db->sql_query( $sql );
$return = $return ? "OK" : "NO";

nv_CreateXML_bannerPlan();

include ( NV_ROOTDIR . "/includes/header.php" );
echo $return . '|act_' . $id . '|' . $id . '|plan_info';
include ( NV_ROOTDIR . "/includes/footer.php" );

?>