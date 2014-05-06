<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9-8-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['content_list'];

if( ! defined( 'SHADOWBOX' ) )
{
	$my_head = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
	$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "Shadowbox.init();\n";
	$my_head .= "</script>\n";

	define( 'SHADOWBOX', true );
}

$stype = $nv_Request->get_string( 'stype', 'get', '-' );
$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$per_page_old = $nv_Request->get_int( 'per_page', 'cookie', 50 );
$per_page = $nv_Request->get_int( 'per_page', 'get', $per_page_old );

if( $per_page < 1 and $per_page > 500 )
{
	$per_page = 50;
}

if( $per_page_old != $per_page )
{
	$nv_Request->set_Cookie( 'per_page', $per_page, NV_LIVE_COOKIE_TIME );
}

$q = nv_substr( $nv_Request->get_title( 'q', 'get', '' ), 0, NV_MAX_SEARCH_LENGTH );
$ordername = $nv_Request->get_string( 'ordername', 'get', 'publtime' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'asc' ? 'asc' : 'desc';

$array_search = array(
	'-' => '---',
	'product_code' => $lang_module['search_product_code'],
	'title' => $lang_module['search_title'],
	'bodytext' => $lang_module['search_bodytext'],
	'author' => $lang_module['search_author'],
	'admin_id' => $lang_module['search_admin']
);
$array_in_rows = array( 'title', 'bodytext' );
$array_in_ordername = array( 'title', 'publtime', 'exptime' );

if( ! in_array( $stype, array_keys( $array_search ) ) )
{
	$stype = '-';
}

if( ! in_array( $ordername, array_keys( $array_in_ordername ) ) )
{
	$ordername = 'id';
}

$from = $db_config['prefix'] . '_' . $module_data . '_rows AS a LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' AS b ON a.user_id=b.userid';

$page = $nv_Request->get_int( 'page', 'get', 1 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );

if( $checkss == md5( session_id() ) )
{
	// Tim theo tu khoa
	if( $stype == 'product_code' )
	{
		$from .= " WHERE product_code LIKE '%" . $db->dblikeescape( $q ) . "%' ";
	}
	elseif( in_array( $stype, $array_in_rows ) and ! empty( $q ) )
	{
		$from .= " WHERE " . NV_LANG_DATA . "_" . $stype . " LIKE '%" . $db->dblikeescape( $q ) . "%' ";
	}
	elseif( $stype == 'admin_id' and ! empty( $q ) )
	{
		$sql = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN (SELECT admin_id FROM " . NV_AUTHORS_GLOBALTABLE . ") AND username LIKE '%" . $db->dblikeescape( $q ) . "%' OR full_name LIKE '%" . $db->dblikeescape( $q ) . "%'";
		$result = $db->query( $sql );
		$array_admin_id = array();
		while( list( $admin_id ) = $result->fetch( 3 ) )
		{
			$array_admin_id[] = $admin_id;
		}
		$from .= " WHERE admin_id IN (0," . implode( ",", $array_admin_id ) . ",0)";
	}
	elseif( ! empty( $q ) )
	{
		$sql = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN (SELECT admin_id FROM " . NV_AUTHORS_GLOBALTABLE . ") AND username LIKE '%" . $db->dblikeescape( $q ) . "%' OR full_name LIKE '%" . $db->dblikeescape( $q ) . "%'";
		$result = $db->query( $sql );

		$array_admin_id = array();
		while( list( $admin_id ) = $result->fetch( 3 ) )
		{
			$array_admin_id[] = $admin_id;
		}

		$arr_from = array();
		$arr_from[] = "(product_code LIKE '%" . $db->dblikeescape( $q ) . "%')";
		foreach( $array_in_rows as $val )
		{
			$arr_from[] = "(" . NV_LANG_DATA . "_" . $val . " LIKE '%" . $db->dblikeescape( $q ) . "%')";
		}
		$from .= " WHERE ( " . implode( " OR ", $arr_from );
		if( ! empty( $array_admin_id ) )
		{
			$from .= ' OR (admin_id IN (0,' . implode( ',', $array_admin_id ) . ',0))';
		}
		$from .= ' )';
	}

	// Tim theo loai san pham
	if( ! empty( $catid ) )
	{
		if( empty( $q ) )
		{
			$from .= ' WHERE';
		}
		else
		{
			$from .= ' AND';
		}

		if( $global_array_cat[$catid]['numsubcat'] == 0 )
		{
			$from .= ' listcatid=' . $catid;
		}
		else
		{
			$array_cat = array();
			$array_cat = GetCatidInParent( $catid );
			$from .= ' listcatid IN (' . implode( ',', $array_cat ) . ')';
		}
	}
}

$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $from )->fetchColumn();

