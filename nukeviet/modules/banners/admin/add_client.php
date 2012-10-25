<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$error = "";

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$login = strip_tags( $nv_Request->get_string( 'login_iavim', 'post', '' ) );
	$pass = strip_tags( $nv_Request->get_string( 'pass_iavim', 'post', '' ) );
	$re_pass = strip_tags( $nv_Request->get_string( 're_pass_iavim', 'post', '' ) );
	$full_name = nv_htmlspecialchars( strip_tags( $nv_Request->get_string( 'full_name', 'post', '' ) ) );
	$email = strip_tags( $nv_Request->get_string( 'email_iavim', 'post', '' ) );
	$website = strip_tags( $nv_Request->get_string( 'website_iavim', 'post', '' ) );
	$location = nv_htmlspecialchars( strip_tags( $nv_Request->get_string( 'location', 'post', '' ) ) );
	$yim = nv_htmlspecialchars( strip_tags( $nv_Request->get_string( 'yim_iavim', 'post', '' ) ) );
	$phone = strip_tags( $nv_Request->get_string( 'phone', 'post', '' ) );
	$fax = strip_tags( $nv_Request->get_string( 'fax', 'post', '' ) );
	$mobile = strip_tags( $nv_Request->get_string( 'mobile', 'post', '' ) );
	$uploadtype = $nv_Request->get_array( 'uploadtype', 'post' );
	$uploadtype = implode( ',', $uploadtype );
	$check_login = nv_check_valid_login( $login, NV_UNICKMAX, NV_UNICKMIN );
	$check_email = nv_check_valid_email( $email );
	$check_pass = nv_check_valid_pass( $pass, NV_UPASSMAX, NV_UPASSMIN );

	if( $website == "http://" ) $website = "";

	if( ! empty( $check_login ) )
	{
		$error = $check_login;
		$login = "";
	}
	elseif( ! empty( $check_email ) )
	{
		$error = $check_email;
		$email = "";
	}
	elseif( ! empty( $check_pass ) )
	{
		$error = $check_pass;
		$pass = $re_pass = "";
	}
	elseif( empty( $re_pass ) )
	{
		$error = $lang_global['re_password_empty'];
	}
	elseif( $pass != $re_pass )
	{
		$error = sprintf( $lang_global['passwordsincorrect'], $pass, $re_pass );
		$pass = $re_pass = "";
	}
	elseif( empty( $full_name ) )
	{
		$error = $lang_module['full_name_empty'];
	}
	elseif( ! empty( $website ) and ! nv_is_url( $website ) )
	{
		$error = $lang_module['website_incorrect'];
	}
	elseif( ! empty( $yim ) and ! preg_match( "/^[a-zA-Z0-9\.\-\_]+$/", $yim ) )
	{
		$error = $lang_module['yim_incorrect'];
	}
	elseif( $db->sql_numrows( $db->sql_query( "SELECT `id` FROM `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` WHERE `login`=" . $db->dbescape( $login ) ) ) > 0 )
	{
		$error = sprintf( $lang_module['login_is_already_in_use'], $login );
		$login = "";
	}
	elseif( $db->sql_numrows( $db->sql_query( "SELECT `id` FROM `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` WHERE `email`=" . $db->dbescape( $email ) ) ) > 0 )
	{
		$error = sprintf( $lang_module['email_is_already_in_use'], $email );
		$email = "";
	}
	else
	{
		$pass_crypt = $crypt->hash( $pass );
	
		$sql = "INSERT INTO `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` (`id`, `login`, `pass`, `reg_time`, `full_name`, `email`, `website`, `location`, `yim`, `phone`, `fax`, `mobile`, `act`, `check_num`, `last_login`, `last_ip`, `last_agent`, `uploadtype`) VALUES 
        (NULL, " . $db->dbescape( $login ) . ", " . $db->dbescape( $pass_crypt ) . ", " . NV_CURRENTTIME . ", " . $db->dbescape( $full_name ) . ", 
        " . $db->dbescape( $email ) . ", " . $db->dbescape( $website ) . ", " . $db->dbescape( $location ) . ", " . $db->dbescape( $yim ) . ", 
        " . $db->dbescape( $phone ) . ", " . $db->dbescape( $fax ) . ", " . $db->dbescape( $mobile ) . ", 1, '', 0, '', ''," . $db->dbescape( $uploadtype ) . ")";
	
		$id = $db->sql_query_insert_id( $sql );
	
		if( $id )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_client', "bannerid " . $id, $admin_info['userid'] );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=client_list" );
			die();
		}
	}
}
else
{
	$login = $pass = $re_pass = $full_name = $email = $website = $location = $yim = $phone = $fax = $mobile = $uploadtype = "";
}

if( $website == "" ) $website = "http://";

$info = ( ! empty( $error ) ) ? $error : $lang_module['add_client_info'];
$is_error = ( ! empty( $error ) ) ? 1 : 0;

$contents = array();
$contents['info'] = $info;
$contents['is_error'] = $is_error;
$contents['submit'] = $lang_module['add_client_submit'];
$contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=add_client";
$contents['login'] = array( $lang_module['login'], 'login_iavim', $login, NV_UNICKMAX );
$contents['pass'] = array( $lang_global['password'], 'pass_iavim', $pass, NV_UPASSMAX );
$contents['re_pass'] = array( $lang_global['password2'], 're_pass_iavim', $re_pass, NV_UPASSMAX );
$contents['full_name'] = array( $lang_module['full_name'], 'full_name', $full_name, 255 );
$contents['email'] = array( $lang_module['email'], 'email_iavim', $email, 70 );
$contents['website'] = array( $lang_module['website'], 'website_iavim', $website, 255 );
$contents['location'] = array( $lang_module['location'], 'location', $location, 255 );
$contents['yim'] = array( $lang_module['yim'], 'yim_iavim', $yim, 255 );
$contents['phone'] = array( $lang_module['phone'], 'phone', $phone, 255 );
$contents['fax'] = array( $lang_module['fax'], 'fax', $fax, 255 );
$contents['mobile'] = array( $lang_module['mobile'], 'mobile', $mobile, 255 );
$contents['uploadtype'] = array( $lang_module['uploadtype'], 'uploadtype' );

$ini = parse_ini_file( NV_ROOTDIR . '/includes/ini/mime.ini', true );
$contents['types'] = array_keys( $ini );

$contents = call_user_func( "nv_add_client_theme", $contents );
$contents .= "<script type=\"text/javascript\">
		//<![CDATA[
		var f = document.getElementById('form_add_client');
		var u = f.elements[0];
		f.setAttribute(\"autocomplete\", \"off\");
		//]]>
	</script>";

$page_title = $lang_module['add_client'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>