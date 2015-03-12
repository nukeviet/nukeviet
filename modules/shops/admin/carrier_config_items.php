<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 16 Jan 2015 02:23:16 GMT
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$page_title = $lang_module['carrier_config_config'];

$config_weight = array( );
$cid = $nv_Request->get_int( 'cid', 'post,get', 0 );

if( $nv_Request->isset_request( 'ajax_action', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $id;
	if( $new_vid > 0 )
	{
		$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items WHERE id!=' . $id . ' and cid=' . $cid . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch( ) )
		{
			++$weight;
			if( $weight == $new_vid )
				++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items SET weight=' . $weight . ' WHERE id=' . $row['id'] . ' AND cid=' . $cid;
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items SET weight=' . $new_vid . ' WHERE id=' . $id . ' AND cid=' . $cid;
		$db->query( $sql );
		$content = 'OK_' . $id;
	}
	nv_del_moduleCache( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit( );
}

if( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
	$id = $nv_Request->get_int( 'delete_id', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$weight = 0;
		$sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items WHERE id =' . $db->quote( $id );
		$result = $db->query( $sql );
		list( $weight ) = $result->fetch( 3 );

		$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items  WHERE id = ' . $db->quote( $id ) );

		// Xoa bang carrier_config_location
		$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_location WHERE iid = ' . $id );

		// Xoa bang carrier_config_weight
		$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_weight WHERE iid = ' . $id );

		if( $weight > 0 )
		{
			$sql = 'SELECT id, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while( list( $id, $weight ) = $result->fetch( 3 ) )
			{
				$weight--;
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items SET weight=' . $weight . ' WHERE id=' . intval( $id ) );
			}
		}
		nv_del_moduleCache( $module_name );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=carrier_config_items&cid=' . $cid );
		die( );
	}
}

$row = array( );
$config_location_old = array( );
$error = array( );
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
$row['cid'] = $nv_Request->get_int( 'cid', 'post,get', 0 );

if( empty( $row['cid'] ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=carrier_config' );
	die( );
}

if( $row['id'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items WHERE id=' . $row['id'] )->fetch( );
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die( );
	}

	$row['config_weight'] = array( );
	$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_weight WHERE iid=' . $row['id'] . ' ORDER BY weight' );
	if( $result )
	{
		while( $weight = $result->fetch( ) )
		{
			$row['config_weight'][] = $weight;
		}
	}

	$row['config_location'] = array( );
	$result = $db->query( 'SELECT lid FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_location WHERE iid=' . $row['id'] );
	if( $result )
	{
		while( $location = $result->fetch( ) )
		{
			$row['config_location'][] = $location['lid'];
		}
	}
	$config_location_old = $row['config_location'];
}
else
{
	$row['id'] = 0;
	$row['title'] = '';
	$row['config_weight'][] = array(
		'weight' => '',
		'weight_unit' => $pro_config['weight_unit'],
		'carrier_price' => '',
		'carrier_price_unit' => $pro_config['money_unit']
	);
	$row['config_location'] = array( );
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$row['cid'] = $nv_Request->get_int( 'cid', 'post', 0 );
	$row['description'] = $nv_Request->get_textarea( 'description', 'post' );
	$row['config_location'] = $nv_Request->get_array( 'config_location', 'post', array( ) );
	$row['config_weight'] = $nv_Request->get_array( 'config_weight', 'post', array( ) );

	foreach( $row['config_weight'] as $key => $array )
	{
		if( empty( $array['weight'] ) or empty( $array['carrier_price'] ) )
		{
			unset( $row['config_weight'][$key] );
		}
	}

	foreach( $row['config_weight'] as $config_i )
	{
		$sortArray['weight'][] = $config_i['weight'];
		$sortArray['weight_unit'][] = $config_i['weight_unit'];
		$sortArray['carrier_price'][] = floatval( $config_i['carrier_price'] );
		$sortArray['carrier_price_unit'][] = $config_i['carrier_price_unit'];
	}
	array_multisort( $sortArray['weight'], empty( $row['id'] ) ? SORT_ASC : SORT_DESC, $row['config_weight'] );

	if( empty( $row['title'] ) )
	{
		$error[] = $lang_module['carrier_config_error_required_name'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{
				$sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items (cid, title, description, weight, add_time) VALUES (:cid, :title, :description, :weight, ' . NV_CURRENTTIME . ' )';

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items WHERE cid=' . $row['cid'] )->fetchColumn( );
				$weight = intval( $weight ) + 1;

				$data_insert = array( );
				$data_insert['cid'] = $row['cid'];
				$data_insert['title'] = $row['title'];
				$data_insert['description'] = $row['description'];
				$data_insert['weight'] = $weight;
				$insert_id = $db->insert_id( $sql, 'id', $data_insert );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items SET cid = :cid, title = :title, description = :description WHERE id=' . $row['id'] );
				$stmt->bindParam( ':title', $row['title'], PDO::PARAM_STR );
				$stmt->bindParam( ':cid', $row['cid'], PDO::PARAM_STR );
				$stmt->bindParam( ':description', $row['description'], PDO::PARAM_STR );

				$exc = $stmt->execute( );
			}

			if( $exc or $insert_id )
			{
				// Cap nhat cau hinh khoi luong
				if( !empty( $row['config_weight'] ) )
				{
					if( !empty( $row['id'] ) )
					{
						$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_weight WHERE iid=' . $row['id'] );
					}
					foreach( $row['config_weight'] as $config_weight )
					{
						$config_weight['carrier_price'] = floatval( preg_replace( '/[^0-9\.]/', '', $config_weight['carrier_price'] ) );
						if( empty( $row['id'] ) )
						{
							$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_weight (iid, weight, weight_unit, carrier_price, carrier_price_unit) VALUES (' . $insert_id . ', ' . $config_weight['weight'] . ', ' . $db->quote( $config_weight['weight_unit'] ) . ', ' . $config_weight['carrier_price'] . ', ' . $db->quote( $config_weight['carrier_price_unit'] ) . ')' );
						}
						else
						{
							$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_weight (iid, weight, weight_unit, carrier_price, carrier_price_unit) VALUES (' . $row['id'] . ', ' . $config_weight['weight'] . ', ' . $db->quote( $config_weight['weight_unit'] ) . ', ' . $config_weight['carrier_price'] . ', ' . $db->quote( $config_weight['carrier_price_unit'] ) . ')' );
						}
					}
				}

				// Cap nhat dia diem
				if( $row['config_location'] != $config_location_old )
				{
					if( !empty( $row['id'] ) )
					{
						$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_location WHERE iid=' . $row['id'] );
					}
					foreach( $row['config_location'] as $config_location_id )
					{
						if( empty( $row['id'] ) )
						{
							$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_location ( cid, iid, lid ) VALUES ( ' . $row['cid'] . ' , ' . $insert_id . ', ' . $config_location_id . ')' );
						}
						else
						{
							$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_location ( cid, iid, lid ) VALUES ( ' . $row['cid'] . ', ' . $row['id'] . ', ' . $config_location_id . ')' );
						}
					}
				}

				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&cid=' . $row['cid'] );
				die( );
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage( ) );
		}
	}
}

// Fetch Limit
$show_view = false;
if( !$nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset( )->select( 'COUNT(*)' )->from( '' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items' )->where( 'cid=' . $row['cid'] );
	$sth = $db->prepare( $db->sql( ) );
	$sth->execute( );
	$num_items = $sth->fetchColumn( );

	$db->select( '*' )->order( 'weight ASC' )->limit( $per_page )->offset( ($page - 1) * $per_page );
	$sth = $db->prepare( $db->sql( ) );
	$sth->execute( );
}

// Lay dia diem
if( !empty( $row['id'] ) )
{
	$sql = "SELECT id, title, lev FROM " . $db_config['prefix'] . '_' . $module_data . "_location WHERE id NOT IN ( SELECT lid FROM " . $db_config['prefix'] . "_" . $module_data . "_carrier_config_location WHERE cid = " . $row['cid'] . " ) OR id IN ( SELECT lid FROM " . $db_config['prefix'] . '_' . $module_data . "_carrier_config_location WHERE iid = " . $row['id'] . " ) ORDER BY sort ASC";
}
else
{
	$sql = "SELECT id, title, lev FROM " . $db_config['prefix'] . '_' . $module_data . "_location WHERE id NOT IN ( SELECT lid FROM " . $db_config['prefix'] . "_" . $module_data . "_carrier_config_location WHERE cid = " . $row['cid'] . " ) ORDER BY sort ASC";
}
$result = $db->query( $sql );
$array_location_list = array( );
while( list( $id_i, $title_i, $lev_i ) = $result->fetch( 3 ) )
{
	$xtitle_i = '';
	if( $lev_i > 0 )
	{
		$xtitle_i .= '&nbsp;';
		for( $i = 1; $i <= $lev_i; $i++ )
		{
			$xtitle_i .= '&nbsp;&nbsp;&nbsp;';
		}
	}
	$xtitle_i .= $title_i;
	$array_location_list[] = array(
		$id_i,
		$xtitle_i
	);
}

// Lay danh sach cau hinh
$_sql = 'SELECT id, title FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config ORDER BY id DESC';
$_query = $db->query( $_sql );
$array_config_list = array( );
while( $lconfig = $_query->fetch( ) )
{
	$array_config_list[$lconfig['id']] = $lconfig;
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'CAPTION', ($row['id']) ? $lang_module['carrier_config_items_edit'] : $lang_module['carrier_config_items_add'] );

$xtpl->assign( 'LOCALTION_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=location' );
$xtpl->assign( 'CARRIER_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=carrier' );
$xtpl->assign( 'CONFIG_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=carrier_config' );
$xtpl->assign( 'SHOPS_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=shops' );

if( $show_view )
{
	while( $view = $sth->fetch( ) )
	{
		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array(
				'key' => $i,
				'title' => $i,
				'selected' => ($i == $view['weight']) ? ' selected="selected"' : ''
			) );
			$xtpl->parse( 'main.view.loop.weight_loop' );
		}
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'] . '&amp;cid=' . $row['cid'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;cid=' . $row['cid'] . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$xtpl->assign( 'VIEW', $view );

		if( !empty( $view['config'] ) )
		{
			$config_weight_i = unserialize( $view['config'] );
			foreach( $config_weight_i as $carrier )
			{
				if( $carrier['carrier_unit'] == 'p' )
				{
					$carrier['carrier_unit'] = '%';
				}
				else
				{
					$carrier['carrier_number'] = nv_number_format( $carrier['carrier_number'], nv_get_decimals( $pro_config['money_unit'] ) );
					$carrier['carrier_unit'] = ' ' . $pro_config['money_unit'];
				}
				$xtpl->assign( 'carrier', $carrier );
				$xtpl->parse( 'main.view.loop.carrier' );
			}
		}

		$xtpl->parse( 'main.view.loop' );
	}

	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&cid=' . $row['cid'];
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'PAGE', $generate_page );
		$xtpl->parse( 'main.view.generate_page' );
	}

	$xtpl->parse( 'main.view' );
}

if( !empty( $row['cid'] ) )
{
	$page_title = sprintf( $lang_module['carrier_config_config_item'], $array_config_list[$row['cid']]['title'] );
}

$row['config_weight'][] = array(
	'weight' => '',
	'weight_unit' => $pro_config['weight_unit'],
	'carrier_price' => '',
	'carrier_price_unit' => $pro_config['money_unit']
);

$i = 0;
foreach( $row['config_weight'] as $config )
{
	$config['id'] = $i;
	$config['carrier_price'] = !empty( $config['carrier_price'] ) ? number_format( $config['carrier_price'], nv_get_decimals( $config['carrier_price_unit'] ), '.', ',' ) : '';
	$xtpl->assign( 'CONFIG', $config );

	if( !empty( $weight_config ) )
	{
		foreach( $weight_config as $key => $unit )
		{
			$xtpl->assign( 'UNIT', array(
				'key' => $key,
				'value' => $unit,
				'selected' => ($config['weight_unit'] == $key) ? 'selected="selected"' : ''
			) );
			$xtpl->parse( 'main.config.weight_unit' );
		}
	}

	if( !empty( $money_config ) )
	{
		foreach( $money_config as $code => $info )
		{
			$info['select'] = ($config['carrier_price_unit'] == $code) ? "selected=\"selected\"" : "";
			$xtpl->assign( 'MON', $info );
			$xtpl->parse( 'main.config.money_unit' );
		}
	}

	$xtpl->parse( 'main.config' );
	++$i;
}
$xtpl->assign( 'config_weight_count', $i );

if( !empty( $money_config ) )
{
	foreach( $money_config as $code => $info )
	{
		$info['select'] = ($pro_config['money_unit'] == $code) ? "selected=\"selected\"" : "";
		$xtpl->assign( 'MON', $info );
		$xtpl->parse( 'main.money_unit' );
	}
}

if( !empty( $weight_config ) )
{
	foreach( $weight_config as $key => $unit )
	{
		$xtpl->assign( 'UNIT', array(
			'key' => $key,
			'value' => $unit,
			'selected' => ($pro_config['weight_unit'] == $key) ? 'selected="selected"' : ''
		) );
		$xtpl->parse( 'main.weight_unit' );
	}
}

foreach( $array_location_list as $rows_i )
{
	$sl = ( in_array( $rows_i[0], $row['config_location'] )) ? ' selected="selected"' : '';
	$xtpl->assign( 'plocal_i', $rows_i[0] );
	$xtpl->assign( 'ptitle_i', $rows_i[1] );
	$xtpl->assign( 'pselect', $sl );
	$xtpl->parse( 'main.parent_loop' );
}

if( !empty( $array_config_list ) )
{
	foreach( $array_config_list as $key => $config_list )
	{
		$xtpl->assign( 'CONFIG', array(
			'key' => $config_list['id'],
			'value' => $config_list['title'],
			'selected' => ($row['cid'] == $key) ? 'selected="selected"' : ''
		) );
		$xtpl->parse( 'main.config_list' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';