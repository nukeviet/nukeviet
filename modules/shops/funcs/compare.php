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
$array_id = unserialize( $_SESSION[$module_name . '_array_id'] );

if( $nv_Request->isset_request( 'compare', 'post' ) )
{
	$idss = $nv_Request->get_int( 'id', 'post', 0 );
	$array_id = $nv_Request->get_string( 'array_id', 'session', '' );
	$array_id = unserialize( $_SESSION[$module_name . '_array_id'] );

	if( in_array( $idss, $array_id ) )
	{
		unset( $array_id[$idss] );
		$array_id = serialize( $array_id );
		$_SESSION[$module_name . '_array_id'] = $array_id;
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
			$_SESSION[$module_name . '_array_id'] = $array_id;
			nv_del_moduleCache( $module_name );
			die( 'OK' );
		}
	}
}

if( $nv_Request->isset_request( 'compareresult', 'post' ) )
{
	$array_id = $nv_Request->get_string( 'array_id', 'session', '' );
	$array_id = unserialize( $_SESSION[$module_name . '_array_id'] );

	if( count( $array_id ) < 2 )
	{
		die( $lang_module['num0'] );
	}
	else
	{
		die( 'OK' );
	}
}

if( $nv_Request->isset_request( 'compare_del', 'post' ) and $nv_Request->isset_request( 'id', 'post' ) and $nv_Request->isset_request( 'all', 'post' ) )
{
	$action = $nv_Request->get_int( 'all', 'post', 0 );
	$array_id = unserialize( $_SESSION[$module_name . '_array_id'] );
	
	if( $action )
	{
		unset( $array_id );
		unset( $_SESSION[$module_name . '_array_id'] );
	}
	else
	{
		$rm_id = $nv_Request->get_int( 'id', 'post', 0 );
		unset( $array_id[$rm_id] );
		$array_id = serialize( $array_id );
		$_SESSION[$module_name . '_array_id'] = $array_id;
	}
	nv_del_moduleCache( $module_name );
	die('OK');
}

$compare_url_rewrite = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=compare';
$compare_url_rewrite = nv_url_rewrite( $compare_url_rewrite, true );
if( $_SERVER['REQUEST_URI'] != $compare_url_rewrite )
{
	header( 'Location:' . $compare_url_rewrite );
	die();
}

$array_id = $nv_Request->get_string( 'array_id', 'session', '' );
$array_id = unserialize( $_SESSION[$module_name . '_array_id'] );
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

if( ! empty( $array_id ) )
{
	foreach( $array_id as $array_id_i )
	{
		$sql = 'SELECT id, listcatid, publtime, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_hometext, homeimgfile, homeimgalt, homeimgthumb, product_code, product_number, product_price, money_unit, discount_id, showprice, ' . NV_LANG_DATA . '_warranty,' . NV_LANG_DATA . '_promotional as promotional, ' . NV_LANG_DATA . '_bodytext, custom, ' . NV_LANG_DATA . '_custom FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id = ' . $array_id_i;
		$result = $db->query( $sql );
		while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgfile, $homeimgalt, $homeimgthumb, $product_code, $product_number, $product_price, $money_unit, $discount_id, $showprice, $warranty, $promotional, $bodytext, $custom, $custom_lang ) = $result->fetch( 3 ) )
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
				'homeimgalt' => $homeimgalt,
				'homeimgthumb' => $homeimgthumbs,
				'product_code' => $product_code,
				'product_number' => $product_number,
				'product_price' => $product_price,
				'discount_id' => $discount_id,
				'money_unit' => $money_unit,
				'showprice' => $showprice,
				'link_pro' => $link . $global_array_cat[$listcatid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
				'link_order' => $link . 'setcart&amp;id=' . $id,
				'warranty' => $warranty,
				'promotional' => $promotional,
				'bodytext' => $bodytext,
				'custom' => $custom,
				NV_LANG_DATA . '_custom' => $custom_lang
			);
		}
	}
}
else
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

$contents = compare( $data_pro );
//$_SESSION['array_id'] = '';

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';