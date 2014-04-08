<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get,post', 0 );

$result = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id = " . $id );
$data_content = $result->fetch();

if( empty( $data_content ) )
{
	include NV_ROOTDIR . '/includes/header.php';
	echo "Error Access!!!";
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

$catid = $data_content['listcatid'];

$result = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_units WHERE id = " . $data_content['product_unit'] );
$data_unit = $result->fetch();
$data_unit['title'] = $data_unit[NV_LANG_DATA . '_title'];

$homeimgfile = $data_content['homeimgfile'];
if( $data_content['homeimgthumb'] == 1 )//image thumb
{
	$data_content['homeimgthumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $homeimgfile;
	$data_content['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfile;
}
elseif( $data_content['homeimgthumb'] == 2 )//image file
{
	$data_content['homeimgthumb'] = $data_content['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfile;
}
elseif( $data_content['homeimgthumb'] == 3 )//image url
{
	$data_content['homeimgthumb'] = $data_content['homeimgfile'] = $homeimgfile;
}
else//no image
{
	$data_content['homeimgthumb'] = $data_content['homeimgfile'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_name . "/no-image.jpg";
}

$page_title = $data_content[NV_LANG_DATA . '_title'];

$contents = print_product( $data_content, $data_unit, $page_title );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';