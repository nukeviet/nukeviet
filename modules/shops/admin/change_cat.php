<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$catid = $nv_Request->get_int( 'catid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$content = "NO_" . $catid;

list( $catid, $parentid, $numsubcat ) = $db->sql_fetchrow( $db->sql_query( "SELECT `catid`, `parentid`, `numsubcat` FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` WHERE `catid`=" . intval( $catid ) ) );
if( $catid > 0 )
{
	if( $mod == "weight" and $new_vid > 0 )
	{
		$sql = "SELECT `catid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` WHERE `catid`!=" . $catid . " AND `parentid`=" . $parentid . " ORDER BY `weight` ASC";
		$result = $db->sql_query( $sql );
		
		$weight = 0;
		while( $row = $db->sql_fetchrow( $result ) )
		{
			$weight++;
			if( $weight == $new_vid ) $weight++;
			$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` SET `weight`=" . $weight . " WHERE `catid`=" . intval( $row['catid'] );
			$db->sql_query( $sql );
		}
		
		$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` SET `weight`=" . $new_vid . " WHERE `catid`=" . intval( $catid );
		$db->sql_query( $sql );
		
		nv_fix_cat_order();
		$content = "OK_" . $parentid;
	}
	elseif( $mod == "inhome" and ( $new_vid == 0 or $new_vid == 1 ) )
	{
		$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` SET `inhome`=" . $new_vid . " WHERE `catid`=" . intval( $catid );
		$db->sql_query( $sql );
		
		$content = "OK_" . $parentid;
	}
	elseif( $mod == "numlinks" and $new_vid >= 0 and $new_vid <= 10 )
	{
		$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` SET `numlinks`=" . $new_vid . " WHERE `catid`=" . intval( $catid );
		$db->sql_query( $sql );
		$content = "OK_" . $parentid;
	}
	elseif( $mod == "viewcat" and $nv_Request->isset_request( 'new_vid', 'post' ) )
	{
		$viewcat = filter_text_input( 'new_vid', 'post' );
		
		$array_viewcat = ( $numsubcat > 0 ) ? $array_viewcat_full : $array_viewcat_nosub;
		if( ! array_key_exists( $viewcat, $array_viewcat ) )
		{
			$viewcat = "viewcat_page_new";
		}
		
		$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` SET `viewcat`=" . $db->dbescape( $viewcat ) . " WHERE `catid`=" . intval( $catid );
		$db->sql_query( $sql );
		
		$content = "OK_" . $parentid;
	}
	nv_del_moduleCache( $module_name );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $content;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>