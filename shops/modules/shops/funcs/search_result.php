<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$bid = 1; // block host
$num = $pro_config['per_page'];
$data_content = array();

$keyword = $nv_Request->get_string( 'keyword', 'get' );
$price1_temp = $nv_Request->get_string( 'price1', 'get', '' );
$price2_temp = $nv_Request->get_string( 'price2', 'get', '' );
$typemoney = $nv_Request->get_string( 'typemoney', 'get', '' );
$cataid = $nv_Request->get_int( 'cata', 'get', 0 );
$sid = $nv_Request->get_int( 'sid', 'get', 0 );
$page = $nv_Request->get_int( 'page', 'get', 0 );
$all_page = 0;
if ( $price1_temp == '' ) $price1 = - 1;
else $price1 = floatval( $price1_temp );
if ( $price2_temp == '' ) $price2 = - 1;
else $price2 = floatval( $price2_temp );

// Set data form search
$xtpl = new XTemplate( "search_all.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
foreach ( $global_array_cat as $row )
{
	$xtitle_i = "";
	if ( $row['lev'] > 0 )
	{
		$xtitle_i .= "&nbsp;&nbsp;&nbsp;";
		for ( $i = 1; $i <= $row['lev']; $i ++ )
		{
			$xtitle_i .= "&nbsp;&nbsp;&nbsp;";
		}
		$xtitle_i .= "&nbsp;";
	}
	$row['xtitle'] = $xtitle_i . $row['title'];
	$row['selected'] = ( $cataid == $row['catid'] ) ? "selected=\"selected\"" : "";
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'form.loopcata' );
}

// Get money
$sql = "SELECT `code`,`currency` FROM `" . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . "`";
$result = $db->sql_query( $sql );

while ( $row = $db->sql_fetchrow( $result, 2 ) )
{
	$row['selected'] = ( $typemoney == $row['code'] ) ? "selected=\"selected\"" : "";
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'form.typemoney' );
}

// Get sources
$sql = "SELECT " . NV_LANG_DATA . "_title as title, sourceid FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources`";
$result = $db->sql_query( $sql );

while ( $row = $db->sql_fetchrow( $result, 2 ) )
{
	$row['selected'] = ( $row['sourceid'] == $sid ) ? "selected=\"selected\"" : "";
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'form.loopsource' );
}

if ( $price1 == -1 ) $price1_temp = "";
if ( $price2 == -1 ) $price2_temp = "";

$xtpl->assign( 'value_keyword', $keyword );
$xtpl->assign( 'value_price1', $price1_temp );
$xtpl->assign( 'value_price2', $price2_temp );

if ( $pro_config['active_price']) $xtpl->parse( 'form.price' );

$xtpl->parse( 'form' );
$contents = $xtpl->text( 'form' );

$search = "";
if ( $keyword != "" )
{
	$search = " AND (`" . NV_LANG_DATA . "_title` LIKE '%" . $db->dblikeescape( $keyword ) . "%' OR `product_code` LIKE '%" . $db->dblikeescape( $keyword ) . "%')";
}
if ( ( $price1 >= 0 and $price2 > 0 ) )
{
	$search .= " AND product_price-(product_discounts/100)*product_price BETWEEN " . $price1 . " AND " . $price2 . " ";

}
elseif ( $price2 == -1 and $price1 >= 0 )
{
	$search .= " AND product_price-(product_discounts/100)*product_price >= " . $price1 . " ";
}
elseif ( $price1 == -1 and $price2 > 0 )
{
	$search .= " AND product_price-(product_discounts/100)*product_price >= " . $price2 . " ";
}
if ( !empty( $typemoney ) )
{
	$search .= " AND `money_unit` = " . $db->dbescape( $typemoney ) . "";
}
if ( $cataid != 0 )
{
	$array_cat = GetCatidInParent( $cataid );
	$search .= " AND `listcatid` IN (" . implode( ",", $array_cat ).") ";
}
if ( $sid != 0 )
{
	$search .= " AND `source_id` =" . $sid . " ";
}
if ( empty( $search ) )
{
	$contents = "<div align =\"center\">" . $lang_module['notresult'] . "</div>";
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

$show_price = "";
if ( $pro_config['active_price'] )
{
	if( ! empty( $price1_temp ) or ! empty( $price2_temp ) ) $show_price = "AND `showprice`=1";
}

$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `publtime`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_hometext`, `" . NV_LANG_DATA . "_address`, `homeimgalt`, `homeimgthumb`, `product_price`, `product_discounts`, `money_unit`, `showprice` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `status`=1 " . $search . " " . $show_price . " ORDER BY `id` DESC LIMIT " . $page . "," . $per_page;

$result = $db->sql_query( $sql );
list( $all_page ) = $db->sql_fetchrow( $db->sql_query( "SELECT FOUND_ROWS()" ) );

$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=search_result&keyword=" . $keyword . "&price1=" . $price1 . "&price2=" . $price2 . "&typemoney=" . $typemoney . "&cata=" . $cataid;
$html_pages = nv_generate_page( $base_url, $all_page, $per_page, $page );

$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";

while ( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_price, $product_discounts, $money_unit, $showprice ) = $db->sql_fetchrow( $result ) )
{
	$thumb = explode( "|", $homeimgthumb );
	if ( ! empty( $thumb[0] ) and ! nv_is_url( $thumb[0] ) )
	{
		$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
	}
	else
	{
		$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/no-image.jpg";
	}
	
	$data_content[] = array( 
		"id" => $id,
		"publtime" => $publtime,
		"title" => $title,
		"alias" => $alias,
		"hometext" => $hometext,
		"address" => $address,
		"homeimgalt" => $homeimgalt,
		"homeimgthumb" => $thumb[0],
		"product_price" => $product_price,
		"product_discounts" => $product_discounts,
		"money_unit" => $money_unit,
		"showprice" => $showprice,
		"link_pro" => $link . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id,
		"link_order" => $link . "setcart&amp;id=" . $id 
	);
}

if ( count( $data_content ) == 0 )
{
	$contents .= "<div align =\"center\">" . $lang_module['notresult'] . "</div>";
}
else
{
	$contents .= view_search_all( $data_content, $html_pages );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>