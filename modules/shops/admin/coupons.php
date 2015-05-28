<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 04 Jan 2015 08:16:04 GMT
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

if( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
	$id = $nv_Request->get_int( 'delete_id', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons WHERE id = ' . $db->quote( $id ) );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die( );
	}
}

if( $nv_Request->isset_request( 'get_product', 'get' ) )
{
	$q = $nv_Request->get_title( 'term', 'get', '', 1 );
	if( empty( $q ) )
		return;

	$db->sqlreset( )->select( 'id, ' . NV_LANG_DATA . '_title' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' )->where( NV_LANG_DATA . '_title LIKE :title OR ' . NV_LANG_DATA . '_alias LIKE :alias' )->order( NV_LANG_DATA . '_title ASC' )->limit( 50 );

	$sth = $db->prepare( $db->sql( ) );
	$sth->bindValue( ':alias', '%' . $q . '%', PDO::PARAM_STR );
	$sth->bindValue( ':title', '%' . $q . '%', PDO::PARAM_STR );
	$sth->execute( );

	$array_data = array( );
	while( list( $id, $title ) = $sth->fetch( 3 ) )
	{
		$array_data[] = array(
			'key' => $id,
			'value' => $title
		);
	}

	header( 'Cache-Control: no-cache, must-revalidate' );
	header( 'Content-type: application/json' );

	ob_start( 'ob_gzhandler' );
	echo json_encode( $array_data );
	exit( );
}

$row = $product_old = array( );
$error = array( );
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );

if( $row['id'] > 0 )
{
	$result = $db->query( 'SELECT pid FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons_product WHERE cid=' . $row['id'] );
	while( list( $pid ) = $result->fetch( 3 ) )
	{
		$product_old[] = $pid;
	}
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$row['code'] = $nv_Request->get_title( 'code', 'post', '' );
	$row['type'] = $nv_Request->get_title( 'type', 'post', 'p' );
	$row['discount'] = $nv_Request->get_title( 'discount', 'post', '' );
	$row['total_amount'] = $nv_Request->get_title( 'total_amount', 'post', '' );
	$row['product'] = $nv_Request->get_array( 'product', 'post', '' );
	$row['product'] = array_diff( $row['product'], array('') );
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'date_start', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['date_start'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['date_start'] = 0;
	}
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'date_end', 'post' ), $m ) )
	{
		$_hour = 23;
		$_min = 59;
		$_sec = 59;
		$row['date_end'] = mktime( $_hour, $_min, $_sec, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['date_end'] = 0;
	}
	$row['uses_per_coupon'] = $nv_Request->get_int( 'uses_per_coupon', 'post', 0 );

	if( empty( $row['title'] ) )
	{
		$error[] = $lang_module['coupons_error_required_title'];
	}
	elseif( empty( $row['code'] ) )
	{
		$error[] = $lang_module['coupons_error_required_code'];
	}
	elseif( !preg_match( '/^\w+$/', $row['code'] ) )
	{
		$error[] = $lang_module['coupons_error_vail_code'];
	}
	elseif( empty( $row['discount'] ) )
	{
		$error[] = $lang_module['coupons_error_required_discount'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{
				$sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_coupons (title, code, type, discount, total_amount, date_start, date_end, uses_per_coupon, date_added, status) VALUES (:title, :code, :type, :discount, :total_amount, :date_start, :date_end, :uses_per_coupon, ' . NV_CURRENTTIME . ', 1)';
				$data_insert = array( );
				$data_insert['title'] = $row['title'];
				$data_insert['code'] = $row['code'];
				$data_insert['type'] = $row['type'];
				$data_insert['discount'] = $row['discount'];
				$data_insert['total_amount'] = $row['total_amount'];
				$data_insert['date_start'] = $row['date_start'];
				$data_insert['date_end'] = $row['date_end'];
				$data_insert['uses_per_coupon'] = $row['uses_per_coupon'];
				$insert_id = $db->insert_id( $sql, 'id', $data_insert );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_coupons SET title = :title, code = :code, type = :type, discount = :discount, total_amount = :total_amount, date_start = :date_start, date_end = :date_end, uses_per_coupon = :uses_per_coupon WHERE id=' . $row['id'] );
				$stmt->bindParam( ':title', $row['title'], PDO::PARAM_STR );
				$stmt->bindParam( ':code', $row['code'], PDO::PARAM_STR );
				$stmt->bindParam( ':type', $row['type'], PDO::PARAM_STR );
				$stmt->bindParam( ':discount', $row['discount'], PDO::PARAM_STR );
				$stmt->bindParam( ':total_amount', $row['total_amount'], PDO::PARAM_STR );
				$stmt->bindParam( ':date_start', $row['date_start'], PDO::PARAM_INT );
				$stmt->bindParam( ':date_end', $row['date_end'], PDO::PARAM_INT );
				$stmt->bindParam( ':uses_per_coupon', $row['uses_per_coupon'], PDO::PARAM_INT );
				$exc = $stmt->execute( );
			}

			if( $exc or $insert_id > 0 )
			{
				// Them san pham vao bang product
				if( empty( $row['id'] ) and !empty( $row['product'] ) )
				{
					foreach( $row['product'] as $pid )
					{
						$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_coupons_product VALUES( ' . $insert_id . ', ' . $pid . ')' );
					}
				}
				else
				{
					if( $product_old != $row['product'] )
					{
						$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons_product WHERE cid=' . $row['id'] );
						foreach( $row['product'] as $pid )
						{
							if( !empty( $pid ) )
							{
								$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_coupons_product VALUES(' . $row['id'] . ', ' . $pid . ')' );
							}
						}
					}
				}
				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
				die( );
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage( ) );
			die( $e->getMessage( ) );
			//Remove this line after checks finished
		}
	}
}
elseif( $row['id'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons WHERE id=' . $row['id'] )->fetch( );
	$row['product'] = $product_old;
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die( );
	}
}
else
{
	$row['id'] = 0;
	$row['title'] = '';
	$row['code'] = '';
	$row['type'] = 'p';
	$row['discount'] = '';
	$row['total_amount'] = '';
	$row['product'] = '';
	$row['date_start'] = NV_CURRENTTIME;
	$row['date_end'] = 0;
	$row['uses_per_coupon'] = '';
}

