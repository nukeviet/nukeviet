<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if ( ! defined( 'NV_IS_MOD_LAWS' ) ) die( 'Stop!!!' );

function nv_theme_laws_main ( $array_data, $generate_page )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'generate_page', $generate_page );

	$i = 1;
    foreach( $array_data as $row )
	{
		$row['class'] = ( $i % 2 == 0 ) ? " class=\"bg\"" : "";
		$row['publtime'] = nv_date( "d/m/Y", $row['publtime'] );
		$row['exptime'] = nv_date( "d/m/Y", $row['exptime'] );
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
		$i ++;
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_theme_laws_detail ( $array_data )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $nv_laws_listcat, $nv_laws_listarea, $nv_laws_listsubject, $client_info;

    $xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	
	$array_data['publtime'] = $array_data['publtime'] ? nv_date( "d/m/Y", $array_data['publtime'] ) : "N/A";
	$array_data['startvalid'] = $array_data['startvalid'] ? nv_date( "d/m/Y", $array_data['startvalid'] ) : "N/A";
	$array_data['exptime'] = $array_data['exptime'] ? nv_date( "d/m/Y", $array_data['exptime'] ) : "N/A";
	
	$array_data['cat'] = $nv_laws_listcat[$array_data['cid']]['title'];
	$array_data['area'] = $nv_laws_listarea[$array_data['aid']]['title'];
	$array_data['subject'] = $nv_laws_listsubject[$array_data['sid']]['title'];
	
    $xtpl->assign( 'DATA', $array_data );
	
	if( ! empty( $array_data['bodytext'] ) )
	{
		$xtpl->parse( 'main.bodytext' );
	}
	
	if( ! empty( $array_data['relatement'] ) )
	{
		foreach( $array_data['relatement'] as $relatement )
		{
			$xtpl->assign( 'relatement', $relatement );
			$xtpl->parse( 'main.relatement.loop' );
		}
		$xtpl->parse( 'main.relatement' );
	}

	if( ! empty( $array_data['replacement'] ) )
	{
		foreach( $array_data['replacement'] as $replacement )
		{
			$xtpl->assign( 'replacement', $replacement );
			$xtpl->parse( 'main.replacement.loop' );
		}
		$xtpl->parse( 'main.replacement' );
	}

	if( ! empty( $array_data['unreplacement'] ) )
	{
		foreach( $array_data['unreplacement'] as $unreplacement )
		{
			$xtpl->assign( 'unreplacement', $unreplacement );
			$xtpl->parse( 'main.unreplacement.loop' );
		}
		$xtpl->parse( 'main.unreplacement' );
	}
	
	if ( nv_user_in_groups( $array_data['groups_download'] ) )
	{
		if( ! empty( $array_data['files'] ) )
		{
			foreach( $array_data['files'] as $file )
			{
				$xtpl->assign( 'FILE', $file );
				$xtpl->parse( 'main.files.loop' );
			}
			$xtpl->parse( 'main.files' );
		}
	}
	elseif( $array_data['groups_download'] == 4 )
	{
		$xtpl->assign( 'URLLOGIN', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_base64_encode( $client_info['selfurl'] ) );
		$xtpl->parse( 'main.logindownload' );
	}
	else
	{
		$xtpl->parse( 'main.nodownload' );
	}
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_theme_laws_search ( $array_data, $generate_page, $all_page )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );

    $xtpl->assign( 'generate_page', $generate_page );
    $xtpl->assign( 'NUMRESULT', sprintf( $lang_module['s_result_num'], $all_page ) );

	$i = 1;
    foreach( $array_data as $row )
	{
		$row['class'] = ( $i % 2 == 0 ) ? " class=\"bg\"" : "";
		$row['publtime'] = nv_date( "d/m/Y", $row['publtime'] );
		$row['exptime'] = nv_date( "d/m/Y", $row['exptime'] );
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
		$i ++;
	}

	if( empty( $array_data ) )
	{
		$xtpl->parse( 'empty' );
		return $xtpl->text( 'empty' );
	}
	
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_theme_laws_area ( $array_data, $generate_page, $cat )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'CAT', $cat );
	
    $xtpl->assign( 'generate_page', $generate_page );

	$i = 1;
    foreach( $array_data as $row )
	{
		$row['class'] = ( $i % 2 == 0 ) ? " class=\"bg\"" : "";
		$row['publtime'] = nv_date( "d/m/Y", $row['publtime'] );
		$row['exptime'] = nv_date( "d/m/Y", $row['exptime'] );
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
		$i ++;
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_theme_laws_cat ( $array_data, $generate_page, $cat )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'CAT', $cat );

    $xtpl->assign( 'generate_page', $generate_page );

	$i = 1;
    foreach( $array_data as $row )
	{
		$row['class'] = ( $i % 2 == 0 ) ? " class=\"bg\"" : "";
		$row['publtime'] = nv_date( "d/m/Y", $row['publtime'] );
		$row['exptime'] = nv_date( "d/m/Y", $row['exptime'] );
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
		$i ++;
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_theme_laws_subject ( $array_data, $generate_page, $cat )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'CAT', $cat );
	
    $xtpl->assign( 'generate_page', $generate_page );

	$i = 1;
    foreach( $array_data as $row )
	{
		$row['class'] = ( $i % 2 == 0 ) ? " class=\"bg\"" : "";
		$row['publtime'] = nv_date( "d/m/Y", $row['publtime'] );
		$row['exptime'] = nv_date( "d/m/Y", $row['exptime'] );
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
		$i ++;
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_theme_laws_signer( $array_data, $generate_page, $cat )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'CAT', $cat );
	
    $xtpl->assign( 'generate_page', $generate_page );

	$i = 1;
    foreach( $array_data as $row )
	{
		$row['class'] = ( $i % 2 == 0 ) ? " class=\"bg\"" : "";
		$row['publtime'] = nv_date( "d/m/Y", $row['publtime'] );
		$row['exptime'] = nv_date( "d/m/Y", $row['exptime'] );
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
		$i ++;
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

?>