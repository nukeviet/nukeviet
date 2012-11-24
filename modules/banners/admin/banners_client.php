<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/11/2010 23:32
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( empty( $id ) ) die( 'Stop!!!' );

$sql = "SELECT `full_name` FROM `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if( $numrows != 1 ) die( 'Stop!!!' );
$row = $db->sql_fetchrow( $result );

$full_name = $row['full_name'];

$contents = array();
$contents['info'] = "";

$sql = "SELECT `full_name` FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE `clid`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if( $numrows != 1 )
{
	$contents['info'] = sprintf( $lang_module['banners_client_empty'], $full_name );
	$contents = call_user_func( "nv_banners_client_theme", $contents );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $contents;
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit;
}

$contents['caption'] = sprintf( $lang_module['banners_client_caption'], $full_name );

$contents = call_user_func( "nv_banners_client_theme", $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>