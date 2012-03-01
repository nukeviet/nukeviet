<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 19:49
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$mod = filter_text_input( 'mod', 'post' );

if( empty( $mod ) or ! preg_match( $global_config['check_module'], $mod ) ) die( "NO_" . $mod );

$query = "SELECT `act` FROM `" . NV_MODULES_TABLE . "` WHERE `title`=" . $db->dbescape( $mod );
$result = $db->sql_query( $query );
$numrows = $db->sql_numrows( $result );

if( $numrows != 1 )
{
	die( 'NO_' . $mod );
}

$row = $db->sql_fetchrow( $result );
$act = intval( $row['act'] );
$in_menu = intval( $row['in_menu'] );

if( $act == 2 )
{
	if( ! is_dir( NV_ROOTDIR . "/modules/" . $mod ) )
	{
		die( 'NO_' . $mod );
	}
	
	$in_menu = 0;
}

$act = ( $act != 1 ) ? 1 : 0;
if( $act == 0 and $mod == $global_config['site_home_module'] )
{
	die( 'NO_' . $mod );
}

$sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `in_menu`=" . $in_menu . ", `act`=" . $act . " WHERE `title`=" . $db->dbescape( $mod );
$db->sql_query( $sql );

nv_del_moduleCache( 'modules' );

$temp = ( $act == 1 ) ? $lang_global['yes'] : $lang_global['no'];
nv_insert_logs( NV_LANG_DATA, $module_name, $lang_global['activate'] . ' module "' . $mod . '"', $temp, $admin_info['userid'] );

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $mod;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>