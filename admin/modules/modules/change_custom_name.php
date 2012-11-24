<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/7/2010 2:23
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

if( ! $nv_Request->isset_request( 'id', 'post,get' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );

$sql = "SELECT f.func_name AS func_title,f.func_custom_name AS func_custom_title,m.custom_title AS mod_custom_title FROM `" . NV_MODFUNCS_TABLE . "` AS f, `" . NV_MODULES_TABLE . "` AS m WHERE f.func_id=" . $id . " AND f.in_module=m.title";
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if( $numrows != 1 ) die( "NO_" . $id );

$row = $db->sql_fetchrow( $result );

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$func_custom_name = filter_text_input( 'func_custom_name', 'post', '', 1 );

	if( empty( $func_custom_name ) ) $func_custom_name = ucfirst( $row['func_name'] );

	$sql = "UPDATE `" . NV_MODFUNCS_TABLE . "` SET `func_custom_name`=" . $db->dbescape( $func_custom_name ) . " WHERE `func_id`=" . $id;
	$db->sql_query( $sql );

	nv_del_moduleCache( 'modules' );

	die( "OK|show_funcs|action" );
}
else
{
	$func_custom_name = $row['func_custom_title'];
}

$contents = array();
$contents['caption'] = sprintf( $lang_module['change_func_name'], $row['func_title'], $row['mod_custom_title'] );
$contents['func_custom_name'] = array(
	$lang_module['funcs_custom_title'],
	$func_custom_name,
	255,
	'func_custom_name'
);
$contents['submit'] = array( $lang_global['submit'], "nv_change_custom_name_submit( " . $id . ",'func_custom_name' );" );
$contents['cancel'] = array( $lang_global['cancel'], "nv_action_cancel('action');" );

$contents = call_user_func( "change_custom_name_theme", $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>