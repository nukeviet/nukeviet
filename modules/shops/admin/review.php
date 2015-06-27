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

if( $nv_Request->isset_request( 'del', 'post,get' ) )
{
	$dellist = $nv_Request->isset_request( 'dellist', 'post,get' );
	if( $dellist )
	{
		$array_id = $nv_Request->get_string( 'listid', 'post,get' );
		$array_id = explode( ',', $array_id );

		foreach( $array_id as $review_id )
		{
			if( !empty( $review_id ) )
			{
				$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_review WHERE review_id=' . $review_id );
			}
		}
		nv_del_moduleCache( $module_name );
		die( 'OK' );
	}
	else
	{
		$id = $nv_Request->get_int( 'id', 'post,get', 0 );
		if( empty( $id ) ) die( 'NO' );

		$result = $db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_review WHERE review_id=' . $id );
		if( $result )
		{
			nv_del_moduleCache( $module_name );
			die( 'OK' );
		}
	}
	die( 'NO' );
}

if( $nv_Request->isset_request( 'change_status', 'get,post' ) )
{
	$array_id = $nv_Request->get_string( 'listid', 'post,get' );
	$array_id = explode( ',', $array_id );
	$new_status = $nv_Request->get_int( 'status', 'post,get', 0 );
	$new_status = ( int )$new_status;

	foreach( $array_id as $review_id )
	{
		if( !empty( $review_id ) )
		{
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_review SET status=' . $new_status . ' WHERE review_id=' . $review_id;
			$db->query( $sql );
		}
	}
	nv_del_moduleCache( $module_name );
	die( 'OK' );
}

$per_page = 20;
$page = $nv_Request->get_int( 'page', 'post,get', 1 );
$array_search = array();
$array_search['product_id'] = $nv_Request->get_int( 'product_id', 'get', 0 );
$array_search['keywords'] = $nv_Request->get_title( 'keywords', 'get', '' );
$array_search['status'] = $nv_Request->get_int( 'status', 'get', -1 );

$db->sqlreset( )
	->select( 'COUNT(*)' )
	->from( $db_config['prefix'] . '_' . $module_data . '_review t1' )
	->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_rows t2 ON t1.product_id = t2.id' );

$where = '';
$base_url = '';

if( !empty( $array_search['keywords'] ) )
{
	$where .= ' AND ' . NV_LANG_DATA . '_title LIKE :q_title OR sender LIKE :q_sender OR content like :q_content';
}

if( !empty( $array_search['product_id'] ) )
{
	$where .= ' AND t1.product_id = ' . $array_search['product_id'];
}

if( $array_search['status'] >= 0 )
{
	$where .= ' AND t1.status = ' . $array_search['status'];
}

if( ! empty( $where ) )
{
	$db->where( '1=1' . $where );
}

$sth = $db->prepare( $db->sql( ) );

if( !empty( $array_search['keywords'] ) )
{
	$sth->bindValue( ':q_title', '%' . $array_search['keywords'] . '%' );
	$sth->bindValue( ':q_sender', '%' . $array_search['keywords'] . '%' );
	$sth->bindValue( ':q_content', '%' . $array_search['keywords'] . '%' );
}

$sth->execute( );
$num_items = $sth->fetchColumn( );

$db->select( 't1.*, t2.listcatid, t2.' . NV_LANG_DATA . '_title title, t2.' . NV_LANG_DATA . '_alias alias' )->order( 'review_id DESC' )->limit( $per_page )->offset( ($page - 1) * $per_page );
$sth = $db->prepare( $db->sql( ) );

if( !empty( $array_search['keywords'] ) )
{
	$sth->bindValue( ':q_title', '%' . $array_search['keywords'] . '%' );
	$sth->bindValue( ':q_sender', '%' . $array_search['keywords'] . '%' );
	$sth->bindValue( ':q_content', '%' . $array_search['keywords'] . '%' );
}
$sth->execute( );

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_UPLOADS_DIR', NV_UPLOADS_DIR );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'SEARCH', $array_search );

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if( !empty( $array_search['keywords'] ) )
{
	$base_url .= '&keywords=' . $array_search['keywords'];
}

if( !empty( $array_search['product_id'] ) )
{
	$base_url .= '&product_id=' . $array_search['product_id'];
}

if( $array_search['status'] >= 0 )
{
	$base_url .= '&status=' . $array_search['status'];
}

while( $view = $sth->fetch( ) )
{
	$view['link_product'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$view['listcatid']]['alias'] . '/' . $view['alias'] . $global_config['rewrite_exturl'];
	$view['add_time'] = nv_date( 'H:i d/m/Y', $view['add_time'] );
	$view['status'] = $lang_module['review_status_' . $view['status']];
	$xtpl->assign( 'VIEW', $view );
	$xtpl->parse( 'main.loop' );
}

$array_status = array( '1' => $lang_module['review_status_1'], '0' => $lang_module['review_status_0'] );
foreach( $array_status as $key => $value )
{
	$xtpl->assign( 'STATUS', array( 'key' => $key, 'value' => $value, 'selected' => $array_search['status'] == $key ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.status' );
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
if( !empty( $generate_page ) )
{
	$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['review'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
