<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( !defined( 'NV_IS_MOD_SHOPS' ) )
	die( 'Stop!!!' );

if( $nv_Request->isset_request( 'check_quantity', 'post' ) )
{
	$id_pro = $nv_Request->get_int( 'id_pro', 'post', 0 );
	$unit = $nv_Request->get_string( 'pro_unit', 'post', '' );
	$listid = $nv_Request->get_string( 'listid', 'post' );
	$listid = explode( ',', $listid );
	asort( $listid );

	$quantity = $db->query( 'SELECT quantity FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity WHERE pro_id = ' . $id_pro . ' AND listgroup="' . implode( ',', $listid ) . '"' )->fetchColumn();
	if( empty( $quantity ) )
	{
		$sum = 0;
		$count = 0;
		$listgroupid = GetGroupID( $id_pro, 1 );
		if( !empty( $listgroupid ) and !empty( $global_array_group ) )
		{
			foreach( $listgroupid as $gid => $subid )
			{
				$parent_info = $global_array_group[$gid];
				if( $parent_info['in_order'] )
				{
					$count++;
				}
			}
		}

		$result = $db->query( 'SELECT listgroup, quantity FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity WHERE pro_id = ' . $id_pro );
		while( list( $listgroup, $quantity ) = $result->fetch( 3 ) )
		{
			$listgroup = explode( ',', $listgroup );
			$_t = 0;
			foreach ($listgroup as $_idgroup)
			{
				if( in_array( $_idgroup, $listid ) )
				{
					$_t = $_t +  1;
				}
			}
			if( $_t == sizeof( $listid ) OR empty( $listid ) )
			{
				$sum += $quantity;
			}
		}

		if( $sum == 0 OR $count == sizeof( $listid ) )
		{
			die( 'NO_0_' . $lang_module['product_empty'] );
		}
		else
		{
			die( 'NO_0_' . $lang_module['detail_pro_number'] . ': ' . $sum . ' ' . $unit );
		}
	}
	else
	{
		die( 'OK_' . $quantity . '_' . $lang_module['detail_pro_number'] . ': ' . $quantity . ' ' . $unit );
	}
}

