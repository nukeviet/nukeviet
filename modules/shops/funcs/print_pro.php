<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int ( 'id', 'get,post', 0 );

$result = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id` = " . $id );
$data_content = $db->sql_fetchrow( $result );

if( empty( $data_content ) )
{
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo "Error Access!!!";
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

$catid = $data_content['listcatid'];

$result = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_units` WHERE `id` = " .$data_content['product_unit'] );
$data_unit = $db->sql_fetchrow( $result ); 
$data_unit['title'] = $data_unit[NV_LANG_DATA . '_title'];

$array_img = explode( "|", $data_content['homeimgthumb'] );
if( ! empty( $array_img[0] ) and ! nv_is_url( $array_img[0] ) )
{
	$data_content['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" .$data_content['homeimgfile'];
	$array_img[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $array_img[0];
}
else
{
	$data_content['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/thumb/no_image.jpg";
	$array_img[0] = NV_BASE_SITEURL . "themes/" . $module_info ['template'] . "/images/" . $module_name . "/no-image.jpg";
}
$data_content['homeimgthumb'] = $array_img[0];

$page_title = $data_content [NV_LANG_DATA . '_title'];

$contents = print_product( $data_content, $data_unit, $page_title );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>