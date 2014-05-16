<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$payment = $nv_Request->get_string( 'id', 'post,get', '' );
$value = $nv_Request->get_int( 'value', 'post,get', 0 );

$table = $db_config['prefix'] . '_' . $module_data . '_payment';
$contents = $lang_module['active_change_not_complete'];

if( ! empty( $payment ) )
{
	$stmt = $db->prepare( 'SELECT active FROM ' . $table . ' WHERE payment= :payment' );
	$stmt->bindParam( ':payment', $payment, PDO::PARAM_STR );
	$stmt->execute();
	$value = $stmt->fetchColumn();
	$value = ( $value == '1' ) ? '0' : '1';

	$stmt = $db->prepare( 'UPDATE ' . $table . ' SET active=' . $value . ' WHERE payment= :payment' );
	$stmt->bindParam( ':payment', $payment, PDO::PARAM_STR );
	if( $stmt->execute() )
	{
		$contents = $lang_module['active_change_complete'];
	}
}

nv_del_moduleCache( $module_name );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';