<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$contents = "";

$setmodule = filter_text_input( 'setmodule', 'get', '', 1 );

// Thiet lap module moi
if( ! empty( $setmodule ) )
{
	if( filter_text_input( 'checkss', 'get' ) == md5( "setmodule" . $setmodule . session_id() . $global_config['sitekey'] ) )
	{
		$sql = "SELECT `module_file`, `module_data` FROM `" . $db_config['prefix'] . "_setup_modules` WHERE `title`=" . $db->dbescape( $setmodule );
		$result = $db->sql_query( $sql );
		
		if( $db->sql_numrows( $result ) == 1 )
		{
			list( $module_file, $module_data ) = $db->sql_fetchrow( $result );
			
			// Unfixdb
			$module_file = $db->unfixdb( $module_file );
			$module_data = $db->unfixdb( $module_data );

			list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(weight) FROM `" . NV_MODULES_TABLE . "`" ) );
			$weight = intval( $weight ) + 1;

			$module_version = array();
			$version_file = NV_ROOTDIR . "/modules/" . $module_file . "/version.php";
		
			if( file_exists( $version_file ) )
			{
				include ( $version_file );
			}
		
			$admin_file = ( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/admin.functions.php" ) and file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/admin/main.php" ) ) ? 1 : 0;
			$main_file = ( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/functions.php" ) and file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/funcs/main.php" ) ) ? 1 : 0;
		
			$custom_title = preg_replace( '/(\W+)/i', ' ', $setmodule );
			$in_menu = ( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/funcs/main.php" ) ) ? 1 : 0;
		
			$db->sql_query( "INSERT INTO `" . NV_MODULES_TABLE . "` (`title`, `module_file`, `module_data`, `custom_title`, `admin_title`, `set_time`, `main_file`, `admin_file`, `theme`, `mobile`, `description`, `keywords`, `groups_view`, `in_menu`, `weight`, `submenu`, `act`, `admins`, `rss`) VALUES (" . $db->dbescape( $setmodule ) . ", " . $db->dbescape( $module_file ) . ", " . $db->dbescape( $module_data ) . ", " . $db->dbescape( $custom_title ) . ", '', " . NV_CURRENTTIME . ", " . $main_file . ", " . $admin_file . ", '', '', '', '', '0', " . $in_menu . ", " . $weight . ", 1, 1, '',1)" );
		
			nv_del_moduleCache( 'modules' );
		
			$return = nv_setup_data_module( NV_LANG_DATA, $setmodule );
		
			if( $return == "OK_" . $setmodule )
			{
				nv_setup_block_module( $setmodule );
				nv_del_moduleCache( 'themes' );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['modules'] . ' ' . $setmodule . '"', '', $admin_info['userid'] );
			
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=edit&mod=" . $setmodule );
				die();
			}
		}
	}
	
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	die();
}

$delmodule = filter_text_input( 'delmodule', 'get', '', 1 );

