<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// Default variable
$error = "";
$post['id'] = $post['parentid'] = $post['active_type'] = 0;
$post['type_menu'] = $post['target'] = $post['who_view'] = $post['module_name'] = $post['css'] = "";
$post['groups_view'] = array();
$arr_item = array();
$sp = '&nbsp;&nbsp;&nbsp;';
$sp_title = "";

//
$post['mid'] = $nv_Request->get_int( 'mid', 'get', 0 );
$post['id'] = $nv_Request->get_int( 'id', 'get', 0 );
$post['parentid'] = $nv_Request->get_int( 'parentid', 'get', 0 );

// System groups user
$groups_list = nv_groups_list();
$array_who = array(
	$lang_global['who_view0'],
	$lang_global['who_view1'],
	$lang_global['who_view2'] );
if( ! empty( $groups_list ) )
{
	$array_who[] = $lang_global['who_view3'];
}

if( $post['id'] != 0 )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid` = " . $post['mid'] . " AND `id`=" . $post['id'] . " ORDER BY `id`";
	$result = $db->sql_query( $sql );
	$post = $db->sql_fetchrow( $result );
	$post['groups_view'] = explode( ",", $post['groups_view'] );
	$post['link'] = nv_htmlspecialchars( $post['link'] );
}

if( $post['mid'] != 0 )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid` = " . $post['mid'] . " ORDER BY `order`";
	$result = $db->sql_query( $sql );

	$arr_item[0] = array(
		'key' => 0, //
		'title' => $lang_module['cat0'], //
		'selected' => ( $post['parentid'] == 0 ) ? " selected=\"selected\"" : "" //
			);

	while( $row = $db->sql_fetchrow( $result ) )
	{
		$sp_title = "";
		if( $row['lev'] > 0 )
		{
			for( $i = 1; $i <= $row['lev']; ++$i )
			{
				$sp_title .= '&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}

		$arr_item[$row['id']] = array(
			'key' => $row['id'], //
			'title' => $sp_title . $row['title'], //
			"selected" => ( $post['parentid'] == $row['parentid'] ) ? " selected=\"selected\"" : "" //
				);
	}
}

$list = $list_module;
$list_module = array();
foreach( $list as $key => $title )
{
	$list_module[] = array(
		"key" => $key,
		"title" => $title['module_data'],
		"selected" => ( $key == $post['module_name'] ) ? " selected=\"selected\"" : "",
	);
}

$list_target = array();
foreach( $type_target as $key => $target )
{
	$list_target[] = array(
		"key" => $key,
		"title" => $target,
		"selected" => ( $key == $post['target'] ) ? " selected=\"selected\"" : "",
	);
}

$who_view = $post['who_view'];
$array['who_view'] = array();
foreach( $array_who as $key => $who )
{
	$array['who_view'][] = array(
		'key' => $key, //
		'title' => $who, //
		'selected' => $key == $who_view ? " selected=\"selected\"" : "" //
	);
}

$groups_view = $post['groups_view'];
$array['groups_view'] = array();
if( ! empty( $groups_list ) )
{
	foreach( $groups_list as $key => $title )
	{
		if( ! empty( $groups_view ) )
		{
			$array['groups_view'][] = array(
				'key' => $key, //
				'title' => $title, //
				'checked' => in_array( $key, $groups_view ) ? " checked=\"checked\"" : "" //
			);
		}
		else
		{
			$array['groups_view'][] = array(
				'key' => $key, //
				'title' => $title, //
				'checked' => "" //
			);
		}
	}
}

$arr_menu = nv_list_menu();

// Tao mot menu/Sua menu
if( $nv_Request->isset_request( 'submit1', 'post' ) )
{
	$post = array();
	$gr = array();

	$gr = $nv_Request->get_typed_array( 'groups_view', 'post', '' );
	$post['groups_view'] = implode( ",", $gr );

	$post['id'] = $nv_Request->get_int( 'id', 'post', 0 );
	$post['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
	$post['mid'] = $nv_Request->get_int( 'item_menu', 'post', 0 );
	$post['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$post['link'] = filter_text_input( 'link', 'post', '', 1, 255 );
	$post['note'] = filter_text_input( 'note', 'post', '', 1, 255 );
	$post['module_name'] = filter_text_input( 'module_name', 'post', '', 1, 255 );
	$post['op'] = filter_text_input( 'op', 'post', '', 1, 255 );
	$post['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
	$post['target'] = $nv_Request->get_int( 'target', 'post', 0 );
	$post['active_type'] = $nv_Request->get_int( 'active_type', 'post', 0 );
	$post['css'] = filter_text_input( 'css', 'post', '', 1, 255 );

	$mid_old = $nv_Request->get_int( 'mid', 'post', 0 );
	$pa_old = $nv_Request->get_int( 'pa', 'post', 0 );

	if( empty( $post['title'] ) )
	{
		$error = $lang_module['error_menu_name'];
	}
	elseif( empty( $post['link'] ) )
	{
		$error = $lang_module['error_menu_link'];
	}
	elseif( $post['id'] == 0 )
	{
		if( $db->sql_numrows( $db->sql_query( "SELECT `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `title`=" . $db->dbescape( $post['title'] ) . " AND `parentid`=" . $post['parentid'] . " AND `mid`=" . $post['mid'] ) ) != 0 )
		{
			$error = $lang_module['title_exit_cat'];
		}
		else
		{
			list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid`=" . intval( $post['mid'] ) . " AND `parentid`=" . intval( $post['parentid'] . " AND `mid`=" . $post['mid'] ) ) );
			$weight = intval( $weight ) + 1;

			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_rows` VALUES (
				NULL, 
				" . intval( $post['parentid'] ) . ", 
				" . intval( $post['mid'] ) . ", 
				" . $db->dbescape( $post['title'] ) . ", 
				" . $db->dbescape( $post['link'] ) . ", 
				" . $db->dbescape( $post['note'] ) . ", 
				" . intval( $weight ) . ", 
				0, 0, '', 
				" . intval( $post['who_view'] ) . ", 
				" . $db->dbescape( $post['groups_view'] ) . ", 
				" . $db->dbescape( $post['module_name'] ) . ", 
				" . $db->dbescape( $post['op'] ) . ", 
				" . intval( $post['target'] ) . ", 
				" . $db->dbescape( $post['css'] ) . ", 
				" . intval( $post['active_type'] ) . ", 
				1 
			)";

			if( $db->sql_query_insert_id( $sql ) )
			{
				$db->sql_freeresult();

				nv_fix_cat_order( $post['mid'] );

				$arr_block = array();

				$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid`=" . $post['mid'];
				$result = $db->sql_query( $sql );
				while( $row = $db->sql_fetchrow( $result ) )
				{
					$arr_block[] = $row['id'];
				}

				$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_menu` SET `menu_item`='" . implode( ",", $arr_block ) . "' WHERE `id`=" . $post['mid'];
				$db->sql_query( $sql );

				if( $post['parentid'] != 0 )
				{
					$arr_item_menu = array();
					$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid`=" . $post['mid'] . " AND `parentid`=" . $post['parentid'];
					$result = $db->sql_query( $sql );

					while( $row = $db->sql_fetchrow( $result ) )
					{
						$arr_item_menu[] = $row['id'];
					}

					$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `subitem`= '" . implode( ",", $arr_item_menu ) . "' WHERE `mid`= " . $post['mid'] . " AND `id`=" . $post['parentid'];
					$db->sql_query( $sql );
				}

				nv_del_moduleCache( $module_name );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&mid=" . $post['mid'] . "&parentid=" . $post['parentid'] );
				exit();
			}
			else
			{
				$error = $lang_module['errorsave'];
			}
		}
	}
	else
	{
		if( $db->sql_numrows( $db->sql_query( "SELECT `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `title`=" . $db->dbescape( $post['title'] ) . " AND `parentid`=" . $post['parentid'] . " AND `mid`=" . $post['mid'] . " AND `id` NOT IN (" . $post['id'] . ")" ) ) != 0 )
		{
			$error = $lang_module['title_exit_cat'];
		}
		else
		{
			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET
				`parentid`=" . intval( $post['parentid'] ) . ", 
				`mid`=" . intval( $post['mid'] ) . ",
				`title`=" . $db->dbescape( $post['title'] ) . ",
				`link`=" . $db->dbescape( $post['link'] ) . ",
				`note`=" . $db->dbescape( $post['note'] ) . ",
				`who_view`=" . intval( $post['who_view'] ) . " ,
				`groups_view`=" . $db->dbescape( $post['groups_view'] ) . ",
				`module_name`=" . $db->dbescape( $post['module_name'] ) . ",	
				`op`=" . $db->dbescape( $post['op'] ) . ",
				`target`=" . intval( $post['target'] ) . ",
				`css`=" . $db->dbescape( $post['css'] ) . ", 
				`active_type`=" . intval( $post['active_type'] ) . "
			WHERE `id`=" . intval( $post['id'] );

			if( $db->sql_query( $sql ) )
			{
				if( $pa_old != $post['parentid'] )
				{
					list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid`=" . intval( $post['mid'] ) . " AND `parentid`=" . intval( $post['parentid'] . " " ) ) );
					$weight = intval( $weight ) + 1;

					$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `weight`=" . intval( $weight ) . " WHERE `id`=" . intval( $post['id'] );
					$db->sql_query( $sql );
				}

				nv_fix_cat_order( $post['mid'] );

				if( $post['mid'] != $mid_old )
				{
					nv_fix_cat_order( $mid_old );

					$arr_block = array();
					$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid`=" . $post['mid'];
					$result = $db->sql_query( $sql );
					while( $row = $db->sql_fetchrow( $result ) )
					{
						$arr_block[] = $row['id'];
					}

					$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_menu` SET `menu_item`= '" . implode( ",", $arr_block ) . "' WHERE `id`=" . $post['mid'];
					$db->sql_query( $sql );

					$arr_block = array();
					$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid`= " . $mid_old;
					$result = $db->sql_query( $sql );
					while( $row = $db->sql_fetchrow( $result ) )
					{
						$arr_block[] = $row['id'];
					}

					$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_menu` SET `menu_item`='" . implode( ",", $arr_block ) . "' WHERE `id`=" . $mid_old;
					$db->sql_query( $sql );
				}

				if( $post['parentid'] != 0 )
				{
					$arr_item_menu = array();
					$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid`= " . $post['mid'] . " AND `parentid`=" . $post['parentid'];
					$result = $db->sql_query( $sql );
					while( $row = $db->sql_fetchrow( $result ) )
					{
						$arr_item_menu[] = $row['id'];
					}

					$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `subitem`='" . implode( ",", $arr_item_menu ) . "' WHERE `mid`=" . $post['mid'] . " AND `id`=" . $post['parentid'];
					$db->sql_query( $sql );
				}

				if( $pa_old != 0 )
				{
					$arr_item_menu = array();
					$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid`= " . $mid_old . " AND `parentid`=" . $pa_old;
					$result = $db->sql_query( $sql );
					while( $row = $db->sql_fetchrow( $result ) )
					{
						$arr_item_menu[] = $row['id'];
					}

					$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `subitem`= '" . implode( ",", $arr_item_menu ) . "' WHERE `mid`=" . $mid_old . " AND `id`=" . $pa_old;
					$db->sql_query( $sql );
				}

				nv_del_moduleCache( $module_name );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&mid=" . $post['mid'] . "&parentid=" . $post['parentid'] );
				exit();
			}
			else
			{
				$error = $lang_module['errorsave'];
			}
		}
	}
}

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid` = " . $post['mid'] . " AND `parentid`=" . $post['parentid'] . " ORDER BY `weight`";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

$arr_table = array();
$i = 0;

while( $row = $db->sql_fetchrow( $result ) )
{
	$nu = 0;
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `parentid`=" . $row['id'];
	$result1 = $db->sql_query( $sql );
	$nu = $db->sql_numrows( $result1 );

	$row['sub'] = sizeof( array_filter( explode( ',', $row['subitem'] ) ) );

	$arr_table[$row['id']] = array(
		'id' => $row['id'], //
		'mid' => $row['mid'], //
		'nu' => $nu, //
		'sub' => $row['sub'], //
		'parentid' => $row['parentid'], //
		'link' => nv_htmlspecialchars( $row['link'] ), //
		'weight' => $row['weight'], //
		'title' => $row['title'], //
		'url_title' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add_menu&amp;mid=" . $post['mid'] . "&amp;parentid=" . $row['id'], //
		'edit_url' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add_menu&amp;mid=" . $post['mid'] . "&amp;id=" . $row['id'] . "#edit", //
		'name_block' => $arr_menu[$row['mid']]['title'] //
	);
}

$link_title = "";
if( $post['parentid'] != 0 )
{
	$sql = "SELECT `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $post['parentid'];
	$result = $db->sql_query( $sql );
	list( $parentid ) = $db->sql_fetchrow( $result );
	$link_title = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add_menu&amp;mid=" . $post['mid'] . "&amp;parentid=0";
}

$xtpl = new XTemplate( "add_menu.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add_menu&mid=" . $post['mid'] ) . "&parentid=" . $post['parentid'];
$xtpl->assign( 'DATA', $post );

if( ! empty( $arr_table ) )
{
	$a = 0;
	foreach( $arr_table as $rows )
	{
		$rows['class'] = ( ++$a % 2 == 0 ) ? ' class="second"' : '';
		$xtpl->assign( 'ROW', $rows );

		for( $i = 1; $i <= $num; ++$i )
		{
			$xtpl->assign( 'stt', $i );
			if( $i == $rows['weight'] )
			{
				$xtpl->assign( 'select', 'selected="selected"' );
			}
			else
			{
				$xtpl->assign( 'select', '' );
			}
			$xtpl->parse( 'main.table.loop1.weight' );
		}

		if( $rows['sub'] )
		{
			$xtpl->parse( 'main.table.loop1.sub' );
		}

		$xtpl->parse( 'main.table.loop1' );
	}

	$xtpl->parse( 'main.table' );
}

if( $nv_Request->isset_request( 'item', 'post' ) )
{
	$post['mid'] = $nv_Request->get_int( 'mid', 'post', 0 );
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid`=" . $post['mid'] . " ORDER BY `order`";
	$result = $db->sql_query( $sql );

	$arr_item[0] = array(
		'key' => 0, //
		'title' => $lang_module['cat0'], //
		'selected' => ( $post['parentid'] == 0 ) ? " selected=\"selected\"" : "" //
	);

	while( $row = $db->sql_fetchrow( $result ) )
	{
		$sp_title = "";
		if( $row['lev'] > 0 )
		{
			for( $i = 1; $i <= $row['lev']; ++$i )
			{
				$sp_title .= '&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}
		$arr_item[$row['id']] = array(
			'key' => $row['id'], //
			'title' => $sp_title . $row['title'], //
			"selected" => ( $post['parentid'] == $row['parentid'] ) ? " selected=\"selected\"" : "" //
		);
	}

	foreach( $arr_item as $arr_items )
	{
		$xtpl->assign( 'cat', $arr_items );
		$xtpl->parse( 'main.cat' );
	}

	$contents = $xtpl->text( 'main.cat' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $contents;
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

// Lay chu de cua module duoc chon
if( $nv_Request->isset_request( 'action', 'post' ) )
{
	$module = $nv_Request->get_string( 'module', 'post', '' );
	if( empty( $module ) ) die( $lang_module['add_error_module'] );

	$sql = "SELECT `module_file`, `module_data` FROM `" . NV_MODULES_TABLE . "` WHERE `title`= " . $db->dbescape( $module );
	$result = $db->sql_query( $sql );
	if( $db->sql_numrows( $result ) != 1 ) die( $lang_module['add_error_module_exist'] );

	list( $module_f, $module_d ) = $db->sql_fetchrow( $result );

	if( file_exists( NV_ROOTDIR . '/modules/' . $module_f . '/menu.php' ) )
	{
		$arr_cat = array();
		include ( NV_ROOTDIR . '/modules/' . $module_f . '/menu.php' );

		if( ! empty( $arr_cat ) )
		{
			foreach( $arr_cat as $item )
			{
				$xtpl->assign( 'item', $item );
				$xtpl->parse( 'main.link.item' );
			}
		}

		$xtpl->assign( 'link', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module );
		$xtpl->parse( 'main.link' );

		$contents = $xtpl->text( 'main.link' );

		include ( NV_ROOTDIR . '/includes/header.php' );
		echo $contents;
		include ( NV_ROOTDIR . '/includes/footer.php' );
	}
	die( '&nbsp;' );
}

if( $post['id'] != 0 )
{
	if( $post['op'] != "" )
	{
		$arr_cat = array();

		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $post['module_name'] . "_cat` ORDER BY `catid` ASC";
		if( ( $result = $db->sql_query( $sql ) ) == false )
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $post['module_name'] . "_categories` ORDER BY `catid` ASC";
			if( $result = $db->sql_query( $sql ) == true )
			{
				$result = $db->sql_query( $sql );
				while( $row = $db->sql_fetchrow( $result ) )
				{
					$arr_cat[$row['id']] = array(
						'module' => $post['module_name'], //
						'id' => $row['id'], //
						'title' => $row['title'], //
						'alias' => $row['alias'], //
						'module' => $post['module_name'], //
						"selected" => ( $row['alias'] == $post['op'] ) ? " selected=\"selected\"" : "" //
					);
				}
			}
		}

		else
		{
			$result = $db->sql_query( $sql );
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$arr_cat[$row['catid']] = array(
					'module' => $post['module_name'], //
					'key' => $row['catid'], //
					'title' => $row['title'], //
					'alias' => $row['alias'], //
					"selected" => ( $row['alias'] == $post['op'] ) ? " selected=\"selected\"" : "" //
				);
			}
		}

		if( ! empty( $arr_cat ) )
		{
			foreach( $arr_cat as $item )
			{
				$xtpl->assign( 'item', $item );
				$xtpl->parse( 'main.link.item' );
			}
		}
		$xtpl->parse( 'main.link' );
	}
}

$arr_menu = nv_list_menu();

foreach( $arr_menu as $arr )
{
	$xtpl->assign( 'key', $arr['id'] );
	$xtpl->assign( 'val', $arr['title'] );

	if( $arr['id'] == $post['mid'] )
	{
		$xtpl->assign( 'select', 'selected="selected"' );
	}
	else
	{
		$xtpl->assign( 'select', '' );
	}

	$xtpl->parse( 'main.loop' );
}

foreach( $arr_item as $arr_items )
{
	$xtpl->assign( 'cat', $arr_items );

	if( $post['parentid'] == $arr_items['key'] )
	{
		$xtpl->assign( 'selected', 'selected="selected"' );
	}
	else
	{
		$xtpl->assign( 'selected', '' );
	}

	$xtpl->parse( 'main.cat' );
}

foreach( $list_module as $module )
{
	$xtpl->assign( 'module', $module );
	$xtpl->parse( 'main.module' );
}

foreach( $list_target as $target )
{
	$xtpl->assign( 'target', $target );
	$xtpl->parse( 'main.target' );
}

foreach( $array['who_view'] as $who )
{
	$xtpl->assign( 'WHO_VIEW', $who );
	$xtpl->parse( 'main.who_view' );
}

if( ! empty( $array['groups_view'] ) )
{
	foreach( $array['groups_view'] as $group )
	{
		$xtpl->assign( 'GROUPS_VIEW', $group );
		$xtpl->parse( 'main.group_view_empty.groups_view' );
	}
	$xtpl->parse( 'main.group_view_empty' );
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( $link_title != "" )
{
	$xtpl->assign( 'link_title', $link_title );
	$xtpl->parse( 'main.title' );
}
$link_menu = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
$xtpl->assign( 'link_menu', $link_menu );

// Xuat kieu active menu
for( $i = 0; $i <= 2; ++$i )
{
	$xtpl->assign( 'ACTIVE_TYPE', array(
		'key' => $i, //
		'title' => $lang_module['add_type_active_' . $i], //
		'selected' => $post['active_type'] == $i ? ' selected="selected"' : '' //
	) );
	$xtpl->parse( 'main.active_type' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['add_item'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>