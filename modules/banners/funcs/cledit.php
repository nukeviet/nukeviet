<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
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

	if( $website == "http://" ) $website = "";

	if( ! empty( $check_email ) ) die( strip_tags( $check_email ) . '|email_iavim' );
	elseif( ! empty( $pass ) and ! empty( $check_pass ) ) die( strip_tags( $check_pass ) . '|pass_iavim' );
	elseif( ! empty( $pass ) and empty( $re_pass ) ) die( strip_tags( $lang_global['re_password_empty'] ) . '|pass_iavim' );
	elseif( ! empty( $pass ) and $pass != $re_pass ) die( strip_tags( sprintf( $lang_global['passwordsincorrect'], $pass, $re_pass ) ) . '|pass_iavim' );
	elseif( empty( $full_name ) ) die( strip_tags( $lang_module['full_name_empty'] ) . '|full_name' );
	elseif( ! empty( $website ) and ! nv_is_url( $website ) ) die( strip_tags( $lang_module['website_incorrect'] ) . '|website_iavim' );
	elseif( ! empty( $yim ) and ! preg_match( "/^[a-zA-Z0-9\.\-\_]+$/", $yim ) ) die( strip_tags( $lang_module['yim_incorrect'] ) . '|yim_iavim' );
	list( $count ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` WHERE `id`!=" . $banner_client_info['id'] . " AND `email`=" . $db->dbescape( $email ) ) );
	if( $count > 0 ) die( strip_tags( sprintf( $lang_module['email_is_already_in_use'], $email ) ) . '|email_iavim' );

	$sql = "UPDATE `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` SET ";
	if( ! empty( $pass ) ) $sql .= "`pass`=" . $db->dbescape( $crypt->hash( $pass ) ) . ", ";
	$sql .= "`full_name`=" . $db->dbescape( $full_name ) . ", `email`=" . $db->dbescape( $email ) . ", `website`=" . $db->dbescape( $website ) . ", 
        `location`=" . $db->dbescape( $location ) . ", `yim`=" . $db->dbescape( $yim ) . ", `phone`=" . $db->dbescape( $phone ) . ", `fax`=" . $db->dbescape( $fax ) . ", 
        `mobile`=" . $db->dbescape( $mobile ) . " WHERE `id`=" . $banner_client_info['id'];
	$db->sql_query( $sql );
	die( "OK|action" );
}

$website = ! empty( $banner_client_info['website'] ) ? $banner_client_info['website'] : "http://";

$contents = array();
$contents['rows']['full_name'] = array( $lang_global['full_name'], "full_name", $banner_client_info['full_name'], 255 );
$contents['rows']['email'] = array( $lang_global['email'], "email_iavim", $banner_client_info['email'], 50 );
$contents['rows']['website'] = array( $lang_module['website'], "website_iavim", $website, 255 );
$contents['rows']['location'] = array( $lang_module['location'], "location", $banner_client_info['location'], 255 );
$contents['rows']['yim'] = array( $lang_module['yim'], "yim_iavim", $banner_client_info['yim'], 100 );
$contents['rows']['phone'] = array( $lang_global['phonenumber'], "phone", $banner_client_info['phone'], 100 );
$contents['rows']['fax'] = array( $lang_module['fax'], "fax", $banner_client_info['fax'], 100 );
$contents['rows']['mobile'] = array( $lang_module['mobile'], "mobile", $banner_client_info['mobile'], 100 );

$contents['npass']['pass'] = array( $lang_module['new_pass'], "pass_iavim", NV_UPASSMAX );
$contents['npass']['re_pass'] = array( $lang_global['password2'], "re_pass_iavim", NV_UPASSMAX );

$contents['edit_name'] = $lang_global['submit'];
$contents['edit_onclick'] = "nv_cl_edit_save('full_name','email_iavim','website_iavim','location','yim_iavim','phone','fax','mobile','pass_iavim','re_pass_iavim','submit_button');";
$contents['edit_id'] = "submit_button";
$contents['cancel_name'] = $lang_global['cancel'];
$contents['cancel_onclick'] = "nv_cl_info('action');";

$contents = cledit_theme( $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>