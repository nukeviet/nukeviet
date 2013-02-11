<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/7/2010 2:23
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$func_id = $nv_Request->get_int( 'funcid', 'post' );
$layout = filter_text_input( 'layout', 'post', '', 1 );
$selectthemes_old = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );
$selectthemes = $nv_Request->get_string( 'selectthemes', 'get', $selectthemes_old );
$numfunc = $db->sql_numrows( $db->sql_query( "SELECT func_id FROM `" . NV_PREFIXLANG . "_modthemes` WHERE `func_id`='" . $func_id . "' AND theme='" . $selectthemes . "'" ) );

if( $numfunc )
{
	$sql = "UPDATE `" . NV_PREFIXLANG . "_modthemes` SET `layout`=" . $db->dbescape( $layout ) . " WHERE `func_id`=" . $func_id;
}
else
{
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_modthemes` VALUES('$func_id'," . $db->dbescape( $layout ) . ", " . $db->dbescape( $selectthemes ) . ")";
}

$result = $db->sql_query( $sql );

if( $result )
{
	echo $lang_module['setup_updated_layout'];
}
else
{
	echo $lang_module['setup_error_layout'];
}

nv_del_moduleCache( 'themes' );

?>