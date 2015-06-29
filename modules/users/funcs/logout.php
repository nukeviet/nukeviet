<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_USER' ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

if( defined( 'NV_IS_ADMIN' ) )
{
	nv_insert_logs( NV_LANG_DATA, 'login', '[' . $user_info['username'] . '] ' . $lang_global['admin_logout_title'], ' Client IP:' . NV_CLIENT_IP, 0 );
	$nv_Request->unset_request( 'admin,online', 'session' );
}

$url_redirect = ! empty( $client_info['referer'] ) ? $client_info['referer'] : ( isset( $_SERVER['SCRIPT_URI'] ) ? $_SERVER['SCRIPT_URI'] : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA );
if( defined( 'NV_IS_USER_FORUM' ) )
{
	require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/logout.php' ;
}
else
{
	$nv_Request->unset_request( 'nvloginhash', 'cookie' );
	if( $user_info['current_mode'] == 4 AND file_exists(NV_ROOTDIR . '/modules/users/login/cas-' . $user_info['openid_server'] . '.php') )
	{
		define( 'CAS_LOGOUT_URL_REDIRECT', $url_redirect );
		include NV_ROOTDIR . '/modules/users/login/cas-' . $user_info['openid_server'] . '.php';
	}
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

$info = $lang_module['logout_ok'] . "<br /><br />\n";
$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
$info .= "[<a href=\"" . $url_redirect . "\">" . $lang_module['redirect_to_back'] . "</a>]";

$contents = user_info_exit( $info );
$contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( $url_redirect ) . "\" />";

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';