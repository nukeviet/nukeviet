<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$payment = $nv_Request->get_string( 'oid', 'post', '' );
$new_weight = $nv_Request->get_int( 'w', 'post', 0 );

$content = "NO_" . $payment;
$table = $db_config['prefix'] . "_" . $module_data . "_payment";

$stmt = $db->prepare( "SELECT payment, weight FROM " . $table . " WHERE payment= :payment" );
$stmt->bindParam( ':payment', $payment, PDO::PARAM_STR );
$stmt->execute();
list( $payment, $weight_old ) = $stmt->fetch( 3 );
if( ! empty( $payment ) )
{
	$sql = "SELECT payment FROM " . $table . " WHERE weight = " . intval( $new_weight );
	$result = $db->query( $sql );
	$payment_swap = $result->fetchColumn();

	$stmt = $db->prepare( "UPDATE " . $table . " SET weight=" . $new_weight . " WHERE payment= :payment" );
	$stmt->bindParam( ':payment', $payment, PDO::PARAM_STR );
	$stmt->execute();

	$stmt = $db->prepare( "UPDATE " . $table . " SET weight=" . $weight_old . " WHERE payment= :payment" );
	$stmt->bindParam( ':payment', $payment, PDO::PARAM_STR );
	$stmt->execute();

	$content = "OK_" . $payment;
	nv_del_moduleCache( $payment );
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';