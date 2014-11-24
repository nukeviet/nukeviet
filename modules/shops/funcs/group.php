<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if( empty( $groupid ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

$page_title = $lang_module['group_title'];
if( preg_match( '/^page\-([0-9]+)$/', ( isset( $array_op[2] ) ? $array_op[2] : '' ), $m ) )
{
	$page = ( int )$m[1];
}

$page_title = $global_array_group[$groupid]['title'];
$key_words = $global_array_group[$groupid]['keywords'];
$description = $global_array_group[$groupid]['description'];
$data_content = array();

$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=group/' . $global_array_group[$groupid]['alias'];

// Fetch Limit
$db->sqlreset()
	->select( 'COUNT(*)' )
	->from( $db_config['prefix'] . '_' . $module_data . '_rows t1' )
	->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_items_group t2 ON t2.pro_id = t1.id' )
	->where( 't2.group_id = ' . $groupid . ' AND status =1' );
	
$num_items = $db->query( $db->sql() )->fetchColumn();

$db->select( 't1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_code, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice, t3.newday' )
	->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_items_group t2 ON t2.pro_id = t1.id INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_catalogs t3 ON t3.catid = t1.listcatid' )
	->order( 'id DESC' )
	->limit( $per_page )
	->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$data_content = GetDataInGroup( $result, $groupid );
$data_content['count'] = $num_items;

if( sizeof( $data_content['data'] ) < 1 and $page > 1 )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

$pages = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	$description .= ' ' . $page;
}

$contents = call_user_func( $global_array_group[$groupid]['viewgroup'], $data_content, $pages );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';