<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_SHOPS', true );

require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

$arr_cat_title = array();
$catid = 0;
$parentid = 0;
$set_viewcat = "";
$alias_cat_url = isset( $array_op[0] ) ? $array_op[0] : "";
$alias_group_url = isset( $array_op[1] ) ? $array_op[1] : "";
$groupid = 0;

// Categories
$global_array_cat = array();
$sql = "SELECT `catid`, `parentid`, `lev`, `" . NV_LANG_DATA . "_title` AS `title`, `" . NV_LANG_DATA . "_alias` AS `alias`, `viewcat`, `numsubcat`, `subcatid`, `numlinks`, `" . NV_LANG_DATA . "_description` AS `description`, `inhome`, `" . NV_LANG_DATA . "_keywords` AS `keywords`, `who_view`, `groups_view` FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` ORDER BY `order` ASC";

$list = nv_db_cache( $sql, "catid", $module_name );
foreach( $list as $row )
{
	$global_array_cat[$row['catid']] = array(
		"catid" => $row['catid'],
		"parentid" => $row['parentid'],
		"title" => $row['title'],
		"alias" => $row['alias'],
		"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $row['alias'],
		"viewcat" => $row['viewcat'],
		"numsubcat" => $row['numsubcat'],
		"subcatid" => $row['subcatid'],
		"numlinks" => $row['numlinks'],
		"description" => $row['description'],
		"inhome" => $row['inhome'],
		"keywords" => $row['keywords'],
		"who_view" => $row['who_view'],
		"groups_view" => $row['groups_view'],
		'lev' => $row['lev']
	);
	
	if( $alias_cat_url == $row['alias'] )
	{
		$catid = $row['catid'];
		$parentid = $row['parentid'];
	}
}

// Groups
$global_array_group = array();

$sql = "SELECT `groupid`, `parentid`, `cateid`, `lev`, `" . NV_LANG_DATA . "_title` AS `title`, `" . NV_LANG_DATA . "_alias` AS `alias`, `viewgroup`, `numsubgroup`, `subgroupid`, `numlinks`, `" . NV_LANG_DATA . "_description` AS `description`, `inhome`, `" . NV_LANG_DATA . "_keywords` AS `keywords`, `who_view`, `groups_view`, `numpro` FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` ORDER BY `order` ASC";

$list = nv_db_cache( $sql, "", $module_name );
foreach( $list as $row )
{
	$global_array_group[$row['groupid']] = array(
		"groupid" => $row['groupid'],
		"parentid" => $row['parentid'],
		"cateid" => $row['cateid'],
		"title" => $row['title'],
		"alias" => $row['alias'],
		"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=group/" . $row['alias'],
		"viewgroup" => $row['viewgroup'],
		"numsubgroup" => $row['numsubgroup'],
		"subgroupid" => $row['subgroupid'],
		"numlinks" => $row['numlinks'],
		"description" => $row['description'],
		"inhome" => $row['inhome'],
		"keywords" => $row['keywords'],
		"who_view" => $row['who_view'],
		"groups_view" => $row['groups_view'],
		"lev" => $row['lev'],
		"numpro" => $row['numpro']
	);
	
	if( $alias_group_url == $row['alias'] )
	{
		$groupid = $row['groupid'];
	}
}
unset( $list, $alias_cat_url, $row, $alias_group_url );

$page = 1;
$per_page = $pro_config['per_page'];

if( $op == "main" )
{
	if( empty( $catid ) )
	{
		if( preg_match( "/^page\-([0-9]+)$/", ( isset( $array_op[0] ) ? $array_op[0] : "" ), $m ) )
		{
			$page = ( int ) $m[1];
		}
	}
	else
	{
		if( sizeof( $array_op ) == 2 and ! preg_match( "/^page\-([0-9]+)$/", $array_op[1], $m ) )
		{
			$alias_url = preg_replace( "/^(.*?)\-([0-9]+)$/", '${1}', $array_op[1] );
			$id = preg_replace( "/^(.*?)\-([0-9]+)$/", '${2}', $array_op[1] );
			
			$op = "detail";
		}
		else
		{
			if( preg_match( "/^page\-([0-9]+)$/", ( isset( $array_op[1] ) ? $array_op[1] : "" ), $m ) )
			{
				$page = ( int ) $m[1];
			}
			
			$op = "viewcat";
		}
		$parentid = $catid;
		while( $parentid > 0 )
		{
			$array_cat_i = $global_array_cat[$parentid];
			$array_mod_title[] = array(
				'catid' => $parentid,
				'title' => $array_cat_i['title'],
				'link' => $array_cat_i['link']
			);
			$parentid = $array_cat_i['parentid'];
		}
		sort( $array_mod_title, SORT_NUMERIC );
	}
}

/**
 * GetDataIn()
 * 
 * @param mixed $result
 * @param mixed $catid
 * @return
 */
