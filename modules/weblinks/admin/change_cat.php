<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$catid = $nv_Request->get_int( 'catid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$content = "NO_" . $catid;

list( $catid, $parentid ) = $db->sql_fetchrow( $db->sql_query( "SELECT `catid`, `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `catid`=" . intval( $catid ) . "" ) );
if( $catid > 0 )
{
	if( $mod == "weight" and $new_vid > 0 )
	{
		$sql = "SELECT `catid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `catid`!=" . $catid . " AND `parentid`=" . $parentid . " ORDER BY `weight` ASC";
		$result = $db->sql_query( $sql );
		
		$weight = 0;
		while( $row = $db->sql_fetchrow( $result ) )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `weight`=" . $weight . " WHERE `catid`=" . intval( $row['catid'] );
			$db->sql_query( $sql );
		}
		
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `weight`=" . $new_vid . " WHERE `catid`=" . intval( $catid );
		$db->sql_query( $sql );
		
		$content = "OK_" . $catid;

	}
	elseif( $mod == "inhome" and ( $new_vid == 0 or $new_vid == 1 ) )
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `inhome`=" . $new_vid . " WHERE `catid`=" . intval( $catid );
		$db->sql_query( $sql );
		$content = "OK_" . $catid;
	}
	elseif( $mod == "numlinks" and $new_vid >= 0 and $new_vid <= 10 )
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `numlinks`=" . $new_vid . " WHERE `catid`=" . intval( $catid );
		$db->sql_query( $sql );
		$content = "OK_" . $catid;
	}
	nv_del_moduleCache( $module_name );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $content;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>