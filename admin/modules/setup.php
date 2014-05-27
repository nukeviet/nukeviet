<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$array_site_cat_module = array();
if( $global_config['idsite'] )
{
	$_module = $db->query( 'SELECT module FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'] )->fetchColumn();
	if( ! empty( $_module ) )
	{
		$array_site_cat_module = explode( ',', $_module );
	}
}

$contents = '';

// Thiet lap module moi
$setmodule = $nv_Request->get_title( 'setmodule', 'get', '', 1 );
if( ! empty( $setmodule ) )
{
	if( $nv_Request->get_title( 'checkss', 'get' ) == md5( 'setmodule' . $setmodule . session_id() . $global_config['sitekey'] ) )
	{
		$sth = $db->prepare( 'SELECT module_file, module_data FROM ' . $db_config['prefix'] . '_setup_modules WHERE title=:title');
		$sth->bindParam( ':title', $setmodule, PDO::PARAM_STR );
		$sth->execute();
		$modrow = $sth->fetch();
		if( ! empty( $modrow ) )
		{
			if( ! empty( $array_site_cat_module ) and ! in_array( $modrow['module_file'], $array_site_cat_module ) )
			{
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
				die();
			}

			$weight = $db->query( 'SELECT MAX(weight) FROM ' . NV_MODULES_TABLE )->fetchColumn();
			$weight = intval( $weight ) + 1;

			$module_version = array();
			$version_file = NV_ROOTDIR . '/modules/' . $modrow['module_file'] . '/version.php';

			if( file_exists( $version_file ) )
			{
				include $version_file;
			}

			$admin_file = ( file_exists( NV_ROOTDIR . '/modules/' . $modrow['module_file'] . '/admin.functions.php' ) and file_exists( NV_ROOTDIR . '/modules/' . $modrow['module_file'] . '/admin/main.php' ) ) ? 1 : 0;
			$main_file = ( file_exists( NV_ROOTDIR . '/modules/' . $modrow['module_file'] . '/functions.php' ) and file_exists( NV_ROOTDIR . '/modules/' . $modrow['module_file'] . '/funcs/main.php' ) ) ? 1 : 0;

			$custom_title = preg_replace( '/(\W+)/i', ' ', $setmodule );

			try
			{
				$sth = $db->prepare( "INSERT INTO " . NV_MODULES_TABLE . "
					(title, module_file, module_data, custom_title, admin_title, set_time, main_file, admin_file, theme, mobile, description, keywords, groups_view, weight, act, admins, rss) VALUES
					(:title, :module_file, :module_data, :custom_title, '', " . NV_CURRENTTIME . ", " . $main_file . ", " . $admin_file . ", '', '', '', '', '6', " . $weight . ", 1, '',1)
				" );
				$sth->bindParam( ':title', $setmodule, PDO::PARAM_STR );
				$sth->bindParam( ':module_file', $modrow['module_file'], PDO::PARAM_STR );
				$sth->bindParam( ':module_data', $modrow['module_data'], PDO::PARAM_STR );
				$sth->bindParam( ':custom_title', $custom_title, PDO::PARAM_STR );
				$sth->execute();
			}
			catch (PDOException $e)
			{
				trigger_error( $e->getMessage() );
			}

			nv_del_moduleCache( 'modules' );
			$return = nv_setup_data_module( NV_LANG_DATA, $setmodule );
			if( $return == 'OK_' . $setmodule )
			{
				nv_setup_block_module( $setmodule );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['modules'] . ' ' . $setmodule, '', $admin_info['userid'] );

				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=edit&mod=' . $setmodule );
				die();
			}
		}
	}

	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	die();
}

