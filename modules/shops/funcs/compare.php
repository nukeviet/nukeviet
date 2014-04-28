<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */
if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

if( $nv_Request->isset_request( 'compare', 'post' ) )
{

	$idss = $nv_Request->get_int( 'id', 'post', 0 );
	$array_id = $nv_Request->get_string( 'array_id', 'session', '' );
	$array_id = unserialize( $_SESSION['array_id'] );

	if( in_array( $idss, $array_id ) )
	{
		unset( $array_id[$idss] );
		$array_id = serialize( $array_id );
		$_SESSION['array_id'] = $array_id;
		nv_del_moduleCache( $module_name );
		die( 'OK' );
	}
	else
	{
		$array_id[$idss] = $idss;
		if( count( $array_id ) > 4 )
		{
			die( 'ERROR[NV3]' . $lang_module['numcompare'] . '[NV3]' . $idss );
		}
		else
		{
			$array_id = serialize( $array_id );
			$_SESSION['array_id'] = $array_id;
			nv_del_moduleCache( $module_name );
			die( 'OK' );
		}
	}
}
if( $nv_Request->isset_request( 'compareresult', 'post' ) )
{
	$array_id = $nv_Request->get_string( 'array_id', 'session', '' );
	$array_id = unserialize( $_SESSION['array_id'] );
	if( count( $array_id ) < 2 )
	{
		die( $lang_module['num0'] );
	}
	else
	{
		die( 'OK' );
	}
}
$xtpl = new XTemplate( 'compare.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'module_name', $module_file );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$array_id = $nv_Request->get_string( 'array_id', 'session', '' );
$array_id = unserialize( $_SESSION['array_id'] );
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

if( ! empty( $array_id ) )
{
	foreach( $array_id as $array_id_i )
	{
		$sql = 'SELECT id, listcatid, publtime, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_hometext, ' . NV_LANG_DATA . '_address,homeimgfile, homeimgalt, homeimgthumb, product_code, product_price, product_discounts, money_unit, showprice,
 ' . NV_LANG_DATA . '_warranty,' . NV_LANG_DATA . '_promotional as promotional,' . NV_LANG_DATA . '_note as note, source_id,' . NV_LANG_DATA . '_bodytext FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id = ' . $array_id_i;
		$result = $db->query( $sql );
		while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $address, $homeimgfile, $homeimgalt, $homeimgthumb, $product_code, $product_price, $product_discounts, $money_unit, $showprice, $warranty, $promotional, $note, $source_id, $bodytext ) = $result->fetch( 3 ) )
		{
			// Xac dinh anh lon
			$homeimgfiles1 = $homeimgfile;
			if( $homeimgthumb == 1 )//image thumb
			{
				$homeimgthumbs = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $homeimgfiles1;
				$homeimgthumbs = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfiles1;
			}
			elseif( $homeimgthumb == 2 )//image file
			{
				$homeimgthumbs = $homeimgfiles1 = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfiles1;
			}
			elseif( $homeimgthumb == 3 )//image url
			{
				$homeimgthumbs = $homeimgfile = $homeimgfiles1;
			}
			else//no image
			{
				$homeimgthumbs = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
			}

			$data_pro[] = array(
				'id' => $id,
				'publtime' => $publtime,
				'title' => $title,
				'alias' => $alias,
				'hometext' => $hometext,
				'address' => $address,
				'homeimgalt' => $homeimgalt,
				'homeimgthumb' => $homeimgthumbs,
				'product_code' => $product_code,
				'product_price' => $product_price,
				'product_discounts' => $product_discounts,
				'money_unit' => $money_unit,
				'showprice' => $showprice,
				'link_pro' => $link . $global_array_cat[$listcatid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
				'link_order' => $link . 'setcart&amp;id=' . $id,
				'source_id' => $source_id,
				'note' => $note,
				'warranty' => $warranty,
				'promotional' => $promotional,
				'bodytext' => $bodytext
			);

		}
	}
	foreach( $data_pro as $data_row )
	{
		$xtpl->assign( 'id', $data_row['id'] );
		$xtpl->assign( 'title_pro', $data_row['title'] );
		$xtpl->assign( 'title_pro0', nv_clean60( $data_row['title'], 25 ) );
		$xtpl->assign( 'link_pro', $data_row['link_pro'] );
		$xtpl->assign( 'img_pro', $data_row['homeimgthumb'] );
		$xtpl->assign( 'link_order', $data_row['link_order'] );
		$xtpl->assign( 'intro', $data_row['hometext'] );
		$xtpl->assign( 'DETAIL', $data_row['bodytext'] );

		$xtpl->assign( 'PRODUCT_CODE', $data_row['product_code'] );
		if( $pro_config['active_price'] == '1' )
		{
			if( $data_row['showprice'] == '1' )
			{
				$xtpl->assign( 'product_price', CurrencyConversion( $data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit'] ) );
				$xtpl->assign( 'money_unit', $pro_config['money_unit'] );
				if( $data_row['product_discounts'] != 0 )
				{
					$price_product_discounts = $data_row['product_price'] - ( $data_row['product_price'] * ( $data_row['product_discounts'] / 100 ) );
					$xtpl->assign( 'product_discounts', CurrencyConversion( $price_product_discounts, $data_row['money_unit'], $pro_config['money_unit'] ) );
					$xtpl->assign( 'class_money', 'discounts_money' );
					if( $pro_config['active_price'] == '1' ) $xtpl->parse( 'main.grid_rows.price.discounts' );
				}
				else
				{
					$xtpl->assign( 'class_money', 'money' );
				}
				$xtpl->parse( 'main.grid_rows.price' );
			}
			else
			{
				$xtpl->parse( 'main.grid_rows.contact' );
			}
		}
		$xtpl->assign( 'height', $pro_config['homeheight'] );
		$xtpl->assign( 'width', $pro_config['homewidth'] );
		if( $pro_config['active_order'] == '1' )
		{
			if( $data_row['showprice'] == '1' )
			{
				$xtpl->parse( 'main.grid_rows.order' );
			}
		}
		if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.grid_rows.tooltip' );
		if( ! empty( $pro_config['show_product_code'] ) and ! empty( $data_row['product_code'] ) )
		{
			$xtpl->parse( 'main.grid_rows.product_code' );
		}
		$sql = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_sources WHERE sourceid = ' . $data_row['source_id'] );
		$data_temp = $sql->fetch();
		$data_row['source'] = $data_temp[NV_LANG_DATA . '_title'];
		if( ! empty( $data_row['source'] ) )
		{
			$xtpl->assign( 'source', $data_row['source'] );
			$xtpl->assign( 'link_source', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=search_result&amp;sid=' . $data_row['source_id'] );
			$xtpl->parse( 'main.source' );
		}

		if( ! empty( $data_row['promotional'] ) )
		{
			$xtpl->assign( 'promotional', $data_row['promotional'] );
			$xtpl->parse( 'main.grid_rows.promotional' );
		}
		if( ! empty( $data_row['warranty'] ) )
		{
			$xtpl->assign( 'warranty', $data_row['warranty'] );
			$xtpl->parse( 'main.grid_rows.warranty' );
		}
		if( ! empty( $data_row['address'] ) )
		{
			$xtpl->assign( 'address', $data_row['address'] );
			$xtpl->parse( 'main.grid_rows.address' );
		}
		if( ! empty( $data_row['note'] ) )
		{
			$xtpl->assign( 'note', $data_row['note'] );
			$xtpl->parse( 'main.grid_rows.note' );
		}
		$sql = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_sources WHERE sourceid = ' . $data_row['source_id'] );
		$data_temp = $sql->fetch();
		$data_row['source'] = $data_temp[NV_LANG_DATA . '_title'];

		if( ! empty( $data_row['source'] ) )
		{
			$xtpl->assign( 'source', $data_row['source'] );
			$xtpl->assign( 'link_source', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=search_result&amp;sid=' . $data_row['source_id'] );
			$xtpl->parse( 'main.grid_rows.source' );
		}
		$xtpl->parse( 'main.grid_rows' );
		$_SESSION['array_id'] = '';
	}

}
else
{
	Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';