<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-17-2010 0:5
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$modname = filter_text_input( 'mod', 'post' );
$contents = 'NO_' . $modname;

if( ! empty( $modname ) and preg_match( $global_config['check_module'], $modname ) )
{
	list( $is_sysmod, $module_file, $module_data ) = $db->sql_fetchrow( $db->sql_query( "SELECT is_sysmod, module_file, module_data FROM `" . $db_config['prefix'] . "_setup_modules` WHERE `title`=" . $db->dbescape( $modname ) ) );
	
	// Unfixdb
	$module_file = $db->unfixdb( $module_file );
	$module_data = $db->unfixdb( $module_data );
	
	if( intval( $is_sysmod ) != 1 )
	{
		$contents = 'OK_' . $modname;
	
		if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/action.php' ) )
		{
			$lang = NV_LANG_DATA;
			$sql_drop_module = array();
		
			require_once ( NV_ROOTDIR . '/modules/' . $module_file . '/action.php' );
		
			if( ! empty( $sql_drop_module ) )
			{
				foreach( $sql_drop_module as $sql )
				{
					if( ! $db->sql_query( $sql ) )
					{
						die( 'NO_' . $modname );
					}
				}
			}
		}

		// Xoa du lieu tai bang nv3_vi_blocks
		$sql = "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid` in (SELECT `bid` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `module`=" . $db->dbescape( $modname ) . ")";
	
		if( ! $db->sql_query( $sql ) )
		{
			die( 'NO_' . $modname );
		}

		$sql = "DELETE FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `module`=" . $db->dbescape( $modname );
		
		if( ! $db->sql_query( $sql ) )
		{
			die( 'NO_' . $modname );
		}

		nv_del_moduleCache( "themes" );

		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_modthemes` WHERE `func_id` IN (SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `in_module`=" . $db->dbescape( $modname ) . ")";
	
		if( ! $db->sql_query( $sql ) )
		{
			die( 'NO_' . $modname );
		}

		// Xoa du lieu tai bang  nv3_vi_modfuncs
		$sql = "DELETE FROM `" . NV_MODFUNCS_TABLE . "` WHERE `in_module`=" . $db->dbescape( $modname );
	
		if( ! $db->sql_query( $sql ) )
		{
			die( 'NO_' . $modname );
		}

		// Xoa du lieu tai bang  nv3_vi_modules
		$sql = "DELETE FROM `" . NV_MODULES_TABLE . "` WHERE `title`=" . $db->dbescape( $modname );
	
		if( ! $db->sql_query( $sql ) )
		{
			die( 'NO_' . $modname );
		}
		
		// Xoa du lieu tai bang nv3_config
		$sql = "DELETE FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang`=" . $db->dbescape( NV_LANG_DATA ) . " AND `module`=" . $db->dbescape( $modname );
		$db->sql_query( $sql );

		$check_exit_mod = false;

		$sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` where setup='1'";
		$result = $db->sql_query( $sql );
	
		while( list( $lang_i ) = $db->sql_fetchrow( $result ) )
		{
			list( $nb ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `title`=" . $db->dbescape( $modname ) . "" ) );
		
			if( intval( $nb ) > 0 )
			{
				$check_exit_mod = true;
				break;
			}
		}
		
		if( ! $check_exit_mod )
		{
			if( $module_file != $modname )
			{
				$sql = "DELETE FROM `" . $db_config['prefix'] . "_setup_modules` WHERE `title`=" . $db->dbescape( $modname );
				$db->sql_query( $sql );
			}
		
			nv_deletefile( NV_UPLOADS_REAL_DIR . '/' . $modname, true );
			nv_deletefile( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $modname, true );
			nv_loadUploadDirList( false );
		}
	
		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_global['delete'] . ' module "' . $modname . '"', '', $admin_info['userid'] );
		nv_delete_all_cache();
	}
}

nv_fix_module_weight();

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>