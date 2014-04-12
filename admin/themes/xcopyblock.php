<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 08-19-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$page_title = $lang_module['xcopyblock'];

$selectthemes = $nv_Request->get_string( 'selectthemes', 'cookie', '' );
$op = $nv_Request->get_string( NV_OP_VARIABLE, 'get', '' );

$xtpl = new XTemplate( 'xcopyblock.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$theme_list = nv_scandir( NV_ROOTDIR . '/themes/', $global_config['check_theme'] );

$result = $db->query( 'SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0' );
while( list( $theme ) = $result->fetch( 3 ) )
{
	if( in_array( $theme, $theme_list ) )
	{
		$xtpl->assign( 'THEME_FROM', $theme );
		$xtpl->parse( 'main.theme_from' );

		$xtpl->assign( 'THEME_TO', array( 'key' => $theme, 'selected' => ( $selectthemes == $theme and $selectthemes != 'default' ) ? ' selected="selected"' : '' ) );
		$xtpl->parse( 'main.theme_to' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';