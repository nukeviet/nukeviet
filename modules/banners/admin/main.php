<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['main_caption'];

$contents = array();
$contents['containerid'] = array();
$contents['aj'] = array();

$new = $db->query( "SELECT COUNT(*) FROM " . NV_BANNERS_GLOBALTABLE. "_rows WHERE act=4" )->fetchColumn();

if( $new > 0 )
{
	$contents['containerid'][] = 'new_list';
	$contents['aj'][] = "nv_show_banners_list('new_list', 0, 0, 4);";
}

$deact = $db->query( "SELECT COUNT(*) FROM " . NV_BANNERS_GLOBALTABLE. "_rows WHERE act=3" )->fetchColumn();

if( $deact > 0 )
{
	$contents['containerid'][] = 'deact_list';
	$contents['aj'][] = "nv_show_banners_list('deact_list', 0, 0, 3);";
}

$exp = $db->query( "SELECT COUNT(*) FROM " . NV_BANNERS_GLOBALTABLE. "_rows WHERE act=2" )->fetchColumn();

if( $exp > 0 )
{
	$contents['containerid'][] = 'exp_list';
	$contents['aj'][] = "nv_show_banners_list('exp_list', 0, 0, 2);";
}

if( empty( $contents['containerid'] ) or empty( $contents['aj'] ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=banners_list' );
	die();
}

$contents = call_user_func( "nv_main_theme", $contents );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';