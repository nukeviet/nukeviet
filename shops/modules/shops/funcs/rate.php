<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$contents = "";
$difftimeout = 360;
$id = $nv_Request->get_int( 'id', 'get,post', 0 );
$val = $nv_Request->get_int( 'val', 'get,post', 0 );

$timeout = $nv_Request->get_int( $module_data . '_' . $op . '_' . $id, 'cookie', 0 );
if( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
{
	$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_rows` SET `ratingdetail`=`ratingdetail`+" . $val . " WHERE `id`=" . $id;
	$db->sql_query( $sql );
	$nv_Request->set_Cookie( $module_data . '_' . $op . '_' . $id, NV_CURRENTTIME );
	$msg = sprintf( $lang_module['detail_rate_ok'], $val );
	$contents = "OK_" . $msg;
}
else
{
	$timeout = ceil( ( $difftimeout - NV_CURRENTTIME + $timeout ) / 60 );
	$timeoutmsg = sprintf( $lang_module['detail_rate_timeout'], $timeout );
	$contents = "ERR_" . $timeoutmsg;
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>