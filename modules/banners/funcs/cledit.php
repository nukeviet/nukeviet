<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 23:58
 */

if( ! defined( 'NV_IS_MOD_BANNERS' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

if( ! defined( 'NV_IS_BANNER_CLIENT' ) or empty( $banner_client_info ) ) die( '&nbsp;' );

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
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

	$check_email = nv_check_valid_email( $email );
	$check_pass = nv_check_valid_pass( $pass, NV_UPASSMAX, NV_UPASSMIN );

	if( $website == 'http://' ) $website = '';

	if( ! empty( $check_email ) ) die( strip_tags( $check_email ) . '|email_iavim' );
	elseif( ! empty( $pass ) and ! empty( $check_pass ) ) die( strip_tags( $check_pass ) . '|pass_iavim' );
	elseif( ! empty( $pass ) and empty( $re_pass ) ) die( strip_tags( $lang_global['re_password_empty'] ) . '|pass_iavim' );
	elseif( ! empty( $pass ) and $pass != $re_pass ) die( strip_tags( sprintf( $lang_global['passwordsincorrect'], $pass, $re_pass ) ) . '|pass_iavim' );
	elseif( empty( $full_name ) ) die( strip_tags( $lang_module['full_name_empty'] ) . '|full_name' );
	elseif( ! empty( $website ) and ! nv_is_url( $website ) ) die( strip_tags( $lang_module['website_incorrect'] ) . '|website_iavim' );
	elseif( ! empty( $yim ) and ! preg_match( '/^[a-zA-Z0-9\.\-\_]+$/', $yim ) ) die( strip_tags( $lang_module['yim_incorrect'] ) . '|yim_iavim' );

	$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE. '_clients WHERE id!=' . $banner_client_info['id'] . ' AND email= :email' );
	$stmt->bindParam( ':email', $email, PDO::PARAM_STR, strlen( $email ) );
	$stmt->execute();

	if( $stmt->fetchColumn() ) die( strip_tags( sprintf( $lang_module['email_is_already_in_use'], $email ) ) . '|email_iavim' );

	$sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE. '_clients SET ';
	if( ! empty( $pass ) ) $sql .= 'pass= :pass, ';
	$sql .= 'full_name= :full_name, email= :email, website= :website, location= :location, yim= :yim, phone= :phone, fax= :fax, mobile= :mobile WHERE id=' . $banner_client_info['id'];

	$stmt = $db->prepare( $sql) ;
	if( ! empty( $pass ) ) $stmt->bindParam( ':pass', $crypt->hash( $pass ), PDO::PARAM_STR );
	$stmt->bindParam( ':full_name', $full_name, PDO::PARAM_STR );
	$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
	$stmt->bindParam( ':website', $website, PDO::PARAM_STR );
	$stmt->bindParam( ':location', $location, PDO::PARAM_STR );
	$stmt->bindParam( ':yim', $yim, PDO::PARAM_STR );
	$stmt->bindParam( ':phone', $phone, PDO::PARAM_STR );
	$stmt->bindParam( ':fax', $fax, PDO::PARAM_STR );
	$stmt->bindParam( ':mobile', $mobile, PDO::PARAM_STR );
	$stmt->execute();

	die( 'OK|action' );
}

$website = ! empty( $banner_client_info['website'] ) ? $banner_client_info['website'] : 'http://';

$contents = array();
$contents['rows']['full_name'] = array( $lang_global['full_name'], 'full_name', $banner_client_info['full_name'], 255 );
$contents['rows']['email'] = array( $lang_global['email'], 'email_iavim', $banner_client_info['email'], 50 );
$contents['rows']['website'] = array( $lang_module['website'], 'website_iavim', $website, 255 );
$contents['rows']['location'] = array( $lang_module['location'], 'location', $banner_client_info['location'], 255 );
$contents['rows']['yim'] = array( $lang_module['yim'], 'yim_iavim', $banner_client_info['yim'], 100 );
$contents['rows']['phone'] = array( $lang_global['phonenumber'], 'phone', $banner_client_info['phone'], 100 );
$contents['rows']['fax'] = array( $lang_module['fax'], 'fax', $banner_client_info['fax'], 100 );
$contents['rows']['mobile'] = array( $lang_module['mobile'], 'mobile', $banner_client_info['mobile'], 100 );

$contents['npass']['pass'] = array( $lang_module['new_pass'], 'pass_iavim', NV_UPASSMAX );
$contents['npass']['re_pass'] = array( $lang_global['password2'], 're_pass_iavim', NV_UPASSMAX );

$contents['edit_name'] = $lang_global['submit'];
$contents['edit_onclick'] = "nv_cl_edit_save('full_name','email_iavim','website_iavim','location','yim_iavim','phone','fax','mobile','pass_iavim','re_pass_iavim','submit_button');";
$contents['edit_id'] = 'submit_button';
$contents['cancel_name'] = $lang_global['cancel'];
$contents['cancel_onclick'] = "nv_cl_info('action');";

$contents = cledit_theme( $contents );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';