<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$contents = "";
$id = $nv_Request->get_int( 'id', 'get', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
$page = $nv_Request->get_int( 'page', 'get', 0 );

if( $module_config[$module_name]['comment'] and $id > 0 and $checkss == md5( $id . session_id() . $global_config['sitekey'] ) )
{
	$comment_array = nv_comment_module( $id, $page );
	$contents = comment_theme( $comment_array );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>