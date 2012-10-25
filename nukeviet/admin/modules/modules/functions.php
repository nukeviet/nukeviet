<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 5:53
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['setup'] = $lang_module['modules'];
$submenu['vmodule'] = $lang_module['vmodule_add'];

$allow_func = array( 'main', 'list', 'setup', 'vmodule', 'edit', 'del', 'change_inmenu', 'change_submenu', 'change_weight', 'change_act', 'empty_mod', 'recreate_mod', 'show', 'change_func_weight', 'change_custom_name', 'change_func_submenu', 'change_block_weight' );

if( defined( "NV_IS_GODADMIN" ) )
{
	$submenu['autoinstall'] = $lang_module['autoinstall'];
	
	$allow_func[] = "autoinstall";
	$allow_func[] = "install_module";
	$allow_func[] = "install_package";
	$allow_func[] = "install_check";
	$allow_func[] = "getfile";
}

if( $module_name == "modules" )
{
	$menu_top = array(
		"title" => $module_name,
		"module_file" => "",
		"custom_title" => $lang_global['mod_modules']
	);

	define( 'NV_IS_FILE_MODULES', true );

	/**
	 * nv_parse_vers()
	 * 
	 * @param mixed $ver
	 * @return
	 */
	function nv_parse_vers( $ver )
	{
		return $ver[1] . "-" . nv_date( "d/m/Y", $ver[2] );
	}

	/**
	 * nv_fix_module_weight()
	 * 
	 * @return
	 */
	function nv_fix_module_weight()
	{
		global $db;
		
		$sql = "SELECT `title` FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
		$result = $db->sql_query( $sql );
		$weight = 0;
		
		while( $row = $db->sql_fetchrow( $result ) )
		{
			++ $weight;
			$sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `weight`=" . $weight . " WHERE `title`=" . $db->dbescape( $row['title'] );
			$db->sql_query( $sql );
		}
		
		nv_del_moduleCache( 'modules' );
	}

	/**
	 * nv_fix_subweight()
	 * 
	 * @param mixed $mod
	 * @return
	 */
	function nv_fix_subweight( $mod )
	{
		global $db;

		$sql = "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `in_module`=" . $db->dbescape( $mod ) . " AND `show_func`='1' ORDER BY `subweight` ASC";
		$result = $db->sql_query( $sql );
		$subweight = 0;
	
		while( $row = $db->sql_fetchrow( $result ) )
		{
			++$subweight;
			$sql = "UPDATE `" . NV_MODFUNCS_TABLE . "` SET `subweight`=" . $subweight . " WHERE `func_id`=" . $row['func_id'];
			$db->sql_query( $sql );
			nv_del_moduleCache( 'modules' );
		}
	}

	/**
	 * nv_setup_block_module()
	 * 
	 * @param mixed $mod
	 * @param integer $func_id
	 * @return
	 */
	function nv_setup_block_module( $mod, $func_id = 0 )
	{
		global $db;
	
		if( empty( $func_id ) )
		{
			//xoa du lieu tai bang blocks
			$db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid` in (SELECT `bid` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `module`=" . $db->dbescape( $mod ) . ")" );
			$db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `module`=" . $db->dbescape( $mod ) );
			$db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `func_id` in (SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `in_module`=" . $db->dbescape( $mod ) . ")" );
		}

		$array_funcid = array();
		$func_result = $db->sql_query( "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `show_func` = '1' AND `in_module`=" . $db->dbescape( $mod ) . " ORDER BY `subweight` ASC" );
	
		while( list( $func_id_i ) = $db->sql_fetchrow( $func_result ) )
		{
			if( $func_id == 0 or $func_id == $func_id_i )
			{
				$array_funcid[] = $func_id_i;
			}
		}

		$weight = 0;
		$old_theme = $old_position = "";
	
		$sql = "SELECT `bid`, `theme`, `position` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `all_func`='1' ORDER BY `theme` ASC, `position` ASC, `weight` ASC";
		$result = $db->sql_query( $sql );
	
		while( $row = $db->sql_fetchrow( $result ) )
		{
			if( $old_theme == $row['theme'] and $old_position == $row['position'] )
			{
				++$weight;
			}
			else
			{
				$weight = 1;
				$old_theme = $row['theme'];
				$old_position = $row['position'];
			}
			
			foreach( $array_funcid as $func_id )
			{
				$db->sql_query( "INSERT INTO `" . NV_BLOCKS_TABLE . "_weight` (`bid`, `func_id`, `weight`) VALUES ('" . $row['bid'] . "', '" . $func_id . "', '" . $weight . "')" );
			}
		}

		nv_del_moduleCache( "themes" );
	}

	/**
	 * nv_setup_data_module()
	 * 
	 * @param mixed $lang
	 * @param mixed $module_name
	 * @return
	 */
	function nv_setup_data_module( $lang, $module_name )
	{
		global $db, $db_config, $global_config;
	
		$return = 'NO_' . $module_name;
	
		$sql = "SELECT `module_file`, `module_data`, `theme` FROM `" . $db_config['prefix'] . "_" . $lang . "_modules` WHERE `title`=" . $db->dbescape( $module_name );
		$result = $db->sql_query( $sql );
		$numrows = $db->sql_numrows( $result );
	
		if( $numrows == 1 )
		{
			list( $module_file, $module_data, $module_theme ) = $db->sql_fetchrow( $result );
			
			// Unfixdb
			$module_file = $db->unfixdb( $module_file );
			$module_data = $db->unfixdb( $module_data );
			$module_theme = $db->unfixdb( $module_theme );
		
			$module_version = array();
			$version_file = NV_ROOTDIR . "/modules/" . $module_file . "/version.php";
		
			if( file_exists( $version_file ) )
			{
				include ( $version_file );
			}
		
			$arr_modfuncs = ( isset( $module_version['modfuncs'] ) and ! empty( $module_version['modfuncs'] ) ) ? array_map( "trim", explode( ",", $module_version['modfuncs'] ) ) : array();
			// Xoa du lieu tai bang _config
		
			$sql = "DELETE FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang`=" . $db->dbescape( $lang ) . " AND `module`=" . $db->dbescape( $module_name );
			$db->sql_query( $sql );
		
			nv_delete_all_cache();
		
			if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/action.php' ) )
			{
				$sql_recreate_module = array();
			
				include ( NV_ROOTDIR . '/modules/' . $module_file . '/action.php' );
			
				if( ! empty( $sql_create_module ) )
				{
					foreach( $sql_create_module as $sql )
					{
						if( ! $db->sql_query( $sql ) )
						{
							return $return;
						}
					}
				}
			}

			$arr_func_id = array();
			$arr_show_func = array();
			$new_funcs = nv_scandir( NV_ROOTDIR . '/modules/' . $module_file . '/funcs', $global_config['check_op_file'] );
		
			if( ! empty( $new_funcs ) )
			{
				// Get default layout
				$layout_array = nv_scandir( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/layout', $global_config['check_op_layout'] );
				if( ! empty( $layout_array ) )
				{
					$layout_array = preg_replace( $global_config['check_op_layout'], "\\1", $layout_array );
				}

				$selectthemes = "default";
				if( ! empty( $module_theme ) and file_exists( NV_ROOTDIR . '/themes/' . $module_theme . '/config.ini' ) )
				{
					$selectthemes = $module_theme;
				}
				elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini' ) )
				{
					$selectthemes = $global_config['site_theme'];
				}

				$xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini' );
				$layoutdefault = ( string )$xml->layoutdefault;
				$layout = $xml->xpath( 'setlayout/layout' );

				$array_layout_func_default = array();
				for( $i = 0, $count = sizeof( $layout ); $i < $count; ++$i )
				{
					$layout_name = ( string )$layout[$i]->name;
					
					if( in_array( $layout_name, $layout_array ) )
					{
						$layout_funcs = $layout[$i]->xpath( 'funcs' );
						for( $j = 0, $count2 = sizeof( $layout_funcs ); $j < $count2; ++$j )
						{
							$mo_funcs = ( string )$layout_funcs[$j];
							$mo_funcs = explode( ":", $mo_funcs );
							$m = $mo_funcs[0];
							$arr_f = explode( ",", $mo_funcs[1] );
							foreach( $arr_f as $f )
							{
								$array_layout_func_default[$m][$f] = $layout_name;
							}
						}
					}
				}
				// end get default layout

				$arr_func_id_old = array();
				$sql = "SELECT `func_id`, `func_name` FROM `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` WHERE `in_module`=" . $db->dbescape( $module_name );
				$result = $db->sql_query( $sql );
				while( $row = $db->sql_fetchrow( $result ) )
				{
					$arr_func_id_old[ $db->unfixdb( $row['func_name'] ) ] = $row['func_id'];
				}

				$new_funcs = preg_replace( $global_config['check_op_file'], "\\1", $new_funcs );
				$new_funcs = array_flip( $new_funcs );
				$array_keys = array_keys( $new_funcs );
				
				foreach( $array_keys as $func )
				{
					$show_func = 0;
					$weight = 0;
					$layout = ( isset( $array_layout_func_default[$module_name][$func] ) ) ? $array_layout_func_default[$module_name][$func] : $layoutdefault;
					if( isset( $arr_func_id_old[$func] ) and isset( $arr_func_id_old[$func] ) > 0 )
					{
						$arr_func_id[$func] = $arr_func_id_old[$func];
						$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` SET `show_func`= " . $show_func . ", `subweight`='0' WHERE `func_id`=" . $arr_func_id[$func] . "" );
					}
					else
					{
						$sql = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` (`func_id`, `func_name`, `func_custom_name`, `in_module`, `show_func`, `in_submenu`, `subweight`, `setting`) VALUES (NULL, " . $db->dbescape( $func ) . ", " . $db->dbescape( ucfirst( $func ) ) . ", " . $db->dbescape( $module_name ) . ", " . $show_func . ", 0, " . $weight . ", '')";
						$arr_func_id[$func] = $db->sql_query_insert_id( $sql );
					}
				}
			
				$subweight = 0;
				foreach( $arr_modfuncs as $func )
				{
					if( isset( $arr_func_id[$func] ) )
					{
						$func_id = $arr_func_id[$func];
						$arr_show_func[] = $func_id;
						$show_func = 1;
						++$subweight;
						$sql = "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` SET `subweight`=" . $subweight . ", show_func=" . $show_func . " WHERE `func_id`=" . $db->dbescape( $func_id );
						$db->sql_query( $sql );
					}
				}
			}
			else
			{
				// Xoa du lieu tai bang _modfuncs
				$sql = "DELETE FROM `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` WHERE `in_module`=" . $db->dbescape( $module_name );
				$db->sql_query( $sql );
			}
		
			if( isset( $module_version['uploads_dir'] ) and ! empty( $module_version['uploads_dir'] ) )
			{
				foreach( $module_version['uploads_dir'] as $path )
				{
					$cp = '';
					$arr_p = explode( "/", $path );
				
					foreach( $arr_p as $p )
					{
						if( trim( $p ) != "" )
						{
							if( ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $cp . $p ) )
							{
								nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $cp, $p );
								nv_loadUploadDirList( false );
							}
						
							$cp .= $p . '/';
						}
					}
				}
			}

			if( isset( $module_version['files_dir'] ) and ! empty( $module_version['files_dir'] ) )
			{
				foreach( $module_version['files_dir'] as $path )
				{
					$cp = '';
					$arr_p = explode( "/", $path );
				
					foreach( $arr_p as $p )
					{
						if( trim( $p ) != "" )
						{
							if( ! is_dir( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $cp . $p ) )
							{
								nv_mkdir( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $cp, $p );
							}
							$cp .= $p . '/';
						}
					}
				}
			}
		
			$return = 'OK_' . $module_name;
		
			nv_delete_all_cache();
		}
		return $return;
	}

	/**
	 * main_theme()
	 * 
	 * @param mixed $contents
	 * @return
	 */
	function main_theme( $contents )
	{
		global $global_config, $module_file;
		
		$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'CONTENT', $contents );
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	/**
	 * list_theme()
	 * 
	 * @param mixed $contents
	 * @param mixed $act_modules
	 * @param mixed $deact_modules
	 * @param mixed $bad_modules
	 * @param mixed $weight_list
	 * @return
	 */
	function list_theme( $contents, $act_modules, $deact_modules, $bad_modules, $weight_list )
	{
		global $global_config, $module_file;
		
		$xtpl = new XTemplate( "list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'CAPTION', $contents['caption'] );
	
		if( ! empty( $act_modules ) )
		{
			foreach( $contents['thead'] as $thead )
			{
				$xtpl->assign( 'THEAD', $thead );
				$xtpl->parse( 'main.act_modules.thead' );
			}
				
			$a = 0;
			foreach( $act_modules as $mod => $values )
			{
				$xtpl->assign( 'ROW', array(
					'class' => ( ++ $a % 2 ) ? " class=\"second\"" : "",
					'mod' => $mod,
					'values' => $values,
					'inmenu_checked' => $values['in_menu'][0] ? " checked=\"checked\"" : "",
					'submenu_checked' => $values['submenu'][0] ? " checked=\"checked\"" : "",
					'act_disabled' => ( isset( $values['act'][2] ) and $values['act'][2] == 1 ) ? " disabled=\"disabled\"" : ""
				) );
				
				foreach( $weight_list as $new_weight )
				{
					$xtpl->assign( 'WEIGHT', array( 'key' => $new_weight, 'selected' => $new_weight == $values['weight'][0] ? " selected=\"selected\"" : "" ) );
					$xtpl->parse( 'main.act_modules.loop.weight' );
				}
				
				if( ! empty( $values['del'] ) ) $xtpl->parse( 'main.act_modules.loop.delete' );
				
				$xtpl->parse( 'main.act_modules.loop' );
			}
			
			$xtpl->parse( 'main.act_modules' );
		}

		if( ! empty( $deact_modules ) )
		{		
			foreach( $contents['thead'] as $thead )
			{
				$xtpl->assign( 'THEAD', $thead );
				$xtpl->parse( 'main.deact_modules.thead' );
			}
		
			$a = 0;		
			foreach( $deact_modules as $mod => $values )
			{			
				$xtpl->assign( 'ROW', array(
					'class' => ( ++ $a % 2 ) ? " class=\"second\"" : "",
					'mod' => $mod,
					'values' => $values,
					'inmenu_checked' => $values['in_menu'][0] ? " checked=\"checked\"" : "",
					'submenu_checked' => $values['submenu'][0] ? " checked=\"checked\"" : "",
					'act_disabled' => ( isset( $values['act'][2] ) and $values['act'][2] == 1 ) ? " disabled=\"disabled\"" : ""
				) );
				
				foreach( $weight_list as $new_weight )
				{
					$xtpl->assign( 'WEIGHT', array( 'key' => $new_weight, 'selected' => $new_weight == $values['weight'][0] ? " selected=\"selected\"" : "" ) );
					$xtpl->parse( 'main.deact_modules.loop.weight' );
				}
			
				if( ! empty( $values['del'] ) ) $xtpl->parse( 'main.deact_modules.loop.delete' );
				
				$xtpl->parse( 'main.deact_modules.loop' );
			}
			
			$xtpl->parse( 'main.deact_modules' );
		}

		if( ! empty( $bad_modules ) )
		{		
			foreach( $contents['thead'] as $thead )
			{
				$xtpl->assign( 'THEAD', $thead );
				$xtpl->parse( 'main.bad_modules.thead' );
			}
			
			$a = 0;
			foreach( $bad_modules as $mod => $values )
			{		
				$xtpl->assign( 'ROW', array(
					'class' => ( ++ $a % 2 ) ? " class=\"second\"" : "",
					'mod' => $mod,
					'values' => $values,
					'inmenu_checked' => $values['in_menu'][0] ? " checked=\"checked\"" : "",
					'submenu_checked' => $values['submenu'][0] ? " checked=\"checked\"" : "",
					'act_disabled' => ( isset( $values['act'][2] ) and $values['act'][2] == 1 ) ? " disabled=\"disabled\"" : ""
				) );
				
				foreach( $weight_list as $new_weight )
				{
					$xtpl->assign( 'WEIGHT', array( 'key' => $new_weight, 'selected' => $new_weight == $values['weight'][0] ? " selected=\"selected\"" : "" ) );
					$xtpl->parse( 'main.bad_modules.loop.weight' );
				}
				
				if( ! empty( $values['del'] ) ) $xtpl->parse( 'main.bad_modules.loop.delete' );
				
				$xtpl->parse( 'main.bad_modules.loop' );
			}
			
			$xtpl->parse( 'main.bad_modules' );
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	/**
	 * edit_theme()
	 * 
	 * @param mixed $contents
	 * @return
	 */
	function edit_theme( $contents )
	{
		global $global_config, $module_file;
		
		$xtpl = new XTemplate( "edit.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'CONTENT', $contents );
	
		if( ! empty( $contents['error'] ) )
		{
			$xtpl->parse( 'main.error' );
		}
	
		foreach( $contents['theme'][2] as $tm )
		{
			$xtpl->assign( 'THEME', array( 'key' => $tm, 'selected' => $tm == $contents['theme'][3] ? " selected=\"selected\"" : "" ) );
			$xtpl->parse( 'main.theme' );
		}
	
		if( ! empty( $contents['mobile'][2] ) )
		{		
			foreach( $contents['mobile'][2] as $tm )
			{
				$xtpl->assign( 'MOBILE', array( 'key' => $tm, 'selected' => $tm == $contents['mobile'][3] ? " selected=\"selected\"" : "" ) );
				$xtpl->parse( 'main.mobile.loop' );
			}
			
			$xtpl->parse( 'main.mobile' );
		}

		if( isset( $contents['who_view'] ) )
		{
			foreach( $contents['who_view'][1] as $k => $w )
			{
				$xtpl->assign( 'WHO_VIEW', array( 'key' => $k, 'selected' => $k == $contents['who_view'][2] ? " selected=\"selected\"" : "", 'title' => $w ) );
				$xtpl->parse( 'main.who_view.loop' );
			}
		
			$xtpl->assign( 'DISPLAY', $contents['who_view'][2] == 3 ? "visibility:visible;display:block;" : "visibility:hidden;display:none;" );
				
			foreach( $contents['groups_view'][1] as $group_id => $grtl )
			{
				$xtpl->assign( 'GROUPS_VIEW', array( 
					'key' => $group_id,
					'checked' => in_array( $group_id, $contents['groups_view'][2] ) ? " checked=\"checked\"" : "",
					'title' => $grtl
				) );
				
				$xtpl->parse( 'main.who_view.groups_view' );
			}
				
			$xtpl->parse( 'main.who_view' );
		}
	
		$xtpl->assign( 'ACTIVE', ( $contents['act'][1] == 1 ) ? ' checked="checked"' : '' );
	
		if( isset( $contents['rss'] ) )
		{
			$xtpl->assign( 'RSS', ( $contents['rss'][1] == 1 ) ? ' checked="checked"' : '' );
			$xtpl->parse( 'main.rss' );
		}
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	/**
	 * show_funcs_theme()
	 * 
	 * @param mixed $contents
	 * @return
	 */
	function show_funcs_theme( $contents )
	{
		global $global_config, $module_file;
		
		$xtpl = new XTemplate( "show_funcs_theme.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'CONTENT', $contents );
		
		if( ! empty( $contents['ajax'][0] ) )
		{
			$xtpl->parse( 'main.ajax0' );
			$xtpl->parse( 'main.loading0' );
		}
		
		if( ! empty( $contents['ajax'][1] ) )
		{
			$xtpl->parse( 'main.ajax1' );
			$xtpl->parse( 'main.loading1' );
		}
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	/**
	 * aj_show_funcs_theme()
	 * 
	 * @param mixed $contents
	 * @return
	 */
	function aj_show_funcs_theme( $contents )
	{
		global $global_config, $module_file;
		
		$xtpl = new XTemplate( "aj_show_funcs_theme.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'CONTENT', $contents );
	
		foreach( $contents['thead'] as $key => $thead )
		{
			$xtpl->assign( 'THEAD', $thead );
			$xtpl->parse( 'main.thead' );
		}
			
		if( isset( $contents['rows'] ) )
		{
			$a = 0;
			foreach( $contents['rows'] as $id => $values )
			{
				$xtpl->assign( 'ROW', array(
					'class' => ( ++ $a % 2 ) ? " class=\"second\"" : "",
					'id' => $id,
					'js_onchange' => $values['weight'][1],
					'in_submenu_click' => $values['in_submenu'][1],
					'in_submenu_checked' => $values['in_submenu'][0] ? " checked=\"checked\"" : "",
					'disabled' => $values['disabled'],
					'name' => $values['name'],
					'layout' => $values['layout']
				) );
				
				foreach( $contents['weight_list'] as $new_weight )
				{
					$xtpl->assign( 'WEIGHT', array( 'key' => $new_weight, 'selected' => $new_weight == $values['weight'][0] ? " selected=\"selected\"" : "" ) );
					$xtpl->parse( 'main.loop.weight' );
				}
				
				$xtpl->parse( 'main.loop' );
			}
		}
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	/**
	 * change_custom_name_theme()
	 * 
	 * @param mixed $contents
	 * @return
	 */
	function change_custom_name_theme( $contents )
	{
		global $global_config, $module_file;
		
		$xtpl = new XTemplate( "change_custom_name_theme.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'CONTENT', $contents );

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	/**
	 * setup_modules()
	 * 
	 * @param mixed $array_head
	 * @param mixed $array_modules
	 * @param mixed $array_virtual_head
	 * @param mixed $array_virtual_modules
	 * @return
	 */
	function setup_modules( $array_head, $array_modules, $array_virtual_head, $array_virtual_modules )
	{
		global $global_config, $module_file;
		
		$xtpl = new XTemplate( "setup_modules.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	
		$xtpl->assign( 'CAPTION', $array_head['caption'] );
	
		foreach( $array_head['head'] as $thead )
		{
			$xtpl->assign( 'HEAD', $thead );
			$xtpl->parse( 'main.head' );
		}
		
		$a = 0;
		foreach( $array_modules as $mod => $values )
		{			
			$xtpl->assign( 'ROW', array(
				'class' => ( ++ $a % 2 ) ? " class=\"second\"" : "",
				'stt' => $a,
				'values' => $values
			) );
			
			$xtpl->parse( 'main.loop' );
		}
	
		if( ! empty( $array_virtual_modules ) )
		{
			$xtpl->assign( 'VCAPTION', $array_virtual_head['caption'] );
					
			foreach( $array_virtual_head['head'] as $thead )
			{
				$xtpl->assign( 'VHEAD', $thead );
				$xtpl->parse( 'main.vmodule.vhead' );
			}
		
			$a = 0;
			foreach( $array_virtual_modules as $mod => $values )
			{
				$xtpl->assign( 'VROW', array(
					'class' => ( ++ $a % 2 ) ? " class=\"second\"" : "",
					'stt' => $a,
					'values' => $values
				) );
				
				$xtpl->parse( 'main.vmodule.loop' );
			}
			
			$xtpl->parse( 'main.vmodule' );
		}
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

?>