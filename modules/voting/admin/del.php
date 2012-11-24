<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$checkss = $nv_Request->get_string( 'checkss', 'post' );
$vid = $nv_Request->get_int( 'vid', 'post', 0 );
$contents = "";

if( $vid > 0 and $checkss == md5( $vid . session_id() ) )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_vote', "votingid " . $vid, $admin_info['userid'] );
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `vid`=" . $vid;
	if( $db->sql_query( $sql ) )
	{
		$db->sql_freeresult();
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `vid`=" . $vid;
		$db->sql_query( $sql );

		nv_del_moduleCache( $module_name );

		$contents = "OK_" . $vid;
	}
	else
	{
		$contents = "ERR_" . $lang_module['voting_delete_unsuccess'];
	}
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>