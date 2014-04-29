<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$select_options = array();
$theme_array = nv_scandir( NV_ROOTDIR . '/themes', array( $global_config['check_theme'], $global_config['check_theme_mobile'] ) );
if( $global_config['idsite'] )
{
	$theme = $db->query( 'SELECT theme FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'] )->fetchColumn();
	if( ! empty( $theme ) )
	{
		$array_site_cat_theme = explode( ',', $theme );
		$result = $db->query( 'SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0' );
		while( list( $theme ) = $result->fetch( 3 ) )
		{
			$array_site_cat_theme[] = $theme;
		}
		$theme_array = array_intersect( $theme_array, $array_site_cat_theme );
	}
}

foreach( $theme_array as $themes_i )
{
	if( file_exists( NV_ROOTDIR . '/themes/' . $themes_i . '/config.ini' ) )
	{
		$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;selectthemes=' . $themes_i] = $themes_i;
	}
}

$selectthemes_old = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );
$selectthemes = $nv_Request->get_string( 'selectthemes', 'get', $selectthemes_old );

if( ! in_array( $selectthemes, $theme_array ) )
{
	$selectthemes = $global_config['site_theme'];
}
if( $selectthemes_old != $selectthemes )
{
	$nv_Request->set_Cookie( 'selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME );
}

if( file_exists( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.php' ) )
{
	// Connect with file language interface configuration
	if( file_exists( NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_' . NV_LANG_INTERFACE . '.php' ) )
	{
		require NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_' . NV_LANG_INTERFACE . '.php';
	}
	elseif( file_exists( NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_' . NV_LANG_DATA . '.php' ) )
	{
		require NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_' . NV_LANG_DATA . '.php';
	}
	elseif( file_exists( NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_en.php' ) )
	{
		require NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_en.php';
	}

	// Connect with file theme configuration
	require NV_ROOTDIR . '/themes/' . $selectthemes . '/config.php';
}
else
{
	$contents = '<h2 class="center vcenter" style="margin: 50px;">' . sprintf( $lang_module['config_not_exit'], $selectthemes ) . '</h2>';
}

$page_title = $lang_module['config'] . ':' . $selectthemes;

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';