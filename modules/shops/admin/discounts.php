<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 10 Jun 2014 02:22:18 GMT
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$config_discount = array();
if( $nv_Request->isset_request( 'ajax_action', 'post' ) )
{
	$did = $nv_Request->get_int( 'did', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $did;
	if( $new_vid > 0 )
	{
		$sql = 'SELECT did FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts WHERE did!=' . $did . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_discounts SET weight=' . $weight . ' WHERE did=' . $row['did'];
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_discounts SET weight=' . $new_vid . ' WHERE did=' . $did;
		$db->query( $sql );
		$content = 'OK_' . $did;
	}
	nv_del_moduleCache( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}
if( $nv_Request->isset_request( 'delete_did', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
	$did = $nv_Request->get_int( 'delete_did', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $did > 0 and $delete_checkss == md5( $did . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$weight = 0;
		$sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts WHERE did =' . $db->quote( $did );
		$result = $db->query( $sql );
		list( $weight ) = $result->fetch( 3 );

		$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts  WHERE did = ' . $db->quote( $did ) );
		if( $weight > 0 )
		{
			$sql = 'SELECT did, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while( list( $did, $weight ) = $result->fetch( 3 ) )
			{
				$weight--;
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_discounts SET weight=' . $weight . ' WHERE did=' . intval( $did ) );
			}
		}
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}

$row = array();
$error = array();
$row['did'] = $nv_Request->get_int( 'did', 'post,get', 0 );
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['title'] = $nv_Request->get_title( 'title', 'post', '' );
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'begin_time', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['begin_time'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['begin_time'] = 0;
	}
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'end_time', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['end_time'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['end_time'] = 0;
	}

	$config = $nv_Request->get_array( 'config', 'post' );
	$sortArray = array();
	foreach( $config as $config_i )
	{
		$sortArray['discount_from'][] = intval( $config_i['discount_from'] );
		$sortArray['discount_to'][] = intval( $config_i['discount_to'] );
		$sortArray['discount_number'][] = floatval( $config_i['discount_number'] );
	}
	array_multisort( $sortArray['discount_from'], SORT_ASC, $config );

	foreach( $config as $key => $config_i )
	{
		$config_i['discount_from'] = intval( $config_i['discount_from'] );
		$config_i['discount_to'] = intval( $config_i['discount_to'] );
		$config_i['discount_number'] = floatval( $config_i['discount_number'] );
		if( $config_i['discount_from'] > 0 and $config_i['discount_to'] >= $config_i['discount_from'] and $config_i['discount_number'] >= 0 and $config_i['discount_number'] <= 100 )
		{
			$config_discount[] = $config_i;
		}
	}
	$row['config'] = serialize( $config_discount );

	if( empty( $row['title'] ) )
	{
		$error[] = $lang_module['error_required_title'];
	}
	elseif( empty( $row['begin_time'] ) )
	{
		$error[] = $lang_module['error_required_begin_time'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['did'] ) )
			{

				$row['add_time'] = NV_CURRENTTIME;
				$row['edit_time'] = NV_CURRENTTIME;

				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_discounts (title, weight, add_time, edit_time, begin_time, end_time, config) VALUES (:title, :weight, :add_time, :edit_time, :begin_time, :end_time, :config)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

				$stmt->bindParam( ':add_time', $row['add_time'], PDO::PARAM_INT );
				$stmt->bindParam( ':edit_time', $row['edit_time'], PDO::PARAM_INT );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_discounts SET title = :title, edit_time = ' . NV_CURRENTTIME . ', begin_time = :begin_time, end_time = :end_time, config = :config WHERE did=' . $row['did'] );
			}
			$stmt->bindParam( ':title', $row['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':begin_time', $row['begin_time'], PDO::PARAM_INT );
			$stmt->bindParam( ':end_time', $row['end_time'], PDO::PARAM_INT );
			$stmt->bindParam( ':config', $row['config'], PDO::PARAM_STR, strlen( $row['config'] ) );

			$exc = $stmt->execute();
			if( $exc )
			{
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
				die();
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
		}
	}
}
elseif( $row['did'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts WHERE did=' . $row['did'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	if( !empty( $row['config'] ) )
	{
		$config_discount = unserialize( $row['config'] );
	}
}
else
{
	$row['did'] = 0;
	$row['title'] = '';
	$row['begin_time'] = NV_CURRENTTIME;
	$row['end_time'] = 0;
	$row['config'] = '';
	$config_discount[0] = array(
		'discount_from' => '1',
		'discount_to' => '',
		'discount_number' => ''
	);
}

if( empty( $row['begin_time'] ) )
{
	$row['begin_time'] = '';
}
else
{
	$row['begin_time'] = date( 'd/m/Y', $row['begin_time'] );
}

if( empty( $row['end_time'] ) )
{
	$row['end_time'] = '';
}
else
{
	$row['end_time'] = date( 'd/m/Y', $row['end_time'] );
}

// Fetch Limit
$show_view = false;
if( !$nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 5;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()->select( 'COUNT(*)' )->from( '' . $db_config['prefix'] . '_' . $module_data . '_discounts' );
	$sth = $db->prepare( $db->sql() );
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( '*' )->order( 'weight ASC' )->limit( $per_page )->offset( ($page - 1) * $per_page );
	$sth = $db->prepare( $db->sql() );
	$sth->execute();
}

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
$xtpl->assign( 'CAPTION', ($row['did']) ? $lang_module['discount_edit'] : $lang_module['discount_add'] );

if( $show_view )
{
	while( $view = $sth->fetch() )
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
		$view['begin_time'] = ( empty( $view['begin_time'] )) ? '' : nv_date( 'd/m/Y', $view['begin_time'] );
		$view['end_time'] = ( empty( $view['end_time'] )) ? '' : nv_date( 'd/m/Y', $view['end_time'] );
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;did=' . $view['did'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_did=' . $view['did'] . '&amp;delete_checkss=' . md5( $view['did'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$xtpl->assign( 'VIEW', $view );

		if( !empty( $view['config'] ) )
		{
			$config_discount_i = unserialize( $view['config'] );
			foreach( $config_discount_i as $discount )
			{
				$xtpl->assign( 'DISCOUNT', $discount );
				$xtpl->parse( 'main.view.loop.discount' );
			}
		}

		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}

$config_discount[] = array(
	'discount_from' => '',
	'discount_to' => '',
	'discount_number' => ''
);
$i = 0;
foreach( $config_discount as $config )
{
	$config['id'] = $i;
	$xtpl->assign( 'CONFIG', $config );
	$xtpl->parse( 'main.config' );
	++$i;
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['discounts'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';