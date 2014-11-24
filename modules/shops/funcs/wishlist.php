<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['wishlist_product'];

if( ! defined( 'NV_IS_USER' ) )
{
	$redirect = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true );
	Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_base64_encode( $redirect ) );
	die();
}

if( empty( $array_wishlist_id ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

if( preg_match( '/^page\-([0-9]+)$/', ( isset( $array_op[1] ) ? $array_op[1] : '' ), $m ) )
{
	$page = ( int )$m[1];
}

$data_content = array();
$array_wishlist_id = implode( ',', $array_wishlist_id ); 

// Fetch Limit
$db->sqlreset()->select( 'COUNT(*)' )->from( $db_config['prefix'] . '_' . $module_data . '_rows t1' )->where( 't1.inhome=1 AND t1.status =1 AND id IN (' . $array_wishlist_id . ')' );

$num_items = $db->query( $db->sql() )->fetchColumn();

$db->select( 't1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_code, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice, t2.newday' )
	->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_catalogs t2 ON t2.catid = t1.listcatid' )
	->limit( $per_page )
	->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgfile, $homeimgthumb, $product_code, $product_number, $product_price, $money_unit, $discount_id, $showprice, $newday ) = $result->fetch( 3 ) )
{
	if( $homeimgthumb == 1 )//image thumb
	{
		$thumb = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $homeimgfile;
	}
	elseif( $homeimgthumb == 2 )//image file
	{
		$thumb = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfile;
	}
	elseif( $homeimgthumb == 3 )//image url
	{
		$thumb = $homeimgfile;
	}
	else//no image
	{
		$thumb = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
	}

	$data_content[] = array(
		'id' => $id,
		'publtime' => $publtime,
		'title' => $title,
		'alias' => $alias,
		'hometext' => $hometext,
		'homeimgalt' => $homeimgalt,
		'homeimgthumb' => $thumb,
		'product_price' => $product_price,
		'product_code' => $product_code,
		'product_number' => $product_number,
		'discount_id' => $discount_id,
		'money_unit' => $money_unit,
		'showprice' => $showprice,
		'newday' => $newday,
		'link_pro' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$listcatid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
		'link_order' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;id=' . $id
	);
}

if( empty( $data_content ) and $page > 1 )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=wishlist';
$html_pages = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );

$contents = call_user_func( 'wishlist', $data_content, $html_pages );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';