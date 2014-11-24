<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'alias', 'items', 'exptime', 'publtime', 'setting', 'content', 'custom_form', 'keywords', 'del_content', 'cat', 'change_cat', 'list_cat', 'del_cat', 'block', 'blockcat', 'del_block_cat', 'list_block_cat', 'chang_block_cat', 'change_block', 'list_block', 'prounit', 'delunit', 'order', 'or_del', 'or_view', 'money', 'delmoney', 'active_pay', 'payport', 'changepay', 'actpay', 'docpay', 'group', 'del_group', 'list_group', 'change_group', 'getcatalog', 'getgroup', 'discounts', 'view', 'tags', 'tagsajax' );

$array_viewcat_full = array(
	'view_home_cat' => $lang_module['view_home_cat'],
	'viewcat_page_list' => $lang_module['viewcat_page_list'],
	'viewcat_page_gird' => $lang_module['viewcat_page_gird']
);
$array_viewcat_nosub = array( 'viewcat_page_list' => $lang_module['viewcat_page_list'], 'viewcat_page_gird' => $lang_module['viewcat_page_gird'] );

define( 'NV_IS_FILE_ADMIN', true );

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/site.functions.php';

/**
 * nv_fix_cat_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_cat_order( $parentid = 0, $order = 0, $lev = 0 )
{
	global $db, $db_config, $module_data;

	$sql = 'SELECT catid, parentid FROM ' . $db_config['prefix'] . '_' . $module_data . '_catalogs WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
	$result = $db->query( $sql );
	$array_cat_order = array();
	while( $row = $result->fetch() )
	{
		$array_cat_order[] = $row['catid'];
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
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_catalogs SET weight=' . $weight . ', sort=' . $order . ', lev=' . $lev . ' WHERE catid=' . $catid_i;
		$db->query( $sql );
		$order = nv_fix_cat_order( $catid_i, $order, $lev );
	}

	$numsubcat = $weight;
	if( $parentid > 0 )
	{
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_catalogs SET numsubcat=' . $numsubcat;
		if( $numsubcat == 0 )
		{
			$sql .= ", subcatid='', viewcat='viewcat_page_list'";
		}
		else
		{
			$sql .= ", subcatid='" . implode( ",", $array_cat_order ) . "'";
		}
		$sql .= ' WHERE catid=' . $parentid;
		$db->query( $sql );
	}
	return $order;
}

/**
 * nv_fix_block_cat()
 *
 * @return
 */
function nv_fix_block_cat()
{
	global $db, $db_config, $module_data;

	$sql = 'SELECT bid FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat ORDER BY weight ASC';
	$weight = 0;
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		++$weight;
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_block_cat SET weight=' . $weight . ' WHERE bid=' . $row['bid'];
		$db->query( $sql );
	}
	$result->closeCursor();
}

/**
 * nv_news_fix_block()
 *
 * @param mixed $bid
 * @param bool $repairtable
 * @return
 */
function nv_news_fix_block( $bid, $repairtable = true )
{
	global $db, $db_config, $module_data;

	$bid = intval( $bid );

	if( $bid > 0 )
	{
		$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_block WHERE bid=' . $bid . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight <= 500 )
			{
				$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE bid=' . $bid . ' AND id=' . $row['id'];
			}
			else
			{
				$sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id=' . $row['id'];
			}
			$db->query( $sql );
		}
		$result->closeCursor();

		if( $repairtable )
		{
			$db->query( 'REPAIR TABLE ' . $db_config['prefix'] . '_' . $module_data . '_block' );
		}
	}
}

/**
 * shops_show_cat_list()
 *
 * @param integer $parentid
 * @return
 */
