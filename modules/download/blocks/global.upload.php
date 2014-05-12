<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( defined( 'NV_SYSTEM' ) )
{
	if( ! nv_function_exists( "nv_mod_down_config" ) )
	{

		function nv_mod_down_config( $module_data )
		{
			global $site_mods, $module_info;
			$sql = "SELECT config_name,config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
			$list = nv_db_cache( $sql );
			$download_config = array();
			foreach( $list as $values )
			{
				$download_config[$values['config_name']] = $values['config_value'];
			}
			$download_config['upload_filetype'] = ! empty( $download_config['upload_filetype'] ) ? explode( ',', $download_config['upload_filetype'] ) : array();
			if( ! empty( $download_config['upload_filetype'] ) ) $download_config['upload_filetype'] = array_map( "trim", $download_config['upload_filetype'] );

			if( empty( $download_config['upload_filetype'] ) )
			{
				$download_config['is_upload'] = 0;
			}
			if( $download_config['is_addfile'] )
			{
				$download_config['is_addfile_allow'] = nv_user_in_groups( $download_config['groups_addfile'] );
			}
			else
			{
				$download_config['is_addfile_allow'] = false;
			}
			if( $download_config['is_addfile_allow'] and $download_config['is_upload'] )
			{
				$download_config['is_upload_allow'] = nv_user_in_groups( $download_config['groups_upload'] );
			}
			else
			{
				$download_config['is_upload_allow'] = false;
			}
			return $download_config;
		}
	}
	global $site_mods, $module_name, $module_info, $lang_module, $nv_Request;
	$content = '';
	$module = $block_config['module'];
	$mod_config = nv_mod_down_config( $module );
	if( isset( $site_mods[$module] ) )
	{
		if( $module == $module_name )
		{
			$lang_block_module = $lang_module;
		}
		else
		{

			$temp_lang_module = $lang_module;
			$lang_module = array();
			include NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php' ;
			$lang_block_module = $lang_module;
			$lang_module = $temp_lang_module;
		}
		$path = NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $site_mods[$module]['module_file'];
		if( ! file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $site_mods[$module]['module_file'] . '/block_upload.tpl' ) )
		{
			$path = NV_ROOTDIR . "/themes/default/modules/" . $site_mods[$module]['module_file'];
		}
		$xtpl = new XTemplate( "block_upload.tpl", $path );
		$xtpl->assign( 'LANG', $lang_block_module );
		if( $mod_config['is_upload_allow'] )
		{
			$xtpl->assign( 'LINK_UP', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=upload' );
			$xtpl->parse( 'main.have' );
			$xtpl->parse( 'main' );
			$content = $xtpl->text( 'main' );
		}
	}
}