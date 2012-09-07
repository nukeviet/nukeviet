<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$topicid = $nv_Request->get_int( 'oid', 'post', 0 );
$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$new_weight = $nv_Request->get_int( 'w', 'post', 0 );
$content = "NO_" . $topicid;
$table = $db_config['prefix'] . "_" . $module_data. "_topics";
list( $topicid, $weight_old ) = $db->sql_fetchrow( $db->sql_query( "SELECT `topicid`, `weight` FROM `" . $table . "` WHERE `catid` = ".$catid." AND `topicid`=" . $db->dbescape($topicid) . "" ) );
if ( !empty($topicid) )
{
	$query = "SELECT `topicid` FROM `" . $table . "` WHERE `catid` = ".$catid." AND `weight` = " . intval($new_weight) . "";
    $result = $db->sql_query( $query );
    list( $topics_swap ) = $db->sql_fetchrow($result);
    $sql = "UPDATE `" . $table . "` SET `weight`=" . $new_weight . " WHERE `topicid`=" . $db->dbescape( $topicid );
    $db->sql_query( $sql );
    $sql = "UPDATE `" . $table . "` SET `weight`=" . $weight_old . " WHERE `topicid`=" . $db->dbescape( $topics_swap );
    $db->sql_query( $sql );
    $content = "OK_" . $topicid;
    nv_del_moduleCache( $module_name );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $content;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>