function shops_show_cat_list( $parentid = 0 )
{
	global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $array_viewcat_full, $array_viewcat_nosub, $global_config, $module_file;

	$xtpl = new XTemplate( 'cat_lists.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );

	if( $parentid > 0 )
	{
		$parentid_i = $parentid;
		$array_cat_title = array();
		$a = 0;

		while( $parentid_i > 0 )
		{
			list( $catid_i, $parentid_i, $title_i ) = $db->query( 'SELECT catid, parentid, ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_catalogs WHERE catid=' . intval( $parentid_i ) )->fetch( 3 );

			$array_cat_title[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&amp;parentid=" . $catid_i . "\"><strong>" . $title_i . "</strong></a>";

			++$a;
		}

		for( $i = $a - 1; $i >= 0; $i-- )
		{
			$xtpl->assign( 'CAT_NAV', $array_cat_title[$i] . ( $i > 0 ? " &raquo; " : "" ) );
			$xtpl->parse( 'main.catnav.loop' );
		}

		$xtpl->parse( 'main.catnav' );
	}

	$sql = 'SELECT catid, parentid, ' . NV_LANG_DATA . '_title, weight, viewcat, numsubcat, inhome, numlinks, newday FROM ' . $db_config['prefix'] . '_' . $module_data . '_catalogs WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
	$result = $db->query( $sql );
	$num = $result->rowCount();

	if( $num > 0 )
	{
		$a = 0;
		$array_inhome = array( $lang_global['no'], $lang_global['yes'] );

		while( list( $catid, $parentid, $title, $weight, $viewcat, $numsubcat, $inhome, $numlinks, $newday ) = $result->fetch( 3 ) )
		{
			$array_viewcat = ( $numsubcat > 0 ) ? $array_viewcat_full : $array_viewcat_nosub;
			if( ! array_key_exists( $viewcat, $array_viewcat ) )
			{
				$viewcat = 'viewcat_page_list';
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_catalogs SET viewcat= :viewcat WHERE catid=' . $catid );
				$stmt->bindParam( ':viewcat', $viewcat, PDO::PARAM_STR );
				$stmt->execute();
			}

			$xtpl->assign( 'ROW', array(
				'catid' => $catid,
				'cat_link' => NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;parentid=' . $catid,
				'title' => $title,
				'numsubcat' => $numsubcat > 0 ? ' <span style="color:#FF0101;">(' . $numsubcat . ')</span>' : '',
				'parentid' => $parentid
			) );

			for( $i = 1; $i <= $num; $i++ )
			{
				$xtpl->assign( 'WEIGHT', array(
					'key' => $i,
					'title' => $i,
					'selected' => $i == $weight ? ' selected=\'selected\'' : ''
				) );
				$xtpl->parse( 'main.data.loop.weight' );
			}

			foreach( $array_inhome as $key => $val )
			{
				$xtpl->assign( 'INHOME', array(
					'key' => $key,
					'title' => $val,
					'selected' => $key == $inhome ? ' selected=\'selected\'' : ''
				) );
				$xtpl->parse( 'main.data.loop.inhome' );
			}

			foreach( $array_viewcat as $key => $val )
			{
				$xtpl->assign( 'VIEWCAT', array(
					'key' => $key,
					'title' => $val,
					'selected' => $key == $viewcat ? ' selected=\'selected\'' : ''
				) );
				$xtpl->parse( 'main.data.loop.viewcat' );
			}

			for( $i = 0; $i <= 10; $i++ )
			{
				$xtpl->assign( 'NUMLINKS', array(
					'key' => $i,
					'title' => $i,
					'selected' => $i == $numlinks ? ' selected=\'selected\'' : ''
				) );
				$xtpl->parse( 'main.data.loop.numlinks' );
			}

			for( $i = 0; $i <= 30; $i++ )
			{
				$xtpl->assign( 'NEWDAY', array(
					'key' => $i,
					'title' => $i,
					'selected' => $i == $newday ? ' selected=\'selected\'' : ''
				) );
				$xtpl->parse( 'main.data.loop.newday' );
			}

			$xtpl->parse( 'main.data.loop' );
			++$a;
		}

		$xtpl->parse( 'main.data' );
	}

	$result->closeCursor();
	unset( $sql, $result );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_fix_group_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_group_order( $parentid = 0, $sort = 0, $lev = 0 )
{
	global $db, $db_config, $module_data;

	$sql = 'SELECT groupid, parentid FROM ' . $db_config['prefix'] . '_' . $module_data . '_group WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
	$result = $db->query( $sql );
	$array_group_order = array();
	while( $row = $result->fetch() )
	{
		$array_group_order[] = $row['groupid'];
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
	foreach( $array_group_order as $groupid_i )
	{
		++$sort;
		++$weight;

		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group SET weight=' . $weight . ', sort=' . $sort . ', lev=' . $lev . ' WHERE groupid=' . $groupid_i;
		$db->query( $sql );

		$sort = nv_fix_group_order( $groupid_i, $sort, $lev );
	}

	$numsubgroup = $weight;

	if( $parentid > 0 )
	{
		$sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_group SET numsubgroup=" . $numsubgroup;
		if( $numsubgroup == 0 )
		{
			$sql .= ",subgroupid='', viewgroup='viewcat_page_list'";
		}
		else
		{
			$sql .= ",subgroupid='" . implode( ",", $array_group_order ) . "'";
		}
		$sql .= " WHERE groupid=" . intval( $parentid );
		$db->query( $sql );
	}
	return $sort;
}

/**
 * shops_show_group_list()
 *
 * @param integer $parentid
 * @return
 */
function shops_show_group_list( $parentid = 0 )
{
	global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $array_viewcat_nosub, $module_file, $global_config;

	$xtpl = new XTemplate( "group_lists.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );

	if( $parentid > 0 )
	{
		$parentid_i = $parentid;
		$array_group_title = array();
		$a = 0;
		while( $parentid_i > 0 )
		{
			list( $groupid_i, $parentid_i, $title_i ) = $db->query( "SELECT groupid, parentid, " . NV_LANG_DATA . "_title FROM " . $db_config['prefix'] . "_" . $module_data . "_group WHERE groupid=" . intval( $parentid_i ) )->fetch( 3 );

			$array_group_title[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=group&amp;parentid=" . $groupid_i . "\"><strong>" . $title_i . "</strong></a>";
			++$a;
		}

		for( $i = $a - 1; $i >= 0; $i-- )
		{
			$xtpl->assign( 'GROUP_NAV', $array_group_title[$i] . ( $i > 0 ? " &raquo; " : "" ) );
			$xtpl->parse( 'main.groupnav.loop' );
		}

		$xtpl->parse( 'main.catnav' );
	}

	$sql = "SELECT groupid, parentid, " . NV_LANG_DATA . "_title, weight, viewgroup, numsubgroup, inhome, in_order FROM " . $db_config['prefix'] . "_" . $module_data . "_group WHERE parentid = '" . $parentid . "' ORDER BY weight ASC";
	$result = $db->query( $sql );
	$num = $result->rowCount();

	if( $num > 0 )
	{
		$a = 0;
		$array_yes_no = array( $lang_global['no'], $lang_global['yes'] );

		while( list( $groupid, $parentid, $title, $weight, $viewgroup, $numsubgroup, $inhome, $in_order ) = $result->fetch( 3 ) )
		{
			$array_viewgroup = $array_viewcat_nosub;
			if( ! array_key_exists( $viewgroup, $array_viewgroup ) )
			{
				$viewgroup = "viewcat_page_list";
				$stmt = $db->prepare( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_group SET viewgroup= :viewgroup WHERE groupid=" . intval( $groupid ) );
				$stmt->bindParam( ':viewgroup', $viewgroup, PDO::PARAM_STR );
				$stmt->execute();
			}

			$xtpl->assign( 'ROW', array(
				"groupid" => $groupid,
				"group_link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=group&amp;parentid=" . $groupid,
				"title" => $title,
				"numsubgroup" => $numsubgroup > 0 ? " <span style=\"color:#FF0101;\">(" . $numsubgroup . ")</span>" : "",
				"parentid" => $parentid
			) );

			for( $i = 1; $i <= $num; $i++ )
			{
				$xtpl->assign( 'OPTION', array(
					"key" => $i,
					"title" => $i,
					"selected" => $i == $weight ? " selected=\"selected\"" : ""
				) );
				$xtpl->parse( 'main.data.loop.weight' );
			}

			foreach( $array_yes_no as $key => $val )
			{
				$xtpl->assign( 'OPTION', array(
					"key" => $key,
					"title" => $val,
					"selected" => $key == $inhome ? " selected=\"selected\"" : ""
				) );
				$xtpl->parse( 'main.data.loop.inhome' );

				$xtpl->assign( 'OPTION', array(
					"key" => $key,
					"title" => $val,
					"selected" => $key == $in_order ? " selected=\"selected\"" : ""
				) );
				$xtpl->parse( 'main.data.loop.in_order' );
			}

			foreach( $array_viewgroup as $key => $val )
			{
				$xtpl->assign( 'OPTION', array(
					"key" => $key,
					"title" => $val,
					"selected" => $key == $viewgroup ? " selected=\"selected\"" : ""
				) );
				$xtpl->parse( 'main.data.loop.viewgroup' );
			}

			$xtpl->parse( 'main.data.loop' );
			++$a;
		}

		$xtpl->parse( 'main.data' );
	}

	$result->closeCursor();
	unset( $sql, $result );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_show_block_cat_list()
 *
 * @return
 */
function nv_show_block_cat_list()
{
	global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $global_config, $module_file;

	$xtpl = new XTemplate( "block_cat_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', 'blockcat' );

	$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_block_cat ORDER BY weight ASC";
	$result = $db->query( $sql );

	$num = $result->rowCount();

	if( $num > 0 )
	{
		$a = 0;
		$array_adddefault = array( $lang_global['no'], $lang_global['yes'] );

		while( $row = $result->fetch() )
		{
			$numnews = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_block WHERE bid=" . $row['bid'] )->fetchColumn();

			$xtpl->assign( 'ROW', array(
				"bid" => $row['bid'],
				"numnews" => $numnews ? " (" . $numnews . " " . $lang_module['num_product'] . ")" : "",
				"title" => $row[NV_LANG_DATA . '_title']
			) );

			for( $i = 1; $i <= $num; $i++ )
			{
				$xtpl->assign( 'WEIGHT', array(
					"key" => $i,
					"title" => $i,
					"selected" => $i == $row['weight'] ? " selected=\"selected\"" : ""
				) );
				$xtpl->parse( 'main.loop.weight' );
			}

			foreach( $array_adddefault as $key => $val )
			{
				$xtpl->assign( 'ADDDEFAULT', array(
					"key" => $key,
					"title" => $val,
					"selected" => $key == $row['adddefault'] ? " selected=\"selected\"" : ""
				) );
				$xtpl->parse( 'main.loop.adddefault' );
			}

			$xtpl->parse( 'main.loop' );
			++$a;
		}
	}
	$result->closeCursor();

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * shops_show_discounts_list()
 *
 * @return
 */
function shops_show_discounts_list()
{
	global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $global_config, $module_file;

	$xtpl = new XTemplate( "discounts_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', 'blockcat' );

	$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_discounts ORDER BY weight ASC";
	$result = $db->query( $sql );

	$num = $result->rowCount();

	if( $num > 0 )
	{
		$a = 0;
		$array_adddefault = array( $lang_global['no'], $lang_global['yes'] );

		while( $row = $result->fetch() )
		{
			$numnews = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_block WHERE bid=" . $row['bid'] )->fetchColumn();

			$xtpl->assign( 'ROW', array(
				"bid" => $row['bid'],
				"numnews" => $numnews ? " (" . $numnews . " " . $lang_module['num_product'] . ")" : "",
				"title" => $row[NV_LANG_DATA . '_title']
			) );

			for( $i = 1; $i <= $num; $i++ )
			{
				$xtpl->assign( 'WEIGHT', array(
					"key" => $i,
					"title" => $i,
					"selected" => $i == $row['weight'] ? " selected=\"selected\"" : ""
				) );
				$xtpl->parse( 'main.loop.weight' );
			}

			foreach( $array_adddefault as $key => $val )
			{
				$xtpl->assign( 'ADDDEFAULT', array(
					"key" => $key,
					"title" => $val,
					"selected" => $key == $row['adddefault'] ? " selected=\"selected\"" : ""
				) );
				$xtpl->parse( 'main.loop.adddefault' );
			}

			$xtpl->parse( 'main.loop' );
			++$a;
		}
	}
	$result->closeCursor();

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_show_block_list()
 *
 * @param mixed $bid
 * @return
 */
function nv_show_block_list( $bid )
{
	global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $global_array_cat, $global_config, $module_file;

	$xtpl = new XTemplate( "block_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'BID', $bid );

	$sql = "SELECT t1.id, t1.listcatid, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t2.weight FROM " . $db_config['prefix'] . "_" . $module_data . "_rows as t1 INNER JOIN " . $db_config['prefix'] . "_" . $module_data . "_block AS t2 ON t1.id = t2.id WHERE t2.bid= " . $bid . " AND t1.inhome='1' ORDER BY t2.weight ASC";

	$result = $db->query( $sql );
	$num = $result->rowCount();
	$a = 0;

	while( list( $id, $listcatid, $title, $alias, $weight ) = $result->fetch( 3 ) )
	{
		$xtpl->assign( 'ROW', array(
			"id" => $id,
			"title" => $title,
			"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id
		) );

		for( $i = 1; $i <= $num; $i++ )
		{
			$xtpl->assign( 'WEIGHT', array(
				"key" => $i,
				"title" => $i,
				"selected" => $i == $weight ? " selected=\"selected\"" : ""
			) );
			$xtpl->parse( 'main.loop.weight' );
		}

		$xtpl->parse( 'main.loop' );
		++$a;
	}
	$result->closeCursor();

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * drawselect_number()
 *
 * @param string $select_name
 * @param integer $number_start
 * @param integer $number_end
 * @param integer $number_curent
 * @param string $func_onchange
 * @return
 */
function drawselect_number( $select_name = "", $number_start = 0, $number_end = 1, $number_curent = 0, $func_onchange = "" )
{
	$html = "<select class=\"form-control\" name=\"" . $select_name . "\" onchange=\"" . $func_onchange . "\">";
	for( $i = $number_start; $i < $number_end; $i++ )
	{
		$select = ( $i == $number_curent ) ? "selected=\"selected\"" : "";
		$html .= "<option value=\"" . $i . "\"" . $select . ">" . $i . "</option>";
	}
	$html .= "</select>";
	return $html;
}

/**
 * GetCatidInChild()
 *
 * @param mixed $catid
 * @return
 */
function GetCatidInChild( $catid )
{
	global $global_array_cat, $array_cat;

	$array_cat[] = $catid;

	if( $global_array_cat[$catid]['parentid'] > 0 )
	{
		$array_cat[] = $global_array_cat[$catid]['parentid'];
		$array_cat_temp = GetCatidInChild( $global_array_cat[$catid]['parentid'] );
		foreach( $array_cat_temp as $catid_i )
		{
			$array_cat[] = $catid_i;
		}
	}
	return array_unique( $array_cat );
}

/**
 * nv_show_custom_form()
 *
 * @param mixed $form
 * @param mixed $array_custom
 * @param mixed $array_custom_lang
 * @return
 */
function nv_show_custom_form( $form, $array_custom, $array_custom_lang )
{
	global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $global_array_cat, $global_config, $module_file;

	$xtpl = new XTemplate( 'cat_form_' . $form . '.tpl', NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );

	if( preg_match( '/^[a-zA-Z0-9\-\_]+$/', $form ) and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin/cat_form_' . $form . '.php' ) )
	{
		require_once NV_ROOTDIR . '/modules/' . $module_file . '/admin/cat_form_' . $form . '.php';
	}

	$xtpl->assign( 'CUSTOM', $array_custom );
	$xtpl->assign( 'CUSTOM_LANG', $array_custom_lang );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}