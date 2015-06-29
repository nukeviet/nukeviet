<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$page_title = $lang_module['nv_lang_check'];

$xtpl = new XTemplate( 'check.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$array_lang_exit = array();

$columns_array = $db->columns_array( NV_LANGUAGE_GLOBALTABLE . '_file' );

$add_field = true;
foreach ( $columns_array as $row )
{
	if( substr( $row['field'], 0, 7 ) == 'author_' )
	{
		$array_lang_exit[] .= trim( substr( $row['field'], 7, 2 ) );
	}
}

if( empty( $array_lang_exit ) )
{
	$xtpl->assign( 'URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setting' );

	$xtpl->parse( 'empty' );
	$contents = $xtpl->text( 'empty' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

$language_array_source = array( 'vi', 'en' );

$language_check_type = array(
	0 => $lang_module['nv_check_type_0'],
	1 => $lang_module['nv_check_type_1'],
	2 => $lang_module['nv_check_type_2']
);

$typelang = $nv_Request->get_title( 'typelang', 'post,get', '' );
$sourcelang = $nv_Request->get_title( 'sourcelang', 'post,get', '' );

$idfile = $nv_Request->get_int( 'idfile', 'post,get', 0 );
$check_type = $nv_Request->get_int( 'check_type', 'post,get', 0 );

if( $nv_Request->isset_request( 'idfile,savedata', 'post' ) and $nv_Request->get_string( 'savedata', 'post' ) == md5( $global_config['sitekey'] . session_id() ) )
{
	$pozlang = $nv_Request->get_array( 'pozlang', 'post', array() );

	if( ! empty( $pozlang ) and isset( $language_array[$typelang] ) )
	{
		foreach( $pozlang as $id => $lang_value )
		{
			$lang_value = trim( strip_tags( $lang_value, NV_ALLOWED_HTML_LANG ) );
			if( ! empty( $lang_value ) )
			{
				$sth = $db->prepare( 'UPDATE ' . NV_LANGUAGE_GLOBALTABLE . ' SET lang_' . $typelang . '= :lang_value, update_' . $typelang . '= ' . NV_CURRENTTIME . ' WHERE id= :id' );
				$sth->bindParam( ':id', $id, PDO::PARAM_INT );
				$sth->bindParam( ':lang_value', $lang_value, PDO::PARAM_STR );
				$sth->execute();
			}
		}
	}
}
$array_files = array();

$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

foreach( $language_array as $key => $value )
{
	if( in_array( $key, $array_lang_exit ) )
	{
		$xtpl->assign( 'LANGUAGE', array(
			'key' => $key,
			'selected' => ( $key == $typelang ) ? ' selected="selected"' : '',
			'title' => $value['name']
		) );

		$xtpl->parse( 'main.language' );
	}
}

foreach( $language_array_source as $key )
{
	if( in_array( $key, $array_lang_exit ) )
	{
		$xtpl->assign( 'LANGUAGE_SOURCE', array(
			'key' => $key,
			'selected' => ( $key == $sourcelang ) ? ' selected="selected"' : '',
			'title' => $language_array[$key]['name']
		) );

		$xtpl->parse( 'main.language_source' );
	}
}

$sql = 'SELECT idfile, module, admin_file FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file ORDER BY idfile ASC';
$result = $db->query( $sql );
while( list( $idfile_i, $module, $admin_file, ) = $result->fetch( 3 ) )
{
	switch( $admin_file )
	{
		case '1':
			$langsitename = $lang_module['nv_lang_admin'];
			break;
		case '0':
			$langsitename = $lang_module['nv_lang_site'];
			break;
		default:
			$langsitename = $admin_file;
			break;
	}

	$xtpl->assign( 'LANGUAGE_AREA', array(
		'key' => $idfile_i,
		'selected' => ( $idfile_i == $idfile ) ? ' selected="selected"' : '',
		'title' => $module . " " . $langsitename
	) );

	$xtpl->parse( 'main.language_area' );
	$array_files[$idfile_i] = $module . " " . $langsitename;
}

foreach( $language_check_type as $key => $value )
{
	$xtpl->assign( 'LANGUAGE_CHECK_TYPE', array(
		'key' => $key,
		'selected' => ( $key == $check_type ) ? ' selected="selected"' : '',
		'title' => $value
	) );

	$xtpl->parse( 'main.language_check_type' );
}

$submit = $nv_Request->get_int( 'submit', 'post,get', 0 );

if( $submit > 0 and in_array( $sourcelang, $array_lang_exit ) and in_array( $typelang, $array_lang_exit ) )
{
	$array_where = array();
	if( $idfile > 0 )
	{
		$array_where[] = 'idfile=' . $idfile;
	}

	if( $check_type == 0 )
	{
		$array_where[] = "update_" . $typelang . "=0";
	}
	elseif( $check_type == 1 )
	{
		$array_where[] = "lang_" . $typelang . "=lang_" . $sourcelang;
	}

	if( empty( $array_where ) )
	{
		$query = 'SELECT id, idfile, lang_key, lang_' . $typelang . ' as datalang, lang_' . $sourcelang . ' as sourcelang FROM ' . NV_LANGUAGE_GLOBALTABLE . ' ORDER BY id ASC';
	}
	else
	{
		$query = 'SELECT id, idfile, lang_key, lang_' . $typelang . ' as datalang, lang_' . $sourcelang . ' as sourcelang FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE ' . implode( ' AND ', $array_where ) . ' ORDER BY id ASC';
	}
	$result = $db->query( $query );

	$array_lang_data = array();

	while( list( $id, $idfile_i, $lang_key, $datalang, $datasourcelang ) = $result->fetch( 3 ) )
	{
		$array_lang_data[$idfile_i][$id] = array(
			'lang_key' => $lang_key,
			'datalang' => $datalang,
			'sourcelang' => $datasourcelang
		);
	}

	if( ! empty( $array_lang_data ) )
	{
		$xtpl->assign( 'DATA', array(
			'typelang' => $typelang,
			'sourcelang' => $sourcelang,
			'check_type' => $check_type,
			'idfile' => $idfile,
			'savedata' => md5( $global_config['sitekey'] . session_id() )
		) );

		foreach( $array_lang_data as $idfile_i => $array_lang_file )
		{
			$xtpl->assign( 'CAPTION', $array_files[$idfile_i] );

			foreach( $array_lang_file as $id => $row )
			{
				$xtpl->assign( 'ROW', array(
					'stt' => ++$i,
					'lang_key' => $row['lang_key'],
					'datalang' => nv_htmlspecialchars( $row['datalang'] ),
					'id' => $id,
					'sourcelang' => nv_htmlspecialchars( $row['sourcelang'] )
				) );

				$xtpl->parse( 'main.data.lang.loop' );
			}

			$xtpl->parse( 'main.data.lang' );
		}

		$xtpl->parse( 'main.data' );
	}
	else
	{
		$xtpl->parse( 'main.nodata' );
	}

	unset( $array_lang_data, $array_files );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';