function GetDataIn( $result, $catid )
{
	global $global_array_cat, $module_name, $db, $link, $module_info;
	$data_content = array();
	$data = array();
	while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_code, $product_price, $product_discounts, $money_unit, $showprice ) = $db->sql_fetchrow( $result ) )
	{
		$thumb = explode( "|", $homeimgthumb );
		if( ! empty( $thumb[0] ) && ! nv_is_url( $thumb[0] ) )
		{
			$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
		}
		else
		{
			$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_name . "/no-image.jpg";
		}
		$data[] = array(
			"id" => $id,
			"publtime" => $publtime,
			"title" => $title,
			"alias" => $alias,
			"hometext" => $hometext,
			"address" => $address,
			"homeimgalt" => $homeimgalt,
			"homeimgthumb" => $thumb[0],
			"product_code" => $product_code,
			"product_price" => $product_price,
			"product_discounts" => $product_discounts,
			"money_unit" => $money_unit,
			"showprice" => $showprice,
			"link_pro" => $link . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id,
			"link_order" => $link . "setcart&amp;id=" . $id
		);
	}
	
	$data_content['id'] = $catid;
	$data_content['title'] = $global_array_cat[$catid]['title'];
	$data_content['data'] = $data;
	$data_content['alias'] = $global_array_cat[$catid]['alias'];
	
	return $data_content;
}

/**
 * GetDataInGroup()
 * 
 * @param mixed $result
 * @param mixed $groupid
 * @return
 */
function GetDataInGroup( $result, $groupid )
{
	global $global_array_group, $module_name, $db, $link, $module_info, $global_array_cat;
	
	$data_content = array();
	$data = array();
	
	while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_code, $product_price, $product_discounts, $money_unit, $showprice ) = $db->sql_fetchrow( $result ) )
	{
		$thumb = explode( "|", $homeimgthumb );
		if( ! empty( $thumb[0] ) && ! nv_is_url( $thumb[0] ) )
		{
			$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
		}
		else
		{
			$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_name . "/no-image.jpg";
		}
		$data[] = array(
			"id" => $id,
			"publtime" => $publtime,
			"title" => $title,
			"alias" => $alias,
			"hometext" => $hometext,
			"address" => $address,
			"homeimgalt" => $homeimgalt,
			"homeimgthumb" => $thumb[0],
			"product_code" => $product_code,
			"product_price" => $product_price,
			"product_discounts" => $product_discounts,
			"money_unit" => $money_unit,
			"showprice" => $showprice,
			"link_pro" => $link . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id,
			"link_order" => $link . "setcart&amp;id=" . $id
		);
	}
	
	$data_content['id'] = $groupid;
	$data_content['title'] = $global_array_group[$groupid]['title'];
	$data_content['data'] = $data;
	$data_content['alias'] = $global_array_group[$groupid]['alias'];
	
	return $data_content;
}

/**
 * FormatNumber()
 * 
 * @param mixed $number
 * @param integer $decimals
 * @param string $thousand_separator
 * @param string $decimal_point
 * @return
 */
function FormatNumber( $number, $decimals = 0, $thousand_separator = '&nbsp;', $decimal_point = '.' )
{
	$str = number_format( $number, 0, ',', '.' );
	return $str;
}

//eg : echo CurrencyConversion ( 100000, 'USD', 'VND' );
/*return string money eg: 100 000 000*/
/**
 * CurrencyConversion()
 * 
 * @param mixed $price
 * @param mixed $currency_curent
 * @param mixed $currency_convert
 * @return
 */
function CurrencyConversion( $price, $currency_curent, $currency_convert )
{
	global $money_config, $pro_config;
	$str = number_format( $price, 0, '.', ' ' );
	if( ! empty( $money_config ) )
	{
		if( $currency_curent == $pro_config['money_unit'] )
		{
			$value = doubleval( $money_config[$currency_convert]['exchange'] );
			$price = doubleval( $price / $value );
			$str = number_format( $price, 0, '.', ' ' );
			$ss = "~";
		}
		elseif( $currency_convert == $pro_config['money_unit'] )
		{
			$value = doubleval( $money_config[$currency_curent]['exchange'] );
			$price = doubleval( $price * $value );
			$str = number_format( $price, 0, '.', ' ' );
		}
	}
	$ss = ( $currency_curent == $currency_convert ) ? "" : "~";
	return $ss . $str;
}

//eg : echo CurrencyConversion ( 100000, 'USD', 'VND' );
/*return double money eg: 100000000 */
/**
 * CurrencyConversionToNumber()
 * 
 * @param mixed $price
 * @param mixed $currency_curent
 * @param mixed $currency_convert
 * @return
 */
function CurrencyConversionToNumber( $price, $currency_curent, $currency_convert )
{
	global $money_config, $pro_config;
	if( ! empty( $money_config ) )
	{
		if( $currency_curent == $pro_config['money_unit'] )
		{
			$value = doubleval( $money_config[$currency_convert]['exchange'] );
			$price = doubleval( $price / $value );
		}
		elseif( $currency_convert == $pro_config['money_unit'] )
		{
			$value = doubleval( $money_config[$currency_curent]['exchange'] );
			$price = doubleval( $price * $value );
		}
	}
	return $price;
}

/**
 * SetSessionProView()
 * 
 * @param mixed $id
 * @param mixed $title
 * @param mixed $alias
 * @param mixed $addtime
 * @param mixed $link
 * @param mixed $homeimgthumb
 * @return
 */
function SetSessionProView( $id, $title, $alias, $addtime, $link, $homeimgthumb )
{
	global $module_data;
	if( ! isset( $_SESSION[$module_data . '_proview'] ) ) $_SESSION[$module_data . '_proview'] = array();
	if( ! isset( $_SESSION[$module_data . '_proview'][$id] ) )
	{
		$_SESSION[$module_data . '_proview'][$id] = array(
			'title' => $title,
			'alias' => $alias,
			'addtime' => $addtime,
			'link' => $link,
			'homeimgthumb' => $homeimgthumb
		);
	}
}

?>