// Xoa module
if( ! empty( $delmodule ) )
{
	if( filter_text_input( 'checkss', 'get' ) == md5( "delmodule" . $delmodule . session_id() . $global_config['sitekey'] ) )
	{
		$module_exit = array();
	
		$sql = "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`='1'";
		$result = $db->sql_query( $sql );
	
		while( list( $lang_i ) = $db->sql_fetchrow( $result ) )
		{
			list( $nmd ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`=" . $db->dbescape_string( $delmodule ) ) );
			
			if( $nmd > 0 )
			{
				$module_exit[] = $lang_i;
			}
		}
	
		if( empty( $module_exit ) )
		{
			list( $nmd ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . $db_config['prefix'] . "_setup_modules` WHERE `module_file`=" . $db->dbescape_string( $delmodule ) . " AND `title`!=" . $db->dbescape_string( $delmodule ) ) );
		
			if( $nmd > 0 )
			{
				$module_exit = 1;
			}
		}

		if( empty( $module_exit ) )
		{
			$theme_list_site = nv_scandir( NV_ROOTDIR . "/themes/", $global_config['check_theme'] );
			$theme_list_admin = nv_scandir( NV_ROOTDIR . "/themes/", $global_config['check_theme_admin'] );
			$theme_list = array_merge( $theme_list_site, $theme_list_admin );
		
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
		
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			die();
		}
		else
		{
			$xtpl = new XTemplate( "delmodule.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
			$xtpl->assign( 'LANG', $lang_module );

			if( is_array( $module_exit ) )
			{
				$info = sprintf( $lang_module['delete_module_info1'], implode( ", ", $module_exit ) );
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
$modules_exit = array_flip( nv_scandir( NV_ROOTDIR . "/modules", $global_config['check_module'] ) );
$modules_data = array();

$sql_data = "SELECT * FROM `" . $db_config['prefix'] . "_setup_modules` ORDER BY `addtime` ASC";
$result = $db->sql_query( $sql_data );

$is_delCache = false;
$module_virtual_setup = array();

while( $row = $db->sql_fetchrow( $result ) )
{
	$row['title'] = $db->unfixdb( $row['title'] );
	$row['module_file'] = $db->unfixdb( $row['module_file'] );

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
		$db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_setup_modules` WHERE `title`=" . $db->dbescape_string( $row['title'] ) );
		$db->sql_query( "UPDATE `" . NV_MODULES_TABLE . "` SET `act`=2 WHERE `title`=" . $db->dbescape_string( $row['title'] ) );
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
	$check_file_main = NV_ROOTDIR . "/modules/" . $module_name_i . "/funcs/main.php";
	$check_file_functions = NV_ROOTDIR . "/modules/" . $module_name_i . "/functions.php";

	$check_admin_main = NV_ROOTDIR . "/modules/" . $module_name_i . "/admin/main.php";
	$check_admin_functions = NV_ROOTDIR . "/modules/" . $module_name_i . "/admin.functions.php";

	if( ( file_exists( $check_file_main ) and filesize( $check_file_main ) != 0 and file_exists( $check_file_functions ) and filesize( $check_file_functions ) != 0 ) or ( file_exists( $check_admin_main ) and filesize( $check_admin_main ) != 0 and file_exists( $check_admin_functions ) and filesize( $check_admin_functions ) != 0 ) )
	{
		$check_addnews_modules = true;

		$module_version = array();
		$version_file = NV_ROOTDIR . "/modules/" . $module_name_i . "/version.php";
		
		if( file_exists( $version_file ) )
		{
			require_once ( $version_file );
		}
		
		if( empty( $module_version ) )
		{
			$timestamp = NV_CURRENTTIME - date( 'Z', NV_CURRENTTIME );
			$module_version = array(
				"name" => $module_name_i, //
				"modfuncs" => "main", //
				"is_sysmod" => 0, //
				"virtual" => 0, //
				"version" => "3.0.01", //
				"date" => date( 'D, j M Y H:i:s', $timestamp ) . ' GMT', //
				"author" => "", //
				"note" => ""
			);
		}
		
		$date_ver = intval( strtotime( $module_version['date'] ) );
		
		if( $date_ver == 0 )
		{
			$date_ver = NV_CURRENTTIME;
		}
		
		$mod_version = $module_version['version'] . " " . $date_ver;
		$note = $module_version['note'];
		$author = $module_version['author'];
		$module_data = preg_replace( '/(\W+)/i', '_', $module_name_i );
		
		$db->sql_query( "INSERT INTO `" . $db_config['prefix'] . "_setup_modules` (`title`, `is_sysmod`, `virtual`, `module_file`, `module_data`, `mod_version`, `addtime`, `author`, `note`) VALUES (" . $db->dbescape( $module_name_i ) . ", " . $db->dbescape( $module_version['is_sysmod'] ) . ", " . $db->dbescape( $module_version['virtual'] ) . ", " . $db->dbescape( $module_name_i ) . ", " . $db->dbescape( $module_data ) . ", " . $db->dbescape( $mod_version ) . ", '" . NV_CURRENTTIME . "', " . $db->dbescape( $author ) . ", " . $db->dbescape( $note ) . ")" );
	}
}

if( $check_addnews_modules )
{
	$result = $db->sql_query( $sql_data );
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$row['title'] = $db->unfixdb( $row['title'] );
		$row['module_file'] = $db->unfixdb( $row['module_file'] );
	
		$modules_data[$row['title']] = $row;
	}
}

// Lay danh sach cac module co trong ngon ngu
$modules_for_lang = array();

$sql = "SELECT * FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );

while( $row = $db->sql_fetchrow( $result ) )
{
	$row['title'] = $db->unfixdb( $row['title'] );
	
	$modules_for_lang[$row['title']] = $row;
}

// Kiem tra module moi
$news_modules_for_lang = array_diff_key( $modules_data, $modules_for_lang );

$array_modules = $array_virtual_modules = $mod_virtual = array();

foreach( $modules_data as $row )
{
	if( in_array( $row['title'], $modules_exit ) )
	{
		$mod = array();
		$mod['title'] = $row['title'];
		$mod['is_sysmod'] = $row['is_sysmod'];
		$mod['virtual'] = $row['virtual'];
		$mod['module_file'] = $row['module_file'];
		$mod['version'] = preg_replace_callback( "/^([0-9a-zA-Z]+\.[0-9a-zA-Z]+\.[0-9a-zA-Z]+)\s+(\d+)$/", "nv_parse_vers", $row['mod_version'] );
		$mod['addtime'] = nv_date( "H:i:s d/m/Y", $row['addtime'] );
		$mod['author'] = $row['author'];
		$mod['note'] = $row['note'];
		$mod['setup'] = "";
		$mod['delete'] = "";
		
		if( array_key_exists( $row['title'], $news_modules_for_lang ) )
		{
			$url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;setmodule=" . $row['title'] . "&amp;checkss=" . md5( "setmodule" . $row['title'] . session_id() . $global_config['sitekey'] );
			$mod['setup'] = "<span class=\"default_icon\"><a href=\"" . $url . "\">" . $lang_module['setup'] . "</a></span>";
			
			if( ! in_array( $row['module_file'], $module_virtual_setup ) )
			{
				$url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;delmodule=" . $row['title'] . "&amp;checkss=" . md5( "delmodule" . $row['title'] . session_id() . $global_config['sitekey'] );
				$mod['delete'] = " - <span class=\"delete_icon\"><a href=\"" . $url . "\" onclick=\"return confirm(nv_is_del_confirm[0]);\">" . $lang_global['delete'] . "</a></span>";
			}
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

$array_head = array( 
	"caption" => $lang_module['module_sys'], 
	"head" => array(
		$lang_module['weight'],
		$lang_module['module_name'],
		$lang_module['version'],
		$lang_module['settime'],
		$lang_module['author'],
		""
	)
);

$array_virtual_head = array( 
	"caption" => $lang_module['vmodule'], 
	"head" => array(
		$lang_module['weight'],
		$lang_module['module_name'],
		$lang_module['vmodule_file'],
		$lang_module['settime'],
		$lang_module['vmodule_note'],
		""
	)
);

$contents .= call_user_func( "setup_modules", $array_head, $array_modules, $array_virtual_head, $array_virtual_modules );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>