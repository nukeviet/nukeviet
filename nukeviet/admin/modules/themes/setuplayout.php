<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$set_layout_site = false;
$select_options = array();
$theme_array = nv_scandir( NV_ROOTDIR . "/themes", array( $global_config['check_theme'], $global_config['check_theme_mobile'] ) );

foreach( $theme_array as $themes_i )
{
	if( file_exists( NV_ROOTDIR . '/themes/' . $themes_i . '/config.ini' ) )
	{
		$select_options[NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setuplayout&amp;selectthemes=" . $themes_i] = $themes_i;
	}
}

$selectthemes_old = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );
$selectthemes = $nv_Request->get_string( 'selectthemes', 'get', $selectthemes_old );

if( ! in_array( $selectthemes, $theme_array ) )
{
	$selectthemes = "default";
}

if( $selectthemes_old != $selectthemes )
{
	$nv_Request->set_Cookie( 'selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME );
}

$layout_array = nv_scandir( NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout'] );

if( ! empty( $layout_array ) )
{
	$layout_array = preg_replace( $global_config['check_op_layout'], "\\1", $layout_array );
}
$array_layout_func_default = array();

if( file_exists( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini' ) )
{
	$xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini' );
	$layoutdefault = ( string )$xml->layoutdefault;
	$layout = $xml->xpath( 'setlayout/layout' );

	for( $i = 0, $count = sizeof( $layout ); $i < $count; ++$i )
	{
		$layout_name = ( string )$layout[$i]->name;

		if( in_array( $layout_name, $layout_array ) )
		{
			$layout_funcs = $layout[$i]->xpath( 'funcs' );

			for( $j = 0, $sizeof = sizeof( $layout_funcs ); $j < $sizeof; ++$j )
			{
				$mo_funcs = ( string )$layout_funcs[$j];
				$mo_funcs = explode( ":", $mo_funcs );
				$m = $mo_funcs[0];
				$arr_f = explode( ",", $mo_funcs[1] );

				foreach( $arr_f as $f )
				{
					$array_layout_func_default[$m][$f] = $layout_name;
				}
			}
		}
	}

	$page_title = $lang_module['setup_layout'] . ':' . $selectthemes;

	$xtpl = new XTemplate( "setuplayout.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );

	if( $nv_Request->isset_request( 'save', 'post' ) and $nv_Request->isset_request( 'func', 'post' ) )
	{
		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['setup_layout'] . ' theme: "' . $selectthemes . '"', '', $admin_info['userid'] );

		$func_arr_save = $nv_Request->get_array( 'func', 'post' );

		foreach( $func_arr_save as $func_id => $layout_name )
		{
			if( in_array( $layout_name, $layout_array ) )
			{
				$sql = "UPDATE `" . NV_PREFIXLANG . "_modthemes` SET `layout`=" . $db->dbescape_string( $layout_name ) . " WHERE `func_id`='" . intval( $func_id ) . "' AND `theme`=" . $db->dbescape_string( $selectthemes ) . "";
				$db->sql_query( $sql );
			}
		}

		$set_layout_site = true;

		$xtpl->parse( 'main.complete' );
	}

	$array_layout_func_data = array();
	$fnsql = "SELECT `func_id`, `layout` FROM `" . NV_PREFIXLANG . "_modthemes` WHERE `theme`='" . $selectthemes . "'";
	$fnresult = $db->sql_query( $fnsql );

	while( list( $func_id, $layout ) = $db->sql_fetchrow( $fnresult ) )
	{
		$array_layout_func_data[$func_id] = $layout;
	}

	if( ! isset( $array_layout_func_data[0] ) )
	{
		$db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_modthemes` (`func_id`, `layout`, `theme`) VALUES ('0'," . $db->dbescape( $layoutdefault ) . ", " . $db->dbescape( $selectthemes ) . ")" );
		$set_layout_site = true;
	}
	elseif( $array_layout_func_data[0] != $layoutdefault )
	{
		$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_modthemes` SET `layout`=" . $db->dbescape_string( $layoutdefault ) . " WHERE `func_id`='0' AND `theme`=" . $db->dbescape_string( $selectthemes ) . "" );
		$set_layout_site = true;
	}

	$array_layout_func = array();
	$fnsql = "SELECT `func_id`, `func_name`, `func_custom_name`, `in_module` FROM `" . NV_MODFUNCS_TABLE . "` WHERE show_func='1' ORDER BY `subweight` ASC";
	$fnresult = $db->sql_query( $fnsql );

	while( list( $func_id, $func_name, $func_custom_name, $in_module ) = $db->sql_fetchrow( $fnresult ) )
	{
		if( isset( $array_layout_func_data[$func_id] ) and ! empty( $array_layout_func_data[$func_id] ) )
		{
			$layout_name = $array_layout_func_data[$func_id];

			if( ! in_array( $layout_name, $layout_array ) )
			{
				$layout_name = $layoutdefault;
				$sql = "UPDATE `" . NV_PREFIXLANG . "_modthemes` SET `layout`=" . $db->dbescape_string( $layout_name ) . " WHERE `func_id`='" . intval( $func_id ) . "' AND `theme`=" . $db->dbescape_string( $selectthemes ) . "";
				$db->sql_query( $sql );

				$set_layout_site = true;
			}
		}
		else
		{
			$layout_name = ( isset( $array_layout_func_default[$in_module][$func_name] ) ) ? $array_layout_func_default[$in_module][$func_name] : $layoutdefault;
			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_modthemes` (`func_id`, `layout`, `theme`) VALUES ('" . $func_id . "'," . $db->dbescape( $layout_name ) . ", " . $db->dbescape( $selectthemes ) . ")";
			$db->sql_query( $sql );

			$set_layout_site = true;
		}

		$array_layout_func[$in_module][$func_name] = array(
			$func_id,
			$func_custom_name,
			$layout_name );
	}

	if( $set_layout_site )
	{
		nv_del_moduleCache( 'themes' );
		nv_del_moduleCache( 'modules' );
	}

	$sql = "SELECT `title`, `custom_title` FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	$number_func = $db->sql_numrows( $result );

	$i = 1;
	while( list( $mod_name, $mod_name_title ) = $db->sql_fetchrow( $result ) )
	{
		if( isset( $array_layout_func[$mod_name] ) )
		{
			$xtpl->assign( 'MOD_NAME_TITLE', $mod_name_title );

			$array_layout_func_mod = $array_layout_func[$mod_name];

			foreach( $array_layout_func_mod as $func_name => $func_arr_val )
			{
				foreach( $layout_array as $value )
				{
					$xtpl->assign( 'OPTION', array( 'key' => $value, 'selected' => ( $func_arr_val[2] == $value ) ? " selected=\"selected\"" : "" ) );
					$xtpl->parse( 'main.loop.func.option' );
				}

				$xtpl->assign( 'FUNC_ARR_VAL', $func_arr_val );
				$xtpl->parse( 'main.loop.func' );
			}

			if( $i % 3 == 0 and $i < $number_func )
			{
				$xtpl->parse( 'main.loop.endtr' );
			}
			else
			{
				$xtpl->parse( 'main.loop.endtd' );
			}

			++$i;

			$xtpl->parse( 'main.loop' );
		}
	}

	--$i;

	if( $i % 3 != 0 )
	{
		$i = $i % 3;
		for( $j = $i; $j < 3; ++$j )
		{
			$xtpl->parse( 'main.fixend' );
		}
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>