<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if( empty( $id ) or empty( $alias_url ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

// Thiet lap quyen xem chi tiet
$contents = "";
$publtime = 0;
$func_who_view = $global_array_cat[$catid]['who_view'];
$allowed = false;
if( $func_who_view == 0 )
{
	$allowed = true;
}
if( $func_who_view == 1 and defined( 'NV_IS_USER' ) )
{
	$allowed = true;
}
elseif( $func_who_view == 2 and defined( 'NV_IS_MODADMIN' ) )
{
	$allowed = true;
}
elseif( $func_who_view == 3 and ( ( defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $global_array_cat[$catid]['groups_view'] ) ) or defined( 'NV_IS_MODADMIN' ) ) )
{
	$allowed = true;
}

$sql = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id` = " . $id . " AND `status`=1" );
$data_content = $db->sql_fetchrow( $sql, 2 );
$data_shop = array();

if ( empty( $data_content ) )
{
    $nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
    redict_link( $lang_module['detail_do_not_view'], $lang_module['redirect_to_back_shops'], $nv_redirect );
}

$page_title = $data_content[NV_LANG_DATA . '_title'];
$key_words = $data_content[NV_LANG_DATA . '_keywords'];
$description = $data_content[NV_LANG_DATA . '_hometext'];

if( $allowed )
{
	$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_rows` SET `hitstotal`=`hitstotal`+1 WHERE `id`=" . $id;
	$db->sql_query( $sql );

	$catid = $data_content['listcatid'];

	$sql = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_units` WHERE `id` = " . $data_content['product_unit'] );
	$data_unit = $db->sql_fetchrow( $sql );
	$data_unit['title'] = $data_unit[NV_LANG_DATA . '_title'];

	$array_img = explode( "|", $data_content['homeimgthumb'] );
	if ( ! empty( $array_img[0] ) and ! nv_is_url( $array_img[0] ) )
	{
		$array_img[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $array_img[0];
	}
	else
	{
		$array_img[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/no-image.jpg";
	}
	$data_content['homeimgthumb'] = $array_img[0];

	// Xac dinh anh lon
	if( nv_is_url( $data_content['homeimgfile'] ) )
	{
		$data_content['homeimgfile'] = $data_content['homeimgfile'];
	}
	elseif( $data_content['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data_content['homeimgfile'] ) )
	{
		$data_content['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data_content['homeimgfile'];
	}
	else
	{
		$data_content['homeimgfile'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/no-image.jpg";
	}
	
	$data_content['comment'] = "";
	$allow_comment = 0;
	
	if( nv_set_allow( $pro_config['who_comment'], $pro_config['groups_comment'] ) and ! empty( $pro_config['comment'] ) and ( ( $data_content['allowed_comm'] == 1 ) or ( $data_content['allowed_comm'] == 2 and defined( 'NV_IS_USER' ) ) ) )
	{
		$data_comment = nv_comment_module( $data_content['id'], 0 );
		$data_content['comment'] = comment_theme( $data_comment );
		$allow_comment = 1;
	}
	elseif( $data_content['allowed_comm'] == 2 and ( $pro_config['who_comment'] == 1 or $pro_config['who_comment'] == 0 ) and ! defined( 'NV_IS_USER' ) )
	{
		$allow_comment = 2;
	}

	$sql = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources` WHERE `sourceid` = " . $data_content['source_id'] );
	$data_temp = $db->sql_fetchrow( $sql );
	$data_content['source'] = $data_temp[NV_LANG_DATA . '_title'];

	$sql = "SELECT `id`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `homeimgthumb`, `addtime`, `product_code`, `product_price`, `product_discounts`, `money_unit`, `showprice`, `" . NV_LANG_DATA . "_hometext` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE id!=" . $id . " AND `listcatid` = " . $data_content['listcatid'] . " AND `status`=1 ORDER BY ID DESC LIMIT " . ( $pro_config['per_row'] * 2 );
	$result = $db->sql_query( $sql );

	$data_others = array();
	while ( list( $_id, $title, $alias, $homeimgthumb, $addtime, $product_code, $product_price, $product_discounts, $money_unit, $showprice, $hometext ) = $db->sql_fetchrow( $result ) )
	{
		$thumb = explode( "|", $homeimgthumb );
		if ( ! empty( $thumb[0] ) and ! nv_is_url( $thumb[0] ) )
		{
			$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
		}
		else
		{
			$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_name . "/no-image.jpg";
		}
		
		$data_others[] = array( 
			"id" => $_id,
			"title" => $title,
			"alias" => $alias,
			"homeimgthumb" => $thumb[0],
			"hometext" => $hometext,
			"addtime" => $addtime,
			"product_code" => $product_code,
			"product_price" => $product_price,
			"product_discounts" => $product_discounts,
			"money_unit" => $money_unit,
			"showprice" => $showprice,
			"link_pro" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$data_content['listcatid']]['alias'] . "/" . $alias . "-" . $_id,
			"link_order" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setcart&amp;id=" . $_id 
		);
	}

	$array_other_view = array();
	if ( ! empty( $_SESSION[$module_data . '_proview'] ) )
	{
		$arrid = array();
		foreach ( $_SESSION[$module_data . '_proview'] as $id_i => $data_i )
		{
			if ( $id_i != $id )
			{
				$arrid[] = $id_i;
			}
		}
		$arrtempid = implode( ",", $arrid );
		
		$sql = "SELECT `id`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `homeimgthumb`, `addtime`, `product_code`, `product_price`, `product_discounts`, `money_unit`, `showprice`, `" . NV_LANG_DATA . "_hometext` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id` IN ( " . $arrtempid . ") AND `status`=1 ORDER BY `id` DESC LIMIT ".( $pro_config['per_row'] * 2 );
		$result = $db->sql_query( $sql );
		
		while ( list( $_id, $title, $alias, $homeimgthumb, $addtime, $product_code, $product_price, $product_discounts, $money_unit, $showprice,$hometext ) = $db->sql_fetchrow( $result ) )
		{
			$thumb = explode( "|", $homeimgthumb );
			if ( ! empty( $thumb[0] ) and ! nv_is_url( $thumb[0] ) )
			{
				$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
			}
			else
			{
				$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_name . "/no-image.jpg";
			}
			
			$array_other_view[] = array( 
				"id" => $_id,
				"title" => $title,
				"alias" => $alias,
				"homeimgthumb" => $thumb[0],
				"hometext" => $hometext,
				"addtime" => $addtime,
				"product_code" => $product_code,
				"product_price" => $product_price,
				"product_discounts" => $product_discounts,
				"money_unit" => $money_unit,
				"showprice" => $showprice,
				"link_pro" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$data_content['listcatid']]['alias'] . "/" . $alias . "-" . $_id,
				"link_order" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setcart&amp;id=" . $_id 
			);
		}
	}

	SetSessionProView( $data_content['id'], $data_content[NV_LANG_DATA . '_title'], $data_content[NV_LANG_DATA . '_alias'], $data_content['addtime'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid]['alias'] . "/" . $data_content[NV_LANG_DATA . '_alias'] . "-" . $data_content['id'], $data_content['homeimgthumb'] );

	$contents = detail_product( $data_content, $data_unit, $allow_comment, $data_others, $data_shop, $array_other_view );
}
else
{
    $nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
    redict_link( $lang_module['detail_no_permission'], $lang_module['redirect_to_back_shops'], $nv_redirect );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>