<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$functionid = $nv_Request->get_int( 'func', 'get' );
$blockredirect = $nv_Request->get_string( 'blockredirect', 'get' );

$selectthemes = $nv_Request->get_string( 'selectthemes', 'post,get,cookie', $global_config['site_theme'] );

$row = array(
	'bid' => 0,
	'theme' => '',
	'module' => 'global',
	'file_name' => '',
	'title' => '',
	'link' => '',
	'template' => '',
	'position' => $nv_Request->get_string( 'tag', 'get', '' ),
	'exp_time' => 0,
	'active' => 1,
	'groups_view' => '',
	'all_func' => 1,
	'weight' => 0,
	'config' => ''
);
$row_old = array();

$row['bid'] = $nv_Request->get_int( 'bid', 'get,post', 0 );

if( $row['bid'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $row['bid'] )->fetch();

	if( empty( $row ) )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	else
	{
		$row_old = $row;
	}
}

$xtpl = new XTemplate( 'block_content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );

if( $nv_Request->isset_request( 'confirm', 'post' ) )
{
	$error = array();
	$list_file_name = $nv_Request->get_title( 'file_name', 'post', '', 0 );
	$array_file_name = explode( '|', $list_file_name );

	$file_name = $row['file_name'] = trim( $array_file_name[0] );
	$module = $row['module'] = nv_substr( $nv_Request->get_title( 'module', 'post', '', 0 ), 0, 55 );
	$row['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );

	$path_file_php = $path_file_ini = $path_file_lang = '';

	unset( $matches );
	preg_match( $global_config['check_block_module'], $row['file_name'], $matches );

	if( isset( $array_file_name[1] ) )
	{
		if( $module == 'global' and file_exists( NV_ROOTDIR . '/includes/blocks/' . $file_name ) and file_exists( NV_ROOTDIR . '/includes/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) )
		{
			$path_file_php = NV_ROOTDIR . '/includes/blocks/' . $file_name;
			$path_file_ini = NV_ROOTDIR . '/includes/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';

			if( file_exists( NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/block.' . $file_name ) )
			{
				$path_file_lang = NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/block.' . $file_name;
			}
			elseif( file_exists( NV_ROOTDIR . '/language/' . NV_LANG_DATA . '/block.' . $file_name ) )
			{
				$path_file_lang = NV_ROOTDIR . '/language/' . NV_LANG_DATA . '/block.' . $file_name;
			}
			elseif( file_exists( NV_ROOTDIR . '/language/en/block.' . $file_name ) )
			{
				$path_file_lang = NV_ROOTDIR . '/language/en/block.' . $file_name;
			}
		}
		elseif( isset( $site_mods[$module] ) )
		{
			$mod_file = $site_mods[$module]['module_file'];

			if( file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $file_name ) and file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) )
			{
				$path_file_php = NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $file_name;
				$path_file_ini = NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';

				if( file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_' . NV_LANG_INTERFACE . '.php' ) )
				{
					$path_file_lang = NV_ROOTDIR . '/modules/' . $mod_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_' . NV_LANG_INTERFACE . '.php';
				}
				elseif( file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_' . NV_LANG_DATA . '.php' ) )
				{
					$path_file_lang = NV_ROOTDIR . '/modules/' . $mod_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_' . NV_LANG_DATA . '.php';
				}
				elseif( file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_en.php' ) )
				{
					$path_file_lang = NV_ROOTDIR . '/modules/' . $mod_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_en.php';
				}
			}
		}

		if( empty( $row['title'] ) )
		{
			$row['title'] = str_replace( '_', ' ', $matches[1] . ' ' . $matches[2] );
		}
	}
	else
	{
		$error[] = $lang_module['block_error_nsblock'];
	}

	$row['link'] = $nv_Request->get_title( 'link', 'post', '' );
	$row['template'] = nv_substr( $nv_Request->get_title( 'template', 'post', '', 0 ), 0, 55 );
	$row['position'] = nv_substr( $nv_Request->get_title( 'position', 'post', '', 0 ), 0, 55 );

	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'exp_time', 'post' ), $m ) )
	{
		$row['exp_time'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['exp_time'] = 0;
	}
	$row['active'] = $nv_Request->get_int( 'active', 'post', 0 );

	$who_view = $nv_Request->get_int( 'who_view', 'post', 0 );

	if( $who_view < 0 or $who_view > 3 ) $who_view = 0;

	$groups_view = '';

	if( $who_view == 3 )
	{
		$groups_view = $nv_Request->get_array( 'groups_view', 'post', array() );
		$row['groups_view'] = ! empty( $groups_view ) ? implode( ',', array_map( 'intval', $groups_view ) ) : '';
	}
	else
	{
		$row['groups_view'] = ( string )$who_view;
	}

	$all_func = ( $nv_Request->get_int( 'all_func', 'post' ) == 1 and preg_match( $global_config['check_block_global'], $row['file_name'] ) ) ? 1 : 0;
	$array_funcid = $nv_Request->get_array( 'func_id', 'post' );

	if( empty( $all_func ) and empty( $array_funcid ) )
	{
		$error[] = $lang_module['block_no_func'];
	}

	$row['leavegroup'] = $nv_Request->get_int( 'leavegroup', 'post', 0 );

	if( ! empty( $row['leavegroup'] ) and ! empty( $row['bid'] ) )
	{
		$all_func = 0;
		$row['leavegroup'] = 1;
	}
	else
	{
		$row['leavegroup'] = 0;
	}

	$row['all_func'] = $all_func;
	$row['config'] = '';

	if( ! empty( $path_file_php ) and ! empty( $path_file_ini ) )
	{
		// Load cac cau hinh cua block
		$xml = simplexml_load_file( $path_file_ini );

		if( $xml !== false )
		{
			$submit_function = trim( $xml->submitfunction );

			if( ! empty( $submit_function ) )
			{
				// Neu ton tai function de xay dung cau truc cau hinh block
				include_once $path_file_php;

				if( nv_function_exists( $submit_function ) )
				{
					$lang_block = array();
					// Ngon ngu cua block

					if( ! empty( $path_file_lang ) )
					{
						require $path_file_lang;
					}
					else
					{
						$xmllanguage = $xml->xpath( 'language' );
						$language = ( array )$xmllanguage[0];

						if( isset( $language[NV_LANG_INTERFACE] ) )
						{
							$lang_block = ( array )$language[NV_LANG_INTERFACE];
						}
						elseif( isset( $language['en'] ) )
						{
							$lang_block = ( array )$language['en'];
						}
						else
						{
							$key = array_keys( $array_config );
							$lang_block = array_combine( $key, $key );
						}
					}

					// Goi ham xu ly hien thi block
					$array_config = call_user_func( $submit_function, $module, $lang_block );

					if( ! empty( $array_config['config'] ) )
					{
						$row['config'] = serialize( $array_config['config'] );
					}
					else
					{
						$row['config'] = '';
					}

					if( ! empty( $array_config['error'] ) )
					{
						$error = array_merge( $error, $array_config['error'] );
					}
				}
			}
		}
	}

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
		$xtpl->parse( 'main.error' );
	}
	else
	{
		if( $all_func and preg_match( $global_config['check_block_global'], $row['file_name'] ) )
		{
			$array_funcid = array();
			$func_result = $db->query( 'SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func = 1 ORDER BY in_module ASC, subweight ASC' );

			while( list( $func_id_i ) = $func_result->fetch( 3 ) )
			{
				$array_funcid[] = $func_id_i;
			}
		}
		elseif( ! empty( $row['module'] ) and isset( $site_mods[$row['module']] ) and ! preg_match( $global_config['check_block_global'], $row['file_name'] ) )
		{
			$array_funcid_module = array();
			$sth = $db->prepare( 'SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func = 1 AND in_module= :in_module ORDER BY in_module ASC, subweight ASC' );
			$sth->bindParam( ':in_module', $row['module'], PDO::PARAM_STR );
			$sth->execute();
			while( list( $func_id_i ) = $sth->fetch( 3 ) )
			{
				$array_funcid_module[] = $func_id_i;
			}

			$array_funcid = array_intersect( $array_funcid, $array_funcid_module );
		}

		if( is_array( $array_funcid ) )
		{
			// Tach va tao nhom moi
			if( ! empty( $row['leavegroup'] ) )
			{
				$db->query( 'UPDATE ' . NV_BLOCKS_TABLE . '_groups SET all_func= 0 WHERE bid=' . $row['bid'] );
				$db->query( 'DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid'] . ' AND func_id in (' . implode( ',', $array_funcid ) . ')' );

				// Cap nhat lai thu tu cho nhom cu
				$func_id_old = $weight = 0;
				$sth = $db->prepare( 'SELECT t1.bid, t1.func_id FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t2.theme= :theme AND t2.position= :position ORDER BY t1.func_id ASC, t1.weight ASC' );
				$sth->bindParam( ':theme', $row_old['theme'], PDO::PARAM_STR );
				$sth->bindParam( ':position', $row_old['position'], PDO::PARAM_STR );
				$sth->execute();
				while( list( $bid_i, $func_id_i ) = $sth->fetch( 3 ) )
				{
					if( $func_id_i == $func_id_old )
					{
						++$weight;
					}
					else
					{
						$weight = 1;
						$func_id_old = $func_id_i;
					}

					$db->query( 'UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=' . $weight . ' WHERE bid=' . $bid_i . ' AND func_id=' . $func_id_i );
				}
				unset( $func_id_old, $weight );

				$row['bid'] = 0;
			}

			if( empty( $row['bid'] ) )
			{
				$sth = $db->prepare( 'SELECT MAX(weight) FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position' );
				$sth->bindParam( ':theme', $selectthemes, PDO::PARAM_STR );
				$sth->bindParam( ':position', $row['position'], PDO::PARAM_STR );
				$sth->execute();
				$row['weight'] = intval( $sth->fetchColumn() ) + 1;

				$_sql = "INSERT INTO " . NV_BLOCKS_TABLE . "_groups (theme, module, file_name, title, link, template, position, exp_time, active, groups_view, all_func, weight, config) VALUES ( :selectthemes, :module, :file_name, :title, :link, :template, :position, '" . $row['exp_time'] . "', '" . $row['active'] . "', :groups_view, '" . $row['all_func'] . "', '" . $row['weight'] . "', :config )";
				$data = array();
				$data['selectthemes'] = $selectthemes;
				$data['module'] = $row['module'];
				$data['file_name'] = $row['file_name'];
				$data['title'] = $row['title'];
				$data['link'] = $row['link'];
				$data['template'] = $row['template'];
				$data['position'] = $row['position'];
				$data['groups_view'] = $row['groups_view'];
				$data['config'] = $row['config'];
				$row['bid'] = $db->insert_id( $_sql, 'bid', $data );

				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['block_add'], 'Name : ' . $row['title'], $admin_info['userid'] );
			}
			else
			{
				$sth = $db->prepare( 'UPDATE ' . NV_BLOCKS_TABLE . '_groups SET
					module=:module,
					file_name=:file_name,
					title=:title,
					link=:link,
					template=:template,
					position=:position,
					exp_time=:exp_time,
					active=:active,
					groups_view=:groups_view,
					all_func=:all_func,
					config=:config
					WHERE bid = :bid' );

				$sth->bindParam( ':module', $row['module'], PDO::PARAM_STR );
				$sth->bindParam( ':file_name', $row['file_name'], PDO::PARAM_STR );
				$sth->bindParam( ':title', $row['title'], PDO::PARAM_STR );
				$sth->bindParam( ':link', $row['link'], PDO::PARAM_STR );
				$sth->bindParam( ':template', $row['template'], PDO::PARAM_STR );
				$sth->bindParam( ':position', $row['position'], PDO::PARAM_STR );
				$sth->bindParam( ':exp_time', $row['exp_time'], PDO::PARAM_STR );
				$sth->bindParam( ':active', $row['active'], PDO::PARAM_STR );
				$sth->bindParam( ':groups_view', $row['groups_view'], PDO::PARAM_STR );
				$sth->bindParam( ':all_func', $row['all_func'], PDO::PARAM_STR );
				$sth->bindParam( ':config', $row['config'], PDO::PARAM_STR );
				$sth->bindParam( ':bid', $row['bid'], PDO::PARAM_STR );
				$sth->execute();

				if( isset( $site_mods[$module] ) )
				{
					nv_del_moduleCache( $module );
				}

				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['block_edit'], 'Name : ' . $row['title'], $admin_info['userid'] );
			}

			if( ! empty( $row['bid'] ) )
			{
				$func_list = array();
				$result_func = $db->query( 'SELECT func_id FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid'] );

				while( list( $func_inlist ) = $result_func->fetch( 3 ) )
				{
					$func_list[] = $func_inlist;
				}

				$array_funcid_old = array_diff( $func_list, $array_funcid );

				if( ! empty( $array_funcid_old ) )
				{
					$db->query( 'DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid'] . ' AND func_id in (' . implode( ',', $array_funcid_old ) . ')' );
				}
				foreach( $array_funcid as $func_id )
				{
					if( ! in_array( $func_id, $func_list ) )
					{
						$sth = $db->prepare( 'SELECT MAX(t1.weight) FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t1.func_id=' . $func_id . ' AND t2.theme= :theme AND t2.position= :position' );
						$sth->bindParam( ':theme', $selectthemes, PDO::PARAM_STR );
						$sth->bindParam( ':position', $row['position'], PDO::PARAM_STR );
						$sth->execute();
						$weight = $sth->fetchColumn;
						$weight = intval( $weight ) + 1;

						$db->query( 'INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (' . $row['bid'] . ', ' . $func_id . ', ' . $weight . ')' );
					}
				}

				nv_del_moduleCache( 'themes' );

				if( empty( $blockredirect ) )
				{
					$blockredirect = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks';
				}

				// Chuyen huong
				$xtpl->assign( 'BLOCKREDIRECT', nv_base64_decode( $blockredirect ) );
				$xtpl->parse( 'blockredirect' );
				$contents = $xtpl->text( 'blockredirect' );

				include NV_ROOTDIR . '/includes/header.php';
				echo $contents;
				include NV_ROOTDIR . '/includes/footer.php';
				die();
			}
		}
		elseif( ! empty( $row['bid'] ) )
		{
			$db->query( 'DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $row['bid'] );
			$db->query( 'DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid'] );

			nv_del_moduleCache( 'themes' );
		}
	}
}

