<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$module = $nv_Request->get_string( 'module', 'get', '' );
$bid = $nv_Request->get_int( 'bid', 'get,post', 0 );

$row = array( 'theme' => '', 'file_name' => '' );
if( $bid > 0 )
{
	$row = $db->query( 'SELECT theme, file_name FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid )->fetch();
}

echo "<option value=\"\">" . $lang_module['block_select'] . "</option>\n";

if( $module == 'theme' )
{
	if( empty( $row['theme'] ) )
	{
		$row['theme'] = $nv_Request->get_string( 'selectthemes', 'post,get', $global_config['site_theme'] );
	}

	$block_file_list = nv_scandir( NV_ROOTDIR . '/themes/' . $row['theme'] . '/blocks', $global_config['check_block_theme'] );
	foreach( $block_file_list as $file_name )
	{
		if( preg_match( $global_config['check_block_theme'], $file_name, $matches ) )
		{
			$sel = ( $file_name == $row['file_name'] ) ? ' selected="selected"' : '';
			$load_config = ( file_exists( NV_ROOTDIR . '/themes/' . $row['theme'] . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) ) ? 1 : 0;
			$load_mod_array = array();
			if( $matches[1] != 'global' )
			{
				foreach ($site_mods as $mod => $row_i)
				{
					if( $row_i['module_file'] == $matches[1] )
					{
						$load_mod_array[] = $mod;
					}
				}
			}
			echo "<option value=\"" . $file_name . "|" . $load_config . "|" . implode('.', $load_mod_array) . "\" " . $sel . ">" . $matches[1] . " " . $matches[2] . " </option>\n";
		}
	}
}
elseif( isset( $site_mods[$module]['module_file'] ) )
{
	$module_file = $site_mods[$module]['module_file'];
	if( !empty( $module_file ) )
	{
		if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/blocks' ) )
		{
			$block_file_list = nv_scandir( NV_ROOTDIR . '/modules/' . $module_file . '/blocks', $global_config['check_block_module'] );

			foreach( $block_file_list as $file_name )
			{
				$sel = ( $file_name == $row['file_name'] ) ? ' selected="selected"' : '';

				unset( $matches );
				preg_match( $global_config['check_block_module'], $file_name, $matches );

				$load_config = ( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) ) ? 1 : 0;

				echo "<option value=\"" . $file_name . "|" . $load_config . "|\" " . $sel . ">" . $matches[1] . " " . $matches[2] . " </option>\n";
			}
		}
	}
}