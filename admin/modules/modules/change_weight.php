<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$mod = filter_text_input( 'mod', 'post', '' );

if( empty( $mod ) or ! preg_match( $global_config['check_module'], $mod ) ) die( "NO_" . $mod );

$sql = "SELECT `weight` FROM `" . NV_MODULES_TABLE . "` WHERE `title`=" . $db->dbescape( $mod );
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if( $numrows != 1 ) die( 'NO_' . $mod );

$new_weight = $nv_Request->get_int( 'new_weight', 'post', 0 );

if( empty( $new_weight ) ) die( 'NO_' . $mod );

$sql = "SELECT `title` FROM `" . NV_MODULES_TABLE . "` WHERE `title`!=" . $db->dbescape( $mod ) . " ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );

$weight = 0;
while( $row = $db->sql_fetchrow( $result ) )
{
	++$weight;
	if( $weight == $new_weight ) ++$weight;
	
	$sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `weight`=" . $weight . " WHERE `title`=" . $db->dbescape( $row['title'] );
	$db->sql_query( $sql );
}

$sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `weight`=" . $new_weight . " WHERE `title`=" . $db->dbescape( $mod );
$db->sql_query( $sql );

nv_del_moduleCache( 'modules' );

nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['weight'] . ' module "' . $mod . '"', $weight . " -> " . $new_weight, $admin_info['userid'] );

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $mod;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>