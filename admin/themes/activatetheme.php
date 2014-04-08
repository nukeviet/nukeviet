<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$theme = $nv_Request->get_title( 'theme', 'post', '' );

if( empty( $theme ) or ! preg_match( $global_config['check_theme'], $theme ) ) die();

$sth = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0 AND theme= :theme');
$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
$sth->execute();
if( $sth->fetchColumn() )
{
	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value= :theme WHERE config_name='site_theme' AND lang='" . NV_LANG_DATA . "'" );
	$sth->bindParam( ':theme', $theme, PDO::PARAM_STR );
	$sth->execute();

	$global_config['site_theme'] = $theme;
	nv_delete_all_cache();
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['block_active'] . ' theme: "' . $theme . '"', '', $admin_info['userid'] );

	echo 'OK_' . $theme;
}
else
{
	echo $lang_module['theme_created_activate_layout'];
}