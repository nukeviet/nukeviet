<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/15/2010 3:35
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$sql = 'SELECT id,full_name FROM ' . NV_BANNERS_GLOBALTABLE. '_clients ORDER BY login ASC';
$result = $db->query( $sql );

$clients = array();
while( $row = $result->fetch() )
{
	$clients[$row['id']] = $row['full_name'];
}

$sql = 'SELECT id,title,blang, form FROM ' . NV_BANNERS_GLOBALTABLE. '_plans ORDER BY blang, title ASC';
$result = $db->query( $sql );

$plans = array();
$plans_form = array();

while( $row = $result->fetch() )
{
	$plans[$row['id']] = $row['title'] . ' (' . ( ! empty( $row['blang'] ) ? $language_array[$row['blang']]['name'] : $lang_module['blang_all'] ) . ')';
	$plans_form[$row['id']] = $row['form'];
}

$contents = array();
$contents['thead'] = array( $lang_module['title'], $lang_module['in_plan'], $lang_module['of_client'], $lang_module['publ_date'], $lang_module['exp_date'], $lang_module['is_act'], $lang_global['actions'] );
$contents['view'] = $lang_global['detail'];
$contents['edit'] = $lang_global['edit'];
$contents['del'] = $lang_global['delete'];
$contents['rows'] = array();

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE. '_rows WHERE ';
$where = array();
$aray_act = array( 1, 2, 3, 4 );
$act = $nv_Request->get_int( 'act', 'get', 0 );
$clid = $nv_Request->get_int( 'clid', 'get', 0 );
$pid = $nv_Request->get_int( 'pid', 'get' );

if( $pid > 0 and isset( $plans[$pid] ) and $plans_form[$pid] == 'sequential' )
{
	array_unshift( $contents['thead'], $lang_module['weight'] );
	define( 'NV_BANNER_WEIGHT', true );
}

if( in_array( $act, $aray_act ) )
{
	$where[] = 'act=' . $nv_Request->get_int( 'act', 'get' );
	$contents['caption'] = $lang_module['banners_list' . $act];
}
else
{
	$contents['caption'] = $lang_module['banners_list'];
}

if( $clid > 0 and isset( $clients[$clid] ) )
{
	$where[] = 'clid=' . $clid;
	$contents['caption'] .= ' ' . sprintf( $lang_module['banners_list_cl'], $clients[$clid] );
}
elseif( $pid > 0 and isset( $plans[$pid] ) )
{
	$where[] = 'pid=' . $pid;
	$contents['caption'] .= ' ' . sprintf( $lang_module['banners_list_pl'], $plans[$pid] );
}
if( ! empty( $where ) )
{
	$sql .= implode( ' AND ', $where );
}
if( defined( 'NV_BANNER_WEIGHT' ) )
{
	$sql .= ' ORDER BY weight ASC';
	$id = $nv_Request->get_int( 'id', 'get', 0 );
	$new_weight = $nv_Request->get_int( 'weight', 'get', 0 );

	if( $id > 0 and $new_weight > 0 )
	{
		$query_weight = 'SELECT id FROM ' . NV_BANNERS_GLOBALTABLE. '_rows WHERE id!=' . $id . ' AND pid=' . $pid . ' ORDER BY weight ASC';
		$result = $db->query( $query_weight );

		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_weight ) ++$weight;
			$db->query( 'UPDATE ' . NV_BANNERS_GLOBALTABLE. '_rows SET weight=' . $weight . ' WHERE id=' . $row['id'] );
		}

		$db->query( 'UPDATE ' . NV_BANNERS_GLOBALTABLE. '_rows SET weight=' . $new_weight . ' WHERE id=' . $id );

		nv_CreateXML_bannerPlan();
	}
}
else
{
	$sql .= ' ORDER BY id DESC';
}

$rows = $db->query( $sql )->fetchAll();
$num = sizeof( $rows );

foreach ( $rows as $row )
{
	$client = ! empty( $row['clid'] ) ? $clients[$row['clid']] : '';

	$weight_banner = '';
	if( defined( 'NV_BANNER_WEIGHT' ) )
	{
		$weight_banner = '';
		$weight_banner .= "<select id=\"id_weight_" . $row['id'] . "\" onchange=\"nv_chang_weight_banners('banners_list',0,'" . $pid . "',0,'" . $row['id'] . "');\">\n";

		for( $i = 1; $i <= $num; ++$i )
		{
			$weight_banner .= "<option value=\"" . $i . "\"" . ( $i == $row['weight'] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
		}

		$weight_banner .= '</select>';
	}

	$contents['rows'][$row['id']]['weight'] = $weight_banner;
	$contents['rows'][$row['id']]['title'] = $row['title'];
	$contents['rows'][$row['id']]['pid'] = array( NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info_plan&amp;id=' . $row['pid'], $plans[$row['pid']] );
	$contents['rows'][$row['id']]['clid'] = ! empty( $client ) ? array( NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info_client&amp;id=' . $row['clid'], $client ) : array();
	$contents['rows'][$row['id']]['publ_date'] = date( 'd/m/Y', $row['publ_time'] );
	$contents['rows'][$row['id']]['exp_date'] = ! empty( $row['exp_time'] ) ? date( 'd/m/Y', $row['exp_time'] ) : $lang_module['unlimited'];
	$contents['rows'][$row['id']]['act'] = array( 'act_' . $row['id'], $row['act'], "nv_b_chang_act(" . $row['id'] . ",'act_" . $row['id'] . "');" );
	$contents['rows'][$row['id']]['view'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info_banner&amp;id=' . $row['id'];
	$contents['rows'][$row['id']]['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_banner&amp;id=' . $row['id'];
	$contents['rows'][$row['id']]['del'] = 'nv_b_del(' . $row['id'] . ');';
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_b_list_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';