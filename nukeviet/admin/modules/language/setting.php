<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$a = 1;

$page_title = $lang_module['nv_lang_setting'];

$array_type = array(
	$lang_module['nv_setting_type_0'],
	$lang_module['nv_setting_type_1'],
	$lang_module['nv_setting_type_2']
);

$xtpl = new XTemplate( "setting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

if( $nv_Request->get_string( 'checksessseting', 'post' ) == md5( session_id() . "seting" ) )
{
	$read_type = $nv_Request->get_int( 'read_type', 'post', 0 );
	$sql = "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value` =  '" . $read_type . "' WHERE `lang`='sys' AND `module` = 'global' AND `config_name` =  'read_type'";
	$result = $db->sql_query( $sql );
	
	nv_save_file_config_global();
	
	$xtpl->assign( 'INFO', $lang_module['nv_setting_save'] );
	$xtpl->assign( 'URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setting" );
	
	$xtpl->parse( 'info' );
	$contents = $xtpl->text( 'info' );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

if( $nv_Request->get_string( 'checksessshow', 'post' ) == md5( session_id() . "show" ) )
{
	$allow_sitelangs = $nv_Request->get_array( 'allow_sitelangs', 'post', array() );
	$allow_adminlangs = $nv_Request->get_array( 'allow_adminlangs', 'post', array() );

	$allow_adminlangs[] = NV_LANG_INTERFACE;
	$allow_adminlangs[] = NV_LANG_DATA;
	$allow_adminlangs[] = $global_config['site_lang'];

	foreach( $allow_sitelangs as $lang_temp )
	{
		$allow_adminlangs[] = $lang_temp;
	}

	$allow_sitelangs[] = $global_config['site_lang'];

	$allow_adminlangs = array_unique( $allow_adminlangs );

	$allow_sitelangs_temp = array_unique( $allow_sitelangs );
	$allow_sitelangs = array();

	foreach( $allow_sitelangs_temp as $lang_temp )
	{
		if( file_exists( NV_ROOTDIR . "/language/" . $lang_temp . "/global.php" ) )
		{
			$allow_sitelangs[] = $lang_temp;
		}
	}

	$allow_sitelangs_temp = array_unique( $allow_adminlangs );
	$allow_adminlangs = array();

	foreach( $allow_sitelangs_temp as $lang_temp )
	{
		if( file_exists( NV_ROOTDIR . "/language/" . $lang_temp . "/global.php" ) )
		{
			$allow_adminlangs[] = $lang_temp;
		}
	}

	$global_config['allow_sitelangs'] = $allow_sitelangs;
	$global_config['allow_adminlangs'] = $allow_adminlangs;

	$allow_sitelangs = implode( ",", $global_config['allow_sitelangs'] );
	$allow_adminlangs = implode( ",", $global_config['allow_adminlangs'] );

	$query = "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value` =  " . $db->dbescape( $allow_sitelangs ) . " WHERE `lang`='sys' AND `module` = 'global' AND `config_name` =  'allow_sitelangs'";
	$result = $db->sql_query( $query );

	$query = "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value` =  " . $db->dbescape( $allow_adminlangs ) . " WHERE `lang`='sys' AND `module` = 'global' AND `config_name` =  'allow_adminlangs'";
	$result = $db->sql_query( $query );

	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['nv_setting_save'], " allow sitelangs : " . $allow_sitelangs . ", allow adminlangs :" . $allow_adminlangs, $admin_info['userid'] );
	nv_save_file_config_global();

	$xtpl->assign( 'INFO', $lang_module['nv_setting_save'] );
	$xtpl->assign( 'URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setting" );
	
	$xtpl->parse( 'info' );
	$contents = $xtpl->text( 'info' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

$lang_array_exit = nv_scandir( NV_ROOTDIR . "/language", "/^[a-z]{2}+$/" );
$result = $db->sql_query( "SHOW COLUMNS FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file`" );
$lang_array_data_exit = array();

while( $row = $db->sql_fetch_assoc( $result ) )
{
	if( substr( $row['Field'], 0, 7 ) == "author_" )
	{
		$lang_array_data_exit[] = substr( $row['Field'], 7, 2 );
	}
}

$sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
$result = $db->sql_query( $sql );
$array_lang_setup = array();

while( $row = $db->sql_fetchrow( $result ) )
{
	$array_lang_setup[] = trim( $row['lang'] );
}


$a = 0;
while( list( $key, $value ) = each( $language_array ) )
{
	$arr_lang_func = array();
	$check_lang_exit = false;

	if( file_exists( NV_ROOTDIR . "/language/" . $key . "/global.php" ) )
	{
		$check_lang_exit = true;
		$arr_lang_func[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=read&amp;dirlang=" . $key . "&amp;checksess=" . md5( "readallfile" . session_id() ) . "\">" . $lang_module['nv_admin_read_all'] . "</a>";
	}

	if( in_array( $key, $lang_array_data_exit ) )
	{
		$arr_lang_func[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=write&amp;dirlang=" . $key . "&amp;checksess=" . md5( "writeallfile" . session_id() ) . "\">" . $lang_module['nv_admin_write'] . "</a>";
	}

	if( $check_lang_exit )
	{
		$arr_lang_func[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=download&amp;dirlang=" . $key . "&amp;checksess=" . md5( "downloadallfile" . session_id() ) . "\">" . $lang_module['nv_admin_download'] . "</a>";
	}

	if( ! empty( $arr_lang_func ) and ! in_array( $key, $global_config['allow_adminlangs'] ) )
	{
		$arr_lang_func[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=delete&amp;dirlang=" . $key . "&amp;checksess=" . md5( "deleteallfile" . session_id() ) . "\">" . $lang_module['nv_admin_delete'] . "</a>";
	}
	
	$xtpl->assign( 'ROW', array(
		'class' => ( ++ $a % 2 ) ? " class=\"second\"" : "",
		'key' => $key,
		'name' => $value['name'],
		'arr_lang_func' => implode( " - ", $arr_lang_func ),
		'allow_sitelangs' => ( $check_lang_exit and in_array( $key, $array_lang_setup ) ) ? ( in_array( $key, $global_config['allow_sitelangs'] ) ? " checked=\"checked\"" : "" ) : ' disabled=\"disabled\"',
		'allow_adminlangs' => ( $check_lang_exit ) ? ( in_array( $key, $global_config['allow_adminlangs'] ) ? " checked=\"checked\"" : "" ) : ' disabled=\"disabled\"',
	) );
	
	$xtpl->parse( 'main.loop' );
}

foreach( $array_type as $key => $value )
{	
	$xtpl->assign( 'TYPE', array(
		'key' => $key,
		'checked' => $global_config['read_type'] == $key ? " checked=\"checked\"" : "",
		'title' => $value
	) );
	
	$xtpl->parse( 'main.type' );
}

$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'CHECKSESSSHOW', md5( session_id() . "show" ) );
$xtpl->assign( 'CHECKSESSSETING', md5( session_id() . "seting" ) );
	
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>