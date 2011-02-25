<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['content_main'];

$link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "";
$array_info = array();

// tong so luong san pham
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` where `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ")"));
$array_info [] = array("title" => $lang_module['product_number_all'], "value" => $number, "link" => $link."=items" , "unit" => $lang_module['product_unit'] ); 

// tong so luong san pham chua duyet
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` where `status`= 0 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ")"));
$array_info [] = array("title" => $lang_module['product_number_all_noctive'], "value" => $number, "link" => $link."=items" , "unit" => $lang_module['product_unit'] );

// tong so luong binh luan
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_comments_".NV_LANG_DATA."` "));
$array_info [] = array("title" => $lang_module['product_number_commet'], "value" => $number, "link" => $link."=comment" , "unit" => $lang_module['product_comment'] );

// tong so luong so luong san pham trong kho
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT SUM(product_number) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` "));
$array_info [] = array("title" => $lang_module['product_number_all_store'], "value" => $number, "link" => $link."=items" , "unit" => $lang_module['product_unit'] );

// tong so luong don dat hang
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_orders` "));
$array_info [] = array("title" => $lang_module['product_number_order'], "value" => $number, "link" => $link."=order" , "unit" => $lang_module['product_order'] );

// tong so luong don dat hang moi
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_orders` WHERE `view` = 0 "));
$array_info [] = array("title" => $lang_module['product_number_order_new'], "value" => $number, "link" => $link."=order" , "unit" => $lang_module['product_order'] );

// tong so luong don dat hang nhung chua duyet
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_orders` WHERE `transaction_status` = -1 "));
$array_info [] = array("title" => $lang_module['product_number_order_no_active'], "value" => $number, "link" => $link."=order" , "unit" => $lang_module['product_order'] );

// tong so luong don dat hang chua thanh toan
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_orders` WHERE `transaction_status` = 0 "));
$array_info [] = array("title" => $lang_module['product_number_order_no_payment'], "value" => $number, "link" => $link."=order" , "unit" => $lang_module['product_order'] );

// tong so luong don dat hang da thanh toan
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_orders` WHERE `transaction_status` = 4 "));
$array_info [] = array("title" => $lang_module['product_number_order_payment'], "value" => $number, "link" => $link."=order" , "unit" => $lang_module['product_order'] );

// tong so luong don dat hang da gui thanh toan
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_orders` WHERE `transaction_status` = 1 "));
$array_info [] = array("title" => $lang_module['product_number_order_send_payment'], "value" => $number, "link" => $link."=order" , "unit" => $lang_module['product_order'] );

// tong so luong don dat hang da gui thanh toan
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $module_data . "_orders` WHERE `transaction_status` = 5 "));
$array_info [] = array("title" => $lang_module['product_number_order_dis_payment'], "value" => $number, "link" => $link."=order" , "unit" => $lang_module['product_order'] );

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
//////////////////////////////////////////////////////
$xtpl->assign("LANG",$lang_module );
$xtpl->assign("NV_BASE_SITEURL",NV_BASE_SITEURL );
$xtpl->assign("module",$module_info['custom_title']." (" .$module_name. ")"  );
$xtpl->assign("module_version","1.0.0 : 20-01-2011");
/////////////////////////////////////////////////////////////
$i = 0;
foreach ( $array_info as $info)
{
	$xtpl->assign("KEY",$info);
	$bg = ($i % 2 == 0) ? "class=\"second\"" : "" ;
	$xtpl->assign("bg",$bg);
	$xtpl->parse( 'main.loop' );
	$i++;
}
$info = array("sitename" => $global_config ['site_name'], "url" =>$global_config ['site_url'] );
$data = array(
	"language" => "vi",
	"nv" => "support",
	"op" => "regis",
	"urlpost" => "http://vinades.vn/",
	"key" => md5($global_config['sitekey']),
	"info" => base64_encode( serialize( $info ) ),
	"module" => base64_encode( $module_file ),
	"checkss" => md5 (base64_encode( serialize( $info )).base64_encode( $module_file ).md5($global_config['sitekey']))
);
$xtpl->assign("DATA",$data);
/////////////////////////////////////////////////////////////
if ( defined( 'NV_IS_GODADMIN' ) ) $xtpl->parse( 'main.modinfo' );
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>