<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 21:38
 */

if( ! defined( 'NV_IS_MOD_BANNERS' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

if( ! defined( 'NV_IS_BANNER_CLIENT' ) ) die( '&nbsp;' );

$contents = array();
$contents['edit_onclick'] = "nv_cl_edit('action');";
$contents['edit_name'] = $lang_module['edit_clinfo'];

$contents['rows']['login'] = array( $lang_module['login'], $banner_client_info['login'] );
$contents['rows']['email'] = array( $lang_global['email'], nv_EncodeEmail( $banner_client_info['email'] ) );
$contents['rows']['full_name'] = array( $lang_global['full_name'], $banner_client_info['full_name'] );
$contents['rows']['reg_time'] = array( $lang_module['reg_time'], nv_date( "d/m/Y H:i", $banner_client_info['reg_time'] ) );

if( ! empty( $banner_client_info['website'] ) and nv_is_url( $banner_client_info['website'] ) )
{
	$contents['rows']['website'] = array( $lang_module['website'], "<a href=\"" . $banner_client_info['website'] . "\" target=\"_blank\">" . $banner_client_info['website'] . "</a>" );
}
if( ! empty( $banner_client_info['location'] ) )
{
	$contents['rows']['location'] = array( $lang_module['location'], $banner_client_info['location'] );
}
if( ! empty( $banner_client_info['yim'] ) )
{
	$contents['rows']['yim'] = array( $lang_module['yim'], $banner_client_info['yim'] );
}
if( ! empty( $banner_client_info['phone'] ) )
{
	$contents['rows']['phone'] = array( $lang_global['phonenumber'], $banner_client_info['phone'] );
}
if( ! empty( $banner_client_info['fax'] ) )
{
	$contents['rows']['fax'] = array( $lang_module['fax'], $banner_client_info['fax'] );
}
if( ! empty( $banner_client_info['mobile'] ) )
{
	$contents['rows']['mobile'] = array( $lang_module['mobile'], $banner_client_info['mobile'] );
}
if( ! empty( $banner_client_info['last_login'] ) )
{
	$contents['rows']['last_login'] = array( $lang_global['last_login'], nv_date( "d/m/Y H:i", $banner_client_info['last_login'] ) . " (" . $lang_module['ip'] . ": " . $banner_client_info['last_ip'] . ")" );
}

$manament = array();
$manament['current_login'] = array( $lang_global['current_login'], nv_date( "d/m/Y H:i", $banner_client_info['current_login'] ) . " (" . $lang_module['ip'] . ": " . $banner_client_info['current_ip'] . ")" );
$manament['clientinfo_link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=clientinfo";
$manament['clientinfo_addads'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=addads";
$manament['clientinfo_stats'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=stats";

$contents = clinfo_theme( $contents, $manament );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';