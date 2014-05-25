<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$page_title = $lang_module['autoinstall_method_packet'];

$xtpl = new XTemplate( 'package_theme.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

if( $nv_Request->isset_request( NV_OP_VARIABLE, 'post' ) )
{
	$themename = $nv_Request->get_string( 'themename', 'post' );
	if( ( preg_match( $global_config['check_theme'], $themename ) or preg_match( $global_config['check_theme_mobile'], $themename ) ) AND is_dir( NV_ROOTDIR . '/themes/' . $themename ) )
	{
		$themefolder = array();

		$list = scandir( NV_ROOTDIR . '/themes/' . $themename );
		$array_no_zip = array(
			'.',
			'..',
			'config.ini'
		);
		foreach( $list as $file_i )
		{
			if( ! in_array( $file_i, $array_no_zip ) )
			{
				$themefolder[] = NV_ROOTDIR . '/themes/' . $themename . '/' . $file_i;
			}
		}

		$config_ini = '';
		if( $xml = @simplexml_load_file( NV_ROOTDIR . '/themes/' . $themename . '/config.ini' ) )
		{
			$info = $xml->xpath( 'info' );
			$layoutdefault = ( string )$xml->layoutdefault;
			$config_ini = "<?xml version='1.0'?>\n<theme>\n\t<info>\n\t\t<name>" . ( string )$info[0]->name . "</name>\n\t\t<author>" . ( string )$info[0]->author . "</author>\n\t\t<website>" . ( string )$info[0]->website . "</website>\n\t\t<description>" . ( string )$info[0]->description . "</description>\n\t\t<thumbnail>" . ( string )$info[0]->thumbnail . "</thumbnail>\n\t</info>\n\n\t<layoutdefault>" . $layoutdefault . "</layoutdefault>\n\n\t<positions>";

			$arr_theme['theme']['info'] = array( 'description' => ( string )$info[0]->description, );

			$position = $xml->xpath( 'positions' );
			$positions = $position[0]->position;
			for( $j = 0, $count = sizeof( $positions ); $j < $count; ++$j )
			{
				$config_ini .= "\n\t\t<position>\n\t\t\t<name>" . $positions[$j]->name . "</name>\n\t\t\t<tag>" . $positions[$j]->tag . "</tag>\n\t\t</position>\n";
			}

			$config_ini .= "\t</positions>";

			$array_layout_other = array();
			$result = $db->query( 'SELECT layout, in_module, func_name FROM ' . NV_PREFIXLANG . '_modthemes t1, ' . NV_MODFUNCS_TABLE . ' t2 WHERE t1.theme=' . $db->quote( $themename ) . ' AND t1.func_id=t2.func_id AND t1.layout!=' . $db->quote( $layoutdefault ) );
			while( list( $layout, $in_module, $func_name ) = $result->fetch( 3 ) )
			{
				$array_layout_other[$layout][$in_module][] = $func_name;
			}
			if( ! empty( $array_layout_other ) )
			{
				$config_ini .= "\n\n\t<setlayout>";
				foreach( $array_layout_other as $layout => $array_layout_i )
				{
					$config_ini .= "\n\t\t<layout>\n\t\t\t<name>" . $layout . "</name>";
					foreach( $array_layout_i as $in_module => $arr_func_name )
					{
						$config_ini .= "\n\t\t\t<funcs>" . $in_module . ":" . implode( ",", $arr_func_name ) . "</funcs>";
					}
					$config_ini .= "\n\t\t</layout>\n";
				}
				$config_ini .= "\t</setlayout>";
			}

			$array_layout_block = array();
			$array_not_all_func = array();
			$result = $db->query( 'SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme=' . $db->quote( $themename ) . ' ORDER BY position ASC, weight ASC' );
			while( $row = $result->fetch() )
			{
				$array_layout_block[] = $row;
				if( empty( $row['all_func'] ) )
				{
					$array_not_all_func[] = $row['bid'];
				}
			}
			if( ! empty( $array_layout_block ) )
			{
				$array_block_func = array();
				if( ! empty( $array_not_all_func ) )
				{
					$result = $db->query( 'SELECT bid, func_name, in_module FROM ' . NV_BLOCKS_TABLE . '_weight t1, ' . NV_MODFUNCS_TABLE . ' t2 WHERE t1.bid IN (' . implode( ',', $array_not_all_func ) . ') AND t1.func_id=t2.func_id' );
					while( list( $bid, $func_name, $in_module ) = $result->fetch( 3 ) )
					{
						$array_block_func[$bid][$in_module][] = $func_name;
					}
				}


				$config_ini .= "\n\n\t<setblocks>";
				foreach( $array_layout_block as $row )
				{
					if( ! empty( $row['config'] ) )
					{
						$row['config'] = htmlspecialchars($row['config']);
					}
					$config_ini .= "\n\t\t<block>";
					$config_ini .= "\n\t\t\t<module>" . $row['module'] . "</module>";
					$config_ini .= "\n\t\t\t<file_name>" . $row['file_name'] . "</file_name>";
					$config_ini .= "\n\t\t\t<title>" . $row['title'] . "</title>";
					$config_ini .= "\n\t\t\t<template>" . $row['template'] . "</template>";
					$config_ini .= "\n\t\t\t<position>" . $row['position'] . "</position>";
					$config_ini .= "\n\t\t\t<all_func>" . $row['all_func'] . "</all_func>";
					$config_ini .= "\n\t\t\t<config>" . $row['config'] . "</config>";

					if( empty( $row['all_func'] ) )
					{
						foreach( $array_block_func[$row['bid']] as $in_module => $arr_func_name )
						{
							$config_ini .= "\n\t\t\t<funcs>" . $in_module . ":" . implode( ",", $arr_func_name ) . "</funcs>";
						}
					}
					$config_ini .= "\n\t\t</block>\n";
				}
				$config_ini .= "\t</setblocks>";
			}

			$config_ini .= "\n</theme>";
		}
		else
		{
			$config_ini = file_get_contents( NV_ROOTDIR . '/themes/default/config.ini' );
		}

		$file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'theme_' . $themename . '_' . md5( nv_genpass( 10 ) . session_id() ) . '.zip';
		require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';

		$zip = new PclZip( $file_src );
		$zip->create( $themefolder, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/themes' );
		if(!empty($config_ini))
		{
			$zip->add( array( array(
					PCLZIP_ATT_FILE_NAME => 'config.ini',
					PCLZIP_ATT_FILE_CONTENT => $config_ini,
					PCLZIP_ATT_FILE_NEW_FULL_NAME => $themename . '/config.ini'
				) ) );
		}

		$filesize = @filesize( $file_src );
		$file_name = basename( $file_src );

		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['autoinstall_method_packet'], 'file name : ' . $themename . '.zip', $admin_info['userid'] );

		$linkgetfile = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getfile&amp;mod=nv4_theme_' . $themename . '.zip&amp;checkss=' . md5( $file_name . $client_info['session_id'] . $global_config['sitekey'] ) . '&amp;filename=' . $file_name;

		$xtpl->assign( 'LINKGETFILE', $linkgetfile );
		$xtpl->assign( 'THEMENAME', $themename );
		$xtpl->assign( 'FILESIZE', nv_convertfromBytes( $filesize ) );

		$xtpl->parse( 'complete' );
		$contents = $xtpl->text( 'complete' );
	}
	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
}
else
{
	$op = $nv_Request->get_string( NV_OP_VARIABLE, 'get', '' );

	$theme_list = nv_scandir( NV_ROOTDIR . '/themes', array( $global_config['check_theme'], $global_config['check_theme_mobile'] ) );

	foreach( $theme_list as $themes_i )
	{
		if( file_exists( NV_ROOTDIR . '/themes/' . $themes_i . '/config.ini' ) )
		{
			$xtpl->assign( 'THEME', $themes_i );
			$xtpl->parse( 'main.theme' );
		}
	}

	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
}