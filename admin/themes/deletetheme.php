<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$theme = $nv_Request->get_title( 'theme', 'post', '', 1 );

if( ! empty( $theme ) and file_exists( NV_ROOTDIR . '/themes/' . trim( $theme ) ) && $global_config['site_theme'] != trim( $theme ) && trim( $theme ) != 'default' )
{
	$check_exit_mod = false;
	$lang_module_array = array();

	$sql_theme = ( preg_match( $global_config['check_theme_mobile'], $theme ) ) ? 'mobile' : 'theme';

	$result = $db->query( 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language where setup = 1');
	while( list( $lang_i ) = $result->fetch( 3 ) )
	{
		$module_array = array();

		$sth = $db->prepare( 'SELECT title, custom_title
			FROM ' . $db_config['prefix'] . '_' . $lang_i . '_modules
			WHERE ' . $sql_theme . ' = :theme
			ORDER BY weight ASC' );
		$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
		$sth->execute();
		while( list( $title, $custom_title ) = $sth->fetch( 3 ) )
		{
			$module_array[] = $custom_title;
		}

		if( ! empty( $module_array ) )
		{
			$lang_module_array[] = $lang_i . ': ' . implode( ', ', $module_array );
		}
	}

	if( ! empty( $lang_module_array ) )
	{
		die( printf( $lang_module['theme_created_delete_module_theme'], implode( '; ', $lang_module_array ) ) );
	}
	else
	{
		nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_theme', 'theme ' . $theme, $admin_info['userid'] );
		$result = nv_deletefile( NV_ROOTDIR . '/themes/' . trim( $theme ), true );

		if( ! empty( $result[0] ) )
		{
			$result = $db->query( 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language where setup=1' );
			while( list( $_lang ) = $result->fetch( 3 ) )
			{
				$sth = $db->prepare( 'DELETE FROM ' . $db_config['prefix'] . '_' . $_lang . '_modthemes WHERE theme = :theme' );
				$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
				$sth->execute();

				$sth = $db->prepare( 'DELETE FROM ' . $db_config['prefix'] . '_' . $_lang . '_blocks_weight WHERE bid IN (SELECT bid FROM ' . $db_config['prefix'] . '_' . $_lang . '_blocks_groups WHERE theme= :theme)' );
				$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
				$sth->execute();

				$sth = $db->prepare( 'DELETE FROM ' . $db_config['prefix'] . '_' . $_lang . '_blocks_groups WHERE theme = :theme' );
				$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
				$sth->execute();
			}
			nv_del_moduleCache( 'themes' );

			$db->exec( 'LOCK TABLE ' . $db_config['prefix'] . '_' . $_lang . '_modthemes WRITE' );
			$db->exec( 'REPAIR TABLE ' . $db_config['prefix'] . '_' . $_lang . '_modthemes' );
			$db->exec( 'OPTIMIZE TABLE ' . $db_config['prefix'] . '_' . $_lang . '_modthemes' );
			$db->exec( 'UNLOCK TABLE' );

			$db->exec( 'LOCK TABLE ' . $db_config['prefix'] . '_' . $_lang . '_blocks_weight WRITE' );
			$db->exec( 'REPAIR TABLE ' . $db_config['prefix'] . '_' . $_lang . '_blocks_weight' );
			$db->exec( 'OPTIMIZE TABLE ' . $db_config['prefix'] . '_' . $_lang . '_blocks_weight' );
			$db->exec( 'UNLOCK TABLE' );

			$db->exec( 'LOCK TABLE ' . $db_config['prefix'] . '_' . $_lang . '_blocks_groups WRITE' );
			$db->exec( 'REPAIR TABLE ' . $db_config['prefix'] . '_' . $_lang . '_blocks_groups' );
			$db->exec( 'OPTIMIZE TABLE ' . $db_config['prefix'] . '_' . $_lang . '_blocks_groups' );
			$db->exec( 'UNLOCK TABLE' );

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