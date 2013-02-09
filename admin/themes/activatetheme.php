<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$theme = filter_text_input( 'theme', 'post', "", 1 );

$sql = "SELECT `theme` FROM `" . NV_PREFIXLANG . "_modthemes`  WHERE `func_id`=0 AND `theme`=" . $db->dbescape_string( $theme ) . "";
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) > 0 )
{
	$db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET  config_value=" . $db->dbescape_string( $theme ) . " WHERE config_name='site_theme' AND lang='" . NV_LANG_DATA . "'" );
	$global_config['site_theme'] = $theme;

	nv_delete_all_cache();
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['block_active'] . ' theme: "' . $theme . '"', '', $admin_info['userid'] );

	echo "OK_" . $theme;
}
else
{
	echo $lang_module['theme_created_activate_layout'];
}

?>