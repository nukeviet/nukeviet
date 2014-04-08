<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:19
 */

if( ! defined( 'NV_IS_MOD_BANNERS' ) ) die( 'Stop!!!' );

$contents = array();
$contents['info'] = $lang_module['main_page_info'];
$contents['detail'] = $lang_global['detail'];

$sql = "SELECT * FROM " . NV_BANNERS_GLOBALTABLE. "_plans WHERE act=1 ORDER BY blang ASC";
$result = $db->query( $sql );
$contents['rows'] = array();

while( $row = $result->fetch() )
{
	$contents['rows'][$row['id']]['title'] = array( $row['title'] );
	$contents['rows'][$row['id']]['blang'] = array( $lang_module['blang'], ( ( ! empty( $row['blang'] ) ) ? $language_array[$row['blang']]['name'] : $lang_module['blang_all'] ) );
	$contents['rows'][$row['id']]['size'] = array( $lang_module['size'], $row['width'] . ' x ' . $row['height'] . 'px' );
	$contents['rows'][$row['id']]['form'] = array( $lang_module['form'], $row['form'] );
	$contents['rows'][$row['id']]['description'] = array( $lang_module['description'], $row['description'] );
}

$contents['containerid'] = "action";
$contents['aj'] = "nv_login_info('action');";
$contents['clientinfo_link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=clientinfo";
$contents['clientinfo_addads'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=addads";
$contents['clientinfo_stats'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=stats";

$page_title = $module_info['custom_title'] . " " . NV_TITLEBAR_DEFIS . " " . $module_info['funcs'][$op]['func_custom_name'];
$contents = nv_banner_theme_main( $contents );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';