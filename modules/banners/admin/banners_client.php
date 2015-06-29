<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/11/2010 23:32
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( empty( $id ) ) die( 'Stop!!!' );

$sql = "SELECT full_name FROM " . NV_BANNERS_GLOBALTABLE. "_clients WHERE id=" . $id;
$full_name = $db->query( $sql )->fetchColumn();

if( $full_name != 1 ) die( 'Stop!!!' );

$contents = array();
$contents['info'] = '';

$sql = "SELECT COUNT(*) FROM " . NV_BANNERS_GLOBALTABLE. "_rows WHERE clid=" . $id;
$numrows = $db->query( $sql )->fetchColumn();
if( $numrows != 1 )
{
	$contents['info'] = sprintf( $lang_module['banners_client_empty'], $full_name );
	$contents = call_user_func( "nv_banners_client_theme", $contents );

	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

$contents['caption'] = sprintf( $lang_module['banners_client_caption'], $full_name );

$contents = call_user_func( "nv_banners_client_theme", $contents );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';