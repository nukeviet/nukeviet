<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$theme = filter_text_input( 'theme', 'post', "", 1 );

if( ! empty( $theme ) and file_exists( NV_ROOTDIR . '/themes/' . trim( $theme ) ) && $global_config['site_theme'] != trim( $theme ) && trim( $theme ) != "default" )
{
	$check_exit_mod = false;
	$lang_module_array = array();

	$sql_theme = ( preg_match( $global_config['check_theme_mobile'], $theme ) ) ? 'mobile' : 'theme';

	$sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` where setup='1'";
	$result = $db->sql_query( $sql );

	while( list( $lang_i ) = $db->sql_fetchrow( $result ) )
	{
		$module_array = array();

		$sql = "SELECT title, custom_title FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `" . $sql_theme . "`=" . $db->dbescape_string( $theme ) . " ORDER BY `weight` ASC";
		$result = $db->sql_query( $sql );
		while( list( $title, $custom_title ) = $db->sql_fetchrow( $result ) )
		{
			$module_array[] = $custom_title;
		}

		if( ! empty( $module_array ) )
		{
			$lang_module_array[] = $lang_i . ": " . implode( ", ", $module_array );
		}
	}

	if( ! empty( $lang_module_array ) )
	{
		die( printf( $lang_module['theme_created_delete_module_theme'], implode( "; ", $lang_module_array ) ) );
	}
	else
	{
		nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_theme', "theme  " . $theme, $admin_info['userid'] );
		$result = nv_deletefile( NV_ROOTDIR . '/themes/' . trim( $theme ), true );
		
		if( ! empty( $result[0] ) )
		{
			$sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` where setup='1'";
			$result = $db->sql_query( $sql );
			
			while( list( $_lang ) = $db->sql_fetchrow( $result ) )
			{
				$db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_" . $_lang . "_modthemes` WHERE `theme` = " . $db->dbescape_string( $theme ) );

				$db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_" . $_lang . "_blocks_weight` WHERE `bid` IN (SELECT `bid` FROM `" . $db_config['prefix'] . "_" . $_lang . "_blocks_groups` WHERE `theme`=" . $db->dbescape_string( $theme ) . ")" );
				$db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_" . $_lang . "_blocks_groups` WHERE `theme` = " . $db->dbescape_string( $theme ) );

				$db->sql_query( "LOCK TABLE `" . $db_config['prefix'] . "_" . $_lang . "_modthemes` WRITE" );
				$db->sql_query( "REPAIR TABLE `" . $db_config['prefix'] . "_" . $_lang . "_modthemes`" );
				$db->sql_query( "OPTIMIZE TABLE `" . $db_config['prefix'] . "_" . $_lang . "_modthemes`" );
				$db->sql_query( "UNLOCK TABLE" );

				$db->sql_query( "LOCK TABLE `" . $db_config['prefix'] . "_" . $_lang . "_blocks_weight` WRITE" );
				$db->sql_query( "REPAIR TABLE `" . $db_config['prefix'] . "_" . $_lang . "_blocks_weight`" );
				$db->sql_query( "OPTIMIZE TABLE `" . $db_config['prefix'] . "_" . $_lang . "_blocks_weight`" );
				$db->sql_query( "UNLOCK TABLE" );

				$db->sql_query( "LOCK TABLE `" . $db_config['prefix'] . "_" . $_lang . "_blocks_groups` WRITE" );
				$db->sql_query( "REPAIR TABLE `" . $db_config['prefix'] . "_" . $_lang . "_blocks_groups`" );
				$db->sql_query( "OPTIMIZE TABLE `" . $db_config['prefix'] . "_" . $_lang . "_blocks_groups`" );
				$db->sql_query( "UNLOCK TABLE" );
			}

			nv_del_moduleCache( 'themes' );
			echo $lang_module['theme_created_delete_theme_success'];
		}
		else
		{
			echo $lang_module['theme_created_delete_theme_unsuccess'];
		}
	}
}
else
{
	echo $lang_module['theme_created_delete_current_theme'];
}

?>