$xtpl = new XTemplate( 'items.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

// Loai san pham
foreach( $global_array_cat as $cat )
{
	if( $cat['catid'] > 0 )
	{
		$cat['selected'] = $cat['catid'] == $catid ? ' selected="selected"' : '';

		$xtpl->assign( 'CATID', $cat );
		$xtpl->parse( 'main.catid' );
	}
}

// Kieu tim kiem
foreach( $array_search as $key => $val )
{
	$xtpl->assign( 'STYPE', array(
		'key' => $key,
		'title' => $val,
		'selected' => ( $key == $stype ) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.stype' );
}

// So san pham hien thi
$i = 5;
while( $i <= 1000 )
{
	$xtpl->assign( 'PER_PAGE', array(
		'key' => $i,
		'title' => $i,
		'selected' => ( $i == $per_page ) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.per_page' );
	$i = $i + 5;
}

// Thong tin tim kiem
$xtpl->assign( 'Q', $q );
$xtpl->assign( 'CHECKSESS', md5( session_id() ) );
$xtpl->assign( 'SEARCH_NOTE', sprintf( $lang_module['search_note'], NV_MIN_SEARCH_LENGTH, NV_MAX_SEARCH_LENGTH ) );
$xtpl->assign( 'NV_MAX_SEARCH_LENGTH', NV_MAX_SEARCH_LENGTH );

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$base_url_name = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&per_page=' . $per_page . '&catid=' . $catid . '&stype=' . $stype . '&q=' . $q . '&checkss=' . $checkss . '&ordername=title&order=' . $order2 . '&page=' . $page;
$base_url_publtime = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&per_page=' . $per_page . '&catid=' . $catid . '&stype=' . $stype . '&q=' . $q . '&checkss=' . $checkss . '&ordername=publtime&order=' . $order2 . '&page=' . $page;

// Order
$xtpl->assign( 'BASE_URL_NAME', $base_url_name );
$xtpl->assign( 'BASE_URL_PUBLTIME', $base_url_publtime );

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page . '&amp;catid=' . $catid . '&amp;stype=' . $stype . '&amp;q=' . $q . '&amp;checkss=' . $checkss . '&amp;ordername=' . $ordername . '&amp;order=' . $order;
$ord_sql = ( $ordername == 'title' ? NV_LANG_DATA . '_title' : $ordername ) . ' ' . $order;
$db->sqlreset()->select( 'id, listcatid, user_id, homeimgfile, homeimgthumb, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, status , edittime, publtime, exptime, product_number, product_price, product_discounts, money_unit, username' )->from( $from )->order( $ord_sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
$result = $db->query( $db->sql() );

$theme = $site_mods[$module_name]['theme'] ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$a = 0;

while( list( $id, $listcatid, $admin_id, $homeimgfile, $homeimgthumb, $title, $alias, $status, $edittime, $publtime, $exptime, $product_number, $product_price, $product_discounts, $money_unit, $username ) = $result->fetch( 3 ) )
{
	$publtime = nv_date( 'H:i d/m/y', $publtime );
	$edittime = nv_date( 'H:i d/m/y', $edittime );
	$title = nv_clean60( $title );

	$catid_i = 0;
	if( $catid > 0 )
	{
		$catid_i = $catid;
	}
	else
	{
		$catid_i = $listcatid;
	}

	// Xac dinh anh nho
	if( $homeimgthumb == 1 )//image thumb
	{
		$thumb = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $homeimgfile;
		$imghome = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfile;
	}
	elseif( $homeimgthumb == 2 )//image file
	{
		$imghome = $thumb = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfile;
	}
	elseif( $homeimgthumb == 3 )//image url
	{
		$imghome = $thumb = $homeimgfile;
	}
	elseif( file_exists( NV_ROOTDIR . '/themes/' . $theme . '/images/' . $module_file . '/no-image.jpg' ) )
	{
		$imghome = $thumb = NV_BASE_SITEURL . 'themes/' . $theme . '/images/' . $module_file . '/no-image.jpg';
	}
	else
	{
		$imghome = $thumb = NV_BASE_SITEURL . 'themes/default/images/' . $module_file . '/no-image.jpg';
	}

	$xtpl->assign( 'ROW', array(
		'id' => $id,
		'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
		'title' => $title,
		'publtime' => $publtime,
		'edittime' => $edittime,
		'status' => $lang_module['status_' . $status],
		'admin_id' => ! empty( $username ) ? $username : '',
		'product_number' => $product_number,
		'product_price' => nv_fomart_money( $product_price, ',', '.' ),
		'money_unit' => $money_unit,
		'thumb' => $thumb,
		'imghome' => $imghome,
		'product_discounts' => $product_discounts,
		'link_edit' => nv_link_edit_page( $id ),
		'link_delete' => nv_link_delete_page( $id )
	) );
	$xtpl->parse( 'main.loop' );

	++$a;
}

$array_list_action = array(
	'delete' => $lang_global['delete'],
	'publtime' => $lang_module['publtime'],
	'exptime' => $lang_module['exptime'],
	'addtoblock' => $lang_module['addtoblock']
);

while( list( $catid_i, $title_i ) = each( $array_list_action ) )
{
	$xtpl->assign( 'ACTION', array( 'key' => $catid_i, 'title' => $title_i ) );
	$xtpl->parse( 'main.action' );
}

$xtpl->assign( 'ACTION_CHECKSESS', md5( $global_config['sitekey'] . session_id() ) );

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
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