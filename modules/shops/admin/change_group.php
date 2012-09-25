<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$groupid = $nv_Request->get_int( 'groupid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$content = "NO_" . $groupid;

list( $groupid, $parentid, $numsubgroup ) = $db->sql_fetchrow( $db->sql_query( "SELECT `groupid`, `parentid`, `numsubgroup` FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE `groupid`=" . intval( $groupid ) ) );

if( $groupid > 0 )
{
	if( $mod == "weight" and $new_vid > 0 )
	{
		$sql = "SELECT `groupid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE `groupid`!=" . $groupid . " AND `parentid`=" . $parentid . " ORDER BY `weight` ASC";
		$result = $db->sql_query( $sql );
		
		$weight = 0;
		while( $row = $db->sql_fetchrow( $result ) )
		{
			$weight++;
			if( $weight == $new_vid ) $weight++;
			$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_group` SET `weight`=" . $weight . " WHERE `groupid`=" . intval( $row['groupid'] );
			$db->sql_query( $sql );
		}
		
		$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_group` SET `weight`=" . $new_vid . " WHERE `groupid`=" . intval( $groupid );
		$db->sql_query( $sql );
		
		nv_fix_group_order();
		$content = "OK_" . $parentid;
	}
	elseif( $mod == "inhome" and ( $new_vid == 0 or $new_vid == 1 ) )
	{
		$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_group` SET `inhome`=" . $new_vid . " WHERE `groupid`=" . intval( $groupid );
		$db->sql_query( $sql );
		$content = "OK_" . $parentid;
	}
	elseif( $mod == "numlinks" and $new_vid >= 0 and $new_vid <= 10 )
	{
		$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_group` SET `numlinks`=" . $new_vid . " WHERE `groupid`=" . intval( $groupid );
		$db->sql_query( $sql );
		$content = "OK_" . $parentid;
	}
	elseif( $mod == "viewgroup" and $nv_Request->isset_request( 'new_vid', 'post' ) )
	{
		$viewgroup = filter_text_input( 'new_vid', 'post' );
		$array_viewgroup = ( $numsubgroup > 0 ) ? $array_viewcat_full : $array_viewcat_nosub;
		if( ! array_key_exists( $viewgroup, $array_viewgroup ) )
		{
			$viewgroup = "viewcat_page_list";
		}
		$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_group` SET `viewgroup`=" . $db->dbescape( $viewgroup ) . " WHERE `groupid`=" . intval( $groupid );
		$db->sql_query( $sql );
		$content = "OK_" . $parentid;
	}
	nv_del_moduleCache( $module_name );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $content;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>