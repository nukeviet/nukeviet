<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_USER' ) )
{
	redict_link( $lang_module['product_login_fail'], $lang_module['redirect_to_back_login'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_base64_encode( $client_info['selfurl'] ) );
}

$page_title = $lang_module['profile_manage_myproducts'];

$per_page = 20;
if( preg_match( "/^page\-([0-9]+)$/", ( isset( $array_op[1] ) ? $array_op[1] : "" ), $m ) )
{
	$page = ( int ) $m[1];
}

list( $all_page ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` AS t1, `" . $db_config['prefix'] . "_" . $module_data . "_units` AS t2 WHERE t1.product_unit = t2.id AND t1.user_id = " . $user_info['userid'] ) );

$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";

$sql = "SELECT `id`, `listcatid`, `publtime`, `exptime`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `homeimgthumb`, `product_price`, `status` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `user_id` = " . $user_info['userid'] . " ORDER BY `id` DESC LIMIT " . ( ( $page - 1 ) * $per_page ) . "," . $per_page;
$result = $db->sql_query( $sql );
$data_pro = array();

while( list( $id, $listcatid, $publtime, $exptime, $title, $alias, $homeimgthumb, $product_price, $status ) = $db->sql_fetchrow( $result ) )
{
	$thumb = explode( "|", $homeimgthumb );
	if( ! empty( $thumb[0] ) and ! nv_is_url( $thumb[0] ) )
	{
		$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
	}
	else
	{
		$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_name . "/no-image.jpg";
	}
	
	$data_pro[] = array(
		"id" => $id,
		"publtime" => $publtime,
		"exptime" => $exptime,
		"title" => $title,
		"alias" => $alias,
		"homeimgthumb" => $thumb[0],
		"product_price" => $product_price,
		"status" => $status,
		"link_pro" => $link . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id,
		"link_del" => $link . "delpro&id=" . $id,
		"link_edit" => $link . "post/" . $id
	);
}
	
if( empty( $data_pro ) and $page > 1 )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

$pages_pro = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
$contents = call_user_func( "my_product", $data_pro, $pages_pro, $page, $per_page );

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	$description .= ' ' . $page;
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>