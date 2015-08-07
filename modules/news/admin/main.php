<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['content_list'];
$stype = $nv_Request->get_string( 'stype', 'get', '-' );
$sstatus = $nv_Request->get_int( 'sstatus', 'get', -1 );
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

$q = $nv_Request->get_title( 'q', 'get', '' );
$q = str_replace( '+', ' ', $q );
$qhtml = nv_htmlspecialchars( $q );
$ordername = $nv_Request->get_string( 'ordername', 'get', 'publtime' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'asc' ? 'asc' : 'desc';
$val_cat_content = array();
$val_cat_content[] = array(
	'value' => 0,
	'selected' => ( $catid == 0 ) ? ' selected="selected"' : '',
	'title' => $lang_module['search_cat_all']
);
$array_cat_view = array();
$check_declined = false;
foreach( $global_array_cat as $catid_i => $array_value )
{
	$lev_i = $array_value['lev'];
	$check_cat = false;
	if( defined( 'NV_IS_ADMIN_MODULE' ) )
	{
		$check_cat = true;
	}
	elseif( isset( $array_cat_admin[$admin_id][$catid_i] ) )
	{
		$_cat_admin_i = $array_cat_admin[$admin_id][$catid_i];
		if( $_cat_admin_i['admin'] == 1 )
		{
			$check_cat = true;
			$check_declined = true;
		}
		elseif( $_cat_admin_i['add_content'] == 1 )
		{
			$check_cat = true;
		}
		elseif( $_cat_admin_i['pub_content'] == 1 or $_cat_admin_i['app_content'] == 1 )
		{
			$check_cat = true;
			$check_declined = true;
		}
		elseif( $_cat_admin_i['edit_content'] == 1 )
		{
			$check_cat = true;
		}
		elseif( $_cat_admin_i['del_content'] == 1 )
		{
			$check_cat = true;
		}
	}

	if( $check_cat )
	{
		$xtitle_i = '';
		if( $lev_i > 0 )
		{
			$xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
			for( $i = 1; $i <= $lev_i; ++$i )
			{
				$xtitle_i .= '---';
			}
			$xtitle_i .= '>&nbsp;';
		}
		$xtitle_i .= $array_value['title'];
		$sl = '';
		if( $catid_i == $catid )
		{
			$sl = ' selected="selected"';
		}
		$val_cat_content[] = array(
			'value' => $catid_i,
			'selected' => $sl,
			'title' => $xtitle_i
		);
		$array_cat_view[] = $catid_i;
	}
}
if( ! defined( 'NV_IS_ADMIN_MODULE' ) and $catid > 0 and !in_array( $catid, $array_cat_view ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main' );
	die();
}
$array_search = array(
	'-' => '---' . $lang_module['search_type'] . '---',
	'title' => $lang_module['search_title'],
	'bodytext' => $lang_module['search_bodytext'],
	'author' => $lang_module['search_author'],
	'admin_id' => $lang_module['search_admin'],
	'sourcetext' => $lang_module['sources']
);
$array_in_rows = array(
	'title',
	'bodytext',
	'author',
	'sourcetext'
);
$array_in_ordername = array(
	'title',
	'publtime',
	'exptime',
	'hitstotal',
	'hitscm'
);
$array_status_view = array(
	'-' => '---' . $lang_module['search_status'] . '---',
	'5' => $lang_module['status_5'],
	'1' => $lang_module['status_1'],
	'0' => $lang_module['status_0'],
	'6' => $lang_module['status_6'],
	'4' => $lang_module['status_4'],
	'2' => $lang_module['status_2'],
	'3' => $lang_module['status_3']
);
$array_status_class = array(
	'5' => 'danger',
	'1' => '',
	'0' => 'warning',
	'6' => 'warning',
	'4' => 'info',
	'2' => 'success',
	'3' => 'danger'
);

$_permission_action = array();
$array_list_action = array(
	'delete' => $lang_global['delete'],
	're-published' => $lang_module['re_published'],
	'publtime' => $lang_module['publtime'],
	'stop' => $lang_module['status_0'],
	'waiting' => $lang_module['status_action_0']
);

// Chuyen sang cho duyet
if( defined( 'NV_IS_ADMIN_MODULE' ) )
{
	$array_list_action['declined'] = $lang_module['declined'];
	$array_list_action['block'] = $lang_module['addtoblock'];
	$array_list_action['addtotopics'] = $lang_module['addtotopics'];
	$array_list_action['move'] = $lang_module['move'];
}
elseif( $check_declined ) //Neu co quyen duyet bai thi
{
	$array_list_action['declined'] = $lang_module['declined'];
}

if( ! in_array( $stype, array_keys( $array_search ) ) )
{
	$stype = '-';
}
if( $sstatus < 0 or $sstatus > 10 )
{
	$sstatus = -1;
}
if( ! in_array( $ordername, array_keys( $array_in_ordername ) ) )
{
	$ordername = 'id';
}
if( $catid == 0 )
{
	$from = NV_PREFIXLANG . '_' . $module_data . '_rows r';
}
else
{
	$from = NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' r';
}
$where = '';
$page = $nv_Request->get_int( 'page', 'get', 1 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
if( $checkss == md5( session_id() ) )
{
	if( $stype == 'bodytext' )
	{
		$from .= ' INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext c ON (r.id=c.id)';
		$where = " c.bodytext LIKE '%" . $db->dblikeescape( $q ) . "%'";
	}
	elseif( $stype == "author" or $stype == "title" )
	{
		$where = " r." . $stype . " LIKE '%" . $db->dblikeescape( $qhtml ) . "%'";
	}
	elseif( $stype == 'sourcetext' )
	{
		$qurl = $q;
		$url_info = @parse_url( $qurl );
		if( isset( $url_info['scheme'] ) and isset( $url_info['host'] ) )
		{
			$qurl = $url_info['scheme'] . '://' . $url_info['host'];
		}
		$where = " r.sourceid IN (SELECT sourceid FROM " . NV_PREFIXLANG . "_" . $module_data . "_sources WHERE title like '%" . $db->dblikeescape( $q ) . "%' OR link like '%" . $db->dblikeescape( $qurl ) . "%')";
	}
	elseif( $stype == 'admin_id' )
	{
		$where = " (u.username LIKE '%" . $db->dblikeescape( $qhtml ) . "%' OR u.first_name LIKE '%" . $db->dblikeescape( $qhtml ) . "%')";
	}
	elseif( ! empty( $q ) )
	{
		$from .= ' INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext c ON (r.id=c.id)';
		$arr_from = array();
		foreach( $array_in_rows as $key => $val )
		{
			$arr_from[] = "(r." . $val . " LIKE '%" . $db->dblikeescape( $q ) . "%')";
		}
		$where = " r.author LIKE '%" . $db->dblikeescape( $qhtml ) . "%'
			OR r.title LIKE '%" . $db->dblikeescape( $qhtml ) . "%'
			OR c.bodytext LIKE '%" . $db->dblikeescape( $q ) . "%'
			OR u.username LIKE '%" . $db->dblikeescape( $qhtml ) . "%'
			OR u.first_name LIKE '%" . $db->dblikeescape( $qhtml ) . "%'";
	}
	if( $sstatus != -1 )
	{
		if( $where == '' )
		{
			$where = ' r.status = ' . $sstatus;
		}
		else
		{
			$where .= ' AND r.status = ' . $sstatus;
		}
	}
}
$from .= ' LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' u ON r.admin_id=u.userid';
if( ! defined( 'NV_IS_ADMIN_MODULE' ) )
{
	$from_catid = array();
	foreach( $array_cat_view as $catid_i )
	{
		$from_catid[] = "r.listcatid = '" . $catid_i . "'";
		$from_catid[] = "r.listcatid like '" . $catid_i . ",%'";
		$from_catid[] = "r.listcatid like '%," . $catid_i . ",%'";
		$from_catid[] = "r.listcatid like '%," . $catid_i . "'";
	}
	$where .= ( empty( $where ) ) ? ' (' . implode( ' OR ', $from_catid ) . ')' : ' AND (' . implode( ' OR ', $from_catid ) . ')';
}
$link_i = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=Other';
$global_array_cat[0] = array(
	'catid' => 0,
	'parentid' => 0,
	'title' => 'Other',
	'alias' => 'Other',
	'link' => $link_i,
	'viewcat' => 'viewcat_page_new',
	'subcatid' => 0,
	'numlinks' => 3,
	'description' => '',
	'keywords' => ''
);
$search_type = array();
foreach( $array_search as $key => $val )
{
	$search_type[] = array(
		'key' => $key,
		'value' => $val,
		'selected' => ( $key == $stype ) ? ' selected="selected"' : ''
	);
}

for ($i=0; $i  <= 10; $i++)
{
	$sl = ( $i == $sstatus ) ? ' selected="selected"' : '';
	$search_status[] = array(
		'key' => $i,
		'value' => $lang_module['status_' . $i],
		'selected' => $sl
	);
}

$i = 5;
$search_per_page = array();
while( $i <= 500 )
{
	$search_per_page[] = array(
		'page' => $i,
		'selected' => ( $i == $per_page ) ? ' selected="selected"' : ''
	);
	$i = $i + 5;
}
$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$ord_sql = ' r.' . $ordername . ' ' . $order;

$db->sqlreset()->select( 'COUNT(*)' )->from( $from )->where( $where );
$num_items = $db->query( $db->sql() )->fetchColumn();

$db->select( 'r.id, r.catid, r.listcatid, r.admin_id, r.title, r.alias, r.status , r.publtime, r.exptime, r.hitstotal, r.hitscm, u.username' )->order( 'r.' . $ordername . ' ' . $order )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
$result = $db->query( $db->sql() );

$data = $array_ids = array();
while( list( $id, $catid_i, $listcatid, $post_id, $title, $alias, $status, $publtime, $exptime, $hitstotal, $hitscm, $username ) = $result->fetch( 3 ) )
{
	$publtime = nv_date( 'H:i d/m/y', $publtime );
	$title = nv_clean60( $title );

	if( $catid > 0 )
	{
		$catid_i = $catid;
	}

	$check_permission_edit = $check_permission_delete = false;

	if( defined( 'NV_IS_ADMIN_MODULE' ) )
	{
		$check_permission_edit = $check_permission_delete = true;
	}
	else
	{
		$array_temp = explode( ',', $listcatid );
		$check_edit = $check_del = 0;

		foreach( $array_temp as $catid_i )
		{
			if( isset( $array_cat_admin[$admin_id][$catid_i] ) )
			{
				if( $array_cat_admin[$admin_id][$catid_i]['admin'] == 1 )
				{
					++$check_edit;
					++$check_del;
					$_permission_action['publtime'] = true;
					$_permission_action['re-published'] = true;
					$_permission_action['exptime'] = true;
					$_permission_action['declined'] = true;
				}
				else
				{
					if( $array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1 )
					{
						++$check_edit;
						if( $status )
						{
							$_permission_action['exptime'] = true;
						}
					}
					elseif( $array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and $status == 0 )
					{
						++$check_edit;
						$_permission_action['publtime'] = true;
						$_permission_action['re-published'] = true;
					}
					elseif( ( $status == 0 or $status == 4 or $status == 5 ) and $post_id == $admin_id )
					{
						++$check_edit;
						$_permission_action['waiting'] = true;
					}

					if( $array_cat_admin[$admin_id][$catid_i]['del_content'] == 1 )
					{
						++$check_del;
					}
					elseif( ( $status == 0 or $status == 4  or $status == 5 ) and $post_id == $admin_id )
					{
						++$check_del;
						$_permission_action['waiting'] = true;
					}
				}
			}
		}

		if( $check_edit == sizeof( $array_temp ) )
		{
			$check_permission_edit = true;
		}

		if( $check_del == sizeof( $array_temp ) )
		{
			$check_permission_delete = true;
		}
	}

	$admin_funcs = array();
	if( $check_permission_edit )
	{
		$admin_funcs[] = nv_link_edit_page( $id );
	}
	if( $check_permission_delete )
	{
		$admin_funcs[] = nv_link_delete_page( $id );
		$_permission_action['delete'] = true;
	}

	$data[$id] = array(
		'id' => $id,
		'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
		'title' => $title,
		'publtime' => $publtime,
		'status' => $lang_module['status_' . $status],
		'class' => $array_status_class[$status],
		'username' => $username,
		'hitstotal' => number_format( $hitstotal, 0, ',', '.' ),
		'hitscm' => number_format( $hitscm, 0, ',', '.' ),
		'numtags' => 0,
		'feature' => implode( '&nbsp;-&nbsp;', $admin_funcs )
	);

	$array_ids[] = $id;
}

// Lay so tags
if( ! empty( $array_ids ) )
{
	$db->sqlreset()->select( 'COUNT(*) AS numtags, id' )->from( NV_PREFIXLANG . '_' . $module_data . '_tags_id' )->where( 'id IN( ' . implode( ',', $array_ids ) . ' )' )->group( 'id' );
	$result = $db->query( $db->sql() );

	while( list( $numtags, $id ) = $result->fetch( 3 ) )
	{
		$data[$id]['numtags'] = $numtags;
	}
}

$base_url_mod = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page . '&amp;catid=' . $catid . '&amp;stype=' . $stype . '&amp;q=' . $q . '&amp;checkss=' . $checkss;
$base_url_id = $base_url_mod . '&amp;ordername=id&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_name = $base_url_mod . '&amp;ordername=title&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_publtime = $base_url_mod . '&amp;ordername=publtime&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_exptime = $base_url_mod . '&amp;ordername=exptime&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_hitstotal = $base_url_mod . '&amp;ordername=hitstotal&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_hitscm = $base_url_mod . '&amp;ordername=hitscm&amp;order=' . $order2 . '&amp;page=' . $page;

$base_url = $base_url_mod . '&amp;sstatus=' . $sstatus . '&amp;ordername=' . $ordername . '&amp;order=' . $order;
$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'Q', $qhtml );
$xtpl->assign( 'CHECKSS', md5( session_id() ) );
$xtpl->assign( 'SITEKEY', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'base_url_id', $base_url_id );
$xtpl->assign( 'base_url_name', $base_url_name );
$xtpl->assign( 'base_url_publtime', $base_url_publtime );
$xtpl->assign( 'base_url_exptime', $base_url_exptime );
$xtpl->assign( 'base_url_hitstotal', $base_url_hitstotal );
$xtpl->assign( 'base_url_hitscm', $base_url_hitscm );

foreach( $val_cat_content as $cat_content )
{
	$xtpl->assign( 'CAT_CONTENT', $cat_content );
	$xtpl->parse( 'main.cat_content' );
}

foreach( $search_type as $search_t )
{
	$xtpl->assign( 'SEARCH_TYPE', $search_t );
	$xtpl->parse( 'main.search_type' );
}

foreach( $search_per_page as $s_per_page )
{
	$xtpl->assign( 'SEARCH_PER_PAGE', $s_per_page );
	$xtpl->parse( 'main.s_per_page' );
}

foreach( $search_status as $status_view )
{
	$xtpl->assign( 'SEARCH_STATUS', $status_view );
	$xtpl->parse( 'main.search_status' );
}

foreach( $data as $row )
{
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.loop' );
}

while( list( $action_i, $title_i ) = each( $array_list_action ) )
{
	if( defined( 'NV_IS_ADMIN_MODULE' ) || isset( $_permission_action[$action_i] ) )
	{
		$action_assign = array(
			'value' => $action_i,
			'title' => $title_i
		);
		$xtpl->assign( 'ACTION', $action_assign );
		$xtpl->parse( 'main.action' );
	}
}

if( !empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';