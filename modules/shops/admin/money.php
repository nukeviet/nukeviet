<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$page_title = $lang_module['money'];

$currencies_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/currencies.ini', true );

if( !empty( $pro_config['money_unit'] ) != '' and isset( $currencies_array[$pro_config['money_unit']] ) )
{
	$page_title .= ' ' . $lang_module['money_compare'] . ' ' . $currencies_array[$pro_config['money_unit']]['currency'];
}

$error = '';
$savecat = 0;
$data = array( );

$table_name = $db_config['prefix'] . '_' . $module_data . '_money_' . NV_LANG_DATA;
$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );

$id = $nv_Request->get_int( 'id', 'get', 0 );
if( !empty( $savecat ) )
{
	$data['code'] = $nv_Request->get_title( 'code', 'post' );
	$data['currency'] = $nv_Request->get_title( 'currency', 'post', '', 1 );
	$data['exchange'] = $nv_Request->get_title( 'exchange', 'post,get', 0 );
	$data['exchange'] = floatval( preg_replace( '/[^0-9\.]/', '', $data['exchange'] ) );
	$data['round'] = $nv_Request->get_title( 'round', 'post,get', 0 );
	$data['round'] = floatval( preg_replace( '/[^0-9\.]/', '', $data['round'] ) );
	if( isset( $currencies_array[$data['code']] ) )
	{
		$numeric = intval( $currencies_array[$data['code']]['numeric'] );
		if( !empty( $pro_config['money_unit'] ) and $pro_config['money_unit'] == $data['code'] )
		{
			$data['exchange'] = 1;
		}

		$data['currency'] = ( empty( $data['currency'] )) ? $currencies_array[$data['code']]['currency'] : $data['currency'];
		$sql = 'REPLACE INTO ' . $table_name . ' (id, code, currency, exchange, round) VALUES (' . $numeric . ', ' . $db->quote( $data['code'] ) . ', ' . $db->quote( $data['currency'] ) . ', ' . $db->quote( $data['exchange'] ) . ', ' . $db->quote( $data['round'] ) . ')';

		if( $db->exec( $sql ) )
		{
			$error = $lang_module['saveok'];
			nv_del_moduleCache( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die( );
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
}
elseif( !empty( $id ) )
{
	$data = $db->query( 'SELECT * FROM ' . $table_name . ' WHERE id=' . $id )->fetch( );
	$data['caption'] = $lang_module['money_edit'];
}

if( empty( $data ) )
{
	$data = array( );
	$data['id'] = '';
	$data['code'] = '';
	$data['currency'] = '';
	$data['exchange'] = 0;
	$data['round'] = 0.01;
	$data['caption'] = $lang_module['money_add'];
}

$xtpl = new XTemplate( 'money.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'MONEY_UNIT', $pro_config['money_unit'] );

$count = 0;
$array_code_exit = array( );
$result = $db->query( 'SELECT id, code, currency, exchange, round FROM ' . $table_name . ' ORDER BY code DESC' );
while( $row = $result->fetch( ) )
{
	$array_code_exit[] = $row['code'];
	$row['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $row['id'];
	$row['link_del'] = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=delmoney&id=' . $row['id'];

	$row['exchange'] = floatval( $row['exchange'] );
	if( intval( $row['exchange'] ) == $row['exchange'] or $row['exchange'] > 1000 )
	{
		$row['exchange'] = number_format( $row['exchange'], 0 );
	}
	elseif( $row['exchange'] > 1 )
	{
		$row['exchange'] = number_format( $row['exchange'], 5 );
	}
	elseif( $row['exchange'] > 0.001 )
	{
		$row['exchange'] = number_format( $row['exchange'], 7 );
	}
	elseif( $row['exchange'] > 0.00001 )
	{
		$row['exchange'] = number_format( $row['exchange'], 9 );
	}
	else
	{
		$row['exchange'] = number_format( $row['exchange'], 11 );
	}
	$row['round'] = ( $row['round'] >= 1 ) ? number_format( $row['round'] ) : number_format( $row['round'], strlen( $row['round'] ) - 2 );
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.data.row' );

	++$count;
}

$xtpl->assign( 'URL_DEL', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=delmoney' );
$xtpl->assign( 'URL_DEL_BACK', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );

if( $count > 0 )
{
	$xtpl->parse( 'main.data' );
}

$numeric = 0;
ksort( $currencies_array );

foreach( $currencies_array as $code => $value )
{
	if( !in_array( $code, $array_code_exit ) or $code == $data['code'] )
	{
		$array_temp = array( );
		$array_temp['value'] = $code;
		$array_temp['title'] = $code . ' - ' . $value['currency'];
		$array_temp['selected'] = ($value['numeric'] == $data['id']) ? ' selected="selected"' : '';

		$xtpl->assign( 'DATAMONEY', $array_temp );

		$xtpl->parse( 'main.money' );
	}
}

$data['exchange'] = floatval( $data['exchange'] );
if( intval( $data['exchange'] ) == $data['exchange'] or $data['exchange'] > 1000 )
{
	$data['exchange'] = number_format( $data['exchange'], 0 );
}
elseif( $data['exchange'] > 1 )
{
	$data['exchange'] = number_format( $data['exchange'], 5 );
}
elseif( $data['exchange'] > 0.001 )
{
	$data['exchange'] = number_format( $data['exchange'], 7 );
}
elseif( $data['exchange'] > 0.00001 )
{
	$data['exchange'] = number_format( $data['exchange'], 9 );
}
else
{
	$data['exchange'] = number_format( $data['exchange'], 11 );
}

$xtpl->assign( 'DATA', $data );
for( $i = -5; $i < 5; $i++ )
{
	$round1 = pow( 10, $i );
	if( $i < 1 )
	{
		$round1 = $round2 = number_format( $round1, - $i );
	}
	else
	{
		$round2 = number_format( $round1 );
	}

	$xtpl->assign( 'ROUND', array(
		'round1' => $round1,
		'round2' => $round2,
		'selected' => ( $round1 == $data['round'] ) ? 'selected="selected"' : ''
	) );
	$xtpl->parse( 'main.round' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
