<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 1:58
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

global $sys_info;

$submenu['main'] = $lang_module['site_config'];

if( defined( 'NV_IS_GODADMIN' ) )
{
	$submenu['system'] = $lang_module['global_config'];
	$submenu['statistics'] = $lang_module['global_statistics'];
	$submenu['cronjobs'] = $lang_global['mod_cronjobs'];
	$submenu['smtp'] = $lang_module['smtp_config'];
	$submenu['ftp'] = $lang_module['ftp_config'];
	$submenu['pagetitle'] = $lang_module['pagetitle'];
	$submenu['metatags'] = $lang_module['metaTagsConfig'];
	$submenu['robots'] = $lang_module['robots'];
	$submenu['bots'] = $lang_module['bots_config'];
	$submenu['banip'] = $lang_module['banip'];
	$submenu['uploadconfig'] = $lang_module['uploadconfig'];
}

if( $module_name == "settings" )
{
	if( defined( 'NV_IS_GODADMIN' ) )
	{
		$allow_func = array( 'main', 'system', 'statistics', 'bots', 'robots', 'smtp', 'ftp', 'pagetitle', 'metatags', 'banip', 'uploadconfig', 'cronjobs','cronjobs_add', 'cronjobs_edit', 'cronjobs_del', 'cronjobs_act' );
	}
	else
	{
		$allow_func = array( 'main' );
	}

	$menu_top = array( "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_settings'] );
	
	unset( $page_title, $select_options );

	define( 'NV_IS_FILE_SETTINGS', true );

	/**
	 * nv_admin_add_theme()
	 * 
	 * @param mixed $contents
	 * @return
	 */
	function nv_admin_add_theme( $contents )
	{
		global $global_config, $module_file, $my_head;
		
		$xtpl = new XTemplate( "cronjobs_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		
		$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
		$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";
		$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.css\" rel=\"stylesheet\" />\n";

		$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
		$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.min.js\"></script>\n";
		$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";

		if( $contents['is_error'] ) $xtpl->parse( 'main.error' );
		
		$xtpl->assign( 'DATA', $contents );
		
		foreach( $contents['run_file'][2] as $run )
		{
			$xtpl->assign( 'RUN_FILE', array( 'key' => $run, 'selected' => $contents['run_file'][3] == $run ? " selected=\"selected\"" : "" ) );
			$xtpl->parse( 'main.run_file' );
		}

		for( $i = 0; $i < 24; ++$i )
		{
			$xtpl->assign( 'HOUR', array( 'key' => $i, 'selected' => $i == $contents['hour'][1] ? " selected=\"selected\"" : "" ) );
			$xtpl->parse( 'main.hour' );
		}
		
		for( $i = 0; $i < 60; ++ $i )
		{
			$xtpl->assign( 'MIN', array( 'key' => $i, 'selected' => $i == $contents['min'][1] ? " selected=\"selected\"" : "" ) );
			$xtpl->parse( 'main.min' );
		}
		
		$xtpl->assign( 'DELETE', ! empty( $contents['del'][1] ) ? " checked=\"checked\"" : "" );

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	/**
	 * main_theme()
	 * 
	 * @param mixed $contents
	 * @return
	 */
	function main_theme( $contents )
	{
		if( empty( $contents ) ) return "";
		
		global $global_config, $module_file;
		
		$xtpl = new XTemplate( "cronjobs_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		
		foreach( $contents as $id => $values )
		{
			
			$xtpl->assign( 'DATA', array(
				'caption' => $values['caption'],
				'edit' => empty( $values['edit'] ) ? array() : $values['edit'],
				'disable' => empty( $values['disable'] ) ? array() : $values['disable'],
				'delete' => empty( $values['delete'] ) ? array() : $values['delete'],
				'id' => $id
			) );

			if( ! empty( $values['edit'][0] ) ) $xtpl->parse( 'main.edit' );
			if( ! empty( $values['disable'][0] ) ) $xtpl->parse( 'main.disable' );
			if( ! empty( $values['delete'][0] ) ) $xtpl->parse( 'main.delete' );
			
			$a = 0;
			foreach( $values['detail'] as $key => $value )
			{
				$xtpl->assign( 'ROW', array(
					'class' => ( ++ $a % 2 ) ? " class=\"second\"" : "",
					'key' => $key,
					'value' => $value
				) );
				
				$xtpl->parse( 'main.loop' );
			}
			
			$xtpl->parse( 'main' );
		}

		return $xtpl->text( 'main' );
	}
}

?>