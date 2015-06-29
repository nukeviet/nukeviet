<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 21:7
 */

if( ! defined( 'NV_IS_MOD_BANNERS' ) ) die( 'Stop!!!' );

$contents = array();
$contents['containerid'] = "action";

if( defined( 'NV_IS_BANNER_CLIENT' ) )
{
	$contents['aj'] = "nv_cl_info('action');";
}
else
{
	$contents['aj'] = "nv_login_info('action');";
}

$page_title = $module_info['custom_title'] . " " . NV_TITLEBAR_DEFIS . " " . $module_info['funcs'][$op]['func_custom_name'];

$contents = clientinfo_theme( $contents );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';