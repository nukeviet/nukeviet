<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */
if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( isset( $array_op[0] ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

if( ! defined( 'NV_IS_ADMIN' ) and ! $global_config['allowuserlogin'] )
{
	$contents = user_info_exit( $lang_module['notallowuserlogin'] );
}
else
{
	if( ! defined( 'NV_IS_USER' ) )
	{
		$url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login';
		$nv_redirect = nv_get_redirect();
		if( ! empty( $nv_redirect ) ) $url .= '&nv_redirect=' . $nv_redirect;
		Header( 'Location: ' . nv_url_rewrite( $url, true ) );
		exit();
	}
	else
	{
		$contents = user_welcome();
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