$who_view = 3;
$groups_view = array();

if( empty( $row['groups_view'] ) or $row['groups_view'] == '1' or $row['groups_view'] == '2' )
{
	$who_view = intval( $row['groups_view'] );
}
else
{
	$groups_view = array_map( 'intval', explode( ',', $row['groups_view'] ) );
}

$sql = 'SELECT func_id, func_custom_name, in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func=1 ORDER BY in_module ASC, subweight ASC';
$func_result = $db->query( $sql );
$aray_mod_func = array();
while( list( $id_i, $func_custom_name_i, $in_module_i ) = $func_result->fetch( 3 ) )
{
	$aray_mod_func[$in_module_i][] = array( 'id' => $id_i, 'func_custom_name' => $func_custom_name_i );
}

// Load position file
$xml = @simplexml_load_file( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini' ) or nv_info_die( $lang_global['error_404_title'], $lang_module['block_error_fileconfig_title'], $lang_module['block_error_fileconfig_content'] );
$xmlpositions = $xml->xpath( 'positions' );
$positions = $xmlpositions[0]->position;

if( $row['bid'] != 0 ) // Canh bao tach block khoi nhom
{
	$xtpl->parse( 'main.block_group_notice' );
}

$xtpl->assign( 'SELECTTHEMES', $selectthemes );
$xtpl->assign( 'BLOCKREDIRECT', $blockredirect );
$xtpl->assign( 'GLOBAL_SELECTED', ( $row['module'] == 'global' ) ? ' selected="selected"' : '' );

$sql = 'SELECT title, custom_title FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC';
$result = $db->query( $sql );
while( $row_i = $result->fetch() )
{
	$xtpl->assign( 'MODULE', array(
		'key' => $row_i['title'],
		'selected' => ( $row_i['title'] == $row['module'] ) ? ' selected="selected"' : '',
		'title' => $row_i['custom_title']
	) );
	$xtpl->parse( 'main.module' );
}

$xtpl->assign( 'ROW', array(
	'title' => $row['title'],
	'exp_time' => ( $row['exp_time'] > 0 ) ? date( 'd/m/Y', $row['exp_time'] ) : '',
	'block_active' => ( intval( $row['active'] ) == 1 ) ? ' checked="checked"' : '',
	'link' => $row['link'],
	'bid' => $row['bid'],
	'module' => $row['module'],
	'file_name' => $row['file_name']
) );

$templ_list = nv_scandir( NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', '/^block\.([a-zA-Z0-9\-\_]+)\.tpl$/' );
$templ_list = preg_replace( '/^block\.([a-zA-Z0-9\-\_]+)\.tpl$/', '\\1', $templ_list );

foreach( $templ_list as $value )
{
	if( ! empty( $value ) and $value != 'default' )
	{
		$xtpl->assign( 'TEMPLATE', array(
			'key' => $value,
			'selected' => ( $row['template'] == $value ) ? ' selected="selected"' : '',
			'title' => $value
		) );
		$xtpl->parse( 'main.template' );
	}
}

for( $i = 0, $count = sizeof( $positions ); $i < $count; ++$i )
{
	$xtpl->assign( 'POSITION', array(
		'key' => ( string )$positions[$i]->tag,
		'selected' => ( $row['position'] == $positions[$i]->tag ) ? ' selected="selected"' : '',
		'title' => ( string )$positions[$i]->name
	) );
	$xtpl->parse( 'main.position' );
}

$array_who_view = array( $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'], $lang_global['who_view3'] );

$row['groups_view'] = intval( $row['groups_view'] );
foreach( $array_who_view as $k => $w )
{
	$xtpl->assign( 'WHO_VIEW', array(
		'key' => $k,
		'selected' => ( $k == $row['groups_view'] ) ? ' selected="selected"' : '',
		'title' => $w
	) );
	$xtpl->parse( 'main.who_view' );
}

$xtpl->assign( 'SHOW_GROUPS_LIST', $who_view == 3 ? 'visibility:visible;display:table-row-group' : 'visibility:hidden;display:none' );

$groups_list = nv_groups_list();

foreach( $groups_list as $group_id => $grtl )
{
	$xtpl->assign( 'GROUPS_LIST', array(
		'key' => $group_id,
		'selected' => ( in_array( $group_id, $groups_view ) ) ? ' checked="checked"' : '',
		'title' => $grtl
	) );
	$xtpl->parse( 'main.groups_list' );
}

if( $row['bid'] != 0 ) // Tach ra va tao nhom moi
{
	$blocks_num = $db->query( 'SELECT COUNT(*) FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid'] )->fetchColumn();
	$xtpl->assign( 'BLOCKS_NUM', $blocks_num );

	$xtpl->parse( 'main.edit' );
}

$add_block_module = array( 1 => $lang_module['add_block_all_module'], 0 => $lang_module['add_block_select_module'] );

$i = 1;
foreach( $add_block_module as $b_key => $b_value )
{
	$showsdisplay = ( ! preg_match( $global_config['check_block_global'], $row['file_name'] ) and $b_key == 1 ) ? ' style="display:none"' : '';

	$xtpl->assign( 'I', $i );
	$xtpl->assign( 'SHOWSDISPLAY', $showsdisplay );
	$xtpl->assign( 'B_KEY', $b_key );
	$xtpl->assign( 'B_VALUE', $b_value );
	$xtpl->assign( 'CK', ( $row['all_func'] == $b_key ) ? ' checked="checked"' : '' );

	$xtpl->parse( 'main.add_block_module' );
	++$i;
}

$xtpl->assign( 'SHOWS_ALL_FUNC', ( intval( $row['all_func'] ) ) ? ' style="display:none" ' : '' );

$func_list = array();

if( $row['bid'] )
{
	$result_func = $db->query( 'SELECT func_id FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid'] );
	while( list( $func_inlist ) = $result_func->fetch( 3 ) )
	{
		$func_list[] = $func_inlist;
	}
}

$sql = 'SELECT title, custom_title FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC';
$result = $db->query( $sql );
while( list( $m_title, $m_custom_title ) = $result->fetch( 3 ) )
{
	if( isset( $aray_mod_func[$m_title] ) and sizeof( $aray_mod_func[$m_title] ) > 0 )
	{
		$i = 0;
		foreach( $aray_mod_func[$m_title] as $aray_mod_func_i )
		{
			$sel = '';

			if( in_array( $aray_mod_func_i['id'], $func_list ) || $functionid == $aray_mod_func_i['id'] )
			{
				++$i;
				$sel = ' checked="checked"';
			}

			$xtpl->assign( 'SELECTED', $sel );
			$xtpl->assign( 'FUNCID', $aray_mod_func_i['id'] );
			$xtpl->assign( 'FUNCNAME', $aray_mod_func_i['func_custom_name'] );

			$xtpl->parse( 'main.loopfuncs.fuc' );
		}

		$xtpl->assign( 'M_TITLE', $m_title );
		$xtpl->assign( 'M_CUSTOM_TITLE', $m_custom_title );
		$xtpl->assign( 'M_CHECKED', ( sizeof( $aray_mod_func[$m_title] ) == $i ) ? ' checked="checked"' : '' );

		$xtpl->parse( 'main.loopfuncs' );
	}
}

$load_block_config = false;

if( preg_match( $global_config['check_block_module'], $row['file_name'], $matches ) )
{
	if( $row['module'] == 'global' and file_exists( NV_ROOTDIR . '/includes/blocks/' . $row['file_name'] ) and file_exists( NV_ROOTDIR . '/includes/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) )
	{
		$load_block_config = true;
	}
	elseif( isset( $site_mods[$row['module']] ) )
	{
		$module_file = $site_mods[$row['module']]['module_file'];

		if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $row['file_name'] ) and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) )
		{
			$load_block_config = true;
		}

		if( ! preg_match( $global_config['check_block_global'], $row['file_name'] ) )
		{

			$xtpl->assign( 'HIDEFUNCLIST', $row['module'] );
			$xtpl->parse( 'main.hidefunclist' );
		}
	}
}

if( $load_block_config )
{
	$xtpl->parse( 'main.load_block_config' );
}
else
{
	$xtpl->parse( 'main.hide_block_config' );
}

$page_title = '&nbsp;&nbsp;' . $lang_module['blocks'] . ': Theme ' . $selectthemes;

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$xtpl->parse( 'head' );
$my_head = $xtpl->text( 'head' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents, 0 );
include NV_ROOTDIR . '/includes/footer.php';

?>