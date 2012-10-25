<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-5-2010 8:49
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

/**
 * nv_show_funcs()
 *
 * @return void
 */
function nv_show_funcs()
{
	global $db, $lang_module, $global_config, $site_mods;

	$mod = filter_text_input( 'mod', 'get', '' );

	if( empty( $mod ) or ! preg_match( $global_config['check_module'], $mod ) ) die();

	$sql = "SELECT `module_file`, `custom_title`, `admin_file` FROM `" . NV_MODULES_TABLE . "` WHERE `title`=" . $db->dbescape( $mod );
	$result = $db->sql_query( $sql );
	$numrows = $db->sql_numrows( $result );

	if( $numrows != 1 ) die();

	$row = $db->sql_fetchrow( $result );

	$custom_title = $row['custom_title'];
	$module_file = $db->unfixdb( $row['module_file'] );
	$admin_file = ( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/admin.functions.php" ) and file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/admin/main.php" ) ) ? 1 : 0;

	$is_delCache = false;

	if( $admin_file != intval( $row['admin_file'] ) )
	{
		$sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `admin_file`=" . $admin_file . " WHERE `title`=" . $db->dbescape( $mod );
		$db->sql_query( $sql );
		$is_delCache = true;
	}

	$local_funcs = nv_scandir( NV_ROOTDIR . '/modules/' . $module_file . '/funcs', $global_config['check_op_file'] );

	if( ! empty( $local_funcs ) )
	{
		$local_funcs = preg_replace( $global_config['check_op_file'], "\\1", $local_funcs );
		$local_funcs = array_flip( $local_funcs );
	}

	$module_version = array();
	$version_file = NV_ROOTDIR . "/modules/" . $module_file . "/version.php";

	if( file_exists( $version_file ) )
	{
		$module_name = $mod;
		require_once ( $version_file );
	}

	if( empty( $module_version ) )
	{
		$timestamp = NV_CURRENTTIME - date( 'Z', NV_CURRENTTIME );
		$module_version = array(
			"name" => $mod, //
			"modfuncs" => "main", //
			"is_sysmod" => 0, //
			"virtual" => 0, //
			"version" => "3.0.01", //
			"date" => date( 'D, j M Y H:i:s', $timestamp ) . ' GMT', //
			"author" => "", //
			"note" => ""
		);
	}

	$module_version['submenu'] = isset( $module_version['submenu'] ) ? trim( $module_version['submenu'] ) : "";
	$modfuncs = array_map( "trim", explode( ",", $module_version['modfuncs'] ) );
	$arr_in_submenu = array_map( "trim", explode( ",", $module_version['submenu'] ) );

	$data_funcs = array();
	$weight_list = array();

	$sql = "SELECT * FROM `" . NV_MODFUNCS_TABLE . "` WHERE `in_module`=" . $db->dbescape( $mod ) . " ORDER BY `subweight` ASC";
	$result = $db->sql_query( $sql );

	while( $row = $db->sql_fetchrow( $result ) )
	{
		$func = $db->unfixdb( $row['func_name'] );
		$show_func = in_array( $func, $modfuncs ) ? 1 : 0;
	
		if( $row['show_func'] != $show_func )
		{
			$row['show_func'] = $show_func;
			$sql = "UPDATE `" . NV_MODFUNCS_TABLE . "` SET `show_func`=" . $show_func . " WHERE `func_id`=" . $row['func_id'];
			$db->sql_query( $sql );
			$is_delCache = true;
		}
	
		$data_funcs[$func]['func_id'] = $row['func_id'];
		$data_funcs[$func]['layout'] = empty( $row['layout'] ) ? '' : $row['layout'];
		$data_funcs[$func]['show_func'] = $row['show_func'];
		$data_funcs[$func]['func_custom_name'] = $row['func_custom_name'];
		$data_funcs[$func]['in_submenu'] = $row['in_submenu'];
		$data_funcs[$func]['subweight'] = $row['subweight'];
	
		if( $show_func )
		{
			$weight_list[] = $row['subweight'];
		}
	}

	$act_funcs = array_intersect_key( $data_funcs, $local_funcs );
	$old_funcs = array_diff_key( $data_funcs, $local_funcs );
	$new_funcs = array_diff_key( $local_funcs, $data_funcs );

	$is_refresh = false;
	if( ! empty( $old_funcs ) )
	{
		foreach( $old_funcs as $func => $values )
		{
			$sql = "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `func_id` = " . $values['func_id'];
			$db->sql_query( $sql );
			$sql = "DELETE FROM `" . NV_MODFUNCS_TABLE . "` WHERE `func_id` = " . $values['func_id'];
			$db->sql_query( $sql );
			$sql = "DELETE FROM `" . NV_PREFIXLANG . "_modthemes` WHERE `func_id` = " . $values['func_id'];
			$db->sql_query( $sql );
			$is_delCache = true;
		}
	
		$db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "_weight`" );
		$db->sql_query( "OPTIMIZE TABLE `" . NV_MODFUNCS_TABLE . "`" );
		$db->sql_query( "OPTIMIZE TABLE `" . NV_PREFIXLANG . "_modthemes`" );
		$is_refresh = true;
	}

	if( ! empty( $new_funcs ) )
	{
		$mod_theme = "default";
	
		if( ! empty( $site_mods[$mod]['theme'] ) and file_exists( NV_ROOTDIR . '/themes/' . $site_mods[$mod]['theme'] . '/config.ini' ) )
		{
			$mod_theme = $site_mods[$mod]['theme'];
		}
	
		if( ! empty( $global_config['site_theme'] ) and file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini' ) )
		{
			$mod_theme = $global_config['site_theme'];
		}
	
		$xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $mod_theme . '/config.ini' );
		$layoutdefault = ( string )$xml->layoutdefault;

		$array_keys = array_keys( $new_funcs );
	
		foreach( $array_keys as $func )
		{
			$show_func = in_array( $func, $modfuncs ) ? 1 : 0;
			$sql = "INSERT INTO `" . NV_MODFUNCS_TABLE . "` (`func_id`, `func_name`, `func_custom_name`, `in_module`, `show_func`, `in_submenu`, `subweight`, `setting`) VALUES (NULL, " . $db->dbescape( $func ) . ", " . $db->dbescape( ucfirst( $func ) ) . ", " . $db->dbescape( $mod ) . ", " . $show_func . ", 0, 0, '')";
			$func_id = $db->sql_query_insert_id( $sql );
			if( $show_func )
			{
				$db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_modthemes` (`func_id`, `layout`, `theme`) VALUES ('" . $func_id . "'," . $db->dbescape( $layoutdefault ) . ", " . $db->dbescape( $mod_theme ) . ")" );
				nv_setup_block_module( $mod, $func_id );
			}
		}
	
		$is_refresh = true;
		$is_delCache = true;
	}

	if( $is_refresh )
	{
		nv_fix_subweight( $mod );
	
		$act_funcs = array();
		$weight_list = array();
	
		$sql = "SELECT * FROM `" . NV_MODFUNCS_TABLE . "` WHERE `in_module`=" . $db->dbescape( $mod ) . " AND `show_func`='1' ORDER BY `subweight` ASC";
		$result = $db->sql_query( $sql );
	
		while( $row = $db->sql_fetchrow( $result ) )
		{
			$func = $db->unfixdb( $row['func_name'] );
			
			$act_funcs[$func]['func_id'] = $row['func_id'];
			$act_funcs[$func]['layout'] = empty( $row['layout'] ) ? "" : $row['layout'];
			$act_funcs[$func]['show_func'] = $row['show_func'];
			$act_funcs[$func]['func_custom_name'] = $row['func_custom_name'];
			$act_funcs[$func]['in_submenu'] = $row['in_submenu'];
			$act_funcs[$func]['subweight'] = $row['subweight'];
			
			$weight_list[] = $row['subweight'];
		}
	}
	
	if( $is_delCache )
	{
		nv_del_moduleCache( 'modules' );
		nv_del_moduleCache( 'themes' );
	}

	$contents = array();
	$contents['caption'] = sprintf( $lang_module['funcs_list'], $custom_title );
	$contents['thead'] = array(
		$lang_module['funcs_subweight'],
		$lang_module['funcs_in_submenu'],
		$lang_module['funcs_title'],
		$lang_module['custom_title'],
		$lang_module['funcs_layout']
	);
	$contents['weight_list'] = $weight_list;
	
	foreach( $act_funcs as $funcs => $values )
	{
		if( $values['show_func'] )
		{
			$func_id = $values['func_id'];
			$contents['rows'][$func_id]['weight'] = array( $values['subweight'], "nv_chang_func_weight(" . $func_id . ");" );
	
			$contents['rows'][$func_id]['name'] = array(
				$funcs,
				$values['func_custom_name'],
				"nv_change_custom_name(" . $values['func_id'] . ",'action');"
			);
		
			$contents['rows'][$func_id]['layout'] = array( $values['layout'], "nv_chang_func_layout(" . $func_id . ");" );
			$contents['rows'][$func_id]['in_submenu'] = array( $values['in_submenu'], "nv_chang_func_in_submenu(" . $func_id . ");" );
			$contents['rows'][$func_id]['disabled'] = ( in_array( $funcs, $arr_in_submenu ) ) ? "" : " disabled";
		}
	}

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo aj_show_funcs_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}

if( $nv_Request->isset_request( 'aj', 'get' ) )
{
	if( filter_text_input( 'aj', 'get' ) == 'show_funcs' )
	{
		nv_show_funcs();
		die();
	}
}

$mod = filter_text_input( 'mod', 'get', '' );

if( empty( $mod ) or ! preg_match( $global_config['check_module'], $mod ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$sql = "SELECT `custom_title` FROM `" . NV_MODULES_TABLE . "` WHERE `title`=" . $db->dbescape( $mod );
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) != 1 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$row = $db->sql_fetchrow( $result );

$page_title = sprintf( $lang_module['funcs_list'], $row['custom_title'] );

$contents = array();

$contents['div_id'][0] = "show_funcs";
$contents['div_id'][1] = "action";

$contents['ajax'][0] = "nv_show_funcs('show_funcs');";
$contents['ajax'][1] = $nv_Request->isset_request( 'func_id,pos', 'get' ) ? "nv_bl_list(" . $nv_Request->get_int( 'func_id', 'get' ) . ",'" . filter_text_input( 'pos', 'get' ) . "','action');" : "";

$contents = show_funcs_theme( $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>