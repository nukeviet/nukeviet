<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 16 Jan 2015 02:23:16 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'ajax_action', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $id;
	if( $new_vid > 0 )
	{
		$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops WHERE id!=' . $id . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_shops SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_shops SET weight=' . $new_vid . ' WHERE id=' . $id;
		$db->query( $sql );
		$content = 'OK_' . $id;
	}
	nv_del_moduleCache( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

if ( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ))
{
	$id = $nv_Request->get_int( 'delete_id', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$weight=0;
		$sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops WHERE id =' . $db->quote( $id );
		$result = $db->query( $sql );
		list( $weight) = $result->fetch( 3 );

		$db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops  WHERE id = ' . $db->quote( $id ) );

		// Xoa bang shops_carrier
		$db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops_carrier WHERE shops_id = ' . $id );

		if( $weight > 0)
		{
			$sql = 'SELECT id, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while(list( $id, $weight) = $result->fetch( 3 ))
			{
				$weight--;
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_shops SET weight=' . $weight . ' WHERE id=' . intval( $id ));
			}
		}
		nv_del_moduleCache( $module_name );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}

if( $nv_Request->isset_request( 'change_active', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops WHERE id=' . $id;
	$id = $db->query( $sql )->fetchColumn();
	if( empty( $id ) ) die( 'NO_' . $id );

	$new_status = $nv_Request->get_bool( 'new_status', 'post' );
	$new_status = ( int )$new_status;

	$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_shops SET status=' . $new_status . ' WHERE id=' . $id;
	$db->query( $sql );

	nv_del_moduleCache( $module_name );

	die( 'OK_' . $pid );
}

$row = array();
$config_carrier_old = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );

// Lay nha cung cap dich vu van chuyen
$sql = 'SELECT id, name FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier WHERE status = 1 ORDER BY weight ASC';
$global_array_carrier = nv_db_cache( $sql, 'id', $module_name );

// Lay cau hinh nha cung cap dich vu van chuyen
$sql = 'SELECT id, title FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config WHERE status = 1 ORDER BY weight ASC';
$global_array_carrier_config = nv_db_cache( $sql, 'id', $module_name );

if( empty( $global_array_carrier ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=carrier' );
	die();
}

if( empty( $global_array_carrier_config ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=carrier_config' );
	die();
}

if( $row['id'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}

	$row['config_carrier'] = array();
	$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops_carrier WHERE shops_id=' . $row['id'] );
	if( $result )
	{
		while( $carrier = $result->fetch() )
		{
			$row['config_carrier'][] = array( 'carrier' => intval($carrier['carrier_id']), 'config' => intval($carrier['config_id']) );
		}
	}
	$config_carrier_old = $row['config_carrier'];
}
else
{
	$row['id'] = 0;
	$row['name'] = '';
	$row['location'] = 0;
	$row['address'] = '';
	$row['description'] = '';
	$row['status'] = 0;
}

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['name'] = $nv_Request->get_title( 'name', 'post', '' );
	$row['location'] = $nv_Request->get_int( 'location', 'post', 0 );
	$row['address'] = $nv_Request->get_title( 'address', 'post', '' );
	$row['config_carrier'] = $nv_Request->get_array( 'config_carrier', 'post', array() );
	$row['description'] = $nv_Request->get_editor( 'description', '', NV_ALLOWED_HTML_TAGS );
	$row['status'] = $nv_Request->get_int( 'status', 'post', 0 );

	if( ! empty( $row['config_carrier'] ) )
	{
		foreach( $row['config_carrier'] as $key => $array )
		{
			if( empty( $array['carrier'] ) or empty( $array['config'] ) )
			{
				unset( $row['config_carrier'][$key] );
			}
		}
	}

	if( empty( $row['name'] ) )
	{
		$error[] = $lang_module['shops_error_required_name'];
	}
	elseif( empty( $row['location'] ) )
	{
		$error[] = $lang_module['shops_error_required_location'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{
				$sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_shops (name, location, address, description, weight, status) VALUES (:name, :location, :address, :description, :weight, 1)';

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops' )->fetchColumn();
				$weight = intval( $weight ) + 1;

				$data_insert = array( );
				$data_insert['name'] = $row['name'];
				$data_insert['location'] = $row['location'];
				$data_insert['address'] = $row['address'];
				$data_insert['description'] = $row['description'];
				$data_insert['weight'] = $weight;
				$insert_id = $db->insert_id( $sql, 'id', $data_insert );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_shops SET name = :name, location = :location, address = :address, description = :description WHERE id=' . $row['id'] );
				$stmt->bindParam( ':name', $row['name'], PDO::PARAM_STR );
				$stmt->bindParam( ':location', $row['location'], PDO::PARAM_STR );
				$stmt->bindParam( ':address', $row['address'], PDO::PARAM_STR );
				$stmt->bindParam( ':description', $row['description'], PDO::PARAM_STR, strlen($row['description']) );
				$exc = $stmt->execute();
			}

			if( $exc or $insert_id )
			{
				if( $row['config_carrier'] != $config_carrier_old )
				{
					if( !empty( $row['id'] ) )
					{
						$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops_carrier WHERE shops_id=' . $row['id'] );
					}
					foreach( $row['config_carrier'] as $array )
					{
						if( empty( $row['id'] ) )
						{
							$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_shops_carrier ( shops_id, carrier_id, config_id ) VALUES (' . $insert_id . ', ' . $array['carrier'] . ', ' . $array['config'] . ')' );
						}
						else
						{
							$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_shops_carrier ( shops_id, carrier_id, config_id ) VALUES (' . $row['id'] . ', ' . $array['carrier'] . ', ' . $array['config'] . ')' );
						}
					}
				}

				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
				die();
			}
		}
		catch( PDOException $e )
		{
			$error[] = $lang_module['shops_error_exist_carrier'];
			trigger_error( $e->getMessage() );
		}
	}
}

if( defined( 'NV_EDITOR' ) ) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
$row['description'] = htmlspecialchars( nv_editor_br2nl( $row['description'] ) );
if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$row['description'] = nv_aleditor( 'description', '100%', '300px', $row['description'], 'Basic' );
}
else
{
	$row['description'] = '<textarea style="width:100%;height:300px" name="description">' . $row['description'] . '</textarea>';
}


// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 5;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( $db_config['prefix'] . '_' . $module_data . '_shops' );
	$sth = $db->prepare( $db->sql() );
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( '*' )
		->order( 'weight ASC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );
	$sth->execute();
}

// Lay dia diem
$sql = "SELECT id, parentid, title, lev FROM " . $db_config['prefix'] . '_' . $module_data . "_location ORDER BY sort ASC";
$result = $db->query( $sql );
$array_location_list = array();
$array_location = array();
$array_location_list[0] = array( '0', $lang_module['location_chose'] );
while( list( $id_i, $parentid_i, $title_i, $lev_i ) = $result->fetch( 3 ) )
{
	$array_location[$id_i] = array( 'id' => $id_i, 'parentid'=> $parentid_i, 'title' => $title_i, 'lev' => $lev_i );
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
	$array_location_list[] = array( $id_i, $xtitle_i );
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_UPLOADS_DIR', NV_UPLOADS_DIR );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );

$xtpl->assign( 'LOCALTION_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=location' );
$xtpl->assign( 'CARRIER_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=carrier' );
$xtpl->assign( 'CONFIG_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=carrier_config' );
$xtpl->assign( 'SHOPS_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=shops' );

if( $show_view )
{
	while( $view = $sth->fetch() )
	{
		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array(
				'key' => $i,
				'title' => $i,
				'selected' => ( $i == $view['weight'] ) ? ' selected="selected"' : '') );
			$xtpl->parse( 'main.view.loop.weight_loop' );
		}

		$view['location_string'] = $array_location[$view['location']]['title'];
		while( $array_location[$view['location']]['parentid'] > 0 )
		{
			$items = $array_location[$array_location[$view['location']]['parentid']];
			$view['location_string'] .= ', ' . $items['title'];
			$array_location[$view['location']]['parentid'] = $items['parentid'];
		}

		$view['status'] = $view['status'] ? 'checked="checked"' : '';
		$view['link_config'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=carrier_config&amp;id=' . $view['id'];
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}

foreach( $array_location_list as $rows_i )
{
	$sl = ( $row['location'] == $rows_i[0] ) ? ' selected="selected"' : '';
	$xtpl->assign( 'plocal_i', $rows_i[0] );
	$xtpl->assign( 'ptitle_i', $rows_i[1] );
	$xtpl->assign( 'pselect', $sl );
	$xtpl->parse( 'main.parent_loop' );
}

$row['config_carrier'][] = array(
	'carrier' => 0,
	'config' => 0,
);

$i = 0;
foreach( $row['config_carrier'] as $config )
{
	$config['id'] = $i;
	$xtpl->assign( 'CONFIG', $config );

	if( ! empty( $global_array_carrier ) )
	{
		foreach( $global_array_carrier as $carrier_id => $carrier )
		{
			$xtpl->assign( 'CARRIER', array( 'key' => $carrier_id, 'value' => $carrier['name'], 'selected' => ($config['carrier'] == $carrier_id) ? 'selected="selected"' : '' ) );
			$xtpl->parse( 'main.config.carrier' );
		}
	}

	if( !empty( $global_array_carrier_config ) )
	{
		foreach( $global_array_carrier_config as $carrier_config_id => $carrier_config )
		{
			$carrier_config['select'] = ( $config['config'] == $carrier_config_id ) ? "selected=\"selected\"" : "";
			$xtpl->assign( 'CARRIER_CONFIG', $carrier_config );
			$xtpl->parse( 'main.config.carrier_config' );
		}
	}

	$xtpl->parse( 'main.config' );
	++$i;
}
$xtpl->assign( 'config_carrier_count', $i );

if( ! empty( $global_array_carrier ) )
{
	foreach( $global_array_carrier as $carrier_id => $carrier )
	{
		$xtpl->assign( 'CARRIER', array( 'key' => $carrier_id, 'value' => $carrier['name'], 'selected' => ($config['carrier'] == $carrier_id) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.carrier' );
	}
}

if( !empty( $global_array_carrier_config ) )
{
	foreach( $global_array_carrier_config as $carrier_config_id => $carrier_config )
	{
		$carrier_config['select'] = ( $config['config'] == $carrier_config_id ) ? "selected=\"selected\"" : "";
		$xtpl->assign( 'CARRIER_CONFIG', $carrier_config );
		$xtpl->parse( 'main.carrier_config' );
	}
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['shops'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';