<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$xtpl = new XTemplate( "interface.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$array_lang_exit = array();

$result = $db->sql_query( "SHOW COLUMNS FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file`" );

while( $row = $db->sql_fetch_assoc( $result ) )
{
	if( substr( $row['Field'], 0, 7 ) == "author_" )
	{
		$array_lang_exit[] .= trim( substr( $row['Field'], 7, 2 ) );
	}
}

$select_options = array();

foreach( $array_lang_exit as $langkey )
{
	$select_options[NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;dirlang=" . $langkey] = $language_array[$langkey]['name'];
}

$dirlang_old = $nv_Request->get_string( 'dirlang', 'cookie', NV_LANG_DATA );
$dirlang = $nv_Request->get_string( 'dirlang', 'get', $dirlang_old );

if( ! in_array( $dirlang, $array_lang_exit ) )
{
	$dirlang = $global_config['site_lang'];
}

if( $dirlang_old != $dirlang )
{
	$nv_Request->set_Cookie( 'dirlang', $dirlang, NV_LIVE_COOKIE_TIME );
}

$sql = "SELECT `idfile`, `module`, `admin_file`, `langtype`, `author_" . $dirlang . "` FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file` ORDER BY `idfile` ASC";
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) == 0 )
{	
	$xtpl->assign( 'URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=read&dirlang=" . $dirlang . "&checksess=" . md5( "readallfile" . session_id() ) );

	$xtpl->parse( 'empty' );
	$contents = $xtpl->text( 'empty' );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

$page_title = $lang_module['nv_lang_interface'] . ": " . $language_array[$dirlang]['name'];

$a = 0;
while( list( $idfile, $module, $admin_file, $langtype, $author_lang ) = $db->sql_fetchrow( $result ) )
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

	if( empty( $author_lang ) )
	{
		$array_translator = array();
		$array_translator['author'] = "";
		$array_translator['createdate'] = "";
		$array_translator['copyright'] = "";
		$array_translator['info'] = "";
		$array_translator['langtype'] = "";
	}
	else
	{
		eval( '$array_translator = ' . $author_lang . ';' );
	}
		
	$xtpl->assign( 'ROW', array(
		'class' => ( ++ $a % 2 ) ? " class=\"second\"" : "",
		'stt' => $a,
		'module' => $module,
		'langsitename' => $langsitename,
		'author' => $array_translator['author'],
		'createdate' => $array_translator['createdate'],
		'url_edit' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit&amp;dirlang=" . $dirlang . "&amp;idfile=" . $idfile . "&amp;checksess=" . md5( $idfile . session_id() ),
		'url_export' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=write&amp;dirlang=" . $dirlang . "&amp;idfile=" . $idfile . "&amp;checksess=" . md5( $idfile . session_id() )
	) );
	
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>