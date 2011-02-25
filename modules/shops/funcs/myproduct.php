<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if (! defined ( 'NV_IS_MOD_SHOPS' ))
	die ( 'Stop!!!' );
	
if (! defined ( 'NV_IS_USER' )) {
	$nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login";
	redict_link($lang_module ['product_login_fail'],$lang_module['redirect_to_back_login'],$nv_redirect);
}
$per_page = 20;
$page = 0;

$temp_pages = isset ($array_op[1]) ? $array_op[1] : "";
if(!empty($temp_pages)){
	$array_page = explode('-',$temp_pages);
	$page = intval( end( $array_page ) );
}

list ( $numf ) = $db->sql_fetchrow ( $db->sql_query ( "SELECT COUNT(*) FROM `" . $db_config ['prefix'] . "_" . $module_data . "_rows` as t1, `" . $db_config['prefix'] . "_" . $module_data . "_units` as t2 WHERE t1.product_unit = t2.id AND t1.user_id = " . $user_info ['userid'] . " AND t1.`status`=1 AND t1.`publtime` < " . NV_CURRENTTIME . " AND (t1.`exptime`=0 OR t1.`exptime`>" . NV_CURRENTTIME . ") " ) );
$all_page = ($numf) ? $numf : 1;
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=myproduct";
$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";

$sql = "SELECT id, listcatid,publtime,exptime, ". NV_LANG_DATA . "_title, ". NV_LANG_DATA . "_alias, homeimgthumb, product_price, status FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE user_id = " . $user_info ['userid'] . " ORDER BY ID DESC LIMIT " . $page . "," . $per_page . "";
$result = $db->sql_query( $sql );
$data_pro = array();
while ( list( $id,$listcatid, $publtime, $exptime,$title, $alias,$homeimgthumb ,$product_price, $status ) = $db->sql_fetchrow( $result )){
	$thumb = explode("|",$homeimgthumb);
    if (!empty($thumb[0]) && ! nv_is_url( $thumb[0] ))
    {
    $thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
    }
    else
    {
    $thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info ['template'] . "/images/" . $module_name . "/no-image.jpg";
    }
	$data_pro[] = array("id"=>$id,"publtime" => $publtime, "exptime" => $exptime,"title" => $title, "alias" =>$alias,"homeimgthumb" => $thumb[0] ,"product_price" => $product_price ,"status" =>$status,"link_pro" => $link.$global_array_cat[$listcatid]['alias'] ."/" . $alias."-".$id,"link_del" => $link."delpro&id=" . $id,"link_edit" => $link."post/" . $id);
}	

$pages_pro = nv_products_page ( $base_url, $all_page, $per_page, $page );

$contents = call_user_func ( "my_product", $data_pro, $pages_pro,$page );

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme ( $contents );
include (NV_ROOTDIR . "/includes/footer.php");

?>