// Xoa module
$delmodule = $nv_Request->get_title( 'delmodule', 'get', '', 1 );
if( defined( 'NV_IS_GODADMIN' ) and ! empty( $delmodule ) )
{
	if( $nv_Request->get_title( 'checkss', 'get' ) == md5( 'delmodule' . $delmodule . session_id() . $global_config['sitekey'] ) )
	{
		$module_exit = array();

		$result = $db->query( 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1' );
		while( list( $lang_i ) = $result->fetch( 3 ) )
		{
			$sth = $db->prepare( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $lang_i . '_modules WHERE module_file= :module_file' );
			$sth->bindParam( ':module_file', $delmodule, PDO::PARAM_STR );
			$sth->execute();
			if( $sth->fetchColumn() )
			{
				$module_exit[] = $lang_i;
			}
		}

		if( empty( $module_exit ) )
		{
			$sth = $db->prepare( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_setup_modules WHERE module_file= :module_file AND title!= :title' );
			$sth->bindParam( ':module_file', $delmodule, PDO::PARAM_STR );
			$sth->bindParam( ':title', $delmodule, PDO::PARAM_STR );
			$sth->execute();
			if( $sth->fetchColumn() )
			{
				$module_exit = 1;
			}
		}

		if( empty( $module_exit ) and defined( 'NV_CONFIG_DIR' ) )
		{
			// kiem tra cac site con
			$result = $db->query( 'SELECT * FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site ORDER BY domain ASC' );
			while( $row = $result->fetch() )
			{
				$result2 = $db->query( 'SELECT lang FROM ' . $row['dbsite'] . '.' . $db_config['prefix'] . '_setup_language WHERE setup=1' );
				while( list( $lang_i ) = $result2->fetch( 3 ) )
				{
					$sth = $db->prepare( 'SELECT COUNT(*) FROM ' . $row['dbsite'] . '.' . $db_config['prefix'] . '_' . $lang_i . '_modules WHERE module_file= :module_file' );
					$sth->bindParam( ':module_file', $delmodule, PDO::PARAM_STR );
					$sth->execute();
					if( $sth->fetchColumn() )
					{
						$module_exit[] = $row['title'] . ' :' . $lang_i;
					}
				}
			}
		}

		if( empty( $module_exit ) )
		{
			$theme_list_site = nv_scandir( NV_ROOTDIR . '/themes/', $global_config['check_theme'] );
			$theme_list_mobile = nv_scandir( NV_ROOTDIR . '/themes/', $global_config['check_theme_mobile'] );
			$theme_list_admin = nv_scandir( NV_ROOTDIR . '/themes/', $global_config['check_theme_admin'] );
			$theme_list = array_merge( $theme_list_site, $theme_list_mobile, $theme_list_admin );

			foreach( $theme_list as $theme )
			{
				if( file_exists( NV_ROOTDIR . '/themes/' . $theme . '/css/' . $delmodule . '.css' ) )
				{
					nv_deletefile( NV_ROOTDIR . '/themes/' . $theme . '/css/' . $delmodule . '.css' );
				}

				if( is_dir( NV_ROOTDIR . '/themes/' . $theme . '/images/' . $delmodule ) )
				{
					nv_deletefile( NV_ROOTDIR . '/themes/' . $theme . '/images/' . $delmodule, true );
				}

				if( is_dir( NV_ROOTDIR . '/themes/' . $theme . '/modules/' . $delmodule ) )
				{
					nv_deletefile( NV_ROOTDIR . '/themes/' . $theme . '/modules/' . $delmodule, true );
				}
			}

			if( is_dir( NV_ROOTDIR . '/modules/' . $delmodule . '/' ) )
			{
				nv_deletefile( NV_ROOTDIR . '/modules/' . $delmodule . '/', true );
			}

			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}
		else
		{
			$xtpl = new XTemplate( 'delmodule.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
			$xtpl->assign( 'LANG', $lang_module );

			if( is_array( $module_exit ) )
			{
				$info = sprintf( $lang_module['delete_module_info1'], implode( ', ', $module_exit ) );
			}
			else
			{
				$info = sprintf( $lang_module['delete_module_info2'], $module_exit );
			}

			$xtpl->assign( 'INFO', $info );
			$xtpl->parse( 'main' );
			$contents .= $xtpl->text( 'main' );
		}
	}
}

$page_title = $lang_module['modules'];
$modules_exit = array_flip( nv_scandir( NV_ROOTDIR . '/modules', $global_config['check_module'] ) );
$modules_data = array();

$is_delCache = false;
$module_virtual_setup = array();

$sql_data = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_modules ORDER BY addtime ASC';
$result = $db->query( $sql_data );
while( $row = $result->fetch() )
{
	if( array_key_exists( $row['module_file'], $modules_exit ) )
	{
		$modules_data[$row['title']] = $row;

		if( $row['title'] != $row['module_file'] )
		{
			$module_virtual_setup[] = $row['module_file'];
		}
	}
	else
	{
		$sth = $db->prepare( 'DELETE FROM ' . $db_config['prefix'] . '_setup_modules WHERE title= :title' );
		$sth->bindParam( ':title', $row['title'], PDO::PARAM_STR );
		$sth->execute();

		$sth = $db->prepare( 'UPDATE ' . NV_MODULES_TABLE . ' SET act=2 WHERE title=:title' );
		$sth->bindParam( ':title', $row['title'], PDO::PARAM_STR );
		$sth->execute();

		$is_delCache = true;
	}
}

if( $is_delCache )
{
	nv_del_moduleCache( 'modules' );
}

$check_addnews_modules = false;
$arr_module_news = array_diff_key( $modules_exit, $modules_data );

foreach( $arr_module_news as $module_name_i => $arr )
{
	$check_file_main = NV_ROOTDIR . '/modules/' . $module_name_i . '/funcs/main.php';
	$check_file_functions = NV_ROOTDIR . '/modules/' . $module_name_i . '/functions.php';

	$check_admin_main = NV_ROOTDIR . '/modules/' . $module_name_i . '/admin/main.php';
	$check_admin_functions = NV_ROOTDIR . '/modules/' . $module_name_i . '/admin.functions.php';

	if( ( file_exists( $check_file_main ) and filesize( $check_file_main ) != 0 and file_exists( $check_file_functions ) and filesize( $check_file_functions ) != 0 ) or ( file_exists( $check_admin_main ) and filesize( $check_admin_main ) != 0 and file_exists( $check_admin_functions ) and filesize( $check_admin_functions ) != 0 ) )
	{
		$check_addnews_modules = true;

		$module_version = array();
		$version_file = NV_ROOTDIR . '/modules/' . $module_name_i . '/version.php';

		if( file_exists( $version_file ) )
		{
			require_once $version_file;
		}

		if( empty( $module_version ) )
		{
			$timestamp = NV_CURRENTTIME - date( 'Z', NV_CURRENTTIME );
			$module_version = array(
				'name' => $module_name_i,
				'modfuncs' => 'main',
				'is_sysmod' => 0,
				'virtual' => 0,
				'version' => '3.5.00',
				'date' => date( 'D, j M Y H:i:s', $timestamp ) . ' GMT',
				'author' => '',
				'note' => ''
			);
		}

		$date_ver = intval( strtotime( $module_version['date'] ) );

		if( $date_ver == 0 )
		{
			$date_ver = NV_CURRENTTIME;
		}

		$mod_version = $module_version['version'] . ' ' . $date_ver;
		$note = $module_version['note'];
		$author = $module_version['author'];
		$module_data = preg_replace( '/(\W+)/i', '_', $module_name_i );

		// Chỉ cho phép ảo hóa module khi virtual = 1, Khi virtual = 2, chỉ đổi được tên các func
		$module_version['virtual'] = ( $module_version['virtual']==1 ) ? 1 : 0;

		$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_setup_modules
			(title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note)	VALUES
			( :title, ' . intval( $module_version['is_sysmod'] ) . ', ' . intval( $module_version['virtual'] ) . ', :module_file, :module_data, :mod_version, ' . NV_CURRENTTIME . ', :author, :note)'
			);
		$sth->bindParam( ':title', $module_name_i, PDO::PARAM_STR );
		$sth->bindParam( ':module_file', $module_name_i, PDO::PARAM_STR );
		$sth->bindParam( ':module_data', $module_data, PDO::PARAM_STR );
		$sth->bindParam( ':mod_version', $mod_version, PDO::PARAM_STR );
		$sth->bindParam( ':author', $author, PDO::PARAM_STR );
		$sth->bindParam( ':note', $note, PDO::PARAM_STR );
		$sth->execute();
	}
}

if( $check_addnews_modules )
{
	$result = $db->query( $sql_data );
	while( $row = $result->fetch() )
	{
		$modules_data[$row['title']] = $row;
	}
}

// Lay danh sach cac module co trong ngon ngu
$modules_for_title = array();
$modules_for_file = array();

$result = $db->query( 'SELECT * FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC' );
while( $row = $result->fetch() )
{
	$modules_for_title[$row['title']] = $row;
	$modules_for_file[$row['module_file']] = $row;
}

// Kiem tra module moi
$news_modules_for_file = array_diff_key( $modules_data, $modules_for_file );

$array_modules = $array_virtual_modules = $mod_virtual = array();

foreach( $modules_data as $row )
{
	if( in_array( $row['title'], $modules_exit ) )
	{
		if( ! empty( $array_site_cat_module ) and ! in_array( $row['module_file'], $array_site_cat_module ) )
		{
			continue;
		}

		if( array_key_exists( $row['title'], $news_modules_for_file ) )
		{
			$mod = array();
			$mod['title'] = $row['title'];
			$mod['is_sysmod'] = $row['is_sysmod'];
			$mod['virtual'] = $row['virtual'];
			$mod['module_file'] = $row['module_file'];
			$mod['version'] = preg_replace_callback( '/^([0-9a-zA-Z]+\.[0-9a-zA-Z]+\.[0-9a-zA-Z]+)\s+(\d+)$/', 'nv_parse_vers', $row['mod_version'] );
			$mod['addtime'] = nv_date( 'H:i:s d/m/Y', $row['addtime'] );
			$mod['author'] = $row['author'];
			$mod['note'] = $row['note'];
			if( array_key_exists( $row['title'], $modules_for_title ) )
			{
				$url = 'javascript:void(0);';
			}
			else
			{
				$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;setmodule=' . $row['title'] . '&amp;checkss=' . md5( 'setmodule' . $row['title'] . session_id() . $global_config['sitekey'] );
			}
			$mod['setup'] = "<em class=\"fa fa-sun-o fa-lg\">&nbsp;</em> <a href=\"" . $url . "\">" . $lang_module['setup'] . "</a>";
			$mod['delete'] = '';
			if( defined( "NV_IS_GODADMIN" ) and ! in_array( $row['module_file'], $module_virtual_setup ) )
			{
				$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delmodule=' . $row['title'] . '&amp;checkss=' . md5( 'delmodule' . $row['title'] . session_id() . $global_config['sitekey'] );
				$mod['delete'] = " <em class=\"fa fa-trash-o fa-lg\">&nbsp;</em> <a href=\"" . $url . "\" onclick=\"return confirm(nv_is_del_confirm[0]);\">" . $lang_global['delete'] . "</a>";
			}
			if( $mod['module_file'] == $mod['title'] )
			{
				$array_modules[] = $mod;

				if( $row['virtual'] )
				{
					$mod_virtual[] = $mod['title'];
				}
			}
			else
			{
				$array_virtual_modules[] = $mod;
			}
		}

	}
}

$array_head = array(
	'caption' => $lang_module['module_sys'],
	'head' => array( $lang_module['weight'], $lang_module['module_name'], $lang_module['version'], $lang_module['settime'], $lang_module['author'], '' )
);

$array_virtual_head = array(
	'caption' => $lang_module['vmodule'],
	'head' => array( $lang_module['weight'], $lang_module['module_name'], $lang_module['vmodule_file'], $lang_module['settime'], $lang_module['vmodule_note'], '' )
);

$contents .= call_user_func( 'setup_modules', $array_head, $array_modules, $array_virtual_head, $array_virtual_modules );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';