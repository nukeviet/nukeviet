<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$topicid = $nv_Request->get_int( 'id', 'get', 0 );
list( $topicid,$catid ) = $db->sql_fetchrow( $db->sql_query( "SELECT `topicid`,`catid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_topics` WHERE `topicid`=" . intval( $topicid ) . "" ) );
$contents = "NO_" . $catid;
if ( $topicid > 0 )
{
    $query = "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_topics` WHERE `topicid`=" . $topicid . "";
    if ( $db->sql_query( $query ) )
    {
        $db->sql_freeresult();
        nv_fix_topic();
        $contents = "OK_" . $catid;
    }
}
nv_del_moduleCache( $module_name );
echo $contents;

?>