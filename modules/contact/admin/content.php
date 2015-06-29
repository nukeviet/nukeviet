<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['content'];

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$bodytext = $nv_Request->get_editor( 'bodytext', '', NV_ALLOWED_HTML_TAGS );

	if ( isset( $module_config[$module_name]['bodytext'] ) )
	{
		$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = 'bodytext' AND lang = '" . NV_LANG_DATA . "' AND module=:module" );
	}
	else
	{
		$sth = $db->prepare( "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . NV_LANG_DATA . "', :module, 'bodytext', :config_value)" );
	}

	$sth->bindParam( ':module', $module_name, PDO::PARAM_STR );
	$sth->bindParam( ':config_value', $bodytext, PDO::PARAM_STR, strlen( $bodytext ) );
	$sth->execute();

	nv_del_moduleCache( 'settings' );

	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	die();
}

$bodytext = ( isset( $module_config[$module_name]['bodytext'] ) ) ? nv_editor_br2nl( $module_config[$module_name]['bodytext'] ) : '';

$is_edit = $nv_Request->get_int( 'is_edit', 'get', 0 );
if( empty( $bodytext ) ) $is_edit = 1;

$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

if( $is_edit )
{
	$bodytext = htmlspecialchars( nv_editor_br2nl( $bodytext ) );

	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op );

	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$data = nv_aleditor( 'bodytext', '99%', '300px', $bodytext );
	}
	else
	{
		$data = "<textarea style=\"width: 99%\" name=\"bodytext\" id=\"bodytext\" cols=\"20\" rows=\"8\">" . $bodytext . "</textarea>";
	}

	$xtpl->assign( 'DATA', $data );

	$xtpl->parse( 'main.edit' );
}
else
{
	$xtpl->assign( 'DATA', $bodytext );
	$xtpl->assign( 'URL_EDIT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;is_edit=1' );

	$xtpl->parse( 'main.data' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';