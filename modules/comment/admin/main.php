<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 21 Jan 2014 01:32:02 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['comment'];

$page = $nv_Request->get_int( 'page', 'get', 1 );
$module = $nv_Request->get_title( 'module', 'get' );
$per_page = $nv_Request->get_int( 'per_page', 'get', 20 );
$stype = $nv_Request->get_string( 'stype', 'get', '' );
$sstatus = $nv_Request->get_title( 'sstatus', 'get', 2 );
$from['q'] = $nv_Request->get_title( 'q', 'get', '' );
$from['from_date'] = $nv_Request->get_title( 'from_date', 'get', '' );
$from['to_date'] = $nv_Request->get_title( 'to_date', 'get', '' );

$array_search = array(
	'' => '---',
	'content' => $lang_module['search_content'],
	'post_name' => $lang_module['search_post_name'],
	'post_email' => $lang_module['search_post_email'],
	'content_id' => $lang_module['search_content_id']
);
$array_status_view = array(
	'2' => '---',
	'1' => $lang_module['enable'],
	'0' => $lang_module['disable']
);
if( ! in_array( $stype, array_keys( $array_search ) ) )
{
	$stype = '';
}

if( ! in_array( $sstatus, array_keys( $array_status_view ) ) )
{
	$sstatus = 2;
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'FROM', $from );

foreach( $array_search as $key => $val )
{
	$xtpl->assign( 'OPTION', array(
		'key' => $key,
		'title' => $val,
		'selected' => ( $key == $stype ) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.search_type' );
}

foreach( $array_status_view as $key => $val )
{
	$xtpl->assign( 'OPTION', array(
		'key' => $key,
		'title' => $val,
		'selected' => ( $key == $sstatus ) ? ' selected="selected"' : ''
	) );

	$xtpl->parse( 'main.search_status' );
}

$xtpl->assign( 'OPTION', array(
	'key' => '',
	'title' => $lang_module['search_module_all'],
	'selected' => ( $module == '' ) ? ' selected="selected"' : ''
) );
$xtpl->parse( 'main.module' );

foreach( $site_mod_comm as $module_i => $row )
{
	$custom_title = ( ! empty( $row['admin_title'] ) ) ? $row['admin_title'] : $row['custom_title'];
	$xtpl->assign( 'OPTION', array(
		'key' => $module_i,
		'title' => $custom_title,
		'selected' => ( $module_i == $module ) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.module' );
}

$i = 15;
$search_per_page = array();
while( $i < 100 )
{
	$i = $i + 5;

	$xtpl->assign( 'OPTION', array( 'page' => $i, 'selected' => ( $i == $page ) ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.per_page' );
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_comments' );

$array_where = array();
if( ! empty( $module ) and isset( $site_mod_comm[$module] ) )
{
	$array_where[] = 'module = ' . $db->quote( $module );
}
elseif( ! defined( 'NV_IS_SPADMIN' ) )
{
	// Gới hạn module tìm kiếm nếu không phải là quản trị site
	if( empty( $site_mod_comm ) )
	{
		include NV_ROOTDIR . '/includes/header.php';
		echo nv_admin_theme( $lang_global['admin_no_allow_func'] );
		include NV_ROOTDIR . '/includes/footer.php';
	}
	else
	{
		$mod_where = array();
		foreach( $site_mod_comm as $module_i => $custom_title )
		{
			$mod_where[] = 'module = ' . $db->quote( $module_i );
		}
		$array_where[] = '( ' . implode( ' OR ', $mod_where ) . ' )';
	}
}

if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $from['from_date'], $m ) )
{
	$array_where[] = 'post_time > ' . mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
}

if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $from['to_date'], $m ) )
{
	$array_where[] = 'post_time < ' . mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
}

if( $sstatus == 0 or $sstatus == 1 )
{
	$array_where[] = 'status = ' . $sstatus;
}
if( ! empty( $from['q'] ) )
{
	$array_like = array();
	if( $stype == '' or $stype == 'content' )
	{
		$array_like[] = 'content LIKE :content';
	}

	if( $stype == '' or $stype == 'post_name' )
	{
		$array_like[] = 'post_name LIKE :post_name';
	}

	if( $stype == '' or $stype == 'post_email' )
	{
		$array_like[] = 'post_email LIKE :post_email';
	}

	if( $stype == 'content_id' and preg_match( '/^[0-9]$/', $from['q'] ) )
	{
		$array_like = array();
		$array_like[] = 'id =' . intval( $from['q'] );
	}
	$array_where[] = '( ' . implode( ' OR ', $array_like ) . ' )';
}

if( ! empty( $array_where ) )
{
	$db->where( implode( ' AND ', $array_where ) );
}
$sql = $db->sql();
$sth = $db->prepare( $sql );
if( strpos( $sql, ':content' ) )
{
	$sth->bindValue( ':content', '%' . $from['q'] . '%', PDO::PARAM_STR );
}
if( strpos( $sql, ':post_name' ) )
{
	$sth->bindValue( ':post_name', '%' . $from['q'] . '%', PDO::PARAM_STR );
}
if( strpos( $sql, ':post_email' ) )
{
	$sth->bindValue( ':post_email', '%' . $from['q'] . '%', PDO::PARAM_STR );
}
$sth->execute();
$num_items = $sth->fetchColumn();

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

$db->select( 'cid, module, area, id, content, userid, post_name, post_email, status' )->order( 'cid DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
$sql = $db->sql();
$sth = $db->prepare( $sql );
if( strpos( $sql, ':content' ) )
{
	$sth->bindValue( ':content', '%' . $from['q'] . '%', PDO::PARAM_STR );
}
if( strpos( $sql, ':post_name' ) )
{
	$sth->bindValue( ':post_name', '%' . $from['q'] . '%', PDO::PARAM_STR );
}
if( strpos( $sql, ':post_email' ) )
{
	$sth->bindValue( ':post_email', '%' . $from['q'] . '%', PDO::PARAM_STR );
}
$sth->execute();
$array = array();
while( list( $cid, $module, $area, $id, $content, $userid, $post_name, $email, $status ) = $sth->fetch( 3 ) )
{
	if( $userid > 0 )
	{
		$email = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $userid . '"> ' . $email . '</a>';
	}

	$row = array(
		'cid' => $cid,
		'post_name' => $post_name,
		'email' => $email,
		'content' => $content,
		'module' => $module,
		'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=view&amp;area=' . $area . '&amp;id=' . $id,
		'status' => ( $status == 1 ) ? 'check' : 'circle-o',
		'linkedit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;cid=' . $cid,
		'linkdelete' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=del&amp;list=' . $cid
	);
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.loop' );
}

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';