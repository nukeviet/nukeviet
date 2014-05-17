<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'menu', 'rows', 'link_menu', 'link_module', 'change_weight_row', 'del_row' );

// Loai lien ket
$type_target = array();
$type_target[1] = $lang_module['type_target1'];
$type_target[2] = $lang_module['type_target2'];
$type_target[3] = $lang_module['type_target3'];

/**
 * nv_list_menu()
 *
 * @return
 */
function nv_list_menu()
{
	global $db, $module_data;

	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY id ASC';
	$result = $db->query( $sql );

	$list = array();
	while( $row = $result->fetch() )
	{
		$list[$row['id']] = array(
			'id' => $row['id'],
			'title' => $row['title'],
		);
	}

	return $list;
}

/**
 * menu_fix_order()
 *
 * @param mixed $mid
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function menu_fix_order( $mid, $parentid = 0, $order = 0, $lev = 0 )
{
	global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;

	$array = array();
	$sql = 'SELECT id, parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE parentid=' . $parentid . ' AND mid= ' . $mid . ' ORDER BY weight ASC';
	$result = $db->query( $sql );

	$array_cat_order = array();
	while( $row = $result->fetch() )
	{
		$array_cat_order[] = $row['id'];
	}
	$result->closeCursor();

	$weight = 0;
	if( $parentid > 0 )
	{
		++$lev;
	}
	else
	{
		$lev = 0;
	}

	foreach( $array_cat_order as $catid_i )
	{
		++$order;
		++$weight;
		$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET weight=" . $weight . ", sort=" . $order . ", lev='" . $lev . "' WHERE id=" . intval( $catid_i );
		$db->query( $sql );
		$order = menu_fix_order( $mid, $catid_i, $order, $lev );
	}

	return $order;
}

define( 'NV_IS_FILE_ADMIN', true );