if( empty( $id ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit( );
}

$compare_id = $nv_Request->get_string( $module_data . '_compare_id', 'session', '' );
$compare_id = unserialize( $compare_id );

// Thiet lap quyen xem chi tiet
$contents = '';
$publtime = 0;

$sql = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id = ' . $id . ' AND status=1' );
$data_content = $sql->fetch( );
$data_shop = array( );

if( empty( $data_content ) )
{
	$nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
	redict_link( $lang_module['detail_do_not_view'], $lang_module['redirect_to_back_shops'], $nv_redirect );
}

$data_content['array_custom'] = array();
$data_content['array_custom_lang'] = array();
$data_content['template'] = '';
if( $global_array_shops_cat[$data_content['listcatid']]['form'] != '' )
{
	$idtemplate = $db->query( 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_template where alias = "' . preg_replace( "/[\_]/", "-", $global_array_shops_cat[$data_content['listcatid']]['form'] ) . '"' )->fetchColumn( );
	if( $idtemplate )
	{
		$listfield = array();
		$array_tmp = array( );
		$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_field ORDER BY weight' );
		while( $row = $result->fetch( ) )
		{
			$listtemplate = explode( '|', $row['listtemplate'] );
			if( in_array( $idtemplate, $listtemplate ) )
			{
				$listfield[] = $row['field'];
				$array_tmp[$row['field']] = unserialize( $row['language'] );
			}
		}

		if( !empty( $listfield ) )
		{
			$sql = $db->query( 'SELECT shopid, status, ' . implode( ',', $listfield ) . ' FROM ' . $db_config['prefix'] . "_" . $module_data . "_info_" . $idtemplate . ' WHERE shopid = ' . $id . ' AND status=1' );
			$data_content['template'] = $global_array_shops_cat[$data_content['listcatid']]['form'];
			$data_content['array_custom'] = $sql->fetch( );

			if( !empty( $array_tmp ) )
			{
				foreach( $array_tmp as $f_key => $field )
				{
					foreach( $field as $key_lang => $lang_data )
					{
						if( $key_lang == NV_LANG_DATA )
						{
							$data_content['array_custom_lang'][$f_key] = $lang_data[0];
						}
					}
				}
				unset( $array_tmp );
			}
		}
	}
}

$page_title = $data_content[NV_LANG_DATA . '_title'];
$description = $data_content[NV_LANG_DATA . '_hometext'];

if( nv_user_in_groups( $global_array_shops_cat[$catid]['groups_view'] ) )
{
	$popup = $nv_Request->get_int( 'popup', 'post,get', 0 );

	$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET hitstotal=hitstotal+1 WHERE id=' . $id;
	$db->query( $sql );

	$catid = $data_content['listcatid'];
	$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$catid]['alias'] . '/' . $data_content[NV_LANG_DATA . '_alias'] . '-' . $data_content['id'] . $global_config['rewrite_exturl'], true );

	if( $_SERVER['REQUEST_URI'] != $base_url_rewrite and !$popup )
	{
		Header( 'Location: ' . $base_url_rewrite );
		die( );
	}

	$sql = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_units WHERE id = ' . $data_content['product_unit'] );
	$data_unit = $sql->fetch( );
	$data_unit['title'] = $data_unit[NV_LANG_DATA . '_title'];

	// Lay chi tiet giam gia
	if( $data_content['discount_id'] )
	{
		$sql = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts WHERE did = ' . $data_content['discount_id'] );
		$data_shop['discount'] = $sql->fetch( );
	}

	// Danh gia - Phan hoi
	$rating_total = 0;
	$result = $db->query( 'SELECT rating FROM ' . $db_config['prefix'] . '_' . $module_data . '_review WHERE product_id = ' . $data_content['id'] . ' AND status=1' );
	$rating_count = $result->rowCount();
	if( $rating_count > 0 )
	{
		while( list( $rating ) = $result->fetch( 3 ) )
		{
			$rating_total += $rating;
		}
	}
	$data_content['rating_total'] = $rating_count;
	$data_content['rating_point'] = $rating_total;
	$data_content['rating_value'] = $rating_count > 0 ? round( $rating_total / $rating_count ) : 0;

	// Xac dinh anh lon
	$homeimgfile = $data_content['homeimgfile'];
	if( $data_content['homeimgthumb'] == 1 )//image thumb
	{
		$data_content['homeimgthumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $homeimgfile;
		$data_content['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfile;
	}
	elseif( $data_content['homeimgthumb'] == 2 )//image file
	{
		$data_content['homeimgthumb'] = $data_content['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfile;
	}
	elseif( $data_content['homeimgthumb'] == 3 )//image url
	{
		$data_content['homeimgthumb'] = $data_content['homeimgfile'] = $homeimgfile;
	}
	else//no image
	{
		$data_content['homeimgthumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
	}

	//metatag image facebook
	$meta_property['og:image'] = NV_MY_DOMAIN . $data_content['homeimgthumb'];

	// Fetch Limit
	$db->sqlreset( )->select( ' id, listcatid, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, homeimgfile, homeimgthumb, addtime, publtime, product_code, product_number, product_price, price_config, money_unit, discount_id, showprice, ' . NV_LANG_DATA . '_hometext,' . NV_LANG_DATA . '_gift_content, gift_from, gift_to' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' )->where( 'id!=' . $id . ' AND listcatid = ' . $data_content['listcatid'] . ' AND status=1' )->order( 'ID DESC' )->limit( $pro_config['per_row'] * 2 );
	$result = $db->query( $db->sql( ) );

	$data_others = array( );
	while( list( $_id, $listcatid, $title, $alias, $homeimgfile, $homeimgthumb, $addtime, $publtime, $product_code, $product_number, $product_price, $price_config, $money_unit, $discount_id, $showprice, $hometext, $gift_content, $gift_from, $gift_to ) = $result->fetch( 3 ) )
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

		$data_others[] = array(
			'id' => $_id,
			'listcatid' => $listcatid,
			'title' => $title,
			'alias' => $alias,
			'publtime' => $publtime,
			'homeimgthumb' => $thumb,
			'hometext' => $hometext,
			'addtime' => $addtime,
			'product_code' => $product_code,
			'product_number' => $product_number,
			'product_price' => $product_price,
			'price_config' => $price_config,
			'discount_id' => $discount_id,
			'money_unit' => $money_unit,
			'showprice' => $showprice,
			'newday' => $global_array_shops_cat[$data_content['listcatid']]['newday'],
			'gift_content' => $gift_content,
			'gift_from' => $gift_from,
			'gift_to' => $gift_to,
			'link_pro' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$data_content['listcatid']]['alias'] . '/' . $alias . '-' . $_id . $global_config['rewrite_exturl'],
			'link_order' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;id=' . $_id
		);
	}

	$array_other_view = array( );
	if( !empty( $_SESSION[$module_data . '_proview'] ) )
	{
		$arrid = array( );
		foreach( $_SESSION[$module_data . '_proview'] as $id_i => $data_i )
		{
			if( $id_i != $id )
			{
				$arrid[] = $id_i;
			}
		}
		$arrtempid = implode( ',', $arrid );
		if( !empty( $arrtempid ) )
		{
			// Fetch Limit
			$db->sqlreset( )->select( 'id, listcatid, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, homeimgfile, homeimgthumb, addtime, publtime, product_code, product_number, product_price, money_unit, discount_id, showprice, ' . NV_LANG_DATA . '_hometext,' . NV_LANG_DATA . '_gift_content, gift_from, gift_to' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' )->where( 'id IN ( ' . $arrtempid . ') AND status=1' )->order( 'id DESC' )->limit( $pro_config['per_row'] * 2 );
			$result = $db->query( $db->sql( ) );

			while( list( $_id, $listcatid, $title, $alias, $homeimgfile, $homeimgthumb, $addtime, $publtime, $product_code, $product_number, $product_price, $money_unit, $discount_id, $showprice, $hometext, $gift_content, $gift_from, $gift_to ) = $result->fetch( 3 ) )
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

				$array_other_view[] = array(
					'id' => $_id,
					'listcatid' => $listcatid,
					'title' => $title,
					'alias' => $alias,
					'publtime' => $publtime,
					'homeimgthumb' => $thumb,
					'hometext' => $hometext,
					'addtime' => $addtime,
					'product_code' => $product_code,
					'product_number' => $product_number,
					'product_price' => $product_price,
					'discount_id' => $discount_id,
					'money_unit' => $money_unit,
					'showprice' => $showprice,
					'newday' => $global_array_shops_cat[$data_content['listcatid']]['newday'],
					'gift_content' => $gift_content,
					'gift_from' => $gift_from,
					'gift_to' => $gift_to,
					'link_pro' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$data_content['listcatid']]['alias'] . '/' . $alias . '-' . $_id . $global_config['rewrite_exturl'],
					'link_order' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;id=' . $_id
				);
			}

		}
	}

	SetSessionProView( $data_content['id'], $data_content[NV_LANG_DATA . '_title'], $data_content[NV_LANG_DATA . '_alias'], $data_content['addtime'], NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$catid]['alias'] . '/' . $data_content[NV_LANG_DATA . '_alias'] . '-' . $data_content['id'], $data_content['homeimgthumb'] );

	// comment
	define( 'NV_COMM_ID', $data_content['id'] );
	define( 'NV_COMM_ALLOWED', $data_content['allowed_comm'] );

	// Kiểm tra quyền đăng bình luận
	$allowed = $module_config[$module_name]['allowed_comm'];
	if( $allowed == '-1' )
	{
		// Quyền hạn đăng bình luận theo bài viết
		$allowed = ( defined( 'NV_COMM_ALLOWED' ) ) ? NV_COMM_ALLOWED : $module_config[$module_name]['setcomm'];
	}
	define( 'NV_PER_PAGE_COMMENT', 5 );//per_page_comment
	require_once NV_ROOTDIR . '/modules/comment/comment.php';
    $area = ( defined( 'NV_COMM_AREA' ) ) ? NV_COMM_AREA : 0;
    $checkss = md5( $module_name . '-' . $area . '-' . NV_COMM_ID . '-' . $allowed . '-' . NV_CACHE_PREFIX );

    // Get url comment
    $url_info = parse_url( $client_info['selfurl'] );
    $url_comment = $url_info['path'];

	$content_comment = nv_comment_module( $module_name, $url_comment, $checkss, $area, NV_COMM_ID, $allowed, 1 );

	$contents = detail_product( $data_content, $data_unit, $data_shop, $data_others, $array_other_view, $content_comment, $compare_id, $popup );
}
else
{
	$nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
	redict_link( $lang_module['detail_no_permission'], $lang_module['redirect_to_back_shops'], $nv_redirect );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents, !$popup );
include NV_ROOTDIR . '/includes/footer.php';