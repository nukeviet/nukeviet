<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// Default variable
$error = '';
$post['active_type'] = 0;
$post['type_menu'] = $post['target'] = $post['module_name'] = $post['css'] = '';
$post['groups_view'] = array( 6 );
$arr_item = array();
$sp = '&nbsp;&nbsp;&nbsp;';
$sp_title = '';

$post['mid'] = $nv_Request->get_int( 'mid', 'post,get', 0 );
$post['id'] = $nv_Request->get_int( 'id', 'get', 0 );
$post['parentid'] = $nv_Request->get_int( 'parentid', 'get', 0 );
if( empty( $post['mid'] ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

// System groups user
$groups_list = nv_groups_list();

if( $post['id'] != 0 )
{
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = ' . $post['mid'] . ' AND id=' . $post['id'] . ' ORDER BY id';
	$result = $db->query( $sql );
	$post = $result->fetch();
	$post['groups_view'] = explode( ',', $post['groups_view'] );
	$post['link'] = nv_htmlspecialchars( $post['link'] );
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = ' . $post['mid'] . ' ORDER BY sort';
$result = $db->query( $sql );

$arr_item[0] = array(
	'key' => 0,
	'parentid' => 0,
	'title' => $lang_module['cat0'],
	'selected' => ($post['parentid'] == 0) ? ' selected="selected"' : ''
);
$array_all_item = array();
while( $row = $result->fetch() )
{
	$sp_title = '';
	if( $row['lev'] > 0 )
	{
		for( $i = 1; $i <= $row['lev']; ++$i )
		{
			$sp_title .= $sp;
		}
	}

	$arr_item[$row['id']] = array(
		'key' => $row['id'],
		'parentid' => $row['parentid'],
		'title' => $sp_title . $row['title'],
		'selected' => ($post['parentid'] == $row['parentid']) ? ' selected="selected"' : ''
	);
	$array_all_item[$row['id']] = $row;
}

$list_module = array();
unset( $site_mods['menu'], $site_mods['comment'] );
foreach( $site_mods as $key => $title )
{
	$list_module[] = array(
		'key' => $key,
		'title' => $title['module_data'],
		'selected' => ($key == $post['module_name']) ? ' selected="selected"' : ''
	);
}

$list_target = array();
foreach( $type_target as $key => $target )
{
	$list_target[] = array(
		'key' => $key,
		'title' => $target,
		'selected' => ($key == $post['target']) ? ' selected="selected"' : ''
	);
}

$groups_view = $post['groups_view'];
$array['groups_view'] = array();
foreach( $groups_list as $key => $title )
{
	if( !empty( $groups_view ) )
	{
		$array['groups_view'][] = array(
			'key' => $key,
			'title' => $title,
			'checked' => in_array( $key, $groups_view ) ? ' checked="checked"' : ''
		);
	}
	else
	{
		$array['groups_view'][] = array(
			'key' => $key,
			'title' => $title,
			'checked' => ''
		);
	}
}

$arr_menu = nv_list_menu();

// Tao menu/Sua menu
if( $nv_Request->isset_request( 'submit1', 'post' ) )
{
	$post = array();

	$_groups_post = $nv_Request->get_array( 'groups_view', 'post', array() );
	$post['groups_view'] = !empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

	$post['id'] = $nv_Request->get_int( 'id', 'post', 0 );
	$post['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
	$post['mid'] = $nv_Request->get_int( 'item_menu', 'post', 0 );
	$post['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	$post['link'] = $nv_Request->get_string( 'link', 'post', '', 1, 255 );
	$post['note'] = nv_substr( $nv_Request->get_title( 'note', 'post', '', 1 ), 0, 255 );
	$post['module_name'] = nv_substr( $nv_Request->get_title( 'module_name', 'post', '', 1 ), 0, 255 );
	$post['op'] = nv_substr( $nv_Request->get_title( 'op', 'post', '', 1 ), 0, 255 );
	$post['target'] = $nv_Request->get_int( 'target', 'post', 0 );
	$post['active_type'] = $nv_Request->get_int( 'active_type', 'post', 0 );
	$post['css'] = nv_substr( $nv_Request->get_title( 'css', 'post', '', 1 ), 0, 255 );

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
		$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE title= :title AND parentid=' . $post['parentid'] . ' AND mid=' . $post['mid'] );
		$stmt->bindParam( ':title', $post['title'], PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() )
		{
			$error = $lang_module['title_exit_cat'];
		}
		else
		{
			$weight = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . intval( $post['mid'] ) . ' AND parentid=' . intval( $post['parentid'] . ' AND mid=' . $post['mid'] ) )->fetchColumn();
			$weight = intval( $weight ) + 1;
			$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_rows (parentid, mid, title, link, note, weight, sort, lev, subitem, groups_view, module_name, op, target, css, active_type, status) VALUES (
				" . intval( $post['parentid'] ) . ",
				" . intval( $post['mid'] ) . ",
				:title,
				:link,
				:note,
				" . intval( $weight ) . ",
				0, 0, '',
				:groups_view,
				:module_name,
				:op,
				" . intval( $post['target'] ) . ",
				:css,
				" . intval( $post['active_type'] ) . ",
				1
			)";

			$data_insert = array();
			$data_insert['title'] = $post['title'];
			$data_insert['link'] = $post['link'];
			$data_insert['note'] = $post['note'];
			$data_insert['groups_view'] = $post['groups_view'];
			$data_insert['module_name'] = $post['module_name'];
			$data_insert['op'] = $post['op'];
			$data_insert['css'] = $post['css'];

			if( $db->insert_id( $sql, 'id', $data_insert ) )
			{
				menu_fix_order( $post['mid'] );

				if( $post['parentid'] != 0 )
				{
					$arr_item_menu = array();
					$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . $post['mid'] . ' AND parentid=' . $post['parentid'];
					$result = $db->query( $sql );

					while( $row = $result->fetch() )
					{
						$arr_item_menu[] = $row['id'];
					}

					$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET subitem= '" . implode( ',', $arr_item_menu ) . "' WHERE mid= " . $post['mid'] . " AND id=" . $post['parentid'];
					$db->query( $sql );
				}

				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&mid=' . $post['mid'] . '&parentid=' . $post['parentid'] );
				exit();
			}
			else
			{
				$error = $sql;
			}
		}
	}
	else
	{
		$stmt = $db->prepare( "SELECT count(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE title= :title AND parentid=" . $post['parentid'] . " AND mid=" . $post['mid'] . " AND id NOT IN (" . $post['id'] . ")" );
		$stmt->bindParam( ':title', $post['title'], PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() )
		{
			$error = $lang_module['title_exit_cat'];
		}
		else
		{
			$stmt = $db->prepare( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET
				parentid=" . intval( $post['parentid'] ) . ",
				mid=" . intval( $post['mid'] ) . ",
				title= :title,
				link= :link,
				note= :note,
				groups_view= :groups_view,
				module_name= :module_name,
				op= :op,
				target=" . intval( $post['target'] ) . ",
				css= :css,
				active_type=" . intval( $post['active_type'] ) . "
			WHERE id=" . intval( $post['id'] ) );

			$stmt->bindParam( ':title', $post['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':link', $post['link'], PDO::PARAM_STR );
			$stmt->bindParam( ':note', $post['note'], PDO::PARAM_STR );
			$stmt->bindParam( ':groups_view', $post['groups_view'], PDO::PARAM_STR );
			$stmt->bindParam( ':module_name', $post['module_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':op', $post['op'], PDO::PARAM_STR );
			$stmt->bindParam( ':css', $post['css'], PDO::PARAM_STR );

			if( $stmt->execute() )
			{
				if( $pa_old != $post['parentid'] )
				{
					$weight = $db->query( "SELECT max(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE mid=" . intval( $post['mid'] ) . " AND parentid=" . intval( $post['parentid'] . " " ) )->fetchColumn();
					$weight = intval( $weight ) + 1;

					$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET weight=" . intval( $weight ) . " WHERE id=" . intval( $post['id'] );
					$db->query( $sql );
				}

				menu_fix_order( $post['mid'] );

				if( $post['mid'] != $mid_old )
				{
					menu_fix_order( $mid_old );
				}

				if( $post['parentid'] != 0 )
				{
					$arr_item_menu = array();
					$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid= ' . $post['mid'] . ' AND parentid=' . $post['parentid'];
					$result = $db->query( $sql );
					while( $row = $result->fetch() )
					{
						$arr_item_menu[] = $row['id'];
					}

					$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET subitem='" . implode( ',', $arr_item_menu ) . "' WHERE mid=" . $post['mid'] . " AND id=" . $post['parentid'] );
				}

				if( $pa_old != 0 )
				{
					$arr_item_menu = array();
					$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid= ' . $mid_old . ' AND parentid=' . $pa_old;
					$result = $db->query( $sql );
					while( $row = $result->fetch() )
					{
						$arr_item_menu[] = $row['id'];
					}

					$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET subitem= '" . implode( ',', $arr_item_menu ) . "' WHERE mid=" . $mid_old . " AND id=" . $pa_old );
				}

				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&mid=' . $post['mid'] . '&parentid=' . $post['parentid'] );
				exit();
			}
			else
			{
				$error = $lang_module['errorsave'];
			}
		}
	}
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = ' . $post['mid'] . ' AND parentid=' . $post['parentid'] . ' ORDER BY weight';
$result = $db->query( $sql );

$arr_table = array();
$num = 0;

while( $row = $result->fetch() )
{
	++$num;
	$sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE parentid=' . $row['id'];
	$nu = $db->query( $sql )->fetchColumn();

	$row['sub'] = sizeof( array_filter( explode( ',', $row['subitem'] ) ) );
	$array_groups_view = explode( ',', $row['groups_view'] );
	$groups_view = array();
	foreach( $array_groups_view as $_group_id )
	{
		$groups_view[] = $groups_list[$_group_id];
	}
	$arr_table[$row['id']] = array(
		'id' => $row['id'],
		'mid' => $row['mid'],
		'nu' => $nu,
		'sub' => $row['sub'],
		'parentid' => $row['parentid'],
		'link' => nv_htmlspecialchars( $row['link'] ),
		'weight' => $row['weight'],
		'title' => $row['title'],
		'groups_view' => implode( '<br>', $groups_view ),
		'url_title' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rows&amp;mid=' . $post['mid'] . '&amp;parentid=' . $row['id'],
		'edit_url' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rows&amp;mid=' . $post['mid'] . '&amp;id=' . $row['id'] . '#edit',
	);
}

$array_mod_title = array();
$parentid = $post['parentid'];
while( $parentid > 0 )
{
	$array_item_i = $array_all_item[$parentid];
	$array_mod_title[] = array(
		'title' => $array_item_i['title'],
		'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rows&amp;mid=' . $post['mid'] . '&amp;parentid=' . $parentid
	);
	$parentid = $array_item_i['parentid'];
}
$array_mod_title[] = array(
	'title' => $arr_menu[$post['mid']]['title'],
	'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rows&amp;mid=' . $post['mid']
);
$array_mod_title[] = array(
	'title' => $lang_module['menu_manager'],
	'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name
);
sort( $array_mod_title, SORT_NUMERIC );

// Active last item
$s = sizeof( $array_mod_title ) - 1;
$array_mod_title[$s]['active'] = true;

$xtpl = new XTemplate( 'rows.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rows&amp;mid=' . $post['mid'] ) . '&amp;parentid=' . $post['parentid'];
$xtpl->assign( 'DATA', $post );

if( !empty( $arr_table ) )
{
	foreach( $arr_table as $rows )
	{
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

if( $post['id'] != 0 )
{
	if( $post['op'] != '' and isset( $site_mods[$post['module_name']] ) )
	{
		$mod_name = $post['module_name'];
		$mod_file = $site_mods[$mod_name]['module_file'];
		$mod_data = $site_mods[$mod_name]['module_data'];
		$array_item = array();
		if( file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php' ) )
		{
			include NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php';
		}
		// Lấy menu từ các chức năng của module
		$funcs_item = $site_mods[$mod_name]['funcs'];
		foreach( $funcs_item as $key => $sub_item )
		{
			if( $sub_item['in_submenu'] == 1 )
			{
				$array_item[$key] = array(
					'key' => $key,
					'title' => $sub_item['func_custom_name'],
					'alias' => $key
				);
			}
		}

		if( !empty( $array_item ) )
		{
			foreach( $array_item as $key => $item )
			{
				$parentid = ( isset( $item['parentid'] )) ? $item['parentid'] : 0;
				if( empty( $parentid ) )
				{
					$item['module'] = $mod_name;
					$item['selected'] = ($item['alias'] == $post['op']) ? ' selected="selected"' : '';
					$xtpl->assign( 'item', $item );
					$xtpl->parse( 'main.link.item' );
					foreach( $array_item as $subitem )
					{
						if( isset( $subitem['parentid'] ) and $subitem['parentid'] === $key )
						{
							$subitem['title'] = $sp . $subitem['title'];
							$subitem['module'] = $mod_name;
							$subitem['selected'] = ($subitem['alias'] == $post['op']) ? ' selected="selected"' : '';
							$xtpl->assign( 'item', $subitem );
							$xtpl->parse( 'main.link.item' );
						}
					}
				}
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

foreach( $array['groups_view'] as $group )
{
	$xtpl->assign( 'GROUPS_VIEW', $group );
	$xtpl->parse( 'main.groups_view' );
}

if( !empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

// Xuat kieu active menu
for( $i = 0; $i <= 2; ++$i )
{
	$xtpl->assign( 'ACTIVE_TYPE', array(
		'key' => $i,
		'title' => $lang_module['add_type_active_' . $i],
		'selected' => $post['active_type'] == $i ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.active_type' );
}
$xtpl->assign( 'FORM_CAPTION', ($post['id']) ? $lang_module['edit_menu'] : $lang_module['add_item'] );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['menu_manager'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';