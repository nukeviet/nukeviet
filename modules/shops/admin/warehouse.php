<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = sprintf( $lang_module['warehouse_day'], nv_date( 'd/m/Y', NV_CURRENTTIME ) );

if( $nv_Request->isset_request( 'checkss', 'get' ) and $nv_Request->get_string( 'checkss', 'get' ) == md5( $global_config['sitekey'] . session_id() ) )
{
	$array_data = array();
	$array_warehouse = array( 'title' => $page_title, 'note' => '' );
	$listid = $nv_Request->get_string( 'listid', 'get', '' );
	if( empty( $listid ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );
		die();
	}
	else
	{
		$listid = rtrim( $listid, ',' );
	}

	if( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$title = $nv_Request->get_title( 'title', 'post', $page_title );
		$note = $nv_Request->get_textarea( 'note', '', 'br' );
		$quantity = $nv_Request->get_array( 'quantity', 'post', array() );
		$price = $nv_Request->get_array( 'price', 'post', array() );
		$money_unit = $nv_Request->get_array( 'money_unit', 'post', array() );

		$sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_warehouse( title, note, user_id, addtime ) VALUES ( :title, :note, ' . $admin_info['admin_id'] . ', ' . NV_CURRENTTIME . ' )';
		$data_insert = array();
		$data_insert['title'] = $title;
		$data_insert['note'] = $note;
		$wid = $db->insert_id( $sql, 'wid', $data_insert );

		if( $wid > 0 and !empty( $quantity ) )
		{
			foreach( $quantity as $pro_id => $quantity_i )
			{
				if( !empty( $quantity_i ) )
				{
					$total_num = 0;
					$total_price = 0;
					foreach( $quantity_i as $groupid => $num )
					{
						if( !empty( $num ) )
						{
							$total_num += $num;
							$price = floatval( preg_replace( '/[^0-9\.]/', '', $price[$pro_id][$groupid] ) );
							$total_price += $price;
							if( !empty( $groupid ) )
							{
								$money_unit = $money_unit[$pro_id][$groupid];
								// Cap nhat so luong san pham cua moi nhom
								$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group_items SET pro_quantity = pro_quantity + ' . $num . ' WHERE pro_id=' . $pro_id . ' AND group_id=' . $groupid );
							}
							else
							{
								$money_unit = $money_unit[$pro_id][0];
							}

							// Cap nhat logs nhap kho
							$sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_warehouse_logs( wid, pro_id, quantity, price, money_unit ) VALUES ( ' . $wid .  ', ' . $pro_id . ', ' . $total_num . ', ' . $total_price . ', :money_unit )';
							$data_insert = array();
							$data_insert['money_unit'] = $money_unit;
							$logid = $db->insert_id( $sql, 'logid', $data_insert );
							if( !empty( $groupid ) and $logid > 0 )
							{
								$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_warehouse_logs_group( logid, quantity, groupid ) VALUES ( ' . $logid . ', ' . $groupid . ', ' . $num . ' )' );
								$sth->execute();
							}
						}
					}
					// Cap nhat tong so luong
					$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET product_number = product_number + ' . $total_num . ' WHERE id=' . $pro_id );
				}
			}
		}
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );
		die();
	}

	// List pro_unit
	$array_unit = array();
	$sql = 'SELECT id, ' . NV_LANG_DATA . '_title title FROM ' . $db_config['prefix'] . '_' . $module_data . '_units';
	$result_unit = $db->query( $sql );
	if( $result_unit->rowCount( ) > 0 )
	{
		while( $row = $result_unit->fetch() )
		{
			$array_unit[$row['id']] = $row;
		}
	}

	$_sql = 'SELECT id, listcatid, ' . NV_LANG_DATA . '_title title, ' . NV_LANG_DATA . '_alias alias, product_number, product_unit, money_unit FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id IN (' . $listid . ')';
	$_query = $db->query( $_sql );

	while( $row = $_query->fetch() )
	{
		$array_data[$row['id']] = $row;
	}

	$xtpl = new XTemplate( "warehouse.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'WAREHOUSE', $array_warehouse );

	if( !empty( $array_data ) )
	{
		$i=1;
		foreach( $array_data as $data )
		{
			$data['no'] = $i;
			$data['product_unit'] = $array_unit[$data['product_unit']]['title'];
			$data['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$data['listcatid']]['alias'] . '/' . $data['alias'] . '-' . $data['id'] . $global_config['rewrite_exturl'];
			$xtpl->assign( 'DATA', $data );

			// Nhom san pham
			$listgroup = GetGroupID( $data['id'] );
			$have_group = 0;
			if( !empty( $listgroup ) )
			{
				foreach( $listgroup as $group_id )
				{
					$group = $global_array_group[$group_id];
					if( $group['indetail'] )
					{
						$have_group = 1;
						$result = $db->query( 'SELECT pro_quantity FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_items WHERE pro_id=' . $data['id'] . ' AND group_id=' . $group['groupid'] );
						$group['pro_quantity'] = $result->fetchColumn();

						$group['parent_title'] = $global_array_group[$group['parentid']]['title'];
						$xtpl->assign( 'GROUP', $group );

						if( !empty( $money_config ) )
						{
							foreach( $money_config as $code => $info )
							{
								$info['selected'] = ($data['money_unit'] == $code) ? "selected=\"selected\"" : "";
								$xtpl->assign( 'MON', $info );
								$xtpl->parse( 'main.loop.group.money_unit' );
							}
						}

						$xtpl->parse( 'main.loop.group' );
					}
				}
			}

			if( !$have_group )
			{
				if( !empty( $money_config ) )
				{
					foreach( $money_config as $code => $info )
					{
						$info['selected'] = ($data['money_unit'] == $code) ? "selected=\"selected\"" : "";
						$xtpl->assign( 'MON', $info );
						$xtpl->parse( 'main.loop.product_number.money_unit' );
					}
				}
				$xtpl->parse( 'main.loop.product_number' );
			}

			$xtpl->parse( 'main.loop' );
			$i++;
		}
	}
}
else
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );
	die();
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';