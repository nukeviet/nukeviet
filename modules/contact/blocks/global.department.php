<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_department_info' ) )
{

	/**
	 * nv_department_info()
	 *
	 * @return
	 */
	function nv_block_config_contact_department( $module, $data_block, $lang_block )
	{
		global $site_mods;

		$html_input = '';
		$html = '';
		$html .= '<tr>';
		$html .= '<td>' . $lang_block['departmentid'] . '</td>';
		$html .= '<td><select name="config_departmentid" class="form-control w200">';
		$sql = 'SELECT id, full_name FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department WHERE act=1';
		$list = nv_db_cache( $sql, 'id', $module );
		foreach( $list as $l )
		{
			$html .= '<option value="' . $l['id'] . '" ' . ( ( $data_block['departmentid'] == $l['id'] ) ? ' selected="selected"' : '' ) . '>' . $l['full_name'] . '</option>';
		}
		$html .= '</select>';
		$html .= '</tr>';

		return $html;
	}

	function nv_block_config_contact_department_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['departmentid'] = $nv_Request->get_int( 'config_departmentid', 'post', 0 );
		return $return;
	}
	function nv_department_info( $block_config )
	{
		global $global_config, $site_mods, $db, $module_name, $lang_module;

		$module = $block_config['module'];
		$module_data = $site_mods[$module]['module_data'];

		if( $module != $module_name )
		{
			$lang_temp = $lang_module;
			if( file_exists( NV_ROOTDIR . '/modules/' . $module . '/language/' . $global_config['site_lang'] . '.php' ) )
			{
				require NV_ROOTDIR . '/modules/' . $module . '/language/' . $global_config['site_lang'] . '.php';
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
		$sql = 'SELECT id, full_name, phone, fax, email, yahoo, skype, note FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE act=1 AND id=' . $block_config['departmentid'];
		$array_department = nv_db_cache( $sql, 'id' );

		$xtpl = new XTemplate( 'block.department.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module );
		$xtpl->assign( 'LANG', $lang_module );

		if( ! empty( $array_department ) )
		{
			foreach( $array_department as $value => $row )
			{
				if( ! empty( $row['phone'] ) or ! empty( $row['fax'] ) or ! empty( $row['email'] ) or ! empty( $row['yahoo'] ) or ! empty( $row['skype'] ) )
				{
					$xtpl->assign( 'DEPARTMENT', $row );
					
					if( ! empty( $row['phone'] ) )
					{
						$xtpl->parse( 'main.phone' );
					}
					
					if( ! empty( $row['fax'] ) )
					{
						$xtpl->parse( 'main.fax' );
					}

					if( ! empty( $row['email'] ) )
					{
						$xtpl->parse( 'main.email' );
					}
					
					if( ! empty( $row['yahoo'] ) )
					{
						$xtpl->parse( 'main.yahoo' );
					}

					if( ! empty( $row['skype'] ) )
					{
						$xtpl->parse( 'main.skype' );
					}
				}
				else
				{
					return '';
				}
			}
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	global $site_mods, $module_name, $global_array_cat, $module_array_cat;
	$module = $block_config['module'];
	if( isset( $site_mods[$module] ) )
	{
		$content = nv_department_info( $block_config );
	}
}
