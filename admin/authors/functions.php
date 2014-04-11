<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

unset( $page_title, $select_options );

$menu_top = array(
	'title' => $module_name,
	'module_file' => '',
	'custom_title' => $lang_global['mod_authors']
);
define( 'NV_IS_FILE_AUTHORS', true );

/**
 * nv_admin_add_result()
 *
 * @param mixed $result
 * @return
 */
function nv_admin_add_result( $result )
{
	global $module_name, $lang_global, $lang_module, $page_title, $global_config;
	if( ! defined( 'NV_IS_GODADMIN' ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}
	//parse content
	$xtpl = new XTemplate( 'add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors' );

	$lev = ( $result['lev'] == 2 ) ? $lang_global['level2'] : $lang_global['level3'];
	$contents = array();
	$contents['admin_id'] = $result['admin_id'];
	$contents['title'] = $lang_module['nv_admin_add_title'];
	$contents['info'] = array();
	$contents['info']['lev'] = array( $lang_module['lev'], $lev );
	$contents['info']['modules'] = array( $lang_module['nv_admin_modules'], $result['modules'] );
	$contents['info']['position'] = array( $lang_module['position'], $result['position'] );
	$contents['info']['editor'] = array( $lang_module['editor'], ( ! empty( $result['editor'] ) ? $result['editor'] : $lang_module['not_use'] ) );
	$contents['info']['allow_files_type'] = array( $lang_module['allow_files_type'], ( ! empty( $result['allow_files_type'] ) ? implode( ', ', $result['allow_files_type'] ) : $lang_global['no'] ) );
	$contents['info']['allow_modify_files'] = array( $lang_module['allow_modify_files'], ( $result['allow_modify_files'] ? $lang_global['yes'] : $lang_global['no'] ) );
	$contents['info']['allow_create_subdirectories'] = array( $lang_module['allow_create_subdirectories'], ( $result['allow_create_subdirectories'] ? $lang_global['yes'] : $lang_global['no'] ) );
	$contents['info']['allow_modify_subdirectories'] = array( $lang_module['allow_modify_subdirectories'], ( $result['allow_modify_subdirectories'] ? $lang_global['yes'] : $lang_global['no'] ) );
	$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add';
	$contents['go_edit'] = array( $lang_global['edit'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id'] );
	$contents['go_home'] = array( $lang_module['main'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );

	$xtpl->assign( 'TITLE', $contents['title'] );
	$a = 0;
	foreach( $contents['info'] as $key => $value )
	{
		if( ! empty( $value[1] ) )
		{
			$xtpl->assign( 'VALUE0', $value[0] );
			$xtpl->assign( 'VALUE1', $value[1] );
			$xtpl->parse( 'add_result.loop' );
			++$a;
		}
	}
	$xtpl->assign( 'ACTION', $contents['action'] );
	$xtpl->assign( 'ADM_ID', $contents['admin_id'] );
	$xtpl->assign( 'EDIT_HREF', $contents['go_edit'][1] );
	$xtpl->assign( 'EDIT', $contents['go_edit'][0] );
	$xtpl->assign( 'HOME_HREF', $contents['go_home'][1] );
	$xtpl->assign( 'HOME', $contents['go_home'][0] );

	$page_title = $lang_module['nv_admin_add_result'];

	$xtpl->parse( 'add_result' );
	$contents = $xtpl->text( 'add_result' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * nv_admin_edit_result()
 *
 * @param mixed $result
 * @return
 */
function nv_admin_edit_result( $result )
{
	global $lang_module, $lang_global, $page_title, $module_name, $global_config;
	$xtpl = new XTemplate( 'edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors' );
	$contents = array();
	$contents['title'] = sprintf( $lang_module['nv_admin_edit_result_title'], $result['login'] );

	$contents['thead'] = array( $lang_module['field'], $lang_module['old_value'], $lang_module['new_value'] );

	$contents['change'] = $result['change'];
	$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id'];
	$contents['download'] = $lang_module['nv_admin_add_download'];
	$contents['sendmail'] = $lang_module['nv_admin_add_sendmail'];
	$contents['go_home'] = array( $lang_module['main'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	$contents['go_edit'] = array( $lang_global['edit'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id'] );

	$page_title = sprintf( $lang_module['nv_admin_edit_result'], $result['login'] );

	$xtpl->assign( 'TITLE', $contents['title'] );
	$xtpl->assign( 'THEAD0', $contents['thead'][0] );
	$xtpl->assign( 'THEAD1', $contents['thead'][1] );
	$xtpl->assign( 'THEAD2', $contents['thead'][2] );

	$a = 0;
	foreach( $contents['change'] as $key => $value )
	{
		$xtpl->assign( 'VALUE0', $value[0] );
		$xtpl->assign( 'VALUE1', $value[1] );
		$xtpl->assign( 'VALUE2', $value[2] );
		$xtpl->parse( 'edit_resuilt.loop' );
		++$a;
	}

	$xtpl->assign( 'DOWNLOAD', $contents['download'] );
	$xtpl->assign( 'SENDMAIL', $contents['sendmail'] );
	$xtpl->assign( 'EDIT_NAME', $contents['go_edit'][0] );
	$xtpl->assign( 'EDIT_HREF', $contents['go_edit'][1] );
	$xtpl->assign( 'HOME_NAME', $contents['go_home'][0] );
	$xtpl->assign( 'HOME_HREF', $contents['go_home'][1] );

	$xtpl->parse( 'edit_resuilt' );
	$contents = $xtpl->text( 'edit_resuilt' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}