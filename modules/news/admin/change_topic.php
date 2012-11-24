<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$topicid = $nv_Request->get_int( 'topicid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );

if( empty( $topicid ) ) die( "NO_" . $topicid );
$content = "NO_" . $topicid;

if( $mod == "weight" and $new_vid > 0 )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` WHERE `topicid`=" . $topicid;
	$result = $db->sql_query( $sql );
	$numrows = $db->sql_numrows( $result );
	if( $numrows != 1 ) die( 'NO_' . $topicid );

	$sql = "SELECT `topicid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` WHERE `topicid`!=" . $topicid . " ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	
	$weight = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		++ $weight;
		if( $weight == $new_vid ) ++$weight;
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topics` SET `weight`=" . $weight . " WHERE `topicid`=" . intval( $row['topicid'] );
		$db->sql_query( $sql );
	}
	
	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topics` SET `weight`=" . $new_vid . " WHERE `topicid`=" . intval( $topicid );
	$db->sql_query( $sql );
	
	$content = "OK_" . $topicid;
	nv_del_moduleCache( $module_name );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $content;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>