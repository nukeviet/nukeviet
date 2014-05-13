<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$page_title = $lang_module['nv_admin_copy'];

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

$xtpl = new XTemplate( 'copy.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

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

if( $nv_Request->isset_request( 'newslang,typelang,checksess', 'post' ) and $nv_Request->get_string( 'checksess', 'post' ) == md5( session_id() ) )
{
	$newslang = $nv_Request->get_title( 'newslang', 'post', '' );
	$typelang = $nv_Request->get_title( 'typelang', 'post', '' );

	if( $typelang == '-vi' )
	{
		$typelang = '-';
		$replace_lang_vi = true;
	}
	else
	{
		$replace_lang_vi = false;
	}

	if( isset( $language_array[$newslang] ) )
	{
		nv_admin_add_field_lang( $newslang );

		if( $replace_lang_vi == true )
		{
			nv_copyfile( NV_ROOTDIR . '/js/language/vi.js', NV_ROOTDIR . '/js/language/' . $newslang . '.js' );
			$db->query( 'UPDATE ' . NV_LANGUAGE_GLOBALTABLE . '_file SET author_' . $newslang . '=author_vi' );

			$query = 'SELECT id, lang_vi FROM ' . NV_LANGUAGE_GLOBALTABLE;
			$result = $db->query( $query );
			while( list( $id, $author_lang ) = $result->fetchrow( 3 ) )
			{
				$author_lang = nv_EncString( $author_lang );
				$sth = $db->prepare( 'UPDATE ' . NV_LANGUAGE_GLOBALTABLE . ' SET lang_' . $newslang . ' = :author_lang WHERE id = ' . $id );
				$sth->bindParam( ':author_lang', $author_lang, PDO::PARAM_STR );
				$sth->execute();

			}
		}
		elseif( isset( $language_array[$typelang] ) )
		{
			nv_copyfile( NV_ROOTDIR . '/js/language/' . $typelang . '.js', NV_ROOTDIR . '/js/language/' . $newslang . '.js' );
			$db->query( 'UPDATE ' . NV_LANGUAGE_GLOBALTABLE . '_file SET author_' . $newslang . '=author_' . $typelang );
			$db->query( 'UPDATE ' . NV_LANGUAGE_GLOBALTABLE . ' SET lang_' . $newslang . '=lang_' . $typelang );
		}

		$nv_Request->set_Cookie( 'dirlang', $newslang, NV_LIVE_COOKIE_TIME );

		$xtpl->assign( 'URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=interface' );

		$xtpl->parse( 'copyok' );
		$contents = $xtpl->text( 'copyok' );

		include NV_ROOTDIR . '/includes/header.php';
		echo nv_admin_theme( $contents );
		include NV_ROOTDIR . '/includes/footer.php';
	}
}
$lang_array_file = array();

$lang_array_file_temp = nv_scandir( NV_ROOTDIR . '/language', '/^[a-z]{2}+$/' );
foreach( $lang_array_file_temp as $value )
{
	if( file_exists( NV_ROOTDIR . '/language/' . $value . '/global.php' ) )
	{
		$lang_array_file[] = $value;
	}
}

$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'CHECKSESS', md5( session_id() ) );

foreach( $language_array as $key => $value )
{
	if( ! in_array( $key, $array_lang_exit ) and ! in_array( $key, $lang_array_file ) )
	{
		$xtpl->assign( 'NEWSLANG', array( 'key' => $key, 'title' => $value['name'] . ' - ' . $value['language'] ) );
		$xtpl->parse( 'main.newslang' );
	}
}

if( in_array( 'vi', $array_lang_exit ) )
{
	$xtpl->assign( 'NAME', $language_array['vi']['name'] );
	$xtpl->parse( 'main.typelang' );
}

foreach( $language_array as $key => $value )
{
	if( in_array( $key, $array_lang_exit ) )
	{
		$xtpl->assign( 'TYPELANG', array( 'key' => $key, 'title' => $value['name'] . ' - ' . $value['language'] ) );

		$xtpl->parse( 'main.typelang_1' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';