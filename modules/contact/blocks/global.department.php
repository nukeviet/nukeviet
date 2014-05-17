<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_department_info' ) )
{

	/**
	 * nv_department_info()
	 *
	 * @return
	 */
	function nv_department_info( $block_config )
	{
		global $global_config, $site_mods, $db, $module_name, $lang_module;
		
		$module = $block_config['module'];
		$module_data = $site_mods[$module]['module_data'];
		
		if( $module != $module_name)
		{
			$lang_temp = $lang_module;
			if ( file_exists( NV_ROOTDIR . "/modules/" . $module . "/language/" . $global_config['site_lang'] . ".php" ) )
			{
				require_once NV_ROOTDIR . "/modules/" . $module . "/language/" . $global_config['site_lang'] . ".php";
			}
			$lang_module = $lang_module + $lang_temp;
			unset( $lang_temp );	
		}

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module . '/block.department.tpl' ) )
		{
			$block_theme = $global_config['module_theme'];
		}
		elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module . '/block.department.tpl' ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = 'default';
		}
		
		//Danh sach cac bo phan
		$sql = 'SELECT id, full_name, phone, fax, email, note FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE act=1';
		$array_department = nv_db_cache( $sql, 'id' );

		$xtpl = new XTemplate( 'block.department.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module );
		$xtpl->assign( 'LANG', $lang_module );
		
		if( ! empty( $array_department ) )
		{
			foreach( $array_department as $value => $row )
			{
				if( ! empty( $row['phone'] ) OR ! empty( $row['fax'] ) OR ! empty( $row['email'] ) OR ! empty( $row['note'] ) )
				{	
					$xtpl->assign( 'DEPARTMENT', $row );
					
					if( ! empty( $row['phone'] ) )
					{
						$xtpl->parse( 'main.department.phone' );
					}
					
					if( ! empty( $row['fax'] ) )
					{
						$xtpl->parse( 'main.department.fax' );
					}
					
					if( ! empty( $row['email'] ) )
					{
						$xtpl->parse( 'main.department.email' );
					}
					
					if( ! empty( $row['note'] ) )
					{
						$xtpl->parse( 'main.department.note' );
					}
					
					$xtpl->parse( 'main.department' );
				}
			}
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

$content = nv_department_info( $block_config );