if( empty( $row['date_start'] ) )
{
	$row['date_start'] = '';
}
else
{
	$row['date_start'] = date( 'd/m/Y', $row['date_start'] );
}

if( empty( $row['date_end'] ) )
{
	$row['date_end'] = '';
}
else
{
	$row['date_end'] = date( 'd/m/Y', $row['date_end'] );
}

$q = $nv_Request->get_title( 'q', 'post,get' );

// Fetch Limit
$show_view = false;
if( !$nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 5;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset( )->select( 'COUNT(*)' )->from( $db_config['prefix'] . '_' . $module_data . '_coupons' );

	if( !empty( $q ) )
	{
		$db->where( 'title LIKE :q_title OR code LIKE :q_code OR discount LIKE :q_discount OR date_start LIKE :q_date_start OR date_end LIKE :q_date_end OR status LIKE :q_status' );
	}
	$sth = $db->prepare( $db->sql( ) );

	if( !empty( $q ) )
	{
		$sth->bindValue( ':q_title', '%' . $q . '%' );
		$sth->bindValue( ':q_code', '%' . $q . '%' );
		$sth->bindValue( ':q_discount', '%' . $q . '%' );
		$sth->bindValue( ':q_date_start', '%' . $q . '%' );
		$sth->bindValue( ':q_date_end', '%' . $q . '%' );
		$sth->bindValue( ':q_status', '%' . $q . '%' );
	}
	$sth->execute( );
	$num_items = $sth->fetchColumn( );

	$db->select( '*' )->order( 'id DESC' )->limit( $per_page )->offset( ($page - 1) * $per_page );
	$sth = $db->prepare( $db->sql( ) );

	if( !empty( $q ) )
	{
		$sth->bindValue( ':q_title', '%' . $q . '%' );
		$sth->bindValue( ':q_code', '%' . $q . '%' );
		$sth->bindValue( ':q_discount', '%' . $q . '%' );
		$sth->bindValue( ':q_date_start', '%' . $q . '%' );
		$sth->bindValue( ':q_date_end', '%' . $q . '%' );
		$sth->bindValue( ':q_status', '%' . $q . '%' );
	}
	$sth->execute( );
}

$row['uses_per_coupon'] = !empty( $row['uses_per_coupon'] ) ? $row['uses_per_coupon'] : '';
$row['total_amount'] = !empty( $row['total_amount'] ) ? $row['total_amount'] : '';

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'Q', $q );

if( $show_view )
{
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
	if( !empty( $q ) )
	{
		$base_url .= '&q=' . $q;
	}
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.view.generate_page' );
	}

	while( $view = $sth->fetch( ) )
	{
		if( NV_CURRENTTIME >= $view['date_start'] and ( empty( $view['uses_per_coupon'] ) or $view['uses_per_coupon_count'] < $view['uses_per_coupon'] ) and ( empty( $view['date_end'] ) or NV_CURRENTTIME < $view['date_end'] ) )
		{
			$view['status'] = $lang_module['coupons_active'];
		}
		else
		{
			$view['status'] = $lang_module['coupons_inactive'];
		}
		$view['discount_text'] = $view['type'] == 'p' ? '%' : ' ' . $pro_config['money_unit'];
		$view['date_start'] = ( empty( $view['date_start'] )) ? '' : nv_date( 'd/m/Y', $view['date_start'] );
		$view['date_end'] = ( empty( $view['date_end'] )) ? '' : nv_date( 'd/m/Y', $view['date_end'] );
		$view['link_view'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=coupons_view&amp;id=' . $view['id'];
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}

if( !empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

$array_select_type = array(
	'p' => $lang_module['coupons_type_percentage'],
	'f' => $lang_module['coupons_type_fixed_amount']
);
foreach( $array_select_type as $key => $title )
{
	$xtpl->assign( 'OPTION', array(
		'key' => $key,
		'title' => $title,
		'selected' => ($key == $row['type']) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.select_type' );
}

if( !empty( $row['product'] ) )
{
	$array_pro = array( );
	$result = $db->query( 'SELECT id, ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id IN (' . implode( ',', $row['product'] ) . ')' );
	while( list( $id, $title ) = $result->fetch( 3 ) )
	{
		$array_pro[$id] = array(
			'id' => $id,
			'title' => $title
		);
	}

	foreach( $row['product'] as $pid )
	{
		$xtpl->assign( 'PRODUCT', array(
			'id' => $pid,
			'title' => $array_pro[$pid]['title']
		) );
		$xtpl->parse( 'main.product' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['coupons'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
