<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_USER_FORUM' ) )
{
	require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/changepass.php' ;
	exit();
}
elseif( ! defined( 'NV_IS_USER' ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}
$sql = 'SELECT password FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $user_info['userid'];
$query = $db->query( $sql );
$oldpassword = $query->fetchColumn();

$page_title = $mod_title = $lang_module['change_pass'];
$key_words = $module_info['keywords'];

$array_data = array();
$array_data['pass_empty'] = empty( $oldpassword ) ? true : false;
$array_data['change_info'] = $lang_module['change_info'];
$array_data['checkss'] = md5( session_id() . $global_config['sitekey'] );

$array_data['nv_password'] = $nv_Request->get_title( 'nv_password', 'post', '' );
$array_data['new_password'] = $nv_Request->get_title( 'new_password', 'post', '' );
$array_data['re_password'] = $nv_Request->get_title( 're_password', 'post', '' );
$checkss = $nv_Request->get_title( 'checkss', 'post', '' );

if( $checkss == $array_data['checkss'] )
{
	$error = '';

	if( ! empty( $oldpassword ) and ! $crypt->validate( $array_data['nv_password'], $oldpassword ) )
	{
		$error = $lang_global['incorrect_password'];
		$error = str_replace( $lang_global['password'], $lang_module['pass_old'], $error );
	}
	elseif( ( $check_new_password = nv_check_valid_pass( $array_data['new_password'], NV_UPASSMAX, NV_UPASSMIN ) ) != '' )
	{
		$error = $check_new_password;
	}
	elseif( $array_data['new_password'] != $array_data['re_password'] )
	{
		$error = sprintf( $lang_global['passwordsincorrect'], $array_data['new_password'], $array_data['re_password'] );
		$error = str_replace( $lang_global['password'], $lang_module['pass_new'], $error );
	}
	else
	{
		$new_password = $crypt->hash( $array_data['new_password'] );

		$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET password= :password WHERE userid=' . $user_info['userid'] );
		$stmt->bindParam( ':password', $new_password, PDO::PARAM_STR );
		$stmt->execute();

		$contents = user_info_exit( $lang_module['change_pass_ok'] );
		$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . "\" />";

		include NV_ROOTDIR . '/includes/header.php';
		echo nv_site_theme( $contents );
		include NV_ROOTDIR . '/includes/footer.php';
		exit();
	}

	$array_data['change_info'] = "<span style=\"color:#fb490b;\">" . $error . "</span>";
}

$contents = user_changepass( $array_data );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';