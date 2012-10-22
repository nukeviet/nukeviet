<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$contents = "";
$payment = $nv_Request->get_string( 'payment', 'get', '' );

// Kiem tra su ton tai cua cong thanh toan.
if ( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".complete.php" ) )
{
	// Lay thong tin config neu cong thanh toan duoc kich hoat.
	$sql = "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_payment` WHERE `active`=1 and `payment`=" . $db->dbescape_string( $payment );
	$result = $db->sql_query( $sql );
	if ( $db->sql_numrows( $result ) )
	{
		$row = $db->sql_fetchrow( $result );
		$payment_config = unserialize( nv_base64_decode( $row['config'] ) );
		$payment_config['paymentname'] = $row['paymentname'];
		$payment_config['domain'] = $row['domain'];
		
		// Xu ly thong tin
		require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".complete.php" );
	}
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>